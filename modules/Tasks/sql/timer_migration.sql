-- Task Timer Database Migration
-- Adds timer-related fields to the tasks table
-- Run this script to enable time tracking functionality

-- Add timer_is_running field
ALTER TABLE tasks 
ADD COLUMN timer_is_running TINYINT(1) DEFAULT 0 
COMMENT 'Indicates if the timer is currently running';

-- Add timer_start_time field
ALTER TABLE tasks 
ADD COLUMN timer_start_time DATETIME DEFAULT NULL 
COMMENT 'Timestamp when the current timer session started';

-- Add timer_total_seconds field
ALTER TABLE tasks 
ADD COLUMN timer_total_seconds INT(11) DEFAULT 0 
COMMENT 'Total accumulated time in seconds';

-- Add timer_sessions field for JSON data
ALTER TABLE tasks 
ADD COLUMN timer_sessions TEXT DEFAULT NULL 
COMMENT 'JSON array of timer sessions with start/end times and durations';

-- Add indexes for better performance
CREATE INDEX idx_timer_running ON tasks(timer_is_running);
CREATE INDEX idx_timer_total ON tasks(timer_total_seconds); 