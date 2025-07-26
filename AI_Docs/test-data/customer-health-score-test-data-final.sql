-- Customer Health Score Test Data (Final Corrected Version)
-- This script creates comprehensive test data for the Customer Health Score feature
-- Fixed for proper SuiteCRM table structure

-- Clean up any existing test data
DELETE FROM accounts WHERE id LIKE 'test-acc-%';
DELETE FROM contacts WHERE id LIKE 'test-con-%';
DELETE FROM opportunities WHERE id LIKE 'test-opp-%';
DELETE FROM calls WHERE id LIKE 'test-call-%';
DELETE FROM meetings WHERE id LIKE 'test-meet-%';
DELETE FROM tasks WHERE id LIKE 'test-task-%';
DELETE FROM notes WHERE id LIKE 'test-note-%';
DELETE FROM emails WHERE id LIKE 'test-email-%';
DELETE FROM accounts_contacts WHERE account_id LIKE 'test-acc-%';
DELETE FROM accounts_opportunities WHERE account_id LIKE 'test-acc-%';
DELETE FROM email_addresses WHERE id LIKE 'test-email-addr-%';
DELETE FROM email_addr_bean_rel WHERE bean_id LIKE 'test-con-%' OR bean_id LIKE 'test-acc-%';

-- Test User (ensure we have a valid user for assignments)
INSERT IGNORE INTO users (id, user_name, first_name, last_name, status, is_admin, date_entered, date_modified, deleted)
VALUES ('test-user-001', 'testuser', 'Test', 'User', 'Active', 0, NOW(), NOW(), 0);

-- ===============================================
-- SCENARIO 1: HIGH HEALTH SCORE ACCOUNT
-- Active engagement, regular activities, progressing opportunities
-- ===============================================

-- Account: TechCorp Solutions (Expected Health Score: 85-95)
INSERT INTO accounts (id, name, account_type, industry, annual_revenue, employees, phone_office, billing_address_street, billing_address_city, billing_address_state, billing_address_postalcode, billing_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-acc-001', 'TechCorp Solutions', 'Customer', 'Technology', 2500000.00, 150, '+1-555-0101', '123 Innovation Drive', 'San Francisco', 'CA', '94105', 'USA', DATE_SUB(NOW(), INTERVAL 180 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Contact: Primary contact at TechCorp
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-001', 'Mr.', 'James', 'Peterson', 'CTO', '+1-555-0102', '123 Innovation Drive', 'San Francisco', 'CA', '94105', 'USA', DATE_SUB(NOW(), INTERVAL 180 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Add email address for contact
INSERT INTO email_addresses (id, email_address, email_address_caps, invalid_email, opt_out, date_created, date_modified, deleted)
VALUES ('test-email-addr-001', 'james.peterson@techcorp.com', 'JAMES.PETERSON@TECHCORP.COM', 0, 0, DATE_SUB(NOW(), INTERVAL 180 DAY), NOW(), 0);

-- Link email to contact
INSERT INTO email_addr_bean_rel (id, email_address_id, bean_id, bean_module, primary_address, reply_to_address, date_created, date_modified, deleted)
VALUES (UUID(), 'test-email-addr-001', 'test-con-001', 'Contacts', 1, 0, DATE_SUB(NOW(), INTERVAL 180 DAY), NOW(), 0);

-- Link contact to account
INSERT INTO accounts_contacts (id, account_id, contact_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-001', 'test-con-001', NOW(), 0);

-- Recent activities (high frequency)
INSERT INTO calls (id, name, status, direction, date_start, date_end, duration_hours, duration_minutes, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-call-001', 'Quarterly Business Review', 'Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 1, 30, 'Accounts', 'test-acc-001', 'test-user-001', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-call-002', 'Technical Discussion', 'Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), 0, 45, 'Accounts', 'test-acc-001', 'test-user-001', DATE_SUB(NOW(), INTERVAL 7 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-call-003', 'Follow-up Call', 'Held', 'Inbound', DATE_SUB(NOW(), INTERVAL 14 DAY), DATE_SUB(NOW(), INTERVAL 14 DAY), 0, 30, 'Accounts', 'test-acc-001', 'test-user-001', DATE_SUB(NOW(), INTERVAL 14 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

INSERT INTO meetings (id, name, status, date_start, date_end, duration_hours, duration_minutes, location, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-meet-001', 'Project Kickoff Meeting', 'Held', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), 2, 0, 'TechCorp Office', 'Accounts', 'test-acc-001', 'test-user-001', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-meet-002', 'Strategy Session', 'Held', DATE_SUB(NOW(), INTERVAL 21 DAY), DATE_SUB(NOW(), INTERVAL 21 DAY), 1, 30, 'Conference Room A', 'Accounts', 'test-acc-001', 'test-user-001', DATE_SUB(NOW(), INTERVAL 21 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

INSERT INTO notes (id, name, description, parent_type, parent_id, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES 
('test-note-001', 'Client Requirements Update', 'James confirmed the new technical requirements and approved the timeline extension. Very positive about the project direction.', 'Accounts', 'test-acc-001', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0),
('test-note-002', 'Budget Discussion', 'Client approved additional budget for Phase 2. Ready to proceed with expanded scope.', 'Accounts', 'test-acc-001', DATE_SUB(NOW(), INTERVAL 10 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- High-value progressing opportunity (removed account_id field)
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-opp-001', 'TechCorp Enterprise License Renewal', 125000.00, 125000.00, '-99', DATE_ADD(NOW(), INTERVAL 30 DAY), 'Proposal/Price Quote', 75, 'Existing Customer', 'test-user-001', DATE_SUB(NOW(), INTERVAL 60 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

-- Link opportunity to account via relationship table
INSERT INTO accounts_opportunities (id, account_id, opportunity_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-001', 'test-opp-001', NOW(), 0);

-- ===============================================
-- SCENARIO 2: MEDIUM HEALTH SCORE ACCOUNT  
-- Moderate engagement, some missed activities, stalled opportunity
-- ===============================================

-- Account: RetailPlus Inc (Expected Health Score: 45-65)
INSERT INTO accounts (id, name, account_type, industry, annual_revenue, employees, phone_office, billing_address_street, billing_address_city, billing_address_state, billing_address_postalcode, billing_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-acc-002', 'RetailPlus Inc', 'Customer', 'Retail', 800000.00, 50, '+1-555-0201', '456 Commerce Street', 'Chicago', 'IL', '60601', 'USA', DATE_SUB(NOW(), INTERVAL 365 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Contact: Less responsive contact
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-002', 'Ms.', 'Sarah', 'Thompson', 'Operations Manager', '+1-555-0202', '456 Commerce Street', 'Chicago', 'IL', '60601', 'USA', DATE_SUB(NOW(), INTERVAL 365 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Add email address for contact
INSERT INTO email_addresses (id, email_address, email_address_caps, invalid_email, opt_out, date_created, date_modified, deleted)
VALUES ('test-email-addr-002', 'sarah.thompson@retailplus.com', 'SARAH.THOMPSON@RETAILPLUS.COM', 0, 0, DATE_SUB(NOW(), INTERVAL 365 DAY), NOW(), 0);

-- Link email to contact
INSERT INTO email_addr_bean_rel (id, email_address_id, bean_id, bean_module, primary_address, reply_to_address, date_created, date_modified, deleted)
VALUES (UUID(), 'test-email-addr-002', 'test-con-002', 'Contacts', 1, 0, DATE_SUB(NOW(), INTERVAL 365 DAY), NOW(), 0);

-- Link contact to account
INSERT INTO accounts_contacts (id, account_id, contact_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-002', 'test-con-002', NOW(), 0);

-- Moderate activity frequency (some gaps)
INSERT INTO calls (id, name, status, direction, date_start, date_end, duration_hours, duration_minutes, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-call-004', 'Check-in Call', 'Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY), 0, 20, 'Accounts', 'test-acc-002', 'test-user-001', DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-call-005', 'Support Issue Discussion', 'Not Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY), 0, 0, 'Accounts', 'test-acc-002', 'test-user-001', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

INSERT INTO tasks (id, name, status, priority, date_due, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-task-001', 'Follow up on pending proposal', 'Not Started', 'High', DATE_SUB(NOW(), INTERVAL 5 DAY), 'Accounts', 'test-acc-002', 'test-user-001', DATE_SUB(NOW(), INTERVAL 20 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-task-002', 'Schedule quarterly review', 'Completed', 'Medium', DATE_SUB(NOW(), INTERVAL 45 DAY), 'Accounts', 'test-acc-002', 'test-user-001', DATE_SUB(NOW(), INTERVAL 50 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

-- Stalled opportunity (removed account_id field)
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-opp-002', 'RetailPlus System Upgrade', 45000.00, 45000.00, '-99', DATE_ADD(NOW(), INTERVAL 90 DAY), 'Needs Analysis', 25, 'Existing Customer', 'test-user-001', DATE_SUB(NOW(), INTERVAL 120 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY), 'test-user-001', 'test-user-001', 0);

-- Link opportunity to account via relationship table
INSERT INTO accounts_opportunities (id, account_id, opportunity_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-002', 'test-opp-002', NOW(), 0);

-- ===============================================
-- SCENARIO 3: LOW HEALTH SCORE ACCOUNT
-- Poor engagement, missed calls, no recent activity, lost opportunity
-- ===============================================

-- Account: DownTrend Corp (Expected Health Score: 15-35)
INSERT INTO accounts (id, name, account_type, industry, annual_revenue, employees, phone_office, billing_address_street, billing_address_city, billing_address_state, billing_address_postalcode, billing_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-acc-003', 'DownTrend Corp', 'Customer', 'Manufacturing', 400000.00, 25, '+1-555-0301', '789 Industrial Blvd', 'Detroit', 'MI', '48201', 'USA', DATE_SUB(NOW(), INTERVAL 730 DAY), DATE_SUB(NOW(), INTERVAL 180 DAY), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Contact: Unresponsive contact
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-003', 'Mr.', 'Robert', 'Martinez', 'Purchasing Manager', '+1-555-0302', '789 Industrial Blvd', 'Detroit', 'MI', '48201', 'USA', DATE_SUB(NOW(), INTERVAL 730 DAY), DATE_SUB(NOW(), INTERVAL 180 DAY), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Add email address for contact (with invalid flag for testing)
INSERT INTO email_addresses (id, email_address, email_address_caps, invalid_email, opt_out, date_created, date_modified, deleted)
VALUES ('test-email-addr-003', 'robert.martinez@downtrend.com', 'ROBERT.MARTINEZ@DOWNTREND.COM', 1, 0, DATE_SUB(NOW(), INTERVAL 730 DAY), NOW(), 0);

-- Link email to contact
INSERT INTO email_addr_bean_rel (id, email_address_id, bean_id, bean_module, primary_address, reply_to_address, date_created, date_modified, deleted)
VALUES (UUID(), 'test-email-addr-003', 'test-con-003', 'Contacts', 1, 0, DATE_SUB(NOW(), INTERVAL 730 DAY), NOW(), 0);

-- Link contact to account
INSERT INTO accounts_contacts (id, account_id, contact_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-003', 'test-con-003', NOW(), 0);

-- Minimal activity (old and missed)
INSERT INTO calls (id, name, status, direction, date_start, date_end, duration_hours, duration_minutes, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-call-006', 'Attempted Follow-up', 'Not Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 60 DAY), 0, 0, 'Accounts', 'test-acc-003', 'test-user-001', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 60 DAY), 'test-user-001', 'test-user-001', 0),
('test-call-007', 'Contract Renewal Discussion', 'Not Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 90 DAY), DATE_SUB(NOW(), INTERVAL 90 DAY), 0, 0, 'Accounts', 'test-acc-003', 'test-user-001', DATE_SUB(NOW(), INTERVAL 90 DAY), DATE_SUB(NOW(), INTERVAL 90 DAY), 'test-user-001', 'test-user-001', 0);

INSERT INTO notes (id, name, description, parent_type, parent_id, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-note-003', 'Contact Unresponsive', 'Multiple attempts to reach Robert. Email bounced back. May need to find new contact at company.', 'Accounts', 'test-acc-003', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Lost opportunity (removed account_id field)
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-opp-003', 'DownTrend Maintenance Contract', 15000.00, 15000.00, '-99', DATE_SUB(NOW(), INTERVAL 30 DAY), 'Closed Lost', 0, 'Existing Customer', 'test-user-001', DATE_SUB(NOW(), INTERVAL 180 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY), 'test-user-001', 'test-user-001', 0);

-- Link opportunity to account via relationship table
INSERT INTO accounts_opportunities (id, account_id, opportunity_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-003', 'test-opp-003', NOW(), 0);

-- ===============================================
-- VERIFICATION QUERY
-- ===============================================
-- Check that the data was inserted correctly
SELECT 'Test data summary:' as info;
SELECT 'Accounts' as Type, COUNT(*) as Count FROM accounts WHERE id LIKE 'test-acc-%'
UNION ALL
SELECT 'Contacts', COUNT(*) FROM contacts WHERE id LIKE 'test-con-%'
UNION ALL  
SELECT 'Opportunities', COUNT(*) FROM opportunities WHERE id LIKE 'test-opp-%'
UNION ALL
SELECT 'Calls', COUNT(*) FROM calls WHERE id LIKE 'test-call-%'
UNION ALL
SELECT 'Meetings', COUNT(*) FROM meetings WHERE id LIKE 'test-meet-%'
UNION ALL
SELECT 'Notes', COUNT(*) FROM notes WHERE id LIKE 'test-note-%'
UNION ALL
SELECT 'Email Addresses', COUNT(*) FROM email_addresses WHERE id LIKE 'test-email-addr-%';

-- ===============================================
-- SUMMARY OF FINAL CORRECTED TEST DATA
-- ===============================================
/*
FINAL CORRECTIONS APPLIED:
1. Removed email1 field from contacts (uses email_addresses relationship)
2. Removed account_id field from opportunities (uses accounts_opportunities relationship)
3. Added proper email_addresses and email_addr_bean_rel entries
4. Proper relationship linking via accounts_opportunities table

This creates 3 test accounts with all proper SuiteCRM relationships:
- test-acc-001 (TechCorp Solutions) - HIGH HEALTH SCORE (85-95)
- test-acc-002 (RetailPlus Inc) - MEDIUM HEALTH SCORE (45-65)  
- test-acc-003 (DownTrend Corp) - LOW HEALTH SCORE (15-35)

Each account includes:
- Contact with proper email relationship
- Activities (calls, meetings, notes, tasks)
- Opportunities with proper account relationships
- Varying patterns for health score testing
*/ 