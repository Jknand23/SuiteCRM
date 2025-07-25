{*
/**
 * @fileoverview Enhanced DetailView Template with CSS Custom Properties
 * 
 * Progressive enhancement template for DetailView that adds CSS custom property
 * fallbacks while maintaining 100% backward compatibility with existing DetailView
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
 * 
 * Usage:
 * Replace existing DetailView.tpl include with:
 * {include file="themes/SuiteP/include/DetailView/DetailView-Enhanced.tpl"}
 * 
 * Migration Path:
 * This template can gradually replace existing DetailView.tpl by:
 * 1. Testing in development environment
 * 2. Rolling out to specific modules first
 * 3. Full deployment after validation
 * 
 * Dependencies:
 * - Existing CSS custom properties layer (Dawn theme custom-properties.scss)
 * - Existing SCSS variables (preserved as fallbacks)
 * - Bootstrap 3.3.7 classes (preserved)
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{{include file=$headerTpl}}
{sugar_include include=$includes}

{* Enhanced Detail View Container with CSS Custom Properties *}
<div id="{{$module}}_detailview_tabs"
     {{if $useTabs}}
     class="yui-navset detailview_tabs enhanced-detailview"
     {{else}}
     class="enhanced-detailview"
     {{/if}}
     style="
       background-color: var(--theme-main-bg, #f5f5f5);
       color: var(--theme-text-color, #333);
     ">
     
    {{if $useTabs}}
    {* Enhanced Tab Headers with CSS Custom Properties *}
    {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
    <ul class="yui-nav enhanced-tabs" 
        style="
          background-color: var(--theme-panel-bg, #ffffff);
          border-bottom-color: var(--theme-panel-border, #ddd);
        ">
    {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {* override from tab definitions *}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
            {{counter name="tabCount" print=false}}
            <li style="border-color: var(--theme-panel-border, #ddd);">
                <a id="tab{{$tabCount}}" 
                   href="javascript:void(0)"
                   style="
                     color: var(--theme-text-color, #333);
                     background-color: var(--theme-panel-bg, #ffffff);
                     border-color: var(--theme-panel-border, #ddd);
                   ">
                   <em style="color: var(--theme-text-color, #333);">
                     {sugar_translate label='{{$label}}' module='{{$module}}'}
                   </em>
                </a>
            </li>
        {{/if}}
    {{/foreach}}
    </ul>
    {{/if}}
    
    {* Enhanced Tab Content Container *}
    <div {{if $useTabs}}class="yui-content enhanced-tab-content"{{/if}}
         style="
           background-color: var(--theme-main-bg, #f5f5f5);
           color: var(--theme-text-color, #333);
         ">

{{* Loop through all top level panels first *}}
{{counter name="panelCount" print=false start=0 assign="panelCount"}}
{{counter name="tabCount" start=-1 print=false assign="tabCount"}}
{{foreach name=section from=$sectionPanels key=label item=panel}}
{{assign var='panel_id' value=$panelCount}}
{{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
  {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
    {{counter name="tabCount" print=false}}
    {{if $tabCount != 0}}</div>{{/if}}
    <div id='tabcontent{{$tabCount}}' class="enhanced-tab-panel"
         style="
           background-color: var(--theme-main-bg, #f5f5f5);
           padding: var(--theme-panel-padding, 15px);
         ">
  {{/if}}

    {{if ( isset($tabDefs[$label_upper].panelDefault) && $tabDefs[$label_upper].panelDefault == "collapsed" && isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false) }}
        {{assign var='panelState' value=$tabDefs[$label_upper].panelDefault}}
    {{else}}
        {{assign var='panelState' value="expanded"}}
    {{/if}}

{* Enhanced Detail Panel with CSS Custom Properties *}
<div id='detailpanel_{{$smarty.foreach.section.iteration}}' 
     class='detail view detail508 {{$panelState}} enhanced-detail-panel'
     style="
       background-color: var(--theme-panel-bg, #ffffff);
       border-color: var(--theme-panel-border, #ddd);
       box-shadow: var(--theme-panel-shadow, 0 1px 3px rgba(0,0,0,0.1));
       margin-bottom: var(--theme-panel-spacing, 20px);
       border-radius: var(--theme-panel-border-radius, 4px);
     ">

{counter name="panelFieldCount" start=0 print=false assign="panelFieldCount"}
{{* Print out the panel title if one exists*}}

{{* Check to see if the panel variable is an array, if not, we'll attempt an include with type param php *}}
{{* See function.sugar_include.php *}}
{{if !is_array($panel)}}
    {sugar_include type='php' file='{{$panel}}'}
{{else}}

    {{if !empty($label) && !is_int($label) && $label != 'DEFAULT' && (!isset($tabDefs[$label_upper].newTab) || (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false))}}
    {* Enhanced Panel Header with CSS Custom Properties *}
    <h4 class="enhanced-panel-header"
        style="
          background-color: var(--theme-panel-heading-bg, #f5f5f5);
          color: var(--theme-text-color, #333);
          border-bottom-color: var(--theme-panel-border, #ddd);
          padding: var(--theme-panel-header-padding, 10px 15px);
          margin: 0;
          border-radius: var(--theme-panel-border-radius, 4px) var(--theme-panel-border-radius, 4px) 0 0;
        ">
      <a href="javascript:void(0)" 
         class="collapseLink enhanced-collapse-link" 
         onclick="collapsePanel({{$smarty.foreach.section.iteration}});"
         style="
           color: var(--theme-link-color, #778591);
           text-decoration: none;
           margin-right: 8px;
         ">
        <img border="0" 
             id="detailpanel_{{$smarty.foreach.section.iteration}}_img_hide" 
             src="{sugar_getimagepath file="basic_search.gif"}"
             style="opacity: var(--theme-icon-opacity, 0.7);">
      </a>
      <a href="javascript:void(0)" 
         class="expandLink enhanced-expand-link" 
         onclick="expandPanel({{$smarty.foreach.section.iteration}});"
         style="
           color: var(--theme-link-color, #778591);
           text-decoration: none;
           margin-right: 8px;
         ">
        <img border="0" 
             id="detailpanel_{{$smarty.foreach.section.iteration}}_img_show" 
             src="{sugar_getimagepath file="advanced_search.gif"}"
             style="opacity: var(--theme-icon-opacity, 0.7);">
      </a>
      <span style="color: var(--theme-text-color, #333);">
        {sugar_translate label='{{$label}}' module='{{$module}}'}
      </span>
      
    {{if isset($panelState) && $panelState == 'collapsed'}}
    <script>
      document.getElementById('detailpanel_{{$smarty.foreach.section.iteration}}').className += ' collapsed';
    </script>
    {{else}}
    <script>
      document.getElementById('detailpanel_{{$smarty.foreach.section.iteration}}').className += ' expanded';
    </script>
    {{/if}}
    </h4>
    {{/if}}

    {* Enhanced Panel Container with CSS Custom Properties *}
    <table id='{{$label}}' 
           class="panelContainer enhanced-panel-table" 
           cellspacing='{$gridline}'
           style="
             background-color: var(--theme-panel-bg, #ffffff);
             border-color: var(--theme-panel-inner-border, #ddd);
             width: 100%;
           ">

    {{foreach name=rowIteration from=$panel key=row item=rowData}}
    {counter name="fieldsUsed" start=0 print=false assign="fieldsUsed"}
    {counter name="fieldsHidden" start=0 print=false assign="fieldsHidden"}
    {capture name="tr" assign="tableRow"}
    
    {* Enhanced Table Row with CSS Custom Properties *}
    <tr class="enhanced-detail-row"
        style="
          background-color: var(--theme-panel-bg, #ffffff);
          border-bottom-color: var(--theme-panel-inner-border, #f0f0f0);
        ">

        {{assign var="columnsInRow" value=$rowData|@count}}
        {{assign var="columnsUsed" value=0}}
        {{foreach name=colIteration from=$rowData key=col item=colData}}

        {{if isset($colData.field) && !empty($colData.field)}}
        {counter name="fieldsUsed"}
        {counter name="columnsUsed"}
        {{assign var="label" value=$colData.field.label}}
        {{assign var="labelValue" value=$colData.field.labelValue}}
        {{assign var="field" value=$colData.field}}

        {* Enhanced Field Label Cell with CSS Custom Properties *}
        <td class="enhanced-field-label" 
            scope="row" 
            valign="top" 
            style="
              color: var(--theme-text-color, #333);
              background-color: var(--theme-panel-bg, #ffffff);
              border-right-color: var(--theme-panel-inner-border, #f0f0f0);
              padding: var(--theme-field-padding, 8px 12px);
              font-weight: var(--theme-label-font-weight, 600);
              width: {{$colData.field.labelSpan}}%;
            ">
            {{if isset($colData.field.customLabel)}}
                {{$colData.field.customLabel}}:
            {{elseif isset($colData.field.label)}}
                {capture name="label" assign="label"}
                    {sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}
                {/capture}
                {$label}:
            {{/if}}
        </td>

        {* Enhanced Field Value Cell with CSS Custom Properties *}
        <td class="enhanced-field-value" 
            valign="top" 
            style="
              color: var(--theme-text-color, #333);
              background-color: var(--theme-panel-bg, #ffffff);
              border-right-color: var(--theme-panel-inner-border, #f0f0f0);
              padding: var(--theme-field-padding, 8px 12px);
              width: {{$colData.field.fieldSpan}}%;
            ">
            {{if $colData.field.customCode}}
                {{$colData.field.customCode}}
            {{elseif $fields[$colData.field.name]}}
                {{$fields[$colData.field.name]}}
            {{/if}}
        </td>
        {{/if}}
        {{/foreach}}

        {{* Fill remaining columns if needed *}}
        {{math assign="remainingCols" equation="x-y" x=2 y=$columnsUsed}}
        {{section name="fillCols" start=0 loop=$remainingCols step=1}}
        <td class="enhanced-empty-cell" 
            style="
              background-color: var(--theme-panel-bg, #ffffff);
              border-right-color: var(--theme-panel-inner-border, #f0f0f0);
            ">
            &nbsp;
        </td>
        {{/section}}
    </tr>
    {/capture}
    {{$tableRow}}
    {{/foreach}}
    </table>
{{/if}}
</div>
{{counter name="panelCount"}}
{{/foreach}}
{{if $useTabs && $tabCount >= 0}}</div>{{/if}}
    </div>
</div>

{* Enhanced DetailView JavaScript with Theme Support *}
<script type="text/javascript">
{literal}
// Enhanced panel collapse/expand with CSS custom property support
function collapsePanel(index) {
    var panel = document.getElementById('detailpanel_' + index);
    var hideImg = document.getElementById('detailpanel_' + index + '_img_hide');
    var showImg = document.getElementById('detailpanel_' + index + '_img_show');
    
    if (panel) {
        panel.className = panel.className.replace(/\s*expanded\s*/, ' ').replace(/\s*collapsed\s*/, ' ') + ' collapsed';
        // Apply enhanced collapsed styles with CSS custom properties
        panel.style.opacity = 'var(--theme-collapsed-opacity, 0.7)';
    }
    
    if (hideImg) hideImg.style.display = 'none';
    if (showImg) showImg.style.display = 'inline';
}

function expandPanel(index) {
    var panel = document.getElementById('detailpanel_' + index);
    var hideImg = document.getElementById('detailpanel_' + index + '_img_hide');
    var showImg = document.getElementById('detailpanel_' + index + '_img_show');
    
    if (panel) {
        panel.className = panel.className.replace(/\s*collapsed\s*/, ' ').replace(/\s*expanded\s*/, ' ') + ' expanded';
        // Remove enhanced collapsed styles
        panel.style.opacity = '';
    }
    
    if (hideImg) hideImg.style.display = 'inline';
    if (showImg) showImg.style.display = 'none';
}

// Enhanced theme switching support for DetailView
if (typeof window.themeManager !== 'undefined') {
    // Register DetailView panels for theme updates
    window.themeManager.registerComponent('detailview', {
        selectors: ['.enhanced-detailview', '.enhanced-detail-panel', '.enhanced-panel-header'],
        updateCallback: function(theme) {
            // Panels will automatically update via CSS custom properties
            console.log('DetailView theme updated to:', theme);
        }
    });
}
{/literal}
</script> 