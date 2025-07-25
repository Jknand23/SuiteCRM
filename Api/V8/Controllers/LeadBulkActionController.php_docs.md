# Lead Bulk Action Controller Documentation

## Overview

The `LeadBulkActionController` provides RESTful API endpoints for performing bulk operations on multiple leads simultaneously. This controller enables efficient batch processing of common lead management tasks with proper validation, authorization, and transaction management to ensure data integrity.

## Key Features

### 1. Bulk Lead Assignment
- Assigns multiple leads to a specific user in a single operation
- Validates user existence and permissions before assignment
- Logs all assignment operations for audit trail

### 2. Bulk Status Updates
- Updates the status of multiple leads simultaneously
- Supports all standard lead statuses (New, Assigned, In Process, Converted, Recycled, Dead)
- Tracks status change history for each lead

### 3. Bulk Campaign Addition
- Adds multiple leads to a marketing campaign
- Validates campaign existence and active status
- Uses SuiteCRM relationship management for proper associations

### 4. Bulk Deletion
- Performs soft delete on multiple leads
- Preserves data for potential recovery
- Logs deletion details including lead data before removal

### 5. Transaction Management
- All bulk operations are wrapped in database transactions
- Automatic rollback on any error to maintain data consistency
- Detailed error tracking for partial failures

## API Endpoints

### POST /Api/V8/leads/bulk-assign
Assigns multiple leads to a specified user.

**Request Body:**
```json
{
  "lead_ids": ["lead-id-1", "lead-id-2", "lead-id-3"],
  "assigned_user_id": "user-id"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "affected_count": 3,
    "total_requested": 3,
    "errors": []
  }
}
```

### POST /Api/V8/leads/bulk-update-status
Updates the status of multiple leads.

**Request Body:**
```json
{
  "lead_ids": ["lead-id-1", "lead-id-2"],
  "status": "In Process"
}
```

**Valid Status Values:**
- New
- Assigned
- In Process
- Converted
- Recycled
- Dead

### POST /Api/V8/leads/bulk-add-to-campaign
Adds multiple leads to a campaign.

**Request Body:**
```json
{
  "lead_ids": ["lead-id-1", "lead-id-2"],
  "campaign_id": "campaign-id"
}
```

### DELETE /Api/V8/leads/bulk-delete
Soft deletes multiple leads.

**Request Body:**
```json
{
  "lead_ids": ["lead-id-1", "lead-id-2"]
}
```

## Security Features

### Permission Checks
- All operations verify user permissions using SuiteCRM's ACL system
- Separate permission checks for different operations (edit, delete)
- Campaign operations require permissions for both leads and campaigns

### Input Validation
- Lead ID arrays are validated for proper format
- Maximum bulk size limit of 500 leads per operation
- User and campaign IDs are validated for existence
- Status values are restricted to valid options

### Audit Trail
- All bulk operations are logged with detailed information
- Includes user performing the action, timestamp, and affected records
- Deletion operations log lead data before removal

## Error Handling

### Response Format
All error responses follow a consistent format:
```json
{
  "status": "error",
  "message": "Error description",
  "code": 400
}
```

### Common Error Codes
- **400 Bad Request**: Invalid input data or exceeds bulk limit
- **403 Forbidden**: Insufficient permissions
- **500 Internal Server Error**: Database or system errors

### Partial Failures
When some operations succeed but others fail:
```json
{
  "status": "success",
  "data": {
    "affected_count": 2,
    "total_requested": 3,
    "errors": [
      "Failed to assign lead lead-id-3: Lead not found"
    ]
  }
}
```

## Performance Considerations

### Transaction Management
- Each bulk operation runs within a single database transaction
- Commits only occur after all operations complete successfully
- Automatic rollback on any failure maintains data integrity

### Bulk Size Limits
- Maximum 500 leads per operation to prevent timeout issues
- Larger batches should be split into multiple requests
- Consider using background jobs for very large operations

### Database Optimization
- Uses efficient batch queries where possible
- Minimizes database round trips
- Proper indexing on lead IDs for fast lookups

## Integration Examples

### JavaScript (Alpine.js)
```javascript
// Bulk assign leads to user
async function bulkAssignLeads(leadIds, userId) {
  const response = await fetch('/Api/V8/leads/bulk-assign', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'same-origin',
    body: JSON.stringify({
      lead_ids: leadIds,
      assigned_user_id: userId
    })
  });
  
  return await response.json();
}
```

### Error Handling Example
```javascript
try {
  const result = await bulkAssignLeads(selectedLeads, userId);
  
  if (result.data.errors.length > 0) {
    console.warn('Some operations failed:', result.data.errors);
  }
  
  console.log(`Successfully assigned ${result.data.affected_count} leads`);
} catch (error) {
  console.error('Bulk assignment failed:', error);
}
```

## Best Practices

### Batch Size Management
- Keep batches under 100 leads for optimal performance
- Use progress indicators for larger operations
- Consider implementing client-side batch splitting

### User Feedback
- Show progress during bulk operations
- Display success/failure counts after completion
- Provide detailed error messages for failures

### Permission Handling
- Check permissions client-side before attempting operations
- Handle permission errors gracefully
- Provide clear messaging about required permissions

## Troubleshooting

### Common Issues

1. **Transaction Timeout**
   - Reduce batch size
   - Check database performance
   - Consider background processing

2. **Permission Errors**
   - Verify user has appropriate module permissions
   - Check record-level security settings
   - Ensure proper authentication

3. **Partial Failures**
   - Review error array in response
   - Check individual lead status
   - Verify related record existence

### Logging
All operations are logged to:
- SuiteCRM application logs
- Audit trail for affected records
- System error logs for failures

## Future Enhancements

### Planned Features
- Background job processing for very large batches
- Progress tracking via SSE or WebSockets
- Bulk field updates beyond status
- Undo/redo functionality

### API Version Compatibility
- Current version: 1.0.0
- Backward compatibility maintained
- Deprecation notices provided in advance
- Version-specific documentation available 