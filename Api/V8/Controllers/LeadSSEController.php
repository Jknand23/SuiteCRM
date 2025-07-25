<?php
/**
 * @fileoverview Lead Server-Sent Events (SSE) Controller
 *
 * Provides real-time updates for lead data changes using Server-Sent Events.
 * Enables live updates for lead list views without polling, supporting instant
 * notifications when leads are created, updated, or deleted.
 *
 * Key Features:
 * - Real-time lead data updates via SSE
 * - User-specific event streams
 * - Heartbeat/keepalive messages
 * - Automatic reconnection support
 * - Event filtering by user permissions
 * - Memory-efficient streaming
 * - Graceful connection handling
 *
 * Dependencies:
 * - Lead model for change detection
 * - User authentication system
 * - Activity tracking for lead changes
 * - SSE-compatible browser clients
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
 * Lead SSE Controller
 *
 * Streams real-time lead updates to connected clients using
 * Server-Sent Events for live data synchronization.
 */
class LeadSSEController extends BaseController
{
    /** @var Lead $leadModel Lead model instance */
    private Lead $leadModel;
    
    /** @var int $heartbeatInterval Seconds between heartbeat messages */
    private int $heartbeatInterval = 30;
    
    /** @var int $checkInterval Seconds between database checks */
    private int $checkInterval = 2;
    
    /** @var int $maxExecutionTime Maximum script execution time */
    private int $maxExecutionTime = 300; // 5 minutes
    
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
     * Streams real-time lead updates via Server-Sent Events
     *
     * GET /Api/V8/leads/sse-stream
     *
     * Event Types:
     * - lead_created: New lead added
     * - lead_updated: Lead data modified
     * - lead_deleted: Lead removed
     * - heartbeat: Keep-alive message
     *
     * @return void Streams events directly
     * @since 1.0.0
     */
    public function streamLeadUpdates(): void
    {
        try {
            // Check permissions
            if (!$this->leadModel->ACLAccess('list')) {
                $this->sendSSEError('Access denied: Insufficient permissions');
                return;
            }
            
            // Set SSE headers
            $this->setSSEHeaders();
            
            // Get initial state
            $userId = $this->getCurrentUserId();
            $lastCheckTime = time();
            $startTime = time();
            
            // Send initial connection message
            $this->sendSSEMessage('connected', [
                'message' => 'SSE connection established',
                'timestamp' => date('c')
            ]);
            
            // Main event loop
            while (true) {
                // Check execution time limit
                if (time() - $startTime > $this->maxExecutionTime) {
                    $this->sendSSEMessage('timeout', [
                        'message' => 'Connection timeout, please reconnect',
                        'timestamp' => date('c')
                    ]);
                    break;
                }
                
                // Check for lead changes
                $changes = $this->checkForLeadChanges($lastCheckTime, $userId);
                
                if (!empty($changes)) {
                    foreach ($changes as $change) {
                        $this->sendLeadChangeEvent($change);
                    }
                    $lastCheckTime = time();
                }
                
                // Send heartbeat
                if (time() - $lastCheckTime > $this->heartbeatInterval) {
                    $this->sendSSEMessage('heartbeat', [
                        'timestamp' => date('c'),
                        'uptime' => time() - $startTime
                    ]);
                    $lastCheckTime = time();
                }
                
                // Flush output
                ob_flush();
                flush();
                
                // Sleep before next check
                sleep($this->checkInterval);
                
                // Check if client disconnected
                if (connection_aborted()) {
                    break;
                }
            }
        } catch (Exception $e) {
            $this->logger->error('SSE stream error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->sendSSEError('Stream error: ' . $e->getMessage());
        }
    }
    
    /**
     * Sets appropriate headers for SSE streaming
     *
     * @since 1.0.0
     */
    private function setSSEHeaders(): void
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Nginx specific
        
        // Disable output buffering
        @ini_set('output_buffering', 'off');
        @ini_set('zlib.output_compression', false);
        @ini_set('implicit_flush', true);
        @ob_end_flush();
        
        // Set execution limits
        set_time_limit(0);
        ignore_user_abort(true);
    }
    
    /**
     * Checks for lead changes since last check
     *
     * @param int $lastCheckTime Last check timestamp
     * @param string $userId Current user ID
     * @return array Array of lead changes
     * @since 1.0.0
     */
    private function checkForLeadChanges(int $lastCheckTime, string $userId): array
    {
        $changes = [];
        $checkDate = date('Y-m-d H:i:s', $lastCheckTime);
        
        try {
            // Check for created/updated leads
            $sql = "SELECT 
                        l.id,
                        l.first_name,
                        l.last_name,
                        l.email1 as email,
                        l.status,
                        l.industry,
                        l.account_name,
                        l.date_entered,
                        l.date_modified,
                        l.deleted,
                        CASE 
                            WHEN l.date_entered > ? THEN 'created'
                            WHEN l.date_modified > ? AND l.date_entered <= ? THEN 'updated'
                            ELSE 'unknown'
                        END as change_type
                    FROM leads l
                    WHERE (l.date_entered > ? OR l.date_modified > ?)
                    AND (l.assigned_user_id = ? OR l.created_by = ? OR ? = 'admin')
                    ORDER BY l.date_modified DESC
                    LIMIT 50";
            
            $result = $this->db->query($sql, [
                $checkDate,
                $checkDate,
                $checkDate,
                $checkDate,
                $checkDate,
                $userId,
                $userId,
                $this->getUserRole($userId)
            ]);
            
            while ($row = $this->db->fetchByAssoc($result)) {
                $changeType = $row['deleted'] ? 'deleted' : $row['change_type'];
                unset($row['change_type']);
                
                $changes[] = [
                    'type' => $changeType,
                    'data' => $row
                ];
            }
        } catch (Exception $e) {
            $this->logger->error('Error checking for lead changes', [
                'error' => $e->getMessage()
            ]);
        }
        
        return $changes;
    }
    
    /**
     * Sends lead change event to client
     *
     * @param array $change Change details
     * @since 1.0.0
     */
    private function sendLeadChangeEvent(array $change): void
    {
        $eventType = 'lead_' . $change['type'];
        $eventData = [
            'lead' => $change['data'],
            'timestamp' => date('c'),
            'change_type' => $change['type']
        ];
        
        $this->sendSSEMessage($eventType, $eventData);
    }
    
    /**
     * Sends SSE message to client
     *
     * @param string $event Event type
     * @param array $data Event data
     * @since 1.0.0
     */
    private function sendSSEMessage(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo "data: " . json_encode($data) . "\n\n";
        
        // Force output
        ob_flush();
        flush();
    }
    
    /**
     * Sends SSE error message
     *
     * @param string $message Error message
     * @since 1.0.0
     */
    private function sendSSEError(string $message): void
    {
        $this->sendSSEMessage('error', [
            'message' => $message,
            'timestamp' => date('c')
        ]);
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
        return $current_user->id ?? '';
    }
    
    /**
     * Gets user role for permission checking
     *
     * @param string $userId User ID
     * @return string User role
     * @since 1.0.0
     */
    private function getUserRole(string $userId): string
    {
        global $current_user;
        
        if ($current_user->id === $userId && $current_user->is_admin) {
            return 'admin';
        }
        
        return 'user';
    }
}
