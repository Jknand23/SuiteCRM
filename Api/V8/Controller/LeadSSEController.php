<?php
/**
 * @fileoverview Lead Server-Sent Events (SSE) Controller
 *
 * Provides real-time updates for lead data changes using Server-Sent Events.
 * Implements an optimized polling mechanism with configurable intervals and
 * efficient change detection via a dedicated lead_changes tracking table.
 *
 * Key Features:
 * - Real-time lead data updates via SSE
 * - Configurable polling interval via environment variable
 * - Efficient change detection using lead_changes table
 * - User-specific event streams with permission filtering
 * - Heartbeat/keepalive messages
 * - Automatic reconnection support
 * - Memory-efficient streaming
 * - Graceful connection handling
 *
 * Environment Variables:
 * - SSE_POLL_INTERVAL: Polling interval in seconds (default: 10)
 * - SSE_HEARTBEAT_INTERVAL: Heartbeat interval in seconds (default: 30)
 * - SSE_MAX_EXECUTION_TIME: Maximum execution time in seconds (default: 300)
 *
 * Dependencies:
 * - Lead model for change detection
 * - User authentication system
 * - lead_changes table for efficient tracking
 * - SSE-compatible browser clients
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace Api\V8\Controller;

use Exception;
use Lead;
use DBManagerFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Lead SSE Controller
 *
 * Streams real-time lead updates to connected clients using
 * Server-Sent Events with optimized change detection.
 */
class LeadSSEController extends BaseController
{
    /** @var Lead $leadModel Lead model instance */
    private Lead $leadModel;
    
    /** @var \DBManager $db Database connection instance */
    private $db;
    
    /** @var int $heartbeatInterval Seconds between heartbeat messages */
    private int $heartbeatInterval;
    
    /** @var int $checkInterval Seconds between database checks */
    private int $checkInterval;
    
    /** @var int $maxExecutionTime Maximum script execution time */
    private int $maxExecutionTime;
    
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->leadModel = new Lead();
        $this->db = DBManagerFactory::getInstance();
        
        // Configure intervals from environment variables
        $this->checkInterval = intval($_ENV['SSE_POLL_INTERVAL'] ?? '10');
        $this->heartbeatInterval = intval($_ENV['SSE_HEARTBEAT_INTERVAL'] ?? '30');
        $this->maxExecutionTime = intval($_ENV['SSE_MAX_EXECUTION_TIME'] ?? '300');
        
        // Ensure reasonable minimum values
        $this->checkInterval = max(5, $this->checkInterval);
        $this->heartbeatInterval = max(15, $this->heartbeatInterval);
        $this->maxExecutionTime = max(60, $this->maxExecutionTime);
    }
    
    /**
     * Streams real-time lead updates via Server-Sent Events
     *
     * @param Request $request The request object
     * @param Response $response The response object
     * @param array $args The route arguments
     *
     * @return Response SSE stream response
     * @since 1.0.0
     */
    public function streamLeadUpdates(Request $request, Response $response, array $args): Response
    {
        try {
            // Check permissions
            if (!$this->leadModel->ACLAccess('list')) {
                return $this->sendSSEError($response, 'Access denied: Insufficient permissions');
            }
            
            // Set SSE headers
            $response = $this->setSSEHeaders($response);
            
            // Get user context
            $userId = $this->getCurrentUserId();
            if (!$userId) {
                return $this->sendSSEError($response, 'Authentication required');
            }
            
            // Initialize tracking
            $lastCheckId = $this->getLastProcessedChangeId();
            $lastCheckTime = time();
            $startTime = time();
            $lastHeartbeat = time();
            
            // Create table if not exists
            $this->ensureLeadChangesTable();
            
            // Send initial connection message
            $this->sendSSEMessage('connected', [
                'message' => 'SSE connection established',
                'timestamp' => date('c'),
                'poll_interval' => $this->checkInterval
            ]);
            
            // Start streaming loop
            while (true) {
                // Check execution time limit
                if ((time() - $startTime) >= $this->maxExecutionTime) {
                    $this->sendSSEMessage('reconnect', [
                        'message' => 'Connection timeout, please reconnect',
                        'timestamp' => date('c')
                    ]);
                    break;
                }
                
                // Send heartbeat if needed
                if ((time() - $lastHeartbeat) >= $this->heartbeatInterval) {
                    $this->sendSSEMessage('heartbeat', [
                        'timestamp' => date('c'),
                        'uptime' => time() - $startTime
                    ]);
                    $lastHeartbeat = time();
                }
                
                // Check for changes if interval has passed
                if ((time() - $lastCheckTime) >= $this->checkInterval) {
                    try {
                        // Query lead_changes table for new changes
                        $changes = $this->getLeadChangesSince($lastCheckId, $userId);
                        
                        foreach ($changes as $change) {
                            $eventType = $this->mapChangeTypeToEvent($change['change_type']);
                            
                            $this->sendSSEMessage($eventType, [
                                'lead_id' => $change['lead_id'],
                                'change_type' => $change['change_type'],
                                'changed_fields' => json_decode($change['changed_fields'], true),
                                'timestamp' => $change['date_created'],
                                'user' => $change['created_by_name']
                            ]);
                            
                            $lastCheckId = $change['id'];
                        }
                    } catch (Exception $e) {
                        $GLOBALS['log']->error('SSE polling error: ' . $e->getMessage());
                    }
                    
                    $lastCheckTime = time();
                }
                
                // Small sleep to prevent CPU spinning
                usleep(100000); // 100ms
                
                // Flush output
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
        } catch (Exception $e) {
            $GLOBALS['log']->error('SSE stream error: ' . $e->getMessage());
            return $this->sendSSEError($response, 'Stream error: ' . $e->getMessage());
        }
        
        return $response;
    }
    
    /**
     * Ensures the lead_changes tracking table exists
     *
     * @return void
     * @since 1.0.0
     */
    private function ensureLeadChangesTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS lead_changes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lead_id VARCHAR(36) NOT NULL,
            change_type ENUM('created', 'updated', 'deleted') NOT NULL,
            changed_fields TEXT,
            created_by VARCHAR(36),
            created_by_name VARCHAR(255),
            date_created DATETIME NOT NULL,
            INDEX idx_date_created (date_created),
            INDEX idx_lead_id (lead_id),
            INDEX idx_change_type (change_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->db->query($sql);
    }
    
    /**
     * Gets lead changes since a specific change ID
     *
     * @param int $sinceId Last processed change ID
     * @param string $userId Current user ID for permission filtering
     * @return array Array of lead changes
     * @since 1.0.0
     */
    private function getLeadChangesSince(int $sinceId, string $userId): array
    {
        // Query for changes with permission check
        $sql = "SELECT 
                    lc.id,
                    lc.lead_id,
                    lc.change_type,
                    lc.changed_fields,
                    lc.created_by,
                    lc.created_by_name,
                    lc.date_created
                FROM lead_changes lc
                INNER JOIN leads l ON l.id = lc.lead_id
                WHERE lc.id > ? 
                    AND l.deleted = 0
                ORDER BY lc.id ASC
                LIMIT 100";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$sinceId]);
        
        $changes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            // Additional permission check if needed
            $lead = new Lead();
            $lead->retrieve($row['lead_id']);
            
            if ($lead->ACLAccess('view')) {
                $changes[] = $row;
            }
        }
        
        return $changes;
    }
    
    /**
     * Gets the last processed change ID from session or default
     *
     * @return int Last processed change ID
     * @since 1.0.0
     */
    private function getLastProcessedChangeId(): int
    {
        // In production, this could be stored in session or user preferences
        // For now, start from current max ID
        $sql = "SELECT COALESCE(MAX(id), 0) as max_id FROM lead_changes";
        $result = $this->db->query($sql);
        $row = $this->db->fetchByAssoc($result);
        
        return intval($row['max_id'] ?? 0);
    }
    
    /**
     * Maps change type to SSE event name
     *
     * @param string $changeType Database change type
     * @return string SSE event name
     * @since 1.0.0
     */
    private function mapChangeTypeToEvent(string $changeType): string
    {
        $mapping = [
            'created' => 'lead_created',
            'updated' => 'lead_updated',
            'deleted' => 'lead_deleted'
        ];
        
        return $mapping[$changeType] ?? 'lead_changed';
    }
    
    /**
     * Gets current authenticated user ID
     *
     * @return string|null User ID or null if not authenticated
     * @since 1.0.0
     */
    private function getCurrentUserId(): ?string
    {
        global $current_user;
        return $current_user->id ?? null;
    }
    
    /**
     * Sets appropriate SSE headers on response
     *
     * @param Response $response The response object
     * @return Response Modified response with SSE headers
     * @since 1.0.0
     */
    private function setSSEHeaders(Response $response): Response
    {
        return $response
            ->withHeader('Content-Type', 'text/event-stream')
            ->withHeader('Cache-Control', 'no-cache')
            ->withHeader('Connection', 'keep-alive')
            ->withHeader('X-Accel-Buffering', 'no'); // Disable Nginx buffering
    }
    
    /**
     * Sends SSE message to client
     *
     * @param string $event Event type
     * @param array $data Event data
     * @return void
     * @since 1.0.0
     */
    private function sendSSEMessage(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo "data: " . json_encode($data) . "\n\n";
        
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
    }
    
    /**
     * Sends SSE error and closes connection
     *
     * @param Response $response The response object
     * @param string $message Error message
     * @return Response Error response
     * @since 1.0.0
     */
    private function sendSSEError(Response $response, string $message): Response
    {
        $response = $this->setSSEHeaders($response);
        
        echo "event: error\n";
        echo "data: " . json_encode(['error' => $message]) . "\n\n";
        
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
        
        return $response;
    }
}
