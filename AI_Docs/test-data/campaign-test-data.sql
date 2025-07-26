-- Test Campaign Data for Campaign Progress Dashlet
-- This script creates test campaigns with budget data and lead associations

-- Clean up any existing test data
DELETE FROM campaign_log WHERE id LIKE 'test-log-%';
DELETE FROM leads WHERE id LIKE 'test-lead-%';
DELETE FROM campaigns WHERE id LIKE 'test-camp-%';

-- Insert test campaigns
INSERT INTO campaigns (id, name, status, campaign_type, budget, actual_cost, date_entered, date_modified, created_by, modified_user_id, assigned_user_id, deleted)
VALUES 
    ('test-camp-001', 'Digital Marketing Q1 2025', 'Active', 'Email', 50000.00, 15000.00, NOW(), NOW(), '1', '1', '1', 0),
    ('test-camp-002', 'Social Media Campaign', 'Active', 'Web', 30000.00, 22000.00, NOW(), NOW(), '1', '1', '1', 0),
    ('test-camp-003', 'Content Marketing Initiative', 'Active', 'Email', 25000.00, 8000.00, NOW(), NOW(), '1', '1', '1', 0),
    ('test-camp-004', 'SEO Optimization Project', 'Inactive', 'Web', 20000.00, 18000.00, NOW(), NOW(), '1', '1', '1', 0),
    ('test-camp-005', 'Email Newsletter Series', 'Active', 'Email', 15000.00, 5000.00, NOW(), NOW(), '1', '1', '1', 0);

-- Insert test leads
INSERT INTO leads (id, salutation, first_name, last_name, status, lead_source, date_entered, date_modified, created_by, modified_user_id, assigned_user_id, deleted)
VALUES
    ('test-lead-001', 'Mr.', 'John', 'Smith', 'New', 'Campaign', NOW(), NOW(), '1', '1', '1', 0),
    ('test-lead-002', 'Ms.', 'Jane', 'Doe', 'New', 'Campaign', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), '1', '1', '1', 0),
    ('test-lead-003', 'Dr.', 'Robert', 'Johnson', 'New', 'Campaign', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), '1', '1', '1', 0),
    ('test-lead-004', 'Mrs.', 'Emily', 'Williams', 'New', 'Campaign', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), '1', '1', '1', 0),
    ('test-lead-005', 'Mr.', 'Michael', 'Brown', 'New', 'Campaign', DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), '1', '1', '1', 0),
    ('test-lead-006', 'Ms.', 'Sarah', 'Davis', 'New', 'Campaign', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), '1', '1', '1', 0),
    ('test-lead-007', 'Mr.', 'David', 'Miller', 'New', 'Campaign', DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), '1', '1', '1', 0);

-- Insert campaign log entries to associate leads with campaigns
INSERT INTO campaign_log (id, campaign_id, target_id, target_type, activity_type, activity_date, date_modified, deleted)
VALUES
    ('test-log-001', 'test-camp-001', 'test-lead-001', 'Leads', 'lead', NOW(), NOW(), 0),
    ('test-log-002', 'test-camp-001', 'test-lead-002', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), 0),
    ('test-log-003', 'test-camp-002', 'test-lead-003', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 0),
    ('test-log-004', 'test-camp-002', 'test-lead-004', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), 0),
    ('test-log-005', 'test-camp-003', 'test-lead-005', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), 0),
    ('test-log-006', 'test-camp-005', 'test-lead-006', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), 0),
    ('test-log-007', 'test-camp-005', 'test-lead-007', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), 0);

-- Add more lead entries for performance trend
INSERT INTO campaign_log (id, campaign_id, target_id, target_type, activity_type, activity_date, date_modified, deleted)
VALUES
    ('test-log-008', 'test-camp-001', 'test-lead-001', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), 0),
    ('test-log-009', 'test-camp-002', 'test-lead-002', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 0),
    ('test-log-010', 'test-camp-001', 'test-lead-003', 'Leads', 'lead', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), 0); 