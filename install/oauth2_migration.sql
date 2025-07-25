-- OAuth2 Provider Associations Table Migration
-- 
-- This migration creates the oauth2_user_providers table for storing
-- encrypted OAuth2 tokens and provider associations.
--
-- Compatible with MySQL, MariaDB, PostgreSQL, and SQL Server
-- through SuiteCRM's database abstraction layer
--
-- Author: SuiteCRM Modernization Team
-- Version: 1.0.0
-- Date: 2024-01-15

-- Drop table if exists (for clean installs/reinstalls)
-- Note: In production, you may want to backup data first
DROP TABLE IF EXISTS oauth2_user_providers;

-- Create OAuth2 user providers table
CREATE TABLE oauth2_user_providers (
    -- Primary key
    id VARCHAR(36) NOT NULL PRIMARY KEY,
    
    -- User association (nullable for unlinked OAuth accounts)
    user_id VARCHAR(36) NULL,
    
    -- OAuth provider information
    provider_name VARCHAR(50) NOT NULL,
    provider_user_id VARCHAR(255) NOT NULL,
    
    -- Encrypted token storage
    access_token TEXT NULL,
    refresh_token TEXT NULL,
    
    -- Token lifecycle
    expires_at DATETIME NULL,
    
    -- Audit fields
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    
    -- Indexes for performance
    INDEX idx_user_provider (user_id, provider_name),
    INDEX idx_provider_user (provider_name, provider_user_id),
    INDEX idx_expires_at (expires_at),
    
    -- Foreign key constraint
    CONSTRAINT fk_oauth2_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE
);

-- Add unique constraint to prevent duplicate provider accounts per user
ALTER TABLE oauth2_user_providers 
    ADD CONSTRAINT uq_user_provider UNIQUE (user_id, provider_name);

-- Create index for cleanup operations
CREATE INDEX idx_expires_refresh ON oauth2_user_providers(expires_at, refresh_token);

-- Grant permissions (adjust as needed for your database user)
-- GRANT SELECT, INSERT, UPDATE, DELETE ON oauth2_user_providers TO 'suitecrm_user'@'localhost';

-- Insert initial migration record (optional - for tracking)
-- INSERT INTO migrations (name, version, executed_at) 
-- VALUES ('oauth2_user_providers', '1.0.0', NOW()); 