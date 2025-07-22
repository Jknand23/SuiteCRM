# metafiles.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/metadata/metafiles.php`  
**Purpose:** Metadata file registry for AOP_Case_Updates module  
**Package:** Advanced OpenPortal  

## Configuration Structure

### Module Metadata Registry
Defines the complete metadata file mapping for the AOP_Case_Updates module to enable SuiteCRM's metadata-driven architecture.

**Global Variable:** `$metafiles[$module_name]`  
**Module Name:** `AOP_Case_Updates`

## Internal API Calls

### Metadata File Mapping
The configuration establishes the following metadata file associations:

#### View Definitions
- **detailviewdefs:** `modules/AOP_Case_Updates/metadata/detailviewdefs.php`
  - Purpose: Defines field layout and structure for detail view
  - Integration: Used by SuiteCRM's view rendering engine

- **editviewdefs:** `modules/AOP_Case_Updates/metadata/editviewdefs.php`
  - Purpose: Defines field layout and structure for edit view
  - Integration: Used by form generation and validation systems

- **listviewdefs:** `modules/AOP_Case_Updates/metadata/listviewdefs.php`
  - Purpose: Defines column layout and properties for list view
  - Integration: Used by list view controllers and sorting mechanisms

#### Search Configuration
- **searchdefs:** `modules/AOP_Case_Updates/metadata/searchdefs.php`
  - Purpose: Defines search form layouts and field configurations
  - Integration: Used by basic and advanced search interfaces

- **searchfields:** `modules/AOP_Case_Updates/metadata/SearchFields.php`
  - Purpose: Defines searchable field properties and query types
  - Integration: Used by search query builders and filters

#### Popup Configuration  
- **popupdefs:** `modules/AOP_Case_Updates/metadata/popupdefs.php`
  - Purpose: Defines popup window layouts for record selection
  - Integration: Used by relationship selection interfaces

## UI Functionality

### Metadata-Driven Architecture
This file enables SuiteCRM's metadata-driven user interface system by:

#### View Rendering
- **Purpose:** Provides file paths for view definition loading
- **Integration:** Used by SugarView classes to locate view metadata
- **Caching:** Metadata files are cached for performance optimization

#### Module Administration
- **Purpose:** Enables Studio and Module Builder customization
- **Integration:** Administration tools use this registry to locate customizable metadata
- **Extensibility:** Supports custom metadata file additions

### Framework Integration
- **MetaDataManager:** Uses this registry to load appropriate metadata files
- **SugarView Classes:** Reference these paths for view rendering
- **Search Framework:** Loads search configurations from registered files
- **Studio Integration:** Enables visual customization of views

## Configuration Management

### File Path Structure
All metadata files follow the standard SuiteCRM convention:
- **Base Path:** `modules/{module_name}/metadata/`
- **File Extension:** `.php` for all metadata files
- **Naming Convention:** Descriptive names ending with 'defs' or 'Fields'

### Module Consistency
- **Module Variable:** Uses consistent `$module_name` variable
- **Path Construction:** Dynamic path building using module name
- **Standardization:** Follows SuiteCRM metadata patterns

## Integration Points

### SuiteCRM Framework Dependencies
- **MetaDataManager:** Core metadata loading system
- **SugarView:** View rendering framework
- **Studio:** Visual customization tools
- **Module Builder:** Development and deployment tools

### Related Modules
- **Cases:** Parent module relationship
- **Contacts:** User assignment and relationships
- **Notes:** File attachment integration
- **Users:** Assignment and ownership tracking

### Development Tools
- **Quick Repair:** Uses metadata registry for cache rebuilding
- **Module Installation:** References during package deployment
- **Upgrades:** Maintains metadata file associations during updates 