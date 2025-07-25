# TokenManager.php Documentation

## Overview

The `TokenManager` class serves as the main coordinator for OAuth2 token operations in SuiteCRM. Following a refactored architecture, it orchestrates token encryption, storage, and retrieval by delegating specific responsibilities to specialized services while maintaining a clean, simplified interface.

## Architecture

The TokenManager follows the Single Responsibility Principle by delegating operations to:
- **EncryptionService**: Handles all token encryption/decryption operations
- **TokenRepository**: Manages all database operations for token storage
- **PSR-3 Logger**: Provides standardized logging for audit trails

## Key Features

### Token Lifecycle Management
- Secure storage of OAuth2 access and refresh tokens
- Automatic token expiration detection
- Token refresh handling (placeholder for future implementation)
- Cleanup of expired tokens

### Security Features
- AES-256-GCM encryption for tokens at rest
- Secure token storage through repository pattern
- Comprehensive audit logging
- Rate-limited refresh operations

## Public Methods

### `__construct(?EncryptionService $encryptionService, ?TokenRepository $tokenRepository, ?LoggerInterface $logger)`
Initializes the TokenManager with optional dependency injection. If dependencies are not provided, creates default instances.

### `storeToken(AccessToken $accessToken, string $providerName, string $providerUserId, ?string $suiteUserId): string`
Stores an OAuth2 access token securely in the database.

**Parameters:**
- `$accessToken`: League OAuth2 AccessToken object
- `$providerName`: OAuth2 provider identifier (e.g., 'google')
- `$providerUserId`: User ID from the OAuth2 provider
- `$suiteUserId`: Optional SuiteCRM user ID for linking

**Returns:** Token record ID for future reference

**Throws:** `RuntimeException` when storage fails

### `getToken(string $suiteUserId, string $providerName): ?AccessToken`
Retrieves and decrypts an OAuth2 token for a specific user and provider.

**Parameters:**
- `$suiteUserId`: SuiteCRM user ID
- `$providerName`: OAuth2 provider name

**Returns:** Decrypted AccessToken object or null if not found/expired

**Throws:** `RuntimeException` when retrieval fails

### `deleteToken(string $suiteUserId, string $providerName): bool`
Deletes an OAuth2 token for a specific user and provider.

**Parameters:**
- `$suiteUserId`: SuiteCRM user ID
- `$providerName`: OAuth2 provider name

**Returns:** True if token was deleted, false otherwise

### `cleanupExpiredTokens(): int`
Removes expired tokens from the database that don't have refresh tokens.

**Returns:** Number of tokens cleaned up

## Protected Methods

### `createAccessTokenFromRecord(array $tokenRecord): AccessToken`
Creates a League OAuth2 AccessToken object from a database record by decrypting the stored tokens.

### `isTokenExpired(array $tokenRecord): bool`
Checks if a token has expired, considering a 5-minute buffer time before actual expiration.

### `refreshToken(array $tokenRecord): ?AccessToken`
Placeholder method for token refresh functionality. Currently logs a warning and returns null.

## Usage Examples

### Storing a Token
```php
$tokenManager = new TokenManager();
$accessToken = new AccessToken([
    'access_token' => 'ya29.a0AfH6SMBx...',
    'refresh_token' => '1//0gEVPg...',
    'expires' => time() + 3600
]);

$tokenId = $tokenManager->storeToken(
    $accessToken,
    'google',
    '115072410867539478552',
    'suite-user-123'
);
```

### Retrieving a Token
```php
$token = $tokenManager->getToken('suite-user-123', 'google');
if ($token) {
    $accessTokenValue = $token->getToken();
    // Use token for API calls
}
```

### Cleaning Up Expired Tokens
```php
$cleanedCount = $tokenManager->cleanupExpiredTokens();
echo "Cleaned up {$cleanedCount} expired tokens";
```

## Error Handling

The TokenManager provides comprehensive error handling:
- All exceptions are caught and re-thrown as `RuntimeException` with context
- Original exceptions are preserved in the exception chain
- All operations are logged with appropriate severity levels

## Security Considerations

1. **Token Encryption**: All tokens are encrypted using AES-256-GCM before storage
2. **Key Derivation**: Encryption keys are derived from SuiteCRM's unique key
3. **Audit Logging**: All token operations are logged for security monitoring
4. **Automatic Cleanup**: Expired tokens are automatically removed to prevent accumulation

## Future Enhancements

1. **Token Refresh Implementation**: The `refreshToken` method is currently a placeholder and needs integration with ProviderFactory for actual OAuth2 token refresh
2. **Rate Limiting**: Implement rate limiting for token refresh attempts
3. **Token Rotation**: Implement automatic token rotation for enhanced security
4. **Multi-Provider Support**: Enhanced support for multiple OAuth2 providers per user

## Dependencies

- `League\OAuth2\Client\Token\AccessToken`: OAuth2 token representation
- `SuiteCRM\Authentication\Services\EncryptionService`: Token encryption/decryption
- `SuiteCRM\Authentication\Repositories\TokenRepository`: Database operations
- `Psr\Log\LoggerInterface`: PSR-3 compliant logging

## Configuration

The TokenManager uses the following configuration:
- **Refresh Buffer**: 300 seconds (5 minutes) before token expiration
- **Max Refresh Attempts**: 3 attempts with exponential backoff
- **Database Table**: `oauth2_user_providers`

## Testing Considerations

When testing TokenManager:
1. Mock the EncryptionService and TokenRepository dependencies
2. Test token expiration logic with various timestamps
3. Verify proper exception handling and logging
4. Test cleanup operations with multiple expired tokens
5. Ensure proper null handling for optional parameters 