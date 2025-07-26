{*
 * @fileoverview Enhanced Lead List View Template with Advanced Filter Bar
 * 
 * Extended version of the standard list view template that includes the
 * Phase 2 advanced filter system with Alpine.js reactive components.
 * Maintains compatibility with existing SuiteCRM list view functionality
 * while adding modern filtering capabilities.
 * 
 * Key Features:
 * - Integrated advanced filter bar with Bootstrap 5 styling
 * - Alpine.js reactive components for real-time filtering
 * - Campaign, industry, and activity-based filtering
 * - Responsive design with mobile-friendly interface
 * - Backward compatibility with existing list view features
 * 
 * Dependencies:
 * - Alpine.js 3.x for reactivity
 * - Bootstrap 5 for enhanced styling
 * - lead-list-filter.js component
 * - Existing SuiteCRM list view infrastructure
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 *}

{* Include Advanced Filter Bar - Phase 2 Feature *}
{if $pageData.bean.moduleDir == 'Leads'}
    {* Include filter bar template *}
    {include file='themes/SuiteP/tpls/lead-list-filter-bar.tpl'}

    {* Load required JavaScript components with error handling *}
    <script src="themes/SuiteP/js/components/lead-table-view.js" onerror="console.error('Failed to load lead-table-view.js')"></script>
    <script src="themes/SuiteP/js/components/lead-list-filter.js" onerror="console.error('Failed to load lead-list-filter.js')"></script>
{/if}

{* Include the standard SuiteCRM list view template with actual data *}
{include file='include/ListView/ListViewGeneric.tpl'}

{* Enhanced table view for leads only (replaces standard table when Alpine.js is available) *}
{if $pageData.bean.moduleDir == 'Leads'}
    {* Basic fallback check for JavaScript availability *}
    <noscript>
        <div class="alert alert-info mt-3">
            <i class="fa fa-info-circle"></i>
            Enhanced lead list features require JavaScript. The standard list view is displayed above.
        </div>
    </noscript>
    
    {* Container for enhanced lead table that will replace standard table via JavaScript *}
    <div id="enhanced-lead-table-container" style="display: none;">
        {include file='themes/SuiteP/tpls/components/lead-table-view.tpl'}
    </div>
{/if} 