
/**
 * @fileoverview This file defines the `ModuleService` for the SuiteCRM V8 API. This is the central service for all CRUD (Create, Read, Update, Delete) operations on module records. It contains the core business logic for interacting with SuiteCRM's data, and it is used by the `ModuleController`.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Module Service

## Overview

The `ModuleService.php` file, located in `Api/V8/Service/`, defines the `ModuleService` class. This service is the heart of the V8 API, containing the business logic for all CRUD (Create, Read, Update, Delete) operations on module records.

## Constructor

The constructor takes the following arguments:

-   `BeanManager $beanManager`: An instance of the `BeanManager`.
-   `AttributeObjectHelper $attributeHelper`: A helper for creating JSON:API attribute objects.
-   `RelationshipObjectHelper $relationshipHelper`: A helper for creating JSON:API relationship objects.
-   `PaginationObjectHelper $paginationHelper`: A helper for creating JSON:API pagination objects.

## Methods

### `getRecord(GetModuleParams $params, $path)`

This method retrieves a single record from a module. It checks for ACL access and then uses the `getDataResponse()` helper method to build the JSON:API response.

### `getRecords(GetModulesParams $params, Request $request)`

This method retrieves a list of records from a module. It supports pagination, filtering, and sorting. It also has special handling for queries that involve the email address field.

### `createRecord(CreateModuleParams $params, Request $request)`

This method creates a new record in a module. It checks for ACL access, sets the record's attributes, handles file uploads, and then saves the record.

### `updateRecord(UpdateModuleParams $params, Request $request)`

This method updates an existing record in a module. It checks for ACL access, sets the record's attributes, handles file uploads, and then saves the record.

### `deleteRecord(DeleteModuleParams $params)`

This method deletes a record from a module. It checks for ACL access and then calls the `mark_deleted()` method on the bean.

### Helper Methods

The `ModuleService` has a number of protected and private helper methods that it uses to perform its work. These include:

-   `addFileToDocument()`: Handles adding a file to a `Document` record.
-   `addFileToNote()`: Handles adding a file to a `Note` record.
-   `processAttributes()`: Processes the attributes from a create or update request.
-   `setRecordUpdateParams()`: Sets the update parameters on a bean before saving.
-   `getDataResponse()`: Builds the JSON:API `DataResponse` object for a given bean.

## Associated Components

-   `Api\V8\Controller\ModuleController`: The controller that uses this service.
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\JsonApi\Helper\*`: Helper classes for creating JSON:API objects.
-   `Api\V8\Param\*`: The parameter objects that contain the validated request parameters.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Api\V8\JsonApi\Response\*`: The classes used to build the JSON:API response.
-   `SugarBean`: The base class for all of SuiteCRM's data objects.
-   `SuiteCRM\Exception\*`: The exception classes used by the service. 