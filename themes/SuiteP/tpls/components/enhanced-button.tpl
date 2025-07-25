{*
/**
 * @fileoverview Enhanced Button Component Template
 * 
 * Progressive enhancement template for buttons that uses CSS custom property fallbacks
 * while maintaining 100% backward compatibility with existing Bootstrap button system.
 * 
 * Features:
 * - CSS custom properties as fallback-supported enhancements
 * - Seamless fallback to existing Bootstrap button classes
 * - Progressive enhancement approach
 * - Bootstrap 3.3.7 compatibility maintained
 * - Accessibility compliance (ARIA, focus states)
 * - Loading states and disabled states
 * 
 * Usage:
 * {include file="themes/SuiteP/tpls/components/enhanced-button.tpl" 
 *          text="Button Text" 
 *          type="primary"
 *          size="default"
 *          icon="save"
 *          onclick="handleClick()"}
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{* Set default values for parameters *}
{assign var="btn_text" value=$text|default:"Button"}
{assign var="btn_type" value=$type|default:"default"}
{assign var="btn_size" value=$size|default:"default"}
{assign var="btn_id" value=$id|default:"btn-`$smarty.now`"}
{assign var="btn_icon" value=$icon|default:""}
{assign var="btn_class" value=$class|default:""}
{assign var="btn_onclick" value=$onclick|default:""}
{assign var="is_disabled" value=$disabled|default:false}
{assign var="is_loading" value=$loading|default:false}
{assign var="btn_href" value=$href|default:""}
{assign var="btn_target" value=$target|default:""}
{assign var="btn_title" value=$title|default:""}
{assign var="btn_form" value=$form|default:""}
{assign var="btn_submit_type" value=$submit_type|default:"button"}

{* Determine button element type *}
{if $btn_href}
    {assign var="element_type" value="a"}
{else}
    {assign var="element_type" value="button"}
{/if}

{* Build size class *}
{assign var="size_class" value=""}
{if $btn_size == "large" || $btn_size == "lg"}
    {assign var="size_class" value="btn-lg"}
{elseif $btn_size == "small" || $btn_size == "sm"}
    {assign var="size_class" value="btn-sm"}
{elseif $btn_size == "extra-small" || $btn_size == "xs"}
    {assign var="size_class" value="btn-xs"}
{/if}

{* Enhanced Button with CSS Custom Property Fallbacks *}
<{$element_type} 
    {if $element_type == "button"}
        type="{$btn_submit_type}"
        {if $btn_form}form="{$btn_form}"{/if}
        {if $is_disabled}disabled="disabled"{/if}
    {else}
        href="{$btn_href}"
        {if $btn_target}target="{$btn_target}"{/if}
        {if $is_disabled}aria-disabled="true" tabindex="-1"{/if}
    {/if}
    
    id="{$btn_id}"
    class="btn btn-{$btn_type} {$size_class} enhanced-btn {$btn_class} {if $is_loading}btn-loading{/if} {if $is_disabled}disabled{/if}"
    
    {if $btn_onclick}onclick="{$btn_onclick}"{/if}
    {if $btn_title}title="{$btn_title}"{/if}
    
    style="
        {if $btn_type == 'primary'}
            background-color: var(--theme-btn-primary-bg, {$BTN_PRIMARY_BG|default:'#337ab7'});
            border-color: var(--theme-btn-primary-border, {$BTN_PRIMARY_BORDER|default:'#2e6da4'});
            color: var(--theme-btn-primary-color, {$BTN_PRIMARY_COLOR|default:'#fff'});
        {elseif $btn_type == 'success'}
            background-color: var(--theme-btn-success-bg, {$BTN_SUCCESS_BG|default:'#5cb85c'});
            border-color: var(--theme-btn-success-border, {$BTN_SUCCESS_BORDER|default:'#4cae4c'});
            color: var(--theme-btn-success-color, {$BTN_SUCCESS_COLOR|default:'#fff'});
        {elseif $btn_type == 'warning'}
            background-color: var(--theme-btn-warning-bg, {$BTN_WARNING_BG|default:'#f0ad4e'});
            border-color: var(--theme-btn-warning-border, {$BTN_WARNING_BORDER|default:'#eea236'});
            color: var(--theme-btn-warning-color, {$BTN_WARNING_COLOR|default:'#fff'});
        {elseif $btn_type == 'danger'}
            background-color: var(--theme-btn-danger-bg, {$BTN_DANGER_BG|default:'#d9534f'});
            border-color: var(--theme-btn-danger-border, {$BTN_DANGER_BORDER|default:'#d43f3a'});
            color: var(--theme-btn-danger-color, {$BTN_DANGER_COLOR|default:'#fff'});
        {elseif $btn_type == 'info'}
            background-color: var(--theme-btn-info-bg, {$BTN_INFO_BG|default:'#5bc0de'});
            border-color: var(--theme-btn-info-border, {$BTN_INFO_BORDER|default:'#46b8da'});
            color: var(--theme-btn-info-color, {$BTN_INFO_COLOR|default:'#fff'});
        {else}
            background-color: var(--theme-btn-default-bg, {$BTN_DEFAULT_BG|default:'#fff'});
            border-color: var(--theme-btn-default-border, {$BTN_DEFAULT_BORDER|default:'#ccc'});
            color: var(--theme-btn-default-color, {$BTN_DEFAULT_COLOR|default:'#333'});
        {/if}
    "
    
    {if $is_loading}
        aria-busy="true"
        aria-live="polite"
    {/if}>
    
    {* Button Content Container *}
    <span class="btn-content">
        {* Loading Spinner *}
        {if $is_loading}
            <span class="btn-spinner" aria-hidden="true">
                <span class="spinner-border spinner-border-sm" role="status"></span>
            </span>
            <span class="sr-only">Loading...</span>
        {/if}
        
        {* Button Icon *}
        {if $btn_icon && !$is_loading}
            <span class="btn-icon suitepicon suitepicon-{$btn_icon}" 
                  aria-hidden="true"
                  style="color: inherit;"></span>
        {/if}
        
        {* Button Text *}
        <span class="btn-text">{$btn_text}</span>
    </span>
</{$element_type}>

{* Enhanced Button Styles - Progressive Enhancement *}
<style>
/* Enhanced Button Styles with CSS Custom Properties */
.enhanced-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease-in-out;
    border-radius: var(--theme-border-radius, 4px);
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
}

/* Button content layout */
.btn-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-text {
    line-height: 1;
}

.btn-icon {
    font-size: 1em;
    flex-shrink: 0;
}

/* Enhanced hover states using custom properties */
.enhanced-btn:hover:not(.disabled):not(.btn-loading) {
    transform: translateY(-1px);
    box-shadow: var(--theme-btn-shadow-hover, 0 4px 8px rgba(0,0,0,0.15));
}

.enhanced-btn.btn-primary:hover:not(.disabled):not(.btn-loading) {
    background-color: var(--theme-primary-hover, #286090) !important;
    border-color: var(--theme-primary-hover, #286090) !important;
}

.enhanced-btn.btn-success:hover:not(.disabled):not(.btn-loading) {
    background-color: var(--theme-success-hover, #449d44) !important;
    border-color: var(--theme-success-hover, #449d44) !important;
}

.enhanced-btn.btn-warning:hover:not(.disabled):not(.btn-loading) {
    background-color: var(--theme-warning-hover, #ec971f) !important;
    border-color: var(--theme-warning-hover, #ec971f) !important;
}

.enhanced-btn.btn-danger:hover:not(.disabled):not(.btn-loading) {
    background-color: var(--theme-danger-hover, #c9302c) !important;
    border-color: var(--theme-danger-hover, #c9302c) !important;
}

.enhanced-btn.btn-info:hover:not(.disabled):not(.btn-loading) {
    background-color: var(--theme-info-hover, #31b0d5) !important;
    border-color: var(--theme-info-hover, #31b0d5) !important;
}

.enhanced-btn.btn-default:hover:not(.disabled):not(.btn-loading) {
    background-color: var(--theme-surface-hover, #e6e6e6) !important;
    border-color: var(--theme-border-hover, #adadad) !important;
}

/* Enhanced focus states */
.enhanced-btn:focus {
    outline: none;
    box-shadow: var(--theme-focus-shadow, 0 0 0 0.2rem rgba(51, 122, 183, 0.25));
    border-color: var(--theme-focus-color, #337ab7);
}

/* Enhanced active states */
.enhanced-btn:active,
.enhanced-btn.active {
    transform: translateY(0);
    box-shadow: var(--theme-btn-shadow-active, 0 1px 3px rgba(0,0,0,0.2));
}

/* Disabled state enhancements */
.enhanced-btn.disabled,
.enhanced-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
    transform: none !important;
    box-shadow: none !important;
}

/* Loading state */
.enhanced-btn.btn-loading {
    cursor: wait;
    pointer-events: none;
}

.btn-spinner {
    display: inline-flex;
    align-items: center;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.125em;
    border-style: solid;
    border-color: currentColor transparent currentColor transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

/* Size-specific enhancements */
.enhanced-btn.btn-lg {
    padding: 12px 20px;
    font-size: 1.125rem;
    line-height: 1.3;
}

.enhanced-btn.btn-sm {
    padding: 6px 12px;
    font-size: 0.875rem;
    line-height: 1.2;
}

.enhanced-btn.btn-xs {
    padding: 3px 8px;
    font-size: 0.75rem;
    line-height: 1.1;
}

.enhanced-btn.btn-lg .btn-icon {
    font-size: 1.2em;
}

.enhanced-btn.btn-sm .btn-icon {
    font-size: 0.9em;
}

.enhanced-btn.btn-xs .btn-icon {
    font-size: 0.8em;
}

/* Link button styling */
a.enhanced-btn {
    text-decoration: none;
}

a.enhanced-btn:hover,
a.enhanced-btn:focus {
    text-decoration: none;
}

a.enhanced-btn.disabled {
    color: inherit;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .enhanced-btn {
        min-width: 44px; /* Touch-friendly minimum size */
        min-height: 44px;
    }
    
    .enhanced-btn.btn-sm {
        min-width: 36px;
        min-height: 36px;
    }
    
    .enhanced-btn.btn-lg {
        min-width: 48px;
        min-height: 48px;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .enhanced-btn,
    .spinner-border-sm {
        transition: none;
        animation: none;
    }
    
    .enhanced-btn:hover {
        transform: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .enhanced-btn {
        border-width: 2px;
    }
    
    .enhanced-btn:focus {
        outline: 3px solid;
        outline-offset: 2px;
    }
}

/* Print styles */
@media print {
    .enhanced-btn {
        background: none !important;
        color: #000 !important;
        border: 1px solid #000 !important;
        box-shadow: none !important;
        transform: none !important;
    }
    
    .btn-spinner {
        display: none;
    }
}
</style> 