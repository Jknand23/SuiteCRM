
/**
 * @fileoverview This file serves as the main entry point for the SuiteCRM API. It initializes the application by changing the current working directory to the root of the SuiteCRM instance and then includes and executes the core application logic from `Api/Core/app.php`. This setup ensures that all relative file paths within the application are resolved correctly from the project root.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM API Entry Point

## Overview

The `index.php` file in the `Api/` directory is the primary gateway for all API requests. Its main responsibilities are:

1.  **Directory Initialization**: It changes the PHP script's current directory to the root of the SuiteCRM installation. This is crucial for ensuring that the application's file includes, configurations, and other resources are loaded with the correct paths.

2.  **Application Bootstrap**: It includes the `app.php` file from the `Api/Core/` directory. This file contains the core application logic, including the initialization of the API framework, routing, and other essential services.

3.  **Application Execution**: It calls the `run()` method on the application instance, which starts the request handling process, executes the appropriate controller and action based on the request URI, and sends a response back to the client.

## Usage

All API endpoints are accessed through this file. For example, a request to `/Api/V8/module/Accounts` would be processed by this `index.php` file, which would then delegate the request to the appropriate handler in the V8 API. 