<?php
require_once('include/ListView/ListViewSmarty.php');
require_once('modules/AOS_PDF_Templates/formLetter.php');


#[\AllowDynamicProperties]
class LeadsListViewSmarty extends ListViewSmarty
{
    public function __construct()
    {
        parent::__construct();
        $this->targetList = true;
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
        $enhancedTemplate = 'modules/Leads/tpls/ListViewEnhanced.tpl';
        if (file_exists($enhancedTemplate)) {
            $file = $enhancedTemplate;
        }
        
        $ret = parent::process($file, $data, $htmlVar);

        if (!ACLController::checkAccess($this->seed->module_dir, 'export', true) || !$this->export) {
            $this->ss->assign('exportLink', $this->buildExportLink());
        }

        // Add the advanced filter bar to the template
        $this->addAdvancedFilterBar();

        return $ret;
    }
    
    /**
     * Includes necessary JavaScript and CSS assets for advanced filtering
     *
     * @since 1.0.0
     */
    protected function includeAdvancedFilterAssets()
    {
        global $sugar_config;
        
        // Add Alpine.js CDN (for Phase 2 reactive components)
        $alpineJs = '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>';
        
        // Add Bootstrap 5 CSS (enhanced styling for filter components)
        $bootstrapCss = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
        
        // Add lead filter component JavaScript
        $filterJs = '<script src="themes/SuiteP/js/components/lead-list-filter.js"></script>';
        
        // Add custom filter styles
        $filterCss = '<style>
            .lead-filter-bar {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
            }
            .filter-tag {
                margin: 0.125rem;
            }
            .badge .btn-close {
                font-size: 0.65em;
                margin-left: 0.25rem;
            }
        </style>';
        
        // Add to page header
        if (isset($GLOBALS['sugar_config']['additionalHeaderContent'])) {
            $GLOBALS['sugar_config']['additionalHeaderContent'] .= $alpineJs . $bootstrapCss . $filterJs . $filterCss;
        } else {
            $GLOBALS['sugar_config']['additionalHeaderContent'] = $alpineJs . $bootstrapCss . $filterJs . $filterCss;
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
