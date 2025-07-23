<?php
/**
 * @fileoverview OAuth2 User Account Linker
 * 
 * Service for linking OAuth2 provider accounts with SuiteCRM user accounts.
 * Handles user creation, account linking, and account management while maintaining
 * data integrity and providing secure association between external identity
 * providers and internal SuiteCRM user records.
 * 
 * Key Features:
 * - Automatic user account creation from OAuth2 provider information
 * - Secure linking of existing SuiteCRM users to OAuth2 providers
 * - User information synchronization from OAuth2 providers
 * - Account unlinking and provider management
 * - Duplicate account detection and prevention
 * - Comprehensive user preference and profile management
 * 
 * Account Linking Strategy:
 * - Email-based account matching for existing user detection
 * - Secure provider association with unique constraints
 * - User preference preservation during linking process
 * - Fallback handling for incomplete provider information
 * - Audit trail for all user account operations
 * 
 * Dependencies:
 * - SuiteCRM User bean for user management
 * - BeanFactory for object creation and retrieval
 * - SuiteCRM database layer for provider associations
 * - SuiteCRM user preference system for settings storage
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Authentication;

class UserLinker
{
    /** @var string $tableName Database table for OAuth2 provider associations */
    private string $tableName = 'oauth2_user_providers';
    
    /** @var array $requiredUserFields Required user fields for account creation */
    private array $requiredUserFields = ['email', 'first_name', 'last_name'];
    
    /** @var array $syncableUserFields User fields that can be synchronized */
    private array $syncableUserFields = [
        'first_name', 'last_name', 'title', 'department', 'phone_work'
    ];
    
    /**
     * Links or creates user account from OAuth2 provider information
     * 
     * Attempts to find existing SuiteCRM user by email address, creates new
     * user if not found, and establishes secure association with OAuth2 provider.
     * 
     * @param array $userInfo User information from OAuth2 provider
     * @param string $providerName OAuth2 provider name
     * 
     * @return string SuiteCRM user ID
     * 
     * @throws \RuntimeException When user creation or linking fails
     * 
     * @since 1.0.0
     */
    public function linkOrCreateUser(array $userInfo, string $providerName): string
    {
        try {
            // Extract email from provider user info
            $email = $this->extractEmailFromUserInfo($userInfo, $providerName);
            
            if (!$email) {
                throw new \RuntimeException('Email address not available from OAuth2 provider');
            }
            
            // Check if user already exists by email
            $existingUser = $this->findUserByEmail($email);
            
            if ($existingUser) {
                $userId = $existingUser->id;
                
                // Check if this provider is already linked to this user
                if ($this->isProviderLinked($userId, $providerName)) {
                    $this->updateProviderAssociation($userId, $providerName, $userInfo);
                } else {
                    $this->createProviderAssociation($userId, $providerName, $userInfo);
                }
                
                // Optionally sync user information from provider
                $this->syncUserInformation($existingUser, $userInfo, $providerName);
                
                $this->logUserEvent('user_linked', [
                    'user_id' => $userId,
                    'provider' => $providerName,
                    'provider_user_id' => $this->extractProviderUserId($userInfo, $providerName),
                    'email' => $email
                ]);
                
                return $userId;
                
            } else {
                // Create new user account
                $newUser = $this->createUserFromProviderInfo($userInfo, $providerName);
                $userId = $newUser->id;
                
                // Create provider association
                $this->createProviderAssociation($userId, $providerName, $userInfo);
                
                $this->logUserEvent('user_created', [
                    'user_id' => $userId,
                    'provider' => $providerName,
                    'provider_user_id' => $this->extractProviderUserId($userInfo, $providerName),
                    'email' => $email
                ]);
                
                return $userId;
            }
            
        } catch (\Exception $e) {
            $this->logUserEvent('user_link_failed', [
                'provider' => $providerName,
                'provider_user_info' => $this->sanitizeUserInfoForLogging($userInfo),
                'error' => $e->getMessage()
            ]);
            throw new \RuntimeException('Failed to link or create user account: ' . $e->getMessage());
        }
    }
    
    /**
     * Links existing SuiteCRM user to OAuth2 provider
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * @param array $userInfo OAuth2 user information
     * 
     * @return bool True if linking successful
     * 
     * @since 1.0.0
     */
    public function linkExistingUser(string $userId, string $providerName, array $userInfo): bool
    {
        try {
            // Verify user exists
            $user = \BeanFactory::newBean('Users');
            if (!$user->retrieve($userId)) {
                throw new \RuntimeException("User with ID {$userId} not found");
            }
            
            // Check if provider is already linked
            if ($this->isProviderLinked($userId, $providerName)) {
                throw new \RuntimeException("Provider {$providerName} is already linked to this user");
            }
            
            // Create provider association
            $this->createProviderAssociation($userId, $providerName, $userInfo);
            
            $this->logUserEvent('existing_user_linked', [
                'user_id' => $userId,
                'provider' => $providerName,
                'provider_user_id' => $userInfo['id'] ?? 'unknown'
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            $this->logUserEvent('existing_user_link_failed', [
                'user_id' => $userId,
                'provider' => $providerName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Checks if OAuth2 provider is linked to user
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * 
     * @return bool True if provider is linked to user
     * 
     * @since 1.0.0
     */
    public function isProviderLinked(string $userId, string $providerName): bool
    {
        $db = \DBManagerFactory::getInstance();
        $query = "SELECT id FROM {$this->tableName} WHERE user_id = " . $db->quoted($userId) . 
                 " AND provider_name = " . $db->quoted($providerName) . " LIMIT 1";
        $result = $db->query($query);
        
        return $db->fetchByAssoc($result) !== false;
    }
    
    /**
     * Removes OAuth2 provider link from user account
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * 
     * @return bool True if unlinking successful
     * 
     * @since 1.0.0
     */
    public function unlinkProvider(string $userId, string $providerName): bool
    {
        try {
            $db = \DBManagerFactory::getInstance();
            $query = "DELETE FROM {$this->tableName} WHERE user_id = " . $db->quoted($userId) . 
                     " AND provider_name = " . $db->quoted($providerName);
            $result = $db->query($query);
            
            $success = $db->getAffectedRowCount($result) > 0;
            
            if ($success) {
                $this->logUserEvent('provider_unlinked', [
                    'user_id' => $userId,
                    'provider' => $providerName
                ]);
            }
            
            return $success;
            
        } catch (\Exception $e) {
            $this->logUserEvent('provider_unlink_failed', [
                'user_id' => $userId,
                'provider' => $providerName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Gets all OAuth2 providers linked to user
     * 
     * @param string $userId SuiteCRM user ID
     * 
     * @return array Array of linked provider information
     * 
     * @since 1.0.0
     */
    public function getLinkedProviders(string $userId): array
    {
        $db = \DBManagerFactory::getInstance();
        $query = "SELECT provider_name, provider_user_id, created_at FROM {$this->tableName} WHERE user_id = " . $db->quoted($userId);
        $result = $db->query($query);
        
        $providers = [];
        while ($row = $db->fetchByAssoc($result)) {
            $providers[] = $row;
        }
        
        return $providers;
    }
    
    /**
     * Finds SuiteCRM user by email address
     * 
     * @param string $email Email address to search for
     * 
     * @return \User|null User object or null if not found
     * 
     * @since 1.0.0
     */
    protected function findUserByEmail(string $email): ?\User
    {
        $db = \DBManagerFactory::getInstance();
        
        // Query to find user by email using the email_addresses and email_addr_bean_rel tables
        $query = "SELECT u.* FROM users u 
                  INNER JOIN email_addr_bean_rel eabr ON u.id = eabr.bean_id 
                  INNER JOIN email_addresses ea ON eabr.email_address_id = ea.id 
                  WHERE eabr.bean_module = 'Users' 
                  AND eabr.deleted = 0 
                  AND ea.email_address = " . $db->quoted($email) . " 
                  AND u.deleted = 0 
                  LIMIT 1";
        
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        
        if ($row) {
            $user = \BeanFactory::newBean('Users');
            $user->populateFromRow($row);
            return $user;
        }
        
        return null;
    }
    
    /**
     * Creates new SuiteCRM user from OAuth2 provider information
     * 
     * @param array $userInfo OAuth2 user information
     * @param string $providerName OAuth2 provider name
     * 
     * @return \User Created user object
     * 
     * @throws \RuntimeException When user creation fails
     * 
     * @since 1.0.0
     */
    protected function createUserFromProviderInfo(array $userInfo, string $providerName): \User
    {
        $user = \BeanFactory::newBean('Users');
        
        // Map provider user info to SuiteCRM user fields
        $userData = $this->mapProviderInfoToUserData($userInfo, $providerName);
        
        // Validate required fields
        $this->validateUserData($userData);
        
        // Extract email before setting other properties
        $email = $userData['email1'] ?? '';
        unset($userData['email1']); // Remove email1 as it's not a direct user field
        
        // Set user properties
        foreach ($userData as $field => $value) {
            if (property_exists($user, $field)) {
                $user->$field = $value;
            }
        }
        
        // Set additional properties for OAuth2 users
        $user->status = 'Active';
        $user->external_auth_only = 1; // Indicate this user uses external authentication
        $user->user_name = $this->generateUniqueUsername($email);
        
        // Generate a secure random password (won't be used for login)
        $user->user_hash = \User::getPasswordHash(bin2hex(random_bytes(32)));
        
        // Save user
        $user->save();
        
        if (empty($user->id)) {
            throw new \RuntimeException('Failed to create user account');
        }
        
        // Now save the email address using SuiteCRM's email system
        if (!empty($email)) {
            require_once 'include/SugarEmailAddress/SugarEmailAddress.php';
            $sea = new \SugarEmailAddress();
            $sea->addAddress($email, true); // true = primary
            $sea->save($user->id, 'Users');
        }
        
        return $user;
    }
    
    /**
     * Synchronizes user information from OAuth2 provider
     * 
     * @param \User $user SuiteCRM user object
     * @param array $userInfo OAuth2 user information
     * @param string $providerName OAuth2 provider name
     * 
     * @return bool True if synchronization successful
     * 
     * @since 1.0.0
     */
    protected function syncUserInformation(\User $user, array $userInfo, string $providerName): bool
    {
        try {
            $userData = $this->mapProviderInfoToUserData($userInfo, $providerName);
            $hasChanges = false;
            
            foreach ($this->syncableUserFields as $field) {
                if (isset($userData[$field]) && $user->$field !== $userData[$field]) {
                    $user->$field = $userData[$field];
                    $hasChanges = true;
                }
            }
            
            if ($hasChanges) {
                $user->save();
                
                $this->logUserEvent('user_info_synced', [
                    'user_id' => $user->id,
                    'provider' => $providerName,
                    'synced_fields' => array_keys(array_intersect_key($userData, array_flip($this->syncableUserFields)))
                ]);
            }
            
            return true;
            
        } catch (\Exception $e) {
            $this->logUserEvent('user_sync_failed', [
                'user_id' => $user->id,
                'provider' => $providerName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Creates OAuth2 provider association record
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * @param array $userInfo OAuth2 user information
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    protected function createProviderAssociation(string $userId, string $providerName, array $userInfo): void
    {
        $db = \DBManagerFactory::getInstance();
        
        $associationData = [
            'id' => create_guid(),
            'user_id' => $userId,
            'provider_name' => $providerName,
            'provider_user_id' => $this->extractProviderUserId($userInfo, $providerName),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Build the query with proper escaping
        $columns = array();
        $values = array();
        
        foreach ($associationData as $column => $value) {
            $columns[] = $column;
            if ($value === null) {
                $values[] = 'NULL';
            } else {
                $values[] = $db->quoted($value);
            }
        }
        
        $columnsStr = implode(', ', $columns);
        $valuesStr = implode(', ', $values);
        
        $query = "INSERT INTO {$this->tableName} ({$columnsStr}) VALUES ({$valuesStr})";
        $result = $db->query($query);
        
        if (!$result) {
            throw new \RuntimeException('Failed to create provider association: ' . $db->lastError());
        }
    }
    
    /**
     * Updates existing OAuth2 provider association
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * @param array $userInfo OAuth2 user information
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    protected function updateProviderAssociation(string $userId, string $providerName, array $userInfo): void
    {
        $db = \DBManagerFactory::getInstance();
        
        $providerId = $this->extractProviderUserId($userInfo, $providerName);
        $updatedAt = date('Y-m-d H:i:s');
        
        $query = "UPDATE {$this->tableName} SET provider_user_id = " . $db->quoted($providerId) . 
                 ", updated_at = " . $db->quoted($updatedAt) . 
                 " WHERE user_id = " . $db->quoted($userId) . 
                 " AND provider_name = " . $db->quoted($providerName);
        
        $result = $db->query($query);
        
        if (!$result) {
            throw new \RuntimeException('Failed to update provider association: ' . $db->lastError());
        }
    }
    
    /**
     * Maps OAuth2 provider user information to SuiteCRM user data
     * 
     * @param array $userInfo OAuth2 user information
     * @param string $providerName OAuth2 provider name
     * 
     * @return array Mapped user data for SuiteCRM
     * 
     * @since 1.0.0
     */
    protected function mapProviderInfoToUserData(array $userInfo, string $providerName): array
    {
        $userData = [];
        
        switch ($providerName) {
            case 'google':
                $userData = [
                    'email1' => $userInfo['email'] ?? '',
                    'first_name' => $userInfo['given_name'] ?? '',
                    'last_name' => $userInfo['family_name'] ?? '',
                    'title' => $userInfo['title'] ?? '',
                ];
                break;
                
            case 'microsoft':
                $userData = [
                    'email1' => $userInfo['mail'] ?? $userInfo['userPrincipalName'] ?? '',
                    'first_name' => $userInfo['givenName'] ?? '',
                    'last_name' => $userInfo['surname'] ?? '',
                    'title' => $userInfo['jobTitle'] ?? '',
                    'department' => $userInfo['department'] ?? '',
                    'phone_work' => $userInfo['businessPhones'][0] ?? '',
                ];
                break;
                
            case 'github':
                $userData = [
                    'email1' => $userInfo['email'] ?? '',
                    'first_name' => $this->parseNamePart($userInfo['name'] ?? '', 'first'),
                    'last_name' => $this->parseNamePart($userInfo['name'] ?? '', 'last'),
                ];
                break;
                
            default:
                // Generic mapping for custom providers
                $userData = [
                    'email1' => $userInfo['email'] ?? $userInfo['mail'] ?? '',
                    'first_name' => $userInfo['first_name'] ?? $userInfo['given_name'] ?? '',
                    'last_name' => $userInfo['last_name'] ?? $userInfo['family_name'] ?? $userInfo['surname'] ?? '',
                    'title' => $userInfo['title'] ?? $userInfo['job_title'] ?? '',
                ];
                break;
        }
        
        return array_filter($userData); // Remove empty values
    }
    
    /**
     * Extracts email address from OAuth2 provider user information
     * 
     * @param array $userInfo OAuth2 user information
     * @param string $providerName OAuth2 provider name
     * 
     * @return string|null Email address or null if not found
     * 
     * @since 1.0.0
     */
    protected function extractEmailFromUserInfo(array $userInfo, string $providerName): ?string
    {
        $emailFields = ['email', 'mail', 'emailAddress', 'userPrincipalName'];
        
        foreach ($emailFields as $field) {
            if (!empty($userInfo[$field])) {
                return $userInfo[$field];
            }
        }
        
        return null;
    }

    /**
     * Extracts the provider user ID from the user info array.
     * 
     * @param array $userInfo OAuth2 user information
     * @param string $providerName OAuth2 provider name
     * 
     * @return string The extracted provider user ID
     * 
     * @since 1.0.0
     */
    protected function extractProviderUserId(array $userInfo, string $providerName): string
    {
        switch ($providerName) {
            case 'google':
                return $userInfo['sub'] ?? $userInfo['id'] ?? '';
            case 'microsoft':
                return $userInfo['id'] ?? '';
            case 'github':
                return $userInfo['id'] ?? '';
            default:
                return $userInfo['id'] ?? $userInfo['sub'] ?? '';
        }
    }
    
    /**
     * Validates user data for required fields
     * 
     * @param array $userData User data to validate
     * 
     * @throws \RuntimeException When required fields are missing
     * 
     * @since 1.0.0
     */
    protected function validateUserData(array $userData): void
    {
        foreach ($this->requiredUserFields as $field) {
            $mappedField = $field === 'email' ? 'email1' : $field;
            
            if (empty($userData[$mappedField])) {
                throw new \RuntimeException("Required user field '{$field}' is missing from OAuth2 provider");
            }
        }
    }
    
    /**
     * Generates unique username from email address
     * 
     * @param string $email Email address
     * 
     * @return string Unique username
     * 
     * @since 1.0.0
     */
    protected function generateUniqueUsername(string $email): string
    {
        $baseUsername = substr($email, 0, strpos($email, '@'));
        $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $baseUsername);
        
        $username = $baseUsername;
        $counter = 1;
        
        // Ensure username is unique
        $user = \BeanFactory::newBean('Users');
        while (!empty($user->get_full_list('', "users.user_name = " . $user->db->quoted($username)))) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
    
    /**
     * Parses name into first and last name parts
     * 
     * @param string $fullName Full name string
     * @param string $part 'first' or 'last'
     * 
     * @return string Name part
     * 
     * @since 1.0.0
     */
    protected function parseNamePart(string $fullName, string $part): string
    {
        $nameParts = explode(' ', trim($fullName));
        
        if ($part === 'first') {
            return $nameParts[0] ?? '';
        } elseif ($part === 'last') {
            return isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
        }
        
        return '';
    }
    
    /**
     * Sanitizes user info for logging purposes
     * 
     * @param array $userInfo OAuth2 user information
     * 
     * @return array Sanitized user information
     * 
     * @since 1.0.0
     */
    protected function sanitizeUserInfoForLogging(array $userInfo): array
    {
        $sensitiveFields = ['access_token', 'refresh_token', 'password', 'secret'];
        
        $sanitized = $userInfo;
        foreach ($sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '[REDACTED]';
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Logs user account events for audit purposes
     * 
     * @param string $eventType Type of user event
     * @param array $context Additional context information
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    protected function logUserEvent(string $eventType, array $context): void
    {
        $GLOBALS['log']->info("OAuth2 User Event: {$eventType}", $context);
    }
} 