-- Mock Tasks for Testing Task Timer Feature
-- This script creates sample tasks with various timer states
-- Run after timer_migration.sql to populate test data

-- Clear existing test tasks (optional - be careful in production!)
-- DELETE FROM tasks WHERE name LIKE 'TEST:%';

-- Get a valid user ID (adjust this based on your setup)
SET @user_id = '1'; -- Admin user ID

-- Task 1: Fresh task with no timer data
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified, 
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Review Q1 Financial Reports', 
    'Not Started', 
    'High', 
    NOW(), 
    NOW(), 
    @user_id, 
    @user_id, 
    @user_id,
    'Review and analyze Q1 financial performance metrics for board presentation',
    0, 
    NULL, 
    0, 
    NULL
);

-- Task 2: Task with some accumulated time (45 minutes)
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Update Client Database', 
    'In Progress', 
    'Medium', 
    DATE_SUB(NOW(), INTERVAL 2 DAY), 
    NOW(),
    @user_id, 
    @user_id, 
    @user_id,
    'Clean up and update client contact information in CRM',
    0, 
    NULL, 
    2700, -- 45 minutes in seconds
    '[{"start":"2024-01-15 09:00:00","end":"2024-01-15 09:30:00","duration":1800},{"start":"2024-01-15 14:00:00","end":"2024-01-15 14:15:00","duration":900}]'
);

-- Task 3: Task with timer currently running (started 15 minutes ago)
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Prepare Marketing Campaign', 
    'In Progress', 
    'High', 
    DATE_SUB(NOW(), INTERVAL 1 DAY), 
    NOW(),
    @user_id, 
    @user_id, 
    @user_id,
    'Design and prepare Q2 marketing campaign materials',
    1, 
    DATE_SUB(NOW(), INTERVAL 15 MINUTE), 
    3600, -- Already has 1 hour logged
    '[{"start":"2024-01-15 10:00:00","end":"2024-01-15 11:00:00","duration":3600}]'
);

-- Task 4: Completed task with extensive timer history
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Website Redesign Project', 
    'Completed', 
    'High', 
    DATE_SUB(NOW(), INTERVAL 7 DAY), 
    DATE_SUB(NOW(), INTERVAL 1 DAY),
    @user_id, 
    @user_id, 
    @user_id,
    'Complete redesign of company website with new branding',
    0, 
    NULL, 
    14400, -- 4 hours total
    '[{"start":"2024-01-10 09:00:00","end":"2024-01-10 11:00:00","duration":7200},{"start":"2024-01-11 14:00:00","end":"2024-01-11 15:30:00","duration":5400},{"start":"2024-01-12 10:00:00","end":"2024-01-12 10:30:00","duration":1800}]'
);

-- Task 5: Task with timer running for a long session (2 hours ago)
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: API Documentation Update', 
    'In Progress', 
    'Medium', 
    DATE_SUB(NOW(), INTERVAL 3 DAY), 
    NOW(),
    @user_id, 
    @user_id, 
    @user_id,
    'Update API documentation for v2.0 release',
    1, 
    DATE_SUB(NOW(), INTERVAL 2 HOUR), 
    5400, -- 1.5 hours previously logged
    '[{"start":"2024-01-14 09:00:00","end":"2024-01-14 10:30:00","duration":5400}]'
);

-- Task 6: New urgent task with no timer
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Fix Critical Bug #1234', 
    'Not Started', 
    'Urgent', 
    NOW(), 
    NOW(),
    @user_id, 
    @user_id, 
    @user_id,
    'Critical bug affecting payment processing - needs immediate attention',
    0, 
    NULL, 
    0, 
    NULL
);

-- Task 7: Task with multiple short sessions
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Customer Support Tickets', 
    'In Progress', 
    'Medium', 
    DATE_SUB(NOW(), INTERVAL 2 DAY), 
    NOW(),
    @user_id, 
    @user_id, 
    @user_id,
    'Respond to customer support tickets in queue',
    0, 
    NULL, 
    3000, -- 50 minutes total
    '[{"start":"2024-01-15 09:00:00","end":"2024-01-15 09:10:00","duration":600},{"start":"2024-01-15 10:00:00","end":"2024-01-15 10:15:00","duration":900},{"start":"2024-01-15 11:00:00","end":"2024-01-15 11:20:00","duration":1200},{"start":"2024-01-15 14:00:00","end":"2024-01-15 14:05:00","duration":300}]'
);

-- Task 8: Long-running project task
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Annual Budget Planning', 
    'In Progress', 
    'High', 
    DATE_SUB(NOW(), INTERVAL 14 DAY), 
    NOW(),
    @user_id, 
    @user_id, 
    @user_id,
    'Prepare comprehensive annual budget for all departments',
    0, 
    NULL, 
    28800, -- 8 hours total
    '[{"start":"2024-01-08 09:00:00","end":"2024-01-08 12:00:00","duration":10800},{"start":"2024-01-09 09:00:00","end":"2024-01-09 12:00:00","duration":10800},{"start":"2024-01-10 14:00:00","end":"2024-01-10 16:00:00","duration":7200}]'
);

-- Task 9: Quick task completed with timer
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Send Follow-up Email', 
    'Completed', 
    'Low', 
    DATE_SUB(NOW(), INTERVAL 1 DAY), 
    DATE_SUB(NOW(), INTERVAL 1 DAY),
    @user_id, 
    @user_id, 
    @user_id,
    'Send follow-up email to potential client after meeting',
    0, 
    NULL, 
    600, -- 10 minutes
    '[{"start":"2024-01-14 15:00:00","end":"2024-01-14 15:10:00","duration":600}]'
);

-- Task 10: Task started but paused quickly
INSERT INTO tasks (
    id, name, status, priority, date_entered, date_modified,
    created_by, modified_user_id, assigned_user_id, description,
    timer_is_running, timer_start_time, timer_total_seconds, timer_sessions
) VALUES (
    UUID(), 
    'TEST: Research Competitor Pricing', 
    'In Progress', 
    'Medium', 
    NOW(), 
    NOW(),
    @user_id, 
    @user_id, 
    @user_id,
    'Analyze competitor pricing strategies for Q2 planning',
    0, 
    NULL, 
    300, -- 5 minutes
    '[{"start":"2024-01-15 16:00:00","end":"2024-01-15 16:05:00","duration":300}]'
);

-- Summary of test data:
SELECT 
    'Test Tasks Created' as Summary,
    COUNT(*) as Total_Tasks,
    SUM(CASE WHEN timer_is_running = 1 THEN 1 ELSE 0 END) as Active_Timers,
    SUM(timer_total_seconds) / 3600 as Total_Hours_Logged,
    AVG(timer_total_seconds) / 3600 as Avg_Hours_Per_Task
FROM tasks 
WHERE name LIKE 'TEST:%'; 