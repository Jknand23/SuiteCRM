# PdfParser.php Documentation

## @fileoverview
**PdfParser - PDF text extraction utility for Lucene search indexing**
- **Package**: modules/AOD_Index
- **Copyright**: Based on work by Sebastien MALOT <sebastien@malot.fr>
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Extracts text content from PDF files for full-text search indexing
- **Deprecation**: All methods deprecated since v7.12.0
- **Date**: Originally created 2013-08-08

## Class Overview
The `PdfParser` class provides static methods for extracting plain text from PDF documents. It parses PDF file structure, handles compression, and converts PDF text commands into readable content suitable for Lucene indexing.

## External API Calls

### File System Operations

#### `parseFile(string $filename): string`
- **Purpose**: Extracts text content from a PDF file on disk
- **Process**:
  1. Reads entire file content using `file_get_contents()`
  2. Delegates to `extractText()` for content processing
- **Parameters**: File system path to PDF document
- **Returns**: Plain text content extracted from PDF
- **Usage**: Primary entry point for file-based PDF parsing

#### `parseContent(string $content): string`
- **Purpose**: Extracts text from PDF content already loaded in memory
- **Process**: Direct delegation to `extractText()` method
- **Parameters**: Raw PDF file content as binary string
- **Returns**: Extracted plain text content
- **Usage**: For processing PDF data from sources other than files

## Internal API Calls

### Core Text Extraction

#### `extractText(string $data): string`
- **Purpose**: Main PDF parsing engine that processes PDF structure
- **Algorithm**:
  1. **Object Parsing**: Splits PDF into objects using `getDataArray()`
  2. **Filter Detection**: Identifies compression/encoding filters
  3. **Stream Processing**: Extracts data streams from objects
  4. **Decompression**: Handles FlateDecode (gzip) compression
  5. **Text Extraction**: Processes PDF text commands
  6. **Content Assembly**: Combines extracted text from all objects

#### PDF Structure Processing
- **Object Boundaries**: Finds content between `obj` and `endobj` markers
- **Filter Analysis**: Parses filter dictionaries between `<<` and `>>`
- **Stream Data**: Extracts content between `stream` and `endstream`
- **Compression Handling**: Decompresses FlateDecode streams with `gzuncompress()`

### Text Command Processing

#### `extractTextElements(string $content): string`
- **Purpose**: Converts PDF text commands to readable text
- **Features**:
  - **Command Parsing**: Recognizes PDF text operators
  - **Character Encoding**: Handles octal and special character encoding
  - **Spacing Management**: Manages text positioning and spacing
  - **Line Breaking**: Processes line breaks and text positioning
  - **UTF-8 Conversion**: Ensures proper character encoding

#### PDF Text Operators
- **`Tj`**: Display text - extracts content from parentheses
- **`TJ`**: Display text with positioning - handles character arrays
- **`Td`**: Move text position - adds spacing based on coordinates
- **`TD`**: Move text and set leading - handles line breaks
- **`T*`**: Move to next line - adds newline characters
- **`Tf`**: Set font - adds spacing for font changes
- **`BT/ET`**: Begin/End text blocks - text boundary markers

### Character Encoding Processing

#### Octal Character Conversion
- **Pattern Matching**: Finds `\NNN` octal sequences
- **Character Filtering**: Skips non-printable characters (octal < 40)
- **Conversion**: Uses `chr(octdec())` for printable characters
- **Cleanup**: Removes encoded newlines and special sequences

#### Character Set Handling
- **Encoding Detection**: Uses `mb_detect_encoding()` for charset detection
- **Supported Formats**: ASCII, UTF-8, Windows-1252, ISO-8859-1
- **Conversion**: Uses `iconv()` for CP1252 to UTF-8 conversion
- **Error Handling**: Graceful degradation for encoding failures

### Text Positioning and Spacing

#### `parseTextCommand(string $text, int $font_size = 0): string`
- **Purpose**: Extracts text from TJ command arrays
- **Features**:
  - **Parentheses Parsing**: Extracts text from `(text)` format
  - **Spacing Calculation**: Determines spacing based on numeric values
  - **Escape Handling**: Processes escaped parentheses
  - **Position Tracking**: Maintains text position through array

#### Spacing Algorithm
- **Large Gaps**: Values > 8 units add space characters
- **Negative Values**: Values < -50 add space for word separation
- **Small Adjustments**: Minor positioning ignored
- **UTF-8 Safety**: Uses `mb_` functions for multibyte safety

## Document Structure Parsing

### PDF Object Processing
#### `getDataArray(string $data, string $start_word, string $end_word): array`
- **Purpose**: Splits PDF content into sections based on delimiters
- **Algorithm**:
  1. Searches for start marker in content
  2. Finds corresponding end marker
  3. Extracts content between markers
  4. Continues until no more sections found
- **Usage**: Fundamental parsing method for PDF structure
- **Returns**: Array of content sections

### Compression Support
- **FlateDecode**: Primary compression method (gzip-based)
- **Decompression**: Uses `@gzuncompress()` with error suppression
- **Fallback**: Raw content used if decompression fails
- **Error Handling**: Silent failure allows processing of uncompressed streams

## Content Processing Features

### Text Cleaning and Optimization
- **Hyphen Optimization**: `preg_replace('/\s*-[\r\n]+\s*/', '')` - removes hyphenated line breaks
- **Whitespace Normalization**: `preg_replace('/\s+/', ' ')` - collapses multiple spaces
- **Bracket Restoration**: Converts `\(` and `\)` back to literal parentheses
- **Empty Content Handling**: Returns `null` for documents with no extractable text

### Character Processing
- **Special Character Removal**: Filters non-printable control characters
- **Line Break Handling**: Converts PDF line breaks to standard newlines
- **Tab Processing**: Handles tab characters and spacing
- **Unicode Support**: Full UTF-8 multibyte character support

## Error Handling and Robustness

### Graceful Degradation
- **Compression Failures**: Falls back to raw content if decompression fails
- **Encoding Errors**: Continues processing with original content if conversion fails
- **Malformed Content**: Handles partially corrupted PDF structures
- **Empty Results**: Returns empty string rather than failing

### Performance Considerations
- **Memory Usage**: Processes entire PDF in memory (not streaming)
- **Error Suppression**: Uses `@` operator for non-critical failures
- **Regex Efficiency**: Optimized patterns for large document processing
- **Multibyte Safety**: UTF-8 aware string functions throughout

## Integration with AOD System

### Usage Pattern
1. **File Upload**: PDF uploaded through DocumentRevisions
2. **Path Resolution**: `getDocumentRevisionPath()` locates file
3. **Content Extraction**: `PdfParser::parseFile()` extracts text
4. **Lucene Document**: Text added to searchable Lucene document
5. **Index Integration**: Document added to search index

### Content Quality
- **Text Preservation**: Maintains readable text content
- **Formatting Loss**: Visual formatting removed for search purposes
- **Metadata Exclusion**: Focuses on content, ignores PDF metadata
- **Error Recovery**: Partial extraction better than complete failure

## Dependencies and Requirements

### PHP Extensions
- **Zlib**: Required for FlateDecode decompression (`gzuncompress`)
- **Multibyte String**: UTF-8 processing (`mb_*` functions)
- **PCRE**: Regular expression processing
- **Iconv**: Character set conversion support

### PDF Format Support
- **PDF Versions**: Supports standard PDF 1.x formats
- **Text Content**: Extracts searchable text (not OCR)
- **Compression**: Handles FlateDecode compression
- **Limitations**: Cannot process image-only or encrypted PDFs

## Algorithm References

### Technical Documentation
- **MacTech Article**: PDF structure reference from mactech.com
- **Zend Framework**: PDF parsing implementation patterns
- **PHP Manual**: PDF processing techniques and examples

### PDF Specification Compliance
- **Text Operators**: Implements core PDF text commands
- **Coordinate System**: Handles PDF coordinate transformations
- **Stream Processing**: Standard PDF stream handling
- **Character Encoding**: PDF character encoding specifications

## Performance and Limitations

### Processing Characteristics
- **Memory Intensive**: Loads entire PDF into memory
- **CPU Usage**: Intensive regex and text processing
- **File Size Limits**: Limited by available PHP memory
- **Processing Time**: Scales with document complexity

### Known Limitations
- **OCR Content**: Cannot extract text from scanned images
- **Encrypted PDFs**: Cannot process password-protected files
- **Complex Layouts**: May not preserve exact text order
- **Non-Standard Fonts**: Limited support for custom font encodings

## Deprecation and Modern Alternatives

### Current Status
- **Deprecated Since**: v7.12.0
- **Reason**: Modern PDF processing libraries more robust
- **Legacy Support**: Maintained for backward compatibility

### Modern Replacements
- **External Libraries**: More comprehensive PDF parsing libraries available
- **Performance**: Modern tools often faster and more accurate
- **Format Support**: Better handling of complex PDF features
- **Memory Efficiency**: Streaming parsers available for large files 