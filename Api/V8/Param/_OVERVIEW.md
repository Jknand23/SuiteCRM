/**
 * @fileoverview This directory contains the parameter validation system for the SuiteCRM V8 API. The parameter system uses the Symfony OptionsResolver component to validate and configure request parameters for each API endpoint.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Parameter System Overview

## Overview

The `Api/V8/Param/` directory contains the parameter validation system for the SuiteCRM V8 API. This system uses the Symfony OptionsResolver component to validate and configure request parameters for each API endpoint.

## Architecture

### Base Classes

-   `BaseParam.php`: Abstract base class for all parameter classes
-   `Options/BaseOption.php`: Abstract base class for parameter option definitions

### Parameter Classes

Each API endpoint has its own parameter class that extends `BaseParam`:

#### Module Operations
-   `GetModuleParams.php`: Parameters for retrieving a single module record
-   `GetModulesParams.php`: Parameters for retrieving multiple module records
-   `CreateModuleParams.php`: Parameters for creating a module record
-   `UpdateModuleParams.php`: Parameters for updating a module record
-   `DeleteModuleParams.php`: Parameters for deleting a module record

#### Relationship Operations
-   `GetRelationshipParams.php`: Parameters for retrieving relationships
-   `CreateRelationshipParams.php`: Parameters for creating relationships
-   `CreateRelationshipByLinkParams.php`: Parameters for creating relationships by link
-   `DeleteRelationshipParams.php`: Parameters for deleting relationships

#### Metadata Operations
-   `GetFieldListParams.php`: Parameters for retrieving field lists
-   `GetUserPreferencesParams.php`: Parameters for retrieving user preferences
-   `ListViewSearchParams.php`: Parameters for list view search definitions
-   `ListViewColumnsParams.php`: Parameters for list view column definitions

#### Data Classes
-   `CreateModuleDataParams.php`: Data structure for module creation
-   `UpdateModuleDataParams.php`: Data structure for module updates
-   `GetRelationshipDataParams.php`: Data structure for relationship data
-   `PageParams.php`: Data structure for pagination parameters

### Parameter Options

The `Options/` directory contains reusable parameter option classes:

-   `ModuleName.php`: Validates module names
-   `Id.php`: Validates record IDs
-   `Fields.php`: Validates field lists
-   `Filter.php`: Validates filter parameters
-   `Sort.php`: Validates sort parameters
-   `Page.php`: Validates pagination parameters
-   `Type.php`: Validates data types
-   `LinkFieldName.php`: Validates relationship link field names
-   `Attributes.php`: Validates attribute objects

## How It Works

1. **Parameter Definition**: Each endpoint defines a parameter class that extends `BaseParam`
2. **Option Configuration**: The parameter class specifies which options it accepts using option classes
3. **Validation**: The `ParamsMiddleware` uses the parameter class to validate incoming requests
4. **Resolution**: The Symfony OptionsResolver validates and normalizes the parameters
5. **Access**: Controllers access validated parameters through getter methods

## Example Usage

```php
// In a parameter class
class GetModuleParams extends BaseParam
{
    protected function configureParameters(OptionsResolver $resolver)
    {
        $this->setOptions($resolver, [
            ParamOption\ModuleName::class,
            ParamOption\Id::class,
            ParamOption\Fields::class,
        ]);
    }
    
    public function getModuleName()
    {
        return $this->parameters['moduleName'];
    }
}

// In middleware
$params->configure($requestParameters);

// In controller
$moduleName = $params->getModuleName();
```

## Associated Components

-   `Api\V8\Middleware\ParamsMiddleware`: Uses parameter classes to validate requests
-   `Api\V8\Factory\ValidatorFactory`: Creates validators for parameter options
-   `Api\V8\BeanDecorator\BeanManager`: Used for bean-related validations
-   `Symfony\Component\OptionsResolver\OptionsResolver`: Core validation component 