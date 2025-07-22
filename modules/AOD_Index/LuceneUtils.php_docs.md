# LuceneUtils.php Documentation

## @fileoverview
**LuceneUtils - Document parsing utilities for Lucene search indexing**
- **Package**: modules/AOD_Index
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Provides document parsing functions to extract text content from various file formats for Lucene indexing
- **Deprecation**: All functions deprecated since v7.12.0

## Overview
This utility file contains functions for parsing different document formats and converting them into Lucene documents suitable for full-text search indexing. Each function extracts text content and creates standardized Lucene document structures.

## File System Operations

### Path Management
#### `getDocumentRevisionPath(string $revisionId): string`
- **Purpose**: Generates file system path for uploaded document revisions
- **Path Pattern**: `"upload/$revisionId"`
- **Usage**: Locates physical files for document parsing
- **Integration**: Used by AOD_Index for file access

## Document Parsing Functions

### Microsoft Office Documents

#### `createDocXDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: Microsoft Word 2007+ (.docx)
- **Parser**: `Zend_Search_Lucene_Document_Docx::loadDocxFile()`
- **Content Extraction**: Reads XML structure from DOCX ZIP archive
- **Fields Created**:
  - `filename`: Text field with document name
  - Document content extracted by Zend parser
- **Returns**: Fully populated Lucene document

#### `createXLSXDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: Microsoft Excel 2007+ (.xlsx)  
- **Parser**: `Zend_Search_Lucene_Document_Xlsx::loadXlsxFile()`
- **Content Extraction**: Reads spreadsheet data from XLSX format
- **Fields Created**:
  - `filename`: Text field with document name
  - Spreadsheet content via Zend parser
- **Returns**: Searchable Lucene document

#### `createPPTXDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: Microsoft PowerPoint 2007+ (.pptx)
- **Parser**: `Zend_Search_Lucene_Document_Pptx::loadPptxFile()`
- **Content Extraction**: Extracts text from presentation slides
- **Fields Created**:
  - `filename`: Text field with document name
  - Slide content via Zend parser
- **Returns**: Indexed presentation document

#### `createDocDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: Microsoft Word 97-2003 (.doc)
- **Parsing Method**: Binary file reading with character filtering
- **Process**:
  1. Reads entire file as binary data
  2. Splits content on carriage return (0x0D)
  3. Filters out null characters and empty lines
  4. Cleans text with regex pattern matching
- **Text Cleaning**: Removes non-alphanumeric characters except: `a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)`
- **Fields Created**:
  - `filename`: Text field with document name
  - `contents`: UnStored field with cleaned text

### PDF Documents

#### `createPDFDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: Portable Document Format (.pdf)
- **Parser**: Custom `PdfParser::parseFile()` (requires PdfParser.php)
- **Content Extraction**: Uses specialized PDF text extraction
- **Fields Created**:
  - `filename`: Text field with document name
  - `contents`: UnStored field with extracted text
- **Dependencies**: Requires local PdfParser.php module

### OpenDocument Formats

#### `createOdtDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: OpenDocument Text (.odt)
- **Parsing Method**: ZIP archive processing with XML parsing
- **Process**:
  1. Opens ODT file as ZIP archive
  2. Extracts `content.xml` from package
  3. Parses XML with SimpleXML
  4. Uses XPath to find all text elements (`//text:*`)
  5. Concatenates paragraph content with spaces
- **Error Handling**: Returns `false` if file not found
- **Fields Created**:
  - `filename`: Text field with document name
  - `contents`: UnStored field with UTF-8 encoded text
- **Dependencies**: Requires ZipArchive and SimpleXML extensions

### Web and Text Formats

#### `createHTMLDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: HyperText Markup Language (.html, .htm)
- **Parser**: `Zend_Search_Lucene_Document_Html::loadHTMLFile()`
- **Content Extraction**: Strips HTML tags, extracts text content
- **Fields Created**:
  - `filename`: Text field with document name
  - Clean text content via Zend parser
- **Features**: Handles HTML entity decoding and tag removal

#### `createTextDocument(string $path): Zend_Search_Lucene_Document`
- **Formats**: Plain text (.txt), CSV (.csv)
- **Parsing Method**: Direct file content reading
- **Process**: Uses `file_get_contents()` for simple text extraction
- **Fields Created**:
  - `filename`: Text field with document name
  - `contents`: UnStored field with raw file content
- **Usage**: Fallback for simple text formats

### Rich Text Format

#### `createRTFDocument(string $path): Zend_Search_Lucene_Document`
- **Format**: Rich Text Format (.rtf)
- **Parser**: Custom `rtf2text()` function (complex RTF parser)
- **Content Extraction**: Converts RTF markup to plain text
- **Fields Created**:
  - `filename`: Text field with document name
  - `contents`: UnStored field with converted text
- **Dependencies**: Uses helper functions for RTF processing

## RTF Processing Engine

### Core RTF Parser
#### `rtf2text(string $filename): string`
- **Purpose**: Converts RTF files to plain text by parsing RTF control codes
- **Algorithm**: Character-by-character parsing with control word recognition
- **Features**:
  - **Unicode Support**: Handles `\u` control words with decimal notation
  - **Special Characters**: Converts RTF entities to readable text
  - **Formatting Removal**: Strips RTF formatting while preserving content
  - **Stack-Based Parsing**: Manages nested RTF groups with array stack

### RTF Control Word Processing
- **Backslash Commands**: Processes RTF escape sequences
- **Character Encoding**: Handles hexadecimal character notation (`\'xx`)
- **Unicode Conversion**: Processes Unicode characters (`\uN`)
- **Special Formatting**:
  - Line breaks: `\par`, `\page`, `\column`, `\line`, `\lbr` → `\n`
  - Spaces: `\emspace`, `\enspace`, `\qmspace` → space
  - Tabs: `\tab` → `\t`
  - Special chars: em-dash, en-dash, bullets, quotes

### RTF Helper Functions
#### `rtf_isPlainText(array $stack): bool`
- **Purpose**: Determines if current parsing context allows plain text output
- **Exclusions**: Skips content in font tables, color tables, datastores, theme data
- **Stack Analysis**: Checks current parsing stack for formatting-only content
- **Return**: Boolean indicating whether to include content in output

## Document Structure Standards

### Lucene Field Types
- **Text Fields**: `filename` - stored and indexed for retrieval
- **UnStored Fields**: `contents` - indexed but not stored (saves space)
- **Encoding**: UTF-8 encoding specified where supported
- **Consistency**: All parsers create compatible document structures

### Content Processing
- **Text Cleaning**: Removes formatting artifacts and control characters
- **Encoding Handling**: Proper UTF-8 encoding for international content
- **Space Normalization**: Consistent spacing between text elements
- **Error Resilience**: Graceful handling of malformed documents

## Error Handling and Validation

### File Validation
- **Existence Checks**: Validates file presence before parsing
- **Format Validation**: Implicit validation through parser success/failure
- **Graceful Degradation**: Returns false or empty content for invalid files

### Exception Safety
- **Parser Errors**: Caught by calling code in AOD_Index
- **Memory Management**: Efficient processing of large documents
- **Resource Cleanup**: Proper file handle and archive closure

## Performance Considerations

### Memory Usage
- **Streaming Processing**: Some parsers process documents in chunks
- **Content Filtering**: Early filtering reduces memory overhead
- **Archive Handling**: Efficient ZIP processing for Office formats

### Processing Speed
- **Format Optimization**: Different strategies for different document types
- **Content Limits**: No explicit size limits but memory-constrained
- **Batch Processing**: Designed for scheduler-based bulk indexing

## Dependencies and Requirements

### PHP Extensions
- **ZipArchive**: Required for ODT and Office 2007+ formats
- **SimpleXML**: Needed for XML parsing in document formats
- **Standard Extensions**: File operations, string processing

### External Libraries
- **Zend Lucene**: Core framework for document creation
- **PdfParser**: Custom PDF text extraction (local module)
- **Office Parsers**: Zend document format parsers

## Integration with AOD System

### Usage Pattern
1. **File Upload**: Documents stored in upload directory
2. **Path Resolution**: `getDocumentRevisionPath()` locates files
3. **Format Detection**: MIME type determines parser selection
4. **Content Extraction**: Appropriate parser converts to text
5. **Index Integration**: Resulting Lucene document added to search index

### Supported Document Workflow
- **Upload Processing**: Handles file uploads through DocumentRevisions
- **Automatic Indexing**: Triggered by logic hooks on document save
- **Search Integration**: Extracted content becomes searchable via Lucene
- **Error Tracking**: Failed parsing recorded in AOD_IndexEvent

## Deprecation and Modern Alternatives

### Current Status
- **Deprecated Since**: v7.12.0
- **Reason**: Replaced by more efficient search implementations
- **Legacy Support**: Functions remain for backward compatibility

### Migration Path
- **Modern Search**: Current SuiteCRM uses different indexing approaches
- **Document Processing**: Newer systems may use different parsing libraries
- **Performance**: Modern implementations often more efficient and scalable 