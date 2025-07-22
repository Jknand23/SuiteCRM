/**
 * @fileoverview Campaign tracking script that logs campaign activity and redirects users to specified URLs. This file handles external campaign link tracking by validating tracker keys, logging campaign interactions, and performing secure redirects to campaign target URLs stored in the database.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# Campaign Tracker

## Overview

The `campaign_tracker.php` file serves as an external entry point for tracking campaign interactions and redirecting users to campaign target URLs. This script is designed to be called from external sources (such as email links) to track campaign effectiveness and provide secure redirection functionality.

## Database Operations

### Campaign Activity Logging
- **Function**: `log_campaign_activity($_REQUEST['identifier'], 'link')`
- **Purpose**: Records campaign interaction data when an identifier is provided
- **Table**: Uses campaign-related tables through the campaigns utils module
- **Data**: Logs link click events with campaign identifiers

### Campaign URL Retrieval
- **Query**: `SELECT refer_url FROM campaigns WHERE tracker_key='$track'`
- **Purpose**: Retrieves the target URL associated with a specific campaign tracker key
- **Security**: Uses database quote method to prevent SQL injection
- **Validation**: Validates tracker key format using regex pattern

## Internal API Calls

### Entry Point Initialization
- **File**: `include/entryPoint.php`
- **Purpose**: Initializes the SuiteCRM environment and database connections
- **Dependencies**: Establishes `$db` database connection object

### Campaign Utilities
- **File**: `modules/Campaigns/utils.php`
- **Purpose**: Provides campaign-specific utility functions including activity logging
- **Functions**: Contains `log_campaign_activity()` function for tracking interactions

### System Cleanup
- **Function**: `sugar_cleanup()`
- **Purpose**: Performs system cleanup before script termination
- **Timing**: Called before both successful redirects and error exits

## External API Calls

### HTTP Redirection
- **Method**: `header("Location: $redirect_URL")`
- **Purpose**: Redirects users to the campaign target URL
- **Security**: Only executes after successful tracker key validation
- **Protocol**: Uses standard HTTP 302 redirect

## UI Functionality

### URL Parameters
- **identifier**: Optional campaign identifier for activity logging
- **track**: Campaign tracker key for URL lookup and redirection

### Input Validation
- **Tracker Key Pattern**: `/^[0-9A-Za-z\-]*$/`
- **Purpose**: Ensures tracker keys contain only alphanumeric characters and hyphens
- **Security**: Prevents injection attacks and malformed requests

### Error Handling
- **Invalid Keys**: Script exits silently without redirection for invalid tracker keys
- **Missing Data**: Handles empty or missing parameters gracefully
- **Database Errors**: Relies on underlying database error handling

## Workflow Process

1. **Entry Point Setup**: Defines `sugarEntry` constant and initializes SuiteCRM environment
2. **Parameter Processing**: Extracts and validates `identifier` and `track` parameters
3. **Activity Logging**: Logs campaign interaction if identifier is provided
4. **Tracker Validation**: Validates tracker key format using regex
5. **Database Query**: Retrieves campaign URL using validated tracker key
6. **Redirection**: Performs HTTP redirect to target URL if found
7. **Cleanup**: Calls system cleanup and exits script

## Security Features

### Input Sanitization
- Database quote method prevents SQL injection
- Regex validation limits acceptable tracker key characters
- Parameter type casting ensures string handling

### Error Containment
- Silent failure prevents information disclosure
- System cleanup ensures proper resource management
- Controlled exit points prevent execution continuation

## Integration Points

### Campaign Management
- **Module**: Campaigns
- **Relationship**: Links external tracking to internal campaign records
- **Data Flow**: External clicks → tracking data → campaign analytics

### Database Schema
- **Table**: campaigns
- **Key Field**: tracker_key (unique identifier)
- **URL Field**: refer_url (target destination) 