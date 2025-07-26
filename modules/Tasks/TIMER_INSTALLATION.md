# Task Timer Installation Guide

## Prerequisites
- SuiteCRM 7.x
- PHP 7.4+
- MySQL 5.7+
- Admin access to SuiteCRM

## Installation Steps

### 1. Database Migration

Run the following SQL script to add timer fields to the tasks table:

```bash
mysql -u your_username -p your_database < modules/Tasks/sql/timer_migration.sql
```

Or execute manually in phpMyAdmin/MySQL client:
```sql
ALTER TABLE tasks 
ADD COLUMN timer_is_running TINYINT(1) DEFAULT 0,
ADD COLUMN timer_start_time DATETIME DEFAULT NULL,
ADD COLUMN timer_total_seconds INT(11) DEFAULT 0,
ADD COLUMN timer_sessions TEXT DEFAULT NULL;

CREATE INDEX idx_timer_running ON tasks(timer_is_running);
CREATE INDEX idx_timer_total ON tasks(timer_total_seconds);
```

### 2. Clear Cache

1. Navigate to Admin → Repair
2. Run "Quick Repair and Rebuild"
3. Execute any SQL changes if prompted
4. Clear browser cache

### 3. Verify Installation

1. Navigate to any task Detail View
2. You should see a "Time Tracking" tab
3. Test the timer by clicking "Start Timer"

## File Checklist

Ensure all files are present:

**Backend Files:**
- [x] `modules/Tasks/Services/TimerService.php`
- [x] `modules/Tasks/controller.php`
- [x] `modules/Tasks/vardefs_timer.php`
- [x] `modules/Tasks/sql/timer_migration.sql`

**Frontend Files:**
- [x] `modules/Tasks/js/timer.js`
- [x] `modules/Tasks/css/timer.css`
- [x] `modules/Tasks/tpls/timer_widget.tpl`
- [x] `modules/Tasks/tpls/timer_widget_quick.tpl`

**Modified Files:**
- [x] `modules/Tasks/vardefs.php` (timer fields added)
- [x] `modules/Tasks/metadata/detailviewdefs.php`
- [x] `modules/Tasks/metadata/editviewdefs.php`
- [x] `modules/Tasks/language/en_us.lang.php`
- [x] `modules/Tasks/Save.php`

## Permissions

Ensure proper file permissions:
```bash
chmod 755 modules/Tasks/Services/
chmod 644 modules/Tasks/Services/TimerService.php
chmod 644 modules/Tasks/js/timer.js
chmod 644 modules/Tasks/css/timer.css
chmod 755 modules/Tasks/tpls/
chmod 644 modules/Tasks/tpls/*.tpl
```

## Troubleshooting

### Timer not appearing
- Clear SuiteCRM cache (Admin → Repair)
- Check file permissions
- Verify all files are uploaded
- Check PHP error logs

### JavaScript errors
- Ensure jQuery is loaded (should be in SuiteCRM)
- Check browser console for conflicts
- Verify timer.js is loading

### Database errors
- Confirm migration script ran successfully
- Check tasks table has new columns
- Verify MySQL user has ALTER permissions

## Rollback

To remove the timer feature:

1. Remove timer columns from database:
```sql
ALTER TABLE tasks 
DROP COLUMN timer_is_running,
DROP COLUMN timer_start_time,
DROP COLUMN timer_total_seconds,
DROP COLUMN timer_sessions;

DROP INDEX idx_timer_running ON tasks;
DROP INDEX idx_timer_total ON tasks;
```

2. Restore original files from backup
3. Clear cache and rebuild 