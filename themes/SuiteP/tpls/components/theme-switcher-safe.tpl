{*
/**
 * @fileoverview Ultra-Safe Theme Switcher Template
 * 
 * Absolutely minimal template with NO variable access to prevent white screen errors.
 * All theme detection is done in JavaScript after page load.
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.2.0
 * @since 2024-01-16
 */
*}

{* NO VARIABLE ACCESS - Just plain HTML *}
<div id="theme_switcher_container"></div>
<script src="themes/SuiteP/js/components/theme-switcher-safe.js"></script>

{* Basic styles for theme switcher *}
<style>
{literal}
.theme-switcher-safe {
    position: relative;
    display: inline-block;
}

.theme-btn-safe {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    min-width: 100px;
}

.theme-dropdown-safe {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 2px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1060;
    min-width: 150px;
}

.theme-options-safe {
    list-style: none;
    margin: 0;
    padding: 4px 0;
}

.theme-options-safe li {
    margin: 0;
}

.theme-option-safe {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 8px 12px;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.2s;
}

.theme-option-safe:hover {
    background-color: #f5f5f5;
}

.theme-option-safe.active {
    background-color: #e6f3ff;
    font-weight: 600;
}

.theme-preview-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
    border: 1px solid #ddd;
}

.theme-name {
    flex: 1;
}

.suitepicon-action-check {
    margin-left: 8px;
    color: #5cb85c;
}
{/literal}
</style> 