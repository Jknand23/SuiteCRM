# controller.php Documentation

/**
 * @fileoverview Alerts Controller implementation for handling alert actions in SuiteCRM
 * @package Alerts
 * @copyright SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `AlertsController` class extends `SugarController` to provide MVC controller functionality for the Alerts module. It handles HTTP requests for alert operations including retrieval, creation, status updates, and navigation redirects. This controller manages the complete alert lifecycle from creation through user interaction.

## Database Operations

### Alert Retrieval
```php
$bean->get_full_list(
    "alerts.date_entered", 
    "alerts.assigned_user_id = '".$current_user->id."' AND is_read != '1'"
)
```
- **Purpose**: Fetches unread alerts for current user
- **Ordering**: By date_entered (newest first)
- **Filtering**: User-specific and unread only
- **Security**: Automatic user isolation through assigned_user_id

### Alert Creation
```php
$bean = BeanFactory::newBean('Alerts');
$bean->save();
```
- **Process**: Instantiate new Alert bean and populate fields
- **Validation**: Checks for existing reminder-based alerts to prevent duplicates
- **Persistence**: Standard SugarBean save operation

### Status Updates
```php
$bean->is_read = 1;
$bean->save();
```
- **Purpose**: Marks alerts as read when user interacts
- **Implementation**: Simple boolean flag update
- **Persistence**: Immediate save to database

## Internal API Calls

### Controller Actions

#### action_get()
```php
public function action_get()
```
- **Purpose**: Retrieves unread alerts for current user
- **Authentication**: Uses global $current_user for security
- **Output**: Sets view object map with results and flash messages
- **View**: Sets view to 'default' for rendering
- **Error Handling**: Sets flash message when no alerts found

**Data Flow**:
1. Get Alert bean instance
2. Query unread alerts for current user
3. Set results in view object map
4. Set appropriate flash message
5. Configure view for rendering

#### action_add()
```php
public function action_add()
```
- **Purpose**: Creates new alert records
- **Method**: Processes POST data for alert creation
- **Duplicate Prevention**: Checks existing reminder-based alerts
- **Response**: JSON response indicating success/failure
- **View**: Sets view to 'ajax' for AJAX responses

**Input Parameters**:
- `name`: Alert title
- `description`: Alert content
- `is_read`: Read status (default: 0)
- `url_redirect`: Navigation URL
- `target_module`: Related module
- `type`: Alert type (default: 'info')
- `reminder_id`: Optional reminder reference

**Processing Logic**:
1. Extract POST parameters with defaults
2. Generate unique URL if none provided
3. Check for duplicate reminder alerts
4. Create new alert if no duplicate exists
5. Return JSON success indicator

#### action_markAsRead()
```php
public function action_markAsRead()
```
- **Purpose**: Marks specific alert as read
- **Method**: GET parameter with record ID
- **Security**: Loads alert by ID (inherits ACL checking)
- **Response**: JSON view for AJAX handling
- **Side Effects**: Updates database immediately

**Process Flow**:
1. Load alert bean by record ID
2. Set is_read flag to 1
3. Save changes to database
4. Set JSON view for response

#### action_redirect()
```php
public function action_redirect()
```
- **Purpose**: Handles alert click navigation
- **Functionality**: Marks as read and redirects user
- **Fallback Logic**: Multiple redirect options for robustness
- **State Change**: Updates read status before redirect

**Redirect Priority**:
1. Alert's url_redirect field
2. HTTP_REFERER header
3. Default to index.php

## External API Calls

### SugarApplication Integration
```php
SugarApplication::redirect($redirect_url);
```
- **Purpose**: Handles HTTP redirects after alert interaction
- **Security**: Uses SuiteCRM's redirect handling for safety
- **Functionality**: Supports both relative and absolute URLs

### BeanFactory Integration
```php
BeanFactory::getBean('Alerts', $_GET['record']);
BeanFactory::newBean('Alerts');
```
- **Purpose**: Standard SuiteCRM bean instantiation
- **Security**: Inherits BeanFactory ACL and validation
- **Performance**: Optimized bean creation and retrieval

### Global State Access
```php
global $current_user, $app_strings;
```
- **current_user**: Authentication and user context
- **app_strings**: Internationalized application strings
- **Security**: Standard SuiteCRM authentication model

## UI Functionality

### View Configuration
```php
$this->view_object_map['Flash'] = '';
$this->view_object_map['Results'] = $results;
$this->view = 'default';
```
- **Flash Messages**: User feedback for empty states
- **Results**: Alert data for display rendering
- **View Selection**: Determines rendering template

### AJAX Response Handling
```php
echo json_encode(['result' => (int)$shouldShowReminderPopup], true);
```
- **Format**: JSON responses for AJAX interactions
- **Data**: Boolean indicators for client-side processing
- **Integration**: Supports JavaScript alert management

### Alert Display Features
- **User Isolation**: Only shows alerts assigned to current user
- **Status Indication**: Visual distinction for read/unread states
- **Interactive Elements**: Click-to-read and navigation functionality
- **Empty State Handling**: Graceful messaging when no alerts exist

## Security Features

### User Authentication
- **Global User Check**: Uses $current_user for all operations
- **Session Validation**: Relies on SuiteCRM authentication
- **Authorization**: Bean-level ACL checking through BeanFactory

### Data Isolation
- **User Filtering**: All queries filter by assigned_user_id
- **Record Access**: ACL checking on individual alert access
- **Input Validation**: Standard POST/GET parameter handling

### SQL Injection Prevention
```php
"alerts.assigned_user_id = '".$current_user->id."'"
$bean->db->quote($reminder_id)
```
- **User ID**: Uses authenticated user ID directly
- **Parameter Quoting**: Database-level parameter escaping
- **ORM Protection**: SugarBean ORM provides additional protection

## Performance Considerations

### Database Optimization
- **Targeted Queries**: User-specific filtering reduces result sets
- **Index Usage**: assigned_user_id and is_read likely indexed
- **Minimal Joins**: Direct table access without complex relationships

### Caching Strategy
- **No Explicit Caching**: Real-time alert data for immediate updates
- **Session Efficiency**: Minimal global state access
- **Bean Optimization**: Single bean operations for efficiency

### Memory Management
- **Lightweight Operations**: Minimal object instantiation
- **Immediate Cleanup**: Objects go out of scope quickly
- **View Efficiency**: Targeted data passing to views

## Error Handling

### Input Validation
- **Parameter Checking**: isset() checks for optional parameters
- **Type Safety**: Appropriate type casting and defaults
- **Boundary Conditions**: Handles missing or invalid input gracefully

### Database Errors
- **Bean Error Handling**: SugarBean provides error management
- **Transaction Safety**: Individual operations with immediate feedback
- **Rollback Capability**: Bean save operations can be validated

### Redirect Safety
- **Fallback Chain**: Multiple redirect options prevent user stranding
- **URL Validation**: SugarApplication provides redirect security
- **Error Recovery**: Default redirect ensures user navigation

## Integration Points

### Reminder System Integration
- **Duplicate Prevention**: Checks existing reminder-based alerts
- **Lifecycle Management**: Alerts persist beyond reminder lifecycle
- **Data Correlation**: reminder_id links maintain relationship

### Module System Integration
- **Cross-Module Alerts**: target_module enables module-specific handling
- **Navigation Context**: URL redirect supports deep linking
- **Global Notification**: Alerts can originate from any module

### JavaScript Integration
- **AJAX Responses**: JSON format for client-side processing
- **Popup Control**: Boolean flags control reminder popup display
- **User Interaction**: Supports rich client-side alert management

## API Endpoints

### GET Requests
- **action_get**: Retrieve user alerts
- **action_markAsRead**: Mark alert as read
- **action_redirect**: Navigate and mark as read

### POST Requests
- **action_add**: Create new alert

### Response Formats
- **HTML**: Default view rendering
- **JSON**: AJAX response format
- **Redirect**: HTTP redirect responses 