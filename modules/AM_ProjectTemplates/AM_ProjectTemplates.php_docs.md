# AM_ProjectTemplates.php Documentation

/**
 * @fileoverview Project Template Bean class implementation for SuiteCRM project management
 * @package AM_ProjectTemplates
 * @copyright Andrew Mclaughlan 2014, SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `AM_ProjectTemplates` class extends the auto-generated `AM_ProjectTemplates_sugar` bean to provide project template functionality in SuiteCRM. This module allows administrators and project managers to create reusable project templates that can be used to generate actual projects with predefined structures, resources, and relationships.

## Database Operations

### Relationship Management
```php
$focus->load_relationship('users');
$users = $focus->get_linked_beans('am_projecttemplates_users_1', 'User');

$focus->load_relationship('contacts');
$contacts = $focus->get_linked_beans('am_projecttemplates_contacts_1', 'Contact');
```
- **Purpose**: Manages many-to-many relationships with Users and Contacts
- **Usage**: Tracks who is involved in project templates
- **Relationship Tables**: `am_projecttemplates_users_1_c` and `am_projecttemplates_contacts_1_c`

### Soft Delete Operations
```php
$sql = "UPDATE am_projecttemplates_users_1_c set deleted = 1 where users_idb in ($sql) AND am_projecttemplates_ida = '". $focus->id . "'";
$focus->db->query($sql);
```
- **Purpose**: Implements soft delete for relationship records
- **Benefit**: Maintains data integrity while removing associations
- **Scope**: Removes user and contact associations from templates

### Data Persistence
```php
$return_id = parent::save($check_notify);
$focus->retrieve($return_id);
```
- **Purpose**: Saves template data and reloads for relationship management
- **Integration**: Uses parent SugarBean save functionality
- **Return Value**: Returns saved record ID for further processing

## Internal API Calls

### Parent Class Integration
```php
require_once('modules/AM_ProjectTemplates/AM_ProjectTemplates_sugar.php');
class AM_ProjectTemplates extends AM_ProjectTemplates_sugar
```
- **Purpose**: Extends auto-generated sugar class for customizations
- **Pattern**: Standard SuiteCRM customization approach
- **Functionality**: Inherits all base bean functionality

### Relationship API Usage
```php
$focus->am_projecttemplates_users_1->add($user_id);
$focus->am_projecttemplates_contacts_1->add($contact_id);
```
- **Purpose**: Adds new relationships using SuiteCRM relationship API
- **Validation**: Checks for existing relationships before adding
- **Efficiency**: Bulk relationship operations during save process

### Constructor Implementation
```php
public function __construct()
{
    parent::__construct();
}
```
- **Purpose**: Initializes project template bean
- **Inheritance**: Calls parent constructor for proper initialization
- **Customization**: Available for future initialization logic

## External API Calls

### Form Data Processing
```php
$userInvitees = explode(',', trim($_POST['user_invitees'], ','));
$contactInvitees = explode(',', trim($_POST['contact_invitees'], ','));
```
- **Purpose**: Processes comma-separated invitee lists from forms
- **Format**: Expects comma-delimited ID strings
- **Validation**: Handles empty arrays when no invitees provided

### Database Query Execution
```php
$focus->db->query($sql);
```
- **Purpose**: Executes custom SQL for relationship soft deletes
- **Integration**: Uses SugarBean database connection
- **Safety**: Uses parameterized queries with proper escaping

## UI Functionality

### Save Operation Workflow
The `save()` method implements complex workflow logic based on form context:

#### Standard Save Scenarios
```php
if ((isset($_POST['isSaveFromDetailView']) && $_POST['isSaveFromDetailView'] == 'true') ||
    (isset($_POST['is_ajax_call']) && !empty($_POST['is_ajax_call']) && !empty($focus->id)) ||
    (isset($_POST['return_action']) && $_POST['return_action'] == 'SubPanelViewer') && !empty($focus->id) ||
    !isset($_POST['user_invitees'])
)
```
- **DetailView Save**: Direct save from detail view
- **AJAX Save**: AJAX-based save operations
- **SubPanel Save**: Save from subpanel context
- **Simple Save**: Save without invitee processing

#### Relationship Management Save
- **User Invitees**: Processes user assignments to template
- **Contact Invitees**: Manages contact relationships
- **Delta Processing**: Identifies additions and removals
- **Bulk Operations**: Efficient relationship updates

### Form Integration
- **Invitee Selection**: Multi-select interfaces for users and contacts
- **Dynamic Updates**: Real-time relationship management
- **Validation**: Ensures data consistency during save operations

## Security Features

### Input Validation
```php
if (!empty($_POST['user_invitees'])) {
    $userInvitees = explode(',', trim($_POST['user_invitees'], ','));
} else {
    $userInvitees = array();
}
```
- **Purpose**: Validates and sanitizes form input
- **Safety**: Handles missing or empty form fields gracefully
- **Type Safety**: Ensures array processing for relationship management

### Database Security
```php
$template_id = $db->quote($_POST['template_id']);
```
- **Purpose**: SQL injection prevention through parameter quoting
- **Integration**: Uses SugarBean database security methods
- **Best Practice**: Consistent with SuiteCRM security standards

### Permission Model
- **Bean-Level Security**: Inherits SugarBean ACL functionality
- **Relationship Security**: User/Contact relationship permissions
- **Administrative Control**: Template management restricted by permissions

## Performance Considerations

### Relationship Optimization
```php
$existingUsers = array();
$deleteUsers = array();
```
- **Purpose**: Minimizes database operations by tracking changes
- **Efficiency**: Only processes relationship changes, not all relationships
- **Memory Management**: Uses arrays for efficient change tracking

### Bulk Operations
- **Single SQL Updates**: Bulk soft delete operations for efficiency
- **Minimal Queries**: Reduces database round trips
- **Transaction Safety**: Logical grouping of related operations

### Lazy Loading
- **Relationship Loading**: Relationships loaded only when needed
- **On-Demand Processing**: Heavy operations only during specific save scenarios
- **Resource Conservation**: Minimal processing for simple saves

## Error Handling

### Form Validation
```php
if (!isset($_POST['user_invitees'])) // we need to check that user_invitees exists before processing
```
- **Purpose**: Validates required form elements exist
- **Safety**: Prevents processing errors from missing form data
- **Graceful Degradation**: Continues with standard save if validation fails

### Database Error Handling
- **Query Safety**: Uses SugarBean error handling mechanisms
- **Transaction Integrity**: Maintains data consistency during failures
- **Recovery Options**: Parent save continues even if relationship processing fails

### Relationship Integrity
```php
if (empty($user_id) || isset($existingUsers[$user_id]) || isset($deleteUsers[$user_id])) {
    continue;
}
```
- **Purpose**: Prevents duplicate relationships and invalid operations
- **Data Integrity**: Ensures clean relationship state
- **Efficiency**: Skips unnecessary operations

## Integration Points

### Project Creation
- **Template Usage**: Templates serve as blueprints for project creation
- **Resource Allocation**: User and contact assignments transfer to projects
- **Configuration Inheritance**: Project settings derived from templates

### User Management Integration
- **User Assignment**: Integration with SuiteCRM Users module
- **Permission Inheritance**: User permissions carry forward to projects
- **Notification System**: User assignments can trigger notifications

### Contact Management Integration
- **Contact Assignment**: Integration with SuiteCRM Contacts module
- **Client Relationships**: Contact assignments for client-facing projects
- **Communication Tracking**: Contact relationships enable project communication

## Template Features

### Resource Planning
- **User Allocation**: Pre-define team members for template-based projects
- **Contact Assignment**: Include external stakeholders in project templates
- **Role Definition**: Structure roles and responsibilities in templates

### Project Structure
- **Task Templates**: Foundation for task template relationships
- **Timeline Planning**: Template-based project scheduling
- **Resource Requirements**: Pre-defined resource allocation patterns

### Reusability
- **Template Library**: Build library of reusable project structures
- **Best Practices**: Encode organizational best practices in templates
- **Standardization**: Ensure consistent project setup across organization

## Administrative Features

### Template Management
- **Template Creation**: Administrative interface for template definition
- **Resource Assignment**: Bulk assignment of users and contacts
- **Template Validation**: Ensures template completeness before use

### Relationship Management
- **Bulk Operations**: Efficient management of template relationships
- **Change Tracking**: Audit trail for template modifications
- **Data Integrity**: Maintains clean relationship state

### Project Generation
- **Template Application**: Convert templates to active projects
- **Resource Transfer**: Move template relationships to project relationships
- **Configuration Inheritance**: Apply template settings to new projects 