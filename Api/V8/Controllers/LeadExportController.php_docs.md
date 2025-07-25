# Lead Export Controller Documentation

## Overview

The `LeadExportController` provides API endpoints for exporting filtered lead data in various formats. This controller maintains the current filter state from the lead list view, allowing users to export exactly what they see on screen. It supports both CSV and Excel formats with proper character encoding and formatting.

## Key Features

### 1. Format Support
- **CSV Export**: Universal format with UTF-8 encoding and Excel compatibility
- **Excel Export**: XLSX format with formatting, filters, and auto-sized columns
- **Automatic Fallback**: Falls back to CSV if Excel library is unavailable

### 2. Filter Preservation
- Maintains all active filters from the lead list view
- Supports search terms, campaign filters, industry filters, and activity filters
- Exports only the data matching current view criteria

### 3. Column Customization
- Respects user's visible column selections
- Allows specific column export via API parameters
- Provides sensible defaults if no columns specified

### 4. Large Dataset Support
- Streams data for memory efficiency
- Configurable export limits to prevent timeouts
- Proper handling of special characters and encoding

## API Endpoint

### GET /Api/V8/leads/export

Exports filtered leads in the specified format.

**Query Parameters:**
- `format` (optional): Export format - "csv" or "excel" (default: "csv")
- `columns` (optional): Comma-separated list of columns to export
- All filter parameters from LeadFilterController are supported

**Example Request:**
```
GET /Api/V8/leads/export?format=csv&columns=name,email,status&search=john&industry=Technology
```

**Response:**
- Direct file download with appropriate headers
- Filename includes timestamp: `lead_export_2024-01-15_14-30-00.csv`

## Exportable Columns

The following columns are available for export:

| Column ID | Display Label | Description |
|-----------|--------------|-------------|
| name | Lead Name | Combined first and last name |
| first_name | First Name | Lead's first name |
| last_name | Last Name | Lead's last name |
| email | Email Address | Primary email address |
| account_name | Company | Associated company name |
| status | Status | Current lead status |
| industry | Industry | Business industry category |
| phone_work | Work Phone | Business phone number |
| phone_mobile | Mobile Phone | Mobile phone number |
| lead_source | Lead Source | Origin of the lead |
| description | Description | Lead notes/description |
| date_entered | Date Created | When lead was created |
| date_modified | Last Modified | Last update timestamp |
| assigned_user_name | Assigned To | User assigned to lead |

## Export Formats

### CSV Format Features
- UTF-8 encoding with BOM for Excel compatibility
- Proper escaping of special characters
- Configurable delimiter (comma by default)
- Header row with column labels
- Date formatting: YYYY-MM-DD HH:MM:SS

**Example CSV Output:**
```csv
"Lead Name","Email Address","Company","Status","Last Modified"
"John Doe","john@example.com","Acme Corp","New","2024-01-15 14:30:00"
"Jane Smith","jane@example.com","Tech Inc","In Process","2024-01-14 10:15:00"
```

### Excel Format Features
- XLSX format with modern Excel compatibility
- Formatted header row with bold text and background color
- Auto-sized columns for optimal viewing
- Auto-filter enabled on header row
- Proper date/time formatting
- Support for large datasets

**Excel Specific Features:**
- Sheet name: "Lead Export"
- Header styling: Bold, gray background
- Column filters pre-enabled
- Automatic column width adjustment

## Security Considerations

### Permission Checks
- Verifies user has "export" permission for leads module
- Respects record-level security settings
- Filters results based on user's data visibility

### Data Security
- No sensitive system fields are exposed
- Configurable column restrictions possible
- Audit trail for export operations
- Secure file delivery headers

## Performance Optimization

### Memory Management
- Streams CSV data directly to output
- Processes leads in batches to avoid memory issues
- 10,000 record limit per export for safety

### Query Optimization
- Reuses efficient query from LeadFilterController
- Selects only required columns
- Proper database indexing utilized

### Caching
- No caching for exports to ensure data freshness
- Filter results are always current
- Real-time data accuracy guaranteed

## Error Handling

### Common Errors

1. **Permission Denied (403)**
```json
{
  "status": "error",
  "message": "Access denied: Insufficient permissions to export leads"
}
```

2. **Invalid Format (400)**
```json
{
  "status": "error",
  "message": "Invalid export format. Supported formats: csv, excel"
}
```

3. **No Columns Selected (400)**
```json
{
  "status": "error",
  "message": "No columns selected for export"
}
```

### Error Recovery
- Graceful fallback from Excel to CSV on library errors
- Clear error messages for troubleshooting
- Maintains data integrity on export failure

## Integration Examples

### JavaScript Export Function
```javascript
// Export filtered leads
async function exportLeads(format = 'csv') {
  // Get current filters from Alpine store
  const filters = Alpine.store('leadFilters').activeFilters;
  
  // Get visible columns
  const visibleColumns = Alpine.store('leadData').visibleColumns
    .filter(col => col.id !== 'selection' && col.id !== 'actions')
    .map(col => col.id);
  
  // Build query parameters
  const params = new URLSearchParams({
    format: format,
    columns: visibleColumns.join(','),
    ...filters
  });
  
  // Trigger download
  window.location.href = `/Api/V8/leads/export?${params}`;
}
```

### Alpine.js Integration
```javascript
// In lead table view component
Alpine.data('leadTableView', () => ({
  async exportFilteredLeads(format = 'csv') {
    this.$store.leadData.exportInProgress = true;
    
    try {
      const filters = this.$store.leadFilters.activeFilters;
      const columns = this.getExportColumns();
      
      const params = new URLSearchParams({
        format: format,
        columns: columns.join(','),
        ...this.buildFilterParams(filters)
      });
      
      // Create hidden iframe for download
      const iframe = document.createElement('iframe');
      iframe.style.display = 'none';
      iframe.src = `/Api/V8/leads/export?${params}`;
      document.body.appendChild(iframe);
      
      // Clean up after download starts
      setTimeout(() => {
        document.body.removeChild(iframe);
      }, 5000);
      
    } catch (error) {
      console.error('Export failed:', error);
    } finally {
      this.$store.leadData.exportInProgress = false;
    }
  }
}));
```

## Best Practices

### User Experience
- Show export progress indicator
- Disable export button during processing
- Provide format selection options
- Display estimated row count before export

### Data Handling
- Always export currently filtered view
- Respect user's column preferences
- Include clear column headers
- Format dates consistently

### Error Communication
- Show clear error messages to users
- Log detailed errors for debugging
- Provide alternative export options
- Guide users to resolve issues

## Troubleshooting

### Common Issues

1. **Excel Export Not Working**
   - Check if PhpSpreadsheet is installed
   - Verify PHP memory limits
   - Try CSV format as alternative

2. **Character Encoding Issues**
   - Ensure UTF-8 BOM is present for Excel
   - Check database character set
   - Verify browser encoding settings

3. **Large Export Timeouts**
   - Reduce number of columns
   - Apply more restrictive filters
   - Consider batch exports

4. **Missing Data**
   - Verify user permissions
   - Check filter criteria
   - Ensure columns are properly mapped

## Configuration Options

### PHP Configuration
```php
// Maximum records per export
private $maxExportRecords = 10000;

// Memory limit for Excel exports
ini_set('memory_limit', '512M');

// Execution time for large exports
set_time_limit(300);
```

### Column Configuration
```php
// Add custom columns
$this->exportableColumns['custom_field'] = 'Custom Field Label';

// Remove sensitive columns
unset($this->exportableColumns['sensitive_data']);
```

## Future Enhancements

### Planned Features
- Scheduled exports with email delivery
- Export templates for common use cases
- PDF export format support
- Compressed file exports for large datasets
- Background job processing for huge exports

### API Improvements
- Webhook notifications on export completion
- Export history tracking
- Shareable export configurations
- Multi-format simultaneous exports 