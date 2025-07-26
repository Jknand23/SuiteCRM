{*
/**
 * @fileoverview Task Timer Widget Quick Create Template
 * 
 * Simplified timer widget for Quick Create forms. Provides option to start
 * timer immediately when creating a new task.
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

<div class="timer-quick-widget">
    <div class="form-group">
        <label>{$MOD.LBL_PANEL_TIMER}:</label>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="timer_start_on_create" name="timer_start_on_create" value="1">
                Start timer when task is created
            </label>
        </div>
        <p class="help-block">
            <i class="glyphicon glyphicon-info-sign"></i>
            The timer will begin tracking time immediately after the task is saved.
        </p>
    </div>
</div> 