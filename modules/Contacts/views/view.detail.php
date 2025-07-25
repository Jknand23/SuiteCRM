<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


/**
 * ContactsViewDetail - Enhanced DetailView with Template Switching Support
 *
 * Extended to support Phase 1, Feature 2, Step 4 enhanced template switching.
 * Conditionally uses enhanced DetailView template when configuration is enabled
 * while maintaining 100% backward compatibility.
 *
 * @package Contacts
 * @subpackage Views
 */
#[\AllowDynamicProperties]
class ContactsViewDetail extends ViewDetail
{
    /**
     * Enhanced preDisplay method with template switching support
     *
     * Conditionally uses enhanced DetailView template based on configuration
     * while preserving all existing functionality. Falls back to standard
     * template when enhanced templates are disabled.
     *
     * @return void
     */
    public function preDisplay()
    {
        $metadataFile = $this->getMetaDataFile();
        $this->dv = new DetailView2();
        $this->dv->ss =& $this->ss;
        
        // Enhanced template switching logic
        $templatePath = $this->getDetailViewTemplate();
        
        $this->dv->setup($this->module, $this->bean, $metadataFile, $templatePath);
    }
    
    /**
     * Get appropriate DetailView template path
     *
     * Returns enhanced template path when configuration is enabled and
     * enhanced template exists, otherwise returns standard template path.
     *
     * @return string Template file path
     */
    private function getDetailViewTemplate()
    {
        // Check if enhanced templates are enabled
        if ($this->shouldUseEnhancedTemplate()) {
            $enhancedTemplate = 'themes/SuiteP/include/DetailView/DetailView-Enhanced.tpl';
            
            // Verify enhanced template exists before using
            if (file_exists($enhancedTemplate)) {
                return $enhancedTemplate;
            }
            
            // Log warning if enhanced template is configured but missing
            $GLOBALS['log']->warn('Enhanced template configured but not found: ' . $enhancedTemplate);
        }
        
        // Default to standard template
        return get_custom_file_if_exists('include/DetailView/DetailView.tpl');
    }
    
    /**
     * Check if enhanced templates should be used
     *
     * Checks global configuration and module-specific settings to determine
     * if enhanced templates should be used for this view.
     *
     * @return bool True if enhanced templates should be used
     */
    private function shouldUseEnhancedTemplate()
    {
        global $sugar_config;
        
        // Check if enhanced templates are globally enabled
        if (empty($sugar_config['enhanced_templates_enabled'])) {
            return false;
        }
        
        // Check if this module is in the enabled modules list (if specified)
        if (!empty($sugar_config['enhanced_templates_modules'])) {
            return in_array($this->module, $sugar_config['enhanced_templates_modules']);
        }
        
        // If no specific modules list, enable for all modules
        return true;
    }

    /**
     * @see SugarView::display()
     *
     * We are overridding the display method to manipulate the portal information.
     * If portal is not enabled then don't show the portal fields.
     */
    public function display()
    {
        global $sugar_config;

        $aop_portal_enabled = !empty($sugar_config['aop']['enable_portal']) && !empty($sugar_config['aop']['enable_aop']);

        $this->ss->assign("AOP_PORTAL_ENABLED", $aop_portal_enabled);

        require_once('modules/AOS_PDF_Templates/formLetter.php');
        formLetter::DVPopupHtml('Contacts');

        $admin = BeanFactory::newBean('Administration');
        $admin->retrieveSettings();
        if (isset($admin->settings['portal_on']) && $admin->settings['portal_on']) {
            $this->ss->assign("PORTAL_ENABLED", true);
        }
        parent::display();
    }
}
