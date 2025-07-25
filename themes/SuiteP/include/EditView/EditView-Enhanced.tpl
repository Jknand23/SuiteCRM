{*
/**
 * @fileoverview Enhanced EditView Template with CSS Custom Properties
 * 
 * Progressive enhancement template for EditView that adds CSS custom property
 * fallbacks while maintaining 100% backward compatibility with existing EditView
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
 * - Improved form styling and accessibility
 * 
 * Usage:
 * Replace existing EditView.tpl include with:
 * {include file="themes/SuiteP/include/EditView/EditView-Enhanced.tpl"}
 * 
 * Migration Path:
 * This template can gradually replace existing EditView.tpl by:
 * 1. Testing in development environment
 * 2. Rolling out to specific modules first
 * 3. Full deployment after validation
 * 
 * Dependencies:
 * - Existing CSS custom properties layer (Dawn theme custom-properties.scss)
 * - Existing SCSS variables (preserved as fallbacks)
 * - Bootstrap 3.3.7 classes (preserved)
 * - Existing tab_panel_content.tpl (preserved)
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{{sugar_include type="smarty" file=$headerTpl}}
{sugar_include include=$includes}

{* Enhanced EditView Container with CSS Custom Properties *}
<div id="EditView_tabs" 
     class="enhanced-editview"
     style="
       background-color: var(--theme-main-bg, #f5f5f5);
       color: var(--theme-text-color, #333);
     ">
     
    {* Enhanced Tab Navigation with CSS Custom Properties *}
    {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
    <ul class="nav nav-tabs enhanced-edit-tabs" 
        style="
          background-color: var(--theme-panel-bg, #ffffff);
          border-bottom-color: var(--theme-panel-border, #ddd);
          margin-bottom: var(--theme-tab-spacing, 0);
        ">
        {{if $useTabs}}
        {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {* if tab *}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
        {*if tab display*}
        {{counter name="tabCount" print=false}}
        {{if $tabCount == '0'}}
        <li role="presentation" class="active enhanced-tab-item">
            <a id="tab{{$tabCount}}" 
               data-toggle="tab" 
               class="hidden-xs enhanced-tab-link"
               style="
                 color: var(--theme-text-color, #333);
                 background-color: var(--theme-panel-bg, #ffffff);
                 border-color: var(--theme-panel-border, #ddd);
               ">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
            
            {* Count Tabs for Mobile View *}
            {{counter name="tabCountOnlyXS" start=-1 print=false assign="tabCountOnlyXS"}}
            {{foreach name=sectionOnlyXS from=$sectionPanels key=labelOnly item=panelOnlyXS}}
            {{capture name=label_upper_count_only assign=label_upper_count_only}}{{$labelOnly|upper}}{{/capture}}
            {{if (isset($tabDefs[$label_upper_count_only].newTab) && $tabDefs[$label_upper_count_only].newTab == true)}}
                {{counter name="tabCountOnlyXS" print=false}}
            {{/if}}
            {{/foreach}}

            {* Enhanced Mobile Tab Dropdown *}
            <a id="xstab{{$tabCount}}" 
               href="#" 
               class="visible-xs first-tab{{if $tabCountOnlyXS > 0}}-xs{{/if}} dropdown-toggle enhanced-mobile-tab" 
               data-toggle="dropdown"
               style="
                 color: var(--theme-text-color, #333);
                 background-color: var(--theme-panel-bg, #ffffff);
                 border-color: var(--theme-panel-border, #ddd);
               ">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
            
            {{if $tabCountOnlyXS > 0}}
            <ul id="first-tab-menu-xs" 
                class="dropdown-menu enhanced-mobile-dropdown"
                style="
                  background-color: var(--theme-panel-bg, #ffffff);
                  border-color: var(--theme-panel-border, #ddd);
                  box-shadow: var(--theme-dropdown-shadow, 0 6px 12px rgba(0,0,0,.175));
                ">
                {{counter name="tabCountXS" start=0 print=false assign="tabCountXS"}}
                {{foreach name=sectionXS from=$sectionPanels key=label item=panelXS}}
                {{capture name=label_upper_xs assign=label_upper_xs}}{{$label|upper}}{{/capture}}
                {{if (isset($tabDefs[$label_upper_xs].newTab) && $tabDefs[$label_upper_xs].newTab == true)}}
                <li role="presentation" class="enhanced-dropdown-item">
                    <a id="tab{{$tabCountXS}}" 
                       data-toggle="tab" 
                       onclick="changeFirstTab(this, 'tab-content-{{$tabCountXS}}');"
                       class="enhanced-dropdown-link"
                       style="
                         color: var(--theme-text-color, #333);
                         background-color: var(--theme-panel-bg, #ffffff);
                       ">
                        {sugar_translate label='{{$label}}' module='{{$module}}'}
                    </a>
                </li>
                {{counter name="tabCountXS" print=false}}
                {{/if}}
                {{/foreach}}
            </ul>
            {{/if}}
        </li>
        {{else}}
        <li role="presentation" class="hidden-xs enhanced-tab-item">
            <a id="tab{{$tabCount}}" 
               data-toggle="tab"
               class="enhanced-tab-link"
               style="
                 color: var(--theme-text-color, #333);
                 background-color: var(--theme-panel-bg, #ffffff);
                 border-color: var(--theme-panel-border, #ddd);
               ">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
        </li>
        {{/if}}
        {{else}}
        {* if panel skip*}
        {{/if}}
        {{/foreach}}
        {{/if}}
    </ul>

    <div class="clearfix"></div>
    
    {* Enhanced Tab Content Container *}
    {{if $useTabs}}
    <div class="tab-content enhanced-tab-content"
         style="
           background-color: var(--theme-main-bg, #f5f5f5);
           border-color: var(--theme-panel-border, #ddd);
           padding: var(--theme-content-padding, 15px);
         ">
    {{else}}
    <div class="tab-content enhanced-tab-content" 
         style="
           padding: 0; 
           border: 0;
           background-color: var(--theme-main-bg, #f5f5f5);
         ">
    {{/if}}
    
        {{counter name="tabCount" start=0 print=false assign="tabCount"}}
        {* Loop through all top level panels first *}
        {{if $useTabs}}
        {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {{if isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true}}
        {{if $tabCount == '0'}}
        <div class="tab-pane-NOBOOTSTRAPTOGGLER active fade in enhanced-tab-pane" 
             id='tab-content-{{$tabCount}}'
             style="
               background-color: var(--theme-main-bg, #f5f5f5);
               color: var(--theme-text-color, #333);
             ">
            {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
        </div>
        {{else}}
        <div class="tab-pane-NOBOOTSTRAPTOGGLER fade enhanced-tab-pane" 
             id='tab-content-{{$tabCount}}'
             style="
               background-color: var(--theme-main-bg, #f5f5f5);
               color: var(--theme-text-color, #333);
             ">
            {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
        </div>
        {{/if}}
         {{counter name="tabCount" print=false}}
        {{/if}}
        {{/foreach}}
        {{else}}
        <div class="tab-pane panel-collapse enhanced-tab-pane">&nbsp;</div>
        {{/if}}
    </div>
    
    {* Enhanced Panel Content Container *}
    <div class="panel-content enhanced-panel-content"
         style="
           background-color: var(--theme-main-bg, #f5f5f5);
           color: var(--theme-text-color, #333);
         ">
        <div>&nbsp;</div>
        {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
        {{counter name="panelCount" start=-1 print=false assign="panelCount"}}
        {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {* if tab *}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true && $useTabs)}}
        {{counter name="tabCount" print=false}}
        {*if tab skip*}
        {{else}}
        {* if panel display*}
        {*if panel collapsed*}
        {{if (isset($tabDefs[$label_upper].panelDefault) && $tabDefs[$label_upper].panelDefault == "collapsed") }}
        {*collapse panel*}
        {{assign var='collapse' value="panel-collapse collapse"}}
        {{assign var='collapsed' value="collapsed"}}
        {{assign var='collapseIcon' value="glyphicon glyphicon-plus"}}
        {{assign var='panelHeadingCollapse' value="panel-heading-collapse"}}
        {{else}}
        {*expand panel*}
        {{assign var='collapse' value="panel-collapse collapse in"}}
        {{assign var='collapseIcon' value="glyphicon glyphicon-minus"}}
        {{assign var='panelHeadingCollapse' value=""}}
        {{/if}}

        {* Enhanced Panel with CSS Custom Properties *}
        {{if $useTabs}}
             {{if $tabCount == 0}}
                 <div class="panel panel-default tab-panel-{{$tabCount}} enhanced-edit-panel" 
                      style="
                        display: block;
                        background-color: var(--theme-panel-bg, #ffffff);
                        border-color: var(--theme-panel-border, #ddd);
                        box-shadow: var(--theme-panel-shadow, 0 1px 3px rgba(0,0,0,0.1));
                        margin-bottom: var(--theme-panel-spacing, 20px);
                        border-radius: var(--theme-panel-border-radius, 4px);
                      ">
             {{else}}
                 <div class="panel panel-default tab-panel-{{$tabCount}} enhanced-edit-panel" 
                      style="
                        display: none;
                        background-color: var(--theme-panel-bg, #ffffff);
                        border-color: var(--theme-panel-border, #ddd);
                        box-shadow: var(--theme-panel-shadow, 0 1px 3px rgba(0,0,0,0.1));
                        margin-bottom: var(--theme-panel-spacing, 20px);
                        border-radius: var(--theme-panel-border-radius, 4px);
                      ">
             {{/if}}
        {{else}}
          <div class="panel panel-default enhanced-edit-panel"
               style="
                 background-color: var(--theme-panel-bg, #ffffff);
                 border-color: var(--theme-panel-border, #ddd);
                 box-shadow: var(--theme-panel-shadow, 0 1px 3px rgba(0,0,0,0.1));
                 margin-bottom: var(--theme-panel-spacing, 20px);
                 border-radius: var(--theme-panel-border-radius, 4px);
               ">
        {{/if}}
        
            {* Enhanced Panel Header *}
            <div class="panel-heading {{$panelHeadingCollapse}} enhanced-panel-header"
                 style="
                   background-color: var(--theme-panel-heading-bg, #f5f5f5);
                   color: var(--theme-text-color, #333);
                   border-bottom-color: var(--theme-panel-border, #ddd);
                   border-radius: var(--theme-panel-border-radius, 4px) var(--theme-panel-border-radius, 4px) 0 0;
                 ">
                <a class="{{$collapsed}} enhanced-panel-toggle" 
                   role="button" 
                   data-toggle="collapse-edit" 
                   aria-expanded="false"
                   style="
                     color: var(--theme-text-color, #333);
                     text-decoration: none;
                     display: block;
                   ">
                    <div class="col-xs-10 col-sm-11 col-md-11"
                         style="color: var(--theme-text-color, #333);">
                        {sugar_translate label='{{$label}}' module='{{$module}}'}
                    </div>
                </a>
            </div>
            
            {* Enhanced Panel Body *}
            <div class="panel-body {{$collapse}} panelContainer enhanced-panel-body" 
                 id="detailpanel_{{$panelCount}}" 
                 data-id="{{$label_upper}}"
                 style="
                   background-color: var(--theme-panel-bg, #ffffff);
                   border-color: var(--theme-panel-inner-border, #ddd);
                   color: var(--theme-text-color, #333);
                   padding: var(--theme-panel-body-padding, 15px);
                 ">
                <div class="tab-content enhanced-form-content"
                     style="
                       background-color: var(--theme-panel-bg, #ffffff);
                       color: var(--theme-text-color, #333);
                     ">
                    {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
                </div>
            </div>
        </div>

        {{/if}}
        {{counter name="panelCount" print=false}}
        {{/foreach}}
    </div>
</div>

{* Enhanced EditView JavaScript with Theme Support *}
<script type="text/javascript">
{literal}
// Enhanced tab switching functionality with CSS custom property support
function changeFirstTab(element, contentId) {
    // Update visual states with CSS custom properties
    var tabs = document.querySelectorAll('.enhanced-tab-link, .enhanced-mobile-tab');
    tabs.forEach(function(tab) {
        tab.style.backgroundColor = 'var(--theme-panel-bg, #ffffff)';
        tab.style.color = 'var(--theme-text-color, #333)';
    });
    
    // Highlight active tab
    element.style.backgroundColor = 'var(--theme-primary, #778591)';
    element.style.color = 'var(--theme-btn-primary-color, #ffffff)';
    
    // Show/hide content panels
    var contents = document.querySelectorAll('.enhanced-tab-pane');
    contents.forEach(function(content) {
        content.style.display = 'none';
    });
    
    var activeContent = document.getElementById(contentId);
    if (activeContent) {
        activeContent.style.display = 'block';
    }
}

// Enhanced panel collapse/expand functionality
function enhancedTogglePanel(panelId) {
    var panel = document.getElementById(panelId);
    if (!panel) return;
    
    var isCollapsed = panel.classList.contains('collapsed');
    var collapseTarget = panel.querySelector('.panel-collapse');
    
    if (isCollapsed) {
        // Expand panel
        panel.classList.remove('collapsed');
        if (collapseTarget) {
            collapseTarget.classList.add('in');
            collapseTarget.style.backgroundColor = 'var(--theme-panel-bg, #ffffff)';
        }
    } else {
        // Collapse panel
        panel.classList.add('collapsed');
        if (collapseTarget) {
            collapseTarget.classList.remove('in');
            collapseTarget.style.opacity = 'var(--theme-collapsed-opacity, 0.7)';
        }
    }
}

// Enhanced theme switching support for EditView
if (typeof window.themeManager !== 'undefined') {
    // Register EditView components for theme updates
    window.themeManager.registerComponent('editview', {
        selectors: [
            '.enhanced-editview', 
            '.enhanced-edit-panel', 
            '.enhanced-panel-header',
            '.enhanced-edit-tabs',
            '.enhanced-tab-content',
            '.enhanced-form-content'
        ],
        updateCallback: function(theme) {
            // Components will automatically update via CSS custom properties
            console.log('EditView theme updated to:', theme);
            
            // Update form elements to match new theme
            var formElements = document.querySelectorAll('.enhanced-editview input, .enhanced-editview select, .enhanced-editview textarea');
            formElements.forEach(function(element) {
                element.style.borderColor = 'var(--theme-input-border, #ddd)';
                element.style.backgroundColor = 'var(--theme-input-bg, #ffffff)';
                element.style.color = 'var(--theme-text-color, #333)';
            });
            
            // Update links to match new theme
            var linkElements = document.querySelectorAll('.enhanced-editview a, .enhanced-tab-link, .enhanced-panel-toggle');
            linkElements.forEach(function(element) {
                element.style.color = 'var(--theme-link-color, #778591)';
            });
        }
    });
}

// Enhanced form validation with theme support
function enhancedValidateForm() {
    var form = document.querySelector('.enhanced-editview form');
    if (!form) return true;
    
    var errors = [];
    var requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(function(field) {
        if (!field.value.trim()) {
            errors.push(field);
            // Apply error styling with CSS custom properties
            field.style.borderColor = 'var(--theme-danger, #f08377)';
            field.style.backgroundColor = 'var(--theme-error-bg, #fff5f5)';
        } else {
            // Remove error styling
            field.style.borderColor = 'var(--theme-input-border, #ddd)';
            field.style.backgroundColor = 'var(--theme-input-bg, #ffffff)';
        }
    });
    
    return errors.length === 0;
}
{/literal}
</script>

{{sugar_include type='smarty' file=$footerTpl}}

{{if $useTabs}}
{sugar_getscript file="cache/include/javascript/sugar_grp_yui_widgets.js"}
<script type="text/javascript">
{literal}
// Enhanced tab functionality with theme awareness
if (typeof sugarTabs !== 'undefined') {
    sugarTabs.enhancedThemeSupport = true;
}
{/literal}
</script>
{{/if}} 