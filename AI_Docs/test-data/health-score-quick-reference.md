# Customer Health Score Test Data - Quick Reference

## 🚀 Quick Setup

```bash
# Execute the test data
docker cp AI_Docs/test-data/customer-health-score-test-data.sql [container-name]:/tmp/
docker exec -it [container-name] mysql -u root -p suitecrm < /tmp/customer-health-score-test-data.sql
```

## 📊 Test Scenarios Overview

| Account | Expected Score | Status | Key Characteristics |
|---------|---------------|---------|-------------------|
| **TechCorp Solutions** | 85-95 | 🟢 Excellent | Recent activities, $125K opportunity (75%) |
| **Enterprise Global Inc** | 90-100 | 🟢 Excellent | Multiple opportunities, exec engagement |
| **FreshStart Ventures** | 50-70 | 🟡 Building | New account, early-stage opportunity |
| **RetailPlus Inc** | 45-65 | 🟡 Needs Attention | Gaps in activity, stalled opportunity |
| **Email Test Corp** | 50-75 | 🟡 Variable | Email tracking test data |
| **DownTrend Corp** | 15-35 | 🔴 At Risk | Missed calls, lost opportunity |

## 🔍 Testing Focus Areas

### Activity Frequency Component (40% Weight)
- **High Score**: TechCorp, Enterprise Global (recent, frequent)
- **Medium Score**: FreshStart, RetailPlus (moderate activity)  
- **Low Score**: DownTrend (missed calls, old data)

### Email Engagement Component (30% Weight)
- **Primary Test**: Email Test Corp (sent/received tracking)
- **Secondary**: All accounts have email addresses for testing

### Opportunity Progress Component (30% Weight)
- **Progressing**: TechCorp (75%), Enterprise Global (90% + 65%)
- **Stalled**: RetailPlus (25%)
- **Lost**: DownTrend (0% - Closed Lost)
- **Early**: FreshStart (50%)

## 📈 Expected Health Score Calculations

### TechCorp Solutions (Expected: 85-95)
- **Activities**: 35-38/40 (recent calls, meetings, notes)
- **Email**: 25-28/30 (active engagement)
- **Opportunities**: 25-27/30 (high-value, progressing)

### Enterprise Global Inc (Expected: 90-100)
- **Activities**: 38-40/40 (very frequent, high-quality)
- **Email**: 27-30/30 (executive-level engagement)
- **Opportunities**: 27-30/30 (multiple high-value deals)

### DownTrend Corp (Expected: 15-35)
- **Activities**: 5-10/40 (old, missed activities)
- **Email**: 3-8/30 (unresponsive patterns)
- **Opportunities**: 0-5/30 (lost deals)

## ✅ Verification Queries

```sql
-- Verify data creation
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
SELECT 'Notes', COUNT(*) FROM notes WHERE id LIKE 'test-note-%';

-- Expected Results:
-- Accounts: 6, Contacts: 6, Opportunities: 8
-- Calls: 12, Meetings: 5, Notes: 5
```

## 🧪 Testing Checklist

- [ ] Execute SQL script successfully
- [ ] Verify all 6 accounts created
- [ ] Check health score calculations appear
- [ ] Validate score ranges match expectations
- [ ] Test visual indicators (red/yellow/green)
- [ ] Verify activity component scoring
- [ ] Test email engagement scoring  
- [ ] Check opportunity progress scoring
- [ ] Test edge cases (new accounts, lost opportunities)

## 🔧 Troubleshooting

**No test data visible?**
- Check database connection
- Verify SQL script executed without errors
- Ensure user permissions for test data

**Health scores not calculating?**
- Verify health score algorithm is implemented
- Check custom fields exist on accounts
- Ensure logic hooks are active

**Unexpected score ranges?**
- Review algorithm weights (40/30/30)
- Check date calculations (recent vs old activities)
- Verify opportunity stage mappings

## 🧹 Cleanup

```sql
-- Remove all test data
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
``` 