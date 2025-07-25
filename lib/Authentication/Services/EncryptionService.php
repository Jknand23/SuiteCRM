<?php
/**
 * @fileoverview OAuth2 Token Encryption Service
 *
 * Dedicated service for secure encryption and decryption of OAuth2 tokens using
 * AES-256-GCM encryption. Provides a centralized encryption interface with key
 * management, secure nonce generation, and integrity validation through GCM tags.
 *
 * Key Features:
 * - AES-256-GCM encryption for strong security
 * - Secure key derivation from SuiteCRM unique key
 * - Automatic nonce generation for each encryption
 * - Integrity validation through GCM authentication tags
 * - Base64 encoding for database storage compatibility
 *
 * Security Features:
 * - Uses authenticated encryption (AES-GCM) to prevent tampering
 * - Unique nonce for each encryption operation
 * - Key derived from SuiteCRM unique key with salt
 * - Constant-time operations where possible
 *
 * Dependencies:
 * - OpenSSL PHP extension for encryption operations
 * - SuiteCRM configuration for unique key access
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Authentication\Services;

class EncryptionService
{
    /** @var string $encryptionMethod Encryption cipher method */
    private string $encryptionMethod = 'aes-256-gcm';
    
    /** @var string $keySalt Salt for key derivation */
    private string $keySalt = 'oauth2_token_encryption';
    
    /** @var int $nonceLength Length of nonce in bytes */
    private int $nonceLength = 12;
    
    /** @var int $tagLength Length of authentication tag in bytes */
    private int $tagLength = 16;
    
    /**
     * Encrypts a token value using AES-256-GCM
     *
     * Generates a unique nonce for each encryption operation, encrypts the token
     * using AES-256-GCM, and returns a base64-encoded string containing the nonce,
     * authentication tag, and ciphertext for secure storage.
     *
     * @param string $tokenValue Plain text token value to encrypt
     *
     * @return string Base64-encoded encrypted token (nonce + tag + ciphertext)
     *
     * @throws \RuntimeException When encryption fails or OpenSSL is not available
     *
     * @since 1.0.0
     */
    public function encryptToken(string $tokenValue): string
    {
        if (!extension_loaded('openssl')) {
            throw new \RuntimeException('OpenSSL extension is required for token encryption');
        }
        
        $key = $this->getEncryptionKey();
        $nonce = $this->generateNonce();
        
        $ciphertext = openssl_encrypt(
            $tokenValue,
            $this->encryptionMethod,
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag
        );
        
        if ($ciphertext === false) {
            throw new \RuntimeException('Token encryption failed: ' . openssl_error_string());
        }
        
        // Combine nonce + tag + ciphertext and encode for storage
        $combined = $nonce . $tag . $ciphertext;
        return base64_encode($combined);
    }
    
    /**
     * Decrypts a token value from encrypted storage
     *
     * Decodes the base64-encoded token, extracts the nonce and authentication tag,
     * and decrypts the ciphertext using AES-256-GCM with integrity validation.
     *
     * @param string $encryptedToken Base64-encoded encrypted token
     *
     * @return string Decrypted plain text token value
     *
     * @throws \RuntimeException When decryption fails or token format is invalid
     *
     * @since 1.0.0
     */
    public function decryptToken(string $encryptedToken): string
    {
        $data = base64_decode($encryptedToken, true);
        
        if ($data === false) {
            throw new \RuntimeException('Invalid encrypted token format: base64 decode failed');
        }
        
        $minLength = $this->nonceLength + $this->tagLength;
        if (strlen($data) < $minLength) {
            throw new \RuntimeException('Invalid encrypted token format: insufficient data length');
        }
        
        // Extract components
        $nonce = substr($data, 0, $this->nonceLength);
        $tag = substr($data, $this->nonceLength, $this->tagLength);
        $ciphertext = substr($data, $minLength);
        
        if (strlen($ciphertext) === 0) {
            throw new \RuntimeException('Invalid encrypted token format: no ciphertext');
        }
        
        $key = $this->getEncryptionKey();
        
        $plaintext = openssl_decrypt(
            $ciphertext,
            $this->encryptionMethod,
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag
        );
        
        if ($plaintext === false) {
            throw new \RuntimeException('Token decryption failed: ' . openssl_error_string());
        }
        
        return $plaintext;
    }
    
    /**
     * Generates the encryption key from SuiteCRM configuration
     *
     * Derives a 256-bit encryption key from the SuiteCRM unique key using
     * SHA-256 hash with a salt for key separation from other uses.
     *
     * @return string 32-byte encryption key
     *
     * @throws \RuntimeException When unique key is not configured
     *
     * @since 1.0.0
     */
    protected function getEncryptionKey(): string
    {
        global $sugar_config;
        
        if (!isset($sugar_config['unique_key']) || empty($sugar_config['unique_key'])) {
            throw new \RuntimeException('SuiteCRM unique_key not configured');
        }
        
        $uniqueKey = $sugar_config['unique_key'];
        
        // Derive key using SHA-256 for consistent 32-byte output
        return hash('sha256', $uniqueKey . $this->keySalt, true);
    }
    
    /**
     * Generates a cryptographically secure nonce
     *
     * Creates a random nonce of the appropriate length for use with AES-GCM
     * encryption to ensure each encryption operation is unique.
     *
     * @return string Random nonce bytes
     *
     * @throws \RuntimeException When random bytes generation fails
     *
     * @since 1.0.0
     */
    protected function generateNonce(): string
    {
        $nonce = random_bytes($this->nonceLength);
        
        if (strlen($nonce) !== $this->nonceLength) {
            throw new \RuntimeException('Failed to generate nonce of correct length');
        }
        
        return $nonce;
    }
    
    /**
     * Validates encryption service configuration
     *
     * Checks that all required extensions and configuration values are present
     * for the encryption service to function properly.
     *
     * @return bool True if configuration is valid
     *
     * @since 1.0.0
     */
    public function validateConfiguration(): bool
    {
        if (!extension_loaded('openssl')) {
            return false;
        }
        
        if (!in_array($this->encryptionMethod, openssl_get_cipher_methods())) {
            return false;
        }
        
        global $sugar_config;
        if (!isset($sugar_config['unique_key']) || empty($sugar_config['unique_key'])) {
            return false;
        }
        
        return true;
    }
}
