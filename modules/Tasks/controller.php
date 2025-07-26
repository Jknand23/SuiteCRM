<?php
/**
 * @fileoverview Task Timer Controller
 *
 * Handles HTTP requests for timer operations including starting, stopping,
 * checking status, and adding manual time entries. Provides AJAX endpoints
 * for the frontend timer interface to interact with the TimerService.
 *
 * Key Features:
 * - AJAX action handlers for timer operations
 * - JSON response formatting
 * - Error handling and validation
 * - CSRF protection
 * - User permission verification
 *
 * Dependencies:
 * - TimerService for business logic
 * - SugarController for base functionality
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/Controller/SugarController.php');
require_once('modules/Tasks/Services/TimerService.php');

class TasksController extends SugarController
{
    /**
     * Starts the timer for a task
     *
     * Expected POST parameters:
     * - task_id: The ID of the task
     *
     * @return void Outputs JSON response
     */
    public function action_TimerStart()
    {
        $this->handleTimerAction('startTimer');
    }
    
    /**
     * Stops the timer for a task
     *
     * Expected POST parameters:
     * - task_id: The ID of the task
     *
     * @return void Outputs JSON response
     */
    public function action_TimerStop()
    {
        $this->handleTimerAction('stopTimer');
    }
    
    /**
     * Gets the current timer status
     *
     * Expected GET/POST parameters:
     * - task_id: The ID of the task
     *
     * @return void Outputs JSON response
     */
    public function action_TimerStatus()
    {
        $this->handleTimerAction('getTimerStatus');
    }
    
    /**
     * Adds manual time to a task
     *
     * Expected POST parameters:
     * - task_id: The ID of the task
     * - hours: Number of hours to add
     * - minutes: Number of minutes to add
     * - notes: Optional notes for the entry
     *
     * @return void Outputs JSON response
     */
    public function action_TimerAddManual()
    {
        try {
            // Validate request
            $taskId = $this->getRequestParam('task_id');
            if (empty($taskId)) {
                throw new Exception('Task ID is required');
            }
            
            // Parse time input
            $hours = (int)$this->getRequestParam('hours', 0);
            $minutes = (int)$this->getRequestParam('minutes', 0);
            $notes = $this->getRequestParam('notes', '');
            
            if ($hours < 0 || $minutes < 0) {
                throw new Exception('Invalid time values');
            }
            
            if ($hours == 0 && $minutes == 0) {
                throw new Exception('Please enter a valid time duration');
            }
            
            // Convert to seconds
            $seconds = ($hours * 3600) + ($minutes * 60);
            
            // Create service and add manual time
            $service = new TimerService($taskId);
            $result = $service->addManualTime($seconds, $notes);
            
            $this->outputJSON($result);
        } catch (Exception $e) {
            $this->outputJSON(array(
                'success' => false,
                'error' => $e->getMessage()
            ));
        }
    }
    
    /**
     * Common handler for timer actions
     *
     * @param string $method The TimerService method to call
     * @return void Outputs JSON response
     */
    protected function handleTimerAction($method)
    {
        try {
            // Validate request
            $taskId = $this->getRequestParam('task_id');
            if (empty($taskId)) {
                throw new Exception('Task ID is required');
            }
            
            // Check user permissions
            if (!$this->checkTaskAccess($taskId)) {
                throw new Exception('Access denied');
            }
            
            // Create service and execute method
            $service = new TimerService($taskId);
            $result = $service->$method();
            
            $this->outputJSON($result);
        } catch (Exception $e) {
            $this->outputJSON(array(
                'success' => false,
                'error' => $e->getMessage()
            ));
        }
    }
    
    /**
     * Gets request parameter from POST or GET
     *
     * @param string $name Parameter name
     * @param mixed $default Default value if not found
     * @return mixed Parameter value
     */
    protected function getRequestParam($name, $default = null)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return $default;
    }
    
    /**
     * Checks if current user has access to the task
     *
     * @param string $taskId Task ID to check
     * @return bool True if user has access
     */
    protected function checkTaskAccess($taskId)
    {
        global $current_user;
        
        // Admin users always have access
        if ($current_user->isAdmin()) {
            return true;
        }
        
        // Check if user is assigned to the task
        $task = BeanFactory::getBean('Tasks', $taskId);
        if ($task && $task->id) {
            // User is assigned or created the task
            if ($task->assigned_user_id == $current_user->id ||
                $task->created_by == $current_user->id) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Outputs JSON response and exits
     *
     * @param array $data Data to encode as JSON
     * @return void
     */
    protected function outputJSON($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        sugar_cleanup(true);
    }
}
