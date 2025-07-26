-- Customer Health Score Test Data
-- This script creates comprehensive test data for the Customer Health Score feature
-- Includes accounts, contacts, activities, emails, and opportunities with various scenarios

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
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, email1, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-001', 'Mr.', 'James', 'Peterson', 'CTO', '+1-555-0102', 'james.peterson@techcorp.com', '123 Innovation Drive', 'San Francisco', 'CA', '94105', 'USA', DATE_SUB(NOW(), INTERVAL 180 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

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

-- High-value progressing opportunity
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, account_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-opp-001', 'TechCorp Enterprise License Renewal', 125000.00, 125000.00, '-99', DATE_ADD(NOW(), INTERVAL 30 DAY), 'Proposal/Price Quote', 75, 'Existing Customer', 'test-acc-001', 'test-user-001', DATE_SUB(NOW(), INTERVAL 60 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

-- Link opportunity to account
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
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, email1, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-002', 'Ms.', 'Sarah', 'Thompson', 'Operations Manager', '+1-555-0202', 'sarah.thompson@retailplus.com', '456 Commerce Street', 'Chicago', 'IL', '60601', 'USA', DATE_SUB(NOW(), INTERVAL 365 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

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

-- Stalled opportunity
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, account_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-opp-002', 'RetailPlus System Upgrade', 45000.00, 45000.00, '-99', DATE_ADD(NOW(), INTERVAL 90 DAY), 'Needs Analysis', 25, 'Existing Customer', 'test-acc-002', 'test-user-001', DATE_SUB(NOW(), INTERVAL 120 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY), 'test-user-001', 'test-user-001', 0);

-- Link opportunity to account
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
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, email1, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-003', 'Mr.', 'Robert', 'Martinez', 'Purchasing Manager', '+1-555-0302', 'robert.martinez@downtrend.com', '789 Industrial Blvd', 'Detroit', 'MI', '48201', 'USA', DATE_SUB(NOW(), INTERVAL 730 DAY), DATE_SUB(NOW(), INTERVAL 180 DAY), 'test-user-001', 'test-user-001', 'test-user-001', 0);

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

-- Lost opportunity
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, account_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-opp-003', 'DownTrend Maintenance Contract', 15000.00, 15000.00, '-99', DATE_SUB(NOW(), INTERVAL 30 DAY), 'Closed Lost', 0, 'Existing Customer', 'test-acc-003', 'test-user-001', DATE_SUB(NOW(), INTERVAL 180 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY), 'test-user-001', 'test-user-001', 0);

-- Link opportunity to account  
INSERT INTO accounts_opportunities (id, account_id, opportunity_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-003', 'test-opp-003', NOW(), 0);

-- ===============================================
-- SCENARIO 4: NEW CUSTOMER - BUILDING RELATIONSHIP
-- Recent account, some initial activities, early-stage opportunity
-- ===============================================

-- Account: FreshStart Ventures (Expected Health Score: 50-70)
INSERT INTO accounts (id, name, account_type, industry, annual_revenue, employees, phone_office, billing_address_street, billing_address_city, billing_address_state, billing_address_postalcode, billing_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-acc-004', 'FreshStart Ventures', 'Prospect', 'Financial Services', 1200000.00, 75, '+1-555-0401', '321 Startup Lane', 'Austin', 'TX', '73301', 'USA', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Contact: New contact, building relationship
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, email1, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-004', 'Dr.', 'Lisa', 'Chen', 'CEO', '+1-555-0402', 'lisa.chen@freshstart.com', '321 Startup Lane', 'Austin', 'TX', '73301', 'USA', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Link contact to account
INSERT INTO accounts_contacts (id, account_id, contact_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-004', 'test-con-004', NOW(), 0);

-- Initial relationship building activities
INSERT INTO calls (id, name, status, direction, date_start, date_end, duration_hours, duration_minutes, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-call-008', 'Discovery Call', 'Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), 0, 45, 'Accounts', 'test-acc-004', 'test-user-001', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-call-009', 'Initial Introduction', 'Held', 'Inbound', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY), 0, 30, 'Accounts', 'test-acc-004', 'test-user-001', DATE_SUB(NOW(), INTERVAL 20 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

INSERT INTO meetings (id, name, status, date_start, date_end, duration_hours, duration_minutes, location, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-meet-003', 'Product Demo', 'Planned', DATE_ADD(NOW(), INTERVAL 7 DAY), DATE_ADD(NOW(), INTERVAL 7 DAY), 1, 0, 'Virtual Meeting', 'Accounts', 'test-acc-004', 'test-user-001', NOW(), NOW(), 'test-user-001', 'test-user-001', 0);

-- Early stage opportunity
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, account_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES ('test-opp-004', 'FreshStart Digital Transformation', 75000.00, 75000.00, '-99', DATE_ADD(NOW(), INTERVAL 120 DAY), 'Qualification', 50, 'Cold Call', 'test-acc-004', 'test-user-001', DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

-- Link opportunity to account
INSERT INTO accounts_opportunities (id, account_id, opportunity_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-004', 'test-opp-004', NOW(), 0);

-- ===============================================
-- SCENARIO 5: VIP CUSTOMER - EXCELLENT RELATIONSHIP
-- High-value customer, frequent contact, multiple opportunities
-- ===============================================

-- Account: Enterprise Global Inc (Expected Health Score: 90-100)
INSERT INTO accounts (id, name, account_type, industry, annual_revenue, employees, phone_office, billing_address_street, billing_address_city, billing_address_state, billing_address_postalcode, billing_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-acc-005', 'Enterprise Global Inc', 'Customer', 'Technology', 15000000.00, 500, '+1-555-0501', '100 Corporate Plaza', 'New York', 'NY', '10001', 'USA', DATE_SUB(NOW(), INTERVAL 1095 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Contact: Highly engaged executive contact
INSERT INTO contacts (id, salutation, first_name, last_name, title, phone_work, email1, primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode, primary_address_country, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-005', 'Ms.', 'Amanda', 'Rodriguez', 'Chief Information Officer', '+1-555-0502', 'amanda.rodriguez@enterprise-global.com', '100 Corporate Plaza', 'New York', 'NY', '10001', 'USA', DATE_SUB(NOW(), INTERVAL 1095 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Link contact to account
INSERT INTO accounts_contacts (id, account_id, contact_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-005', 'test-con-005', NOW(), 0);

-- Frequent, high-quality activities
INSERT INTO calls (id, name, status, direction, date_start, date_end, duration_hours, duration_minutes, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-call-010', 'Executive Review Meeting', 'Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), 1, 0, 'Accounts', 'test-acc-005', 'test-user-001', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-call-011', 'Strategic Planning Call', 'Held', 'Inbound', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), 0, 45, 'Accounts', 'test-acc-005', 'test-user-001', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-call-012', 'Partnership Discussion', 'Held', 'Outbound', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY), 1, 15, 'Accounts', 'test-acc-005', 'test-user-001', DATE_SUB(NOW(), INTERVAL 12 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

INSERT INTO meetings (id, name, status, date_start, date_end, duration_hours, duration_minutes, location, parent_type, parent_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-meet-004', 'Quarterly Business Review', 'Held', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), 3, 0, 'Enterprise Global HQ', 'Accounts', 'test-acc-005', 'test-user-001', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-meet-005', 'Innovation Workshop', 'Held', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY), 4, 0, 'Conference Center', 'Accounts', 'test-acc-005', 'test-user-001', DATE_SUB(NOW(), INTERVAL 18 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

INSERT INTO notes (id, name, description, parent_type, parent_id, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES 
('test-note-004', 'Expansion Opportunities', 'Amanda expressed strong interest in expanding our partnership to include their European operations. Scheduled follow-up for next week.', 'Accounts', 'test-acc-005', NOW(), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0),
('test-note-005', 'Excellent Partnership Feedback', 'Client extremely satisfied with project delivery. Mentioned us in their board presentation as a key strategic partner.', 'Accounts', 'test-acc-005', DATE_SUB(NOW(), INTERVAL 7 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Multiple high-value opportunities
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, account_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-opp-005', 'Enterprise Global Expansion Phase 3', 500000.00, 500000.00, '-99', DATE_ADD(NOW(), INTERVAL 45 DAY), 'Negotiation/Review', 90, 'Existing Customer', 'test-acc-005', 'test-user-001', DATE_SUB(NOW(), INTERVAL 90 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-opp-006', 'European Operations Integration', 250000.00, 250000.00, '-99', DATE_ADD(NOW(), INTERVAL 60 DAY), 'Value Proposition', 65, 'Existing Customer', 'test-acc-005', 'test-user-001', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

-- Link opportunities to account
INSERT INTO accounts_opportunities (id, account_id, opportunity_id, date_modified, deleted)
VALUES 
(UUID(), 'test-acc-005', 'test-opp-005', NOW(), 0),
(UUID(), 'test-acc-005', 'test-opp-006', NOW(), 0);

-- ===============================================
-- ADDITIONAL TEST SCENARIOS
-- ===============================================

-- Account with email tracking data (for email engagement scoring)
INSERT INTO accounts (id, name, account_type, industry, annual_revenue, employees, phone_office, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-acc-006', 'Email Test Corp', 'Customer', 'Services', 300000.00, 20, '+1-555-0601', DATE_SUB(NOW(), INTERVAL 90 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

INSERT INTO contacts (id, salutation, first_name, last_name, title, email1, date_entered, date_modified, assigned_user_id, created_by, modified_user_id, deleted)
VALUES ('test-con-006', 'Mr.', 'David', 'Email', 'Marketing Director', 'david.email@emailtest.com', DATE_SUB(NOW(), INTERVAL 90 DAY), NOW(), 'test-user-001', 'test-user-001', 'test-user-001', 0);

-- Link contact to account
INSERT INTO accounts_contacts (id, account_id, contact_id, date_modified, deleted)
VALUES (UUID(), 'test-acc-006', 'test-con-006', NOW(), 0);

-- Sample email records (for email engagement tracking)
INSERT INTO emails (id, name, type, status, parent_type, parent_id, date_sent, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-email-001', 'Monthly Newsletter', 'out', 'sent', 'Accounts', 'test-acc-006', DATE_SUB(NOW(), INTERVAL 7 DAY), 'test-user-001', DATE_SUB(NOW(), INTERVAL 7 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-email-002', 'Product Update Announcement', 'out', 'send', 'Accounts', 'test-acc-006', DATE_SUB(NOW(), INTERVAL 14 DAY), 'test-user-001', DATE_SUB(NOW(), INTERVAL 14 DAY), NOW(), 'test-user-001', 'test-user-001', 0),
('test-email-003', 'Follow-up Response', 'in', 'read', 'Accounts', 'test-acc-006', DATE_SUB(NOW(), INTERVAL 21 DAY), 'test-user-001', DATE_SUB(NOW(), INTERVAL 21 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

-- Opportunities in various stages for testing
INSERT INTO opportunities (id, name, amount, amount_usdollar, currency_id, date_closed, sales_stage, probability, lead_source, account_id, assigned_user_id, date_entered, date_modified, created_by, modified_user_id, deleted)
VALUES 
('test-opp-007', 'Small Service Contract', 10000.00, 10000.00, '-99', DATE_ADD(NOW(), INTERVAL 30 DAY), 'Closed Won', 100, 'Existing Customer', 'test-acc-006', 'test-user-001', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY), 'test-user-001', 'test-user-001', 0),
('test-opp-008', 'Upsell Opportunity', 25000.00, 25000.00, '-99', DATE_ADD(NOW(), INTERVAL 75 DAY), 'Prospecting', 20, 'Existing Customer', 'test-acc-006', 'test-user-001', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW(), 'test-user-001', 'test-user-001', 0);

-- Link opportunities to account
INSERT INTO accounts_opportunities (id, account_id, opportunity_id, date_modified, deleted)
VALUES 
(UUID(), 'test-acc-006', 'test-opp-007', NOW(), 0),
(UUID(), 'test-acc-006', 'test-opp-008', NOW(), 0);

-- ===============================================
-- SUMMARY OF TEST DATA
-- ===============================================
/*
ACCOUNTS CREATED FOR HEALTH SCORE TESTING:

1. test-acc-001 (TechCorp Solutions) - HIGH HEALTH SCORE (85-95)
   - Frequent recent activities (calls, meetings, notes)
   - High-value progressing opportunity ($125K, 75% probability)
   - Responsive contact, positive engagement

2. test-acc-002 (RetailPlus Inc) - MEDIUM HEALTH SCORE (45-65)  
   - Moderate activity, some gaps and missed calls
   - Stalled opportunity ($45K, 25% probability)
   - Mixed engagement patterns

3. test-acc-003 (DownTrend Corp) - LOW HEALTH SCORE (15-35)
   - Minimal activity, missed calls, old data
   - Lost opportunity (Closed Lost)
   - Unresponsive contact

4. test-acc-004 (FreshStart Ventures) - BUILDING HEALTH SCORE (50-70)
   - New account with initial activities
   - Early-stage opportunity ($75K, 50% probability)
   - Good initial engagement

5. test-acc-005 (Enterprise Global Inc) - EXCELLENT HEALTH SCORE (90-100)
   - Very frequent, high-quality activities
   - Multiple high-value opportunities ($500K + $250K)
   - Strong executive engagement

6. test-acc-006 (Email Test Corp) - EMAIL TESTING
   - Email engagement tracking
   - Won and early-stage opportunities
   - Mixed activity patterns

This data provides comprehensive scenarios for testing the Customer Health Score algorithm across all three components:
- Activity frequency (40% weight)
- Email engagement (30% weight)  
- Opportunity progress (30% weight)
*/ 