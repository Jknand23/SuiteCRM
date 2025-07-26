<?php
/**
 * @fileoverview Campaign Metrics API Controller
 *
 * RESTful API controller providing campaign performance metrics and analytics data.
 * Supports the Campaign Progress Dashboard Widget with real-time metrics including
 * lead generation, budget utilization, and performance trends. Implements caching
 * for optimal performance and provides flexible time range filtering.
 *
 * Key Features:
 * - Real-time campaign metrics calculation
 * - Flexible time range filtering (day, week, month, quarter, year)
 * - Performance trend data aggregation
 * - Budget utilization tracking
 * - Secure access with existing authentication
 * - Response caching for performance optimization
 *
 * Dependencies:
 * - Existing Slim 3 routing infrastructure
 * - Campaign and Lead modules for data access
 * - SuiteCRM authentication and ACL system
 * - JSON response formatting
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace Api\V8\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use ACLController;
use BeanFactory;
use DBManagerFactory;
use TimeDate;
use DateTime;
use DateInterval;
use SugarCache;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class CampaignMetricsController extends BaseController
{
    /**
     * @var TimeDate SuiteCRM time/date handler
     */
    protected $timedate;
    
    /**
     * @var \DBManager Database connection
     */
    protected $db;
    
    /**
     * @var string Cache key prefix
     */
    protected const CACHE_KEY_PREFIX = 'campaign_metrics_';
    
    /**
     * @var int Cache TTL in seconds (5 minutes)
     */
    protected const CACHE_TTL = 300;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        global $timedate;
        $this->timedate = $timedate;
        $this->db = DBManagerFactory::getInstance();
    }
    
    /**
     * Gets campaign metrics data
     *
     * Endpoint: GET /Api/V8/campaigns/metrics
     *
     * @param Request $request PSR-7 request object
     * @param Response $response PSR-7 response object
     * @param array $args Route arguments
     *
     * @return Response JSON response with campaign metrics
     */
    public function getMetrics(Request $request, Response $response, array $args)
    {
        global $current_user;
        
        // Check ACL permissions
        if (!ACLController::checkAccess('Campaigns', 'view', true)) {
            return $this->generateErrorResponse(
                $response,
                new \Exception('Access denied - insufficient permissions for Campaigns module'),
                403
            );
        }
        
        try {
            // Get query parameters
            $params = $request->getQueryParams();
            $timeRange = $params['timeRange'] ?? 'week';
            $activeOnly = filter_var($params['activeOnly'] ?? true, FILTER_VALIDATE_BOOLEAN);
            
            // Check cache first
            $cacheKey = self::CACHE_KEY_PREFIX . md5($timeRange . '_' . $activeOnly . '_' . $current_user->id);
            $cachedData = SugarCache::instance()->get($cacheKey);
            
            if ($cachedData !== null) {
                return $this->generateResponse($response, $cachedData, 200);
            }
            
            // Calculate date range
            $dateRange = $this->calculateDateRange($timeRange);
            
            // Gather metrics
            $metrics = [
                'new_leads_count' => $this->getNewLeadsCount($dateRange['start'], $dateRange['end'], $activeOnly),
                'active_campaigns' => $this->getActiveCampaignsCount($activeOnly),
                'budget_utilization' => $this->getAverageBudgetUtilization($activeOnly),
                'performance_data' => $this->getPerformanceTrendData($dateRange['start'], $dateRange['end']),
                'campaign_list' => $this->getCampaignListWithProgress($activeOnly),
                'metadata' => [
                    'time_range' => $timeRange,
                    'start_date' => $dateRange['start']->format('Y-m-d'),
                    'end_date' => $dateRange['end']->format('Y-m-d'),
                    'active_only' => $activeOnly,
                    'generated_at' => gmdate('c')
                ]
            ];
            
            // Cache the results
            SugarCache::instance()->set($cacheKey, $metrics, self::CACHE_TTL);
            
            return $this->generateResponse($response, $metrics, 200);
        } catch (\Exception $e) {
            $GLOBALS['log']->error('Campaign Metrics API Error: ' . $e->getMessage());
            return $this->generateErrorResponse($response, $e, 500);
        }
    }
    
    /**
     * Calculates date range based on time range parameter
     *
     * @param string $timeRange Time range identifier (day, week, month, quarter, year)
     * @return array Array with 'start' and 'end' DateTime objects
     */
    protected function calculateDateRange($timeRange)
    {
        $end = new DateTime();
        $end->setTime(23, 59, 59);
        
        $start = clone $end;
        $start->setTime(0, 0, 0);
        
        switch ($timeRange) {
            case 'day':
                // Today only
                break;
                
            case 'week':
                $start->modify('monday this week');
                break;
                
            case 'month':
                $start->modify('first day of this month');
                break;
                
            case 'quarter':
                $currentMonth = (int)$start->format('n');
                $quarterStartMonth = ceil($currentMonth / 3) * 3 - 2;
                $start->setDate($start->format('Y'), $quarterStartMonth, 1);
                break;
                
            case 'year':
                $start->modify('first day of january');
                break;
                
            default:
                // Default to week
                $start->modify('monday this week');
        }
        
        return ['start' => $start, 'end' => $end];
    }
    
    /**
     * Gets count of new leads for active campaigns in date range
     *
     * @param DateTime $startDate Start date
     * @param DateTime $endDate End date
     * @param bool $activeOnly Include only active campaigns
     * @return int Number of new leads
     */
    protected function getNewLeadsCount($startDate, $endDate, $activeOnly = true)
    {
        $startStr = $startDate->format('Y-m-d H:i:s');
        $endStr = $endDate->format('Y-m-d H:i:s');
        
        $query = "SELECT COUNT(DISTINCT cl.target_id) as lead_count
                 FROM campaign_log cl
                 INNER JOIN campaigns c ON cl.campaign_id = c.id
                 WHERE cl.target_type = 'Leads'
                 AND cl.activity_type = 'lead'
                 AND cl.date_modified BETWEEN '{$startStr}' AND '{$endStr}'
                 AND cl.deleted = 0
                 AND c.deleted = 0";
        
        if ($activeOnly) {
            $query .= " AND c.status = 'Active'";
        }
        
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);
        
        return (int)($row['lead_count'] ?? 0);
    }
    
    /**
     * Gets count of active campaigns
     *
     * @param bool $activeOnly Include only active campaigns
     * @return int Number of campaigns
     */
    protected function getActiveCampaignsCount($activeOnly = true)
    {
        $query = "SELECT COUNT(*) as campaign_count
                 FROM campaigns
                 WHERE deleted = 0";
        
        if ($activeOnly) {
            $query .= " AND status = 'Active'";
        }
        
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);
        
        return (int)($row['campaign_count'] ?? 0);
    }
    
    /**
     * Calculates average budget utilization across campaigns
     *
     * @param bool $activeOnly Include only active campaigns
     * @return float Average budget utilization percentage
     */
    protected function getAverageBudgetUtilization($activeOnly = true)
    {
        $query = "SELECT 
                    AVG(CASE 
                        WHEN budget > 0 THEN (actual_cost / budget) * 100 
                        ELSE 0 
                    END) as avg_utilization
                 FROM campaigns
                 WHERE deleted = 0
                 AND budget > 0";
        
        if ($activeOnly) {
            $query .= " AND status = 'Active'";
        }
        
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);
        
        return round((float)($row['avg_utilization'] ?? 0), 2);
    }
    
    /**
     * Gets daily performance trend data for the date range
     *
     * @param DateTime $startDate Start date
     * @param DateTime $endDate End date
     * @return array Array of daily performance data
     */
    protected function getPerformanceTrendData($startDate, $endDate)
    {
        $trendData = [];
        $currentDate = clone $startDate;
        
        // Limit to max 365 days for performance
        $maxDays = 365;
        $dayCount = 0;
        
        while ($currentDate <= $endDate && $dayCount < $maxDays) {
            $dateStr = $currentDate->format('Y-m-d');
            
            $query = "SELECT COUNT(*) as lead_count
                     FROM campaign_log
                     WHERE target_type = 'Leads'
                     AND activity_type = 'lead'
                     AND DATE(date_modified) = '{$dateStr}'
                     AND deleted = 0";
            
            $result = $this->db->query($query);
            $row = $this->db->fetchByAssoc($result);
            
            $trendData[] = [
                'date' => $currentDate->format('M d'),
                'leads' => (int)($row['lead_count'] ?? 0),
                'date_full' => $currentDate->format('Y-m-d')
            ];
            
            $currentDate->modify('+1 day');
            $dayCount++;
        }
        
        return $trendData;
    }
    
    /**
     * Gets list of campaigns with budget progress
     *
     * @param bool $activeOnly Include only active campaigns
     * @return array Array of campaign data with progress
     */
    protected function getCampaignListWithProgress($activeOnly = true)
    {
        $campaignList = [];
        
        $query = "SELECT 
                    id,
                    name,
                    status,
                    budget,
                    actual_cost,
                    CASE 
                        WHEN budget > 0 THEN (actual_cost / budget) * 100 
                        ELSE 0 
                    END as budget_utilization
                 FROM campaigns
                 WHERE deleted = 0";
        
        if ($activeOnly) {
            $query .= " AND status = 'Active'";
        }
        
        $query .= " ORDER BY name ASC LIMIT 20"; // Limit for dashboard display
        
        $result = $this->db->query($query);
        
        while ($row = $this->db->fetchByAssoc($result)) {
            $campaignList[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'status' => $row['status'],
                'budget' => (float)$row['budget'],
                'actual_cost' => (float)$row['actual_cost'],
                'budget_utilization' => round((float)$row['budget_utilization'], 2)
            ];
        }
        
        return $campaignList;
    }
}
