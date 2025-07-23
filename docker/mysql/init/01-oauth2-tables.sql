-- OAuth2 User Providers Table for SuiteCRM Docker Setup
-- This script will automatically run when MySQL container is first created

USE suitecrm;

-- Create OAuth2 user providers table for external authentication
CREATE TABLE IF NOT EXISTS oauth2_user_providers (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NULL COMMENT 'SuiteCRM user ID (can be null for unlinked accounts)',
    provider_name VARCHAR(50) NOT NULL COMMENT 'OAuth2 provider name (google, microsoft, github)',
    provider_user_id VARCHAR(255) NOT NULL COMMENT 'User ID from OAuth2 provider',
    access_token TEXT COMMENT 'Encrypted OAuth2 access token',
    refresh_token TEXT COMMENT 'Encrypted OAuth2 refresh token',
    expires_at DATETIME COMMENT 'Token expiration timestamp',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes for performance
    INDEX idx_user_id (user_id),
    INDEX idx_provider_name (provider_name),
    INDEX idx_provider_user_id (provider_user_id),
    UNIQUE KEY unique_provider_user (provider_name, provider_user_id),
    
    -- Foreign key constraint (if users table exists)
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OAuth2 external provider user associations';

-- Insert initial test data (optional, remove in production)
-- INSERT INTO oauth2_user_providers (id, provider_name, provider_user_id) 
-- VALUES ('test-oauth2-1', 'google', 'test-google-user-123');

COMMIT; 