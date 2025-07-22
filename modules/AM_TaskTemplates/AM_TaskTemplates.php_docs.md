# modules/AM_TaskTemplates/AM_TaskTemplates.php Documentation

## Overview

**File**: `modules/AM_TaskTemplates/AM_TaskTemplates.php`  
**Type**: Module Bean Class (Customization Layer)  
**Purpose**: Provides customizable task template functionality extending the auto-generated base class

This file serves as the customization layer for the AM_TaskTemplates module, allowing developers to add custom functionality while preserving auto-generated base functionality.

## Class Definition

### AM_TaskTemplates Class

**Inheritance**: `extends AM_TaskTemplates_sugar`  
**Properties**: Uses `#[\AllowDynamicProperties]` attribute for dynamic property support  
**Purpose**: Customizable layer for task template business logic

**Constructor**:
- Calls parent constructor to maintain inheritance chain
- Provides initialization point for custom functionality
- Follows SuiteCRM standard patterns for customizable modules

## Integration Points

### Base Class Integration
- **AM_TaskTemplates_sugar**: Auto-generated base functionality
- **SugarBean Framework**: Core ORM and framework integration
- **Module System**: Standard SuiteCRM module functionality

### Task Template Management
- Supports task template creation and management
- Integrates with project management workflows
- Provides template-based task generation capabilities

## Usage Context

### Project Management Integration
- Works with AM_ProjectTemplates for comprehensive project planning
- Supports template-based task creation workflows
- Enables standardized task definition and reuse

### Customization Framework
- Provides developer extension point for custom business logic
- Maintains separation between auto-generated and custom code
- Supports upgrade-safe customizations

## Related Files

- `modules/AM_TaskTemplates/AM_TaskTemplates_sugar.php` - Auto-generated base class
- `modules/AM_TaskTemplates/vardefs.php` - Database field definitions
- `modules/AM_ProjectTemplates/` - Related project template functionality

## Notes

- Standard SuiteCRM customization pattern for module beans
- Minimal implementation ready for developer customization
- Maintains compatibility with SuiteCRM framework standards
- Essential for task template functionality within project management system 