# AccountsJjwg_MapsLogicHook.php Documentation

## @fileoverview
Logic hook integration for jjwg_Maps module that provides automatic geocoding functionality for Account records and their related entities through comprehensive address-based coordinate updating.

## @package SuiteCRM\Modules\Accounts
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This class provides logic hook integration between the Accounts module and the jjwg_Maps module, enabling automatic geocoding of account addresses and cascading geocode updates to related records. It ensures that geographical coordinates are maintained for accounts and their related entities.

## Database Operations

### Address Geocoding
- **Account Address Processing**: Automatically geocodes account billing and shipping addresses
- **Coordinate Updates**: Updates latitude/longitude coordinates in jjwg_maps custom fields
- **Address Change Detection**: Compares current address with fetched row to determine if geocoding is needed

### Related Entity Updates
- **Project Geocoding**: Updates geocode information for related projects
- **Opportunity Geocoding**: Updates geocode information for related opportunities  
- **Case Geocoding**: Updates geocode information for related cases
- **Meeting Geocoding**: Updates geocode information for related meetings

### Custom Field Integration
- **jjwg_maps_address_c**: Custom field containing formatted address for geocoding
- **Custom Field Retrieval**: Retrieves custom fields before geocoding operations
- **Field Comparison**: Compares current vs. fetched values to determine update necessity

## Internal API Calls

### jjwg_Maps Integration
- **`get_module_info('jjwg_Maps')`**: Retrieves jjwg_Maps module instance
- **`$this->jjwg_Maps->updateGeocodeInfo()`**: Core geocoding method calls
- **`$this->jjwg_Maps->settings['logic_hooks_enabled']`**: Setting validation for hook execution

### Bean Relationship Management
- **`$bean->get_linked_beans()`**: Retrieves related records for geocoding updates
- **Relationship Types**: projects, opportunities, cases, meetings
- **Bean Loading**: Dynamic loading of related entity beans

### Logic Hook Events
- **before_save**: Account geocoding before record save
- **after_save**: Related entity geocoding after account save
- **after_relationship_add**: Geocoding when relationships are added
- **after_relationship_delete**: Geocoding when relationships are removed

## External API Calls

### Module Loading
- **`require_once()`**: Dynamic loading of related module classes
- **Project Module**: `modules/Project/Project.php`
- **Opportunity Module**: `modules/Opportunities/Opportunity.php`
- **Case Module**: `modules/Cases/Case.php`

### Bean Factory Integration
- **`get_module_info()`**: Retrieves module information for relationship processing
- **Dynamic Bean Creation**: Creates beans for relationship-based geocoding updates

## UI Functionality

### Automatic Geocoding
- **Transparent Operation**: Geocoding occurs automatically without user intervention
- **Address Updates**: Responds to address field changes in account forms
- **Related Record Updates**: Cascades address updates to related entities

### Settings Integration
- **Logic Hook Control**: Respects jjwg_Maps settings for enabling/disabling hooks
- **Performance Management**: Conditional execution based on module settings
- **Administrative Control**: Allows administrators to control geocoding behavior

## Hook Implementation

### Core Hook Methods

#### `updateGeocodeInfo($bean, $event, $arguments)`
- **Event**: before_save
- **Purpose**: Updates account geocode information before saving
- **Logic**: Checks settings and calls jjwg_Maps geocoding

#### `updateRelatedProjectGeocodeInfo($bean, $event, $arguments)`
- **Event**: after_save
- **Purpose**: Updates related project geocode information
- **Logic**: Retrieves linked projects and updates their geocode data

#### `updateRelatedOpportunitiesGeocodeInfo($bean, $event, $arguments)`
- **Event**: after_save
- **Purpose**: Updates related opportunity geocode information
- **Logic**: Retrieves linked opportunities and updates their geocode data

#### `updateRelatedCasesGeocodeInfo($bean, $event, $arguments)`
- **Event**: after_save
- **Purpose**: Updates related case geocode information
- **Logic**: Retrieves linked cases and updates their geocode data

#### `updateRelatedMeetingsGeocodeInfo($bean, $event, $arguments)`
- **Event**: after_save
- **Purpose**: Updates related meeting geocode information
- **Logic**: Delegates to jjwg_Maps for meeting geocoding updates

### Relationship Hook Methods

#### `addRelationship($bean, $event, $arguments)`
- **Event**: after_relationship_add
- **Purpose**: Updates geocode when relationships are added
- **Parameters**: module, related_module, id, related_id
- **Logic**: Retrieves and geocodes the related entity

#### `deleteRelationship($bean, $event, $arguments)`
- **Event**: after_relationship_delete
- **Purpose**: Updates geocode when relationships are removed
- **Parameters**: module, related_module, id, related_id
- **Logic**: Retrieves and geocodes the affected entity

## Performance Optimization

### Conditional Execution
- **Settings Check**: Validates logic_hooks_enabled before processing
- **Change Detection**: Only updates when address fields have changed
- **Efficient Queries**: Uses get_linked_beans() for optimized relationship queries

### Batch Processing
- **Related Entity Updates**: Processes all related entities of a type together
- **Custom Field Retrieval**: Retrieves custom fields once per entity
- **Save Optimization**: Uses save(false) to prevent recursive logic hook execution

## Integration Points

### jjwg_Maps Module
- **Core Dependency**: Requires jjwg_Maps module for geocoding functionality
- **Settings Integration**: Respects jjwg_Maps configuration settings
- **API Integration**: Uses jjwg_Maps API methods for geocoding operations

### SuiteCRM Logic Hook Framework
- **Hook Registration**: Integrates with SuiteCRM logic hook system
- **Event Handling**: Responds to standard SuiteCRM events
- **Bean Integration**: Works with SuiteCRM bean architecture

### Related Modules
- **Project Integration**: Updates project location data
- **Opportunity Integration**: Updates opportunity location data
- **Case Integration**: Updates case location data
- **Meeting Integration**: Updates meeting location data

## Security and Validation

### Settings Validation
- **Hook Enablement**: Validates that logic hooks are enabled before execution
- **Module Availability**: Checks for jjwg_Maps module availability
- **Safe Execution**: Prevents execution when geocoding is disabled

### Data Integrity
- **Change Detection**: Ensures geocoding only occurs when needed
- **Custom Field Safety**: Safely handles custom field retrieval and updates
- **Error Prevention**: Prevents recursive save operations through save(false) 