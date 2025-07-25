# EncryptionService.php Documentation

## Overview

The `EncryptionService` class provides secure encryption and decryption services for OAuth2 tokens using AES-256-GCM encryption. This dedicated service ensures all token encryption operations follow security best practices with proper key management, nonce generation, and integrity validation.

## Security Architecture

### Encryption Method
- **Algorithm**: AES-256-GCM (Galois/Counter Mode)
- **Key Size**: 256 bits (32 bytes)
- **Nonce Size**: 96 bits (12 bytes)
- **Tag Size**: 128 bits (16 bytes)

### Key Features
- Authenticated encryption preventing tampering
- Unique nonce for each encryption operation
- Secure key derivation from SuiteCRM configuration
- Base64 encoding for database storage compatibility

## Public Methods

### `encryptToken(string $tokenValue): string`
Encrypts a plain text token value using AES-256-GCM encryption.

**Parameters:**
- `$tokenValue`: Plain text token value to encrypt

**Returns:** Base64-encoded string containing nonce + tag + ciphertext

**Throws:** 
- `RuntimeException` when OpenSSL is not available
- `RuntimeException` when encryption operation fails

**Process:**
1. Generates a cryptographically secure 12-byte nonce
2. Encrypts the token using AES-256-GCM
3. Combines nonce + authentication tag + ciphertext
4. Base64-encodes the combined data for storage

### `decryptToken(string $encryptedToken): string`
Decrypts an encrypted token value with integrity validation.

**Parameters:**
- `$encryptedToken`: Base64-encoded encrypted token

**Returns:** Decrypted plain text token value

**Throws:**
- `RuntimeException` when base64 decode fails
- `RuntimeException` when data format is invalid
- `RuntimeException` when decryption fails (including authentication failure)

**Process:**
1. Base64-decodes the encrypted data
2. Extracts nonce, authentication tag, and ciphertext
3. Decrypts and validates using AES-256-GCM
4. Returns the plain text token

### `validateConfiguration(): bool`
Validates that the encryption service is properly configured and can operate.

**Returns:** True if all requirements are met, false otherwise

**Checks:**
1. OpenSSL extension is loaded
2. AES-256-GCM cipher is available
3. SuiteCRM unique_key is configured

## Protected Methods

### `getEncryptionKey(): string`
Generates the encryption key from SuiteCRM's unique key configuration.

**Returns:** 32-byte encryption key

**Throws:** `RuntimeException` when unique_key is not configured

**Key Derivation:**
- Uses SHA-256 hash function
- Combines unique_key with salt: `oauth2_token_encryption`
- Produces consistent 256-bit key for AES-256

### `generateNonce(): string`
Generates a cryptographically secure nonce for encryption.

**Returns:** 12-byte random nonce

**Throws:** `RuntimeException` when nonce generation fails

## Data Format

### Encrypted Token Structure
```
[Base64 Encoded]
├── Nonce (12 bytes)
├── Authentication Tag (16 bytes)
└── Ciphertext (variable length)
```

### Storage Format
The encrypted token is stored as a single Base64-encoded string that can be safely stored in databases and transmitted in JSON.

## Usage Examples

### Basic Encryption
```php
$encryptionService = new EncryptionService();

// Encrypt a token
$plainToken = 'ya29.a0AfH6SMBxU7Xz...';
$encryptedToken = $encryptionService->encryptToken($plainToken);
// Result: "YmFzZTY0ZW5jb2RlZGRhdGE..."
```

### Basic Decryption
```php
// Decrypt a token
$encryptedToken = 'YmFzZTY0ZW5jb2RlZGRhdGE...';
$plainToken = $encryptionService->decryptToken($encryptedToken);
// Result: "ya29.a0AfH6SMBxU7Xz..."
```

### Configuration Validation
```php
if (!$encryptionService->validateConfiguration()) {
    throw new \Exception('Encryption service not properly configured');
}
```

## Security Considerations

### Key Management
1. **Key Derivation**: Keys are derived from SuiteCRM's unique_key using SHA-256
2. **Key Isolation**: Different salt ensures OAuth2 keys are separate from other uses
3. **Key Storage**: Never store encryption keys in code or logs

### Nonce Security
1. **Uniqueness**: Each encryption uses a unique random nonce
2. **Size**: 96-bit nonce provides sufficient uniqueness for GCM mode
3. **Generation**: Uses PHP's `random_bytes()` for cryptographic security

### Authentication
1. **GCM Mode**: Provides built-in authentication preventing tampering
2. **Tag Validation**: Decryption fails if data has been modified
3. **Timing Attacks**: GCM validation is constant-time

## Error Handling

### Exception Types
All errors throw `RuntimeException` with descriptive messages:
- Missing OpenSSL extension
- Encryption/decryption failures
- Invalid data format
- Configuration issues

### Error Messages
- `"OpenSSL extension is required for token encryption"`
- `"Token encryption failed: [OpenSSL error]"`
- `"Invalid encrypted token format: [specific issue]"`
- `"SuiteCRM unique_key not configured"`

## Performance Considerations

1. **Encryption Overhead**: AES-256-GCM is hardware-accelerated on modern CPUs
2. **Memory Usage**: Minimal - only token data is held in memory
3. **Base64 Encoding**: Increases storage size by ~33%

## Testing Recommendations

### Unit Tests
1. Test encryption/decryption round trip
2. Test invalid token handling
3. Test configuration validation
4. Test error conditions

### Integration Tests
1. Test with actual SuiteCRM configuration
2. Test with various token sizes
3. Test concurrent encryption operations

### Security Tests
1. Verify unique nonces
2. Test tampering detection
3. Verify key derivation consistency

## Compatibility

- **PHP Version**: 7.4+ (uses typed properties)
- **Extensions**: Requires OpenSSL
- **SuiteCRM**: Requires configured unique_key
- **Database**: Base64 encoding ensures compatibility

## Future Enhancements

1. **Key Rotation**: Support for periodic key rotation
2. **Algorithm Agility**: Support for alternative encryption algorithms
3. **HSM Integration**: Hardware security module support
4. **Audit Logging**: Enhanced logging of encryption operations 