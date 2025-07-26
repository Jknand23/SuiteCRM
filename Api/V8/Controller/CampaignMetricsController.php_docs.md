# Campaign Metrics API Controller Documentation

## Overview

The Campaign Metrics API Controller provides RESTful endpoints for retrieving campaign performance metrics and analytics data. It supports the Campaign Progress Dashboard Widget and any other integrations requiring campaign analytics.

## Endpoint Details

### GET /Api/V8/campaigns/metrics

Retrieves comprehensive campaign performance metrics for a specified time range.

#### Request Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `timeRange` | string | 'week' | Time period for metrics (day, week, month, quarter, year) |
| `activeOnly` | boolean | true | Filter to include only active campaigns |

#### Response Format

```json
{
  "new_leads_count": 42,
  "active_campaigns": 5,
  "budget_utilization": 67.5,
  "performance_data": [
    {
      "date": "Jan 15",
      "leads": 5,
      "date_full": "2024-01-15"
    }
  ],
  "campaign_list": [
    {
      "id": "campaign-uuid",
      "name": "Q1 Marketing Campaign",
      "status": "Active",
      "budget": 10000.00,
      "actual_cost": 7550.00,
      "budget_utilization": 75.5
    }
  ],
  "metadata": {
    "time_range": "week",
    "start_date": "2024-01-15",
    "end_date": "2024-01-21",
    "active_only": true,
    "generated_at": "2024-01-15T10:30:00Z"
  }
}
```

#### Status Codes

- **200 OK**: Successful response with metrics data
- **403 Forbidden**: User lacks permission to view campaigns
- **500 Internal Server Error**: Server error during metrics calculation

## Implementation Details

### Authentication & Authorization

The controller uses SuiteCRM's existing authentication system:
- Requires valid user session
- Checks ACL permissions for Campaign module view access
- User-specific data filtering based on team/role permissions

### Caching Strategy

Implements a 5-minute cache to optimize performance:
- Cache key includes: time range, active filter, and user ID
- Uses SugarCache for distributed cache support
- Automatic cache invalidation on campaign updates

### Date Range Calculations

#### Time Range Options

1. **Day**: Current day (00:00:00 to 23:59:59)
2. **Week**: Monday to Sunday of current week
3. **Month**: First to last day of current month
4. **Quarter**: Current quarter (Q1: Jan-Mar, Q2: Apr-Jun, etc.)
5. **Year**: January 1 to December 31 of current year

#### Example Date Calculations

```php
// Week calculation
$start->modify('monday this week');

// Quarter calculation
$currentMonth = (int)$start->format('n');
$quarterStartMonth = ceil($currentMonth / 3) * 3 - 2;
$start->setDate($start->format('Y'), $quarterStartMonth, 1);
```

## Database Queries

### New Leads Count Query

```sql
SELECT COUNT(DISTINCT cl.target_id) as lead_count
FROM campaign_log cl
INNER JOIN campaigns c ON cl.campaign_id = c.id
WHERE cl.target_type = 'Leads'
  AND cl.activity_type = 'lead'
  AND cl.date_modified BETWEEN :start_date AND :end_date
  AND cl.deleted = 0
  AND c.deleted = 0
  AND c.status = 'Active' -- Optional based on activeOnly parameter
```

### Budget Utilization Query

```sql
SELECT AVG(
  CASE 
    WHEN budget > 0 THEN (actual_cost / budget) * 100 
    ELSE 0 
  END
) as avg_utilization
FROM campaigns
WHERE deleted = 0
  AND budget > 0
  AND status = 'Active' -- Optional based on activeOnly parameter
```

### Performance Trend Query

Executes daily queries within the date range:
```sql
SELECT COUNT(*) as lead_count
FROM campaign_log
WHERE target_type = 'Leads'
  AND activity_type = 'lead'
  AND DATE(date_modified) = :current_date
  AND deleted = 0
```

## Performance Considerations

### Query Optimization

1. **Indexed Columns**: Ensures indexes on:
   - `campaign_log.campaign_id`
   - `campaign_log.date_modified`
   - `campaign_log.activity_type`
   - `campaigns.status`

2. **Query Limits**: 
   - Maximum 20 campaigns in list view
   - Maximum 365 days for trend data
   - Aggregated daily data for performance

### Response Time Targets

- **Cached Response**: < 50ms
- **Fresh Query**: < 500ms for typical data volumes
- **Large Dataset**: < 2s for 100k+ campaign log entries

## Error Handling

### Exception Types

1. **Access Denied**: Returns 403 with clear error message
2. **Database Errors**: Logged and returns 500 status
3. **Invalid Parameters**: Returns 400 with validation details

### Error Response Format

```json
{
  "errors": [
    {
      "status": 403,
      "title": "Access Denied",
      "detail": "Access denied - insufficient permissions for Campaigns module"
    }
  ]
}
```

## Integration Examples

### JavaScript/Alpine.js

```javascript
async function loadCampaignMetrics() {
  try {
    const response = await fetch('/Api/V8/campaigns/metrics?timeRange=month', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    });
    
    if (!response.ok) throw new Error('Failed to load metrics');
    
    const data = await response.json();
    console.log('Campaign metrics:', data);
  } catch (error) {
    console.error('Error loading metrics:', error);
  }
}
```

### PHP Integration

```php
// Using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $sugar_config['site_url'] . '/Api/V8/campaigns/metrics?timeRange=week');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Cookie: ' . $_SERVER['HTTP_COOKIE']
]);

$response = curl_exec($ch);
$metrics = json_decode($response, true);
```

## Security Best Practices

1. **SQL Injection Prevention**: All queries use parameterized statements
2. **XSS Protection**: All output is JSON-encoded
3. **CSRF Protection**: Requires authenticated session
4. **Rate Limiting**: Inherits API rate limiting configuration
5. **Data Isolation**: Users only see campaigns they have permission to view

## Monitoring & Logging

### Log Entries

The controller logs:
- All errors with full stack traces
- Cache hits/misses for performance monitoring
- Slow queries (> 1 second execution time)
- Access denied attempts

### Example Log Entry

```
[2024-01-15 10:30:45] Campaign Metrics API Error: Database connection failed
Stack trace: ...
User: admin (user-id-123)
Parameters: timeRange=week, activeOnly=true
```

## Testing Recommendations

### Unit Tests

```php
public function testGetMetricsWithWeekRange() {
    $controller = new CampaignMetricsController();
    $request = $this->createMock(Request::class);
    $request->method('getQueryParams')->willReturn(['timeRange' => 'week']);
    
    $response = $controller->getMetrics($request, new Response(), []);
    
    $this->assertEquals(200, $response->getStatusCode());
    $data = json_decode($response->getBody(), true);
    $this->assertArrayHasKey('new_leads_count', $data);
}
```

### Integration Tests

1. Test with various date ranges
2. Verify permission checking
3. Test cache behavior
4. Validate response structure
5. Test with large datasets

## Future Enhancements

1. **GraphQL Support**: Add GraphQL endpoint for flexible queries
2. **Webhook Integration**: Push metrics to external systems
3. **Custom Metrics**: Allow user-defined KPIs
4. **Forecasting**: Add predictive analytics based on trends
5. **Comparison API**: Compare multiple campaigns or time periods 