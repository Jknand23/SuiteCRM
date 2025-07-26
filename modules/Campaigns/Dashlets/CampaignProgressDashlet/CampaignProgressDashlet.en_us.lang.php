<?php
/**
 * @fileoverview Language strings for Campaign Progress Dashlet
 *
 * English (US) language definitions for the Campaign Progress Dashboard Widget.
 * Contains all user-facing strings for the dashlet interface, configuration,
 * and metrics display.
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dashletStrings['CampaignProgressDashlet'] = [
    // General
    'LBL_TITLE' => 'Campaign Progress',
    'LBL_DESCRIPTION' => 'Real-time campaign performance metrics and analytics',
    'LBL_CONFIGURE_TITLE' => 'Configure Campaign Progress Widget',
    
    // Metrics Labels
    'LBL_NEW_LEADS' => 'New Leads This Week',
    'LBL_ACTIVE_CAMPAIGNS' => 'Active Campaigns',
    'LBL_BUDGET_UTILIZATION' => 'Budget Utilization',
    'LBL_PERFORMANCE_TREND' => 'Performance Trend',
    'LBL_CAMPAIGN_STATUS' => 'Campaign Status',
    
    // Configuration Options
    'LBL_LOOKBACK_DAYS' => 'Days to Look Back',
    'LBL_SELECT_METRICS' => 'Select Metrics to Display',
    'LBL_ACTIVE_ONLY' => 'Show Active Campaigns Only',
    'LBL_TIME_RANGE' => 'Time Range',
    
    // Time Range Options
    'LBL_TIME_RANGE_DAY' => 'Today',
    'LBL_TIME_RANGE_WEEK' => 'This Week',
    'LBL_TIME_RANGE_MONTH' => 'This Month',
    'LBL_TIME_RANGE_QUARTER' => 'This Quarter',
    'LBL_TIME_RANGE_YEAR' => 'This Year',
    
    // Metric Options
    'LBL_METRIC_NEW_LEADS' => 'New Leads Count',
    'LBL_METRIC_BUDGET_UTIL' => 'Budget Utilization Percentage',
    'LBL_METRIC_PERFORMANCE' => 'Performance Trend Chart',
    'LBL_METRIC_CAMPAIGN_LIST' => 'Campaign List with Progress',
    
    // Chart Labels
    'LBL_CHART_LEADS_OVER_TIME' => 'Leads Over Time',
    'LBL_CHART_BUDGET_PROGRESS' => 'Budget Progress',
    'LBL_CHART_NO_DATA' => 'No data available for selected time range',
    
    // Status Messages
    'LBL_LOADING' => 'Loading campaign data...',
    'LBL_ERROR_LOADING' => 'Error loading campaign metrics',
    'LBL_NO_CAMPAIGNS' => 'No active campaigns found',
    'LBL_UPDATED' => 'Last updated',
    
    // Tooltips
    'LBL_TOOLTIP_NEW_LEADS' => 'Number of new leads generated from active campaigns',
    'LBL_TOOLTIP_BUDGET_UTIL' => 'Percentage of allocated budget used across campaigns',
    'LBL_TOOLTIP_CLICK_DETAILS' => 'Click to view campaign details',
    
    // Actions
    'LBL_VIEW_CAMPAIGN' => 'View Campaign',
    'LBL_VIEW_ALL_CAMPAIGNS' => 'View All Campaigns',
    'LBL_REFRESH' => 'Refresh Data',
    'LBL_EXPORT_DATA' => 'Export Metrics',
    
    // Units
    'LBL_PERCENT' => '%',
    'LBL_LEADS' => 'leads',
    'LBL_DAYS' => 'days',
];
