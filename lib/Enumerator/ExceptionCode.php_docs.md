# ExceptionCode.php Documentation

## @fileoverview
Central enumeration class that defines standardized error codes for all exceptions throughout the SuiteCRM system. Provides consistent error code management across different subsystems including application, API, and malware detection components.

## @package
SuiteCRM\Enumerator

## @copyright
Copyright (C) 2011 - 2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### ExceptionCode
A static enumeration class that centralizes all error codes used across the SuiteCRM system. Uses a consistent naming convention: `[Sub_System]_[Error_Name] = unique integer` to ensure clear categorization and prevent code conflicts.

## Error Code Categories

### Application Errors (4000-6999 range)
- **APPLICATION_MALWARE_FOUND (4000)**: Triggered when malware detection systems identify suspicious files
- **APPLICATION_UNHANDLED_BEHAVIOUR (6000)**: General application-level exceptions for unexpected behaviors
- **APPLICTAION_MODULE_NOT_FOUND (6005)**: When requested SuiteCRM modules cannot be located or loaded

### API Errors (8000-8999 range)
- **API_EXCEPTION (8000)**: General API-level exception code
- **API_CONTENT_NEGOTIATION_FAILED (8005)**: Content type negotiation failures in API requests
- **API_INVALID_BODY (8010)**: Malformed or invalid request body content
- **API_MODULE_NOT_FOUND (8015)**: API requests for non-existent modules
- **API_MISSING_REQUIRED (8020)**: Required API parameters or fields are missing
- **API_DATE_CONVERTION_SUGARBEAN (8025)**: Date conversion errors when working with SugarBean objects
- **API_USER_NOT_ACTIVE (8030)**: API access attempts by inactive user accounts
- **API_NOT_IMPLEMENTED (8035)**: API endpoints or features not yet implemented
- **API_RESERVED_KEYWORD_NOT_ALLOWED (8040)**: Use of reserved system keywords in API operations
- **API_RELATIONSHIP_NOT_FOUND (8045)**: Referenced relationships between records don't exist
- **API_RECORD_NOT_FOUND (8050)**: Requested database records cannot be found
- **API_VIEWDEF_NOT_FOUND (8055)**: View definitions for modules are missing or corrupted
- **API_ID_ALREADY_EXISTS (8060)**: Attempted creation of records with duplicate identifiers

## Implementation Details

### Namespace Structure
- Uses `SuiteCRM\Enumerator` namespace for clear organizational hierarchy
- Supports PHP 8+ attribute syntax with `#[\AllowDynamicProperties]`

### Error Code Ranges
- **4000-4999**: Malware and security-related errors
- **6000-6999**: General application errors
- **8000-8999**: API-specific errors

### Usage Patterns
Error codes are used throughout the system by:
- Exception classes in `lib/Exception/` directory
- API handlers in `Api/` directory
- Security components for malware detection
- Module loading and management systems

## Integration Points

### Exception System
All exception classes reference these codes for consistent error handling across:
- Custom exception classes extending base Exception
- API response generation
- Error logging and monitoring
- User-facing error messages

### Error Reporting
These codes enable:
- Centralized error tracking and analytics
- Consistent error documentation
- Automated error handling workflows
- Debug and troubleshooting processes

## Security Considerations

### Malware Detection
- Code 4000 specifically handles malware detection scenarios
- Integrates with SuiteCRM's security scanning systems
- Enables rapid response to security threats

### API Security
- Multiple codes dedicated to API security validation
- Prevents unauthorized access and data manipulation
- Supports audit trail requirements 