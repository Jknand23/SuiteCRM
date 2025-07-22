
/**
 * @fileoverview This file defines the `ConfigResolver` class, a utility responsible for loading and merging configuration files for the SuiteCRM API. It provides a robust mechanism for handling multiple configuration sources, ensuring that they are valid and readable before combining them into a single configuration array. This class is an essential part of the API's flexible configuration system.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# Configuration File Resolver

## Overview

The `ConfigResolver` class, located in `Api/Core/Resolver/`, is a utility designed to handle the loading and merging of configuration files. It plays a critical role in the API's startup process by providing a standardized and error-resistant way to aggregate configuration settings from multiple files.

### `loadFiles(array $files)`

This is the primary static method of the class. It takes an array of file paths and performs the following steps:

1.  **Iterate Through Files**: It loops through the provided array of file paths.

2.  **Construct Full Path**: For each file, it constructs the full, absolute path by prepending the global `$BASE_DIR` variable. This ensures that files are resolved correctly relative to the SuiteCRM root directory.

3.  **Check and Require File**: It calls the `isFileExist()` method to verify that the file exists and is readable. If the check passes, it includes the file using `require`.

4.  **Validate Configuration**: It checks that the included file returns an array. If it doesn't, it throws an `InvalidArgumentException`, preventing malformed configuration files from breaking the application.

5.  **Aggregate Configurations**: It adds the configuration array from each file to an array of configurations.

6.  **Merge Configurations**: After processing all the files, it uses `array_reduce` with `array_merge` to merge all the individual configuration arrays into a single, unified array. It's noted that this approach is used for compatibility with older PHP versions.

### `isFileExist($file)`

This static helper method is used to check if a given file path points to a file that exists and is readable.

-   If the file does not exist or is not readable, it throws a `RuntimeException`. This provides a clear and immediate failure for a missing or improperly permissioned configuration file, which aids in debugging setup issues.
-   If the file is valid, it returns `true`.

## Error Handling

The `ConfigResolver` is designed to be strict about its inputs. It will throw exceptions in the following cases:

-   `InvalidArgumentException`: If a configuration file does not return an array.
-   `RuntimeException`: If a configuration file does not exist or is not readable.

This fail-fast approach helps to quickly identify problems with the API's configuration. 