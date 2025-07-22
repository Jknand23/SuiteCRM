# AOP Assignment Manager

**File**: `modules/AOP_Case_Updates/AOPAssignManager.php`  
**Type**: PHP Assignment Management Class  
**Purpose**: Manages automatic case assignment strategies for Advanced OpenPortal (AOP) system

## Overview

The AOPAssignManager class provides sophisticated case assignment algorithms for the Advanced OpenPortal system. It supports multiple distribution methods including single user, round-robin, least busy, and random assignment strategies. The class integrates with both global AOP configuration and inbound email-specific settings to provide flexible assignment management.

## Database Operations

### User Query Operations
The class performs several database operations for assignment calculations:
- **Role User Queries**: Retrieves users associated with specific ACL roles
- **Security Group Queries**: Gets users within security groups (if module exists)
- **Case Count Queries**: Calculates current case loads for least busy assignment
- **User Array Queries**: Generates lists of assignable users

### Performance Optimization
- **Efficient Queries**: Uses optimized SQL for case count calculations
- **Caching**: Maintains internal cache of user assignments and counts
- **Lazy Loading**: Loads user data only when required
- **Index Utilization**: Leverages database indexes for assignment queries

### Case Load Tracking
For least busy assignment:
- **Active Case Counting**: Counts non-deleted cases per user
- **Real-time Updates**: Updates case counts after assignment
- **Sorted Results**: Orders users by current case load
- **Dynamic Balancing**: Automatically balances workload across users

## Internal API Integration

### Configuration Management
The class integrates with multiple configuration sources:
- **Global AOP Settings**: Primary configuration from `$sugar_config['aop']`
- **Inbound Email Settings**: Email-specific distribution settings
- **Fallback Logic**: Automatic fallback to AOP defaults when needed
- **Dynamic Configuration**: Runtime configuration switching

### Bean Factory Integration
- **ACLRole Integration**: Uses BeanFactory for role-based user retrieval
- **SecurityGroup Integration**: Optional SecurityGroup module support
- **User Management**: Standard User bean operations for assignment
- **Relationship Management**: Processes linked beans for user lists

### Assignment Strategy Engine
- **Strategy Pattern**: Multiple assignment algorithms in single class
- **Context Awareness**: Handles both global and email-specific contexts
- **State Management**: Maintains assignment state across operations
- **Extensible Design**: Easy addition of new assignment strategies

## Assignment Strategy Implementation

### Single User Assignment
- **Fixed Assignment**: All cases assigned to designated user
- **Configuration**: Uses `distribution_user_id` setting
- **Simplicity**: Minimal overhead for single-person teams
- **Reliability**: Guaranteed assignment when user exists

### Round Robin Assignment
- **Fair Distribution**: Cycles through all assignable users
- **State Persistence**: Maintains last assigned user across sessions
- **Session Storage**: Uses $_SESSION for temporary state
- **File Caching**: Persistent state storage in cache files
- **Wraparound Logic**: Automatically cycles back to first user

### Least Busy Assignment
- **Workload Balancing**: Assigns to user with fewest active cases
- **Dynamic Calculation**: Real-time case count queries
- **Automatic Updates**: Updates counts after assignment
- **Load Balancing**: Maintains even workload distribution
- **Performance Tracking**: Monitors case assignment patterns

### Random Assignment
- **Random Distribution**: Uses PHP's random functions for assignment
- **Equal Probability**: Each user has equal chance of assignment
- **Simple Implementation**: Minimal overhead and complexity
- **Unpredictable Pattern**: Prevents gaming of assignment system

## UI Functionality

### Cache Management
The class manages cache files for persistent state:
- **Directory Creation**: Creates cache directories as needed
- **File Management**: Handles cache file creation and updates
- **Session Integration**: Balances session and file-based storage
- **Cleanup**: Maintains clean cache directory structure

### State Persistence
Round-robin assignment requires sophisticated state management:
- **Session Variables**: `$_SESSION['AOPLastUser']` for temporary state
- **Cache Files**: Persistent storage in `modules/AOP_Case_Updates/Users/`
- **State Recovery**: Restores state from cache files on session loss
- **Multi-context Support**: Separate state per inbound email configuration

## Security and Access Control

### User Filtering
Assignment respects security boundaries:
- **ACL Role Filtering**: Only assigns to users with appropriate roles
- **Security Group Filtering**: Respects security group membership
- **Active User Filtering**: Excludes inactive users from assignment
- **Permission Checking**: Ensures assigned users can access cases

### Configuration Security
- **Safe Defaults**: Provides safe fallbacks for missing configuration
- **Validation**: Validates configuration values before use
- **Error Handling**: Graceful handling of invalid configuration
- **Access Control**: Respects user permissions in assignment logic

## Integration with Module Components

### Inbound Email Integration
- **Email Context**: Supports per-email assignment configuration
- **Distribution Methods**: Email-specific vs. global distribution settings
- **Configuration Override**: Email settings override global defaults
- **Fallback Mechanism**: Graceful fallback to AOP defaults

### Security Group Integration
- **Module Detection**: Checks for SecurityGroup module existence
- **Conditional Logic**: SecurityGroup features only if module available
- **User Filtering**: Combines security group and role filtering
- **Graceful Degradation**: Functions without SecurityGroup module

### ACL Role Integration
- **Role-based Assignment**: Assigns based on ACL role membership
- **Combined Filtering**: Security group + role combinations
- **Role Validation**: Ensures valid role-based assignments
- **User Relationship**: Processes role-user relationships efficiently

## Advanced Features

### Multi-Context Support
The class handles multiple assignment contexts:
- **Global Context**: System-wide case assignment
- **Email Context**: Inbound email-specific assignment
- **Context Switching**: Dynamic context switching based on source
- **State Isolation**: Separate state management per context

### Workload Management
Sophisticated workload balancing:
- **Real-time Monitoring**: Continuous case load monitoring
- **Dynamic Adjustment**: Automatic workload rebalancing
- **Performance Metrics**: Case assignment performance tracking
- **Load Distribution**: Even distribution across available users

### Cache Optimization
Intelligent caching for performance:
- **User List Caching**: Caches assignable user lists
- **Count Caching**: Caches case count calculations
- **State Caching**: Persistent state storage for round-robin
- **Cache Invalidation**: Appropriate cache invalidation strategies

## Error Handling and Logging

### Assignment Failure Handling
- **User Validation**: Validates assigned users before assignment
- **Fallback Users**: Provides fallback assignment if primary fails
- **Error Logging**: Logs assignment failures for debugging
- **Graceful Degradation**: Continues operation despite assignment issues

### Configuration Error Handling
- **Missing Configuration**: Handles missing configuration gracefully
- **Invalid Settings**: Validates configuration before use
- **Default Values**: Provides sensible defaults for missing settings
- **Warning Generation**: Logs warnings for configuration issues

## Performance Optimization

### Database Efficiency
- **Query Optimization**: Efficient SQL for assignment calculations
- **Index Usage**: Leverages database indexes for performance
- **Result Caching**: Caches database query results appropriately
- **Batch Operations**: Optimizes for bulk assignment operations

### Memory Management
- **Efficient Storage**: Minimizes memory usage for large user sets
- **Lazy Loading**: Loads data only when needed
- **Cache Management**: Appropriate cache size management
- **Resource Cleanup**: Proper resource cleanup after operations

## Best Practices

### Assignment Strategy Selection
- **Team Size Consideration**: Choose strategy based on team size
- **Workload Patterns**: Consider typical case workload patterns
- **User Availability**: Account for user availability and schedules
- **Performance Requirements**: Balance fairness with performance

### Configuration Management
- **Clear Defaults**: Provide clear default configuration
- **Documentation**: Document configuration options thoroughly
- **Validation**: Validate configuration at runtime
- **Migration**: Provide migration paths for configuration changes

### Scalability Considerations
- **Large Teams**: Design for large user bases
- **High Volume**: Handle high case volumes efficiently
- **Performance**: Maintain performance with growing data
- **Resource Usage**: Optimize resource usage for scalability 