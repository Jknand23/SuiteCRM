-- OAuth2 User Providers Table Creation Script
-- This table stores associations between SuiteCRM users and OAuth2 provider accounts
-- Supports secure linking of external identity providers to internal user accounts

CREATE TABLE IF NOT EXISTS oauth2_user_providers (
    id VARCHAR(36) NOT NULL PRIMARY KEY,
    user_id VARCHAR(36) NULL,
    provider_name VARCHAR(50) NOT NULL,
    provider_user_id VARCHAR(255) NOT NULL,
    access_token TEXT NULL,
    refresh_token TEXT NULL,
    expires_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes for performance
    INDEX idx_oauth2_user_provider (user_id, provider_name),
    INDEX idx_oauth2_provider_user (provider_name, provider_user_id),
    INDEX idx_oauth2_expires (expires_at),
    
    -- Foreign key constraint (optional - depends on SuiteCRM database configuration)
    -- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Unique constraint to prevent duplicate provider associations
    UNIQUE KEY unique_user_provider (user_id, provider_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add comments for documentation
ALTER TABLE oauth2_user_providers 
COMMENT = 'OAuth2 external provider associations for SuiteCRM users';

ALTER TABLE oauth2_user_providers 
MODIFY COLUMN id VARCHAR(36) NOT NULL COMMENT 'Unique record identifier (UUID)',
MODIFY COLUMN user_id VARCHAR(36) NULL COMMENT 'SuiteCRM user ID (NULL for unlinked accounts)',
MODIFY COLUMN provider_name VARCHAR(50) NOT NULL COMMENT 'OAuth2 provider name (google, microsoft, etc.)',
MODIFY COLUMN provider_user_id VARCHAR(255) NOT NULL COMMENT 'User ID from OAuth2 provider',
MODIFY COLUMN access_token TEXT NULL COMMENT 'Encrypted OAuth2 access token',
MODIFY COLUMN refresh_token TEXT NULL COMMENT 'Encrypted OAuth2 refresh token',
MODIFY COLUMN expires_at DATETIME NULL COMMENT 'Access token expiration timestamp',
MODIFY COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
MODIFY COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record last update timestamp'; 