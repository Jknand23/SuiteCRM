# SugarSpriteBuilder.php Documentation

## @fileoverview
Advanced CSS sprite generation system that combines multiple small images into optimized sprite sheets with corresponding CSS and metadata files, improving web performance by reducing HTTP requests and optimizing image loading.

## @package Administration
## @copyright SalesAgility Ltd
## @license GNU AGPL v3

## Overview
SugarSpriteBuilder.php provides comprehensive CSS sprite generation functionality for SuiteCRM themes and modules. It automatically combines small images into larger sprite sheets, generates corresponding CSS rules, and creates metadata files for efficient image management and improved web performance.

## Database Operations
This component primarily operates on the file system and does not perform direct database operations. However, it integrates with the system's caching mechanism and configuration management.

### Configuration Integration
- **System configuration**: Reads sprite-related settings from `$sugar_config`
- **Cache management**: Utilizes SuiteCRM's caching system for sprite storage
- **Performance tracking**: May log sprite generation metrics for system optimization

## Internal API Calls

### Core Image Processing
- **GD Library integration**: Uses PHP GD functions for image manipulation and sprite creation
- **Image type detection**: Supports GIF, JPEG, and PNG formats through `getimagesize()`
- **Memory management**: Efficiently manages image resources with `imagedestroy()`

### File System Operations
- **sugar_cached()**: Manages cache directory structure for sprite storage
- **sugar_mkdir()**: Creates sprite directory structure with proper permissions
- **sugar_file_put_contents()**: Writes CSS and metadata files securely
- **sugar_fopen/sugar_fclose**: Handles file operations for metadata generation

### CSS Generation and Optimization
- **cssmin::minify()**: CSS minification for optimized sprite stylesheets
- **getVersionedPath()**: Generates versioned paths for cache-busting
- **CSS rule generation**: Creates efficient CSS rules for sprite positioning

### Configuration and Metadata
- **Sprite configuration loading**: Processes `sprites_config.php` files for custom settings
- **Metadata serialization**: Generates PHP metadata arrays for sprite management
- **Version integration**: Links sprites to system versioning for cache management

## External API Calls

### Image Processing Operations
- **imagecreatetruecolor()**: Creates optimized sprite canvas with transparency support
- **imagecopy()**: Combines source images into sprite canvas with precise positioning
- **imagepng()**: Exports optimized PNG sprites with compression settings
- **imagecolorallocatealpha()**: Manages transparent backgrounds for sprite optimization

### File Type Validation
- **getimagesize()**: Validates image files and extracts dimension information
- **Image format detection**: Automatically detects and validates supported image types
- **File extension verification**: Ensures proper file type handling and security

### Directory Scanning
- **opendir/readdir/closedir**: Scans directories for eligible sprite images
- **File filtering**: Applies sprite configuration rules for file inclusion/exclusion
- **Recursive directory processing**: Handles complex directory structures

## UI Functionality

### Sprite Generation Interface
- **Silent operation mode**: Supports both interactive and background sprite generation
- **Progress reporting**: Provides status updates during sprite generation process
- **Error reporting**: Comprehensive error handling with user-friendly messages

### Administrative Integration
- **Admin panel integration**: Accessible through Administration module's repair tools
- **Rebuild functionality**: On-demand sprite regeneration through admin interface
- **Configuration management**: Administrative control over sprite generation settings

### Performance Optimization
- **Batch processing**: Efficient handling of multiple sprite namespaces
- **Memory optimization**: Careful memory management for large sprite operations
- **Caching integration**: Optimized caching for improved sprite delivery

## Associated Tests

### Image Processing Testing
- **GD library availability**: Tests for required image processing capabilities
- **Image format support**: Validates support for GIF, JPEG, and PNG formats
- **Sprite generation accuracy**: Tests sprite positioning and CSS generation

### File System Testing
- **Directory creation**: Tests sprite directory structure creation
- **File permission validation**: Ensures proper file and directory permissions
- **Cache integration testing**: Validates sprite caching functionality

### CSS Generation Testing
- **CSS rule accuracy**: Tests generated CSS rules for proper sprite positioning
- **Minification testing**: Validates CSS minification and optimization
- **Metadata generation**: Tests PHP metadata array generation and serialization

### Performance Testing
- **Memory usage monitoring**: Tests memory efficiency during sprite generation
- **Processing time analysis**: Measures sprite generation performance
- **Output size optimization**: Validates sprite compression and optimization

## Key Classes and Components

### SugarSpriteBuilder Class
Main sprite generation class that orchestrates the entire sprite creation process.

#### Core Properties
- **$isAvailable**: GD library availability detection
- **$supportedTypeMap**: Mapping of supported image types to GD constants
- **$maxWidth/$maxHeight**: Maximum dimensions for individual sprite images
- **$imageTypes**: Available image type support matrix

#### Configuration Properties
- **$pngCompression**: PNG compression level (0-9) for optimized output
- **$pngFilter**: PNG filtering options for size optimization
- **$rowCnt**: Number of images per row in boxed sprite layout
- **$cssMinify**: CSS minification enable/disable flag

#### Data Structures
- **$spriteSrc**: Organized collection of source images by namespace and directory
- **$spriteRepeat**: Special handling for repeatable background sprites
- **$sprites_config**: Configuration data loaded from sprites_config.php files

### SpritePlacement Class
Specialized class for calculating optimal sprite positioning and layout algorithms.

#### Layout Algorithms
- **Boxed layout**: Grid-based positioning for uniform-sized sprites
- **Horizontal layout**: Linear horizontal arrangement for repeatable sprites
- **Vertical layout**: Linear vertical arrangement for repeatable sprites

#### Optimization Features
- **Surface area calculation**: Minimizes total sprite canvas size
- **Coordinate optimization**: Calculates precise positioning for minimal waste
- **Dimension calculation**: Determines optimal sprite canvas dimensions

## Key Methods

### addDirectory($name, $dir)
Adds a directory of images to a sprite namespace, processing all eligible images and applying configuration rules.

### createSprites()
Main sprite generation method that orchestrates the complete sprite creation process including image combination, CSS generation, and metadata creation.

### getFileList($dir)
Scans directory for eligible sprite images, applies exclusion rules, and categorizes images by type and repeat patterns.

### loadSpritesConfig($dir)
Loads optional sprites_config.php files that define exclusion rules, repeat patterns, and custom sprite behaviors.

### initSpriteImg($w, $h)
Creates optimized sprite canvas with transparency support and proper alpha channel handling.

### loadImage($dir, $file, $type)
Loads individual source images using appropriate GD functions based on detected image type.

## Sprite Configuration System

### sprites_config.php Files
Optional configuration files that customize sprite generation behavior:

#### Exclusion Rules
```php
$sprites_config['directory/path']['exclude'] = array('file1.png', 'file2.gif');
```

#### Repeat Pattern Definition
```php
$sprites_config['directory/path']['repeat'] = array(
    array('width' => 16, 'height' => 16, 'direction' => 'horizontal'),
    array('width' => 32, 'height' => 32, 'direction' => 'vertical')
);
```

### Configuration Inheritance
- **Directory-specific rules**: Apply to specific directories only
- **Global configuration**: System-wide sprite generation settings
- **Override capabilities**: Local configurations override global settings

## Output Structure

### Generated Files
- **sprites.png**: Optimized sprite image file with combined images
- **sprites.css**: Generated CSS rules for sprite positioning
- **sprites.meta.php**: PHP metadata array for programmatic sprite access

### CSS Structure
```css
/* Auto-generated sprite CSS */
span.spr_[hash] {
    background: url('path/to/sprite.png') no-repeat;
    width: [width]px;
    height: [height]px;
    background-position: -[x]px -[y]px;
}
```

### Metadata Structure
```php
$sprites["path/to/image.png"] = array(
    "class" => "hash_id",
    "width" => "width",
    "height" => "height"
);
```

## Performance Optimization Features

### Image Optimization
- **PNG compression**: Configurable compression levels for size optimization
- **Transparency preservation**: Maintains alpha channel information
- **Format optimization**: Automatic format selection for best compression

### CSS Optimization
- **Minification**: Optional CSS minification for reduced file size
- **Efficient selectors**: Optimized CSS selector generation
- **Cache-friendly URLs**: Versioned sprite URLs for optimal caching

### Memory Management
- **Resource cleanup**: Automatic cleanup of image resources
- **Batch processing**: Efficient handling of large sprite sets
- **Memory limit awareness**: Respects PHP memory limits during processing

## Integration Points

### Theme System Integration
- **Theme-specific sprites**: Generates sprites for individual themes
- **Theme inheritance**: Supports sprite inheritance in theme hierarchies
- **Dynamic loading**: Runtime sprite loading based on active theme

### Module Integration
- **Module-specific sprites**: Supports module-specific sprite generation
- **Extension compatibility**: Works with custom modules and extensions
- **Upgrade safety**: Preserves custom sprites during system upgrades

### Caching Integration
- **Cache directory management**: Integrates with SuiteCRM's cache system
- **Cache invalidation**: Automatic cache clearing when sprites are regenerated
- **Performance monitoring**: Tracks sprite generation and usage metrics

## Error Handling and Logging

### Comprehensive Error Reporting
- **GD library detection**: Clear messaging when image processing unavailable
- **File permission errors**: Detailed feedback for permission issues
- **Image format errors**: Specific error messages for unsupported formats
- **Memory limit warnings**: Alerts for insufficient memory conditions

### Logging Integration
- **System logging**: Integration with SuiteCRM's logging system
- **Upgrade logging**: Special logging during silent upgrade operations
- **Debug information**: Detailed logging for troubleshooting sprite issues

## Security Considerations

### File Validation
- **Image type verification**: Strict validation of image file types
- **File extension checking**: Security validation of file extensions
- **Content validation**: Verification of actual image content vs. extension

### Path Security
- **Directory traversal protection**: Prevents malicious path manipulation
- **Secure file operations**: Uses SuiteCRM's secure file handling functions
- **Permission management**: Appropriate file and directory permissions

## Configuration Dependencies
- **GD library**: Required for image processing operations
- **Memory limits**: Adequate PHP memory for image processing
- **File permissions**: Write access to cache directories
- **Image format support**: GD support for GIF, JPEG, and PNG formats 