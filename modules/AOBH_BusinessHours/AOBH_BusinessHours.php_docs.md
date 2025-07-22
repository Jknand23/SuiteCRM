# modules/AOBH_BusinessHours/AOBH_BusinessHours.php Documentation

## Overview

**File**: `modules/AOBH_BusinessHours/AOBH_BusinessHours.php`  
**Type**: Business Hours Management System  
**Purpose**: Provides comprehensive business hours tracking, calculation, and validation functionality for SuiteCRM

This class manages organizational business hours, enabling accurate time calculations, scheduling validation, and business hour-aware date operations across the CRM system.

## Class Definition

### AOBH_BusinessHours Class

**Inheritance**: `extends Basic`  
**Properties**: Uses `#[\AllowDynamicProperties]` attribute for dynamic property support

**Core Properties**:
- **Table**: `aobh_businesshours` - Database table for business hours storage
- **Importable**: `false` - Not available for data import operations
- **Row Level Security**: Disabled for system-wide business hours access

### Database Fields

**Basic Information**:
- **name**: Business hour configuration name
- **description**: Detailed description of business hours setup
- **day**: Day of the week for business hours definition
- **opening_hours**: Start time for business operations
- **closing_hours**: End time for business operations
- **open_status**: Boolean indicator for business day status

**Standard Tracking Fields**:
- **date_entered/date_modified**: Record lifecycle tracking
- **created_by/modified_user_id**: User attribution for changes
- **assigned_user_id**: User assignment for business hours management

## Internal API Methods

### Business Hours Validation

**areBusinessHoursSet()**:
- **Purpose**: Determines if business hours have been configured
- **Returns**: `int` - Count of configured business hour entries
- **Caching**: Uses `$businessHoursSet` property for performance optimization

**getBusinessHoursForDay($day)**:
- **Purpose**: Retrieves business hours configuration for specified day
- **Parameter**: `$day` - Day of week string (e.g., 'Monday', 'Tuesday')
- **Returns**: Array of business hour objects for the specified day
- **Caching**: Uses `$cached` array for efficient repeated access

### Time Calculation Methods

**getBusinessHours($startTime, $endTime)**:
- **Purpose**: Calculates total business hours between two DateTime objects
- **Parameters**: 
  - `$startTime` - Starting DateTime object
  - `$endTime` - Ending DateTime object
- **Returns**: `float` - Number of business hours in the specified range
- **Logic**: Iterates hour by hour, counting only hours within business hours

**addBusinessHours($hours, DateTime $date = null)**:
- **Purpose**: Adds specified business hours to a date, respecting business hour constraints
- **Parameters**:
  - `$hours` - Number of business hours to add (can be negative)
  - `$date` - Optional starting date (defaults to current date/time)
- **Returns**: `DateTime` - Calculated end date after adding business hours
- **Features**: Automatically adjusts for weekends and non-business hours

### Business Hours Checking

**insideAnyBusinessHours(DateTime $datetime)**:
- **Purpose**: Determines if specified datetime falls within any configured business hours
- **Parameter**: `$datetime` - DateTime object to check
- **Returns**: `bool` - True if within business hours, false otherwise
- **Logic**: 
  - Returns true if no business hours configured (24/7 operation assumption)
  - Checks against all business hour configurations for the day
  - Uses day-specific business hour validation

## Business Logic Implementation

### Time Interval Processing

**Hour-by-Hour Calculation**:
- Uses DateInterval('PT1H') for precise hour-based calculations
- Handles negative hour calculations with interval inversion
- Provides accurate business hour counting across date boundaries

**Business Day Logic**:
- Extracts day of week using `$datetime->format('l')`
- Retrieves day-specific business hour configurations
- Validates time against multiple business hour periods per day

### Caching Optimization

**Performance Enhancement**:
- **$cached Array**: Stores frequently accessed business hour data
- **$businessHoursSet**: Caches business hours configuration status
- Reduces database queries for repeated business hour checks

### Date Boundary Handling

**Cross-Day Calculations**:
- Properly handles business hour calculations spanning multiple days
- Adjusts for weekend and holiday exclusions
- Ensures accurate scheduling across business day boundaries

## Integration Points

### SuiteCRM Core Integration

**Basic Class Extension**:
- Extends SuiteCRM's Basic class for standard module functionality
- Inherits standard database operations and ORM capabilities
- Maintains compatibility with SuiteCRM framework patterns

**DateTime Integration**:
- Uses PHP DateTime objects for precise time calculations
- Integrates with SuiteCRM's date/time handling systems
- Supports timezone-aware business hour calculations

### Module System Integration

**Project Management Integration**:
- Supports project scheduling with business hour constraints
- Enables accurate task duration calculations
- Provides foundation for deadline and milestone planning

**Service Level Agreement (SLA) Support**:
- Enables SLA calculations based on business hours
- Supports response time tracking within business operations
- Provides accurate escalation timing

## Usage Context

### Business Operations Management

**Scheduling System**:
- Validates appointment scheduling within business hours
- Calculates accurate delivery timeframes
- Supports resource planning based on operational hours

**Time Tracking Integration**:
- Enables business hour-aware time logging
- Supports accurate billing calculations
- Provides foundation for productivity analysis

### Service Management

**Response Time Calculations**:
- Calculates accurate response times during business hours
- Supports SLA compliance monitoring
- Enables proper escalation timing

**Project Planning**:
- Provides realistic project duration estimates
- Supports resource allocation during business hours
- Enables accurate milestone and deadline planning

## Error Handling and Validation

### Input Validation
- Handles null DateTime parameters with appropriate defaults
- Validates business hour configurations before calculations
- Provides fallback behavior when business hours are not configured

### Calculation Accuracy
- Uses precise interval-based calculations for time arithmetic
- Handles negative hour calculations correctly
- Ensures boundary conditions are properly addressed

## Related Files

- `modules/AOBH_BusinessHours/vardefs.php` - Database field definitions
- `modules/AOBH_BusinessHours/Menu.php` - Module navigation configuration
- `modules/AM_ProjectTemplates/` - Project management integration
- Project and task scheduling modules that utilize business hours

## Dependencies

### Core Framework
- **Basic Class**: SuiteCRM's basic module functionality
- **DateTime**: PHP's DateTime class for time calculations
- **DateInterval**: PHP's interval class for time arithmetic

### System Integration
- **Database Layer**: For business hours configuration storage
- **Caching System**: For performance optimization
- **Module Framework**: For SuiteCRM integration

## Notes

- Critical component for business hour-aware scheduling and time calculations
- Provides foundation for accurate project management and SLA compliance
- Implements sophisticated caching for optimal performance
- Supports complex business hour configurations with multiple periods per day
- Essential for enterprise-level time management and scheduling functionality
- Integrates seamlessly with SuiteCRM's date/time handling systems 