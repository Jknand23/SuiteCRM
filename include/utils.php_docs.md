# utils.php Documentation

/**
 * @fileoverview Core utility functions library providing comprehensive helper functions for 
 * configuration management, security operations, language handling, user management, data processing,
 * UI generation, validation, and system utilities across all SuiteCRM modules.
 * @package SuiteCRM.Utils
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `utils.php` file serves as the central utility library for SuiteCRM, containing over 100 essential helper functions that provide core functionality across the entire application. These functions handle everything from security and data validation to user interface generation and configuration management. This file is included by virtually every SuiteCRM component and provides the foundational utility functions that make the application work.

### Key Function Categories
- Configuration management and system setup
- Security operations and data sanitization
- Language and localization utilities
- User management and authentication helpers
- Data processing and validation functions
- UI generation and form helpers
- Database utilities and query builders
- File and path management functions
- Administrative and permission utilities
- Performance monitoring and debugging tools

## Database Operations

### Configuration Management

#### make_sugar_config(&$sugar_config)
**Converts legacy configuration format to modern array structure**
- Migrates old global variable-based configuration to array format
- Handles backward compatibility with existing installations
- Processes all configuration variables into unified `$sugar_config` array
- Essential for configuration system modernization

#### get_sugar_config_defaults(): array
**Returns default configuration values for new installations**
- Provides comprehensive default settings for all configuration options
- Used during installation and configuration reset operations
- Ensures system has proper defaults when custom configuration is missing
- Covers database, security, performance, and feature settings

#### sugar_config_union($default, $override)
**Merges default and override configuration arrays**
- Combines base configuration with custom overrides
- Handles nested array merging for complex configuration structures
- Used in config_override.php processing
- Maintains configuration hierarchy and precedence

### Database Utility Functions

#### getSQLDate($date_str)
**Converts date strings to database-compatible format**
- Handles various input date formats
- Ensures consistent database date storage
- Supports timezone-aware date conversion
- Returns properly formatted SQL date strings

#### clone_history(&$db, $from_id, $to_id, $to_type)
**Clones historical records for duplicated entities**
- Copies audit and history data for cloned records
- Maintains data integrity during record duplication
- Handles foreign key relationships in history tables
- Essential for record cloning functionality

#### clone_relationship(&$db, $tables, $from_column, $from_id, $to_id)
**Clones relationship data between records**
- Duplicates many-to-many relationship entries
- Supports complex relationship table structures
- Maintains referential integrity during cloning
- Used in record duplication and import operations

## Internal API Operations

### Security and Data Sanitization

#### clean_incoming_data()
**Primary security function for processing all incoming request data**
- Sanitizes $_GET, $_POST, $_REQUEST, and $_COOKIE superglobals
- Applies XSS filtering to prevent cross-site scripting attacks
- Handles file upload validation and security
- Processes form data for safe database storage
- **Critical security function called during application bootstrap**

#### clean_special_arguments()
**Sanitizes special PHP variables and superglobals**
- Processes system-level PHP variables for security
- Handles special cases in request processing
- Prevents manipulation of critical system variables
- Complements main data cleaning operations

#### clean_string($str, $filter = 'STANDARD', $dieOnBadData = true)
**Applies configurable filtering to string data**
- Supports multiple filter levels (STANDARD, STANDARDSPACE, etc.)
- Removes potentially dangerous characters and patterns
- Can terminate execution on detection of malicious data
- Configurable strictness based on security requirements

#### remove_xss($str) & clean_xss($str, $cleanImg = true)
**Advanced XSS protection functions**
- Removes cross-site scripting attack vectors
- Handles complex HTML and JavaScript filtering
- Supports image tag filtering for enhanced security
- Uses configurable XSS tag definitions for comprehensive protection

#### purify_html(?string $value, array $extraOptions = []): string
**HTML purification using HTMLPurifier library**
- Provides advanced HTML sanitization capabilities
- Supports custom configuration for specific use cases
- Handles complex HTML structures while maintaining security
- Used for rich text content processing

#### securexss($uncleanString) & securexsskey($value, $die = true)
**Additional XSS protection with validation**
- Provides extra layers of XSS protection
- Validates keys and values for security compliance
- Can terminate execution on security violations
- Used for critical data validation scenarios

### User Management and Authentication

#### get_authenticated_user(): ?User
**Returns currently authenticated user object**
- Provides access to current user session
- Returns null if no authenticated user
- Used throughout application for user context
- Essential for permission checking and user-specific functionality

#### get_user_array($add_blank, $status, $user_id, $use_real_name, $user_name_filter, $portal_filter, $from_cache)
**Generates arrays of users for dropdown lists and selection**
- Creates formatted arrays for UI components
- Supports filtering by status, portal access, and custom criteria
- Enables caching for performance optimization
- Handles real name vs username display preferences

#### get_assigned_user_name($assigned_user_id, $is_group = '')
**Retrieves display name for assigned user**
- Handles both user and group assignments
- Returns formatted names for display purposes
- Supports group assignment display
- Used extensively in list and detail views

#### getUserArrayFromFullName($args, $hide_portal_users = false)
**Searches users by full name with advanced filtering**
- Enables user lookup by partial name matching
- Supports portal user filtering
- Returns formatted results for autocomplete functionality
- Used in user selection interfaces

### Language and Localization

#### get_languages() & get_all_languages()
**Language management and detection functions**
- `get_languages()`: Returns installed/enabled languages
- `get_all_languages()`: Returns all available language options
- Used for language selection interfaces
- Supports multi-language application configuration

#### get_current_language()
**Determines current user's language setting**
- Checks user preferences first
- Falls back to system default language
- Returns language code for translation functions
- Essential for localization operations

#### translate($string, $mod = '', $selectedValue = '')
**Primary translation function for multi-language support**
- Translates label keys to localized strings
- Supports module-specific translations
- Handles placeholder replacement with selected values
- Core function for application internationalization

#### return_application_language($language)
**Loads application-level language strings**
- Returns global language labels and messages
- Handles caching for performance optimization
- Supports custom language file extensions
- Used for system-wide text and messages

#### return_module_language($language, $module, $refresh = false)
**Loads module-specific language strings**
- Returns localized strings for specific modules
- Supports language file caching and refresh
- Handles custom module language extensions
- Essential for module-specific text display

### Administrative and Permission Utilities

#### is_admin($user) & is_admin_for_module($user, $module)
**Administrative permission checking functions**
- `is_admin()`: Checks global administrative access
- `is_admin_for_module()`: Checks module-specific admin rights
- Used throughout application for access control
- Essential for security and permission enforcement

#### get_admin_modules_for_user($user)
**Returns list of modules user can administer**
- Generates array of modules with admin access
- Used for administrative interface construction
- Supports role-based permission filtering
- Essential for dynamic admin menu generation

#### displayStudioForCurrentUser() & displayWorkflowForCurrentUser()
**Checks if user can access Studio and Workflow tools**
- Controls access to development and configuration tools
- Based on administrative permissions and system configuration
- Used for menu and interface customization
- Prevents unauthorized access to system modification tools

## External API Integration

### Path and URL Management

#### getWebPath($relative_path) & getImagePath($image_name)
**Web-accessible path generation functions**
- Converts relative paths to web-accessible URLs
- Handles theme-specific image paths
- Supports CDN and asset management integration
- Essential for proper resource loading

#### getVersionedPath($path, $additional_attrs = '') & getVersionedScript($path, $additional_attrs = '')
**Cache-busting path generation for assets**
- Adds version parameters to prevent browser caching issues
- Supports JavaScript and CSS file versioning
- Handles additional HTML attributes for asset tags
- Critical for deployment and update management

#### add_http($url)
**Ensures URLs have proper HTTP/HTTPS protocol**
- Adds protocol to URLs missing scheme
- Handles SSL detection and protocol selection
- Used for external link processing
- Prevents malformed URL generation

#### check_trusted_hosts()
**Validates request against trusted host configuration**
- Prevents host header injection attacks
- Validates incoming requests against whitelist
- Essential security function for production environments
- Protects against domain-based attacks

### File and Upload Management

#### getRunningUser()
**Determines system user running the application**
- Returns user account running web server process
- Used for file permission and ownership checks
- Critical for security and file system operations
- Supports both web and CLI execution contexts

#### make_not_writable($file)
**Removes write permissions from sensitive files**
- Enhances security by restricting file modifications
- Used during installation and configuration processes
- Prevents unauthorized changes to critical files
- Supports Unix-style permission management

### Form and UI Generation

#### get_select_options($option_list, $selected) & related functions
**HTML select option generation utilities**
- Creates properly formatted HTML option elements
- Handles selected state management
- Supports various option list formats
- Essential for dropdown and select interfaces

#### get_clear_form_js() & get_set_focus_js()
**JavaScript generation for form functionality**
- Generates client-side JavaScript for form operations
- Provides consistent form behavior across application
- Handles focus management and form clearing
- Used in form template generation

#### display_notice($msg = false)
**System notice and message display function**
- Shows administrative messages and system notifications
- Handles various message types and formatting
- Used for user feedback and system communication
- Supports dismissible and persistent notices

## UI Functionality

### Data Processing and Validation

#### is_guid($guid) & create_guid()
**GUID validation and generation functions**
- `is_guid()`: Validates GUID format and structure
- `create_guid()`: Generates new globally unique identifiers
- Essential for database record identification
- Ensures consistent ID format across application

#### clean($string, $maxLength) & preprocess_param($value)
**Data cleaning and preprocessing functions**
- Removes unwanted characters and formats data
- Applies length restrictions for data integrity
- Preprocesses parameters for safe processing
- Used throughout application for data validation

#### number_empty($value)
**Checks if numeric value is empty or zero**
- Handles various empty value representations
- Used for numeric field validation
- Supports database NULL handling
- Essential for form validation and data processing

### Array and Data Structure Utilities

#### array_csort()
**Custom array sorting function**
- Provides enhanced array sorting capabilities
- Supports complex sorting criteria
- Used for list and data presentation
- Handles multi-dimensional array sorting

#### values_to_keys($array)
**Converts array values to keys for lookup tables**
- Creates efficient lookup structures
- Used for data transformation and processing
- Supports enum and list processing
- Essential for dropdown and selection operations

#### safe_map($request_var, &$focus, $always_copy = false)
**Safely maps request variables to object properties**
- Prevents overwriting of critical object properties
- Provides secure data binding for forms
- Handles type conversion and validation
- Essential for form-to-object data mapping

### Session and State Management

#### return_session_value_or_default($varname, $default)
**Session value retrieval with fallback defaults**
- Safely retrieves session variables
- Provides default values when session data is missing
- Used for user preference and state management
- Handles session timeout and cleanup scenarios

#### set_register_value($category, $name, $value) & get_register_value($category, $name)
**Application-level value registry functions**
- Provides global value storage and retrieval
- Supports categorized value organization
- Used for application state and configuration
- Enables cross-module communication

### Performance and Debugging

#### microtime_diff($a, $b)
**High-precision time difference calculation**
- Calculates execution time between points
- Supports performance monitoring and optimization
- Used for debugging and profiling
- Essential for application performance analysis

#### sugar_die($error_message, $exit_code = 1)
**Graceful application termination with logging**
- Provides controlled application shutdown
- Logs error messages for debugging
- Supports different exit codes for error categorization
- Used for fatal error handling

### Specialized Utilities

#### parse_calendardate($local_format) & getSQLDate($date_str)
**Date parsing and formatting functions**
- Handles various date format conversions
- Supports localized date formats
- Essential for calendar and scheduling functionality
- Provides database-compatible date formatting

#### get_emails_by_assign_or_link($params)
**Email retrieval with advanced filtering**
- Fetches emails based on assignment or relationships
- Supports complex filtering criteria
- Used in email management interfaces
- Handles both direct assignment and linked emails

#### formatDecimalInConfigSettings($decimalValue, $userSetting = false)
**Decimal formatting based on user/system preferences**
- Applies proper decimal separators
- Respects user and system configuration
- Used for numeric display formatting
- Supports international number formatting

## Security Features

### XSS Protection
- Multiple layers of XSS filtering and validation
- Configurable security levels for different contexts
- Comprehensive HTML and JavaScript sanitization
- Protection against various attack vectors

### Input Validation
- Thorough sanitization of all incoming data
- Special handling for file uploads and binary data
- Validation of special characters and patterns
- Protection against SQL injection and code injection

### Access Control
- Administrative permission checking
- Module-level access validation
- User authentication and session management
- Trusted host validation for security

## Performance Optimizations

### Caching Strategies
- Language file caching for improved performance
- User array caching for reduced database queries
- Configuration caching for faster access
- Session-based caching for user preferences

### Efficient Data Processing
- Optimized array operations and sorting
- Batch processing for large datasets
- Efficient string operations and validation
- Memory-conscious data handling

### Resource Management
- Proper file handle management
- Efficient path resolution and caching
- Optimized database query generation
- Smart loading of language and configuration data

## Integration Points

### Module System Integration
- Seamless integration with all SuiteCRM modules
- Module-specific language and configuration support
- Administrative tool integration
- Permission system integration

### Theme and UI Integration
- Theme-aware path and resource management
- UI component generation utilities
- Consistent interface element creation
- Asset management and versioning

### Database Layer Integration
- Database-agnostic utility functions
- Query building and optimization helpers
- Data type conversion utilities
- Transaction and relationship management

## Error Handling and Logging

### Comprehensive Error Management
- Graceful error handling with proper logging
- Detailed error messages for debugging
- Configurable error levels and responses
- User-friendly error presentation

### Debug Support
- Performance monitoring utilities
- Execution time measurement tools
- Memory usage tracking capabilities
- Development and debugging helpers

## Best Practices

### Security Guidelines
- Always use provided sanitization functions
- Validate all input data before processing
- Implement proper access control checks
- Use secure path and URL generation functions

### Performance Considerations
- Utilize caching functions where appropriate
- Use efficient array operations for large datasets
- Implement proper session management
- Optimize database queries using provided utilities

### Data Integrity
- Use GUID generation for consistent identifiers
- Implement proper data validation and cleaning
- Handle null and empty values appropriately
- Maintain referential integrity in data operations

---

*This documentation covers the extensive utility functionality provided by utils.php, which serves as the foundation for common operations throughout SuiteCRM. The file provides essential functions for security, data processing, user management, configuration, and system utilities that enable consistent and secure application operation.* 