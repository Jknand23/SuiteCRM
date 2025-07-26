{*
/**
 * @fileoverview Task Timer Widget Smarty Template
 * 
 * Renders the timer widget interface for task time tracking. Displays current
 * timer status, controls for starting/stopping, manual time entry, and session
 * history. Compatible with Bootstrap 3.3.7 and SuiteCRM's Smarty template system.
 * 
 * Template Variables:
 * - $task: The current task bean object
 * - $timerData: Timer status data from the service
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

<div id="timer-widget" class="timer-widget">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-time"></i> Time Tracker
                <span id="timer-status" class="timer-status"></span>
            </h3>
        </div>
        
        <div class="panel-body">
            {* Timer Display *}
            <div class="timer-display">
                <div class="timer-display-item">
                    <span class="timer-display-label">Current Session</span>
                    <span id="timer-elapsed" class="timer-display-value">00:00:00</span>
                </div>
                <div class="timer-display-item">
                    <span class="timer-display-label">Total Time</span>
                    <span id="timer-total" class="timer-display-value">00:00:00</span>
                </div>
            </div>
            
            {* Timer Controls *}
            <div class="timer-controls">
                <button id="timer-toggle-btn" class="btn btn-success" type="button">
                    <i class="glyphicon glyphicon-play"></i> Start Timer
                </button>
                <button id="timer-manual-btn" class="btn btn-default" type="button">
                    <i class="glyphicon glyphicon-plus"></i> Add Time
                </button>
            </div>
            
            {* Messages Container *}
            <div id="timer-messages"></div>
            
            {* Timer Sessions *}
            <div class="timer-sessions">
                <div id="timer-sessions-toggle" class="timer-sessions-header">
                    <h4>
                        Time Sessions
                        <i class="glyphicon glyphicon-chevron-down"></i>
                    </h4>
                </div>
                <div id="timer-sessions-list" class="timer-sessions-list">
                    <table class="table table-striped timer-sessions-table">
                        <thead>
                            <tr>
                                <th>Start Time</th>
                                <th>Duration</th>
                                <th>Type</th>
                                <th>User</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="timer-sessions-tbody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No time sessions recorded
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{* Manual Time Entry Modal *}
<div id="timer-manual-modal" class="modal fade timer-manual-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add Manual Time Entry</h4>
            </div>
            <form id="timer-manual-form">
                <div class="modal-body">
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="timer-manual-hours">Hours:</label>
                            <input type="number" class="form-control" id="timer-manual-hours" 
                                   min="0" max="999" value="0">
                        </div>
                        <div class="form-group">
                            <label for="timer-manual-minutes">Minutes:</label>
                            <input type="number" class="form-control" id="timer-manual-minutes" 
                                   min="0" max="59" value="0">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <label for="timer-manual-notes">Notes (optional):</label>
                        <textarea class="form-control" id="timer-manual-notes" rows="3"
                                  placeholder="Describe the work performed..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Time</button>
                </div>
            </form>
        </div>
    </div>
</div>

{* Include timer JavaScript and CSS *}
<link rel="stylesheet" type="text/css" href="modules/Tasks/css/timer.css">
<script type="text/javascript" src="modules/Tasks/js/timer.js"></script> 