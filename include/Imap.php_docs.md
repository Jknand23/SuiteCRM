# Imap.php Documentation

/**
 * @fileoverview IMAP wrapper class providing object-oriented interface to PHP's IMAP extension
 * @package SuiteCRM
 * @namespace SuiteCRM
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2018)
 * @license AGPL-3.0
 */

## Overview

The `Imap` class provides a modern, object-oriented wrapper around PHP's built-in IMAP functions. It implements the `ImapInterface` and serves as an abstraction layer for IMAP operations within SuiteCRM's email processing system.

## Class Definition

### Imap
- **Namespace**: `SuiteCRM`
- **Implements**: `ImapInterface`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility

## Properties

### $resource
- **Type**: `resource`
- **Visibility**: `protected`
- **Purpose**: Stores the IMAP connection resource returned by `imap_open()`

## Methods

### open()
```php
public function open($mailbox, $username, $password, $options = 0, $n_retries = 0, array $params = null)
```

**Purpose**: Establishes IMAP connection to mailbox server

**Parameters**:
- `$mailbox` (string): Mailbox specification (e.g., `{imap.example.com:993/imap/ssl}INBOX`)
- `$username` (string): Authentication username
- `$password` (string): Authentication password
- `$options` (int): Bitmask of connection options (default: 0)
- `$n_retries` (int): Number of retry attempts (default: 0)
- `$params` (array|null): Additional connection parameters (default: null)

**Returns**: `resource|false` - IMAP connection resource on success, false on failure

**Integration**: Directly wraps PHP's `imap_open()` function while maintaining the resource for future operations

## External API Calls

### PHP IMAP Extension
- **Function**: `imap_open()`
- **Purpose**: Creates IMAP connection to mail server
- **Error Handling**: Returns false on connection failure
- **Resource Management**: Stores connection resource in `$resource` property

## Integration Points

### Email Processing
- Used by SuiteCRM's inbound email functionality
- Integrates with email account configuration
- Part of the email import and synchronization system

### Interface Implementation
- Implements `ImapInterface` for standardized IMAP operations
- Provides abstraction layer for testability and mock implementations
- Enables dependency injection in email processing classes

## Usage Examples

### Basic Connection
```php
$imap = new SuiteCRM\Imap();
$connection = $imap->open(
    '{mail.example.com:993/imap/ssl}INBOX',
    'username@example.com',
    'password'
);
```

### Connection with Options
```php
$imap = new SuiteCRM\Imap();
$connection = $imap->open(
    '{mail.example.com:143/imap}INBOX',
    'username',
    'password',
    OP_HALFOPEN, // Options
    3            // Retries
);
```

## Security Considerations

- Credentials are passed directly to PHP's IMAP extension
- SSL/TLS encryption should be specified in mailbox string
- Connection resources should be properly closed after use
- Implements interface pattern for secure dependency injection

## Dependencies

- Requires PHP IMAP extension to be installed and enabled
- Depends on `ImapInterface` definition
- Part of SuiteCRM's email processing infrastructure 