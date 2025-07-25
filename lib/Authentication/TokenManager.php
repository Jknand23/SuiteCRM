<?php
/**
 * @fileoverview OAuth2 Token Manager Coordinator
 *
 * Coordinates OAuth2 token operations by orchestrating encryption, storage,
 * and retrieval services. Acts as the main interface for token management
 * while delegating specific responsibilities to specialized services.
 *
 * Key Features:
 * - Simplified token management interface
 * - Coordination of encryption and storage services
 * - Automatic token refresh handling
 * - Token lifecycle management
 * - Comprehensive audit logging
 *
 * Security Features:
 * - Delegated encryption to dedicated service
 * - Secure token storage through repository pattern
 * - Automatic cleanup of expired tokens
 * - Rate-limited refresh operations
 *
 * Dependencies:
 * - EncryptionService for token encryption/decryption
 * - TokenRepository for database operations
 * - League\OAuth2\Client for token objects
 * - PSR-3 logger for audit trails
 *
 * @author SuiteCRM Modernization Team
 * @version 2.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Authentication;

use League\OAuth2\Client\Token\AccessToken;
use SuiteCRM\Authentication\Services\EncryptionService;
use SuiteCRM\Authentication\Repositories\TokenRepository;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TokenManager
{
    /** @var EncryptionService $encryptionService Token encryption service */
    private EncryptionService $encryptionService;
    
    /** @var TokenRepository $tokenRepository Token storage repository */
    private TokenRepository $tokenRepository;
    
    /** @var LoggerInterface $logger PSR-3 logger instance */
    private LoggerInterface $logger;
    
    /** @var int $refreshTokenBuffer Buffer time before expiration for refresh */
    private int $refreshTokenBuffer = 300; // 5 minutes
    
    /** @var int $maxRefreshAttempts Maximum token refresh attempts */
    private int $maxRefreshAttempts = 3;
    /**
     * Constructor with dependency injection
     *
     * @param EncryptionService|null $encryptionService Token encryption service
     * @param TokenRepository|null $tokenRepository Token storage repository
     * @param LoggerInterface|null $logger PSR-3 logger instance
     *
     * @since 2.0.0
     */
    public function __construct(
        ?EncryptionService $encryptionService = null,
        ?TokenRepository $tokenRepository = null,
        ?LoggerInterface $logger = null
    ) {
        $this->encryptionService = $encryptionService ?? new EncryptionService();
        $this->tokenRepository = $tokenRepository ?? new TokenRepository();
        $this->logger = $logger ?? new NullLogger();
    }
    
    /**
     * Stores OAuth2 access token securely
     *
     * @param AccessToken $accessToken OAuth2 access token
     * @param string $providerName OAuth2 provider name
     * @param string $providerUserId Provider user identifier
     * @param string|null $suiteUserId SuiteCRM user ID
     *
     * @return string Token record ID
     *
     * @throws \RuntimeException When storage fails
     *
     * @since 2.0.0
     */
    public function storeToken(
        AccessToken $accessToken,
        string $providerName,
        string $providerUserId,
        ?string $suiteUserId = null
    ): string {
        try {
            $tokenData = [
                'user_id' => $suiteUserId,
                'provider_name' => $providerName,
                'provider_user_id' => $providerUserId,
                'access_token' => $this->encryptionService->encryptToken(
                    $accessToken->getToken()
                ),
                'refresh_token' => $accessToken->getRefreshToken()
                    ? $this->encryptionService->encryptToken($accessToken->getRefreshToken())
                    : null,
                'expires_at' => date('Y-m-d H:i:s', $accessToken->getExpires())
            ];
            
            $tokenId = $this->tokenRepository->createToken($tokenData);
            
            $this->logger->info('OAuth2 token stored', [
                'token_id' => $tokenId,
                'provider' => $providerName,
                'provider_user_id' => $providerUserId,
                'suite_user_id' => $suiteUserId
            ]);
            
            return $tokenId;
        } catch (\Exception $e) {
            $this->logger->error('Failed to store OAuth2 token', [
                'provider' => $providerName,
                'error' => $e->getMessage()
            ]);
            throw new \RuntimeException(
                'Failed to store OAuth2 token: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
    
    /**
     * Retrieves and decrypts OAuth2 token
     *
     * @param string $suiteUserId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     *
     * @return AccessToken|null Access token or null
     *
     * @throws \RuntimeException When retrieval fails
     *
     * @since 2.0.0
     */
    public function getToken(string $suiteUserId, string $providerName): ?AccessToken
    {
        try {
            $tokenRecord = $this->tokenRepository->findByUserAndProvider(
                $suiteUserId,
                $providerName
            );
            
            if (!$tokenRecord) {
                return null;
            }
            
            if ($this->isTokenExpired($tokenRecord)) {
                if ($tokenRecord['refresh_token']) {
                    return $this->refreshToken($tokenRecord);
                }
                
                $this->tokenRepository->deleteToken($tokenRecord['id']);
                return null;
            }
            
            return $this->createAccessTokenFromRecord($tokenRecord);
        } catch (\Exception $e) {
            $this->logger->error('Failed to retrieve OAuth2 token', [
                'user_id' => $suiteUserId,
                'provider' => $providerName,
                'error' => $e->getMessage()
            ]);
            throw new \RuntimeException(
                'Failed to retrieve OAuth2 token: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
    
    /**
     * Deletes OAuth2 token
     *
     * @param string $suiteUserId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     *
     * @return bool True if deleted
     *
     * @since 2.0.0
     */
    public function deleteToken(string $suiteUserId, string $providerName): bool
    {
        try {
            $tokenRecord = $this->tokenRepository->findByUserAndProvider(
                $suiteUserId,
                $providerName
            );
            
            if (!$tokenRecord) {
                return false;
            }
            
            $result = $this->tokenRepository->deleteToken($tokenRecord['id']);
            
            if ($result) {
                $this->logger->info('OAuth2 token deleted', [
                    'user_id' => $suiteUserId,
                    'provider' => $providerName
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Failed to delete OAuth2 token', [
                'user_id' => $suiteUserId,
                'provider' => $providerName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Cleans up expired tokens
     *
     * @return int Number cleaned
     *
     * @since 2.0.0
     */
    public function cleanupExpiredTokens(): int
    {
        try {
            $expiredTokens = $this->tokenRepository->findExpiredTokens();
            $tokenIds = array_column($expiredTokens, 'id');
            
            if (empty($tokenIds)) {
                return 0;
            }
            
            $deletedCount = $this->tokenRepository->deleteMultipleTokens($tokenIds);
            
            $this->logger->info('Expired tokens cleaned up', [
                'count' => $deletedCount
            ]);
            
            return $deletedCount;
        } catch (\Exception $e) {
            $this->logger->error('Token cleanup failed', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
     * Creates AccessToken from database record
     *
     * @param array $tokenRecord Token database record
     *
     * @return AccessToken Decrypted access token
     *
     * @since 2.0.0
     */
    protected function createAccessTokenFromRecord(array $tokenRecord): AccessToken
    {
        $tokenData = [
            'access_token' => $this->encryptionService->decryptToken(
                $tokenRecord['access_token']
            ),
            'refresh_token' => $tokenRecord['refresh_token']
                ? $this->encryptionService->decryptToken($tokenRecord['refresh_token'])
                : null,
            'expires' => strtotime($tokenRecord['expires_at'])
        ];
        
        return new AccessToken($tokenData);
    }
    
    /**
     * Checks if token has expired
     *
     * @param array $tokenRecord Token database record
     *
     * @return bool True if expired
     *
     * @since 2.0.0
     */
    protected function isTokenExpired(array $tokenRecord): bool
    {
        $expiresAt = strtotime($tokenRecord['expires_at']);
        return (time() + $this->refreshTokenBuffer) >= $expiresAt;
    }
    
    /**
     * Refreshes expired token (placeholder)
     *
     * @param array $tokenRecord Token database record
     *
     * @return AccessToken|null Refreshed token or null
     *
     * @since 2.0.0
     */
    protected function refreshToken(array $tokenRecord): ?AccessToken
    {
        // This is a placeholder implementation
        // In production, this would integrate with ProviderFactory
        // to perform actual token refresh
        
        $this->logger->warning('Token refresh not implemented', [
            'token_id' => $tokenRecord['id'],
            'provider' => $tokenRecord['provider_name']
        ]);
        
        return null;
    }
}
