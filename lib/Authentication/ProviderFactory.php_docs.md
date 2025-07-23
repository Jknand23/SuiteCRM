# ProviderFactory.php Documentation

## Overview

The `ProviderFactory` class is responsible for creating OAuth2 provider instances for external identity providers. It currently supports Google OAuth2 authentication and a generic OAuth2 provider for custom implementations.

## File Location
`lib/Authentication/ProviderFactory.php`

## Key Features
- Google OAuth2 provider support with provider-specific configurations
- Generic OAuth2 provider support for custom implementations
- Secure credential management from SuiteCRM configuration system
- Provider validation and configuration checking
- Environment-specific configuration support

## Supported Providers
- **Google OAuth2**: For Google Workspace and Gmail authentication
- **Generic OAuth2**: For custom OAuth2 provider implementations

## Class Methods

### Public Methods

#### `createProvider(string $providerName, string $redirectUri): AbstractProvider`
Creates and returns a configured OAuth2 provider instance.

**Parameters:**
- `$providerName`: Name of the OAuth2 provider ('google' or 'generic')
- `$redirectUri`: Callback URI for OAuth2 flow completion

**Returns:** Configured OAuth2 provider instance

**Throws:**
- `InvalidArgumentException`: When provider is not supported
- `RuntimeException`: When provider configuration is invalid

#### `isProviderSupported(string $providerName): bool`
Checks if an OAuth2 provider is supported.

#### `isProviderEnabled(string $providerName): bool`
Checks if an OAuth2 provider is enabled in configuration.

#### `getSupportedProviders(): array`
Returns list of all supported OAuth2 providers.

#### `getEnabledProviders(): array`
Returns list of enabled OAuth2 providers.

### Protected Methods

#### `createGoogleProvider(array $config, string $redirectUri): Google`
Creates Google OAuth2 provider instance with specific configuration.

#### `createGenericProvider(array $config, string $redirectUri): GenericProvider`
Creates generic OAuth2 provider instance for custom implementations.

#### `getProviderConfig(string $providerName): array`
Gets configuration for specific OAuth2 provider.

#### `validateProviderCredentials(array $config, array $requiredFields): void`
Validates OAuth2 provider credentials and configuration.

#### `loadProviderConfigurations(): array`
Loads OAuth2 provider configurations from SuiteCRM config system.

## Configuration

The factory expects provider configurations in the SuiteCRM configuration under `$sugar_config['oauth2_external_providers']`:

```php
$sugar_config['oauth2_external_providers'] = [
    'google' => [
        'enabled' => true,
        'client_id' => 'your-google-client-id',
        'client_secret' => 'your-google-client-secret',
        'scopes' => ['openid', 'email', 'profile'],
        'hosted_domain' => null // Optional: restrict to specific Google Workspace domain
    ],
    'generic' => [
        'enabled' => false,
        'client_id' => '',
        'client_secret' => '',
        'authorize_url' => '',
        'token_url' => '',
        'resource_owner_url' => '',
        'scopes' => ['openid', 'email', 'profile']
    ]
];
```

## Usage Example

```php
// Create factory instance
$factory = new ProviderFactory();

// Check if Google provider is supported and enabled
if ($factory->isProviderSupported('google') && $factory->isProviderEnabled('google')) {
    // Create Google provider instance
    $redirectUri = 'https://your-domain.com/index.php?entryPoint=oauth2Callback&provider=google';
    $provider = $factory->createProvider('google', $redirectUri);
    
    // Use provider for OAuth2 authentication flow
    $authorizationUrl = $provider->getAuthorizationUrl();
}
```

## Security Considerations

1. **Credential Storage**: OAuth2 credentials should be stored securely in configuration files that are not committed to version control
2. **Environment Variables**: Production deployments should use environment variables for sensitive credentials
3. **HTTPS Required**: OAuth2 requires secure HTTPS connections in production
4. **Validation**: All provider configurations are validated before use

## Dependencies

- League\OAuth2\Client\Provider\AbstractProvider
- League\OAuth2\Client\Provider\Google
- League\OAuth2\Client\Provider\GenericProvider
- SuiteCRM configuration system

## Related Files

- `lib/Authentication/OAuth2Service.php` - Main OAuth2 coordination service
- `lib/Authentication/SecurityValidator.php` - Security validation for OAuth2 flows
- `lib/Authentication/TokenManager.php` - OAuth2 token management
- `config.oauth2.example.php` - Example configuration file 