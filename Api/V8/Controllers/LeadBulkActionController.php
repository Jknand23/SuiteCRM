<?php
/**
 * @fileoverview Lead Bulk Action API Controller
 *
 * Handles bulk operations on multiple leads including assignment, status updates,
 * campaign additions, and deletions. Provides efficient batch processing with
 * proper validation, authorization, and transaction management.
 *
 * Key Features:
 * - Bulk lead assignment to users
 * - Bulk status updates
 * - Bulk campaign additions
 * - Bulk deletions with soft delete
 * - Transaction management for data integrity
 * - Comprehensive validation and error handling
 * - Audit trail for all bulk operations
 * - Performance optimized for large batches
 *
 * Dependencies:
 * - SuiteCRM ACL system for permissions
 * - Lead model for data operations
 * - Campaign model for associations
 * - User model for assignments
 * - Database transactions for consistency
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace Api\V8\Controllers;

use Api\Core\Controllers\BaseController;
use Exception;
use Lead;
use Campaign;
use User;

/**
 * Lead Bulk Action Controller
 *
 * Provides endpoints for performing bulk operations on multiple leads
 * with proper validation, authorization, and transaction management.
 */
class LeadBulkActionController extends BaseController
{
    /** @var Lead $leadModel Lead model instance */
    private Lead $leadModel;
    
    /** @var Campaign $campaignModel Campaign model instance */
    private Campaign $campaignModel;
    
    /** @var User $userModel User model instance */
    private User $userModel;
    
    /** @var int $maxBulkSize Maximum leads per bulk operation */
    private int $maxBulkSize = 500;
    
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->leadModel = new Lead();
        $this->campaignModel = new Campaign();
        $this->userModel = new User();
    }
    
    /**
     * Bulk assigns leads to a user
     *
     * POST /Api/V8/leads/bulk-assign
     *
     * Request Body:
     * {
     *   "lead_ids": ["id1", "id2", ...],
     *   "assigned_user_id": "user_id"
     * }
     *
     * @return array JSON response with operation results
     * @throws Exception When assignment fails
     * @since 1.0.0
     */
    public function bulkAssignLeads(): array
    {
        try {
            // Check permissions
            if (!$this->leadModel->ACLAccess('edit')) {
                return $this->generateErrorResponse(
                    'Access denied: Insufficient permissions to edit leads',
                    403
                );
            }
            
            // Get request data
            $requestData = $this->getRequestBody();
            $leadIds = $requestData['lead_ids'] ?? [];
            $assignedUserId = $requestData['assigned_user_id'] ?? '';
            
            // Check rate limiting for bulk operations
            if (!$this->checkBulkRateLimit()) {
                return $this->generateErrorResponse(
                    'Rate limit exceeded. Please wait before performing another bulk operation.',
                    429
                );
            }
            
            // Validate input
            if (empty($leadIds) || !is_array($leadIds)) {
                return $this->generateErrorResponse(
                    'Invalid request: lead_ids must be a non-empty array',
                    400
                );
            }
            
            if (empty($assignedUserId)) {
                return $this->generateErrorResponse(
                    'Invalid request: assigned_user_id is required',
                    400
                );
            }
            
            // Check bulk size limit
            if (count($leadIds) > $this->maxBulkSize) {
                return $this->generateErrorResponse(
                    "Bulk operation limit exceeded: Maximum {$this->maxBulkSize} leads allowed",
                    400
                );
            }
            
            // Validate user exists
            $user = $this->userModel->retrieve($assignedUserId);
            if (!$user || $user->deleted) {
                return $this->generateErrorResponse(
                    'Invalid user: User not found or inactive',
                    400
                );
            }
            
            // Start transaction
            $this->db->query('START TRANSACTION');
            
            $affectedCount = 0;
            $errors = [];
            
            foreach ($leadIds as $leadId) {
                try {
                    $lead = $this->leadModel->retrieve($leadId);
                    if ($lead && !$lead->deleted) {
                        $lead->assigned_user_id = $assignedUserId;
                        $lead->save();
                        $affectedCount++;
                        
                        // Log assignment
                        $this->logBulkAction('assign', $leadId, [
                            'assigned_to' => $assignedUserId,
                            'assigned_by' => $this->getCurrentUserId()
                        ]);
                    }
                } catch (Exception $e) {
                    $errors[] = "Failed to assign lead {$leadId}: " . $e->getMessage();
                }
            }
            
            // Commit transaction
            $this->db->query('COMMIT');
            
            return $this->generateSuccessResponse([
                'affected_count' => $affectedCount,
                'total_requested' => count($leadIds),
                'errors' => $errors
            ]);
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->query('ROLLBACK');
            
            $this->logger->error('Bulk assign leads failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->generateErrorResponse(
                'Failed to assign leads: ' . $e->getMessage(),
                500
            );
        }
    }
    
    /**
     * Bulk updates lead status
     *
     * POST /Api/V8/leads/bulk-update-status
     *
     * Request Body:
     * {
     *   "lead_ids": ["id1", "id2", ...],
     *   "status": "New|Assigned|In Process|Converted|Recycled|Dead"
     * }
     *
     * @return array JSON response with operation results
     * @throws Exception When status update fails
     * @since 1.0.0
     */
    public function bulkUpdateStatus(): array
    {
        try {
            // Check permissions
            if (!$this->leadModel->ACLAccess('edit')) {
                return $this->generateErrorResponse(
                    'Access denied: Insufficient permissions to edit leads',
                    403
                );
            }
            
            // Get and validate request data
            $requestData = $this->getRequestBody();
            $leadIds = $requestData['lead_ids'] ?? [];
            $status = $requestData['status'] ?? '';
            
            // Validate input
            if (empty($leadIds) || !is_array($leadIds)) {
                return $this->generateErrorResponse(
                    'Invalid request: lead_ids must be a non-empty array',
                    400
                );
            }
            
            // Validate status
            $validStatuses = ['New', 'Assigned', 'In Process', 'Converted', 'Recycled', 'Dead'];
            if (!in_array($status, $validStatuses)) {
                return $this->generateErrorResponse(
                    'Invalid status: Must be one of: ' . implode(', ', $validStatuses),
                    400
                );
            }
            
            // Check bulk size limit
            if (count($leadIds) > $this->maxBulkSize) {
                return $this->generateErrorResponse(
                    "Bulk operation limit exceeded: Maximum {$this->maxBulkSize} leads allowed",
                    400
                );
            }
            
            // Start transaction
            $this->db->query('START TRANSACTION');
            
            $affectedCount = 0;
            $errors = [];
            
            foreach ($leadIds as $leadId) {
                try {
                    $lead = $this->leadModel->retrieve($leadId);
                    if ($lead && !$lead->deleted) {
                        $oldStatus = $lead->status;
                        $lead->status = $status;
                        $lead->save();
                        $affectedCount++;
                        
                        // Log status change
                        $this->logBulkAction('status_update', $leadId, [
                            'old_status' => $oldStatus,
                            'new_status' => $status,
                            'updated_by' => $this->getCurrentUserId()
                        ]);
                    }
                } catch (Exception $e) {
                    $errors[] = "Failed to update status for lead {$leadId}: " . $e->getMessage();
                }
            }
            
            // Commit transaction
            $this->db->query('COMMIT');
            
            return $this->generateSuccessResponse([
                'affected_count' => $affectedCount,
                'total_requested' => count($leadIds),
                'new_status' => $status,
                'errors' => $errors
            ]);
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->query('ROLLBACK');
            
            $this->logger->error('Bulk update status failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->generateErrorResponse(
                'Failed to update lead status: ' . $e->getMessage(),
                500
            );
        }
    }
    
    /**
     * Bulk adds leads to a campaign
     *
     * POST /Api/V8/leads/bulk-add-to-campaign
     *
     * Request Body:
     * {
     *   "lead_ids": ["id1", "id2", ...],
     *   "campaign_id": "campaign_id"
     * }
     *
     * @return array JSON response with operation results
     * @throws Exception When campaign addition fails
     * @since 1.0.0
     */
    public function bulkAddToCampaign(): array
    {
        try {
            // Check permissions
            if (!$this->leadModel->ACLAccess('edit') || !$this->campaignModel->ACLAccess('edit')) {
                return $this->generateErrorResponse(
                    'Access denied: Insufficient permissions',
                    403
                );
            }
            
            // Get and validate request data
            $requestData = $this->getRequestBody();
            $leadIds = $requestData['lead_ids'] ?? [];
            $campaignId = $requestData['campaign_id'] ?? '';
            
            // Validate input
            if (empty($leadIds) || !is_array($leadIds)) {
                return $this->generateErrorResponse(
                    'Invalid request: lead_ids must be a non-empty array',
                    400
                );
            }
            
            if (empty($campaignId)) {
                return $this->generateErrorResponse(
                    'Invalid request: campaign_id is required',
                    400
                );
            }
            
            // Check bulk size limit
            if (count($leadIds) > $this->maxBulkSize) {
                return $this->generateErrorResponse(
                    "Bulk operation limit exceeded: Maximum {$this->maxBulkSize} leads allowed",
                    400
                );
            }
            
            // Validate campaign exists
            $campaign = $this->campaignModel->retrieve($campaignId);
            if (!$campaign || $campaign->deleted) {
                return $this->generateErrorResponse(
                    'Invalid campaign: Campaign not found or inactive',
                    400
                );
            }
            
            // Start transaction
            $this->db->query('START TRANSACTION');
            
            $affectedCount = 0;
            $errors = [];
            
            foreach ($leadIds as $leadId) {
                try {
                    $lead = $this->leadModel->retrieve($leadId);
                    if ($lead && !$lead->deleted) {
                        // Add lead to campaign (using SuiteCRM relationship)
                        $campaign->load_relationship('leads');
                        if ($campaign->leads->add($leadId)) {
                            $affectedCount++;
                            
                            // Log campaign addition
                            $this->logBulkAction('add_to_campaign', $leadId, [
                                'campaign_id' => $campaignId,
                                'campaign_name' => $campaign->name,
                                'added_by' => $this->getCurrentUserId()
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    $errors[] = "Failed to add lead {$leadId} to campaign: " . $e->getMessage();
                }
            }
            
            // Commit transaction
            $this->db->query('COMMIT');
            
            return $this->generateSuccessResponse([
                'affected_count' => $affectedCount,
                'total_requested' => count($leadIds),
                'campaign_id' => $campaignId,
                'campaign_name' => $campaign->name,
                'errors' => $errors
            ]);
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->query('ROLLBACK');
            
            $this->logger->error('Bulk add to campaign failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->generateErrorResponse(
                'Failed to add leads to campaign: ' . $e->getMessage(),
                500
            );
        }
    }
    
    /**
     * Bulk deletes leads (soft delete)
     *
     * DELETE /Api/V8/leads/bulk-delete
     *
     * Request Body:
     * {
     *   "lead_ids": ["id1", "id2", ...]
     * }
     *
     * @return array JSON response with operation results
     * @throws Exception When deletion fails
     * @since 1.0.0
     */
    public function bulkDeleteLeads(): array
    {
        try {
            // Check permissions
            if (!$this->leadModel->ACLAccess('delete')) {
                return $this->generateErrorResponse(
                    'Access denied: Insufficient permissions to delete leads',
                    403
                );
            }
            
            // Get and validate request data
            $requestData = $this->getRequestBody();
            $leadIds = $requestData['lead_ids'] ?? [];
            
            // Validate input
            if (empty($leadIds) || !is_array($leadIds)) {
                return $this->generateErrorResponse(
                    'Invalid request: lead_ids must be a non-empty array',
                    400
                );
            }
            
            // Check bulk size limit
            if (count($leadIds) > $this->maxBulkSize) {
                return $this->generateErrorResponse(
                    "Bulk operation limit exceeded: Maximum {$this->maxBulkSize} leads allowed",
                    400
                );
            }
            
            // Start transaction
            $this->db->query('START TRANSACTION');
            
            $affectedCount = 0;
            $errors = [];
            
            foreach ($leadIds as $leadId) {
                try {
                    $lead = $this->leadModel->retrieve($leadId);
                    if ($lead && !$lead->deleted) {
                        // Store lead data for logging before deletion
                        $leadData = [
                            'name' => $lead->first_name . ' ' . $lead->last_name,
                            'email' => $lead->email1,
                            'status' => $lead->status
                        ];
                        
                        // Soft delete
                        $lead->mark_deleted($leadId);
                        $affectedCount++;
                        
                        // Log deletion
                        $this->logBulkAction('delete', $leadId, [
                            'lead_data' => $leadData,
                            'deleted_by' => $this->getCurrentUserId()
                        ]);
                    }
                } catch (Exception $e) {
                    $errors[] = "Failed to delete lead {$leadId}: " . $e->getMessage();
                }
            }
            
            // Commit transaction
            $this->db->query('COMMIT');
            
            return $this->generateSuccessResponse([
                'affected_count' => $affectedCount,
                'total_requested' => count($leadIds),
                'errors' => $errors
            ]);
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->query('ROLLBACK');
            
            $this->logger->error('Bulk delete leads failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->generateErrorResponse(
                'Failed to delete leads: ' . $e->getMessage(),
                500
            );
        }
    }
    
    /**
     * Logs bulk action for audit trail
     *
     * @param string $action Action performed
     * @param string $leadId Lead ID affected
     * @param array $details Additional details
     * @since 1.0.0
     */
    private function logBulkAction(string $action, string $leadId, array $details): void
    {
        try {
            $this->logger->info("Bulk action performed: {$action}", [
                'action' => $action,
                'lead_id' => $leadId,
                'details' => $details,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            // Don't fail bulk operation due to logging error
            error_log("Failed to log bulk action: " . $e->getMessage());
        }
    }
    
    /**
     * Gets current user ID from session
     *
     * @return string Current user ID
     * @since 1.0.0
     */
    private function getCurrentUserId(): string
    {
        global $current_user;
        return $current_user->id ?? 'system';
    }
    
    /**
     * Checks rate limiting for bulk operations
     *
     * @return bool Whether the operation is allowed
     * @since 1.0.0
     */
    private function checkBulkRateLimit(): bool
    {
        $userId = $this->getCurrentUserId();
        $cacheKey = "bulk_rate_limit_{$userId}";
        
        // Check if user has recent bulk operations
        $recentOperations = $this->cache->get($cacheKey, 0);
        $maxOperationsPerMinute = 5;
        
        if ($recentOperations >= $maxOperationsPerMinute) {
            return false;
        }
        
        // Increment counter with 60 second TTL
        $this->cache->set($cacheKey, $recentOperations + 1, 60);
        
        return true;
    }
    
    /**
     * Generates error response with consistent format
     *
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @return array JSON response array
     * @since 1.0.0
     */
    private function generateErrorResponse(string $message, int $statusCode = 500): array
    {
        return [
            'success' => false,
            'message' => $message,
            'status' => $statusCode
        ];
    }
    
    /**
     * Generates success response with consistent format
     *
     * @param array $data Data to include in the response
     * @return array JSON response array
     * @since 1.0.0
     */
    private function generateSuccessResponse(array $data): array
    {
        return [
            'success' => true,
            'data' => $data
        ];
    }
    
    /**
     * Gets request body data
     *
     * @return array Request body data
     * @since 1.0.0
     */
    private function getRequestBody(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
    
    /**
     * Gets request data (body or query parameters)
     *
     * @return array Request data
     * @since 1.0.0
     */
    private function getRequestData(): array
    {
        return $_REQUEST;
    }
}
