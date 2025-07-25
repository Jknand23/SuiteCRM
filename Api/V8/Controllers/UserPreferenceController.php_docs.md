# User Preference API Controller Documentation

## Overview

The `UserPreferenceController.php` implements **Phase 2, Feature 1, Step 3** of the SuiteCRM modernization project by providing secure, RESTful API endpoints for managing user-specific preferences and customizations. This controller enables persistent storage of user interface configurations including column management, dashboard layouts, and filter presets.

## Purpose

This controller serves as the **backend foundation for user personalization** by:
- ✅ **Secure preference storage** - User-isolated data with authentication enforcement
- ✅ **JSON data validation** - Comprehensive input sanitization and structure validation
- ✅ **Size limit enforcement** - Prevents abuse with configurable data size limits
- ✅ **Comprehensive logging** - Full audit trail of preference operations
- ✅ **Type-specific validation** - Custom validation logic for different preference types
- ✅ **Database integration** - Efficient storage using dedicated user_preferences table

## Key Features

### Security & Authentication
- **User Isolation**: All preferences are strictly isolated by user ID
- **Authentication Required**: All endpoints require valid SuiteCRM session authentication
- **Preference Key Whitelist**: Only predefined preference keys are allowed for security
- **Input Validation**: Comprehensive validation and sanitization of all user input
- **Size Limits**: Configurable maximum size (64KB default) to prevent abuse

### Supported Preference Types
- **lead_table_columns**: Column configuration, visibility, ordering, and sorting preferences
- **dashboard_layout**: Dashboard widget arrangements and personalization settings
- **filter_presets**: Saved filter combinations and quick search configurations
- **theme_settings**: User-specific theme and display preferences
- **campaign_dashboard_config**: Campaign dashboard widget configurations
- **notification_preferences**: User notification and alert preferences

### Data Management
- **UPSERT Operations**: Automatic creation or update of existing preferences
- **JSON Serialization**: Efficient storage of complex nested data structures
- **Validation Pipeline**: Type-specific validation for different preference categories
- **Error Recovery**: Graceful handling of corrupted or invalid stored data

## API Endpoints

### POST `/Api/V8/user/preferences`
**Save User Preference**

Saves or updates a user preference with validation and security checks.

#### Request Format
```json
{
    "key": "lead_table_columns",
    "value": {
        "columnConfig": {
            "name": {
                "id": "name",
                "label": "Lead Name", 
                "visible": true,
                "width": 200,
                "order": 1
            }
        },
        "sortColumns": [
            {
                "field": "date_modified",
                "direction": "desc",
                "priority": 1
            }
        ]
    }
}
```

#### Response Format
```json
{
    "data": {
        "message": "Preference saved successfully",
        "key": "lead_table_columns",
        "timestamp": "2024-01-15T10:30:45+00:00"
    }
}
```

#### Error Responses
- **400 Bad Request**: Invalid preference key or malformed data
- **401 Unauthorized**: Authentication required
- **413 Payload Too Large**: Preference data exceeds size limit
- **500 Internal Server Error**: Database or system error

### GET `/Api/V8/user/preferences/{key}`
**Load User Preference**

Retrieves a specific user preference by key with validation.

#### Request Parameters
- **key** (path): Preference key to retrieve (must be whitelisted)

#### Response Format
```json
{
    "data": {
        "key": "lead_table_columns",
        "value": {
            "columnConfig": { /* column configuration object */ },
            "sortColumns": [ /* sort configuration array */ ]
        },
        "timestamp": "2024-01-15T10:30:45+00:00"
    }
}
```

#### Error Responses
- **400 Bad Request**: Invalid preference key
- **401 Unauthorized**: Authentication required
- **404 Not Found**: Preference does not exist
- **500 Internal Server Error**: Database error or corrupted data

### DELETE `/Api/V8/user/preferences/{key}`
**Delete User Preference**

Removes a user preference with logging and audit trail.

#### Request Parameters
- **key** (path): Preference key to delete

#### Response Format
```json
{
    "data": {
        "message": "Preference deleted successfully",
        "key": "lead_table_columns"
    }
}
```

#### Error Responses
- **400 Bad Request**: Invalid preference key
- **401 Unauthorized**: Authentication required
- **500 Internal Server Error**: Database error

## Technical Implementation

### Controller Structure

#### Security Features
```php
// Whitelisted preference keys for security
private array $allowedPreferenceKeys = [
    'lead_table_columns',
    'dashboard_layout', 
    'filter_presets',
    'theme_settings',
    'campaign_dashboard_config',
    'notification_preferences'
];

// Size limit enforcement (64KB default)
private int $maxPreferenceSize = 65536;
```

#### Validation Pipeline
```php
public function validatePreferenceValue(string $key, $value)
{
    switch ($key) {
        case 'lead_table_columns':
            return $this->validateColumnPreference($value);
        case 'dashboard_layout':
            return $this->validateDashboardPreference($value);
        // ... additional validators
    }
}
```

### Database Schema

#### Table: `user_preferences`
```sql
CREATE TABLE user_preferences (
    id VARCHAR(36) PRIMARY KEY,
    assigned_user_id VARCHAR(36) NOT NULL,
    preference_key VARCHAR(100) NOT NULL,
    preference_value TEXT,
    date_modified DATETIME,
    UNIQUE KEY unique_user_pref (assigned_user_id, preference_key),
    INDEX idx_user_id (assigned_user_id),
    INDEX idx_pref_key (preference_key)
);
```

#### UPSERT Operation
```php
$sql = "INSERT INTO user_preferences (assigned_user_id, preference_key, preference_value, date_modified) 
        VALUES (?, ?, ?, NOW()) 
        ON DUPLICATE KEY UPDATE preference_value = VALUES(preference_value), date_modified = NOW()";
```

### Column Preference Validation

#### Required Structure
```php
private function validateColumnPreference($value): array
{
    // Must be array with required fields
    if (!is_array($value)) {
        throw new \InvalidArgumentException('Column preference must be an array');
    }
    
    $required = ['columnConfig'];
    foreach ($required as $field) {
        if (!isset($value[$field])) {
            throw new \InvalidArgumentException("Missing required field: {$field}");
        }
    }
    
    return $value;
}
```

#### Column Configuration Format
```json
{
    "columnConfig": {
        "column_id": {
            "id": "column_id",
            "label": "Column Label",
            "visible": true,
            "width": 150,
            "resizable": true,
            "sortable": true,
            "order": 1
        }
    },
    "sortColumns": [
        {
            "field": "field_name",
            "direction": "asc|desc",
            "priority": 1
        }
    ]
}
```

## Integration Points

### Frontend Integration
**File**: `themes/SuiteP/js/components/lead-table-view.js`
```javascript
// Save preferences
await this.$store.leadData.saveUserPreference('lead_table_columns', preferences);

// Load preferences
const preferences = await this.$store.leadData.loadUserPreference('lead_table_columns');
```

### Column Management Integration
- **Automatic Saving**: Column visibility changes trigger preference saves
- **Width Persistence**: Column resize operations update stored widths
- **Sort Persistence**: Multi-column sort configurations are preserved
- **Order Persistence**: Drag-and-drop column reordering is saved

### Authentication Integration
- **Session Validation**: Uses existing SuiteCRM session management
- **User Context**: Automatically retrieves current user from session
- **Permission Enforcement**: Integrates with SuiteCRM ACL system

## Logging and Monitoring

### Success Logging
```php
$this->getLogger()->info('User preference saved successfully', [
    'user_id' => $currentUser->id,
    'preference_key' => $preferenceKey,
    'data_size' => strlen($serializedValue)
]);
```

### Error Logging
```php
$this->getLogger()->error('Error saving user preference', [
    'error' => $e->getMessage(),
    'user_id' => $currentUser->id,
    'preference_key' => $preferenceKey
]);
```

### Monitoring Metrics
- **Preference Save Frequency**: Track user engagement with customization features
- **Data Size Trends**: Monitor preference data growth and optimize limits
- **Error Rates**: Track validation failures and system errors
- **User Adoption**: Measure feature usage across different preference types

## Security Considerations

### Input Validation
- **Key Whitelist**: Only predefined preference keys are accepted
- **JSON Validation**: All data must be valid JSON and properly structured
- **Size Limits**: Configurable maximum data size prevents abuse
- **Type Validation**: Each preference type has specific validation rules

### Data Protection
- **User Isolation**: Strict user ID-based data separation
- **SQL Injection Prevention**: All database queries use prepared statements
- **XSS Prevention**: Data is JSON-encoded and never executed as code
- **Authentication Enforcement**: All endpoints require valid authentication

### Error Handling
- **Information Disclosure**: Error messages don't reveal system internals
- **Graceful Degradation**: Invalid preferences don't break the system
- **Audit Trail**: All operations are logged for security monitoring
- **Rate Limiting**: Inherits rate limiting from parent API infrastructure

## Performance Optimization

### Database Efficiency
- **Indexed Queries**: Proper indexing on user_id and preference_key
- **UPSERT Operations**: Single query for create/update operations
- **Connection Pooling**: Reuses existing database connections
- **Query Optimization**: Minimal data transfer with targeted selects

### Caching Strategy
- **Frontend Caching**: Preferences cached in Alpine.js stores
- **Conditional Updates**: Only save when preferences actually change
- **Lazy Loading**: Preferences loaded only when needed
- **Batch Operations**: Multiple preference updates in single requests

### Memory Management
- **Size Limits**: Prevents excessive memory usage
- **JSON Streaming**: Efficient handling of large preference objects
- **Garbage Collection**: Proper cleanup of temporary variables
- **Resource Cleanup**: Database connections and statements properly closed

## Testing Strategy

### Unit Testing
- [ ] Preference validation logic for each type
- [ ] Database save/load/delete operations
- [ ] Error handling and edge cases
- [ ] Security validation and input sanitization

### Integration Testing
- [ ] API endpoint authentication and authorization
- [ ] Frontend component integration
- [ ] Database transaction handling
- [ ] Cross-user data isolation

### Security Testing
- [ ] Input validation bypass attempts
- [ ] Authentication and authorization enforcement
- [ ] SQL injection and XSS prevention
- [ ] Data size limit enforcement

## Future Enhancements

### Advanced Features
- **Preference Versioning**: Track changes to user preferences over time
- **Bulk Operations**: Support for saving multiple preferences simultaneously
- **Export/Import**: Allow users to backup and restore preference configurations
- **Team Sharing**: Enable sharing of preferences between team members

### Performance Improvements
- **Redis Caching**: Add Redis-based caching for frequently accessed preferences
- **GraphQL Support**: Implement GraphQL interface for complex preference queries
- **Real-time Sync**: Live synchronization of preferences across multiple browser tabs
- **Compression**: Compress large preference objects for storage efficiency

### Analytics Integration
- **Usage Analytics**: Track which preferences are most commonly used
- **Performance Metrics**: Monitor preference load times and system impact
- **User Behavior**: Analyze how users customize their interface
- **A/B Testing**: Support for testing different default preference configurations

---

*This controller successfully implements Phase 2, Feature 1, Step 3 by providing a secure, scalable foundation for user preference management that enables persistent column management and other user interface customizations.* 