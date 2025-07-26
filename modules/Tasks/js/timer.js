/**
 * @fileoverview Task Timer JavaScript Component
 * 
 * Client-side timer functionality for task time tracking. Provides real-time
 * timer display updates, AJAX interactions with server endpoints, and UI
 * management for timer controls. Compatible with Bootstrap 3.3.7 and jQuery.
 * 
 * Key Features:
 * - Real-time timer display with automatic updates
 * - Start/stop timer functionality with server synchronization
 * - Manual time entry modal interface
 * - Session history display and management
 * - Error handling and user feedback
 * 
 * Dependencies:
 * - jQuery (existing in SuiteCRM)
 * - Bootstrap 3.3.7 for modal and UI components
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

var TaskTimer = (function($) {
    'use strict';
    
    // Private variables
    var timerInterval = null;
    var currentTaskId = null;
    var timerState = {
        isRunning: false,
        startTime: null,
        totalSeconds: 0,
        elapsedSeconds: 0
    };
    
    /**
     * Initializes the timer for a specific task
     * 
     * @param {string} taskId The ID of the task
     */
    function init(taskId) {
        currentTaskId = taskId;
        
        // Load initial timer status
        loadTimerStatus(function() {
            // Set up UI event handlers
            bindEventHandlers();
            
            // Start update interval if timer is running
            if (timerState.isRunning) {
                startUpdateInterval();
            }
        });
    }
    
    /**
     * Binds event handlers to timer UI elements
     */
    function bindEventHandlers() {
        // Start/Stop button
        $('#timer-toggle-btn').on('click', function(e) {
            e.preventDefault();
            toggleTimer();
        });
        
        // Manual time entry button
        $('#timer-manual-btn').on('click', function(e) {
            e.preventDefault();
            showManualTimeModal();
        });
        
        // Manual time form submission
        $('#timer-manual-form').on('submit', function(e) {
            e.preventDefault();
            submitManualTime();
        });
        
        // Session history toggle
        $('#timer-sessions-toggle').on('click', function(e) {
            e.preventDefault();
            $('#timer-sessions-list').slideToggle();
            $(this).find('i').toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        });
    }
    
    /**
     * Loads the current timer status from server
     * 
     * @param {Function} callback Optional callback after loading
     */
    function loadTimerStatus(callback) {
        $.ajax({
            url: 'index.php?module=Tasks&action=TimerStatus',
            type: 'GET',
            data: { task_id: currentTaskId },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    updateTimerState(response);
                    updateUI();
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            },
            error: function() {
                showError('Failed to load timer status');
            }
        });
    }
    
    /**
     * Toggles the timer between running and stopped states
     */
    function toggleTimer() {
        var action = timerState.isRunning ? 'TimerStop' : 'TimerStart';
        var button = $('#timer-toggle-btn');
        
        // Disable button during request
        button.prop('disabled', true);
        
        $.ajax({
            url: 'index.php?module=Tasks&action=' + action,
            type: 'POST',
            data: { task_id: currentTaskId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Reload timer status
                    loadTimerStatus(function() {
                        if (timerState.isRunning) {
                            startUpdateInterval();
                            showSuccess('Timer started');
                        } else {
                            stopUpdateInterval();
                            showSuccess('Timer stopped');
                        }
                    });
                } else {
                    showError(response.error || 'Timer operation failed');
                }
            },
            error: function() {
                showError('Failed to ' + (timerState.isRunning ? 'stop' : 'start') + ' timer');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    }
    
    /**
     * Shows the manual time entry modal
     */
    function showManualTimeModal() {
        $('#timer-manual-modal').modal('show');
        $('#timer-manual-hours').val('');
        $('#timer-manual-minutes').val('');
        $('#timer-manual-notes').val('');
    }
    
    /**
     * Submits manual time entry
     */
    function submitManualTime() {
        var hours = parseInt($('#timer-manual-hours').val()) || 0;
        var minutes = parseInt($('#timer-manual-minutes').val()) || 0;
        var notes = $('#timer-manual-notes').val();
        
        if (hours === 0 && minutes === 0) {
            showError('Please enter a valid time duration');
            return;
        }
        
        $.ajax({
            url: 'index.php?module=Tasks&action=TimerAddManual',
            type: 'POST',
            data: {
                task_id: currentTaskId,
                hours: hours,
                minutes: minutes,
                notes: notes
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#timer-manual-modal').modal('hide');
                    loadTimerStatus();
                    showSuccess('Manual time added successfully');
                } else {
                    showError(response.error || 'Failed to add manual time');
                }
            },
            error: function() {
                showError('Failed to add manual time');
            }
        });
    }
    
    /**
     * Updates the internal timer state from server response
     * 
     * @param {Object} data Server response data
     */
    function updateTimerState(data) {
        timerState.isRunning = data.is_running;
        timerState.startTime = data.start_time;
        timerState.totalSeconds = data.total_seconds;
        timerState.elapsedSeconds = data.elapsed_seconds;
        timerState.currentTotal = data.current_total_seconds;
        timerState.sessions = data.sessions || [];
    }
    
    /**
     * Updates the UI to reflect current timer state
     */
    function updateUI() {
        // Update button state
        var button = $('#timer-toggle-btn');
        if (timerState.isRunning) {
            button.removeClass('btn-success').addClass('btn-danger');
            button.html('<i class="glyphicon glyphicon-stop"></i> Stop Timer');
        } else {
            button.removeClass('btn-danger').addClass('btn-success');
            button.html('<i class="glyphicon glyphicon-play"></i> Start Timer');
        }
        
        // Update time displays
        updateTimeDisplay();
        
        // Update sessions list
        updateSessionsList();
    }
    
    /**
     * Updates the time display elements
     */
    function updateTimeDisplay() {
        var currentTotal = timerState.totalSeconds;
        if (timerState.isRunning) {
            currentTotal += timerState.elapsedSeconds;
        }
        
        $('#timer-elapsed').text(formatTime(timerState.elapsedSeconds));
        $('#timer-total').text(formatTime(currentTotal));
        
        // Update status indicator
        if (timerState.isRunning) {
            $('#timer-status').html('<span class="label label-success">Running</span>');
        } else {
            $('#timer-status').html('<span class="label label-default">Stopped</span>');
        }
    }
    
    /**
     * Updates the sessions history list
     */
    function updateSessionsList() {
        var sessionsHtml = '';
        
        if (timerState.sessions && timerState.sessions.length > 0) {
            // Sort sessions by start time (newest first)
            var sortedSessions = timerState.sessions.slice().reverse();
            
            sortedSessions.forEach(function(session) {
                var typeLabel = session.manual ? 
                    '<span class="label label-info">Manual</span>' : 
                    '<span class="label label-primary">Tracked</span>';
                
                sessionsHtml += '<tr>' +
                    '<td>' + formatDateTime(session.start) + '</td>' +
                    '<td>' + formatTime(session.duration) + '</td>' +
                    '<td>' + typeLabel + '</td>' +
                    '<td>' + (session.user_name || '') + '</td>' +
                    '<td>' + (session.notes || '') + '</td>' +
                    '</tr>';
            });
        } else {
            sessionsHtml = '<tr><td colspan="5" class="text-center text-muted">No time sessions recorded</td></tr>';
        }
        
        $('#timer-sessions-tbody').html(sessionsHtml);
    }
    
    /**
     * Starts the timer update interval
     */
    function startUpdateInterval() {
        // Clear any existing interval
        stopUpdateInterval();
        
        // Update every second
        timerInterval = setInterval(function() {
            timerState.elapsedSeconds++;
            updateTimeDisplay();
        }, 1000);
    }
    
    /**
     * Stops the timer update interval
     */
    function stopUpdateInterval() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }
    
    /**
     * Formats seconds into HH:MM:SS format
     * 
     * @param {number} seconds Total seconds
     * @return {string} Formatted time string
     */
    function formatTime(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var secs = seconds % 60;
        
        return pad(hours) + ':' + pad(minutes) + ':' + pad(secs);
    }
    
    /**
     * Formats a datetime string for display
     * 
     * @param {string} datetime DateTime string
     * @return {string} Formatted datetime
     */
    function formatDateTime(datetime) {
        if (!datetime) return '';
        
        var date = new Date(datetime + ' UTC');
        return date.toLocaleString();
    }
    
    /**
     * Pads a number with leading zero if needed
     * 
     * @param {number} num Number to pad
     * @return {string} Padded string
     */
    function pad(num) {
        return num < 10 ? '0' + num : num;
    }
    
    /**
     * Shows a success message
     * 
     * @param {string} message Success message
     */
    function showSuccess(message) {
        var alert = $('<div class="alert alert-success alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            message + '</div>');
        
        $('#timer-messages').html(alert);
        setTimeout(function() {
            alert.fadeOut();
        }, 3000);
    }
    
    /**
     * Shows an error message
     * 
     * @param {string} message Error message
     */
    function showError(message) {
        var alert = $('<div class="alert alert-danger alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            message + '</div>');
        
        $('#timer-messages').html(alert);
    }
    
    // Public API
    return {
        init: init,
        refresh: loadTimerStatus
    };
    
})(jQuery);

// Initialize when DOM is ready
$(document).ready(function() {
    // Check if we're on a task detail/edit page with timer
    var taskId = $('input[name="record"]').val();
    if (taskId && $('#timer-widget').length > 0) {
        TaskTimer.init(taskId);
    }
}); 