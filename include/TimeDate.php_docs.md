# TimeDate.php Documentation

/**
 * @fileoverview TimeDate class - Comprehensive date and time handling with timezone management, format conversion, user preferences, and database operations for SuiteCRM
 * @package SuiteCRM\Core
 * @copyright SalesAgility Ltd
 * @license AGPL-3.0
 */

## Overview

The TimeDate class is the central date and time management system for SuiteCRM, providing comprehensive functionality for handling date/time formatting, timezone conversions, user preferences, and database operations. It serves as the bridge between user-facing date displays and internal database storage, supporting multiple timezones, user-specific formatting preferences, and various input/output formats.

## Class Structure

```php
class TimeDate
{
    // Database format constants
    public const DB_DATE_FORMAT = 'Y-m-d';
    public const DB_TIME_FORMAT = 'H:i:s';
    public const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const RFC2616_FORMAT = 'D, d M Y H:i:s \G\M\T';
    public const SECONDS_IN_A_DAY = 86400;
    
    // Core properties
    protected static $gmtTimezone;        // GMT timezone object
    protected $now;                       // Current time
    protected $user;                      // Current user
    protected $current_user_id;           // User ID for caching
    protected $current_user_tz;           // User timezone for caching
    protected $always_db;                 // Force DB format flag
    protected static $timedate;           // Global instance
    public $allow_cache;                  // Cache control flag
}
```

## Core Functionality

### Configuration and Initialization

#### Singleton Pattern
- **getInstance()**: Returns global TimeDate instance
  - Manages PHP timezone configuration warnings
  - Auto-detects timezone if date.timezone not set
  - Uses guessTimezone() for fallback detection
  - Logs configuration warnings for admin attention

#### Constructor and Setup
- **Constructor**: `__construct(User $user = null)`
  - Initializes GMT timezone object
  - Creates SugarDateTime for current time
  - Accepts optional user for context-specific operations

#### Database Format Control
- **setAlwaysDb()**: Forces database format for all operations
  - Used for SOAP/API contexts where user formatting inappropriate
  - Clears cache when toggled
  - Returns $this for method chaining

- **isAlwaysDb()**: Checks if DB format forced
  - Considers global `$GLOBALS['disable_date_format']` flag
  - Used throughout class for format decision logic

### User Management and Preferences

#### User Context
- **setUser()**: Sets user context for operations
  - Accepts User object or defaults to current user
  - Clears all cached user-specific data
  - Returns $this for method chaining

- **_getUser()**: Internal user resolution
  - Priority: parameter > instance user > global current_user
  - Handles null user scenarios gracefully

#### User Preference Retrieval
- **get_date_format()**: Gets user's date format preference
  - Reads from user preferences: 'datef'
  - Falls back to current user if specified user has no preference
  - Uses global config default_date_format as fallback
  - Returns DB format if alwaysDb enabled

- **get_time_format()**: Gets user's time format preference
  - Reads from user preferences: 'timef'
  - Includes backward compatibility handling for deprecated API
  - Falls back through user hierarchy to global config
  - Returns DB format if alwaysDb enabled

- **get_date_time_format()**: Combined date/time format with caching
  - Merges date and time formats with space separator
  - Implements cache via sugar_cache_* functions
  - Uses get_date_time_format_cache_key() for cache key generation
  - Handles user-specific and DB format caching

#### Week and Calendar Preferences
- **get_first_day_of_week()**: Gets user's week start preference
  - Reads 'fdow' preference (0=Sunday, 1=Monday, etc.)
  - Defaults to 0 (Sunday) if no preference set

### Format Conversion and Validation

#### Format Utilities
- **merge_date_time()**: Combines separate date and time strings
- **split_date_time()**: Splits datetime into date and time components
- **check_matching_format()**: Validates date string against format
  - Uses SugarDateTime::createFromFormat() for validation
  - Returns boolean indicating format compliance

#### JavaScript Integration
- **get_cal_date_format()**: Converts PHP format to JavaScript calendar format
- **get_cal_time_format()**: Time format for JavaScript calendars
- **get_cal_date_time_format()**: Combined datetime format for JavaScript
- **getCalFormat()**: Core format conversion using self::$format_to_str mapping

### Database Operations

#### Database Format Conversion
- **asDb()**: Converts DateTime to database datetime format
  - Sets timezone to GMT before formatting
  - Returns 'Y-m-d H:i:s' format string

- **asDbType()**: Converts DateTime based on field type
  - Supports: 'date', 'time', 'datetime', 'datetimecombo'
  - Routes to appropriate type-specific method

- **asDbDate()**: Database date format conversion
- **asDbtime()**: Database time format conversion

#### Database Format Retrieval
- **get_db_date_time_format()**: Returns DB_DATETIME_FORMAT constant
- **get_db_date_format()**: Returns DB_DATE_FORMAT constant  
- **get_db_time_format()**: Returns DB_TIME_FORMAT constant

### User Display Operations

#### User Format Conversion
- **asUser()**: Converts DateTime to user display format
  - Applies user timezone conversion
  - Uses user's preferred date/time format
  - Returns formatted string for display

- **asUserType()**: Type-specific user format conversion
  - Handles 'date', 'time', 'datetime', 'datetimecombo' types
  - Routes to appropriate conversion method

#### Display Format Conversion
- **to_display_date_time()**: DB datetime to user display format
  - Supports timezone conversion control
  - Uses _convert() for format transformation
  - Handles meridiem parameter for backward compatibility

- **to_display_time()**: DB time to user display format
  - Expands time-only strings to include current date for TZ conversion
  - Conditionally applies timezone conversion

- **to_display_date()**: DB date to user display format
  - Supports timezone conversion control for date-only values

- **to_display()**: Generic format-to-format conversion
  - No timezone conversion performed
  - Direct format transformation utility

### DateTime Object Creation

#### From Database
- **fromDb()**: Creates DateTime from database datetime string
  - Assumes GMT timezone for database values
  - Returns SugarDateTime object

- **fromDbDate()**: Creates DateTime from database date string
- **fromDbTime()**: Creates DateTime from database time string

#### From User Input
- **fromUser()**: Creates DateTime from user datetime format
  - Uses user's timezone and datetime format
  - Comprehensive error handling with logging
  - Returns null on conversion failure

- **fromUserType()**: Type-specific user input parsing
  - Routes to fromUserDate(), fromUserTime(), or fromUser()
  - Based on field type parameter

- **fromUserDate()**: Creates DateTime from user date format
  - Optional timezone conversion control
  - Defaults to GMT for date-only values

- **fromUserTime()**: Creates DateTime from user time format
  - Uses user timezone for time context

#### From Various Sources
- **fromString()**: Creates DateTime from any string format
  - Uses standard DateTime constructor capabilities
  - Applies user timezone context

- **fromTimestamp()**: Creates DateTime from Unix timestamp
  - Uses "@timestamp" DateTime constructor format

- **fromTimeArray()**: Creates DateTime from array structure
  - Supports 'ts', 'date_str', or component arrays
  - Handles various calendar array formats

### Timezone Operations

#### Timezone Conversion
- **tzGMT()**: Converts DateTime to GMT timezone
  - Modifies DateTime object in place
  - Returns modified DateTime for chaining

- **tzUser()**: Converts DateTime to user timezone
  - Applies user's preferred timezone
  - Uses _getUserTZ() for timezone resolution

#### Timezone Management
- **_getUserTZ()**: Gets user's timezone object
  - Caches timezone objects for performance
  - Reads 'timezone' user preference
  - Falls back to GMT for invalid timezones
  - Logs fatal errors for unknown timezones

#### Timezone Utilities
- **guessTimezone()**: Attempts to detect system timezone
  - Uses DateTime to determine GMT offset
  - Matches offset against known timezone list
  - Returns timezone name or null if detection fails

- **userTimezoneSuffix()**: Gets timezone display suffix
  - Format: "PST(+08:00)" for specific date
  - Considers DST status for accurate offset

- **tzName()**: Gets display name for timezone
  - Includes GMT offset calculation
  - Supports timezone translation via translate()
  - Formats as "Name (GMT±HH:MM)"

### Timezone Lists and References

#### Timezone Enumeration
- **getTimezoneList()**: Returns all system timezones
  - Sorted by GMT offset then alphabetically
  - Includes display names with GMT offsets
  - Used for timezone selection interfaces

- **_sortTz()**: Internal timezone sorting helper
  - Primary sort: GMT offset
  - Secondary sort: timezone name

### Advanced Operations

#### Core Conversion Engine
- **_convert()**: Internal format conversion engine
  - Handles format transformation with timezone conversion
  - Used by all public conversion methods
  - Supports format validation and error handling

#### Time Manipulation
- **expandTime()**: Expands time string to full datetime
  - Adds current date to time-only strings
  - Necessary for timezone conversion of time values

- **splitTime()**: Parses time string into components
  - Returns array with h, m, s, and optional a (AM/PM)
  - Handles 12/24 hour format differences

#### Calendar Integration
- **get_midnight()**: Gets midnight time in user format
- **getDSTStart()**: Gets DST start date for year
- **getDSTEnd()**: Gets DST end date for year
- **getDstPeriods()**: Gets DST transition periods for year
  - Uses timezone transition calculations
  - Returns start/end dates in DB format

### Legacy and Deprecated Methods

#### GUI Methods (Deprecated)
- **AMPMMenu()**: Renders AM/PM select HTML
  - Hardcoded HTML output
  - Scheduled for removal with field conversion
  - Supports user time format AM/PM detection

- **get_user_date_format()**: JavaScript-compatible date format
- **get_user_time_format()**: Example time format for display
- **get_javascript_validation()**: Moved to SugarView

## Database Operations

### Date Storage Strategy
- **Database Format**: All dates stored in GMT using 'Y-m-d H:i:s' format
- **User Display**: Converted to user timezone and format for display
- **Input Processing**: User input converted from user format/timezone to GMT for storage

### Transaction Flow
1. **Input**: User enters date in their preferred format/timezone
2. **Conversion**: fromUser() converts to DateTime object in user timezone
3. **Storage**: asDb() converts to GMT and formats for database
4. **Retrieval**: Database GMT value converted to user timezone/format for display

## Integration Points

### Core Framework Integration
- **SugarDateTime**: Extended DateTime class with additional SuiteCRM features
- **User Class**: User preference retrieval and timezone settings
- **Global Configuration**: Default formats and timezone settings
- **Cache System**: Performance optimization for format and timezone data

### Global Variables
- **$sugar_config**: Configuration defaults and time format definitions
- **$GLOBALS['current_user']**: Default user context
- **$GLOBALS['disable_date_format']**: Forces DB format globally

### External Dependencies
- **PHP DateTime**: Core PHP datetime functionality
- **DateTimeZone**: Timezone handling
- **PHP timezone functions**: timezone_identifiers_list(), timezone_open()

## Performance Optimization

### Caching Strategies
- **User Timezone Caching**: Avoids repeated timezone object creation
- **Format Caching**: Date/time format strings cached per user
- **Instance Caching**: Singleton pattern for global TimeDate instance
- **Now() Caching**: Optional caching of current time for request duration

### Cache Control
- **allow_cache**: Controls whether caching is enabled
- **clearCache()**: Invalidates all cached user-specific data
- **Cache Keys**: User-specific cache keys for format data

## Error Handling

### Validation and Safety
- **Format Validation**: check_matching_format() validates input strings
- **Exception Handling**: Comprehensive try/catch for DateTime operations
- **Fallback Behavior**: Graceful degradation when conversions fail
- **Logging**: Detailed error logging for debugging

### Common Error Scenarios
- **Invalid Timezones**: Falls back to GMT with error logging
- **Format Mismatches**: Returns null with error logging
- **Missing User Data**: Graceful fallback to defaults

## Usage Patterns

### Basic Date Conversion
```php
$timedate = TimeDate::getInstance();
$userDate = $timedate->to_display_date_time($dbDateTime);
$dbDate = $timedate->to_db($userDateTime);
```

### User-Specific Operations
```php
$timedate = new TimeDate($specificUser);
$userFormat = $timedate->get_date_time_format();
$userDate = $timedate->fromUser($inputString);
```

### Timezone Operations
```php
$datetime = $timedate->fromDb($dbString);
$timedate->tzUser($datetime, $user);
$userString = $timedate->asUser($datetime, $user);
```

### Type-Specific Conversions
```php
$dateObject = $timedate->fromUserType($input, 'date', $user);
$displayString = $timedate->asUserType($dateObject, 'datetime', $user);
```

## Dependencies

### Core Dependencies
- SugarDateTime class for extended datetime functionality
- User class for preference management
- Global configuration system
- PHP DateTime and DateTimeZone classes
- SuiteCRM cache system (sugar_cache_*)

### Integration Requirements
- User preference system for date/time formats and timezones
- Global configuration for default formats
- Logging system for error reporting
- Translation system for timezone names 