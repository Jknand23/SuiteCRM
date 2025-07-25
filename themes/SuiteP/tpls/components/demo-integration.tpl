{*
/**
 * @fileoverview Demo Integration Template
 * 
 * Demonstrates how to integrate enhanced components with CSS custom property fallbacks
 * into existing SuiteCRM templates without breaking compatibility. Shows migration path
 * from traditional templates to enhanced components.
 * 
 * Key Integration Principles:
 * 1. Preserve all existing template functionality
 * 2. Add CSS custom properties as fallback-supported enhancements
 * 3. Maintain Bootstrap 3.3.7 class structure
 * 4. Progressive enhancement approach
 * 5. Accessibility improvements
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{* Load Alpine.js and theme manager (progressive enhancement) *}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="themes/SuiteP/js/components/theme-manager.js"></script>

{* Example: Enhanced Header with Theme Switcher Integration *}
<header class="main-header" 
        style="
          background-color: var(--theme-primary, {$THEME_PRIMARY|default:'#778591'});
          color: var(--theme-on-primary, {$ON_PRIMARY_COLOR|default:'#ffffff'});
          border-bottom-color: var(--theme-border-color, {$BORDER_COLOR|default:'#ddd'});
        ">
    <div class="container-fluid">
        <div class="header-content">
            {* Existing logo and title - unchanged *}
            <div class="header-logo">
                <img src="{$THEME_IMAGE_PATH}logo.png" alt="{$APP_NAME}" class="logo-img">
                <h1 class="app-title">{$APP_NAME}</h1>
            </div>
            
            {* Enhanced navigation with CSS custom properties *}
            <nav class="main-navigation" 
                 style="
                   background-color: var(--theme-surface, {$SURFACE_COLOR|default:'#ffffff'});
                   border-color: var(--theme-border-color, {$BORDER_COLOR|default:'#ddd'});
                 ">
                {* Existing navigation items - preserved *}
                <ul class="nav navbar-nav">
                    {foreach from=$MODULE_TAB item=module_tab}
                        <li class="nav-item">
                            <a href="{$module_tab.url}" 
                               class="nav-link"
                               style="color: var(--theme-link-color, {$LINK_COLOR|default:'#337ab7'});">
                                {$module_tab.label}
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </nav>
            
            {* NEW: Theme Switcher Integration (progressive enhancement) *}
            <div class="header-actions">
                {* Include theme switcher component *}
                {include file="themes/SuiteP/tpls/components/theme-switcher.tpl"}
                
                {* Existing user menu - enhanced with custom properties *}
                <div class="user-menu dropdown">
                    <button type="button" 
                            class="btn btn-default dropdown-toggle" 
                            data-toggle="dropdown"
                            style="
                              background-color: var(--theme-btn-default-bg, {$BTN_DEFAULT_BG|default:'#ffffff'});
                              border-color: var(--theme-btn-default-border, {$BTN_DEFAULT_BORDER|default:'#ccc'});
                              color: var(--theme-btn-default-color, {$BTN_DEFAULT_COLOR|default:'#333'});
                            ">
                        <span class="suitepicon suitepicon-user"></span>
                        {$CURRENT_USER_NAME}
                        <span class="caret"></span>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-right"
                        style="
                          background-color: var(--theme-surface, {$SURFACE_COLOR|default:'#ffffff'});
                          border-color: var(--theme-border-color, {$BORDER_COLOR|default:'#ddd'});
                        ">
                        <li><a href="index.php?module=Users&action=EditView&record={$CURRENT_USER_ID}"
                               style="color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});">Profile</a></li>
                        <li><a href="index.php?action=Logout"
                               style="color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

{* Example: Enhanced Panel Integration in Main Content *}
<main class="main-content" 
      style="
        background-color: var(--theme-main-bg, {$MAIN_BG|default:'#f5f5f5'});
        color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});
      ">
    <div class="container-fluid">
        <div class="row">
            {* Existing sidebar - enhanced with custom properties *}
            <aside class="col-md-3 sidebar" 
                   style="
                     background-color: var(--theme-surface, {$SURFACE_COLOR|default:'#ffffff'});
                     border-right-color: var(--theme-border-color, {$BORDER_COLOR|default:'#ddd'});
                   ">
                {* Traditional panel - still works exactly the same *}
                <div class="panel panel-default">
                    <div class="panel-heading">Traditional Panel</div>
                    <div class="panel-body">
                        This panel uses traditional Bootstrap classes and continues to work exactly as before.
                        No changes needed to existing templates.
                    </div>
                </div>
                
                {* NEW: Enhanced panel with custom properties *}
                {include file="themes/SuiteP/tpls/components/enhanced-panel.tpl" 
                         title="Enhanced Panel" 
                         content="This panel uses CSS custom properties as fallbacks while maintaining full compatibility."
                         type="primary"
                         collapsible=true}
            </aside>
            
            {* Main content area with enhanced components *}
            <div class="col-md-9 main-panel">
                {* Example: Enhanced form with custom property fallbacks *}
                <form class="enhanced-form" 
                      style="
                        background-color: var(--theme-surface, {$SURFACE_COLOR|default:'#ffffff'});
                        border-color: var(--theme-border-color, {$BORDER_COLOR|default:'#ddd'});
                        padding: var(--theme-spacing-lg, 20px);
                      ">
                    
                    <div class="form-group">
                        <label for="example-input" 
                               style="color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});">
                            Example Input:
                        </label>
                        <input type="text" 
                               id="example-input"
                               class="form-control" 
                               placeholder="Enter text here..."
                               style="
                                 border-color: var(--theme-input-border, {$INPUT_BORDER|default:'#ccc'});
                                 background-color: var(--theme-input-bg, {$INPUT_BG|default:'#ffffff'});
                                 color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});
                               ">
                    </div>
                    
                    <div class="form-actions">
                        {* Traditional button - still works *}
                        <button type="submit" class="btn btn-primary">Traditional Save</button>
                        
                        {* Enhanced button with custom properties *}
                        {include file="themes/SuiteP/tpls/components/enhanced-button.tpl" 
                                 text="Enhanced Save" 
                                 type="success"
                                 icon="save"
                                 onclick="handleEnhancedSave()"}
                        
                        {* Enhanced button with loading state *}
                        {include file="themes/SuiteP/tpls/components/enhanced-button.tpl" 
                                 text="Save & Process" 
                                 type="warning"
                                 icon="process"
                                 loading=false
                                 id="process-btn"}
                    </div>
                </form>
                
                {* Example: Enhanced data table with custom properties *}
                <div class="data-table-container" 
                     style="
                       background-color: var(--theme-surface, {$SURFACE_COLOR|default:'#ffffff'});
                       border-color: var(--theme-border-color, {$BORDER_COLOR|default:'#ddd'});
                       margin-top: var(--theme-spacing-lg, 20px);
                     ">
                    
                    <table class="table table-striped enhanced-table">
                        <thead style="background-color: var(--theme-panel-heading-bg, {$PANEL_HEADING_BG|default:'#f5f5f5'});">
                            <tr>
                                <th style="color: var(--theme-panel-text, {$PANEL_TEXT_COLOR|default:'#333'});">Name</th>
                                <th style="color: var(--theme-panel-text, {$PANEL_TEXT_COLOR|default:'#333'});">Email</th>
                                <th style="color: var(--theme-panel-text, {$PANEL_TEXT_COLOR|default:'#333'});">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$DEMO_DATA item=record}
                                <tr style="border-color: var(--theme-border-color, {$BORDER_COLOR|default:'#ddd'});">
                                    <td style="color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});">{$record.name}</td>
                                    <td style="color: var(--theme-text-color, {$TEXT_COLOR|default:'#333'});">{$record.email}</td>
                                    <td>
                                        {include file="themes/SuiteP/tpls/components/enhanced-button.tpl" 
                                                 text="Edit" 
                                                 type="default"
                                                 size="sm"
                                                 icon="edit"}
                                        {include file="themes/SuiteP/tpls/components/enhanced-button.tpl" 
                                                 text="Delete" 
                                                 type="danger"
                                                 size="sm"
                                                 icon="delete"}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

{* Enhanced styles for demo integration *}
<style>
/* Progressive enhancement styles that work with existing CSS */

/* Header enhancements */
.main-header {
    transition: background-color 0.3s ease;
    box-shadow: var(--theme-panel-shadow, 0 2px 4px rgba(0,0,0,0.1));
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
}

.header-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-img {
    height: 40px;
    width: auto;
}

.app-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: inherit;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Enhanced navigation */
.main-navigation {
    flex: 1;
    margin: 0 2rem;
}

.nav-link:hover {
    color: var(--theme-link-hover-color, #23527c) !important;
    text-decoration: none;
}

/* Enhanced sidebar */
.sidebar {
    padding: 1rem;
    min-height: calc(100vh - 120px);
}

/* Enhanced form styles */
.enhanced-form {
    border: 1px solid;
    border-radius: var(--theme-border-radius, 4px);
    margin-bottom: 2rem;
}

.form-control:focus {
    border-color: var(--theme-focus-color, #337ab7);
    box-shadow: var(--theme-focus-shadow, 0 0 0 0.2rem rgba(51, 122, 183, 0.25));
}

/* Enhanced table styles */
.enhanced-table {
    margin: 0;
}

.enhanced-table th,
.enhanced-table td {
    padding: 12px;
    vertical-align: middle;
}

.enhanced-table tbody tr:hover {
    background-color: var(--theme-surface-hover, #f8f9fa);
}

/* Enhanced container */
.data-table-container {
    border: 1px solid;
    border-radius: var(--theme-border-radius, 4px);
    overflow: hidden;
}

/* Responsive enhancements */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .main-navigation {
        order: 2;
        margin: 0;
        width: 100%;
    }
    
    .header-actions {
        order: 1;
        width: 100%;
        justify-content: flex-end;
    }
    
    .nav.navbar-nav {
        flex-direction: column;
        width: 100%;
    }
}

/* Print styles */
@media print {
    .theme-switcher-container,
    .header-actions,
    .enhanced-btn {
        display: none !important;
    }
    
    .main-content {
        background: white !important;
        color: black !important;
    }
}
</style>

{* Enhanced JavaScript for demo functionality *}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Example: Enhanced button functionality
    function handleEnhancedSave() {
        console.log('Enhanced save button clicked');
        // Add your save logic here
        alert('Enhanced save functionality triggered!');
    }
    
    // Example: Process button with loading state
    const processBtn = document.getElementById('process-btn');
    if (processBtn) {
        processBtn.addEventListener('click', function() {
            // Simulate processing
            this.classList.add('btn-loading');
            this.setAttribute('aria-busy', 'true');
            this.disabled = true;
            
            setTimeout(() => {
                this.classList.remove('btn-loading');
                this.removeAttribute('aria-busy');
                this.disabled = false;
                alert('Processing completed!');
            }, 3000);
        });
    }
    
    // Example: Theme change event listener
    document.addEventListener('suiteThemeChanged', function(event) {
        console.log('Theme changed to:', event.detail.theme);
        // You can add custom logic here when theme changes
    });
});

// Global function to be called by onclick attributes
function handleEnhancedSave() {
    console.log('Enhanced save button clicked');
    alert('Enhanced save functionality triggered!');
}
</script> 