# Enabling Health Score Columns in List Views

The health score fields and columns have been created and the system has been rebuilt. To make them visible in your list views, follow one of these methods:

## Method 1: Through Studio (Recommended)

1. **Login as Administrator** to your SuiteCRM instance

2. **Navigate to Admin → Studio**
   - URL: `http://localhost:8080/index.php?module=Administration&action=index`

3. **Configure Accounts List View**:
   - Click on **Accounts** module
   - Click on **Layouts**
   - Click on **List View**
   - From the "Hidden" column on the right, find:
     - **Health Status** (This is set as default=true, so it might already be visible)
     - **Health Score** (This is set as default=false, so you need to add it)
   - Drag these fields to the "Default" column on the left
   - Position them where you want them to appear
   - Click **Save & Deploy**

4. **Configure Contacts List View** (if implemented):
   - Repeat the same process for the Contacts module

## Method 2: Check List View Display Options

If the columns are available but not displayed:

1. **Go to Accounts List View**
   - Navigate to the Accounts module list view

2. **Check Column Selector** (if available in your theme):
   - Look for a column selector icon (usually gear/settings icon)
   - Select "Health Score" and "Health Status" to display them

## Method 3: Direct Configuration (Alternative)

If Studio doesn't show the fields, you might need to add them directly to the list view configuration:

1. The fields have been added via the extension framework at:
   - `/custom/Extension/modules/Accounts/Ext/Listview/health_score_listview.php`

2. After rebuild, these should be merged into:
   - `/custom/modules/Accounts/metadata/listviewdefs.php`

## Troubleshooting

If columns still don't appear:

1. **Check Browser Cache**:
   - Hard refresh the page (Ctrl+F5 or Cmd+Shift+R)
   - Try in an incognito/private window

2. **Verify Fields Exist**:
   - Go to an Account detail view
   - Check if you can see Health Score and Health Status fields
   - If not visible, go to Studio → Accounts → Layouts → Detail View and add them

3. **Check Permissions**:
   - Ensure your user role has permission to view these fields
   - Admin → Role Management → Check field permissions

4. **Manual Column Addition**:
   If needed, you can manually edit the list view by adding to the displayed columns array.

## Expected Result

Once enabled, you should see:
- **Health Status**: Color-coded badges (Green/Yellow/Red) with icons
- **Health Score**: Numeric score (0-100) with color-coded background

The Health Status column is set to display by default, while Health Score needs to be manually added to avoid cluttering existing views. 