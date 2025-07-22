/**
 * @fileoverview Main application entry point for SuiteCRM. This file initializes the entire application framework, handles session management, and executes the primary application logic through the MVC pattern. It serves as the central gateway for all web-based user interactions with the CRM system.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM Main Entry Point

## Overview

The root `index.php` file is the primary entry point for the SuiteCRM web application. It orchestrates the complete application bootstrap process, from initial setup through session management to request execution. This file implements the foundational MVC (Model-View-Controller) pattern that drives the entire CRM system.

## Internal API Calls

### Pre-Dispatch Processing
- **File**: `include/MVC/preDispatch.php`
- **Purpose**: Performs pre-execution setup and routing preparation
- **Timing**: First operation after security check
- **Dependencies**: Establishes foundational application state

### Entry Point Initialization
- **File**: `include/entryPoint.php`
- **Purpose**: Initializes core SuiteCRM environment
- **Functions**: Database connections, configuration loading, security setup
- **Global State**: Establishes `$sugar_config`, `$db`, and other system globals

### Application Framework
- **File**: `include/MVC/SugarApplication.php`
- **Class**: `SugarApplication`
- **Purpose**: Core application controller managing request lifecycle
- **Pattern**: Implements MVC architecture for request handling

## UI Functionality

### Session Management
- **Method**: `$app->startSession()`
- **Purpose**: Initializes user session and authentication state
- **Security**: Manages login state and user permissions
- **Storage**: Handles session data persistence and cleanup

### Request Execution
- **Method**: `$app->execute()`
- **Purpose**: Processes incoming HTTP requests through MVC pattern
- **Routing**: Determines appropriate controller and action
- **Response**: Generates and returns appropriate response to client

### Performance Monitoring
- **Variable**: `$startTime = microtime(true)`
- **Purpose**: Captures application start time for performance measurement
- **Usage**: Enables execution time calculation and performance optimization

### Output Buffering
- **Function**: `ob_start()`
- **Purpose**: Enables output buffering for response management
- **Benefits**: Allows header modification after content generation
- **Control**: Provides flexibility in response formatting

## Application Architecture

### MVC Pattern Implementation
- **Model**: Data layer through SugarBean and database abstractions
- **View**: Smarty templates and display logic
- **Controller**: SugarApplication and module-specific controllers

### Request Lifecycle
1. **Security Check**: Validates `sugarEntry` constant
2. **Pre-Dispatch**: Initial setup and routing preparation
3. **Environment Setup**: Database, configuration, and global initialization
4. **Session Start**: Authentication and user session management
5. **Request Processing**: Route to appropriate controller and action
6. **Response Generation**: Template rendering and output generation

### Security Framework
- **Entry Validation**: `sugarEntry` constant prevents direct file access
- **Session Security**: Integrated authentication and authorization
- **Input Validation**: Framework-level request sanitization
- **Output Protection**: Controlled response generation

## Performance Considerations

### Execution Timing
- **Start Time Capture**: Enables performance monitoring and optimization
- **Benchmark Points**: Allows identification of bottlenecks
- **Optimization**: Supports performance tuning decisions

### Memory Management
- **Output Buffering**: Efficient memory usage for large responses
- **Session Handling**: Optimized session data management
- **Resource Cleanup**: Automatic cleanup through framework

## Integration Points

### Module System
- **Module Loading**: Dynamic module discovery and initialization
- **Controller Routing**: Maps requests to appropriate module controllers
- **Action Execution**: Delegates to module-specific action handlers

### Theme System
- **Template Loading**: Integrates with Smarty templating engine
- **Asset Management**: CSS, JavaScript, and image resource handling
- **Responsive Design**: Mobile and desktop interface support

### Authentication System
- **Login Processing**: Handles user authentication workflow
- **Permission Checking**: Validates user access rights
- **Security Policies**: Enforces system security requirements

## Error Handling

### Framework Errors
- **Exception Handling**: Centralized error processing
- **Error Logging**: Comprehensive error tracking and reporting
- **User Experience**: Graceful error presentation to users

### Debug Information
- **Development Mode**: Enhanced debugging in development environments
- **Production Safety**: Secure error handling in production
- **Logging Integration**: Comprehensive application logging 