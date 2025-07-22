# AOD_IndexEvent.php Documentation

## @fileoverview
**AOD_IndexEvent - Index event tracking for Advanced OpenDiscovery search system**
- **Package**: modules/AOD_IndexEvent
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Tracks individual indexing events, their success/failure status, and error information for search index management
- **Deprecation**: Deprecated since v7.12.0

## Class Overview
The `AOD_IndexEvent` class extends `AOD_IndexEvent_sugar` and serves as a tracking system for search indexing operations. It records when records are indexed, whether the operation succeeded, and captures error information for failed indexing attempts.

## Internal API Calls

### Framework Integration
- **Parent Class**: `AOD_IndexEvent_sugar` (auto-generated base class)
- **Base Framework**: Inherits from `Basic` SugarBean through parent class
- **Module Pattern**: Follows SuiteCRM's module builder customization pattern

### Constructor
#### `__construct()`
- **Purpose**: Initializes AOD_IndexEvent instance
- **Inheritance**: Calls parent constructor to setup base SugarBean functionality
- **Framework Integration**: Ensures proper SuiteCRM bean initialization
- **Customization Point**: Where developers can add custom initialization logic

## Database Operations

### Event Tracking
The class provides a framework for tracking indexing operations through its database structure:

#### Record Identification
- **`record_id`**: ID of the record that was indexed
- **`record_module`**: Module name of the indexed record
- **Purpose**: Links indexing events to specific SuiteCRM records

#### Operation Status
- **`success`**: Boolean flag indicating if indexing succeeded
- **`error`**: Error message for failed indexing operations
- **Usage**: Enables troubleshooting and monitoring of indexing health

#### Standard Audit Fields
- **`date_entered`**: When indexing event was recorded
- **`date_modified`**: Last update to event record
- **`assigned_user_id`**: User responsible for the indexing operation

## Integration with AOD System

### Event Creation Workflow
1. **Indexing Trigger**: Logic hooks fire during record save/delete/restore operations
2. **Event Creation**: `AOD_LogicHooks` creates new `AOD_IndexEvent` record
3. **Status Recording**: Success/failure status recorded based on indexing outcome
4. **Error Capture**: Failed operations store error messages for debugging

### Monitoring and Reporting
- **Administrative Views**: Events displayed in AOD_Index admin interface
- **Failed Record Analysis**: Helps identify problematic modules or records
- **Performance Tracking**: Success rates provide indexing health metrics
- **Debugging**: Error messages assist in troubleshooting indexing issues

## Customization Framework

### Developer Extension Points
```php
class AOD_IndexEvent extends AOD_IndexEvent_sugar
{
    public function __construct()
    {
        parent::__construct();
        // Custom initialization logic here
    }
    
    // Custom methods can be added here
}
```

### Extension Patterns
- **Custom Logic**: Developers can add methods for custom event processing
- **Validation**: Additional validation logic for indexing events
- **Integration**: Custom hooks for external system integration
- **Reporting**: Custom methods for specialized reporting needs

## Relationship Management

### Record References
- **Target Records**: Events reference the records that were indexed
- **Module Context**: Events track which module the indexed record belongs to
- **User Association**: Events linked to users through standard assignable fields

### Data Integrity
- **Foreign Key Simulation**: `record_id` and `record_module` provide record references
- **Orphaned Events**: Events may persist even if target records are deleted
- **Historical Data**: Provides complete history of indexing operations

## Performance Considerations

### Database Optimization
- **Indexes**: Database indexes on `record_id` and `record_module` for efficient queries
- **Querying**: Optimized for filtering by success status and record criteria
- **Cleanup**: May require periodic cleanup of old events to manage table size

### Event Volume
- **High Volume**: Active sites may generate many indexing events
- **Storage Growth**: Event table can grow large over time
- **Maintenance**: Consider archiving or purging old successful events

## Error Handling and Debugging

### Error Information Storage
- **Error Messages**: Captured from indexing exceptions
- **Diagnostic Data**: Helps identify patterns in indexing failures
- **Troubleshooting**: Provides specific error context for failed operations

### Common Error Scenarios
- **File Access**: Document parsing failures
- **Memory Issues**: Large document processing problems
- **Permission Errors**: File system access issues
- **Format Problems**: Unsupported document types

## Administrative Usage

### Monitoring Index Health
- **Success Rates**: Percentage of successful vs failed indexing operations
- **Error Patterns**: Common error types and their frequency
- **Module Analysis**: Which modules have indexing problems
- **Performance Trends**: Indexing success over time

### Maintenance Operations
- **Failed Event Review**: Identifying records that need re-indexing
- **Error Resolution**: Addressing systematic indexing problems
- **Cleanup Operations**: Removing old or unnecessary event records
- **Performance Optimization**: Using event data to improve indexing

## Security and Access Control

### ACL Integration
- **Access Control**: Supports SuiteCRM's standard ACL system
- **Administrative Access**: Typically restricted to system administrators
- **Audit Trail**: Provides accountability for indexing operations

### Data Protection
- **Sensitive Information**: Error messages may contain file paths or system information
- **Access Restrictions**: Should be limited to authorized personnel
- **Privacy**: Events don't contain indexed record content, only metadata

## Deprecation Impact

### Current Status
- **Deprecated Since**: v7.12.0
- **Functionality**: Event tracking still functional but not recommended
- **Data Preservation**: Historical event data remains accessible

### Migration Considerations
- **Modern Search**: Newer search systems may use different event tracking
- **Data Migration**: Historical events may need conversion for new systems
- **Reporting**: Existing reports may need updates for new tracking systems
- **Cleanup**: Legacy events can be safely archived or removed during migration

## Integration Points

### System Components
- **AOD_Index**: Primary indexing system that generates events
- **AOD_LogicHooks**: Creates events in response to record changes
- **Administrative Interface**: Displays events for monitoring and debugging
- **Reporting System**: Uses events for indexing performance reports

### Framework Integration
- **SugarBean**: Standard SuiteCRM entity with full framework support
- **Module Builder**: Generated using SuiteCRM's module development tools
- **Workflow**: Integrates with SuiteCRM's logic hook and workflow systems
- **API**: Accessible through standard SuiteCRM API endpoints 