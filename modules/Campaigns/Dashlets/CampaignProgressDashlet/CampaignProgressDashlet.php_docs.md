# Campaign Progress Dashlet Documentation

## Overview

The Campaign Progress Dashlet is a modern dashboard widget that provides real-time campaign performance metrics and visualizations for marketing teams. It extends SuiteCRM's existing Dashlet framework while introducing Alpine.js reactivity and Chart.js visualizations.

## File Structure

```
modules/Campaigns/Dashlets/CampaignProgressDashlet/
├── CampaignProgressDashlet.php         # Main dashlet class
├── CampaignProgressDashlet.meta.php    # Dashlet registration metadata
├── CampaignProgressDashlet.en_us.lang.php  # Language strings
├── CampaignProgressDashletDisplay.tpl  # Display template with Alpine.js
└── CampaignProgressDashletConfigure.tpl # Configuration template
```

## Troubleshooting

### Blank Home Screen
If the home screen appears blank after adding this dashlet:
1. Check if the debug template loads by temporarily switching templates
2. Verify all template variables are properly assigned
3. Check for PHP errors in Apache/SuiteCRM logs
4. Ensure language files are properly loaded

## Key Features

### 1. Real-time Metrics Display
- **New Leads Count**: Shows total new leads generated from active campaigns
- **Active Campaigns**: Displays count of currently active campaigns
- **Budget Utilization**: Shows average budget usage across campaigns
- **Performance Trends**: Line chart showing daily lead generation

### 2. Interactive Visualizations
- **Chart.js Integration**: Beautiful, responsive charts
- **Theme-aware Styling**: Charts adapt to selected SuiteCRM theme
- **Alpine.js Reactivity**: Real-time updates without page refresh
- **Progress Bars**: Visual representation of budget utilization

### 3. Customization Options
- **Time Range Selection**: Day, Week, Month, Quarter, Year
- **Metric Selection**: Choose which metrics to display
- **Active Campaigns Filter**: Option to show only active campaigns
- **Auto-refresh Settings**: Configurable refresh intervals

## API Integration

### Campaign Metrics Endpoint
- **URL**: `/Api/V8/campaigns/metrics`
- **Method**: GET
- **Parameters**: 
  - `timeRange`: (string) day|week|month|quarter|year
  - `activeOnly`: (boolean) Filter for active campaigns only
- **Response**: JSON with metrics data and metadata

### Response Structure
```json
{
  "new_leads_count": 42,
  "active_campaigns": 5,
  "budget_utilization": 67.5,
  "performance_data": [
    {"date": "Jan 15", "leads": 5},
    {"date": "Jan 16", "leads": 8}
  ],
  "campaign_list": [
    {
      "id": "uuid",
      "name": "Campaign Name",
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

## Class Methods

### CampaignProgressDashlet Class

#### `__construct($id, $def)`
Initializes the dashlet with saved configuration options.

#### `display()`
Renders the dashlet content with metrics and visualizations.

#### `getCampaignMetrics()`
Retrieves campaign metrics from the database with the following data:
- New leads count for the selected time range
- Active campaigns count
- Average budget utilization percentage
- Daily performance trend data
- Campaign list with individual progress

#### `displayOptions()`
Renders the configuration panel for the dashlet.

#### `saveOptions($req)`
Processes and saves user configuration choices.

#### `hasAccess()`
Checks if current user has permission to view campaigns.

## Database Queries

### Lead Count Query
```sql
SELECT COUNT(DISTINCT cl.target_id) as lead_count
FROM campaign_log cl
INNER JOIN campaigns c ON cl.campaign_id = c.id
WHERE cl.target_type = 'Leads'
AND cl.activity_type = 'lead'
AND cl.date_modified BETWEEN :start AND :end
AND cl.deleted = 0
AND c.deleted = 0
AND c.status = 'Active'
```

### Budget Utilization Query
```sql
SELECT AVG(CASE 
    WHEN budget > 0 THEN (actual_cost / budget) * 100 
    ELSE 0 
END) as avg_utilization
FROM campaigns
WHERE deleted = 0
AND budget > 0
AND status = 'Active'
```

## Alpine.js Component

### Component Structure
```javascript
function campaignProgressWidget_{dashletId}() {
    return {
        isLoading: true,
        hasError: false,
        metrics: {},
        lastUpdated: '',
        performanceChart: null,
        
        init() {
            this.loadMetrics();
            this.setupRealtimeUpdates();
        },
        
        async loadMetrics() {
            // Fetches data from API endpoint
        },
        
        updatePerformanceChart() {
            // Updates Chart.js visualization
        },
        
        setupRealtimeUpdates() {
            // Connects to SSE for real-time updates
        }
    };
}
```

## Security Considerations

1. **ACL Checks**: Verifies user has Campaign view permissions
2. **SQL Injection Prevention**: Uses parameterized queries
3. **XSS Protection**: All output is properly escaped
4. **CSRF Protection**: Leverages SuiteCRM's existing protection

## Performance Optimizations

1. **Caching**: 5-minute cache for metrics data
2. **Query Limits**: Maximum 20 campaigns in list view
3. **Date Range Limits**: Maximum 365 days for trend data
4. **Lazy Loading**: Charts only render when visible

## Theme Integration

The dashlet uses CSS custom properties for theme-aware styling:
- `--theme-primary`: Primary color for charts
- `--theme-success`: Success indicators
- `--theme-warning`: Warning states
- `--theme-danger`: Alert states
- `--theme-border-subtle`: Grid lines
- `--theme-text`: Text colors

## Installation Requirements

1. **PHP 7.4+**: Required for typed properties and modern syntax
2. **Chart.js 4.4.1**: Loaded via CDN
3. **Alpine.js 3.x**: Loaded via CDN
4. **SuiteCRM 7.10+**: Compatible with existing dashlet framework

## Testing Considerations

### Unit Tests
- Test metric calculations with various date ranges
- Verify ACL permission checks
- Test configuration saving and loading

### Integration Tests
- Verify API endpoint returns correct data
- Test dashlet rendering in different themes
- Verify real-time updates via SSE

### User Acceptance Tests
- Add dashlet to dashboard
- Configure different metric combinations
- Verify chart interactions and tooltips
- Test responsive behavior on tablets

## Troubleshooting

### Common Issues

1. **No Data Displayed**
   - Check if campaigns exist with budget data
   - Verify campaign_log table has lead entries
   - Ensure proper date range is selected

2. **Charts Not Rendering**
   - Verify Chart.js CDN is accessible
   - Check browser console for JavaScript errors
   - Ensure Alpine.js is properly loaded

3. **Real-time Updates Not Working**
   - Verify SSE endpoint is configured
   - Check browser supports EventSource
   - Ensure no proxy blocks SSE connections

## Future Enhancements

1. **Export Functionality**: Add CSV/PDF export for metrics
2. **Drill-down Navigation**: Click metrics to view details
3. **Custom Date Ranges**: Allow specific date selection
4. **Email Alerts**: Notify when thresholds are reached
5. **Comparison Mode**: Compare multiple campaigns 