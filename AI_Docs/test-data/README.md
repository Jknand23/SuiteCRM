# Adding Test Campaign Data

To see the Campaign Progress dashlet in action, you need some test data in your database.

## Option 1: Using SuiteCRM UI (Recommended)

1. **Create Campaigns:**
   - Go to Marketing → Campaigns
   - Click "Create Campaign"
   - Fill in:
     - Name: "Digital Marketing Q1 2025"
     - Status: "Active"
     - Type: "Email"
     - Budget: $50,000
     - Actual Cost: $15,000
   - Save
   - Repeat for 2-3 more campaigns with different budgets

2. **Create Leads and Associate with Campaigns:**
   - Go to Sales → Leads
   - Create several test leads
   - For each lead, go to the Campaign subpanel and relate them to a campaign

## Option 2: Using SQL Script

Since you're using Docker [[memory:4282330]], you can execute the SQL script directly:

```bash
# Copy the SQL file to the container
docker cp AI_Docs/test-data/campaign-test-data.sql [container-name]:/tmp/

# Execute the SQL script
docker exec -it [container-name] mysql -u root -p suitecrm < /tmp/campaign-test-data.sql
```

Replace `[container-name]` with your actual Docker container name.

## Option 3: Manual Database Entry

You can also manually run SQL queries in your database management tool to insert test data.

## What the Test Data Includes

- 5 test campaigns (4 active, 1 inactive)
- Budget data ranging from $15,000 to $50,000
- Various budget utilization percentages
- 7 test leads distributed across campaigns
- Campaign log entries spanning the last 7 days

After adding the test data, refresh your home page and the Campaign Progress dashlet should display:
- Total new leads count
- Active campaigns count
- Budget utilization percentage
- Performance trend chart
- List of campaigns with progress bars 