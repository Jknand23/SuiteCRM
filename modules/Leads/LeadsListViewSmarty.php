<?php
require_once('include/ListView/ListViewSmarty.php');
require_once('modules/AOS_PDF_Templates/formLetter.php');


#[\AllowDynamicProperties]
class LeadsListViewSmarty extends ListViewSmarty
{
    /** @var bool $forceEnhancedTemplate Force enhanced template usage */
    public $forceEnhancedTemplate = false;
    
    public function __construct()
    {
        parent::__construct();
        $this->targetList = true;
    }

    /**
     * Override setup to ensure proper initialization
     *
     * @since 1.0.0
     */
    public function setup($seed, $file, $where, $params = array(), $offset = 0, $limit = -1, $filter_fields = array(), $id_field = 'id', $id = null)
    {
        // Don't override the template file here anymore - we handle it in display()
        // Just call parent setup with the standard template
        return parent::setup($seed, $file, $where, $params, $offset, $limit, $filter_fields, $id_field, $id);
    }


    /**
     *
     * @param file $file Template file to use
     * @param array $data from ListViewData
     * @param string $htmlVar the corresponding html public in xtpl per row
     * @return bool|void
     */
    public function process($file, $data, $htmlVar)
    {
        // Add Alpine.js and Bootstrap 5 support for Phase 2 filter system
        $this->includeAdvancedFilterAssets();
        
        $configurator = new Configurator();
        if ($configurator->isConfirmOptInEnabled()) {
            $this->actionsMenuExtraItems[] = $this->buildSendConfirmOptInEmailToPersonAndCompany();
        }

        // Use enhanced template for Phase 2 advanced filtering
        // Force enhanced template if flag is set or if not a popup request
        $shouldUseEnhanced = $this->forceEnhancedTemplate || 
                            (!isset($_REQUEST['action']) || $_REQUEST['action'] !== 'Popup');
        
        if ($shouldUseEnhanced) {
            $enhancedTemplate = 'modules/Leads/tpls/ListViewEnhanced.tpl';
            if (file_exists($enhancedTemplate)) {
                $file = $enhancedTemplate;
                $GLOBALS['log']->info('Using enhanced leads template: ' . $enhancedTemplate);
            } else {
                $GLOBALS['log']->warn('Enhanced template not found: ' . $enhancedTemplate);
            }
        }
        
        $ret = parent::process($file, $data, $htmlVar);

        if (!ACLController::checkAccess($this->seed->module_dir, 'export', true) || !$this->export) {
            $this->ss->assign('exportLink', $this->buildExportLink());
        }

        // Add the advanced filter bar to the template (only for enhanced view)
        if ($shouldUseEnhanced) {
            $this->addAdvancedFilterBar();
        }

        return $ret;
    }
    
    /**
     * Override display to add enhanced components before standard list view
     *
     * @since 1.0.0
     */
    public function display($end = true)
    {
        // Debug template loading
        $GLOBALS['log']->info('LeadsListViewSmarty::display() called');
        $GLOBALS['log']->info('Template file: ' . $this->tpl);
        $GLOBALS['log']->info('Ajax load: ' . (isset($_REQUEST['ajax_load']) ? $_REQUEST['ajax_load'] : 'not set'));
        $GLOBALS['log']->info('Data count: ' . (isset($this->data['data']) ? count($this->data['data']) : 0));
        
        // Check if we should show enhanced components
        $shouldUseEnhanced = $this->forceEnhancedTemplate || 
                            (!isset($_REQUEST['action']) || $_REQUEST['action'] !== 'Popup');
        
        $enhancedContent = '';
        
        if ($shouldUseEnhanced) {
            // Render our enhanced components separately
            $enhancedTemplate = 'modules/Leads/tpls/ListViewEnhanced.tpl';
            if (file_exists($enhancedTemplate)) {
                // Render the enhanced content using the same Smarty instance so all vars are present
                // Ensure data array is available for any future use
                $this->ss->assign('data', $this->data);

                $enhancedContent = $this->ss->fetch($enhancedTemplate);
                
                $GLOBALS['log']->info('Enhanced content rendered, length: ' . strlen($enhancedContent));
            }
        }
        
        // Now set the template to the standard list view template
        $this->tpl = 'include/ListView/ListViewGeneric.tpl';
        
        // Call parent display to get the standard list view
        $listContent = parent::display(false); // Don't end yet
        
        // Combine enhanced content with list content
        $result = $enhancedContent . $listContent;
        
        // Do not echo here; listViewProcess will handle output
        
        $GLOBALS['log']->info('LeadsListViewSmarty::display() completed, total result length: ' . strlen($result));
        
        return $result;
    }
    
    /**
     * Includes necessary JavaScript and CSS assets for advanced filtering
     *
     * @since 1.0.0
     */
    protected function includeAdvancedFilterAssets()
    {
        global $sugar_config;
        
        // Add Alpine.js - use local file if available, fallback to CDN
        $alpineJs = '<script defer src="themes/SuiteP/js/alpine.min.js"></script>';
        if (!file_exists('themes/SuiteP/js/alpine.min.js')) {
            $alpineJs = '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>';
        }
        
        // Add Bootstrap 5 CSS - prioritize local file
        $bootstrapCss = '';
        if (file_exists('themes/SuiteP/css/bootstrap.min.css')) {
            $bootstrapCss = '<link href="themes/SuiteP/css/bootstrap.min.css" rel="stylesheet">';
        } else {
            // Fallback to CDN with error handling
            $bootstrapCss = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" onerror="console.warn(\'Bootstrap CSS failed to load from CDN\')">';
        }
        
        // Add lead filter component JavaScript
        $filterJs = '<script src="themes/SuiteP/js/components/lead-list-filter.js"></script>';
        
        // Add improved custom filter styles with better integration
        $filterCss = '<style>
            /* Lead Filter Bar Styles */
            .lead-filter-bar {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                background-color: #f8f9fa;
                border-radius: 0.375rem;
            }
            
            /* Filter tags */
            .filter-tag {
                margin: 0.125rem;
                display: inline-flex;
                align-items: center;
            }
            
            /* Badge close button */
            .badge .btn-close {
                font-size: 0.65em;
                margin-left: 0.25rem;
                filter: brightness(0) invert(1);
            }
            
            /* Error message styling */
            .alert-danger {
                border-color: #dc3545;
                background-color: #f8d7da;
                color: #721c24;
            }
            
            /* Table styling improvements */
            .lead-table-container {
                background: white;
                border-radius: 0.375rem;
                border: 1px solid #dee2e6;
                overflow: hidden;
            }
            
            /* Ensure existing SuiteCRM table styles are preserved */
            .list table tr:hover {
                background-color: #f5f5f5;
            }
            
            /* Bootstrap integration with SuiteCRM */
            .form-control, .form-select {
                border-color: #ccc;
                padding: 0.375rem 0.75rem;
            }
            
            .btn {
                padding: 0.375rem 0.75rem;
                border-radius: 0.25rem;
            }
        </style>';
        
        // Add to page header with error handling
        try {
            if (isset($GLOBALS['sugar_config']['additionalHeaderContent'])) {
                $GLOBALS['sugar_config']['additionalHeaderContent'] .= $alpineJs . $bootstrapCss . $filterJs . $filterCss;
            } else {
                $GLOBALS['sugar_config']['additionalHeaderContent'] = $alpineJs . $bootstrapCss . $filterJs . $filterCss;
            }
        } catch (Exception $e) {
            error_log('Failed to include advanced filter assets: ' . $e->getMessage());
        }
    }
    
    /**
     * Adds the advanced filter bar to the Smarty template
     *
     * @since 1.0.0
     */
    protected function addAdvancedFilterBar()
    {
        // Get the filter bar template content
        $filterBarContent = $this->getFilterBarTemplate();
        
        // Assign to Smarty template
        $this->ss->assign('advancedFilterBar', $filterBarContent);
        
        // Add JavaScript for integrating with existing list view
        $integrationJs = '<script>
            document.addEventListener("lead-filters-changed", function(event) {
                // Reload the list view when filters change
                const filters = event.detail.filters;
                console.log("Filters changed:", filters);
                
                // This will be enhanced to actually reload the list view data
                // For now, just log the filter change
                if (typeof sListView !== "undefined") {
                    // Future: Integrate with existing SuiteCRM list view reload mechanism
                    console.log("Would reload list view with filters:", filters);
                }
            });
        </script>';
        
        if (isset($GLOBALS['sugar_config']['additionalHeaderContent'])) {
            $GLOBALS['sugar_config']['additionalHeaderContent'] .= $integrationJs;
        } else {
            $GLOBALS['sugar_config']['additionalHeaderContent'] = $integrationJs;
        }
    }
    
    /**
     * Gets the filter bar template content
     *
     * @return string Filter bar HTML content
     * @since 1.0.0
     */
    protected function getFilterBarTemplate()
    {
        $templatePath = 'themes/SuiteP/tpls/lead-list-filter-bar.tpl';
        
        if (file_exists($templatePath)) {
            return file_get_contents($templatePath);
        }
        
        // Fallback basic filter bar if template not found
        return '<div class="alert alert-info">Advanced filter system not available</div>';
    }

    public function buildExportLink($id = 'export_link')
    {
        global $app_strings;
        global $sugar_config;

        $script = "";
        if (ACLController::checkAccess($this->seed->module_dir, 'export', true)) {
            if ($this->export) {
                $script = parent::buildExportLink($id);
            }
        }

        $script .= "<a href='javascript:void(0)' id='map_listview_top' " .
                    " onclick=\"return sListView.send_form(true, 'jjwg_Maps', " .
                    "'index.php?entryPoint=jjwg_Maps&display_module={$_REQUEST['module']}', " .
                    "'{$app_strings['LBL_LISTVIEW_NO_SELECTED']}')\">{$app_strings['LBL_MAP']}</a>";

        return formLetter::LVSmarty().$script;
    }
}
