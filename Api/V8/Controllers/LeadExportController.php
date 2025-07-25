<?php
/**
 * @fileoverview Lead Export API Controller
 *
 * Handles exporting filtered lead data in various formats including CSV and Excel.
 * Provides flexible column selection, preserves filter criteria, and supports
 * large dataset exports with proper memory management.
 *
 * Key Features:
 * - CSV export with customizable columns
 * - Excel export with formatting
 * - Filtered data export (maintains current view filters)
 * - Column visibility respect from user preferences
 * - Large dataset streaming for memory efficiency
 * - Proper character encoding handling
 * - Download headers for immediate file download
 *
 * Dependencies:
 * - Lead model for data retrieval
 * - Filter controller for query building
 * - PhpSpreadsheet for Excel generation (optional)
 * - Native PHP for CSV generation
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace Api\V8\Controllers;

use Api\Core\Controllers\BaseController;
use Exception;
use Lead;

/**
 * Lead Export Controller
 *
 * Provides endpoints for exporting lead data in various formats
 * with column customization and filter preservation.
 */
class LeadExportController extends BaseController
{
    /** @var Lead $leadModel Lead model instance */
    private Lead $leadModel;
    
    /** @var array $exportableColumns Columns available for export */
    private array $exportableColumns = [
        'name' => 'Lead Name',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email Address',
        'account_name' => 'Company',
        'status' => 'Status',
        'industry' => 'Industry',
        'phone_work' => 'Work Phone',
        'phone_mobile' => 'Mobile Phone',
        'lead_source' => 'Lead Source',
        'description' => 'Description',
        'date_entered' => 'Date Created',
        'date_modified' => 'Last Modified',
        'assigned_user_name' => 'Assigned To'
    ];
    
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->leadModel = new Lead();
    }
    
    /**
     * Exports filtered leads to specified format
     *
     * GET /Api/V8/leads/export
     *
     * Query Parameters:
     * - format: Export format (csv/excel, default: csv)
     * - columns: Comma-separated list of columns to export
     * - All filter parameters from LeadFilterController
     *
     * @return void Outputs file directly
     * @throws Exception When export fails
     * @since 1.0.0
     */
    public function exportFilteredLeads(): void
    {
        try {
            // Check permissions
            if (!$this->leadModel->ACLAccess('export')) {
                $this->sendJsonError('Access denied: Insufficient permissions to export leads', 403);
                return;
            }
            
            // Get export parameters
            $format = $_GET['format'] ?? 'csv';
            $requestedColumns = $_GET['columns'] ?? '';
            
            // Validate format
            if (!in_array($format, ['csv', 'excel'])) {
                $this->sendJsonError('Invalid export format. Supported formats: csv, excel', 400);
                return;
            }
            
            // Get columns to export
            $columns = $this->getExportColumns($requestedColumns);
            if (empty($columns)) {
                $this->sendJsonError('No columns selected for export', 400);
                return;
            }
            
            // Get filtered leads using the same logic as LeadFilterController
            $leads = $this->getFilteredLeadsForExport();
            
            // Generate filename
            $filename = $this->generateExportFilename($format);
            
            // Export based on format
            if ($format === 'csv') {
                $this->exportAsCSV($leads, $columns, $filename);
            } else {
                $this->exportAsExcel($leads, $columns, $filename);
            }
        } catch (Exception $e) {
            $this->logger->error('Lead export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->sendJsonError('Failed to export leads: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Gets columns to export based on request
     *
     * @param string $requestedColumns Comma-separated column names
     * @return array Column configuration for export
     * @since 1.0.0
     */
    private function getExportColumns(string $requestedColumns): array
    {
        if (empty($requestedColumns)) {
            // Default columns if none specified
            return [
                'name' => $this->exportableColumns['name'],
                'email' => $this->exportableColumns['email'],
                'account_name' => $this->exportableColumns['account_name'],
                'status' => $this->exportableColumns['status'],
                'date_modified' => $this->exportableColumns['date_modified']
            ];
        }
        
        $columns = [];
        $requested = array_map('trim', explode(',', $requestedColumns));
        
        foreach ($requested as $column) {
            if (isset($this->exportableColumns[$column])) {
                $columns[$column] = $this->exportableColumns[$column];
            }
        }
        
        return $columns;
    }
    
    /**
     * Gets filtered leads for export using filter parameters
     *
     * @return array Lead data for export
     * @since 1.0.0
     */
    private function getFilteredLeadsForExport(): array
    {
        // Reuse LeadFilterController logic for consistency
        $filterController = new LeadFilterController();
        
        // Remove pagination for export (get all results)
        $_GET['limit'] = 10000; // Reasonable limit to prevent memory issues
        $_GET['page'] = 1;
        
        $response = $filterController->getFilteredLeads();
        
        if ($response['status'] !== 'success') {
            throw new Exception('Failed to retrieve leads for export');
        }
        
        return $response['data']['data'] ?? [];
    }
    
    /**
     * Exports leads as CSV file with streaming to prevent memory issues
     *
     * @param array $leads Lead data
     * @param array $columns Column configuration
     * @param string $filename Export filename
     * @since 1.0.0
     */
    private function exportAsCSV(array $leads, array $columns, string $filename): void
    {
        // Set CSV headers
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Content-Transfer-Encoding: chunked');
        
        // Disable time limit for large exports
        set_time_limit(0);
        
        // Add BOM for UTF-8 Excel compatibility
        echo "\xEF\xBB\xBF";
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Write headers
        fputcsv($output, array_values($columns));
        
        // Stream data in chunks to prevent memory issues
        $chunkSize = 100;
        $offset = 0;
        
        while (true) {
            // Get next chunk of leads
            $chunkLeads = $this->getLeadChunk($offset, $chunkSize);
            
            if (empty($chunkLeads)) {
                break;
            }
            
            // Write chunk rows
            foreach ($chunkLeads as $lead) {
                $row = [];
                foreach (array_keys($columns) as $column) {
                    if ($column === 'name') {
                        // Combine first and last name
                        $value = trim(($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? ''));
                    } else {
                        $value = $lead[$column] ?? '';
                    }
                    
                    // Format dates
                    if (in_array($column, ['date_entered', 'date_modified']) && !empty($value)) {
                        $value = $this->formatDateForExport($value);
                    }
                    
                    $row[] = $value;
                }
                fputcsv($output, $row);
            }
            
            // Flush output buffer periodically
            if ($offset % 500 === 0) {
                ob_flush();
                flush();
            }
            
            $offset += $chunkSize;
            
            // Free memory
            unset($chunkLeads);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Gets a chunk of leads for streaming export
     *
     * @param int $offset Starting position
     * @param int $limit Number of records to fetch
     * @return array Lead data chunk
     * @since 1.0.0
     */
    private function getLeadChunk(int $offset, int $limit): array
    {
        // Build query with filters
        $filterController = new LeadFilterController();
        
        // Override pagination for chunk fetching
        $_GET['limit'] = $limit;
        $_GET['offset'] = $offset;
        
        $response = $filterController->getFilteredLeads();
        
        if ($response['status'] !== 'success') {
            return [];
        }
        
        return $response['data']['data'] ?? [];
    }
    
    /**
     * Exports leads as Excel file
     *
     * @param array $leads Lead data
     * @param array $columns Column configuration
     * @param string $filename Export filename
     * @since 1.0.0
     */
    private function exportAsExcel(array $leads, array $columns, string $filename): void
    {
        // Check if PhpSpreadsheet is available
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            // Fallback to CSV if Excel library not available
            $this->exportAsCSV($leads, $columns, str_replace('.xlsx', '.csv', $filename));
            return;
        }
        
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set title
            $sheet->setTitle('Lead Export');
            
            // Write headers
            $col = 1;
            foreach ($columns as $header) {
                $sheet->setCellValueByColumnAndRow($col, 1, $header);
                // Style headers
                $sheet->getStyleByColumnAndRow($col, 1)->getFont()->setBold(true);
                $sheet->getStyleByColumnAndRow($col, 1)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE0E0E0');
                $col++;
            }
            
            // Write data
            $row = 2;
            foreach ($leads as $lead) {
                $col = 1;
                foreach (array_keys($columns) as $column) {
                    if ($column === 'name') {
                        $value = trim(($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? ''));
                    } else {
                        $value = $lead[$column] ?? '';
                    }
                    
                    // Format dates
                    if (in_array($column, ['date_entered', 'date_modified']) && !empty($value)) {
                        $value = $this->formatDateForExport($value);
                    }
                    
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                    $col++;
                }
                $row++;
            }
            
            // Auto-size columns
            foreach (range(1, count($columns)) as $col) {
                $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
            }
            
            // Add filters
            $sheet->setAutoFilter('A1:' . $sheet->getHighestColumn() . '1');
            
            // Set headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // Write file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            // Fallback to CSV on error
            $this->logger->warning('Excel export failed, falling back to CSV', [
                'error' => $e->getMessage()
            ]);
            $this->exportAsCSV($leads, $columns, str_replace('.xlsx', '.csv', $filename));
        }
    }
    
    /**
     * Generates export filename with timestamp
     *
     * @param string $format Export format
     * @return string Generated filename
     * @since 1.0.0
     */
    private function generateExportFilename(string $format): string
    {
        $timestamp = date('Y-m-d_H-i-s');
        $extension = $format === 'excel' ? 'xlsx' : 'csv';
        
        return "lead_export_{$timestamp}.{$extension}";
    }
    
    /**
     * Formats date for export
     *
     * @param string $date Date string
     * @return string Formatted date
     * @since 1.0.0
     */
    private function formatDateForExport(string $date): string
    {
        if (empty($date)) {
            return '';
        }
        
        try {
            return date('Y-m-d H:i:s', strtotime($date));
        } catch (Exception $e) {
            return $date;
        }
    }
    
    /**
     * Sends JSON error response
     *
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @since 1.0.0
     */
    private function sendJsonError(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}
