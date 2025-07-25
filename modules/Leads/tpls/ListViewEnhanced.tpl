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

    {* Include Lead Table View Component - Phase 2 Feature 1 Step 1 *}
    {* Basic fallback check for JavaScript availability *}
    <noscript>
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i>
            JavaScript is required for the enhanced lead list features. Please enable JavaScript and refresh the page.
        </div>
    </noscript>
    
    {* Load required JavaScript components with error handling *}
    <script src="themes/SuiteP/js/components/lead-table-view.js" onerror="console.error('Failed to load lead-table-view.js')"></script>
    <script src="themes/SuiteP/js/components/lead-list-filter.js" onerror="console.error('Failed to load lead-list-filter.js')"></script>
    
    {* Include the interactive lead table *}
    {include file='themes/SuiteP/tpls/components/lead-table-view.tpl'}
{/if} 