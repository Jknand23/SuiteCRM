# Customer Interaction Summary Troubleshooting Guide

## Issue: "View Interaction Timeline" Button Does Nothing

### Problem Description
When clicking the "View Interaction Timeline" button in the Account detail view dropdown, nothing happens.

### Root Cause
The InteractionSummary module wasn't properly registered with SuiteCRM's module loader, preventing the system from routing to the module.

### Solution Applied

1. **Created Module Registration Files**:
   - `custom/modules/InteractionSummary/InteractionSummary.php` - Base module class
   - `custom/modules/InteractionSummary/vardefs.php` - Variable definitions
   - `custom/Extension/application/Ext/Include/InteractionSummary.php` - Module loader registration

2. **Module Configuration**:
   - Registered as a service module (no database table)
   - Added to module list but kept invisible from tabs
   - Properly mapped bean class and file locations

### Steps to Fix

1. **Run Quick Repair and Rebuild** (CRITICAL - Must be done after adding module files) [[memory:4282330]]
   ```
   Admin → Repair → Quick Repair and Rebuild
   ```

2. **Clear ALL Caches**:
   - Browser cache (Ctrl+F5)
   - SuiteCRM cache: Admin → Repair → Clear Additional Cache

3. **Test the Button**:
   - Navigate to any Account detail view
   - Click "View Interaction Timeline"
   - Should now redirect to the timeline view

### Verification Steps

To verify the module is registered:
1. Check if the file `custom/application/Ext/Include/modules.ext.php` contains InteractionSummary
2. Look for any JavaScript errors in browser console when clicking the button
3. Check URL changes when button is clicked

### Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Button not visible | Run Quick Repair and clear browser cache |
| Click does nothing | Module not registered - ensure all files created and repair run |
| 404 error | Check action name matches view file name |
| Permission denied | Check module ACL settings |

### Technical Details

The button uses this URL pattern:
```
index.php?module=InteractionSummary&action=timeline&parent_type=Accounts&parent_id={record_id}
```

SuiteCRM routing:
- Module: InteractionSummary
- Action: timeline → maps to `view.timeline.php`
- View class: ViewTimeline

### Additional Notes [[memory:4278509]]

Since the project uses Docker, you can also try:
- Restarting the Docker containers
- Checking container logs for PHP errors
- Ensuring file permissions are correct in the container

---
*Module registered*  
*Quick Repair clears the path*  
*Timeline now works* 