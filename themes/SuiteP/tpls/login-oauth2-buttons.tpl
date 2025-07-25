{*
/**
 * @fileoverview OAuth2 Login Buttons Template
 * 
 * Displays OAuth2 provider login buttons on the login page. Shows configured
 * providers with appropriate icons and styling integrated with SuiteP theme.
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
*}

{if $oauth2_providers && count($oauth2_providers) > 0}
<div class="oauth2-login-section">
    <div class="oauth2-divider">
        <hr class="oauth2-line" />
        <span class="oauth2-text">{$MOD.LBL_OR_SIGNIN_WITH|default:'Or sign in with'}</span>
        <hr class="oauth2-line" />
    </div>
    
    <div class="oauth2-buttons">
        {foreach from=$oauth2_providers key=provider_key item=provider}
            <a href="index.php?entryPoint=oauth2Authorize&provider={$provider_key}" 
               class="btn btn-lg btn-block oauth2-button oauth2-{$provider_key}">
                <i class="oauth2-icon oauth2-icon-{$provider_key}"></i>
                <span>{$provider.label|default:$provider_key}</span>
            </a>
        {/foreach}
    </div>
</div>

<style>
{literal}
.oauth2-login-section {
    margin-top: 30px;
}

.oauth2-divider {
    display: flex;
    align-items: center;
    margin: 20px 0;
}

.oauth2-line {
    flex: 1;
    height: 1px;
    background: #ddd;
    border: none;
}

.oauth2-text {
    padding: 0 15px;
    color: #666;
    font-size: 14px;
}

.oauth2-buttons {
    margin-top: 15px;
}

.oauth2-button {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    padding: 12px 20px;
    border: 1px solid #ddd;
    background: #fff;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.oauth2-button:hover {
    background: #f5f5f5;
    border-color: #ccc;
    text-decoration: none;
}

.oauth2-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

/* Provider-specific styles */
.oauth2-google {
    border-color: #4285f4;
    color: #4285f4;
}

.oauth2-google:hover {
    background: #f0f5ff;
    border-color: #2b5ec6;
}

.oauth2-icon-google {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PHBhdGggZmlsbD0iI0VBNDMzNSIgZD0iTTI0IDkuNWMzLjU0IDAgNi43MSAxLjIyIDkuMjEgMy42bDYuODUtNi44NUMzNS45IDIuMzggMzAuNDcgMCAyNCAwIDExLjY0IDAgMi4xIDcuOTQuMDcgMTguMjZsNy44MiA2LjA1QzkuNTYgMTcuMDMgMTYuMTkgOS41IDI0IDkuNXoiLz48cGF0aCBmaWxsPSIjNDI4NUY0IiBkPSJNNDYuOTggMjQuNTVjMC0xLjU3LS4xNS0zLjA5LS4zOC00LjU1SDI0djkuMDJoMTIuOTRjLS41OCAyLjk2LTIuMjYgNS40OC00Ljc4IDcuMThsNy43NSA2YzQuNTEtNC4xOCA3LjA5LTEwLjM2IDcuMDktMTcuNjV6Ii8+PHBhdGggZmlsbD0iI0ZCQkMwNSIgZD0iTTEwLjUzIDI4LjU5Yy0uNDgtMS40NS0uNzYtMi45OS0uNzYtNC41OXMuMjctMy4xNC43Ni00LjU5bC03LjktNi4xMUM5MiAxNi40NyAwIDE5Ljg5IDAgMjRzLjkyIDcuNTMgMi42MiA5LjdsMTAuNTMtNS4xMXoiLz48cGF0aCBmaWxsPSIjMzRBODUzIiBkPSJNMjQgNDhjNi40OCAwIDExLjkzLTIuMTMgMTUuODktNS44MWwtNy43NS02Yy0yLjE1IDEuNDUtNC44NCAyLjMtOC4xMyAyLjMtNy43OSAwLTE0LjQyLTYuNDktMTYuMjYtMTMuOTZsLTcuOSA2LjA1QzUuOTIgNDAuMDUgMTQuMjggNDggMjQgNDh6Ii8+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTAgMGg0OHY0OEgweiIvPjwvc3ZnPg==');
}

.oauth2-microsoft {
    border-color: #0078d4;
    color: #0078d4;
}

.oauth2-microsoft:hover {
    background: #f0f8ff;
    border-color: #005a9e;
}

.oauth2-github {
    border-color: #24292e;
    color: #24292e;
}

.oauth2-github:hover {
    background: #f6f8fa;
    border-color: #1a1e22;
}
{/literal}
</style>
{/if} 