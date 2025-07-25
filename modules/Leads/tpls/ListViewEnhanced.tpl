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
    {if isset($advancedFilterBar)}
        {$advancedFilterBar}
    {else}
        {* Fallback: Include filter bar template directly *}
        {include file='themes/SuiteP/tpls/lead-list-filter-bar.tpl'}
    {/if}
{/if}

{* Include standard column filter dialog *}
{include file='include/ListView/ListViewColumnsFilterDialog.tpl'}
<script type='text/javascript' src='{sugar_getjspath file='include/javascript/popup_helper.js'}'></script>

<script>
    {literal}
    $(document).ready(function () {
      $("ul.clickMenu").each(function (index, node) {
        $(node).sugarActionMenu();
      });

      $('.selectActionsDisabled').children().each(function (index) {
        $(this).attr('onclick', '').unbind('click');
      });

      var selectedTopValue = $("#selectCountTop").attr("value");
      if (typeof(selectedTopValue) != "undefined" && selectedTopValue != "0") {
        sugarListView.prototype.toggleSelected();
      }
      
      // Initialize enhanced filter system for leads
      if (typeof leadListFilter !== 'undefined') {
        console.log('Enhanced lead filter system initialized');
      }
    });
    {/literal}
</script>

{assign var="currentModule" value = $pageData.bean.moduleDir}
{assign var="singularModule" value = $moduleListSingular.$currentModule}
{assign var="moduleName" value = $moduleList.$currentModule}
{assign var="hideTable" value=false}

{if !isset($data) || $data|count == 0}
    {assign var="hideTable" value=true}
    <div class="list view listViewEmpty">
        {if $displayEmptyDataMesssages}
            {if strlen($query) == 0}
                {capture assign="createLink"}<a
                    href="?module={$pageData.bean.moduleDir}&action=EditView&return_module={$pageData.bean.moduleDir}&return_action=DetailView">{$APP.LBL_CREATE_BUTTON_LABEL}</a>{/capture}
                {capture assign="importLink"}<a
                    href="?module=Import&action=Step1&import_module={$pageData.bean.moduleDir}&return_module={$pageData.bean.moduleDir}&return_action=index">{$APP.LBL_IMPORT}</a>{/capture}
                {capture assign="helpLink"}<a target="_blank"
                                              href='?module=Administration&action=SupportPortal&view=documentation&version={$sugar_info.sugar_version}&edition={$sugar_info.sugar_flavor}&lang=&help_module={$currentModule}&help_action=&key='>{$APP.LBL_CLICK_HERE}</a>{/capture}
                <p class="msg">
                    {$APP.MSG_EMPTY_LIST_VIEW_NO_RESULTS|replace:"<item2>":$createLink|replace:"<item3>":$importLink}
                </p>
            {elseif $query == "-advanced_search"}
                <p class="msg emptyResults">
                    {$APP.MSG_LIST_VIEW_NO_RESULTS_CHANGE_CRITERIA}
                </p>
            {else}
                <p class="msg">
                    {capture assign="quotedQuery"}"{$query}"{/capture}
                    {$APP.MSG_LIST_VIEW_NO_RESULTS|replace:"<item1>":$quotedQuery}
                </p>
                <p class="submsg">
                    <a href="?module={$pageData.bean.moduleDir}&action=EditView&return_module={$pageData.bean.moduleDir}&return_action=DetailView">
                        {$APP.MSG_LIST_VIEW_NO_RESULTS_SUBMSG|replace:"<item1>":$quotedQuery|replace:"<item2>":$singularModule}
                    </a>
                </p>
            {/if}
        {else}
            <p class="msg">
                {$APP.LBL_NO_DATA}
            </p>
        {/if}
        {if $showFilterIcon}
            {include file='include/ListView/ListViewSearchLink.tpl'}
        {/if}
    </div>
{/if}

{$multiSelectData}

{* Enhanced List View Container with Filter Integration *}
{if $hideTable == false}
    <div id="enhanced-lead-list-container" class="enhanced-list-view">
        <table cellpadding='0' cellspacing='0' width='100%' border='0' class='list view table table-responsive'>
            <thead>
            {assign var="link_select_id" value="selectLinkTop"}
            {assign var="link_action_id" value="actionLinkTop"}
            {assign var="actionsLink" value=$actionsLinkTop}
            {assign var="selectLink" value=$selectLinkTop}
            {assign var="action_menu_location" value="top"}
            {include file='include/ListView/ListViewPagination.tpl'}
            <tr height='20'>
                {if $prerow}
                    <td width='1%' class="td_alt">
                        &nbsp;
                    </td>
                {/if}
                {if !empty($quickViewLinks)}
                    <td class='td_alt' width='1%' style="padding: 0px;">&nbsp;</td>
                {/if}
                {counter start=0 name="colCounter" print=false assign="colCounter"}
                {assign var='datahide' value="phone"}
                {foreach from=$displayColumns key=colHeader item=params}
                    {if $colCounter == '3'}{assign var='datahide' value="phone,phonelandscape"}{/if}
                    {if $colCounter == '5'}{assign var='datahide' value="phone,phonelandscape,tablet"}{/if}
                    {if $colCounter == '0'}
                        {assign var='hiddenclass' value=""}
                    {elseif $colCounter < '5'}
                        {assign var='hiddenclass' value="hidden-xs"}
                    {elseif $colCounter >= '5'}
                        {assign var='hiddenclass' value="hidden-xs hidden-sm hidden-md"}
                    {/if}
                    {if $colHeader == 'NAME' || $params.bold}
                    <th scope='col' data-toggle="true" class="{$hiddenclass}">
                    {else}<th scope='col' data-hide="{$datahide}" class="{$hiddenclass}">{/if}
                    <div style='white-space: normal;' width='100%' align='{$params.align|default:'left'}'>
                        {if $params.sortable|default:true}
                        {if $params.url_sort}
                        <a href='{$pageData.urls.orderBy}{$params.orderBy|default:$colHeader|lower}'
                           class='listViewThLinkS1'>
                            {else}
                            {if $params.orderBy|default:$colHeader|lower == $pageData.ordering.orderBy}
                            <a href='javascript:sListView.order_checks("{$pageData.ordering.sortOrder|default:ASCerror}", "{$params.orderBy|default:$colHeader|lower}" , "{$pageData.bean.moduleDir}{"2_"}{$pageData.bean.objectName|upper}{"_ORDER_BY"}")'
                               class='listViewThLinkS1'>
                                {else}
                                <a href='javascript:sListView.order_checks("ASC", "{$params.orderBy|default:$colHeader|lower}" , "{$pageData.bean.moduleDir}{"2_"}{$pageData.bean.objectName|upper}{"_ORDER_BY"}")'
                                   class='listViewThLinkS1'>
                                    {/if}
                                    {/if}
                                    {sugar_translate label=$params.label default=$colHeader module=$pageData.bean.moduleDir}
                                    &nbsp;&nbsp;
                                    {if $params.orderBy|default:$colHeader|lower == $pageData.ordering.orderBy}
                                        {if $pageData.ordering.sortOrder == 'ASC'}
                                            {capture assign="imageName"}arrow_down.{$arrowExt}{/capture}
                                            {capture assign="alt_sort"}{sugar_translate label='LBL_ALT_SORT_DESC'}{/capture}
                                            <img border='0' src="{sugar_getimagepath file="$imageName"}" alt="{$alt_sort}">
                                        {else}
                                            {capture assign="imageName"}arrow_up.{$arrowExt}{/capture}
                                            {capture assign="alt_sort"}{sugar_translate label='LBL_ALT_SORT_ASC'}{/capture}
                                            <img border='0' src="{sugar_getimagepath file="$imageName"}" alt="{$alt_sort}">
                                        {/if}
                                    {else}
                                        {capture assign="imageName"}arrow.{$arrowExt}{/capture}
                                        {capture assign="alt_sort"}{sugar_translate label='LBL_ALT_SORT'}{/capture}
                                        <img border='0' src="{sugar_getimagepath file="$imageName"}" alt="{$alt_sort}">
                                    {/if}
                                </a>
                                {else}
                                {sugar_translate label=$params.label default=$colHeader module=$pageData.bean.moduleDir}
                                {/if}
                    </div>
                    </th>
                    {counter name="colCounter"}
                {/foreach}
            </tr>
            </thead>
            <tbody id="lead-list-tbody">
            {counter start=$pageData.offsets.current print=false assign="offset" name="offset"}
            {foreach name=rowIteration from=$data key=id item=rowData}
                {counter name="offset" print=false}
                {assign var='scope_row' value=true}

                {if $smarty.foreach.rowIteration.iteration is odd}
                    {assign var="ROW_COLOR" value=$rowColor[0]}
                {else}
                    {assign var="ROW_COLOR" value=$rowColor[1]}
                {/if}
                <tr height='20' class='{$ROW_COLOR}S1' data-lead-id="{$rowData.ID}">
                    {if $prerow}
                        <td width='1%' class='{$ROW_COLOR}S1'>
                            {if !$is_admin && is_admin_for_user && $rowData.IS_ADMIN == 1}
                                <input type='checkbox' disabled="disabled" class='checkbox' value='{$rowData.ID}'>
                            {else}
                                <input onclick='sListView.check_item(this, "{$rowData.ID}")' type='checkbox' class='checkbox'
                                       name='mass[]' value='{$rowData.ID}' {if $rowData.CHECKMARK}checked{/if}>
                            {/if}
                        </td>
                    {/if}
                    {if !empty($quickViewLinks)}
                        <td class='{$ROW_COLOR}S1' width='1%' style="padding: 0px;">
                            {if $pageData.access.edit}
                                <a title='{$editLinkString}' id="edit-{$rowData.ID}" href="index.php?action=EditView&module={$pageData.bean.moduleDir}&record={$rowData.ID}&offset={$pageData.offsets.current+$smarty.foreach.rowIteration.iteration}&stamp={$pageData.stamp}&return_module={$pageData.bean.moduleDir}&return_action={$pageData.bean.action}">{sugar_getimage name="edit_inline" attr='border="0" '}</a>
                            {/if}
                        </td>
                    {/if}

                    {counter start=0 name="colCounter" print=false assign="colCounter"}
                    {foreach from=$displayColumns key=col item=params}
                        {strip}
                            <td class='{$ROW_COLOR}S1' align='{$params.align|default:'left'}' valign="top" {if $colCounter == '0'}scope='row'{/if}>
                                {if $col == 'NAME' || $params.bold}<b>{/if}
                                {if $params.link && !$params.customCode}
                                    <{$pageData.tag.$id.$col}>{$rowData.$col}</{$pageData.tag.$id.$col}>
                                {else}
                                    {if $params.customCode}
                                        {sugar_evalcolumn_old var=$params.customCode rowData=$rowData}
                                    {else}
                                        {sugar_field parentFieldArray=$rowData vardef=$params displayType=ListView field=$col}
                                    {/if}
                                {/if}
                                {if $col == 'NAME' || $params.bold}</b>{/if}
                            </td>
                        {/strip}
                        {counter name="colCounter"}
                    {/foreach}
                </tr>
            {/foreach}
            </tbody>
        </table>
        
        {* Bottom Pagination *}
        {assign var="link_select_id" value="selectLinkBottom"}
        {assign var="link_action_id" value="actionLinkBottom"}
        {assign var="actionsLink" value=$actionsLinkBottom}
        {assign var="selectLink" value=$selectLinkBottom}
        {assign var="action_menu_location" value="bottom"}
        {include file='include/ListView/ListViewPagination.tpl'}
    </div>
    
    {* Enhanced JavaScript for Filter Integration *}
    <script>
        {literal}
        // Enhanced list view functionality for Phase 2 filters
        if (typeof enhancedLeadListView === 'undefined') {
            var enhancedLeadListView = {
                currentFilters: {},
                
                // Apply filters and reload data
                applyFilters: function(filters) {
                    this.currentFilters = filters;
                    this.reloadTableData();
                },
                
                // Reload table data with current filters
                reloadTableData: function() {
                    const tbody = document.getElementById('lead-list-tbody');
                    if (!tbody) return;
                    
                    // Show loading state
                    tbody.innerHTML = '<tr><td colspan="100%" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading filtered results...</td></tr>';
                    
                    // Make API request with current filters
                    fetch('/Api/V8/leads/filtered', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(this.currentFilters)
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.updateTableRows(data.data.leads || []);
                    })
                    .catch(error => {
                        console.error('Filter error:', error);
                        tbody.innerHTML = '<tr><td colspan="100%" class="text-center text-danger">Error loading filtered results</td></tr>';
                    });
                },
                
                // Update table rows with new data
                updateTableRows: function(leads) {
                    const tbody = document.getElementById('lead-list-tbody');
                    if (!tbody) return;
                    
                    if (leads.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="100%" class="text-center text-muted">No leads found matching current filters</td></tr>';
                        return;
                    }
                    
                    let html = '';
                    leads.forEach((lead, index) => {
                        const rowClass = index % 2 === 0 ? 'oddListRowS1' : 'evenListRowS1';
                        html += `
                            <tr height="20" class="${rowClass}" data-lead-id="${lead.id}">
                                <td width="1%" class="${rowClass}">
                                    <input onclick="sListView.check_item(this, '${lead.id}')" type="checkbox" class="checkbox" name="mass[]" value="${lead.id}">
                                </td>
                                <td class="${rowClass}" align="left" valign="top" scope="row">
                                    <b><a href="index.php?module=Leads&action=DetailView&record=${lead.id}">${lead.name}</a></b>
                                </td>
                                <td class="${rowClass}" align="left" valign="top">${lead.email}</td>
                                <td class="${rowClass}" align="left" valign="top">${lead.phone}</td>
                                <td class="${rowClass}" align="left" valign="top">${lead.account_name}</td>
                                <td class="${rowClass}" align="left" valign="top">${lead.status}</td>
                            </tr>
                        `;
                    });
                    
                    tbody.innerHTML = html;
                }
            };
            
            // Listen for filter changes
            document.addEventListener('lead-filters-changed', function(event) {
                enhancedLeadListView.applyFilters(event.detail.filters);
            });
        }
        {/literal}
    </script>
{/if} 