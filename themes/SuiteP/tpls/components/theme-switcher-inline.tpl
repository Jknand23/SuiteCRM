{*
/**
 * @fileoverview Inline Theme Switcher Component for Header Navigation
 * 
 * Compact version of the theme switcher designed for integration into the main
 * SuiteCRM header navigation bar. Provides instant theme switching with minimal
 * visual footprint while maintaining full functionality.
 * 
 * Features:
 * - Compact design suitable for navigation bar
 * - Alpine.js reactive theme switching
 * - Integration with existing UserPreference system
 * - Progressive enhancement (graceful degradation)
 * - Accessible dropdown design
 * 
 * Dependencies:
 * - Alpine.js 3.x
 * - theme-manager.js component
 * - CSS custom properties in compiled themes
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{* Safe theme preference initialization with multiple fallbacks *}
{php}
    // Safely get the current theme with proper checks
    $currentTheme = 'Dawn'; // Default fallback
    
    // Check if we're in a valid SuiteCRM context
    if (isset($GLOBALS['current_user']) && is_object($GLOBALS['current_user'])) {
        // Try to get theme from global user object
        $user = $GLOBALS['current_user'];
        if (method_exists($user, 'getSubTheme')) {
            $userTheme = $user->getSubTheme();
            if (!empty($userTheme)) {
                $currentTheme = $userTheme;
            }
        }
    } elseif (isset($_SESSION['authenticated_user_theme'])) {
        // Fallback to session if available
        $currentTheme = $_SESSION['authenticated_user_theme'];
    } elseif (isset($GLOBALS['sugar_config']['default_theme'])) {
        // Fallback to system default
        $currentTheme = $GLOBALS['sugar_config']['default_theme'];
    }
    
    // Ensure theme is valid
    $validThemes = array('Dawn', 'Day', 'Dusk', 'Night', 'Noon');
    if (!in_array($currentTheme, $validThemes)) {
        $currentTheme = 'Dawn';
    }
    
    // Assign to template
    $this->assign('safeCurrentTheme', $currentTheme);
{/php}

{* Only render theme switcher if we have a valid context *}
{if !empty($safeCurrentTheme)}

{* Defer all scripts until DOM is ready *}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lazy load Alpine.js and theme manager
    if (typeof Alpine === 'undefined') {
        var alpineScript = document.createElement('script');
        alpineScript.src = 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js';
        alpineScript.defer = true;
        document.head.appendChild(alpineScript);
    }
    
    // Load theme manager
    var themeManagerScript = document.createElement('script');
    themeManagerScript.src = 'themes/SuiteP/js/components/theme-manager.js';
    document.head.appendChild(themeManagerScript);
    
    // Initialize theme preference after scripts load
    themeManagerScript.onload = function() {
        window.suiteThemePreference = '{$safeCurrentTheme}';
    };
});
</script>

{* Compact Theme Switcher for Header Navigation *}
<div class="theme-switcher-inline" 
     x-data="themeManager()" 
     x-init="init()"
     @click.away="closeDropdown()">
     
    {* Compact Theme Button *}
    <button type="button" 
            class="btn btn-default theme-btn-inline"
            @click="toggleDropdown()"
            :disabled="isLoading"
            aria-haspopup="true"
            :aria-expanded="isDropdownOpen"
            aria-label="Switch Theme"
            title="Change Theme">
        
        {* Theme Icon *}
        <span class="suitepicon suitepicon-action-theme" aria-hidden="true"></span>
        
        {* Loading Indicator *}
        <span x-show="isLoading" class="loading-spinner-inline" aria-hidden="true"></span>
        
        {* Dropdown Caret *}
        <span x-show="!isLoading" 
              class="suitepicon suitepicon-action-caret" 
              :class="{ 'caret-up': isDropdownOpen }"
              aria-hidden="true"></span>
    </button>

    {* Compact Theme Dropdown *}
    <div class="theme-dropdown-inline"
         x-show="isDropdownOpen"
         x-transition:enter="dropdown-enter"
         x-transition:enter-start="dropdown-enter-start"  
         x-transition:enter-end="dropdown-enter-end"
         x-transition:leave="dropdown-leave"
         x-transition:leave-start="dropdown-leave-start"
         x-transition:leave-end="dropdown-leave-end"
         role="menu"
         aria-labelledby="theme-switcher-button">

        {* Theme Options List *}
        <ul class="theme-options-inline" role="none">
            <template x-for="theme in availableThemes" :key="theme">
                <li role="none">
                    <button type="button"
                            class="theme-option-inline"
                            :class="{ 'theme-option-active': isThemeActive(theme) }"
                            @click="switchTheme(theme)"
                            :disabled="isLoading"
                            role="menuitem"
                            :aria-label="`Switch to ${getThemeInfo(theme).name} theme`">
                        
                        {* Theme Preview Dot *}
                        <span class="theme-preview-inline" 
                              :style="`background-color: ${getThemeInfo(theme).preview}`"
                              aria-hidden="true"></span>
                        
                        {* Theme Name *}
                        <span class="theme-name-inline" x-text="getThemeInfo(theme).name"></span>
                        
                        {* Active Indicator *}
                        <span x-show="isThemeActive(theme)" 
                              class="theme-active-check suitepicon suitepicon-action-check"
                              aria-label="Active"></span>
                    </button>
                </li>
            </template>
        </ul>
        
        {* Error Message *}
        <div x-show="hasError" 
             class="theme-error-inline" 
             role="alert"
             x-transition>
            <span x-text="errorMessage"></span>
            <button type="button" 
                    class="error-dismiss" 
                    @click="clearError()" 
                    aria-label="Dismiss">×</button>
        </div>
    </div>
</div>

{* Fallback for Non-JavaScript Users *}
<noscript>
    <div class="theme-switcher-fallback-inline">
        <form action="index.php" method="POST" style="display: inline;">
            <input type="hidden" name="module" value="Users" />
            <input type="hidden" name="action" value="Save" />
            {php}
                // Safely get user ID for fallback form
                $userId = '';
                if (isset($GLOBALS['current_user']) && is_object($GLOBALS['current_user']) && !empty($GLOBALS['current_user']->id)) {
                    $userId = $GLOBALS['current_user']->id;
                }
                $this->assign('safeUserId', $userId);
            {/php}
            {if !empty($safeUserId)}
                <input type="hidden" name="record" value="{$safeUserId}" />
            {/if}
            
            <select name="user_subtheme" onchange="this.form.submit();" class="form-control-inline">
                <option value="Dawn" {if $safeCurrentTheme == "Dawn"}selected{/if}>Dawn</option>
                <option value="Day" {if $safeCurrentTheme == "Day"}selected{/if}>Day</option>
                <option value="Dusk" {if $safeCurrentTheme == "Dusk"}selected{/if}>Dusk</option>
                <option value="Night" {if $safeCurrentTheme == "Night"}selected{/if}>Night</option>
                <option value="Noon" {if $safeCurrentTheme == "Noon"}selected{/if}>Noon</option>
            </select>
        </form>
    </div>
</noscript>

{* Inline Component Styles *}
<style>
/* Inline Theme Switcher Styles */
.theme-switcher-inline {
    position: relative;
    display: inline-block;
}

.theme-btn-inline {
    display: flex;
    align-items: center;
    gap: 4px;
    min-width: 40px;
    padding: 6px 8px;
    border: 1px solid var(--theme-border-color, #ddd);
    background-color: var(--theme-surface, #fff);
    color: var(--theme-text-color, #333);
    transition: all 0.2s ease;
}

.theme-btn-inline:hover {
    background-color: var(--theme-surface-hover, #f5f5f5);
    border-color: var(--theme-border-hover, #999);
}

.theme-btn-inline:focus {
    outline: 2px solid var(--theme-focus-color, #337ab7);
    outline-offset: 1px;
}

.loading-spinner-inline {
    display: inline-block;
    width: 10px;
    height: 10px;
    border: 1px solid var(--theme-border-color, #ddd);
    border-top: 1px solid var(--theme-primary, #337ab7);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.caret-up {
    transform: rotate(180deg);
}

/* Dropdown Styles */
.theme-dropdown-inline {
    position: absolute;
    top: 100%;
    right: 0;
    min-width: 200px;
    margin-top: 2px;
    background-color: var(--theme-surface, #fff);
    border: 1px solid var(--theme-border-color, #ddd);
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    z-index: 1060;
}

/* Dropdown Transitions */
.dropdown-enter {
    transition: all 0.15s ease-out;
}

.dropdown-enter-start {
    opacity: 0;
    transform: translateY(-5px) scale(0.95);
}

.dropdown-enter-end {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.dropdown-leave {
    transition: all 0.1s ease-in;
}

.dropdown-leave-start {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.dropdown-leave-end {
    opacity: 0;
    transform: translateY(-5px) scale(0.95);
}

.theme-options-inline {
    list-style: none;
    margin: 0;
    padding: 4px 0;
}

.theme-option-inline {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 8px 12px;
    border: none;
    background: none;
    color: var(--theme-text-color, #333);
    text-align: left;
    cursor: pointer;
    transition: background-color 0.1s ease;
}

.theme-option-inline:hover {
    background-color: var(--theme-surface-hover, #f5f5f5);
}

.theme-option-inline:focus {
    background-color: var(--theme-surface-focus, #e6f3ff);
    outline: none;
}

.theme-option-active {
    background-color: var(--theme-primary-light, #e6f3ff);
    font-weight: 600;
}

.theme-preview-inline {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
    border: 1px solid var(--theme-border-color, #ddd);
    flex-shrink: 0;
}

.theme-name-inline {
    flex: 1;
    font-size: 14px;
}

.theme-active-check {
    color: var(--theme-success, #5cb85c);
    font-size: 14px;
    margin-left: 4px;
}

.theme-error-inline {
    padding: 6px 12px;
    background-color: var(--theme-danger-light, #f2dede);
    color: var(--theme-danger, #d9534f);
    font-size: 12px;
    border-top: 1px solid var(--theme-border-color, #ddd);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.error-dismiss {
    background: none;
    border: none;
    color: var(--theme-danger, #d9534f);
    font-size: 16px;
    cursor: pointer;
    padding: 0;
    margin-left: 8px;
}

/* Fallback Styles */
.theme-switcher-fallback-inline .form-control-inline {
    font-size: 12px;
    padding: 4px 6px;
    border: 1px solid var(--theme-border-color, #ddd);
    background-color: var(--theme-surface, #fff);
}

/* Responsive */
@media (max-width: 768px) {
    .theme-dropdown-inline {
        right: auto;
        left: 0;
        min-width: 180px;
    }
}
</style>

{/if} {* End of theme switcher conditional - only renders if we have a valid theme context *} 