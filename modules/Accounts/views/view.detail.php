<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2010 SugarCRM Inc.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 */


/**
 * AccountsViewDetail - Enhanced DetailView with Template Switching Support
 *
 * Extended to support Phase 1, Feature 2, Step 4 enhanced template switching.
 * Conditionally uses enhanced DetailView template when configuration is enabled
 * while maintaining 100% backward compatibility.
 *
 * @package Accounts
 * @subpackage Views
 */
#[\AllowDynamicProperties]
class AccountsViewDetail extends ViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

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
     * display
     * Override the display method to support customization for the buttons that display
     * a popup and allow you to copy the account's address into the selected contacts.
     * The custom_code_billing and custom_code_shipping Smarty variables are found in
     * include/SugarFields/Fields/Address/DetailView.tpl (default).  If it's a English U.S.
     * locale then it'll use file include/SugarFields/Fields/Address/en_us.DetailView.tpl.
     */
    public function display()
    {
        if (empty($this->bean->id)) {
            global $app_strings;
            sugar_die($app_strings['ERROR_NO_RECORD']);
        }

        require_once('modules/AOS_PDF_Templates/formLetter.php');
        formLetter::DVPopupHtml('Accounts');

        $this->dv->process();
        
        if (ACLController::checkAccess('Contacts', 'edit', true)) {
            $push_billing = $this->generatePushCode('billing');
            $push_shipping = $this->generatePushCode('shipping');
        } else {
            $push_billing = '';
            $push_shipping = '';
        }

        $this->ss->assign("custom_code_billing", $push_billing);
        $this->ss->assign("custom_code_shipping", $push_shipping);

        if (empty($this->bean->id)) {
            global $app_strings;
            sugar_die($app_strings['ERROR_NO_RECORD']);
        }
        echo $this->dv->display();
    }

    public function generatePushCode($param)
    {
        global $mod_strings;
        $address_fields = array('street', 'city', 'state', 'postalcode','country');

        $html = '<input class="button" title="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_LABEL'] .
             '" type="button" onclick=\'open_contact_popup("Contacts", 600, 600, "&account_name=' .
             $this->bean->name . '&html=change_address';

        foreach ($address_fields as $value) {
            $field_name = $param.'_address_'.$value;
            $html .= '&primary_address_'.$value.'='.str_replace(array("\rn", "\r", "\n"), array('','','<br>'), urlencode($this->bean->$field_name)) ;
        }

        $html .= '", true, false);\' value="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_TITLE']. '">';
        return $html;
    }
}
