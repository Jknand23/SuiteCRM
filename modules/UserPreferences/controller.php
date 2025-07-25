<?php
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


#[\AllowDynamicProperties]
class UserPreferencesController extends SugarController
{
    public function action_save_rich_text_preferences()
    {
        $this->view = 'ajax';
        global $current_user;
        if (!empty($current_user)) {
            $height = isset($_REQUEST['height']) ? $_REQUEST['height'] : '325px';
            $width =  isset($_REQUEST['width']) ? $_REQUEST['width'] : '95%';
            $current_user->setPreference('text_editor_height', $height);
            $current_user->setPreference('text_editor_width', $width);
            $current_user->savePreferencesToDB();
            $json = getJSONobj();
            $retArray = array();
            $retArray['height'] = $height;
            $retArray['width'] = $width;
            echo 'result = ' . $json->encode($retArray);
        }
    }

    /**
     * Save theme preference action for Alpine.js theme switcher
     *
     * Handles AJAX requests to save user theme preferences from the theme switcher component.
     * Integrates with existing UserPreference system for compatibility and persistence.
     *
     * @since 1.0.0 - Feature 2 Step 3 Alpine.js Theme Switching
     */
    public function action_SaveThemePreference()
    {
        $this->view = 'ajax';
        header('Content-Type: application/json');
        
        try {
            // Validate request method
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendErrorResponse('Only POST method allowed', 405);
                return;
            }
            
            // Validate user authentication
            global $current_user;
            if (empty($current_user) || empty($current_user->id)) {
                $this->sendErrorResponse('User not authenticated', 401);
                return;
            }
            
            // Validate AJAX request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
                $this->sendErrorResponse('Invalid request type', 400);
                return;
            }
            
            // Get and validate input parameters
            $preferenceName = $this->getInputParameter('preference_name');
            $preferenceValue = $this->getInputParameter('preference_value');
            $category = $this->getInputParameter('category', 'global');
            
            // Validate theme preference specifically
            $validThemes = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
            if ($preferenceName === 'subtheme' && !in_array($preferenceValue, $validThemes, true)) {
                $this->sendErrorResponse('Invalid theme name provided', 400);
                return;
            }
            
            // Save preference using existing UserPreference system
            $current_user->setPreference($preferenceName, $preferenceValue, $category);
            $current_user->savePreferencesToDB();
            
            // Log successful save
            $GLOBALS['log']->info("Theme preference saved: {$preferenceName} = {$preferenceValue} for user {$current_user->id}");
            
            // Send success response
            $this->sendSuccessResponse([
                'preference_name' => $preferenceName,
                'preference_value' => $preferenceValue,
                'category' => $category,
                'timestamp' => time()
            ]);
        } catch (Exception $e) {
            $GLOBALS['log']->error('Theme preference save failed: ' . $e->getMessage());
            $this->sendErrorResponse('Failed to save preference: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get input parameter with validation
     *
     * @param string $name Parameter name
     * @param string $default Default value
     * @return string Parameter value
     */
    private function getInputParameter($name, $default = '')
    {
        $value = $_POST[$name] ?? $default;
        
        // Basic sanitization
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        return $value;
    }
    
    /**
     * Send JSON success response
     *
     * @param array $data Response data
     */
    private function sendSuccessResponse($data = [])
    {
        $response = [
            'success' => true,
            'message' => 'Preference saved successfully',
            'data' => $data
        ];
        
        echo json_encode($response);
    }
    
    /**
     * Send JSON error response
     *
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     */
    private function sendErrorResponse($message, $statusCode = 400)
    {
        http_response_code($statusCode);
        
        $response = [
            'success' => false,
            'error' => $message,
            'timestamp' => time()
        ];
        
        echo json_encode($response);
    }
}
