<?php
/**
 * @fileoverview Task Timer Service Class
 *
 * Provides business logic for task time tracking functionality including
 * starting/stopping timers, calculating elapsed time, managing timer sessions,
 * and handling manual time entries. Integrates with SuiteCRM's Task module
 * to enable accurate time tracking for billing and productivity analysis.
 *
 * Key Features:
 * - Start/stop timer operations with session tracking
 * - Calculate elapsed time for running timers
 * - Add manual time entries with validation
 * - Retrieve and format timer session history
 * - Handle concurrent timer prevention
 *
 * Dependencies:
 * - SuiteCRM BeanFactory for data persistence
 * - Task module for task record management
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('data/BeanFactory.php');

class TimerService
{
    /** @var string $taskId Current task ID */
    protected $taskId;
    
    /** @var SugarBean $task Current task bean instance */
    protected $task;
    
    /**
     * Constructor
     *
     * @param string $taskId The ID of the task to manage timer for
     * @throws Exception When task not found
     */
    public function __construct($taskId)
    {
        $this->taskId = $taskId;
        $this->task = BeanFactory::getBean('Tasks', $taskId);
        
        if (!$this->task || empty($this->task->id)) {
            throw new Exception('Task not found: ' . $taskId);
        }
    }
    
    /**
     * Starts the timer for the current task
     *
     * @return array Response with success status and timer data
     * @throws Exception When timer is already running
     */
    public function startTimer()
    {
        if ($this->task->timer_is_running) {
            throw new Exception('Timer is already running for this task');
        }
        
        // Set timer state
        $this->task->timer_is_running = 1;
        $this->task->timer_start_time = gmdate('Y-m-d H:i:s');
        
        // Save the task
        $this->task->save();
        
        return array(
            'success' => true,
            'task_id' => $this->taskId,
            'start_time' => $this->task->timer_start_time,
            'total_seconds' => $this->task->timer_total_seconds,
            'message' => 'Timer started successfully'
        );
    }
    
    /**
     * Stops the timer and records the session
     *
     * @return array Response with success status and session data
     * @throws Exception When timer is not running
     */
    public function stopTimer()
    {
        if (!$this->task->timer_is_running) {
            throw new Exception('Timer is not running for this task');
        }
        
        // Calculate elapsed time
        $startTime = strtotime($this->task->timer_start_time);
        $endTime = time();
        $duration = $endTime - $startTime;
        
        // Update total seconds
        $this->task->timer_total_seconds += $duration;
        
        // Create session record
        $session = array(
            'start' => $this->task->timer_start_time,
            'end' => gmdate('Y-m-d H:i:s', $endTime),
            'duration' => $duration,
            'manual' => false,
            'user_id' => $GLOBALS['current_user']->id,
            'user_name' => $GLOBALS['current_user']->full_name,
            'notes' => ''
        );
        
        // Add session to sessions array
        $sessions = $this->getTimerSessions();
        $sessions[] = $session;
        $this->task->timer_sessions = json_encode(array('sessions' => $sessions));
        
        // Reset timer state
        $this->task->timer_is_running = 0;
        $this->task->timer_start_time = null;
        
        // Save the task
        $this->task->save();
        
        return array(
            'success' => true,
            'task_id' => $this->taskId,
            'session' => $session,
            'total_seconds' => $this->task->timer_total_seconds,
            'message' => 'Timer stopped successfully'
        );
    }
    
    /**
     * Gets the current elapsed time if timer is running
     *
     * @return int Elapsed seconds (0 if timer not running)
     */
    public function getElapsedTime()
    {
        if (!$this->task->timer_is_running || empty($this->task->timer_start_time)) {
            return 0;
        }
        
        $startTime = strtotime($this->task->timer_start_time);
        return time() - $startTime;
    }
    
    /**
     * Adds a manual time entry
     *
     * @param int $seconds Number of seconds to add
     * @param string $notes Optional notes for the entry
     * @return array Response with success status and updated data
     * @throws Exception When invalid seconds provided
     */
    public function addManualTime($seconds, $notes = '')
    {
        if ($seconds <= 0) {
            throw new Exception('Invalid time duration');
        }
        
        // Update total seconds
        $this->task->timer_total_seconds += $seconds;
        
        // Create manual session record
        $session = array(
            'start' => gmdate('Y-m-d H:i:s'),
            'end' => gmdate('Y-m-d H:i:s'),
            'duration' => $seconds,
            'manual' => true,
            'user_id' => $GLOBALS['current_user']->id,
            'user_name' => $GLOBALS['current_user']->full_name,
            'notes' => $notes
        );
        
        // Add session to sessions array
        $sessions = $this->getTimerSessions();
        $sessions[] = $session;
        $this->task->timer_sessions = json_encode(array('sessions' => $sessions));
        
        // Save the task
        $this->task->save();
        
        return array(
            'success' => true,
            'task_id' => $this->taskId,
            'session' => $session,
            'total_seconds' => $this->task->timer_total_seconds,
            'message' => 'Manual time added successfully'
        );
    }
    
    /**
     * Gets all timer sessions for the task
     *
     * @return array Array of timer session records
     */
    public function getTimerSessions()
    {
        if (empty($this->task->timer_sessions)) {
            return array();
        }
        
        $data = json_decode($this->task->timer_sessions, true);
        return isset($data['sessions']) ? $data['sessions'] : array();
    }
    
    /**
     * Gets the current timer status
     *
     * @return array Timer status data
     */
    public function getTimerStatus()
    {
        $elapsed = $this->getElapsedTime();
        $total = $this->task->timer_total_seconds + $elapsed;
        
        return array(
            'task_id' => $this->taskId,
            'is_running' => (bool)$this->task->timer_is_running,
            'start_time' => $this->task->timer_start_time,
            'elapsed_seconds' => $elapsed,
            'total_seconds' => $this->task->timer_total_seconds,
            'current_total_seconds' => $total,
            'formatted_elapsed' => $this->formatSeconds($elapsed),
            'formatted_total' => $this->formatSeconds($total),
            'sessions' => $this->getTimerSessions()
        );
    }
    
    /**
     * Formats seconds into HH:MM:SS format
     *
     * @param int $seconds Number of seconds
     * @return string Formatted time string
     */
    public function formatSeconds($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }
}
