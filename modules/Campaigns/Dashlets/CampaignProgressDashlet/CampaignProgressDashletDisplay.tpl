{*
/**
 * @fileoverview Campaign Progress Dashlet Display Template
 * 
 * Smarty template for rendering the Campaign Progress Dashboard Widget.
 * Integrates Chart.js for visualizations and Alpine.js for reactive updates.
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{* Load Chart.js and Alpine.js if not already loaded *}
{if $includeChartJs}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
{/if}
{if $includeAlpineJs}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
{/if}

{* Main dashlet container with Alpine.js component *}
<div id="campaignProgress_{$dashletId}" 
     x-data="campaignProgressWidget_{$dashletId|replace:'-':'_'}()"
     x-init="init()"
     class="campaign-progress-dashlet">
    
    {* Loading state *}
    <div x-show="isLoading" class="dashlet-loading">
        <div class="text-center p-4">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">{$dashletStrings.LBL_LOADING}</p>
        </div>
    </div>
    
    {* Error state *}
    <div x-show="hasError && !isLoading" class="alert alert-danger" x-cloak>
        <i class="fa fa-exclamation-triangle"></i> {$dashletStrings.LBL_ERROR_LOADING}
    </div>
    
    {* Main content *}
    <div x-show="!isLoading && !hasError" x-cloak>
        
        {* Empty state message *}
        <div x-show="metrics.active_campaigns === 0" class="alert alert-info">
            <h4><i class="fa fa-info-circle"></i> {$dashletStrings.LBL_NO_CAMPAIGNS|default:'No Active Campaigns'}</h4>
            <p>Create some campaigns with budget information to see metrics here.</p>
            <a href="index.php?module=Campaigns&action=EditView&return_module=Campaigns&return_action=DetailView" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Create Campaign
            </a>
        </div>
        
        {* Show content only when there's data *}
        <div x-show="metrics.active_campaigns > 0">
        
        {* Metrics Summary Cards *}
        <div class="metrics-summary row mb-3">
            {if in_array('new_leads', $selectedMetrics)}
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="metric-card bg-primary text-white p-3 rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" x-text="metrics.new_leads_count || 0"></h3>
                            <small>{$dashletStrings.LBL_NEW_LEADS}</small>
                        </div>
                        <i class="fa fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            {/if}
            
            {if in_array('budget_utilization', $selectedMetrics)}
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="metric-card bg-success text-white p-3 rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">
                                <span x-text="metrics.budget_utilization || 0"></span>%
                            </h3>
                            <small>{$dashletStrings.LBL_BUDGET_UTILIZATION}</small>
                        </div>
                        <i class="fa fa-dollar fa-2x opacity-50"></i>
                    </div>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar" 
                             role="progressbar" 
                             :style="'width: ' + (metrics.budget_utilization || 0) + '%'"
                             :aria-valuenow="metrics.budget_utilization || 0"
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="metric-card bg-info text-white p-3 rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" x-text="metrics.active_campaigns || 0"></h3>
                            <small>{$dashletStrings.LBL_ACTIVE_CAMPAIGNS}</small>
                        </div>
                        <i class="fa fa-bullhorn fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
                 {* Performance Trend Chart *}
         {if in_array('performance_trend', $selectedMetrics)}
         <div class="chart-container mb-3">
             <h5 class="chart-title">{$dashletStrings.LBL_PERFORMANCE_TREND}</h5>
             <div style="position: relative; height: 200px;">
                 <canvas id="performanceChart_{$dashletId}"></canvas>
             </div>
         </div>
         {/if}
        
        {* Campaign List with Progress *}
        {if in_array('campaign_list', $selectedMetrics)}
        <div class="campaign-list">
            <h5 class="mb-3">{$dashletStrings.LBL_CAMPAIGN_STATUS}</h5>
            <template x-for="campaign in metrics.campaign_list" :key="campaign.id">
                <div class="campaign-item mb-2 p-2 border rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <a :href="'index.php?module=Campaigns&action=DetailView&record=' + campaign.id" 
                           class="campaign-name"
                           x-text="campaign.name">
                        </a>
                        <span class="badge badge-primary" x-text="campaign.budget_utilization + '%'"></span>
                    </div>
                    <div class="progress mt-1" style="height: 10px;">
                        <div class="progress-bar"
                             :class="campaign.budget_utilization > 90 ? 'bg-danger' : (campaign.budget_utilization > 70 ? 'bg-warning' : 'bg-success')"
                             role="progressbar"
                             :style="'width: ' + campaign.budget_utilization + '%'"
                             :aria-valuenow="campaign.budget_utilization"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="!metrics.campaign_list || metrics.campaign_list.length === 0" class="text-muted text-center p-3">
                {$dashletStrings.LBL_NO_CAMPAIGNS}
            </div>
        </div>
        {/if}
        
        {* Footer with last update time *}
        <div class="dashlet-footer text-muted small mt-3">
            <span>{$dashletStrings.LBL_UPDATED}: <span x-text="lastUpdated"></span></span>
            <a href="index.php?module=Campaigns&action=index" class="float-right">
                {$dashletStrings.LBL_VIEW_ALL_CAMPAIGNS} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
        
        </div>{* End of data content wrapper *}
    </div>
</div>

{* Alpine.js Component Script *}
<script>
{literal}
function campaignProgressWidget_{/literal}{$dashletId|replace:'-':'_'}{literal}() {
    return {
        isLoading: true,
        hasError: false,
        metrics: {
            new_leads_count: 0,
            active_campaigns: 0,
            budget_utilization: 0,
            performance_data: [],
            campaign_list: []
        },
        lastUpdated: '',
        performanceChart: null,
        
        init() {
            this.loadMetrics();
            
            // Set up auto-refresh if enabled
            {/literal}{if $autoRefresh > 0}{literal}
            setInterval(() => this.loadMetrics(), {/literal}{$autoRefresh}{literal} * 60 * 1000);
            {/literal}{/if}{literal}
            
            // Set up SSE for real-time updates
            this.setupRealtimeUpdates();
        },
        
        async loadMetrics() {
            try {
                this.isLoading = true;
                this.hasError = false;
                
                // Use PHP data directly instead of API call
                this.metrics = {/literal}{$metrics|@json_encode}{literal};
                this.lastUpdated = new Date().toLocaleTimeString();
                
                // Update chart
                if (this.metrics.performance_data && this.metrics.performance_data.length > 0) {
                    this.$nextTick(() => this.updatePerformanceChart());
                }
                
                this.isLoading = false;
                
            } catch (error) {
                console.error('Error loading campaign metrics:', error);
                this.hasError = true;
                this.isLoading = false;
            }
        },
        
        updatePerformanceChart() {
            const ctx = document.getElementById('performanceChart_{/literal}{$dashletId}{literal}');
            if (!ctx) return;
            
            const chartData = {
                labels: this.metrics.performance_data.map(d => d.date),
                datasets: [{
                    label: '{/literal}{$dashletStrings.LBL_NEW_LEADS}{literal}',
                    data: this.metrics.performance_data.map(d => d.leads),
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--theme-primary'),
                    backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--theme-primary') + '20',
                    tension: 0.4,
                    fill: true
                }]
            };
            
                         const chartOptions = {
                 responsive: true,
                 maintainAspectRatio: false,
                 aspectRatio: 2,
                 plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--theme-bg-dark'),
                        titleColor: getComputedStyle(document.documentElement).getPropertyValue('--theme-text'),
                        bodyColor: getComputedStyle(document.documentElement).getPropertyValue('--theme-text'),
                        borderColor: getComputedStyle(document.documentElement).getPropertyValue('--theme-border'),
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--theme-border-subtle'),
                            drawBorder: false
                        },
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--theme-text-muted')
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--theme-border-subtle'),
                            drawBorder: false
                        },
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--theme-text-muted'),
                            precision: 0
                        }
                    }
                }
            };
            
            if (this.performanceChart) {
                this.performanceChart.data = chartData;
                this.performanceChart.update();
            } else {
                this.performanceChart = new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: chartOptions
                });
            }
        },
        
        setupRealtimeUpdates() {
            // SSE connection for real-time updates
            if (typeof EventSource !== 'undefined') {
                const eventSource = new EventSource('{/literal}{$sugar_config.site_url}{literal}/sse/campaign-updates');
                
                eventSource.addEventListener('campaign-update', (event) => {
                    const data = JSON.parse(event.data);
                    if (data.type === 'metrics_update') {
                        this.loadMetrics();
                    }
                });
                
                eventSource.onerror = (error) => {
                    console.error('SSE connection error:', error);
                    eventSource.close();
                };
            }
        }
    };
}
{/literal}
</script>

{* Custom styles for the dashlet *}
<style>
{literal}
.campaign-progress-dashlet {
    padding: 15px;
}

.metric-card {
    transition: transform 0.2s;
    cursor: pointer;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.campaign-item {
    background-color: var(--theme-bg-subtle);
    transition: background-color 0.2s;
}

.campaign-item:hover {
    background-color: var(--theme-bg-hover);
}

.campaign-name {
    color: var(--theme-primary);
    text-decoration: none;
    font-weight: 500;
}

.campaign-name:hover {
    text-decoration: underline;
}

.chart-container {
    background-color: var(--theme-bg);
    border: 1px solid var(--theme-border);
    border-radius: 5px;
    padding: 15px;
}

.dashlet-loading {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

[x-cloak] {
    display: none !important;
}
{/literal}
</style> 