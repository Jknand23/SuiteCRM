<?php
/**
 * @fileoverview Campaign Progress Dashboard Widget
 *
 * Provides real-time campaign performance metrics and visualizations for marketing teams.
 * This dashlet displays key indicators including active campaign leads, budget utilization,
 * and performance trends using Chart.js visualizations and Alpine.js reactivity.
 *
 * Key Features:
 * - Real-time campaign metrics display
 * - Budget utilization progress bars
 * - Performance trend charts
 * - Interactive drill-down capabilities
 * - Theme-aware styling
 *
 * Dependencies:
 * - Base Dashlet class for dashboard integration
 * - Chart.js for data visualization
 * - Alpine.js for reactive UI updates
 * - Campaign module for data access
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/Dashlets/Dashlet.php');
require_once('modules/Campaigns/Campaign.php');

class CampaignProgressDashlet extends Dashlet
{
    /**
     * @var int Number of days to look back for metrics
     */
    public $lookbackDays = 7;
    
    /**
     * @var array Selected metrics to display
     */
    public $selectedMetrics = ['new_leads', 'budget_utilization', 'performance_trend'];
    
    /**
     * @var bool Show only active campaigns
     */
    public $activeOnly = true;
    
    /**
     * @var string Time range for data
     */
    public $timeRange = 'week';
    
    /**
     * Constructor
     *
     * @param string $id Unique dashlet identifier
     * @param array $def Dashlet definition array
     */
    public function __construct($id, $def)
    {
        parent::__construct($id);
        
        // Initialize default values
        $this->lookbackDays = 7;
        $this->selectedMetrics = ['new_leads', 'budget_utilization', 'performance_trend', 'campaign_list'];
        $this->activeOnly = true;
        $this->timeRange = 'week';
        $this->autoRefresh = 0;
        
        $this->isConfigurable = true;
        $this->isRefreshable = true;
        $this->hasScript = true;
        
        // Load language strings
        $this->loadLanguage('CampaignProgressDashlet', 'modules/Campaigns/Dashlets/');
        
        // Set title
        if (!empty($def['title'])) {
            $this->title = $def['title'];
        } else {
            $this->title = $this->dashletStrings['LBL_TITLE'];
        }
        
        // Load saved options
        if (!empty($def['lookbackDays'])) {
            $this->lookbackDays = $def['lookbackDays'];
        }
        if (!empty($def['selectedMetrics'])) {
            $this->selectedMetrics = $def['selectedMetrics'];
        }
        if (isset($def['activeOnly'])) {
            $this->activeOnly = $def['activeOnly'];
        }
        if (!empty($def['timeRange'])) {
            $this->timeRange = $def['timeRange'];
        }
        if (isset($def['autoRefresh'])) {
            $this->autoRefresh = $def['autoRefresh'];
        }
    }
    
    /**
     * Displays the dashlet content
     *
     * @return string HTML content for display
     */
    public function display()
    {
        global $current_user, $sugar_config;
        
        $ss = new Sugar_Smarty();
        
        // Get campaign metrics
        $metrics = $this->getCampaignMetrics();
        
        // Debug output
        error_log('Campaign Progress Dashlet - Metrics: ' . json_encode($metrics));
        
        // Assign variables to template
        $ss->assign('dashletId', $this->id);
        $ss->assign('metrics', $metrics);
        $ss->assign('selectedMetrics', $this->selectedMetrics);
        $ss->assign('timeRange', $this->timeRange);
        $ss->assign('dashletStrings', $this->dashletStrings);
        
        // Assign theme variables for Chart.js
        $ss->assign('chartColors', $this->getThemeChartColors());
        
        // API endpoint for real-time updates
        $apiUrl = $sugar_config['site_url'] . '/Api/V8/campaigns/metrics';
        $ss->assign('apiUrl', $apiUrl);
        
        // Include Chart.js and Alpine.js
        $ss->assign('includeChartJs', true);
        $ss->assign('includeAlpineJs', true);
        
        // Auto-refresh setting
        $ss->assign('autoRefresh', isset($this->autoRefresh) ? $this->autoRefresh : 0);
        
        // Additional template variables
        $ss->assign('sugar_config', $sugar_config);
        
        $parentDisplay = parent::display();
        $templateContent = '';
        
        try {
            // Use the actual display template
            $templateContent = $ss->fetch('modules/Campaigns/Dashlets/CampaignProgressDashlet/CampaignProgressDashletDisplay.tpl');
        } catch (Exception $e) {
            // If template fails, return a safe error message
            $templateContent = '<div class="alert alert-danger">Campaign Progress Dashlet: Template Error - ' . $e->getMessage() . '</div>';
        }
        
        return $parentDisplay . $templateContent;
    }
    
    /**
     * Retrieves campaign metrics data
     *
     * @return array Campaign metrics including leads, budget, and trends
     */
    protected function getCampaignMetrics()
    {
        global $db, $timedate;
        
        $metrics = [
            'new_leads_count' => 0,
            'active_campaigns' => 0,
            'budget_utilization' => 0,
            'performance_data' => [],
            'campaign_list' => []
        ];
        
        // Calculate date range
        $endDate = $timedate->getNow();
        $startDate = clone $endDate;
        $startDate->modify("-{$this->lookbackDays} days");
        
        // Get active campaigns
        $campaignQuery = "SELECT c.id, c.name, c.status, c.budget, c.actual_cost 
                         FROM campaigns c 
                         WHERE c.deleted = 0";
        
        if ($this->activeOnly) {
            $campaignQuery .= " AND c.status = 'Active'";
        }
        
        $result = $db->query($campaignQuery);
        
        while ($row = $db->fetchByAssoc($result)) {
            $metrics['active_campaigns']++;
            
            // Calculate budget utilization
            if ($row['budget'] > 0) {
                $utilization = ($row['actual_cost'] / $row['budget']) * 100;
                $metrics['campaign_list'][] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'budget_utilization' => round($utilization, 2)
                ];
            }
            
            // Get lead count for this campaign
            $leadCount = $this->getCampaignLeadCount($row['id'], $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
            $metrics['new_leads_count'] += $leadCount;
        }
        
        // Calculate average budget utilization
        if (count($metrics['campaign_list']) > 0) {
            $totalUtilization = array_sum(array_column($metrics['campaign_list'], 'budget_utilization'));
            $metrics['budget_utilization'] = round($totalUtilization / count($metrics['campaign_list']), 2);
        }
        
        // Get performance trend data
        $metrics['performance_data'] = $this->getPerformanceTrendData($startDate, $endDate);
        
        return $metrics;
    }
    
    /**
     * Gets lead count for a specific campaign within date range
     *
     * @param string $campaignId Campaign UUID
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return int Number of leads created
     */
    protected function getCampaignLeadCount($campaignId, $startDate, $endDate)
    {
        global $db;
        
        $query = "SELECT COUNT(*) as lead_count 
                 FROM leads l
                 JOIN campaign_log cl ON l.id = cl.target_id
                 WHERE cl.campaign_id = '{$campaignId}'
                 AND cl.target_type = 'Leads'
                 AND cl.activity_type = 'lead'
                 AND DATE(cl.date_modified) BETWEEN '{$startDate}' AND '{$endDate}'
                 AND l.deleted = 0
                 AND cl.deleted = 0";
        
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        
        return (int)$row['lead_count'];
    }
    
    /**
     * Gets performance trend data for charts
     *
     * @param DateTime $startDate Start date
     * @param DateTime $endDate End date
     * @return array Daily performance data
     */
    protected function getPerformanceTrendData($startDate, $endDate)
    {
        global $db;
        
        $trendData = [];
        $currentDate = clone $startDate;
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Get daily lead count
            $query = "SELECT COUNT(*) as count 
                     FROM campaign_log cl
                     WHERE DATE(cl.date_modified) = '{$dateStr}'
                     AND cl.activity_type = 'lead'
                     AND cl.deleted = 0";
            
            $result = $db->query($query);
            $row = $db->fetchByAssoc($result);
            
            $trendData[] = [
                'date' => $currentDate->format('M d'),
                'leads' => (int)$row['count']
            ];
            
            $currentDate->modify('+1 day');
        }
        
        return $trendData;
    }
    
    /**
     * Gets theme-aware colors for Chart.js
     *
     * @return array Color configuration for charts
     */
    protected function getThemeChartColors()
    {
        return [
            'primary' => 'var(--theme-primary)',
            'success' => 'var(--theme-success)',
            'warning' => 'var(--theme-warning)',
            'danger' => 'var(--theme-danger)',
            'info' => 'var(--theme-info)',
            'gridLines' => 'var(--theme-border-subtle)',
            'text' => 'var(--theme-text)'
        ];
    }
    
    /**
     * Displays configuration options
     *
     * @return string HTML for configuration panel
     */
    public function displayOptions()
    {
        global $app_strings;
        
        $ss = new Sugar_Smarty();
        $ss->assign('id', $this->id);
        $ss->assign('title', $this->title);
        $ss->assign('lookbackDays', $this->lookbackDays);
        $ss->assign('selectedMetrics', $this->selectedMetrics);
        $ss->assign('activeOnly', $this->activeOnly);
        $ss->assign('timeRange', $this->timeRange);
        $ss->assign('dashletStrings', $this->dashletStrings);
        $ss->assign('app_strings', $app_strings);
        
        // Auto refresh options
        if ($this->isAutoRefreshable()) {
            $ss->assign('isRefreshable', true);
            $ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
            $ss->assign('autoRefreshSelect', $this->autoRefresh);
        }
        
        return parent::displayOptions() . $ss->fetch('modules/Campaigns/Dashlets/CampaignProgressDashlet/CampaignProgressDashletConfigure.tpl');
    }
    
    /**
     * Saves configuration options
     *
     * @param array $req Request data containing options
     * @return array Filtered options to save
     */
    public function saveOptions($req)
    {
        $options = [];
        
        if (!empty($req['title'])) {
            $options['title'] = $req['title'];
        }
        
        if (!empty($req['lookbackDays'])) {
            $options['lookbackDays'] = (int)$req['lookbackDays'];
        }
        
        if (!empty($req['selectedMetrics'])) {
            $options['selectedMetrics'] = $req['selectedMetrics'];
        }
        
        $options['activeOnly'] = !empty($req['activeOnly']);
        
        if (!empty($req['timeRange'])) {
            $options['timeRange'] = $req['timeRange'];
        }
        
        if (isset($req['autoRefresh'])) {
            $options['autoRefresh'] = $req['autoRefresh'];
        }
        
        return $options;
    }
    
    /**
     * Determines if user has access to view this dashlet
     *
     * @return bool True if user can view campaigns module
     */
    public function hasAccess()
    {
        global $current_user;
        return ACLController::checkAccess('Campaigns', 'view', true);
    }
    
    /**
     * Displays JavaScript for the dashlet
     *
     * This method is required when hasScript is set to true.
     * Returns JavaScript code that needs to be included for the dashlet.
     *
     * @return string JavaScript code for the dashlet
     */
    public function displayScript()
    {
        // Return empty string for now as our JavaScript is embedded in the template
        // This method is required by SuiteCRM when hasScript = true
        return '';
    }
}
