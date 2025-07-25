{*
/**
 * @fileoverview Theme Switcher Component Template
 * 
 * Smarty template for the Alpine.js theme switcher component. Provides instant theme
 * switching UI that integrates with existing SuiteCRM theme preference system.
 * 
 * Features:
 * - Dropdown theme selector with preview colors
 * - Progressive enhancement (works without JavaScript)
 * - Responsive design with Bootstrap compatibility
 * - ARIA accessibility compliance
 * - Error handling with user feedback
 * 
 * Dependencies:
 * - Alpine.js 3.x
 * - Bootstrap 3.3.7+ styling
 * - theme-manager.js component
 * - CSS custom properties in compiled themes
 * 
 * Usage:
 * {include file="themes/SuiteP/tpls/components/theme-switcher.tpl"}
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{* Initialize current theme from SuiteCRM preferences *}
{assign var="currentSubTheme" value=$current_user->getSubTheme()}
{if empty($currentSubTheme)}
    {assign var="currentSubTheme" value="Dawn"}
{/if}

<script>
    // Initialize global theme preference for Alpine.js component
    window.suiteThemePreference = '{$currentSubTheme}';
</script>

{* Theme Switcher Component *}
<div class="theme-switcher-container" 
     x-data="themeManager()" 
     x-init="init()"
     @click.away="closeDropdown()">
     
    {* Main Theme Switcher Button *}
    <div class="theme-switcher-trigger">
        <button type="button" 
                class="btn btn-default theme-switcher-btn"
                @click="toggleDropdown()"
                :disabled="isLoading"
                aria-haspopup="true"
                :aria-expanded="isDropdownOpen"
                aria-label="Switch Theme">
            
            {* Theme Icon *}
            <span class="suitepicon suitepicon-theme" aria-hidden="true"></span>
            
            {* Current Theme Display *}
            <span class="theme-current-name" x-text="getThemeInfo(currentTheme).name"></span>
            
            {* Loading Indicator *}
            <span x-show="isLoading" class="theme-loading" aria-hidden="true">
                <span class="sr-only">Loading...</span>
                <span class="loading-spinner"></span>
            </span>
            
            {* Dropdown Caret *}
            <span x-show="!isLoading" 
                  class="caret" 
                  :class="{ 'caret-up': isDropdownOpen }"
                  aria-hidden="true"></span>
        </button>
    </div>

    {* Theme Dropdown Menu *}
    <div class="theme-dropdown"
         x-show="isDropdownOpen"
         x-transition:enter="theme-dropdown-enter"
         x-transition:enter-start="theme-dropdown-enter-start"  
         x-transition:enter-end="theme-dropdown-enter-end"
         x-transition:leave="theme-dropdown-leave"
         x-transition:leave-start="theme-dropdown-leave-start"
         x-transition:leave-end="theme-dropdown-leave-end"
         role="menu"
         aria-labelledby="theme-switcher-button">

        {* Theme Options List *}
        <ul class="theme-options" role="none">
            <template x-for="theme in availableThemes" :key="theme">
                <li role="none">
                    <button type="button"
                            class="theme-option"
                            :class="{ 'theme-option-active': isThemeActive(theme) }"
                            @click="switchTheme(theme)"
                            :disabled="isLoading"
                            role="menuitem"
                            :aria-label="`Switch to ${getThemeInfo(theme).name} theme`">
                        
                        {* Theme Preview Color *}
                        <span class="theme-preview" 
                              :style="`background-color: ${getThemeInfo(theme).preview}`"
                              aria-hidden="true"></span>
                        
                        {* Theme Info *}
                        <div class="theme-info">
                            <div class="theme-name" x-text="getThemeInfo(theme).name"></div>
                            <div class="theme-description" x-text="getThemeInfo(theme).description"></div>
                        </div>
                        
                        {* Active Indicator *}
                        <span x-show="isThemeActive(theme)" 
                              class="theme-active-indicator suitepicon suitepicon-check"
                              aria-label="Currently active theme"></span>
                    </button>
                </li>
            </template>
        </ul>
        
        {* Error Message Display *}
        <div x-show="hasError" 
             class="theme-error alert alert-danger" 
             role="alert"
             x-transition>
            <button type="button" 
                    class="close" 
                    @click="clearError()" 
                    aria-label="Dismiss error">
                <span aria-hidden="true">&times;</span>
            </button>
            <span x-text="errorMessage"></span>
        </div>
    </div>
</div>

{* Fallback for Non-JavaScript Users *}
<noscript>
    <div class="theme-switcher-fallback">
        <form action="index.php" method="POST" class="form-inline">
            <input type="hidden" name="module" value="Users" />
            <input type="hidden" name="action" value="Save" />
            <input type="hidden" name="record" value="{$current_user->id}" />
            
            <div class="form-group">
                <label for="user_subtheme" class="sr-only">Select Theme:</label>
                <select name="user_subtheme" id="user_subtheme" class="form-control">
                    <option value="Dawn" {if $currentSubTheme == "Dawn"}selected{/if}>Dawn - Light Professional</option>
                    <option value="Day" {if $currentSubTheme == "Day"}selected{/if}>Day - Bright Energetic</option>
                    <option value="Dusk" {if $currentSubTheme == "Dusk"}selected{/if}>Dusk - Warm Comfortable</option>
                    <option value="Night" {if $currentSubTheme == "Night"}selected{/if}>Night - Dark Elegant</option>
                    <option value="Noon" {if $currentSubTheme == "Noon"}selected{/if}>Noon - Clean Minimal</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Change Theme</button>
        </form>
    </div>
</noscript>

{* Component Styles *}
<style>
/* Theme Switcher Container */
.theme-switcher-container {
    position: relative;
    display: inline-block;
    z-index: 1050;
}

/* Main Trigger Button */
.theme-switcher-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 120px;
    padding: 6px 12px;
    border: 1px solid var(--theme-border-color, #ddd);
    background-color: var(--theme-surface, #fff);
    color: var(--theme-text-color, #333);
    transition: all 0.2s ease-in-out;
}

.theme-switcher-btn:hover {
    background-color: var(--theme-surface-hover, #f5f5f5);
    border-color: var(--theme-border-hover, #999);
}

.theme-switcher-btn:focus {
    outline: 2px solid var(--theme-focus-color, #337ab7);
    outline-offset: 2px;
}

/* Loading Spinner */
.loading-spinner {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 2px solid var(--theme-border-color, #ddd);
    border-top: 2px solid var(--theme-primary, #337ab7);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Dropdown Caret */
.caret-up {
    transform: rotate(180deg);
}

/* Theme Dropdown */
.theme-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    min-width: 280px;
    margin-top: 4px;
    background-color: var(--theme-surface, #fff);
    border: 1px solid var(--theme-border-color, #ddd);
    border-radius: 4px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    z-index: 1060;
}

/* Dropdown Transitions */
.theme-dropdown-enter {
    transition: all 0.2s ease-out;
}

.theme-dropdown-enter-start {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}

.theme-dropdown-enter-end {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.theme-dropdown-leave {
    transition: all 0.15s ease-in;
}

.theme-dropdown-leave-start {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.theme-dropdown-leave-end {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}

/* Theme Options List */
.theme-options {
    list-style: none;
    margin: 0;
    padding: 8px 0;
}

/* Individual Theme Option */
.theme-option {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 12px 16px;
    border: none;
    background: none;
    color: var(--theme-text-color, #333);
    text-align: left;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.theme-option:hover {
    background-color: var(--theme-surface-hover, #f5f5f5);
}

.theme-option:focus {
    background-color: var(--theme-surface-focus, #e6f3ff);
    outline: none;
}

.theme-option-active {
    background-color: var(--theme-primary-light, #e6f3ff);
    font-weight: 600;
}

/* Theme Preview Color Dot */
.theme-preview {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin-right: 12px;
    border: 2px solid var(--theme-border-color, #ddd);
    flex-shrink: 0;
}

/* Theme Info Text */
.theme-info {
    flex: 1;
}

.theme-name {
    font-weight: 500;
    margin-bottom: 2px;
}

.theme-description {
    font-size: 0.875rem;
    color: var(--theme-text-muted, #666);
}

/* Active Theme Indicator */
.theme-active-indicator {
    color: var(--theme-success, #5cb85c);
    font-size: 16px;
    margin-left: 8px;
}

/* Error Message */
.theme-error {
    margin: 8px 12px 0;
    padding: 8px 12px;
    font-size: 0.875rem;
}

/* Fallback Styles for Non-JS */
.theme-switcher-fallback {
    padding: 8px;
    background-color: var(--theme-surface, #fff);
    border: 1px solid var(--theme-border-color, #ddd);
    border-radius: 4px;
}

.theme-switcher-fallback .form-group {
    margin-right: 8px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .theme-dropdown {
        right: auto;
        left: 0;
        min-width: 260px;
    }
    
    .theme-switcher-btn {
        min-width: 100px;
    }
    
    .theme-current-name {
        display: none;
    }
}
</style> 