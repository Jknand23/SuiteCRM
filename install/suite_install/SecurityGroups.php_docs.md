# SecurityGroups.php_docs.md

/**
 * @fileoverview Security Groups installation module for role-based access control and permission management
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file provides installation functionality for the Security Groups module, which implements comprehensive role-based access control, user permission management, and security inheritance across the SuiteCRM system.

## Database Operations

### Configuration Management
- Updates global `sugar_config` array with Security Groups settings
- Configures permission inheritance and access control parameters
- Manages Ajax module restrictions for security-sensitive components

### Version Tracking
- Sets `securitysuite_version` to '6.5.17' for compatibility tracking
- Maintains configuration consistency through sorted array writing
- Integrates with SuiteCRM's core configuration framework

## Internal API Calls

### Module Installation Process

**install_ss() Function**
- **Purpose**: Main installation function for Security Groups functionality
- **Dependencies**: 
  - `sugar_version.php` for version compatibility
  - `modules/Administration/Administration.php` for admin integration
- **Configuration**: Sets up comprehensive security framework settings
- **Hook Installation**: Calls `installSSHooks()` to configure logic hooks

**installSSHooks() Function**
- **Purpose**: Installs logic hooks for security group management
- **Dependencies**: Requires `ModuleInstall/ModuleInstaller.php`
- **Hook Management**: Uses `check_logic_hook_file()` for hook registration

### Security Configuration Settings

**Security Framework Configuration**
- `securitysuite_additive`: Default true for additive permission model
- `securitysuite_user_role_precedence`: Default true for user role priority
- `securitysuite_user_popup`: Default true for user popup functionality
- `securitysuite_popup_select`: Default false for popup selection behavior
- `securitysuite_inherit_creator`: Default true for creator inheritance
- `securitysuite_inherit_parent`: Default true for parent record inheritance
- `securitysuite_inherit_assigned`: Default true for assigned user inheritance
- `securitysuite_strict_rights`: Default true for strict permission enforcement
- `securitysuite_filter_user_list`: Default false for user list filtering

**Ajax Security Configuration**
- Adds `SecurityGroups` to `addAjaxBannedModules` array
- Prevents unauthorized Ajax access to security-sensitive functionality
- Maintains system security through restricted module access

## System Architecture

### Logic Hook Configuration

**After UI Footer Hook**
- **Module**: Applied globally (all modules)
- **Hook Type**: `after_ui_footer`
- **Order**: 10 (medium priority)
- **Handler**: `AssignGroups::popup_onload`
- **File**: `modules/SecurityGroups/AssignGroups.php`
- **Purpose**: Manages security group assignment popup functionality

**After UI Frame Hook**
- **Module**: Applied globally (all modules)
- **Hook Type**: `after_ui_frame`
- **Order**: 20 (medium priority)
- **Handler**: `AssignGroups::mass_assign`
- **File**: `modules/SecurityGroups/AssignGroups.php`
- **Purpose**: Handles mass assignment operations for security groups

**After Save Hook**
- **Module**: Applied globally (all modules)
- **Hook Type**: `after_save`
- **Order**: 30 (low priority)
- **Handler**: `AssignGroups::popup_select`
- **File**: `modules/SecurityGroups/AssignGroups.php`
- **Purpose**: Manages security group assignment during record save

### Permission Inheritance Model

**Inheritance Hierarchy**
- **Creator Inheritance**: Records inherit permissions from creator's security groups
- **Parent Inheritance**: Child records inherit from parent record permissions
- **Assigned User Inheritance**: Records inherit from assigned user's groups
- **Additive Model**: Combines multiple permission sources for maximum access

**Strict Rights Enforcement**
- Controls whether security restrictions are strictly enforced
- Configurable for different organizational security requirements
- Balances security with usability based on deployment needs

## UI Functionality

### Security Group Assignment Interface
- Provides popup functionality for group assignment during record operations
- Supports mass assignment operations for efficient group management
- Integrates seamlessly with existing SuiteCRM interface components

### Administrative Configuration
- Enables administrators to configure security inheritance behavior
- Provides flexible permission models for different organizational structures
- Supports both strict and flexible security enforcement modes

## System Integration

### Global Security Framework
- **Universal Application**: Security hooks apply to all modules automatically
- **Comprehensive Coverage**: All record operations subject to security evaluation
- **Performance Optimized**: Efficient permission checking with minimal overhead

### Module Integration Architecture
- **File Location**: `modules/SecurityGroups/AssignGroups.php`
- **Class Structure**: `AssignGroups` class with comprehensive group management
- **Hook Priority**: Carefully ordered execution for optimal security enforcement

## Configuration Management

### Default Settings Strategy
- Only configures settings if not already present (first-time installation)
- Preserves existing custom security configurations
- Provides sensible defaults for most organizational security requirements

### Incremental Configuration
- Supports phased rollout of security features
- Allows selective enabling of security components
- Maintains backward compatibility with existing installations

### Version-Aware Configuration
- Handles configuration evolution across Security Groups versions
- Provides upgrade paths for enhanced security features
- Maintains compatibility with different SuiteCRM versions

## Performance Considerations

### Efficient Permission Checking
- Optimized algorithms for rapid permission evaluation
- Minimal database queries for security group verification
- Cached permission calculations for improved performance

### Scalable Architecture
- Supports large user bases with complex group hierarchies
- Efficient mass operations for group assignment and management
- Optimized for high-volume record operations with security evaluation

## Security Features

### Access Control Mechanisms
- **User Role Precedence**: User-specific permissions override group defaults
- **Additive Permissions**: Multiple group memberships combine for maximum access
- **Inheritance Controls**: Flexible inheritance from creators, parents, and assignees
- **Strict Enforcement**: Configurable strict mode for high-security environments

### Permission Filtering
- **User List Filtering**: Optional filtering of user lists based on security groups
- **Popup Selection**: Controlled popup selection behavior for security-sensitive operations
- **Ajax Restrictions**: Prevents unauthorized Ajax access to security modules

## Usage Context

**Security Deployment Scenarios**
- Multi-tenant SuiteCRM installations with isolated data access
- Department-based access control with hierarchical permissions
- Customer data segregation for service providers
- Compliance requirements for data access auditing

**Administrative Use Cases**
- Role-based access control implementation
- Data segregation for different business units
- Customer portal security management
- Audit trail maintenance for data access

## Integration Benefits

### Enhanced Data Security
- Comprehensive record-level access control
- Flexible permission inheritance models
- Audit-ready permission tracking and enforcement

### Administrative Efficiency
- Automated group assignment through logic hooks
- Mass operations for efficient user management
- Flexible configuration for different security requirements 