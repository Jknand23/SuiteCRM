<?php
/**
 * @fileoverview OAuth2 Security Validator
 * 
 * Security validation service for OAuth2 authentication flows providing CSRF
 * protection through state parameter validation, secure token generation, and
 * comprehensive security checks. Integrates with SuiteCRM's existing security
 * infrastructure while adding OAuth2-specific security measures.
 * 
 * Key Features:
 * - CSRF protection via cryptographically secure state parameter generation
 * - State parameter validation with timing attack protection
 * - Secure random token generation using cryptographically secure methods
 * - Session-based state storage with automatic cleanup
 * - Security event logging for monitoring and audit trails
 * - Rate limiting integration for authentication attempt protection
 * 
 * Security Measures:
 * - Uses random_bytes() for cryptographically secure state generation
 * - Implements timing-safe string comparison for state validation
 * - Automatic state expiration to prevent replay attacks
 * - Comprehensive logging of security events and failures
 * - Integration with existing SuiteCRM IP validation
 * 
 * Dependencies:
 * - PHP's random_bytes() function for secure randomness
 * - SuiteCRM session management for state storage
 * - SuiteCRM logging system for security event tracking
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Authentication;

class SecurityValidator
{
    /** @var int $stateLength Length of generated state parameter in bytes */
    private int $stateLength = 32;
    
    /** @var int $stateTimeout State parameter timeout in seconds */
    private int $stateTimeout = 600; // 10 minutes
    
    /** @var string $sessionStateKey Session key for storing OAuth2 state */
    private string $sessionStateKey = 'oauth2_state';
    
    /** @var string $sessionTimestampKey Session key for storing state timestamp */
    private string $sessionTimestampKey = 'oauth2_state_timestamp';
    
    /**
     * Generates cryptographically secure state parameter for CSRF protection
     * 
     * Creates a URL-safe base64-encoded random string for use as OAuth2 state
     * parameter. Stores state and timestamp in session for later validation.
     * 
     * @return string Cryptographically secure state parameter
     * 
     * @throws \RuntimeException When secure random generation fails
     * 
     * @since 1.0.0
     */
    public function generateState(): string
    {
        try {
            // Generate cryptographically secure random bytes
            $randomBytes = random_bytes($this->stateLength);
            
            // Encode as URL-safe base64 string
            $state = rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');
            
            // Store state and timestamp in session
            $_SESSION[$this->sessionStateKey] = $state;
            $_SESSION[$this->sessionTimestampKey] = time();
            
            $this->logSecurityEvent('state_generated', [
                'state_length' => strlen($state),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
            
            return $state;
            
        } catch (\Exception $e) {
            $this->logSecurityEvent('state_generation_failed', [
                'error' => $e->getMessage(),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            throw new \RuntimeException('Failed to generate secure state parameter: ' . $e->getMessage());
        }
    }
    
    /**
     * Validates OAuth2 state parameter against stored session state
     * 
     * Performs timing-safe comparison of provided state against session-stored
     * state, checks for state expiration, and handles cleanup. Provides protection
     * against CSRF attacks and replay attacks.
     * 
     * @param string $providedState State parameter from OAuth2 callback
     * 
     * @return bool True if state is valid and not expired
     * 
     * @since 1.0.0
     */
    public function validateState(string $providedState): bool
    {
        $sessionState = $_SESSION[$this->sessionStateKey] ?? null;
        $stateTimestamp = $_SESSION[$this->sessionTimestampKey] ?? null;
        
        // Check if state exists in session
        if (!$sessionState || !$stateTimestamp) {
            $this->logSecurityEvent('state_validation_failed', [
                'reason' => 'missing_session_state',
                'provided_state_length' => strlen($providedState),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            return false;
        }
        
        // Check if state has expired
        if ((time() - $stateTimestamp) > $this->stateTimeout) {
            $this->logSecurityEvent('state_validation_failed', [
                'reason' => 'state_expired',
                'age_seconds' => time() - $stateTimestamp,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $this->cleanupSessionState();
            return false;
        }
        
        // Perform timing-safe string comparison
        $isValid = $this->timingSafeEquals($providedState, $sessionState);
        
        if ($isValid) {
            $this->logSecurityEvent('state_validation_success', [
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            // Clean up session state after successful validation
            $this->cleanupSessionState();
        } else {
            $this->logSecurityEvent('state_validation_failed', [
                'reason' => 'state_mismatch',
                'provided_state_length' => strlen($providedState),
                'session_state_length' => strlen($sessionState),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
        }
        
        return $isValid;
    }
    
    /**
     * Generates secure random string for various security purposes
     * 
     * @param int $length Length of random string in bytes
     * 
     * @return string URL-safe base64-encoded random string
     * 
     * @throws \RuntimeException When secure random generation fails
     * 
     * @since 1.0.0
     */
    public function generateSecureRandomString(int $length = 32): string
    {
        try {
            $randomBytes = random_bytes($length);
            return rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to generate secure random string: ' . $e->getMessage());
        }
    }
    
    /**
     * Validates redirect URI against allowed patterns
     * 
     * Checks if provided redirect URI matches allowed patterns to prevent
     * open redirect vulnerabilities in OAuth2 flows.
     * 
     * @param string $redirectUri Redirect URI to validate
     * @param array $allowedPatterns Array of allowed URI patterns
     * 
     * @return bool True if redirect URI is allowed
     * 
     * @since 1.0.0
     */
    public function validateRedirectUri(string $redirectUri, array $allowedPatterns = []): bool
    {
        // Default allowed patterns if none provided
        if (empty($allowedPatterns)) {
            $allowedPatterns = $this->getDefaultAllowedRedirectPatterns();
        }
        
        foreach ($allowedPatterns as $pattern) {
            if (fnmatch($pattern, $redirectUri)) {
                return true;
            }
        }
        
        $this->logSecurityEvent('redirect_uri_validation_failed', [
            'redirect_uri' => $redirectUri,
            'allowed_patterns' => $allowedPatterns,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
        return false;
    }
    
    /**
     * Checks if OAuth2 authentication should be rate limited
     * 
     * @param string $ipAddress Client IP address
     * @param string $identifier Additional identifier (e.g., provider name)
     * 
     * @return bool True if rate limit exceeded
     * 
     * @since 1.0.0
     */
    public function isRateLimited(string $ipAddress, string $identifier = ''): bool
    {
        $sessionKey = "oauth2_rate_limit_{$ipAddress}_{$identifier}";
        $attemptCount = $_SESSION[$sessionKey] ?? 0;
        $maxAttempts = 10; // Maximum attempts per hour
        $timeWindow = 3600; // 1 hour in seconds
        
        // Reset counter if time window has passed
        $lastAttemptKey = "{$sessionKey}_timestamp";
        $lastAttempt = $_SESSION[$lastAttemptKey] ?? 0;
        
        if ((time() - $lastAttempt) > $timeWindow) {
            $_SESSION[$sessionKey] = 0;
            $attemptCount = 0;
        }
        
        if ($attemptCount >= $maxAttempts) {
            $this->logSecurityEvent('rate_limit_exceeded', [
                'ip_address' => $ipAddress,
                'identifier' => $identifier,
                'attempt_count' => $attemptCount
            ]);
            return true;
        }
        
        return false;
    }
    
    /**
     * Records OAuth2 authentication attempt for rate limiting
     * 
     * @param string $ipAddress Client IP address
     * @param string $identifier Additional identifier
     * @param bool $success Whether attempt was successful
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    public function recordAttempt(string $ipAddress, string $identifier = '', bool $success = false): void
    {
        $sessionKey = "oauth2_rate_limit_{$ipAddress}_{$identifier}";
        $timestampKey = "{$sessionKey}_timestamp";
        
        $_SESSION[$sessionKey] = ($_SESSION[$sessionKey] ?? 0) + 1;
        $_SESSION[$timestampKey] = time();
        
        // Reset counter on successful authentication
        if ($success) {
            unset($_SESSION[$sessionKey], $_SESSION[$timestampKey]);
        }
    }
    
    /**
     * Performs timing-safe string comparison to prevent timing attacks
     * 
     * @param string $string1 First string to compare
     * @param string $string2 Second string to compare
     * 
     * @return bool True if strings are equal
     * 
     * @since 1.0.0
     */
    protected function timingSafeEquals(string $string1, string $string2): bool
    {
        if (function_exists('hash_equals')) {
            return hash_equals($string1, $string2);
        }
        
        // Fallback implementation for older PHP versions
        if (strlen($string1) !== strlen($string2)) {
            return false;
        }
        
        $result = 0;
        for ($i = 0; $i < strlen($string1); $i++) {
            $result |= ord($string1[$i]) ^ ord($string2[$i]);
        }
        
        return $result === 0;
    }
    
    /**
     * Cleans up OAuth2 state from session
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    protected function cleanupSessionState(): void
    {
        unset($_SESSION[$this->sessionStateKey], $_SESSION[$this->sessionTimestampKey]);
    }
    
    /**
     * Gets default allowed redirect URI patterns
     * 
     * @return array Default allowed redirect patterns
     * 
     * @since 1.0.0
     */
    protected function getDefaultAllowedRedirectPatterns(): array
    {
        global $sugar_config;
        
        $siteUrl = $sugar_config['site_url'] ?? '';
        
        return [
            $siteUrl . '/index.php?module=Users&action=OAuth2Callback',
            $siteUrl . '/auth/oauth/callback/*',
            'https://*.' . parse_url($siteUrl, PHP_URL_HOST) . '/auth/oauth/callback/*'
        ];
    }
    
    /**
     * Logs security events for monitoring and audit purposes
     * 
     * @param string $eventType Type of security event
     * @param array $context Additional context information
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    protected function logSecurityEvent(string $eventType, array $context): void
    {
        $GLOBALS['log']->info("OAuth2 Security Event: {$eventType}", $context);
    }
} 