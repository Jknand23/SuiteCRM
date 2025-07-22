# modules/AM_TaskTemplates/vardefs.php Documentation

## Overview

**File**: `modules/AM_TaskTemplates/vardefs.php`  
**Type**: Database Field Definitions  
**Purpose**: Defines the database structure and field properties for the Advanced Task Templates module

This file establishes the data model for task templates, providing the foundation for template-based task creation and project management functionality.

## Database Operations

### Table Configuration

**Primary Table**: `am_tasktemplates`  
**Features**:
- **Auditing Enabled**: `'audited' => true` - Tracks field changes
- **Duplicate Merge Support**: `'duplicate_merge' => true` - Enables deduplication

### Core Field Definitions

**Basic Information Fields**:
- **name**: Primary identifier field (varchar, 255 length, required)
- **status**: Enum field with task status options, default 'Not Started'
- **description**: Text field for detailed task template descriptions
- **priority**: Task priority management

**Assignment and Tracking**:
- **assigned_user_id**: Links to Users module for ownership
- **assigned_user_name**: Related field for user display
- **date_due**: Due date management for task scheduling

## Field Properties

### Standard Attributes
- **massupdate**: Controls bulk update capability
- **importable**: Enables import functionality
- **duplicate_merge**: Controls deduplication behavior
- **audited**: Change tracking for compliance
- **reportable**: Analytics and reporting support
- **unified_search**: Global search participation

### Task-Specific Fields
- **Status Management**: Enum field for workflow tracking
- **Priority Control**: Task importance classification
- **Assignment System**: User ownership and responsibility tracking

## Integration Points

### Project Management System
- Works with AM_ProjectTemplates for comprehensive planning
- Supports template-based task generation
- Enables standardized task workflows

### SuiteCRM Core Framework
- Follows standard vardefs patterns
- Integrates with SugarBean ORM system
- Supports standard module functionality

## Usage Context

### Task Template Management
- Defines reusable task templates for project planning
- Supports standardized task creation workflows
- Enables task template organization and reuse

### Project Planning Integration
- Provides building blocks for project templates
- Supports complex project structure definition
- Enables consistent task management across projects

## Related Files

- `modules/AM_TaskTemplates/AM_TaskTemplates.php` - Bean class implementation
- `modules/AM_ProjectTemplates/` - Related project template functionality
- `include/SugarObjects/VardefManager.php` - Field template management

## Notes

- Essential data model for task template functionality
- Supports enterprise project management workflows
- Integrates with SuiteCRM's standard module patterns
- Provides foundation for template-based task creation and management 