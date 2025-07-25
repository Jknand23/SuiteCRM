{*
/**
 * @fileoverview Enhanced ListView Template with CSS Custom Properties
 * 
 * Progressive enhancement template for ListView that adds CSS custom property
 * fallbacks while maintaining 100% backward compatibility with existing ListView
 * functionality. This template demonstrates the gradual migration approach for
 * Phase 1, Feature 2, Step 4.
 * 
 * Features:
 * - CSS custom properties as fallback-supported enhancements
 * - Seamless fallback to existing SCSS variables
 * - Progressive enhancement approach
 * - Bootstrap 3.3.7 compatibility maintained
 * - Zero breaking changes to existing functionality
 * - Enhanced theme switching capabilities
 * - Improved table styling and accessibility
 * - Responsive design enhancements
 * 
 * Usage:
 * Replace existing ListViewGeneric.tpl include with:
 * {include file="themes/SuiteP/include/ListView/ListViewGeneric-Enhanced.tpl"}
 * 
 * Migration Path:
 * This template can gradually replace existing ListViewGeneric.tpl by:
 * 1. Testing in development environment
 * 2. Rolling out to specific modules first
 * 3. Full deployment after validation
 * 
 * Dependencies:
 * - Existing CSS custom properties layer (Dawn theme custom-properties.scss)
 * - Existing SCSS variables (preserved as fallbacks)
 * - Bootstrap 3.3.7 classes (preserved)
 * - Existing ListView includes (preserved)
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{include file='include/ListView/ListViewColumnsFilterDialog.tpl'}
<script type='text/javascript' src='{sugar_getjspath file='include/javascript/popup_helper.js'}'></script>

{* Enhanced ListView JavaScript with Theme Support *}
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
      
      // Enhanced theme switching support for ListView
      if (typeof window.themeManager !== 'undefined') {
        // Register ListView components for theme updates
        window.themeManager.registerComponent('listview', {
          selectors: [
            '.enhanced-listview', 
            '.enhanced-list-table', 
            '.enhanced-list-header',
            '.enhanced-list-row',
            '.enhanced-empty-message'
          ],
          updateCallback: function(theme) {
            // Components will automatically update via CSS custom properties
            console.log('ListView theme updated to:', theme);
          }
        });
      }
    });
    {/literal}
</script>

{{assign var="currentModule" value = $pageData.bean.moduleDir}}
{{assign var="singularModule" value = $moduleListSingular.$currentModule}}
{{assign var="moduleName" value = $moduleList.$currentModule}}
{{assign var="hideTable" value=false}}

{{if !isset($data) || $data|count == 0}}
         {{assign var="hideTable" value=true}}
     {* Enhanced Empty State with CSS Custom Properties *}
     <div class="list view listViewEmpty enhanced-empty-message"
          style="
            background-color: var(--theme-panel-bg, #ffffff);
            border-color: var(--theme-panel-border, #ddd);
            color: var(--theme-text-color, #333);
            padding: var(--theme-panel-padding, 20px);
            margin: var(--theme-panel-spacing, 15px 0);
            border-radius: var(--theme-panel-border-radius, 4px);
            text-align: center;
          ">
         {{if $displayEmptyDataMesssages}}
             {{if strlen($query) == 0}}
                                 {{capture assign="createLink"}}<a
                     href="?module={{$pageData.bean.moduleDir}}&action=EditView&return_module={{$pageData.bean.moduleDir}}&return_action=DetailView"
                     style="color: var(--theme-link-color, #778591);">{{$APP.LBL_CREATE_BUTTON_LABEL}}</a>{{/capture}}
                 {{capture assign="importLink"}}<a
                     href="?module=Import&action=Step1&import_module={{$pageData.bean.moduleDir}}&return_module={{$pageData.bean.moduleDir}}&return_action=index"
                     style="color: var(--theme-link-color, #778591);">{{$APP.LBL_IMPORT}}</a>{{/capture}}
                 {{capture assign="helpLink"}}<a target="_blank"
                                               href='?module=Administration&action=SupportPortal&view=documentation&version={{$sugar_info.sugar_version}}&edition={{$sugar_info.sugar_flavor}}&lang=&help_module={{$currentModule}}&help_action=&key='
                                               style="color: var(--theme-link-color, #778591);">{{$APP.LBL_CLICK_HERE}}</a>{{/capture}}
                 <p class="msg" style="color: var(--theme-text-color, #333);">
                     {{$APP.MSG_EMPTY_LIST_VIEW_NO_RESULTS|replace:"<item2>":$createLink|replace:"<item3>":$importLink}}
                 </p>
             {{elseif $query == "-advanced_search"}}
                                 <p class="msg emptyResults" style="color: var(--theme-text-color, #333);">
                     {{$APP.MSG_LIST_VIEW_NO_RESULTS_CHANGE_CRITERIA}}
                 </p>
             {{else}}
                 <p class="msg" style="color: var(--theme-text-color, #333);">
                     {{capture assign="quotedQuery"}}"{{$query}}"{{/capture}}
                     {{$APP.MSG_LIST_VIEW_NO_RESULTS|replace:"<item1>":$quotedQuery}}
                 </p>
                 <p class="submsg">
                     <a href="?module={{$pageData.bean.moduleDir}}&action=EditView&return_module={{$pageData.bean.moduleDir}}&return_action=DetailView"
                        style="color: var(--theme-link-color, #778591);">
                         {{$APP.MSG_LIST_VIEW_NO_RESULTS_SUBMSG|replace:"<item1>":$quotedQuery|replace:"<item2>":$singularModule}}
                     </a>
                 </p>
             {{/if}}
         {{else}}
             <p class="msg" style="color: var(--theme-text-color, #333);">
                 {{$APP.LBL_NO_DATA}}
             </p>
         {{/if}}
         {{if $showFilterIcon}}
                         {include file='include/ListView/ListViewSearchLink.tpl'}
         {{/if}}
     </div>
 {{/if}}
 
 {{$multiSelectData}}
 
 {{if $hideTable == false}}
    {* Enhanced ListView Container *}
    <div class="enhanced-listview"
         style="
           background-color: var(--theme-main-bg, #f5f5f5);
           color: var(--theme-text-color, #333);
         ">
         
        {* Enhanced List Table with CSS Custom Properties *}
        <table cellpadding='0' 
               cellspacing='0' 
               width='100%' 
               border='0' 
               class='list view table enhanced-list-table'
               style="
                 background-color: var(--theme-panel-bg, #ffffff);
                 border-color: var(--theme-panel-border, #ddd);
                 border-radius: var(--theme-table-border-radius, 4px);
                 box-shadow: var(--theme-panel-shadow, 0 1px 3px rgba(0,0,0,0.1));
               ">
            <thead class="enhanced-list-header"
                   style="
                     background-color: var(--theme-panel-heading-bg, #f5f5f5);
                     color: var(--theme-text-color, #333);
                   ">
                         {{assign var="link_select_id" value="selectLinkTop"}}
             {{assign var="link_action_id" value="actionLinkTop"}}
             {{assign var="actionsLink" value=$actionsLinkTop}}
             {{assign var="selectLink" value=$selectLinkTop}}
             {{assign var="action_menu_location" value="top"}}
            {include file='include/ListView/ListViewPagination.tpl'}
            
            {* Enhanced Table Header Row *}
            <tr height='20' class="enhanced-header-row"
                style="
                  background-color: var(--theme-panel-heading-bg, #f5f5f5);
                  border-bottom-color: var(--theme-panel-border, #ddd);
                ">
                {if $prerow}
                    <td width='1%' 
                        class="td_alt enhanced-prerow-cell"
                        style="
                          background-color: var(--theme-panel-heading-bg, #f5f5f5);
                          border-color: var(--theme-panel-border, #ddd);
                        ">
                        &nbsp;
                    </td>
                {/if}
                {if !empty($quickViewLinks)}
                    <td class='td_alt enhanced-quickview-cell' 
                        width='1%' 
                        style="
                          padding: 0px;
                          background-color: var(--theme-panel-heading-bg, #f5f5f5);
                          border-color: var(--theme-panel-border, #ddd);
                        ">&nbsp;</td>
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
                    
                    {* Enhanced Column Header *}
                    {if $colHeader == 'NAME' || $params.bold}
                    <th scope='col' 
                        data-toggle="true" 
                        class="{$hiddenclass} enhanced-header-cell enhanced-primary-column"
                        style="
                          background-color: var(--theme-panel-heading-bg, #f5f5f5);
                          color: var(--theme-text-color, #333);
                          border-color: var(--theme-panel-border, #ddd);
                          font-weight: var(--theme-header-font-weight, 600);
                          padding: var(--theme-cell-padding, 8px 12px);
                        ">
                    {else}
                    <th scope='col' 
                        data-hide="{$datahide}" 
                        class="{$hiddenclass} enhanced-header-cell"
                        style="
                          background-color: var(--theme-panel-heading-bg, #f5f5f5);
                          color: var(--theme-text-color, #333);
                          border-color: var(--theme-panel-border, #ddd);
                          font-weight: var(--theme-header-font-weight, 600);
                          padding: var(--theme-cell-padding, 8px 12px);
                        ">
                    {/if}
                    
                    <div style='white-space: normal;' width='100%' align='{$params.align|default:'left'}'>
                        {if $params.sortable|default:true}
                        {if $params.url_sort}
                        <a href='{$pageData.urls.orderBy}{$params.orderBy|default:$colHeader|lower}'
                           class='listViewThLinkS1 enhanced-sort-link'
                           style="
                             color: var(--theme-link-color, #778591);
                             text-decoration: none;
                           ">
                            {else}
                            {if $params.orderBy|default:$colHeader|lower == $pageData.ordering.orderBy}
                            <a href='javascript:sListView.order_checks("{$pageData.ordering.sortOrder|default:ASCerror}", "{$params.orderBy|default:$colHeader|lower}" , "{$pageData.bean.moduleDir}{"2_"}{$pageData.bean.objectName|upper}{"_ORDER_BY"}")'
                               class='listViewThLinkS1 enhanced-sort-link'
                               style="
                                 color: var(--theme-link-color, #778591);
                                 text-decoration: none;
                               ">
                                {else}
                                <a href='javascript:sListView.order_checks("ASC", "{$params.orderBy|default:$colHeader|lower}" , "{$pageData.bean.moduleDir}{"2_"}{$pageData.bean.objectName|upper}{"_ORDER_BY"}")'
                                   class='listViewThLinkS1 enhanced-sort-link'
                                   style="
                                     color: var(--theme-link-color, #778591);
                                     text-decoration: none;
                                   ">
                                    {/if}
                                    {/if}
                                    {sugar_translate label=$params.label module=$pageData.bean.moduleDir}
                                    &nbsp;&nbsp;
                                    {if $params.orderBy|default:$colHeader|lower == $pageData.ordering.orderBy}
                                        {if $pageData.ordering.sortOrder == 'ASC'}
                                            {capture assign="imageName"}arrow_down.{$arrowExt}{/capture}
                                            <span class="suitepicon suitepicon-action-sorting-ascending"
                                                  style="color: var(--theme-primary, #778591);"></span>
                                        {else}
                                            {capture assign="imageName"}arrow_up.{$arrowExt}{/capture}
                                            <span class="suitepicon suitepicon-action-sorting-descending"
                                                  style="color: var(--theme-primary, #778591);"></span>
                                        {/if}
                                    {else}
                                        {capture assign="imageName"}arrow.{$arrowExt}{/capture}
                                        <span class="suitepicon suitepicon-action-sorting-none"
                                              style="color: var(--theme-gray-light, #999);"></span>
                                    {/if}
                                </a>
                                {else}
                                {if !isset($params.noHeader) || $params.noHeader == false}
                                    {sugar_translate label=$params.label module=$pageData.bean.moduleDir}
                                {/if}
                                {/if}
                    </div>
                    </th>
                    {counter name="colCounter"}
                {/foreach}
                <th width='1%' 
                    class="td_alt enhanced-actions-header"
                    style="
                      background-color: var(--theme-panel-heading-bg, #f5f5f5);
                      border-color: var(--theme-panel-border, #ddd);
                    ">
                    &nbsp;
                </th>
            </tr>
            </thead>
            
            {* Enhanced Table Body *}
            <tbody class="enhanced-list-body">
            {counter start=$pageData.offsets.current print=false assign="offset" name="offset"}
            {foreach name=rowIteration from=$data key=id item=rowData}
                {counter name="offset" print=false}
                {assign var='scope_row' value=true}

                {if $smarty.foreach.rowIteration.iteration is odd}
                    {assign var='_rowColor' value=$rowColor[0]}
                {else}
                    {assign var='_rowColor' value=$rowColor[1]}
                {/if}
                
                {* Enhanced Table Row with CSS Custom Properties *}
                <tr height='20' 
                    class='{$_rowColor}S1 enhanced-list-row'
                    style="
                      background-color: var(--theme-row-bg, #ffffff);
                      border-bottom-color: var(--theme-panel-inner-border, #f0f0f0);
                      color: var(--theme-text-color, #333);
                    "
                    onmouseover="this.style.backgroundColor='var(--theme-row-hover-bg, #f5f5f5)'"
                    onmouseout="this.style.backgroundColor='var(--theme-row-bg, #ffffff)'">
                    
                    {if $prerow}
                        <td width='1%' 
                            class='nowrap enhanced-prerow-data'
                            style="
                              background-color: var(--theme-row-bg, #ffffff);
                              border-color: var(--theme-panel-inner-border, #f0f0f0);
                              padding: var(--theme-cell-padding, 8px 12px);
                            ">
                            {if !$is_admin && $is_admin_for_user && $rowData.IS_ADMIN==1}
                                <input type='checkbox' disabled="disabled" class='checkbox' value='{$rowData.ID}'>
                            {else}
                                <input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='{$rowData.ID}'>
                            {/if}
                        </td>
                    {/if}
                    
                    {if !empty($quickViewLinks)}
                        <td class='nowrap enhanced-quickview-data'
                            style="
                              background-color: var(--theme-row-bg, #ffffff);
                              border-color: var(--theme-panel-inner-border, #f0f0f0);
                              padding: var(--theme-cell-padding, 4px);
                            ">
                            {if $pageData.access.edit}
                                <a href='{$pageData.urls.edit}&record={$rowData.ID}&offset={$pageData.offsets.current+$smarty.foreach.rowIteration.iteration}&stamp={$pageData.stamp}'
                                   style="color: var(--theme-link-color, #778591);">
                                    {sugar_getimage name="edit_inline" ext=".gif" alt=$editLinkText other_attributes='border="0" '}
                                </a>
                            {/if}
                        </td>
                    {/if}
                    
                    {counter start=0 name="colCounter" print=false assign="colCounter"}
                    {foreach from=$displayColumns key=col item=params}
                        {strip}
                        {if $colCounter == '0'}
                            {assign var='hiddenclass' value=""}
                        {elseif $colCounter < '5'}
                            {assign var='hiddenclass' value="hidden-xs"}
                        {elseif $colCounter >= '5'}
                            {assign var='hiddenclass' value="hidden-xs hidden-sm hidden-md"}
                        {/if}
                        
                        {* Enhanced Data Cell *}
                        <td class='{$hiddenclass} enhanced-data-cell'
                            scope='{if $scope_row}row{else}col{/if}'
                            style="
                              background-color: var(--theme-row-bg, #ffffff);
                              border-color: var(--theme-panel-inner-border, #f0f0f0);
                              color: var(--theme-text-color, #333);
                              padding: var(--theme-cell-padding, 8px 12px);
                            ">
                            {assign var='scope_row' value=false}
                            
                            {if $params.customCode}
                                {sugar_evalcolumn_old var=$params.customCode rowData=$rowData}
                            {else}
                                {sugar_field parentFieldArray=$rowData vardef=$params displayType=ListView field=$col}
                            {/if}
                            &nbsp;
                        </td>
                        {/strip}
                        {counter name="colCounter"}
                    {/foreach}
                    
                    {* Enhanced Actions Cell *}
                    <td align='right' 
                        class="enhanced-actions-cell"
                        style="
                          background-color: var(--theme-row-bg, #ffffff);
                          border-color: var(--theme-panel-inner-border, #f0f0f0);
                          padding: var(--theme-cell-padding, 4px 8px);
                        ">
                        {if $pageData.access.edit}
                            <a href='{$pageData.urls.edit}&record={$rowData.ID}&offset={$pageData.offsets.current+$smarty.foreach.rowIteration.iteration}&stamp={$pageData.stamp}'
                               style="
                                 color: var(--theme-link-color, #778591);
                                 margin-right: 4px;
                               ">
                                {sugar_getimage name="edit" ext=".gif" alt=$editLinkText other_attributes='border="0" '}
                            </a>
                        {/if}
                        {if $pageData.access.view}
                            <a href='{$pageData.urls.view}&record={$rowData.ID}&offset={$pageData.offsets.current+$smarty.foreach.rowIteration.iteration}&stamp={$pageData.stamp}'
                               style="color: var(--theme-link-color, #778591);">
                                {sugar_getimage name="view" ext=".gif" alt=$viewLinkText other_attributes='border="0" '}
                            </a>
                        {/if}
                    </td>
                </tr>
            {/foreach}
            </tbody>
            
            {* Enhanced Table Footer *}
            <tfoot class="enhanced-list-footer"
                   style="
                     background-color: var(--theme-panel-heading-bg, #f5f5f5);
                     border-top-color: var(--theme-panel-border, #ddd);
                   ">
                {assign var="link_select_id" value="selectLinkBottom"}
                {assign var="link_action_id" value="actionLinkBottom"}
                {assign var="actionsLink" value=$actionsLinkBottom}
                {assign var="selectLink" value=$selectLinkBottom}
                {assign var="action_menu_location" value="bottom"}
                {include file='include/ListView/ListViewPagination.tpl'}
            </tfoot>
        </table>
    </div>
{/if}

{* Enhanced ListView Styling *}
<style>
.enhanced-list-table {
    margin-bottom: var(--theme-table-spacing, 20px);
}

.enhanced-list-row:hover {
    background-color: var(--theme-row-hover-bg, #f5f5f5) !important;
    transition: background-color 0.2s ease;
}

.enhanced-sort-link:hover {
    color: var(--theme-link-hover-color, #5a6c7d) !important;
    text-decoration: underline !important;
}

.enhanced-data-cell a {
    color: var(--theme-link-color, #778591);
    text-decoration: none;
}

.enhanced-data-cell a:hover {
    color: var(--theme-link-hover-color, #5a6c7d);
    text-decoration: underline;
}

@media (max-width: 768px) {
    .enhanced-list-table {
        font-size: var(--theme-mobile-font-size, 14px);
    }
    
    .enhanced-header-cell,
    .enhanced-data-cell {
        padding: var(--theme-mobile-cell-padding, 6px 8px) !important;
    }
}
</style> 