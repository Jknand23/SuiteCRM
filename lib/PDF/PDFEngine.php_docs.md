# PDFEngine.php Documentation

## @fileoverview
Abstract base class defining the contract for PDF generation engines within SuiteCRM. Establishes standard interface for PDF creation, content management, and output operations across different PDF library implementations.

## @package
SuiteCRM\PDF

## @copyright
Copyright (C) 2011 - 2021 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### PDFEngine
Abstract foundation class that defines the essential interface for all PDF generation engines in SuiteCRM. Provides standardized method signatures for HTML content processing, header/footer management, styling, and PDF output operations.

## Dependencies

### Security
- Includes sugarEntry validation for secure access control

## Properties

### Static Properties
- **$configMapperFile** (string): Configuration mapper file path for engine-specific settings

## Abstract Methods

### writeHTML(string $html): void
**Purpose**: Processes and renders HTML content into the PDF document body.

**Parameters**:
- `$html` (string): HTML content to render in the PDF document

**Implementation Requirements**:
- Parse and render HTML content accurately
- Support CSS styling and layout
- Handle complex HTML structures and formatting
- Maintain content fidelity during conversion

**Use Cases**: Main content rendering for documents, reports, templates.

### writeFooter(string $html): void
**Purpose**: Sets footer content for PDF pages using HTML markup.

**Parameters**:
- `$html` (string): HTML content for page footers

**Implementation Requirements**:
- Render footer content on each page
- Support HTML formatting in footer area
- Handle page-specific footer variables
- Maintain consistent footer positioning

**Use Cases**: Page numbering, document metadata, branding elements.

### writeHeader(string $html): void
**Purpose**: Sets header content for PDF pages using HTML markup.

**Parameters**:
- `$html` (string): HTML content for page headers

**Implementation Requirements**:
- Render header content on each page
- Support HTML formatting in header area
- Handle page-specific header variables
- Maintain consistent header positioning

**Use Cases**: Document titles, logos, company information, page metadata.

### addCSS(string $css): void
**Purpose**: Applies CSS styles to the PDF document for formatting and layout.

**Parameters**:
- `$css` (string): CSS stylesheet rules to apply

**Implementation Requirements**:
- Parse and apply CSS rules to document elements
- Support standard CSS properties for PDF context
- Handle CSS inheritance and cascading
- Optimize styles for PDF output format

**Styling Support**: Colors, fonts, layouts, spacing, borders, backgrounds.

### writeBlankPage(): void
**Purpose**: Inserts a blank page into the PDF document.

**Implementation Requirements**:
- Add new page with no content
- Respect document margins and page settings
- Maintain page numbering sequence
- Support page break functionality

**Use Cases**: Page separation, formatting requirements, document structure.

### outputPDF(string $name, string $destination, string $fullName = ''): ?string
**Purpose**: Generates final PDF output with specified name and destination options.

**Parameters**:
- `$name` (string): Base filename for the PDF document
- `$destination` (string): Output destination mode (I, D, F, S)
- `$fullName` (string): Optional full path for file output

**Returns**: String content for inline/string output modes, null for file/download modes.

**Destination Modes**:
- **I**: Inline browser display
- **D**: Download/attachment
- **F**: File system save
- **S**: String return

**Implementation Requirements**:
- Handle all destination modes appropriately
- Generate properly formatted PDF output
- Manage file system operations for save modes
- Return appropriate content based on destination

### configurePDF(array $options): void
**Purpose**: Configures PDF engine settings and options for document generation.

**Parameters**:
- `$options` (array): Configuration options for PDF generation

**Implementation Requirements**:
- Apply configuration options to engine settings
- Validate configuration parameters
- Support engine-specific customization options
- Maintain configuration state throughout generation

**Configuration Options**: Page size, orientation, margins, metadata, security settings.

## Implementation Guidelines

### Engine Development
- Extend PDFEngine class for custom implementations
- Implement all abstract methods according to specifications
- Maintain consistent behavior across different engines
- Support standard PDF features and capabilities

### Content Processing
- Handle HTML parsing and rendering accurately
- Support CSS styling for proper document formatting
- Manage page layout and content flow
- Ensure content accessibility and structure

### Output Management
- Support all required destination modes
- Handle file operations securely and efficiently
- Generate standards-compliant PDF documents
- Provide appropriate error handling and feedback

## Integration Points

### PDFWrapper Integration
- Registered through PDFWrapper::addEngine()
- Instantiated via PDFWrapper::getPDFEngine()
- Managed through engine registry system
- Configured via PDFConfigurator settings

### Template System
- Integration with SuiteCRM's PDF template system
- Support for dynamic content generation
- Variable substitution and data binding
- Template-based document creation

### Module Integration
- Used by various SuiteCRM modules for PDF generation
- Support for module-specific PDF requirements
- Integration with business logic and data models
- Customizable output for different use cases

## Design Patterns

### Template Method Pattern
- Abstract class defines PDF generation workflow
- Concrete implementations provide engine-specific logic
- Consistent interface across different PDF libraries
- Extensible architecture for new engines

### Strategy Pattern
- Interchangeable PDF generation strategies
- Runtime selection of appropriate engines
- Configurable behavior based on requirements
- Support for multiple PDF library backends

## Security Considerations

### Content Security
- Secure handling of HTML content and user input
- Prevention of XSS vulnerabilities in PDF content
- Sanitization of CSS and styling inputs
- Protection against malicious content injection

### File Operations
- Secure file system operations for PDF output
- Validation of file paths and destinations
- Protection against directory traversal attacks
- Appropriate file permissions and access control

## Performance Considerations

### Memory Management
- Efficient handling of large documents and content
- Memory optimization for PDF generation operations
- Resource cleanup after document generation
- Support for streaming and chunked processing

### Processing Efficiency
- Optimized HTML and CSS processing
- Efficient PDF generation algorithms
- Minimal resource overhead for operations
- Scalable performance for high-volume scenarios

## Error Handling

### Implementation Requirements
- Comprehensive error handling for all operations
- Meaningful error messages for troubleshooting
- Exception-based error reporting
- Graceful degradation for non-critical failures

### Common Error Scenarios
- Invalid HTML or CSS content
- File system operation failures
- PDF generation library errors
- Configuration and setup issues 