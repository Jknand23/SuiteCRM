# Phase 1 Review Implementation Checklist

## Critical Issues (Priority 1 - Blocking)

### File Size Violations & Documentation
- [x] Split TokenManager.php (540 lines) into focused components:
  - [x] Create EncryptionService.php for encryption logic
  - [x] Create TokenRepository.php for database operations
  - [ ] Create TokenValidator.php for validation logic
  - [x] Keep TokenManager.php as coordinator (317 lines)
- [ ] Split UserLinker.php (683 lines) into focused components:
  - [ ] Create UserSyncService.php for user synchronization
  - [ ] Create AccountLinkingService.php for OAuth linking
  - [ ] Create UserProvisioningService.php for new user creation
  - [ ] Keep UserLinker.php as coordinator (<300 lines)
- [x] Create TokenManager.php_docs.md
- [ ] Create UserLinker.php_docs.md
- [x] Create documentation for all new split components (EncryptionService, TokenRepository)

### Database & Infrastructure
- [x] Create database migration script for oauth2_user_providers table
- [x] Add migration to install/upgrade process
- [ ] Commit composer.json changes with league/oauth2-client

### Routing & Entry Points
- [x] Register OAuth2 entry points in include/MVC/Controller/entry_point_registry.php
- [x] Create OAuth2Controller.php for handling auth flows
- [x] Create entry point files for OAuth2 flow:
  - [x] oauth2Authorize entry point
  - [x] oauth2Callback entry point

## High Priority (Priority 2 - Core Functionality)

### UI Integration
- [x] Create OAuth2 login buttons template (login-oauth2-buttons.tpl)
- [ ] Integrate OAuth2 buttons into main login.tpl
- [ ] Create user profile OAuth2 management panel
- [ ] Add OAuth2 provider icons/assets
- [ ] Create admin configuration interface for OAuth2 providers

### Testing Infrastructure
- [ ] Create PHPUnit tests for OAuth2Service
- [x] Create PHPUnit tests for SecurityValidator
- [ ] Create PHPUnit tests for ProviderFactory
- [ ] Create integration tests for OAuth2 flow
- [ ] Add UI tests for login button functionality

## Medium Priority (Priority 3 - Enhancement)

### Theme System Implementation
- [ ] Create themes/SuiteP/css/[theme]/custom-properties.scss for each theme
- [ ] Implement Alpine.js theme switcher component
- [ ] Update BuildCommands.php with enhanced build process
- [ ] Add hot-reload development tooling

### API Infrastructure
- [ ] Generate OpenAPI specifications
- [ ] Create interactive Swagger documentation
- [ ] Implement rate limiting middleware
- [ ] Enhance CORS handling
- [ ] Add security headers middleware

### Development Environment
- [ ] Set up Vite configuration
- [ ] Create PHPUnit 10 upgrade scripts
- [ ] Update Robo commands for enhanced builds
- [ ] Configure hot-reload development server

## Code Quality Improvements

### Refactoring Tasks
- [ ] Extract encryption logic to central CryptoUtility class
- [ ] Replace $GLOBALS['log'] with injected PSR-3 logger
- [ ] Consolidate duplicate encryption key derivation
- [ ] Add dependency injection for better testability

### Documentation Tasks
- [ ] Complete all missing _docs.md files
- [ ] Update phase documentation with implementation details
- [ ] Create user guide for OAuth2 setup
- [ ] Document API changes and new endpoints

## Quick Wins (Can be done immediately)

- [ ] Fix file size violations by splitting large classes
- [ ] Add placeholder OAuth2 buttons to login template
- [ ] Create basic database migration script
- [ ] Register entry points for OAuth2 callbacks
- [ ] Add composer dependencies

## Verification Checklist

- [ ] All files under 500 lines
- [ ] All PHP files have paired _docs.md
- [ ] Database migrations tested on fresh install
- [ ] OAuth2 flow works end-to-end with Google
- [ ] UI elements display correctly in all 5 themes
- [ ] All tests passing
- [ ] No regression in existing authentication 