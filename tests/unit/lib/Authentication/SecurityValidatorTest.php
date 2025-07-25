<?php
/**
 * @fileoverview SecurityValidator Test Suite
 *
 * Unit tests for the OAuth2 SecurityValidator class covering state validation,
 * nonce verification, and security checks.
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Tests\Unit\Authentication;

use PHPUnit\Framework\TestCase;
use SuiteCRM\Authentication\SecurityValidator;

class SecurityValidatorTest extends TestCase
{
    /** @var SecurityValidator */
    private $validator;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SecurityValidator();
        
        // Clear any existing session data
        $_SESSION = [];
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
        parent::tearDown();
    }
    
    /**
     * Test state generation creates valid state token
     */
    public function testGenerateStateCreatesValidToken(): void
    {
        $state = $this->validator->generateState();
        
        $this->assertIsString($state);
        $this->assertEquals(64, strlen($state)); // 32 bytes = 64 hex chars
        $this->assertMatchesRegularExpression('/^[0-9a-f]+$/', $state);
    }
    
    /**
     * Test state validation with valid state
     */
    public function testValidateStateWithValidState(): void
    {
        $state = $this->validator->generateState();
        $_SESSION['oauth2_state'] = $state;
        
        $result = $this->validator->validateState($state);
        
        $this->assertTrue($result);
        $this->assertArrayNotHasKey('oauth2_state', $_SESSION);
    }
    
    /**
     * Test state validation with invalid state
     */
    public function testValidateStateWithInvalidState(): void
    {
        $_SESSION['oauth2_state'] = 'valid_state_token';
        
        $result = $this->validator->validateState('invalid_state_token');
        
        $this->assertFalse($result);
        $this->assertArrayHasKey('oauth2_state', $_SESSION);
    }
    
    /**
     * Test state validation with missing session state
     */
    public function testValidateStateWithMissingSessionState(): void
    {
        $result = $this->validator->validateState('some_state');
        
        $this->assertFalse($result);
    }
    
    /**
     * Test state validation with empty state
     */
    public function testValidateStateWithEmptyState(): void
    {
        $_SESSION['oauth2_state'] = 'valid_state';
        
        $result = $this->validator->validateState('');
        
        $this->assertFalse($result);
    }
    
    /**
     * Test nonce generation creates unique values
     */
    public function testGenerateNonceCreatesUniqueValues(): void
    {
        $nonces = [];
        
        for ($i = 0; $i < 100; $i++) {
            $nonce = $this->validator->generateNonce();
            $this->assertNotContains($nonce, $nonces);
            $nonces[] = $nonce;
        }
        
        $this->assertCount(100, array_unique($nonces));
    }
    
    /**
     * Test redirect URI validation with valid URI
     */
    public function testValidateRedirectUriWithValidUri(): void
    {
        $validUris = [
            'https://example.com/callback',
            'https://app.example.com/oauth/callback',
            'http://localhost:8000/callback',
            'https://example.com:8443/secure/callback'
        ];
        
        foreach ($validUris as $uri) {
            $result = $this->validator->validateRedirectUri($uri, $uri);
            $this->assertTrue($result, "Failed to validate URI: {$uri}");
        }
    }
    
    /**
     * Test redirect URI validation with mismatched URIs
     */
    public function testValidateRedirectUriWithMismatch(): void
    {
        $configured = 'https://example.com/callback';
        $requested = 'https://evil.com/callback';
        
        $result = $this->validator->validateRedirectUri($configured, $requested);
        
        $this->assertFalse($result);
    }
    
    /**
     * Test redirect URI validation with path traversal attempt
     */
    public function testValidateRedirectUriWithPathTraversal(): void
    {
        $configured = 'https://example.com/callback';
        $requested = 'https://example.com/../admin/callback';
        
        $result = $this->validator->validateRedirectUri($configured, $requested);
        
        $this->assertFalse($result);
    }
    
    /**
     * Test provider name validation
     */
    public function testValidateProviderName(): void
    {
        $validProviders = ['google', 'microsoft', 'github', 'custom_provider'];
        $invalidProviders = ['', 'provider!', 'provider@evil', '../google', 'provider%20name'];
        
        foreach ($validProviders as $provider) {
            $result = $this->validator->validateProviderName($provider);
            $this->assertTrue($result, "Failed to validate provider: {$provider}");
        }
        
        foreach ($invalidProviders as $provider) {
            $result = $this->validator->validateProviderName($provider);
            $this->assertFalse($result, "Should not validate provider: {$provider}");
        }
    }
    
    /**
     * Test authorization code validation
     */
    public function testValidateAuthorizationCode(): void
    {
        $validCodes = [
            '4/0AX4XfWh8vH2Xh1234567890abcdef',
            'M.R3_BAY.1234567890',
            'simple_code_123'
        ];
        
        $invalidCodes = ['', null, false];
        
        foreach ($validCodes as $code) {
            $result = $this->validator->validateAuthorizationCode($code);
            $this->assertTrue($result, "Failed to validate code: {$code}");
        }
        
        foreach ($invalidCodes as $code) {
            $result = $this->validator->validateAuthorizationCode($code);
            $this->assertFalse($result);
        }
    }
    
    /**
     * Test PKCE code verifier generation
     */
    public function testGenerateCodeVerifier(): void
    {
        $verifier = $this->validator->generateCodeVerifier();
        
        $this->assertIsString($verifier);
        $this->assertGreaterThanOrEqual(43, strlen($verifier));
        $this->assertLessThanOrEqual(128, strlen($verifier));
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9\-._~]+$/', $verifier);
    }
    
    /**
     * Test PKCE code challenge generation
     */
    public function testGenerateCodeChallenge(): void
    {
        $verifier = $this->validator->generateCodeVerifier();
        $challenge = $this->validator->generateCodeChallenge($verifier);
        
        $this->assertIsString($challenge);
        $this->assertEquals(43, strlen($challenge)); // Base64 URL encoded SHA256
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9\-_]+$/', $challenge);
    }
    
    /**
     * Test timing-safe comparison
     */
    public function testTimingSafeComparison(): void
    {
        $string1 = 'test_string_123';
        $string2 = 'test_string_123';
        $string3 = 'different_string';
        
        $this->assertTrue(hash_equals($string1, $string2));
        $this->assertFalse(hash_equals($string1, $string3));
    }
}
