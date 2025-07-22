# MassUpdate.php Documentation

/**
 * @fileoverview MassUpdate class - Comprehensive bulk update functionality for multiple records with form generation, field processing, security validation, and database operations
 * @package SuiteCRM\Core
 * @copyright SalesAgility Ltd
 * @license AGPL-3.0
 */

## Overview

The MassUpdate class provides comprehensive functionality for performing bulk operations on multiple records in SuiteCRM. It handles form generation, field processing, security validation, database operations, and user interface elements for mass updating records. The class supports various field types, relationship handling, and integrates with the search framework for record filtering.

## Class Structure

```php
class MassUpdate
{
    public $sugarbean = null;           // Target SugarBean instance
    public $where_clauses = '';         // WHERE clauses for filtering
    public $searchFields = array();     // Search field definitions
    public $use_old_search = true;      // Search form version flag
    
    // Core functionality methods
    public function setSugarBean($sugar)
    public function handleMassUpdate()
    public function getMassUpdateForm()
    public function getMassUpdateFormHeader()
    // ... and many more specialized methods
}
```

## Core Functionality

### Initialization and Configuration

#### Bean Management
- **setSugarBean()**: Sets target SugarBean for mass update operations
  - Stores bean reference for field definition access
  - Used by all form generation and processing methods

#### Form Display Control
- **getDisplayMassUpdateForm()**: Generates toggle form for mass update interface
  - Supports multi-select popup mode
  - Controls visibility of mass update interface
  - Includes hidden fields for state management

### Form Generation and Processing

#### Form Header Generation
- **getMassUpdateFormHeader()**: Creates mass update form opening elements
  - Handles request parameter preservation via JSON encoding
  - Manages pagination and ordering state
  - Special handling for Email module with type/owner parameters
  - Security: Removes session data from preserved parameters

#### Main Form Generation
- **getMassUpdateForm()**: Generates complete mass update form interface
  - Processes all available fields for mass update capability
  - Handles field-specific HTML generation
  - Includes delete functionality with ACL checking
  - Configurable hiding of delete button when no fields available

#### Form Closure
- **endMassUpdateForm()**: Returns form closing tag
  - Simple utility method for consistent form structure

### Database Operations

#### Mass Update Processing
- **handleMassUpdate()**: Core mass update execution engine
  - Processes POST data with comprehensive field validation
  - Handles both selected records (uid) and entire result sets
  - Supports delete operations with ACL verification
  - Special Contact sync functionality for user relationships
  - Email address opt-in/opt-out handling
  - Dynamic enum field synchronization

#### Data Processing Flow
1. **Field Validation**: POST data cleaning and type conversion
2. **Record Selection**: Process uid list or generate from WHERE clauses
3. **ACL Verification**: Check permissions for each operation
4. **Field Processing**: Handle special field types and relationships
5. **Database Updates**: Execute save operations with notifications
6. **Email Handling**: Process primary email address settings

### Field Type Handling

#### Boolean Field Processing
- **checkClearField()**: Handles boolean field clearing logic
  - Processes special clear field values
  - Maintains proper boolean state representation

#### Date/Time Field Processing
- **Date Fields**: Converts user format to database format using TimeDate
- **DateTime Fields**: Handles datetime combo processing
- **date_to_dateTime()**: Utility for date to datetime conversion

#### Special Field Handling
- **Dynamic Enum Fields**: Synchronizes dependent dropdown values
- **Email Fields**: Primary email opt-in/opt-out functionality
- **Relationship Fields**: Parent/child relationship updates
- **Reports To**: Handles hierarchical relationship updates

### User Interface Components

#### Relationship Field Handlers
- **handleRelationship()**: Routes relationship fields to appropriate handlers
  - Supports multiple modules: Accounts, Contacts, Users, etc.
  - Special handling for reports_to relationships
  - Generic module relationship support

#### Module-Specific Popup Generators
- **addAccountID()**: Account selection popup with Smart Quick Search
- **addAssignedUserID()**: User assignment popup with SQS integration
- **addGenericModuleID()**: Generic module selection popup
- **addUserName()**: User name selection for reports_to fields
- **addParent()**: Parent record selection with type dropdown

#### Parent Record Handling
- **addParent()**: Complex parent selection interface
  - Dynamic module type selection
  - Popup integration for record selection
  - Smart Quick Search integration
  - ACL-aware module type filtering

### Search Integration

#### WHERE Clause Generation
- **generateSearchWhere()**: Generates WHERE clauses from search criteria
  - Supports both legacy and modern search forms
  - Integrates with SearchForm and SearchForm2 classes
  - Handles modules without search capabilities
  - Uses session-stored WHERE clauses as fallback

#### Search Form Processing
- **getSearchDefs()**: Loads search definitions from metadata
  - Checks custom metadata overrides first
  - Falls back to default module metadata
  - Supports metafiles.php configuration

- **getSearchFields()**: Loads search field definitions
  - Custom SearchFields.php override support
  - Module-specific field configuration loading

### Field Processing Utilities

#### Function Field Handling
- **getFunctionValue()**: Executes function-based field definitions
  - Supports both string and array function definitions
  - Handles HTML-returning functions with includes
  - Uses call_user_func for dynamic function execution

#### Field Availability Detection
- **doMassUpdateFieldsExistForFocus()**: Determines if mass update fields exist
  - Excludes system fields (date_modified, created_by, etc.)
  - Checks massupdate field attribute
  - Supports wide range of updateable field types
  - Returns boolean indicating mass update availability

### Security and Validation

#### Access Control Integration
- **ACL Verification**: Each operation checked against user permissions
  - Delete operations require Delete access
  - Save operations require Save access
  - Per-record permission verification

#### Data Validation
- **Field Type Validation**: Ensures data matches field definitions
- **Required Field Handling**: Processes required field constraints
- **Special Field Processing**: Handles complex field relationships

#### Input Sanitization
- **POST Data Cleaning**: Removes empty values appropriately
- **Type Conversion**: Converts data to proper field types
- **Security Filtering**: Prevents injection through input validation

## Database Operations

### Mass Update Execution
- **Record Iteration**: Processes each selected record individually
- **Bean Recreation**: Creates fresh bean instances for each update
- **Change Tracking**: Monitors field changes for notifications
- **Transaction Safety**: Each record updated in separate transaction

### Query Generation
- **Dynamic WHERE Clauses**: Built from search form criteria
- **Record Selection**: Supports both explicit IDs and query-based selection
- **Optimization**: Filters unnecessary table joins when possible

## User Interface Features

### Popup Integration
- **Smart Quick Search**: Integrated SQS for relationship fields
- **Multi-Module Support**: Handles various module types
- **Callback Functions**: JavaScript callbacks for popup selection
- **Form Integration**: Seamless integration with form processing

### JavaScript Integration
- **SQS Object Registration**: Automatic Smart Quick Search setup
- **Validation Dependencies**: Field validation rule registration
- **Popup Window Management**: Standardized popup window handling

## Integration Points

### Core Framework Integration
- **SugarBean**: Deep integration with bean field definitions
- **EditView2**: Leverages edit view functionality
- **SearchForm**: Integrates with search framework
- **TimeDate**: Date/time format conversion
- **ACLController**: Security permission checking

### Global Variables and Configuration
- **$_POST/$_REQUEST**: Form data processing
- **$app_strings**: Localized string access
- **$app_list_strings**: Dropdown option lists
- **$sugar_config**: Configuration setting access
- **Global Database**: Direct database query execution

### External Dependencies
- **formbase.php**: Form processing utilities
- **JSON Utilities**: Data encoding for JavaScript integration
- **BeanFactory**: Bean instantiation and management
- **LoggerManager**: Error and debug logging

## Performance Considerations

### Optimization Strategies
- **Selective Field Processing**: Only processes changed fields
- **Bean Instance Management**: Efficient bean creation/destruction
- **Query Optimization**: Minimizes database queries where possible
- **Memory Management**: Cleans up resources during processing

### Scalability Features
- **Batch Processing**: Handles large record sets efficiently
- **Memory-Conscious**: Processes records individually to manage memory
- **Error Recovery**: Continues processing despite individual record failures

## Error Handling

### Validation Errors
- **Field Type Mismatches**: Graceful handling of invalid data
- **Required Field Violations**: Clear error messaging
- **Relationship Validation**: Ensures valid relationship references

### Database Errors
- **Save Failures**: Individual record error handling
- **Permission Errors**: ACL violation management
- **Data Integrity**: Maintains database consistency

### User Interface Errors
- **JavaScript Errors**: Graceful degradation when JS fails
- **Popup Failures**: Alternative input methods available
- **Form Validation**: Client and server-side validation

## Usage Patterns

### Basic Mass Update
```php
$massUpdate = new MassUpdate();
$massUpdate->setSugarBean($bean);
$massUpdate->where_clauses = $whereClause;
$massUpdate->handleMassUpdate();
```

### Form Generation
```php
$massUpdate = new MassUpdate();
$massUpdate->setSugarBean($bean);
$formHeader = $massUpdate->getMassUpdateFormHeader();
$formBody = $massUpdate->getMassUpdateForm();
$formEnd = $massUpdate->endMassUpdateForm();
```

### Search Integration
```php
$massUpdate = new MassUpdate();
$massUpdate->generateSearchWhere($module, $searchQuery);
$whereClause = $massUpdate->where_clauses;
```

### Field Availability Check
```php
$massUpdate = new MassUpdate();
$massUpdate->setSugarBean($bean);
$hasFields = $massUpdate->doMassUpdateFieldsExistForFocus();
```

## Field Type Support

### Basic Field Types
- **Text/Varchar**: Standard text input processing
- **Integer**: Numeric validation and conversion
- **Boolean**: True/false/null state handling
- **Date/DateTime**: Timezone and format conversion

### Complex Field Types
- **Enum/Multienum**: Dropdown and multi-select processing
- **Dynamic Enum**: Parent-dependent dropdown handling
- **Radio Enum**: Radio button group processing

### Relationship Fields
- **Relate**: Related record connections
- **Parent**: Polymorphic parent relationships
- **Assigned User**: User assignment relationships
- **Account/Contact**: Specific module relationships

## Dependencies

### Core Dependencies
- SugarBean class for field definitions and save operations
- EditView2 for form generation utilities
- SearchForm/SearchForm2 for query generation
- TimeDate for date/time processing
- ACLController for permission management

### External Dependencies
- formbase.php for form processing utilities
- JavaScript libraries for popup and SQS functionality
- JSON utilities for data serialization
- Global configuration and language systems
- Database abstraction layer for query execution 