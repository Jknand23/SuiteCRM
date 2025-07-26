# Test Data for SuiteCRM Features

This directory contains SQL scripts and documentation for testing various SuiteCRM features.

## Customer Health Score Test Data

The `customer-health-score-test-data.sql` file provides comprehensive mock data for testing the Customer Health Score feature. This data creates six different account scenarios with varying health scores.

### Test Scenarios Created

✅ **test-acc-001 (TechCorp Solutions)** - **HIGH HEALTH SCORE (85-95)**
- Frequent recent activities (calls, meetings, notes)
- High-value progressing opportunity ($125K, 75% probability)
- Responsive contact with positive engagement patterns
- Perfect for testing high-performing accounts

✅ **test-acc-002 (RetailPlus Inc)** - **MEDIUM HEALTH SCORE (45-65)**
- Moderate activity frequency with some gaps
- Stalled opportunity ($45K, 25% probability)  
- Mixed engagement patterns (some missed calls)
- Tests accounts needing attention

✅ **test-acc-003 (DownTrend Corp)** - **LOW HEALTH SCORE (15-35)**
- Minimal activity, mostly missed calls
- Lost opportunity (Closed Lost status)
- Unresponsive contact patterns
- Tests at-risk account identification

✅ **test-acc-004 (FreshStart Ventures)** - **BUILDING HEALTH SCORE (50-70)**
- New account with initial relationship building
- Early-stage opportunity ($75K, 50% probability)
- Good initial engagement patterns
- Tests new customer onboarding scenarios

✅ **test-acc-005 (Enterprise Global Inc)** - **EXCELLENT HEALTH SCORE (90-100)**
- Very frequent, high-quality activities
- Multiple high-value opportunities ($500K + $250K)
- Strong executive-level engagement
- Tests VIP customer scenarios

✅ **test-acc-006 (Email Test Corp)** - **EMAIL ENGAGEMENT TESTING**
- Email tracking data (sent/received/opened)
- Won and early-stage opportunities
- Mixed activity patterns
- Tests email engagement scoring component

### Health Score Algorithm Components

The test data is designed to test all three algorithm components:

1. **Activity Frequency (40% weight)**
   - Recent calls, meetings, tasks, and notes
   - Frequency and timing patterns
   - Success rates (held vs. missed)

2. **Email Engagement (30% weight)**
   - Sent/received email tracking
   - Response patterns and timing
   - Email status tracking

3. **Opportunity Progress (30% weight)**
   - Deal pipeline health and stage progression
   - Probability and amount weighting
   - Win/loss patterns and timing

## Campaign Progress Test Data

The `campaign-test-data.sql` file provides test data for the Campaign Progress Dashlet feature.

### Using the Test Data

Since you're using Docker [[memory:4282330]], you can execute the SQL scripts directly:

```bash
# Copy the SQL file to the container
docker cp AI_Docs/test-data/customer-health-score-test-data.sql [container-name]:/tmp/

# Execute the SQL script
docker exec -it [container-name] mysql -u root -p suitecrm < /tmp/customer-health-score-test-data.sql
```

Replace `[container-name]` with your actual Docker container name.

### Alternative Methods

**Option 1: Using SuiteCRM UI (Recommended for Small Data Sets)**
1. Go to Sales → Accounts and create test accounts manually
2. Add contacts, activities, and opportunities through the UI
3. This method provides better data validation

**Option 2: Database Management Tool**
1. Connect to your database using phpMyAdmin, MySQL Workbench, etc.
2. Execute the SQL script directly in the query interface

### Test Data Verification

After running the customer health score test data, verify the data was created successfully:

```sql
-- Check accounts were created
SELECT COUNT(*) FROM accounts WHERE id LIKE 'test-acc-%';
-- Should return 6

-- Check activities were created  
SELECT COUNT(*) FROM calls WHERE id LIKE 'test-call-%';
SELECT COUNT(*) FROM meetings WHERE id LIKE 'test-meet-%';
SELECT COUNT(*) FROM notes WHERE id LIKE 'test-note-%';

-- Check opportunities were created
SELECT COUNT(*) FROM opportunities WHERE id LIKE 'test-opp-%';
-- Should return 8
```

### Expected Health Score Ranges

After implementing the health score algorithm, you should see these approximate ranges:

- **TechCorp Solutions**: 85-95 (Green - Excellent)
- **Enterprise Global Inc**: 90-100 (Green - Excellent)  
- **FreshStart Ventures**: 50-70 (Yellow - Good)
- **RetailPlus Inc**: 45-65 (Yellow - Needs Attention)
- **Email Test Corp**: 50-75 (Yellow/Green - Variable)
- **DownTrend Corp**: 15-35 (Red - At Risk)

### Cleanup

To remove test data:

```sql
-- Clean up customer health score test data
DELETE FROM accounts WHERE id LIKE 'test-acc-%';
DELETE FROM contacts WHERE id LIKE 'test-con-%';
DELETE FROM opportunities WHERE id LIKE 'test-opp-%';
DELETE FROM calls WHERE id LIKE 'test-call-%';
DELETE FROM meetings WHERE id LIKE 'test-meet-%';
DELETE FROM tasks WHERE id LIKE 'test-task-%';
DELETE FROM notes WHERE id LIKE 'test-note-%';
DELETE FROM emails WHERE id LIKE 'test-email-%';
```

---

*This test data enables comprehensive testing of the Customer Health Score feature across all algorithm components and business scenarios.* 