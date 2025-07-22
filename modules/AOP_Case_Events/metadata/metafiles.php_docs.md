# AOP_Case_Events Metadata File Registry

**File**: `modules/AOP_Case_Events/metadata/metafiles.php`  
**Type**: PHP Configuration File  
**Purpose**: Registers and maps metadata file paths for the AOP_Case_Events module

## Overview

This file serves as a central registry for all metadata files used by the AOP_Case_Events module. It provides the system with a mapping of metadata types to their corresponding file paths, enabling SuiteCRM to locate and load the appropriate view definitions for different interfaces.

## Internal API Integration

### Metadata Registry Configuration
The file populates the global `$metafiles` array with module-specific metadata mappings:

#### Registered Metadata Files
- **`detailviewdefs`**: Maps to `detailviewdefs.php` for record detail view configuration
- **`editviewdefs`**: Maps to `editviewdefs.php` for record edit form configuration  
- **`listviewdefs`**: Maps to `listviewdefs.php` for list view column configuration
- **`searchdefs`**: Maps to `searchdefs.php` for search form layout configuration
- **`popupdefs`**: Maps to `popupdefs.php` for popup selection dialog configuration
- **`searchfields`**: Maps to `SearchFields.php` for search criteria configuration

### System Integration Points

#### MetaDataManager Integration
- Enables automatic metadata discovery by SuiteCRM's MetaDataManager
- Provides standardized metadata loading mechanism
- Supports Studio customization tools
- Facilitates module deployment and upgrades

#### View System Integration
- Links view controllers to their corresponding metadata definitions
- Enables dynamic view rendering based on metadata configuration
- Supports customization without core file modification
- Facilitates metadata caching and optimization

## UI Functionality

### View Definition Management
The registered metadata files control:
- **Detail Views**: Field layout and display configuration
- **Edit Views**: Form structure and field arrangement
- **List Views**: Column definitions and sorting options
- **Search Views**: Search form layout and criteria options
- **Popup Views**: Selection dialog behavior and display

### Studio Customization Support
The metadata registry enables:
- Drag-and-drop field arrangement in Studio
- Custom field addition and modification
- Layout customization without code changes
- View template modifications through admin interface

## Module Architecture

### Metadata File Structure
```
modules/AOP_Case_Events/metadata/
├── metafiles.php (this file - registry)
├── detailviewdefs.php (detail view layout)
├── editviewdefs.php (edit form layout)
├── listviewdefs.php (list view columns)
├── searchdefs.php (search form layout)
├── popupdefs.php (popup configuration)
└── SearchFields.php (search criteria)
```

### Path Resolution
All file paths are constructed using:
- Module base path: `modules/AOP_Case_Events/metadata/`
- Dynamic module name variable: `$module_name`
- Standardized filename conventions

## Development Integration

### Module Building
The metadata registry supports:
- Automated module packaging and deployment
- Metadata validation during module installation
- Custom view definition distribution
- Third-party extension integration

### Customization Framework
Provides foundation for:
- Studio-based layout modifications
- Developer customization workflows
- Version control friendly customization
- Upgrade-safe metadata management

### Caching and Performance
The registry enables:
- Metadata caching for improved performance
- Lazy loading of view definitions
- Optimized metadata access patterns
- Reduced file system overhead

## Integration with SuiteCRM Core

### Metadata Loading Workflow
1. System requests metadata for specific view type
2. MetaDataManager consults metafiles registry
3. Appropriate metadata file is located and loaded
4. View is rendered using loaded configuration

### Extension and Customization
- Custom metadata files can be added to registry
- Third-party modules can extend metadata definitions
- Studio modifications update registered metadata files
- Custom views integrate seamlessly with existing framework

## Security and Access Control

### File Access Protection
- All metadata files inherit module-level security
- ACL rules apply to view generation based on metadata
- User permissions control metadata visibility
- Role-based view customization supported

### Metadata Integrity
- Centralized registry prevents orphaned metadata files
- Standardized paths reduce configuration errors
- Version control friendly structure
- Consistent metadata loading behavior 