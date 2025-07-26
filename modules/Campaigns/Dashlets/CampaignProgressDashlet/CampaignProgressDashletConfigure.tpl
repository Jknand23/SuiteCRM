{*
/**
 * @fileoverview Campaign Progress Dashlet Configuration Template
 * 
 * Smarty template for configuring the Campaign Progress Dashboard Widget.
 * Allows users to customize displayed metrics, time ranges, and refresh settings.
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

<div class="campaign-progress-config">
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <td valign='top' nowrap class='dataLabel' width="20%">
                {$dashletStrings.LBL_CONFIGURE_TITLE}:
            </td>
            <td valign='top' class='dataField' width="80%">
                <input class="text" name="title" size="30" maxlength="255" value="{$title}">
            </td>
        </tr>
        
        <tr>
            <td valign='top' nowrap class='dataLabel'>
                {$dashletStrings.LBL_TIME_RANGE}:
            </td>
            <td valign='top' class='dataField'>
                <select name="timeRange" class="form-control">
                    <option value="day" {if $timeRange == 'day'}selected{/if}>{$dashletStrings.LBL_TIME_RANGE_DAY}</option>
                    <option value="week" {if $timeRange == 'week'}selected{/if}>{$dashletStrings.LBL_TIME_RANGE_WEEK}</option>
                    <option value="month" {if $timeRange == 'month'}selected{/if}>{$dashletStrings.LBL_TIME_RANGE_MONTH}</option>
                    <option value="quarter" {if $timeRange == 'quarter'}selected{/if}>{$dashletStrings.LBL_TIME_RANGE_QUARTER}</option>
                    <option value="year" {if $timeRange == 'year'}selected{/if}>{$dashletStrings.LBL_TIME_RANGE_YEAR}</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td valign='top' nowrap class='dataLabel'>
                {$dashletStrings.LBL_LOOKBACK_DAYS}:
            </td>
            <td valign='top' class='dataField'>
                <input type="number" name="lookbackDays" class="form-control" value="{$lookbackDays}" min="1" max="365" style="width: 100px;">
            </td>
        </tr>
        
        <tr>
            <td valign='top' nowrap class='dataLabel'>
                {$dashletStrings.LBL_SELECT_METRICS}:
            </td>
            <td valign='top' class='dataField'>
                <div class="metric-checkboxes">
                    <label class="checkbox">
                        <input type="checkbox" name="selectedMetrics[]" value="new_leads" 
                               {if in_array('new_leads', $selectedMetrics)}checked{/if}>
                        {$dashletStrings.LBL_METRIC_NEW_LEADS}
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" name="selectedMetrics[]" value="budget_utilization" 
                               {if in_array('budget_utilization', $selectedMetrics)}checked{/if}>
                        {$dashletStrings.LBL_METRIC_BUDGET_UTIL}
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" name="selectedMetrics[]" value="performance_trend" 
                               {if in_array('performance_trend', $selectedMetrics)}checked{/if}>
                        {$dashletStrings.LBL_METRIC_PERFORMANCE}
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" name="selectedMetrics[]" value="campaign_list" 
                               {if in_array('campaign_list', $selectedMetrics)}checked{/if}>
                        {$dashletStrings.LBL_METRIC_CAMPAIGN_LIST}
                    </label>
                </div>
            </td>
        </tr>
        
        <tr>
            <td valign='top' nowrap class='dataLabel'>
                {$dashletStrings.LBL_ACTIVE_ONLY}:
            </td>
            <td valign='top' class='dataField'>
                <input type="checkbox" name="activeOnly" value="1" {if $activeOnly}checked{/if}>
            </td>
        </tr>
        
        {if $isRefreshable}
        <tr>
            <td valign='top' nowrap class='dataLabel'>
                {$app_strings.LBL_DASHLET_CONFIGURE_AUTOREFRESH}:
            </td>
            <td valign='top' class='dataField'>
                <select name="autoRefresh" class="form-control">
                    {html_options options=$autoRefreshOptions selected=$autoRefreshSelect}
                </select>
            </td>
        </tr>
        {/if}
    </table>
    
    <div class="dashletConfigButtons" style="margin-top: 20px;">
        <input type="submit" class="btn btn-primary" value="{$app_strings.LBL_SAVE_BUTTON_LABEL}">
        <input type="button" class="btn btn-default" onclick="SUGAR.mySugar.closeDashletConfig()" value="{$app_strings.LBL_CANCEL_BUTTON_LABEL}">
    </div>
</div>

<style>
.campaign-progress-config .dataLabel {
    font-weight: bold;
    padding: 10px;
}

.campaign-progress-config .dataField {
    padding: 10px;
}

.metric-checkboxes label {
    display: block;
    margin-bottom: 5px;
    font-weight: normal;
}

.metric-checkboxes input[type="checkbox"] {
    margin-right: 8px;
}
</style> 