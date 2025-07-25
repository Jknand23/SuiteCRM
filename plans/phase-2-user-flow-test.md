# Phase 2 Feature 1: User Flow Test Guide

## Overview
This guide provides step-by-step instructions for testing the Interactive Lead List View with Advanced Filtering functionality implemented in Phase 2, Feature 1. The test covers all major features including filtering, column management, preference persistence, and real-time updates.

## Prerequisites

### Environment Setup
- [ ] SuiteCRM [[memory:4282330]] running in Docker environment 
- [ ] User account with Lead module access permissions
- [ ] Modern browser (Chrome, Firefox, Safari) with developer tools
- [ ] At least 50 test leads in the database with varied data

### Test Data Requirements
- [ ] Leads associated with different campaigns
- [ ] Leads with various industries (especially marketing/advertising focus)
- [ ] Leads with different activity levels (some with no recent activity)
- [ ] Leads with different statuses and sources

### Environment Variables (for Docker)
```bash
# Add to docker-compose.yml or .env file
SSE_POLL_INTERVAL=10          # Polling interval in seconds (default: 10)
SSE_HEARTBEAT_INTERVAL=30     # Heartbeat interval in seconds (default: 30)
SSE_MAX_EXECUTION_TIME=300    # Max SSE connection time in seconds (default: 300)
```

---

## Test Flow 1: Basic Lead List Access

### 1.1 Navigate to Lead List View
1. **Action**: Log into SuiteCRM
2. **Action**: Navigate to Leads module
3. **Expected**: 
   - Lead list view loads with Alpine.js components initialized
   - Filter bar is visible at the top
   - Column headers are interactive
   - No JavaScript errors in console

### 1.2 Verify Initial Data Load
1. **Action**: Check lead list displays properly
2. **Expected**:
   - Leads are displayed in table format
   - Pagination controls are visible
   - Default sort is by date_modified (descending)
   - Loading states work properly (skeleton screens)

---

## Test Flow 2: Advanced Filtering

### 2.1 Campaign Filter
1. **Action**: Click on Campaign dropdown in filter bar
2. **Expected**: 
   - Dropdown populates with active campaigns
   - Shows only campaigns with associated leads
3. **Action**: Select a specific campaign
4. **Expected**:
   - Lead list updates to show only leads from selected campaign
   - Filter is applied without page reload
   - Applied filter is visually indicated

### 2.2 Industry Filter
1. **Action**: Click on Industry dropdown
2. **Expected**:
   - Marketing/advertising industries appear at top (prioritized)
   - Other industries listed below
   - Visual separation between priority and regular industries
3. **Action**: Select "Marketing" industry
4. **Expected**:
   - Lead list filters to show only Marketing industry leads
   - Can combine with campaign filter (test AND logic)

### 2.3 Activity Filter
1. **Action**: Select "No activity in last 30 days" option
2. **Expected**:
   - Lead list shows leads with no recent activity
   - Activity type dropdown becomes available
3. **Action**: Select specific activity type (e.g., "emails")
4. **Expected**:
   - Filter refines to show leads with no email activity in 30 days

### 2.4 Search Filter
1. **Action**: Type "john" in search box
2. **Expected**:
   - Real-time filtering as you type (with debounce)
   - Searches across name, email, and account fields
   - Results update without page reload

### 2.5 Combined Filters
1. **Action**: Apply multiple filters simultaneously:
   - Campaign: "Q1 Marketing Campaign"
   - Industry: "Technology"
   - Search: "smith"
   - Activity: "No calls in 14 days"
2. **Expected**:
   - All filters work together
   - Results match ALL criteria (AND logic)
   - Filter summary shows all active filters

### 2.6 Filter Logic Toggle
1. **Action**: Switch filter logic from AND to OR
2. **Expected**:
   - Results update to show leads matching ANY criteria
   - More results typically shown with OR logic
   - Visual indication of active logic mode

---

## Test Flow 3: Column Management

### 3.1 Show/Hide Columns
1. **Action**: Click column management icon/button
2. **Expected**: 
   - Column selection panel appears
   - All available columns listed with checkboxes
   - Current visible columns are checked
3. **Action**: Uncheck "Industry" column
4. **Expected**:
   - Industry column immediately hides
   - Table adjusts layout smoothly
   - No data loss or display issues

### 3.2 Reorder Columns
1. **Action**: Drag "Email" column header to new position
2. **Expected**:
   - Drag handle appears on hover
   - Visual feedback during drag
   - Column moves to new position
   - Data remains aligned correctly

### 3.3 Column Sorting
1. **Action**: Click "Last Name" column header
2. **Expected**:
   - Leads sort by last name (ascending)
   - Sort indicator appears in header
3. **Action**: Click same header again
4. **Expected**:
   - Sort reverses to descending
   - Sort indicator updates

### 3.4 Multi-Column Sort
1. **Action**: Hold Shift and click "First Name" header
2. **Expected**:
   - Secondary sort applied
   - Leads sorted by Last Name, then First Name
   - Both sort indicators visible

---

## Test Flow 4: User Preference Persistence

### 4.1 Save Column Configuration
1. **Action**: Make changes to column visibility and order
2. **Expected**: 
   - Changes save automatically (or via Save button)
   - Success notification appears
   - No errors in network tab

### 4.2 Test Persistence
1. **Action**: Refresh the page (F5)
2. **Expected**:
   - Column configuration restored exactly
   - Hidden columns remain hidden
   - Column order preserved
   - Sort preferences maintained

### 4.3 Cross-Session Persistence
1. **Action**: Log out and log back in
2. **Expected**:
   - All preferences restored
   - Filter presets available
   - Column settings intact

### 4.4 Different Browser Test
1. **Action**: Log in from different browser
2. **Expected**:
   - Same preferences load (server-side storage)
   - Consistent experience across devices

---

## Test Flow 5: Real-Time Updates (SSE)

### 5.1 Establish SSE Connection
1. **Action**: Open browser developer tools → Network tab
2. **Action**: Navigate to lead list view
3. **Expected**:
   - See SSE connection request to `/Api/V8/leads/sse-stream`
   - Connection stays open (EventStream type)
   - Initial "connected" message received

### 5.2 Monitor Heartbeat
1. **Action**: Wait 30 seconds while watching Network tab
2. **Expected**:
   - Heartbeat messages appear every 30 seconds
   - Connection remains stable
   - No excessive server load

### 5.3 Test Lead Creation Update
1. **Action**: In another browser/tab, create a new lead
2. **Expected** (in original tab):
   - SSE "lead_created" event received within polling interval (10s default)
   - New lead appears in list automatically
   - Notification or visual indicator of update

### 5.4 Test Lead Update
1. **Action**: In second tab, edit an existing lead's name
2. **Expected** (in original tab):
   - SSE "lead_updated" event received
   - Lead data updates in real-time
   - Changed fields highlighted briefly

### 5.5 Test Lead Deletion
1. **Action**: In second tab, delete a lead
2. **Expected** (in original tab):
   - SSE "lead_deleted" event received
   - Lead removed from list smoothly
   - No layout disruption

### 5.6 Connection Recovery
1. **Action**: Disconnect network briefly (airplane mode)
2. **Expected**:
   - Error handling activates
   - Reconnection attempt after network returns
   - Missed updates synchronized

---

## Test Flow 6: Performance Testing

### 6.1 Large Dataset Handling
1. **Action**: Remove all filters to show all leads
2. **Expected**:
   - Pagination limits results (20-50 per page)
   - Smooth scrolling and interaction
   - No browser freezing

### 6.2 Rapid Filter Changes
1. **Action**: Quickly change multiple filters
2. **Expected**:
   - Debouncing prevents excessive API calls
   - UI remains responsive
   - Latest filter state applied correctly

### 6.3 Memory Usage
1. **Action**: Use Chrome DevTools Memory profiler
2. **Action**: Navigate between pages multiple times
3. **Expected**:
   - No significant memory leaks
   - Memory usage stabilizes
   - Alpine.js components properly cleaned up

---

## Test Flow 7: Error Handling

### 7.1 Network Error
1. **Action**: Use DevTools to throttle network to offline
2. **Action**: Try to apply filters
3. **Expected**:
   - Appropriate error messages
   - UI doesn't break
   - Can recover when network returns

### 7.2 Session Timeout
1. **Action**: Leave page idle until session expires
2. **Action**: Try to interact with filters
3. **Expected**:
   - Redirect to login or session timeout message
   - No data exposure
   - Preferences saved before timeout

### 7.3 Invalid Data
1. **Action**: Use DevTools to modify API response
2. **Expected**:
   - Graceful handling of malformed data
   - Error boundaries prevent full page crash
   - User-friendly error messages

---

## Test Flow 8: Accessibility Testing

### 8.1 Keyboard Navigation
1. **Action**: Tab through all interactive elements
2. **Expected**:
   - Logical tab order
   - All controls keyboard accessible
   - Visual focus indicators present

### 8.2 Screen Reader
1. **Action**: Enable screen reader (NVDA/JAWS)
2. **Expected**:
   - Table properly announced
   - Filter controls labeled
   - Status updates announced

### 8.3 Color Contrast
1. **Action**: Use Chrome Accessibility audit
2. **Expected**:
   - All text meets WCAG AA standards
   - Interactive elements have sufficient contrast
   - Error/success states distinguishable

---

## API Endpoint Verification

### Endpoints to Test
```bash
# Get filtered leads
GET /Api/V8/leads/filtered?page=1&limit=20&sort=date_modified&direction=desc

# Get campaigns list
GET /Api/V8/leads/campaigns/list

# Get industries list
GET /Api/V8/leads/industries/list

# Save user preference
POST /Api/V8/user/preferences
{
  "key": "lead_table_columns",
  "value": {"columnConfig": {...}}
}

# Load user preference
GET /Api/V8/user/preferences/lead_table_columns

# SSE stream (long-running)
GET /Api/V8/leads/sse-stream
```

### Expected Response Formats
- All endpoints return standardized JSON responses
- Success: `{"success": true, "data": {...}, "timestamp": "..."}`
- Error: `{"success": false, "error": "...", "timestamp": "..."}`

---

## Database Verification

### Check lead_changes Table
```sql
-- Verify table exists
SHOW TABLES LIKE 'lead_changes';

-- Check recent changes
SELECT * FROM lead_changes ORDER BY date_created DESC LIMIT 10;

-- Verify cleanup works (should have no records older than 7 days)
SELECT COUNT(*) FROM lead_changes WHERE date_created < DATE_SUB(NOW(), INTERVAL 7 DAY);
```

### Check user_preferences Table
```sql
-- Check saved preferences
SELECT * FROM user_preferences WHERE preference_key IN ('lead_table_columns', 'filter_presets');
```

---

## Common Issues & Troubleshooting

### Issue: SSE Connection Drops Frequently
- **Check**: Server configuration for long-running connections
- **Check**: Proxy/firewall timeout settings
- **Solution**: Adjust SSE_MAX_EXECUTION_TIME environment variable

### Issue: Filters Not Applying
- **Check**: Browser console for JavaScript errors
- **Check**: Network tab for failed API calls
- **Check**: Alpine.js devtools for component state

### Issue: Preferences Not Saving
- **Check**: user_preferences table permissions
- **Check**: API authentication is working
- **Check**: Browser localStorage not full

### Issue: Poor Performance
- **Check**: Database indexes on filtered columns
- **Check**: SSE_POLL_INTERVAL not too low (< 5 seconds)
- **Check**: Browser memory usage

---

## Success Criteria Checklist

- [ ] All filters work individually and in combination
- [ ] Column management features fully functional
- [ ] User preferences persist across sessions
- [ ] Real-time updates work via SSE
- [ ] Performance acceptable with large datasets
- [ ] No memory leaks or browser crashes
- [ ] Accessibility standards met
- [ ] Error handling prevents data loss
- [ ] API endpoints return consistent formats
- [ ] Database tables properly maintained

---

## Performance Benchmarks

| Action | Target | Acceptable |
|--------|--------|------------|
| Initial page load | < 2s | < 3s |
| Filter application | < 1s | < 2s |
| Column reorder | Instant | < 500ms |
| SSE update latency | < 10s | < 15s |
| Preference save | < 500ms | < 1s |

---

*This test guide ensures comprehensive validation of the Interactive Lead List View feature, covering functional, performance, and user experience aspects.* 