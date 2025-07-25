{*
/**
 * @fileoverview Enhanced Panel Component Template
 * 
 * Progressive enhancement template that demonstrates CSS custom property fallbacks
 * while maintaining 100% backward compatibility with existing panel system.
 * 
 * Features:
 * - CSS custom properties as fallback-supported enhancements
 * - Seamless fallback to existing SCSS variables
 * - Progressive enhancement approach
 * - Bootstrap 3.3.7 compatibility maintained
 * - Accessibility compliance (ARIA)
 * 
 * Usage:
 * {include file="themes/SuiteP/tpls/components/enhanced-panel.tpl" 
 *          title="Panel Title" 
 *          content="Panel content here"
 *          type="default"
 *          collapsible=true}
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{* Set default values for parameters *}
{assign var="panel_title" value=$title|default:"Panel"}
{assign var="panel_content" value=$content|default:""}
{assign var="panel_type" value=$type|default:"default"}
{assign var="panel_id" value=$id|default:"panel-`$smarty.now`"}
{assign var="is_collapsible" value=$collapsible|default:false}
{assign var="is_collapsed" value=$collapsed|default:false}
{assign var="panel_class" value=$class|default:""}
{assign var="header_actions" value=$actions|default:""}

{* Enhanced Panel with CSS Custom Property Fallbacks *}
<div class="panel panel-{$panel_type} enhanced-panel {$panel_class}" 
     id="{$panel_id}"
     style="
       background-color: var(--theme-panel-bg, {$PANEL_BG|default:'#ffffff'});
       border-color: var(--theme-panel-border, {$PANEL_BORDER|default:'#ddd'});
       box-shadow: var(--theme-panel-shadow, 0 1px 3px rgba(0,0,0,0.1));
     ">
     
    {* Enhanced Panel Header *}
    <div class="panel-heading enhanced-panel-header" 
         {if $is_collapsible}
         role="button"
         tabindex="0"
         aria-expanded="{if $is_collapsed}false{else}true{/if}"
         aria-controls="{$panel_id}-body"
         data-toggle="collapse"
         data-target="#{$panel_id}-body"
         {/if}
         style="
           background-color: var(--theme-panel-heading-bg, {$PANEL_HEADING_BG|default:'#f5f5f5'});
           color: var(--theme-panel-text, {$PANEL_TEXT_COLOR|default:'#333'});
           border-bottom-color: var(--theme-panel-inner-border, {$PANEL_INNER_BORDER|default:'#ddd'});
         ">
         
        <div class="panel-title-container">
            {* Collapsible Toggle Icon *}
            {if $is_collapsible}
                <span class="panel-toggle-icon suitepicon" 
                      aria-hidden="true"
                      style="color: var(--theme-link-color, {$LINK_COLOR|default:'#337ab7'});">
                    <span class="{if $is_collapsed}suitepicon-expand{else}suitepicon-collapse{/if}"></span>
                </span>
            {/if}
            
            {* Panel Title *}
            <h3 class="panel-title enhanced-panel-title"
                style="color: var(--theme-panel-text, {$PANEL_TEXT_COLOR|default:'#333'});">
                {$panel_title}
            </h3>
            
            {* Header Actions *}
            {if $header_actions}
                <div class="panel-actions">
                    {$header_actions}
                </div>
            {/if}
        </div>
    </div>

    {* Enhanced Panel Body *}
    <div class="panel-body enhanced-panel-body {if $is_collapsed}collapse{else}collapse in{/if}" 
         id="{$panel_id}-body"
         aria-labelledby="{$panel_id}-header"
         style="
           background-color: var(--theme-panel-bg, {$PANEL_BG|default:'#ffffff'});
           color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});
         ">
         
        {* Panel Content *}
        {$panel_content}
        
        {* Slot for additional content *}
        {if isset($panel_extra_content)}
            <div class="panel-extra-content">
                {$panel_extra_content}
            </div>
        {/if}
    </div>

    {* Enhanced Panel Footer (Optional) *}
    {if isset($footer_content)}
        <div class="panel-footer enhanced-panel-footer"
             style="
               background-color: var(--theme-panel-footer-bg, {$PANEL_FOOTER_BG|default:'#f5f5f5'});
               color: var(--theme-panel-text, {$PANEL_TEXT_COLOR|default:'#333'});
               border-top-color: var(--theme-panel-inner-border, {$PANEL_INNER_BORDER|default:'#ddd'});
             ">
            {$footer_content}
        </div>
    {/if}
</div>

{* Enhanced Panel Styles - Progressive Enhancement *}
<style>
/* Enhanced Panel Styles with CSS Custom Properties */
.enhanced-panel {
    /* Enhanced focus states using custom properties */
    transition: box-shadow 0.3s ease, border-color 0.3s ease;
}

.enhanced-panel:focus-within {
    border-color: var(--theme-focus-color, #337ab7);
    box-shadow: var(--theme-focus-shadow, 0 0 0 0.2rem rgba(51, 122, 183, 0.25));
}

/* Enhanced panel header with better interactivity */
.enhanced-panel-header[role="button"] {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.enhanced-panel-header[role="button"]:hover {
    background-color: var(--theme-surface-hover, #f8f9fa) !important;
}

.enhanced-panel-header[role="button"]:focus {
    outline: 2px solid var(--theme-focus-color, #337ab7);
    outline-offset: 2px;
}

/* Panel title container layout */
.panel-title-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}

.enhanced-panel-title {
    margin: 0;
    font-size: 1.1em;
    font-weight: 600;
    flex: 1;
}

/* Panel toggle icon */
.panel-toggle-icon {
    font-size: 1.2em;
    transition: transform 0.2s ease;
    margin-right: 0.5rem;
}

.enhanced-panel-header[aria-expanded="false"] .panel-toggle-icon {
    transform: rotate(-90deg);
}

/* Panel actions */
.panel-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Enhanced body with better spacing */
.enhanced-panel-body {
    line-height: 1.6;
}

.enhanced-panel-body.collapsing {
    transition: height 0.35s ease;
}

/* Enhanced footer with better visual separation */
.enhanced-panel-footer {
    border-top: 1px solid var(--theme-panel-inner-border, #ddd);
}

/* Theme-aware enhancements for different panel types */
.enhanced-panel.panel-primary .enhanced-panel-header {
    background-color: var(--theme-btn-primary-bg, #337ab7) !important;
    color: var(--theme-btn-primary-color, #fff) !important;
}

.enhanced-panel.panel-success .enhanced-panel-header {
    background-color: var(--theme-btn-success-bg, #5cb85c) !important;
    color: var(--theme-btn-success-color, #fff) !important;
}

.enhanced-panel.panel-warning .enhanced-panel-header {
    background-color: var(--theme-btn-warning-bg, #f0ad4e) !important;
    color: var(--theme-btn-warning-color, #fff) !important;
}

.enhanced-panel.panel-danger .enhanced-panel-header {
    background-color: var(--theme-btn-danger-bg, #d9534f) !important;
    color: var(--theme-btn-danger-color, #fff) !important;
}

.enhanced-panel.panel-info .enhanced-panel-header {
    background-color: var(--theme-btn-info-bg, #5bc0de) !important;
    color: var(--theme-btn-info-color, #fff) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .panel-title-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .panel-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .enhanced-panel-title {
        font-size: 1em;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .enhanced-panel,
    .enhanced-panel-header,
    .panel-toggle-icon,
    .enhanced-panel-body.collapsing {
        transition: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .enhanced-panel {
        border-width: 2px;
    }
    
    .enhanced-panel-header {
        border-bottom-width: 2px;
    }
}
</style>

{* JavaScript Enhancement for Collapsible Panels *}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced keyboard support for collapsible panels
    document.querySelectorAll('.enhanced-panel-header[role="button"]').forEach(function(header) {
        header.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        header.addEventListener('click', function() {
            // Update toggle icon
            const icon = this.querySelector('.panel-toggle-icon span');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            if (icon) {
                icon.className = isExpanded ? 'suitepicon-expand' : 'suitepicon-collapse';
            }
        });
    });
});
</script> 