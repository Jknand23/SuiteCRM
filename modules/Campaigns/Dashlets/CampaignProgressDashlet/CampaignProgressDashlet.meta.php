<?php
/**
 * @fileoverview Campaign Progress Dashlet Metadata
 *
 * Registers the Campaign Progress Dashboard Widget in the SuiteCRM dashlet system.
 * Makes the dashlet available in the "Add Dashlet" menu for users to add to their
 * dashboard with proper categorization and description.
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['CampaignProgressDashlet'] = array(
    'module' => 'Campaigns',
    'title' => translate('LBL_CAMPAIGN_PROGRESS', 'Campaigns'),
    'description' => 'Real-time campaign performance metrics with interactive visualizations',
    'icon' => 'icon_Campaigns_32.gif',
    'category' => 'Module Views'
);
