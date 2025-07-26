<?php
/**
 * @fileoverview Timer Fields for Tasks Module
 *
 * Extends the Tasks module with time tracking capabilities by adding timer-related
 * fields to the tasks table. These fields enable accurate time tracking for billing,
 * productivity analysis, and project estimation.
 *
 * Key Features:
 * - Timer state management (running/stopped)
 * - Total accumulated time tracking
 * - Session-based time recording
 * - Manual time entry support
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Timer fields to be added to the Tasks vardefs
$timerFieldDefs = array(
    // Timer state management
    'timer_is_running' => array(
        'name' => 'timer_is_running',
        'vname' => 'LBL_TIMER_IS_RUNNING',
        'type' => 'bool',
        'default' => 0,
        'reportable' => true,
        'audited' => false,
        'comment' => 'Indicates if the timer is currently running'
    ),
    
    // Timer start timestamp
    'timer_start_time' => array(
        'name' => 'timer_start_time',
        'vname' => 'LBL_TIMER_START_TIME',
        'type' => 'datetime',
        'dbType' => 'datetime',
        'reportable' => true,
        'audited' => false,
        'comment' => 'Timestamp when the current timer session started'
    ),
    
    // Total accumulated time in seconds
    'timer_total_seconds' => array(
        'name' => 'timer_total_seconds',
        'vname' => 'LBL_TIMER_TOTAL_SECONDS',
        'type' => 'int',
        'len' => 11,
        'default' => 0,
        'reportable' => true,
        'audited' => true,
        'comment' => 'Total accumulated time in seconds'
    ),
    
    // Timer sessions JSON data
    'timer_sessions' => array(
        'name' => 'timer_sessions',
        'vname' => 'LBL_TIMER_SESSIONS',
        'type' => 'text',
        'dbType' => 'text',
        'reportable' => false,
        'audited' => false,
        'comment' => 'JSON array of timer sessions with start/end times and durations'
    ),
    
    // Formatted total time display (non-db field)
    'timer_total_display' => array(
        'name' => 'timer_total_display',
        'vname' => 'LBL_TIMER_TOTAL_DISPLAY',
        'type' => 'varchar',
        'source' => 'non-db',
        'comment' => 'Formatted display of total time (HH:MM:SS)'
    )
);

// Function to add timer fields to existing Tasks vardefs
function addTimerFieldsToTasks(&$dictionary)
{
    global $timerFieldDefs;
    
    if (isset($dictionary['Task']['fields'])) {
        foreach ($timerFieldDefs as $fieldName => $fieldDef) {
            $dictionary['Task']['fields'][$fieldName] = $fieldDef;
        }
    }
}
