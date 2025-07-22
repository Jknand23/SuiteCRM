
/**
 * @fileoverview This file defines the `ListViewSearchService` for the SuiteCRM V8 API. This service is responsible for retrieving the search definitions for a module's list view, transforming the data into a JSON:API compliant format, and handling the translation of field labels.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API List View Search Service

## Overview

The `ListViewSearchService.php` file, located in `Api/V8/Service/`, defines the `ListViewSearchService` class. This service contains the business logic for retrieving the search field definitions for a module's list view. It is used by the `ListViewSearchController`.

## Constructor

The controller's constructor takes a single argument:

-   `BeanManager $beanManager`: An instance of the `BeanManager`. This service is injected by the dependency injection container and is used to interact with SuiteCRM's beans.

## `getListViewSearchDefs(ListViewSearchParams $params)`

This is the main method of the service. It is responsible for retrieving and formatting the list view search definitions.

### Arguments:

-   `ListViewSearchParams $params`: An object that contains the validated request parameters, including the module name.

### Logic:

1.  **Retrieve Search Definitions**: It uses the static `SearchForm::retrieveSearchDefs()` method to get the raw search definitions for the specified module.
2.  **Retrieve List View Definitions**: It uses the static `ListViewFacade::getDisplayColumns()` method to get the list view column definitions, which are used for translations.
3.  **Simplify Data Structure**: It creates a simplified array containing the module name, the template metadata, the basic and advanced search layouts, and the search fields.
4.  **Translate Labels**: It uses a helper method, `getDataTranslated()`, to translate the labels for the basic search fields, the advanced search fields, and the search fields themselves.
5.  **Generate Response**: It creates a `DocumentResponse` object, which is the top-level object in a JSON:API response. It then creates a `DataResponse` and an `AttributeResponse` to hold the simplified and translated data, and it adds these to the `DocumentResponse`.

### `getDataTranslated($trans, $data, $part, $valueKey, $displayColumns)`

This is a protected helper method that is used to translate the labels for the different parts of the search definitions.

## Associated Components

-   `Api\V8\Controller\ListViewSearchController`: The controller that uses this service.
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\Param\ListViewSearchParams`: The parameter object that contains the validated request parameters.
-   `SearchForm`: A legacy class used to retrieve search definitions.
-   `ListViewFacade`: A legacy class used to retrieve list view definitions.
-   `SuiteCRM\LangText`: A class used for translating labels.
-   `Api\V8\JsonApi\Response\*`: The classes used to build the JSON:API response. 