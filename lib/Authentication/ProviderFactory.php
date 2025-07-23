<?php
/**
 * @fileoverview OAuth2 Provider Factory
 * 
 * Factory class for creating OAuth2 provider instances for external
 * identity providers. Handles provider-specific configuration and initialization 
 * while providing a consistent interface for OAuth2 authentication flows.
 * 
 * Key Features:
 * - Support for Google OAuth2 provider with provider-specific configurations
 * - Secure credential management from SuiteCRM configuration system
 * - Extensible architecture for adding new OAuth2 providers
 * - Validation of provider configurations and credentials
 * - Environment-specific configuration support (dev, staging, production)
 * 
 * Supported Providers:
 * - Google OAuth2 (Google Workspace, Gmail)
 * - Generic OAuth2 providers (custom implementations)
 * 
 * Dependencies:
 * - League\OAuth2\Client provider implementations
 * - SuiteCRM configuration system for secure credential storage
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Authentication;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GenericProvider;

class ProviderFactory
{
    /** @var array $config OAuth2 provider configurations */
    private array $config;
    
    /** @var array $supportedProviders List of supported OAuth2 providers */
    private array $supportedProviders = [
        'google',
        'generic'
    ];
    
    /**
     * Constructor loads OAuth2 provider configurations
     */
    public function __construct()
    {
        $this->config = $this->loadProviderConfigurations();
    }
    
    /**
     * Creates OAuth2 provider instance for specified provider
     * 
     * Validates provider configuration, creates appropriate provider instance
     * with credentials and settings, and returns configured provider ready
     * for OAuth2 authentication flow.
     * 
     * @param string $providerName Name of OAuth2 provider (google, generic)
     * @param string $redirectUri Callback URI for OAuth2 flow completion
     * 
     * @return AbstractProvider Configured OAuth2 provider instance
     * 
     * @throws \InvalidArgumentException When provider is not supported
     * @throws \RuntimeException When provider configuration is invalid
     * 
     * @since 1.0.0
     */
    public function createProvider(string $providerName, string $redirectUri): AbstractProvider
    {
        if (!$this->isProviderSupported($providerName)) {
            throw new \InvalidArgumentException("OAuth2 provider '{$providerName}' is not supported");
        }
        
        if (!$this->isProviderEnabled($providerName)) {
            throw new \RuntimeException("OAuth2 provider '{$providerName}' is not enabled");
        }
        
        $providerConfig = $this->getProviderConfig($providerName);
        
        switch ($providerName) {
            case 'google':
                return $this->createGoogleProvider($providerConfig, $redirectUri);
                
            case 'generic':
                return $this->createGenericProvider($providerConfig, $redirectUri);
                
            default:
                throw new \InvalidArgumentException("No factory method for provider '{$providerName}'");
        }
    }
    
    /**
     * Checks if OAuth2 provider is supported by this factory
     * 
     * @param string $providerName Name of OAuth2 provider
     * 
     * @return bool True if provider is supported
     * 
     * @since 1.0.0
     */
    public function isProviderSupported(string $providerName): bool
    {
        return in_array($providerName, $this->supportedProviders, true);
    }
    
    /**
     * Checks if OAuth2 provider is enabled in configuration
     * 
     * @param string $providerName Name of OAuth2 provider
     * 
     * @return bool True if provider is enabled
     * 
     * @since 1.0.0
     */
    public function isProviderEnabled(string $providerName): bool
    {
        return $this->config[$providerName]['enabled'] ?? false;
    }
    
    /**
     * Gets list of all supported OAuth2 providers
     * 
     * @return array Array of supported provider names
     * 
     * @since 1.0.0
     */
    public function getSupportedProviders(): array
    {
        return $this->supportedProviders;
    }
    
    /**
     * Gets list of enabled OAuth2 providers
     * 
     * @return array Array of enabled provider names
     * 
     * @since 1.0.0
     */
    public function getEnabledProviders(): array
    {
        $enabledProviders = [];
        
        foreach ($this->supportedProviders as $provider) {
            if ($this->isProviderEnabled($provider)) {
                $enabledProviders[] = $provider;
            }
        }
        
        return $enabledProviders;
    }
    
    /**
     * Creates Google OAuth2 provider instance
     * 
     * @param array $config Provider configuration
     * @param string $redirectUri Callback URI
     * 
     * @return Google Configured Google OAuth2 provider
     * 
     * @since 1.0.0
     */
    protected function createGoogleProvider(array $config, string $redirectUri): Google
    {
        $this->validateProviderCredentials($config, ['client_id', 'client_secret']);
        
        return new Google([
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'redirectUri' => $redirectUri,
            'scopes' => $config['scopes'] ?? ['openid', 'email', 'profile'],
            'hostedDomain' => $config['hosted_domain'] ?? null,
        ]);
    }
    
    /**
     * Creates generic OAuth2 provider instance
     * 
     * @param array $config Provider configuration
     * @param string $redirectUri Callback URI
     * 
     * @return GenericProvider Configured generic OAuth2 provider
     * 
     * @since 1.0.0
     */
    protected function createGenericProvider(array $config, string $redirectUri): GenericProvider
    {
        $requiredFields = [
            'client_id', 'client_secret', 'authorize_url', 
            'token_url', 'resource_owner_url'
        ];
        
        $this->validateProviderCredentials($config, $requiredFields);
        
        return new GenericProvider([
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'redirectUri' => $redirectUri,
            'urlAuthorize' => $config['authorize_url'],
            'urlAccessToken' => $config['token_url'],
            'urlResourceOwnerDetails' => $config['resource_owner_url'],
            'scopes' => $config['scopes'] ?? ['openid', 'email', 'profile'],
        ]);
    }
    
    /**
     * Gets configuration for specific OAuth2 provider
     * 
     * @param string $providerName Name of OAuth2 provider
     * 
     * @return array Provider configuration array
     * 
     * @throws \RuntimeException When provider configuration not found
     * 
     * @since 1.0.0
     */
    protected function getProviderConfig(string $providerName): array
    {
        if (!isset($this->config[$providerName])) {
            throw new \RuntimeException("Configuration for OAuth2 provider '{$providerName}' not found");
        }
        
        return $this->config[$providerName];
    }
    
    /**
     * Validates OAuth2 provider credentials and configuration
     * 
     * @param array $config Provider configuration
     * @param array $requiredFields Required configuration fields
     * 
     * @throws \RuntimeException When required credentials are missing
     * 
     * @since 1.0.0
     */
    protected function validateProviderCredentials(array $config, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($config[$field])) {
                throw new \RuntimeException("OAuth2 provider configuration missing required field: {$field}");
            }
        }
    }
    
    /**
     * Loads OAuth2 provider configurations from SuiteCRM config system
     * 
     * @return array OAuth2 provider configurations
     * 
     * @since 1.0.0
     */
    protected function loadProviderConfigurations(): array
    {
        global $sugar_config;
        
        return $sugar_config['oauth2_external_providers'] ?? [
            'google' => [
                'enabled' => false,
                'client_id' => '',
                'client_secret' => '',
                'scopes' => ['openid', 'email', 'profile'],
                'hosted_domain' => null
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
    }
} 