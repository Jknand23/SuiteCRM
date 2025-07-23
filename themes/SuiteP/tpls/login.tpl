{*
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
*}
<script type='text/javascript'>
    var LBL_LOGIN_SUBMIT = '{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}';
    var LBL_REQUEST_SUBMIT = '{sugar_translate module="Users" label="LBL_REQUEST_SUBMIT"}';
    var LBL_SHOWOPTIONS = '{sugar_translate module="Users" label="LBL_SHOWOPTIONS"}';
    var LBL_HIDEOPTIONS = '{sugar_translate module="Users" label="LBL_HIDEOPTIONS"}';
</script>

<!-- Start login container -->

<div class="p_login">

	<div class="p_login_top">
		
		<a title="SuiteCRM" href="https://www.suitecrm.com">SuiteCRM</a>
		
	</div>
    
    <div class="p_login_middle">
        {if $LOGIN_ERROR_MESSAGE}
            <p align='center' class='error'>{$LOGIN_ERROR_MESSAGE}</p>
        {/if}


    <div id="loginform">
        
        <form class="form-signin" role="form" action="index.php" method="post" name="DetailView" id="form"
              onsubmit="return document.getElementById('cant_login').value == ''" autocomplete="off">
            <div class="companylogo">{$LOGIN_IMAGE}</div>
        <span class="error" id="browser_warning" style="display:none">
            {sugar_translate label="WARN_BROWSER_VERSION_WARNING"}
        </span>
		<span class="error" id="ie_compatibility_mode_warning" style="display:none">
		{sugar_translate label="WARN_BROWSER_IE_COMPATIBILITY_MODE_WARNING"}
		</span>
            {if $LOGIN_ERROR !=''}
                <span class="error">{$LOGIN_ERROR}</span>
                {if $WAITING_ERROR !=''}
                    <span class="error">{$WAITING_ERROR}</span>
                {/if}
            {else}
                <span id='post_error' class="error"></span>
            {/if}
            <input type="hidden" name="module" value="Users">
            <input type="hidden" name="action" value="Authenticate">
            <input type="hidden" name="return_module" value="Users">
            <input type="hidden" name="return_action" value="Login">
            <input type="hidden" id="cant_login" name="cant_login" value="">
            {foreach from=$LOGIN_VARS key=key item=var}
                <input type="hidden" name="{$key}" value="{$var}">
            {/foreach}
            {if !empty($SELECT_LANGUAGE)}
                <div class="login-language-chooser" >
                    {sugar_translate module="Users" label="LBL_LANGUAGE"}:
                    <select name='login_language' onchange="switchLanguage(this.value)">{$SELECT_LANGUAGE}</select>
                </div>
            {/if}
            <br>
            <div class="input-group">
                <input type="text" class="form-control"
                       placeholder="{sugar_translate module="Users" label="LBL_USER_NAME"}" required autofocus
                       tabindex="1" id="user_name" name="user_name" value='{$LOGIN_USER_NAME}' autocomplete="off">
            </div>
            <br>
            <div class="input-group">
                <input type="password" class="form-control"
                       placeholder="{sugar_translate module="Users" label="LBL_PASSWORD"}" tabindex="2"
                       id="username_password" name="username_password" value='{$LOGIN_PASSWORD}' autocomplete="off">
            </div>
            <br>
            <input id="bigbutton" class="btn btn-lg btn-primary btn-block" type="submit"
                   title="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_TITLE"}" tabindex="3" name="Login"
                   value="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_LABEL"}">
            
            <!-- OAuth2/SSO Login Options -->
            <div class="oauth2-login-section" style="margin-top: 20px; margin-bottom: 20px;">
                <div style="text-align: center; margin-bottom: 15px;">
                    <span style="color: #666; font-size: 14px;">or sign in with</span>
                </div>
                
                <!-- Google OAuth2 Login -->
                <a href="index.php?entryPoint=oauth2Authorize&provider=google" 
                   class="btn btn-outline-danger btn-block oauth2-btn" 
                   style="margin-bottom: 10px; padding: 10px; border: 1px solid #dc3545; color: #dc3545; background: white; text-decoration: none;">
                    <svg width="18" height="18" style="margin-right: 8px; vertical-align: middle;" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Sign in with Google
                </a>
            </div>
            
            <div id="forgotpasslink" style="cursor: pointer; display:{$DISPLAY_FORGOT_PASSWORD_FEATURE};"
                 onclick='toggleDisplay("forgot_password_dialog");'>
                <a href='javascript:void(0)'>{sugar_translate module="Users" label="LBL_LOGIN_FORGOT_PASSWORD"}</a>
            </div>
        </form>
        
        <form class="form-signin passform" role="form" action="index.php" method="post" name="DetailView" id="form" name="fp_form" id="fp_form" autocomplete="off">
            <div id="forgot_password_dialog" style="display:none">
                <input type="hidden" name="entryPoint" value="GeneratePassword">
                <div id="generate_success" class='error' style="display:inline;"></div>
                <br>
                <div class="input-group">
                    {*<span class="input-group-addon logininput glyphicon glyphicon-user"></span>*}
                    <input type="text" class="form-control" size='26' id="fp_user_name" name="fp_user_name"
                           value='{$LOGIN_USER_NAME}'
                           placeholder="{sugar_translate module="Users" label="LBL_USER_NAME"}" autocomplete="off">
                </div>
                <br>
                <div class="input-group">
                    {*<span class="input-group-addon logininput glyphicon glyphicon-envelope"></span>*}
                    <input type="text" class="form-control" size='26' id="fp_user_mail" name="fp_user_mail" value=''
                           placeholder="{sugar_translate module="Users" label="LBL_EMAIL"}" autocomplete="off">
                </div>
                <br>
                {$CAPTCHA}
                <div id='wait_pwd_generation'></div>
                <input title="Email Temp Password" class="button  btn-block" type="button" style="display:inline"
                       onclick="validateAndSubmit(); return document.getElementById('cant_login').value == ''"
                       id="generate_pwd_button" name="fp_login"
                       value="{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}" autocomplete="off">
            </div>
        </form>
        
    </div>
    </div>
    
    <div class="p_login_bottom">

    		<a id="admin_options">&copy; Supercharged by SuiteCRM</a>
            <a id="powered_by">&copy; Powered By SugarCRM</a>
    	
	</div>
    
</div>
<!-- End login container -->



