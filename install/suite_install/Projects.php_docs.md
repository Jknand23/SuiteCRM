# Projects.php_docs.md

/**
 * @fileoverview Project management installation module with logic hooks for task dependencies and project lifecycle
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file provides installation functionality for the project management module, setting up logic hooks that manage project task dependencies, project lifecycle events, and automated project date calculations.

## Database Operations

### Project Task Relationship Management
- Manages cascade delete operations for project tasks when parent project is deleted
- Updates project end dates automatically based on task completion
- Maintains project task dependency chains and calculations

### Data Integrity Enforcement
- Ensures project data consistency through automated logic hook operations
- Prevents orphaned task records through proper deletion handling
- Maintains project timeline accuracy through dependency updates

## Internal API Calls

### Module Installation Process

**install_projects() Function**
- **Purpose**: Main installation function for project management features
- **Dependencies**: Requires `ModuleInstall/ModuleInstaller.php`
- **Hook Management**: Uses `check_logic_hook_file()` for logic hook registration
- **Integration**: Sets up automated project and task management workflows

### Logic Hook Configuration

**Project Before Delete Hook**
- **Module**: `Project`
- **Hook Type**: `before_delete`
- **Order**: 1 (high priority)
- **Handler**: `delete_project_tasks::delete_tasks`
- **File**: `modules/Project/delete_project_tasks.php`
- **Purpose**: Automatically deletes associated tasks before project deletion

**ProjectTask Before Save Hook**
- **Module**: `ProjectTask`
- **Hook Type**: `before_save`
- **Order**: 1 (high priority)
- **Handler**: `updateDependencies::update_dependency`
- **File**: `modules/ProjectTask/updateDependencies.php`
- **Purpose**: Updates task dependencies and calculates timeline impacts

**ProjectTask After Save Hook**
- **Module**: `ProjectTask`
- **Hook Type**: `after_save`
- **Order**: 1 (high priority)
- **Handler**: `updateEndDate::update`
- **File**: `modules/ProjectTask/updateProject.php`
- **Purpose**: Updates parent project end date based on task changes

## System Architecture

### Project Lifecycle Management
- **Deletion Cascade**: Ensures proper cleanup of project-related data
- **Dependency Tracking**: Maintains task relationship integrity
- **Timeline Calculation**: Automatically updates project schedules

### Task Dependency System
- **Before Save Processing**: Validates and updates dependencies before data persistence
- **After Save Processing**: Propagates changes to related project data
- **Performance Optimization**: Efficient dependency calculation algorithms

## UI Functionality

### Project Management Interface
- Supports comprehensive project tracking and timeline management
- Enables task dependency visualization and management
- Provides automated project completion date calculations

### Task Management Features
- Real-time dependency tracking and validation
- Automated timeline updates based on task progress
- Cascade operations for maintaining data integrity

## Performance Considerations

### Hook Execution Strategy
- All hooks execute with order priority 1 for immediate processing
- Before/after save pattern ensures data consistency
- Efficient dependency calculation minimizes system overhead

### Database Operation Optimization
- Targeted updates to specific project and task records
- Minimal database queries through efficient hook design
- Optimized for large project structures with many dependencies

## Integration Points

### Module Relationships
- **Project → ProjectTask**: One-to-many relationship with cascade delete
- **ProjectTask → ProjectTask**: Dependency relationships with automatic updates
- **Project Timeline**: Automatic calculation based on task dependencies

### SuiteCRM Framework Integration
- Leverages SuiteCRM's logic hook framework for reliable operation
- Integrates with standard module deletion and save operations
- Maintains compatibility with custom project management extensions

## Usage Context

**Project Management Scenarios**
- Complex project tracking with multiple task dependencies
- Timeline management with automatic schedule updates
- Resource planning with dependency-aware task scheduling
- Project portfolio management with automated date calculations

**Data Integrity Assurance**
- Prevents orphaned task records through cascade deletion
- Maintains accurate project timelines through dependency tracking
- Ensures consistent project data across all related modules

## Error Handling and Validation

### Dependency Validation
- Validates task dependencies before save operations
- Prevents circular dependencies in project task chains
- Ensures timeline consistency across project hierarchy

### Data Consistency
- Maintains referential integrity between projects and tasks
- Handles edge cases in project deletion and task updates
- Provides robust error handling for dependency calculations

## Configuration Management

### Hook Installation
- Automatically installs all required logic hooks during setup
- Configures proper execution order for dependency management
- Ensures compatibility with existing project management customizations

### System Integration
- Seamlessly integrates with SuiteCRM project modules
- Maintains compatibility with custom project workflow extensions
- Supports both standard and advanced project management scenarios 