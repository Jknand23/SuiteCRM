# TokenRepository.php Documentation

## Overview

The `TokenRepository` class provides a clean data access layer for OAuth2 token storage and retrieval operations. It encapsulates all database interactions related to token management, following the repository pattern to separate business logic from data persistence concerns.

## Architecture

### Design Principles
- **Single Responsibility**: Focused solely on database operations for tokens
- **SQL Injection Prevention**: All queries use proper parameter escaping
- **Database Abstraction**: Uses SuiteCRM's DBManager for portability
- **Clean Interface**: Provides intuitive methods for CRUD operations

### Database Table Structure
```sql
oauth2_user_providers
├── id (VARCHAR 36) - Primary key
├── user_id (VARCHAR 36) - SuiteCRM user ID (nullable)
├── provider_name (VARCHAR 50) - OAuth provider identifier
├── provider_user_id (VARCHAR 255) - Provider's user ID
├── access_token (TEXT) - Encrypted access token
├── refresh_token (TEXT) - Encrypted refresh token (nullable)
├── expires_at (DATETIME) - Token expiration timestamp
├── created_at (DATETIME) - Record creation timestamp
└── updated_at (DATETIME) - Last update timestamp
```

## Public Methods

### `__construct()`
Initializes the repository with a database connection from DBManagerFactory.

### `createToken(array $tokenData): string`
Creates a new token record in the database.

**Parameters:**
- `$tokenData`: Associative array with token information
  - `user_id`: SuiteCRM user ID (optional)
  - `provider_name`: OAuth provider name
  - `provider_user_id`: Provider's user identifier
  - `access_token`: Encrypted access token
  - `refresh_token`: Encrypted refresh token (optional)
  - `expires_at`: Expiration timestamp

**Returns:** Generated token record ID

**Throws:** `RuntimeException` when insert fails

### `findByUserAndProvider(string $userId, string $providerName): ?array`
Retrieves a token record by user ID and provider combination.

**Parameters:**
- `$userId`: SuiteCRM user ID
- `$providerName`: OAuth provider name

**Returns:** Token record array or null if not found

### `findById(string $tokenId): ?array`
Retrieves a token record by its ID.

**Parameters:**
- `$tokenId`: Token record ID

**Returns:** Token record array or null if not found

### `updateToken(string $tokenId, array $updateData): bool`
Updates an existing token record.

**Parameters:**
- `$tokenId`: Token record ID to update
- `$updateData`: Associative array of fields to update

**Returns:** True if update was successful

**Throws:** `RuntimeException` when update fails

### `deleteToken(string $tokenId): bool`
Deletes a token record by ID.

**Parameters:**
- `$tokenId`: Token record ID to delete

**Returns:** True if deletion was successful, false otherwise

### `findExpiredTokens(int $limit = 100): array`
Finds expired tokens that don't have refresh tokens.

**Parameters:**
- `$limit`: Maximum number of records to return (default: 100)

**Returns:** Array of expired token records

### `deleteMultipleTokens(array $tokenIds): int`
Deletes multiple token records in a single operation.

**Parameters:**
- `$tokenIds`: Array of token IDs to delete

**Returns:** Number of records deleted

### `findByUser(string $userId): array`
Retrieves all token records for a specific user.

**Parameters:**
- `$userId`: SuiteCRM user ID

**Returns:** Array of token records ordered by creation date (newest first)

### `countByProvider(): array`
Gets token counts grouped by provider.

**Returns:** Associative array mapping provider names to counts

## Private Methods

### `prepareValue($value): string`
Prepares a value for SQL query insertion.

**Parameters:**
- `$value`: Value to prepare (can be null)

**Returns:** Properly quoted value or 'NULL' string

### `generateId(): string`
Generates a new unique ID using SuiteCRM's GUID generation.

**Returns:** New UUID string

### `getCurrentTimestamp(): string`
Gets the current timestamp in database format.

**Returns:** Formatted datetime string (Y-m-d H:i:s)

## Usage Examples

### Creating a Token
```php
$repository = new TokenRepository();

$tokenId = $repository->createToken([
    'user_id' => 'user-123',
    'provider_name' => 'google',
    'provider_user_id' => 'google-user-456',
    'access_token' => 'encrypted-token-data',
    'refresh_token' => 'encrypted-refresh-token',
    'expires_at' => '2024-01-20 15:30:00'
]);
```

### Finding a Token
```php
// By user and provider
$token = $repository->findByUserAndProvider('user-123', 'google');

// By ID
$token = $repository->findById($tokenId);
```

### Updating a Token
```php
$success = $repository->updateToken($tokenId, [
    'access_token' => 'new-encrypted-token',
    'expires_at' => '2024-01-21 15:30:00'
]);
```

### Cleaning Up Expired Tokens
```php
$expiredTokens = $repository->findExpiredTokens();
$tokenIds = array_column($expiredTokens, 'id');
$deletedCount = $repository->deleteMultipleTokens($tokenIds);
```

### Getting Provider Statistics
```php
$providerCounts = $repository->countByProvider();
// Result: ['google' => 42, 'microsoft' => 15, 'github' => 8]
```

## Error Handling

### Exception Handling
- All database errors are caught and re-thrown as `RuntimeException`
- Original error messages are preserved for debugging
- Failed operations log detailed error information

### Common Error Scenarios
1. **Duplicate Records**: Handled by unique constraints
2. **Missing Required Fields**: Validated before insertion
3. **Database Connection Issues**: Caught and reported
4. **Invalid Data Types**: Prevented by parameter preparation

## Performance Considerations

### Query Optimization
1. **Indexed Columns**: user_id, provider_name, expires_at
2. **Limited Result Sets**: Default limits on bulk operations
3. **Prepared Statements**: Efficient query execution

### Batch Operations
- `deleteMultipleTokens()` uses single DELETE with IN clause
- `findExpiredTokens()` limits results to prevent memory issues

## Security Features

### SQL Injection Prevention
- All user input is escaped using `DBManager::quoted()`
- No direct query concatenation of user data
- Parameterized query building

### Data Validation
- Input sanitization before database operations
- Type checking for all parameters
- Null handling for optional fields

## Database Compatibility

The repository is designed to work with:
- MySQL 5.7+
- MariaDB 10.2+
- PostgreSQL 9.6+
- SQL Server 2016+

All queries use standard SQL syntax supported by SuiteCRM's database abstraction layer.

## Testing Recommendations

### Unit Tests
```php
// Test successful token creation
$tokenId = $repository->createToken($validTokenData);
assertNotEmpty($tokenId);

// Test finding by user and provider
$token = $repository->findByUserAndProvider('user-123', 'google');
assertEquals('user-123', $token['user_id']);

// Test deletion
$result = $repository->deleteToken($tokenId);
assertTrue($result);
```

### Integration Tests
1. Test with actual database connections
2. Verify transaction handling
3. Test concurrent operations
4. Validate foreign key constraints

## Maintenance Operations

### Regular Cleanup
```php
// Schedule this in a cron job
$repository = new TokenRepository();
$expiredTokens = $repository->findExpiredTokens(1000);
$tokenIds = array_column($expiredTokens, 'id');

if (!empty($tokenIds)) {
    $deletedCount = $repository->deleteMultipleTokens($tokenIds);
    echo "Cleaned up {$deletedCount} expired tokens\n";
}
```

### Provider Migration
```php
// Update all tokens for a provider
$tokens = $repository->findByProvider('old-provider-name');
foreach ($tokens as $token) {
    $repository->updateToken($token['id'], [
        'provider_name' => 'new-provider-name'
    ]);
}
```

## Future Enhancements

1. **Soft Deletes**: Add deleted_at column for audit trails
2. **Token Metadata**: Additional columns for device info, IP addresses
3. **Partitioning**: Partition by date for large-scale deployments
4. **Read Replicas**: Support for read/write splitting
5. **Caching Layer**: Redis/Memcached integration for frequent queries 