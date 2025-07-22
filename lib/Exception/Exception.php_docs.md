# Exception.php Documentation

/**
 * @fileoverview Base exception class for the SuiteCRM system that extends PHP's standard Exception class. Provides standardized exception handling with integrated logging capabilities, consistent error code management, and integration with the broader SuiteCRM error handling architecture.
 * @package SuiteCRM.Exception  
 * @copyright Copyright (C) 2011 - 2018 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

Foundation exception class that serves as the base for all custom SuiteCRM exceptions. Integrates with the PSR-3 logging standard and the centralized ExceptionCode enumeration system to provide consistent error handling across the application.

**Integration Points:**
- **ExceptionCode Enumeration**: Uses centralized error code definitions for consistent error identification
- **PSR-3 Logging**: Integrates with modern logging standards through `LogLevel` constants
- **SuiteCRM Logging System**: Compatible with `LoggerManager` and `SugarLoggerHandler` for unified logging
- **Exception Hierarchy**: Base class for specialized exceptions across modules and subsystems

**Error Handling Architecture:**
- Provides standardized message formatting with "[SuiteCRM]" prefix
- Supports exception chaining for complex error scenarios
- Integrates with centralized error code enumeration
- Compatible with PSR-3 logging standards for modern logging frameworks 