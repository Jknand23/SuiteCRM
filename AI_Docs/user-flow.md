# SuiteCRM User Flow Documentation

## Overview

This document defines the user journeys through the modernized SuiteCRM application, detailing how different user types interact with the six enhanced features within their existing workflows. Each user type has distinct entry points and task-focused workflows that leverage the modernized capabilities.

## User Types & Access Levels

### 1. Regular Users
- **Primary Role**: Day-to-day CRM operations
- **Access**: Standard modules, personal dashboard, assigned records
- **Typical Tasks**: Lead management, contact updates, activity tracking

### 2. Administrator
- **Primary Role**: System configuration and user management
- **Access**: Full system access, administration panel, user management
- **Typical Tasks**: System setup, user permissions, module configuration

### 3. Group Users
- **Primary Role**: Team-based collaboration and shared resources
- **Access**: Group-specific records, shared dashboards, team campaigns
- **Typical Tasks**: Collaborative campaign management, shared lead pools

### 4. Portal API Users
- **Primary Role**: External system integration and data exchange
- **Access**: API endpoints, programmatic data creation/retrieval
- **Typical Tasks**: Automated lead creation, data synchronization

## Authentication & Session Management

### Login Experience Flow

```
1. User Access → Login Page
2. Authentication Options:
   ├── Traditional Username/Password
   └── OAuth2/SSO Integration (NEW)
       ├── Google OAuth
       ├── Corporate SSO
       └── External Identity Provider
3. Authentication Validation
4. Session Establishment
5. Role-Based Dashboard Redirect
```

### OAuth2/SSO Integration Points

- **Initial Authentication**: Session start with external provider
- **Session Renewal**: Token refresh during extended sessions
- **Re-authentication**: Required for sensitive operations
- **Fallback**: Traditional login if OAuth fails

## User Journey Workflows

### Workflow 1: Marketing Manager - Campaign-Centric Journey

**User Type**: Regular User / Group User  
**Entry Point**: Dashboard → Campaign Management

```
1. Login (OAuth2/SSO)
   ↓
2. Dashboard Landing
   ├── Campaign Progress Widget (NEW) - Quick metrics overview
   ├── Real-time Client Message Notifications (NEW) - Urgent alerts
   └── Standard dashlets (pipeline, activities)
   ↓
3. Campaign Management
   ├── View Active Campaigns
   ├── Campaign Specific Notes (NEW) - Rich text editing for strategy docs
   └── Monitor campaign performance
   ↓
4. Lead Management Integration
   ├── Interactive Lead List View (NEW) - Filter "Leads from [Campaign X]"
   ├── Advanced filtering by campaign performance
   └── Lead status updates
   ↓
5. External Integration
   ├── API-Generated Leads (NEW) - Automated from marketing channels
   └── Real-time notifications for new campaign leads
```

### Workflow 2: Sales Representative - Lead-Centric Journey

**User Type**: Regular User  
**Entry Point**: Dashboard → Lead Management

```
1. Login (OAuth2/SSO)
   ↓
2. Dashboard Landing
   ├── Real-time Client Message Notifications (NEW) - Customer inquiries
   ├── Lead pipeline dashlets
   └── Activity feeds
   ↓
3. Lead List Management
   ├── Interactive Lead List View (NEW) - Customizable columns
   ├── Advanced Filtering (NEW):
   │   ├── "Leads with no activity in last X days"
   │   ├── "Leads where Industry is Marketing/Advertising"
   │   └── Custom filter combinations
   └── Lead qualification and assignment
   ↓
4. Lead Detail Management
   ├── Contact information updates
   ├── Activity tracking
   └── Campaign association review
   ↓
5. Opportunity Conversion
   ├── Lead-to-opportunity conversion
   └── Account/contact creation
```

### Workflow 3: Administrator - System Management Journey

**User Type**: Administrator  
**Entry Point**: Administration Panel

```
1. Login (OAuth2/SSO Configuration)
   ↓
2. Administration Dashboard
   ├── User management
   ├── OAuth2/SSO Configuration (NEW) - Provider setup
   └── System settings
   ↓
3. Feature Configuration
   ├── Dashboard Widget Configuration (NEW) - Campaign Progress setup
   ├── Lead List View Customization (NEW) - Column permissions
   ├── API Endpoint Management (NEW) - Campaign Lead API security
   └── Notification Settings (NEW) - Real-time message configuration
   ↓
4. User Role Management
   ├── Permission assignments for new features
   ├── Group access configuration
   └── API user credentials
   ↓
5. System Monitoring
   ├── Real-time notification system status
   ├── API usage monitoring
   └── Authentication logs
```

### Workflow 4: External System - API Integration Journey

**User Type**: Portal API User  
**Entry Point**: API Endpoints

```
1. API Authentication
   ├── OAuth2 token acquisition
   └── API key validation
   ↓
2. Campaign Lead Creation (NEW)
   ├── POST /api/leads/campaign
   ├── JSON payload validation
   ├── Campaign association
   └── Lead record creation
   ↓
3. Integration Triggers
   ├── Real-time notifications to internal users (NEW)
   ├── Lead list view updates (NEW)
   └── Dashboard widget metric updates (NEW)
   ↓
4. Response Handling
   ├── Success confirmation
   ├── Error handling
   └── Data validation feedback
```

## Feature Integration Points

### Cross-Feature Connections

#### Real-time Notifications → Multiple Destinations
```
Client Message Notification Triggered
├── Redirect to Account record with new communication
├── Redirect to Campaign with updated notes
└── Redirect to Lead detail with client feedback
```

#### API Lead Creation → Internal User Workflows
```
API Creates Campaign Lead
├── Real-time notification to campaign manager
├── Lead appears in filtered list view
├── Dashboard widget metrics update
└── Campaign notes may be updated
```

#### Dashboard Widget → Detailed Views
```
Campaign Progress Widget Interaction
├── Click metrics → Campaign detail view
├── Drill-down → Lead list filtered by campaign
└── Budget utilization → Campaign financial tracking
```

## Task Completion Scenarios

### Scenario 1: New Lead to Opportunity Conversion
```
External lead capture (API) → 
Real-time notification → 
Lead list view (filtered) → 
Lead qualification → 
Campaign notes update → 
Opportunity creation
```

### Scenario 2: Campaign Performance Review
```
Dashboard login → 
Campaign Progress Widget → 
Campaign detail view → 
Campaign notes review (rich text) → 
Lead performance analysis (filtered list) → 
Strategy adjustments
```

### Scenario 3: Client Communication Response
```
Real-time client message notification → 
Account/Contact record → 
Communication history review → 
Response preparation → 
Activity logging → 
Follow-up scheduling
```

## Navigation Patterns

### Primary Navigation Enhanced
- **Dashboard**: Central hub with new widgets and notifications
- **Leads Module**: Enhanced with configurable list views and filtering
- **Campaigns Module**: Enriched with progress tracking and rich text notes
- **Administration**: Extended with OAuth configuration and feature management

### Secondary Navigation
- **Notifications Bell**: Real-time message alerts with click-to-action
- **Quick Create**: API-enhanced lead creation options
- **User Profile**: OAuth/SSO account linking and preferences

## Mobile Considerations

While not part of the current scope, the user flows should accommodate future mobile enhancements:
- Responsive dashboard widgets
- Mobile-optimized list view filtering
- Push notification capabilities for real-time alerts

## Error Handling & Fallback Flows

### Authentication Failures
```
OAuth2 Failure → Traditional Login Fallback → Role-Based Dashboard
```

### API Integration Errors
```
API Lead Creation Failure → Error Response → Manual Lead Entry Option
```

### Real-time Notification Issues
```
WebSocket Failure → Long-polling Fallback → Email Notification Backup
```

---

*This user flow documentation provides the foundation for implementing intuitive navigation patterns and seamless feature integration within the modernized SuiteCRM environment.* 