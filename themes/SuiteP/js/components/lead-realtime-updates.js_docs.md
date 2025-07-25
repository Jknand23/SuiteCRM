# Lead Real-time Updates Component Documentation

## Overview

The `lead-realtime-updates.js` component manages Server-Sent Events (SSE) connections for real-time lead data synchronization. This Alpine.js-based component handles connection lifecycle, event processing, automatic reconnection, and seamless UI updates when lead data changes occur.

## Architecture

### Component Structure
```javascript
Alpine.store('leadSSE', {
  // Connection state
  eventSource: null,
  isConnected: false,
  isConnecting: false,
  connectionError: null,
  
  // Reconnection state
  reconnectAttempts: 0,
  maxReconnectAttempts: 10,
  reconnectDelay: 1000,
  maxReconnectDelay: 30000,
  reconnectTimer: null,
  
  // Statistics
  eventsReceived: 0,
  lastEventTime: null,
  connectionStartTime: null
});
```

### Key Features

#### 1. Automatic Connection Management
- Establishes SSE connection on component initialization
- Handles browser visibility changes (disconnect when hidden)
- Automatic reconnection with exponential backoff
- Graceful degradation for unsupported browsers

#### 2. Event Processing
- Real-time lead creation notifications
- Live lead update synchronization
- Instant lead deletion handling
- Event distribution to other components

#### 3. Performance Optimization
- Connection pooling and reuse
- Memory-efficient event handling
- Cache invalidation on data changes
- Throttled UI updates

#### 4. Error Resilience
- Exponential backoff for reconnection attempts
- Maximum retry limits to prevent infinite loops
- Connection state monitoring
- Error event dispatching

## Event Handling

### Incoming SSE Events

#### Connected Event
```javascript
eventSource.addEventListener('connected', (event) => {
  const data = JSON.parse(event.data);
  // Connection established successfully
  console.log('Connected to SSE stream:', data);
});
```

#### Lead Created Event
```javascript
eventSource.addEventListener('lead_created', (event) => {
  const data = JSON.parse(event.data);
  // New lead created - update UI
  this.handleLeadEvent('created', event);
});
```

#### Lead Updated Event
```javascript
eventSource.addEventListener('lead_updated', (event) => {
  const data = JSON.parse(event.data);
  // Lead modified - update existing record
  this.handleLeadEvent('updated', event);
});
```

#### Lead Deleted Event
```javascript
eventSource.addEventListener('lead_deleted', (event) => {
  const data = JSON.parse(event.data);
  // Lead removed - remove from UI
  this.handleLeadEvent('deleted', event);
});
```

### Outgoing Custom Events

#### lead-realtime-update
Dispatched when any lead change occurs.
```javascript
window.dispatchEvent(new CustomEvent('lead-realtime-update', {
  detail: {
    type: 'created|updated|deleted',
    lead: { /* lead data */ },
    timestamp: '2024-01-15T14:30:00+00:00'
  }
}));
```

#### show-notification
Dispatched to show user notifications.
```javascript
window.dispatchEvent(new CustomEvent('show-notification', {
  detail: {
    message: 'New lead created: John Doe',
    type: 'info',
    duration: 3000
  }
}));
```

#### sse-connection-change
Dispatched when connection state changes.
```javascript
window.dispatchEvent(new CustomEvent('sse-connection-change', {
  detail: {
    status: 'connected|disconnected|failed',
    timestamp: 1642257000000
  }
}));
```

## Connection Lifecycle

### 1. Initialization
```javascript
// Automatic initialization on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('[x-data*="leadTableView"]')) {
    Alpine.store('leadSSE')?.init();
  }
});
```

### 2. Connection States
```
DISCONNECTED → CONNECTING → CONNECTED → DISCONNECTING → DISCONNECTED
     ↑              ↓            ↓              ↑
     └──────────────┴────────────┴──────────────┘
              (Reconnection Loop)
```

### 3. Visibility Handling
```javascript
document.addEventListener('visibilitychange', () => {
  if (document.hidden && this.isConnected) {
    this.disconnect(); // Save resources when page hidden
  } else if (!document.hidden && !this.isConnected) {
    this.connect(); // Reconnect when page visible
  }
});
```

## Reconnection Strategy

### Exponential Backoff Algorithm
```javascript
const delay = Math.min(
  this.reconnectDelay * Math.pow(2, this.reconnectAttempts - 1),
  this.maxReconnectDelay
);
```

### Reconnection Timeline
- Attempt 1: 1 second delay
- Attempt 2: 2 seconds delay
- Attempt 3: 4 seconds delay
- Attempt 4: 8 seconds delay
- Attempt 5: 16 seconds delay
- Attempt 6+: 30 seconds delay (max)
- After 10 attempts: Give up and show error

## UI Integration

### Lead List Updates

#### Adding New Leads
```javascript
case 'created':
  if (this.leadMatchesCurrentFilters(lead)) {
    leadStore.leads.unshift(lead); // Add to beginning
    leadStore.totalCount++;
  }
  break;
```

#### Updating Existing Leads
```javascript
case 'updated':
  const index = leadStore.leads.findIndex(l => l.id === lead.id);
  if (index !== -1) {
    leadStore.leads[index] = { ...leadStore.leads[index], ...lead };
  }
  break;
```

#### Removing Deleted Leads
```javascript
case 'deleted':
  leadStore.leads = leadStore.leads.filter(l => l.id !== lead.id);
  leadStore.totalCount--;
  break;
```

### Filter Matching
```javascript
leadMatchesCurrentFilters(lead) {
  const filters = Alpine.store('leadFilters')?.activeFilters || {};
  
  // Search filter
  if (filters.search) {
    const searchTerm = filters.search.toLowerCase();
    const leadName = `${lead.first_name} ${lead.last_name}`.toLowerCase();
    if (!leadName.includes(searchTerm) && 
        !lead.email?.toLowerCase().includes(searchTerm)) {
      return false;
    }
  }
  
  // Industry filter
  if (filters.industry && lead.industry !== filters.industry) {
    return false;
  }
  
  return true;
}
```

## Configuration

### Default Settings
```javascript
{
  maxReconnectAttempts: 10,      // Maximum reconnection attempts
  reconnectDelay: 1000,          // Initial reconnect delay (ms)
  maxReconnectDelay: 30000,      // Maximum reconnect delay (ms)
  heartbeatTimeout: 60000        // Expected heartbeat interval (ms)
}
```

### Customization
```javascript
// Override default settings
Alpine.store('leadSSE').maxReconnectAttempts = 20;
Alpine.store('leadSSE').reconnectDelay = 2000;
```

## Error Handling

### Connection Errors
```javascript
this.eventSource.onerror = (error) => {
  console.error('SSE connection error:', error);
  this.handleConnectionError();
};
```

### Event Processing Errors
```javascript
try {
  const data = JSON.parse(event.data);
  // Process event
} catch (error) {
  console.error('Error handling lead event:', error);
  // Continue processing other events
}
```

### Browser Compatibility
```javascript
if (!window.EventSource) {
  console.warn('Server-Sent Events not supported in this browser');
  this.connectionError = 'Browser does not support real-time updates';
  return;
}
```

## Performance Metrics

### Connection Statistics
```javascript
getStats() {
  return {
    isConnected: this.isConnected,
    eventsReceived: this.eventsReceived,
    lastEventTime: this.lastEventTime,
    uptime: this.connectionStartTime ? Date.now() - this.connectionStartTime : 0,
    reconnectAttempts: this.reconnectAttempts
  };
}
```

### Monitoring Usage
```javascript
// Get current statistics
const stats = Alpine.store('leadSSE').getStats();
console.log(`Connected: ${stats.isConnected}`);
console.log(`Events received: ${stats.eventsReceived}`);
console.log(`Uptime: ${stats.uptime}ms`);
```

## Best Practices

### 1. Resource Management
- Disconnect when page is hidden
- Clean up on component destruction
- Limit event processing frequency
- Use debouncing for UI updates

### 2. Error Recovery
- Implement exponential backoff
- Set maximum retry limits
- Provide user feedback
- Log errors for debugging

### 3. Security
- Always use HTTPS in production
- Validate event data
- Sanitize content before display
- Check user permissions

### 4. Testing
```javascript
// Mock SSE for testing
function mockSSE() {
  const mockEventSource = {
    addEventListener: jest.fn(),
    close: jest.fn(),
    readyState: EventSource.OPEN
  };
  
  window.EventSource = jest.fn(() => mockEventSource);
  return mockEventSource;
}
```

## Troubleshooting

### Common Issues

#### 1. Connection Fails Immediately
- Check authentication status
- Verify API endpoint URL
- Ensure CORS headers are correct
- Check browser console for errors

#### 2. Events Not Updating UI
- Verify Alpine.js stores are initialized
- Check event listener registration
- Ensure lead data store exists
- Monitor browser console

#### 3. Frequent Disconnections
- Check network stability
- Review server timeout settings
- Monitor proxy/firewall logs
- Verify heartbeat configuration

#### 4. High Memory Usage
- Check for memory leaks in event handlers
- Ensure proper cleanup on disconnect
- Monitor event processing frequency
- Review cache management

### Debug Mode
```javascript
// Enable verbose logging
Alpine.store('leadSSE').debug = true;

// Log all events
if (this.debug) {
  console.log(`SSE Event: ${type}`, data);
}
```

## Integration Examples

### With Notification System
```javascript
// Listen for SSE notifications
window.addEventListener('show-notification', (event) => {
  const { message, type, duration } = event.detail;
  
  // Show toast notification
  showToast(message, type, duration);
});
```

### With Lead List Component
```javascript
// Clear cache on updates
window.addEventListener('lead-realtime-update', (event) => {
  // Invalidate cached filter results
  Alpine.store('leadData').clearFilterCache();
  
  // Update visible leads
  updateLeadDisplay(event.detail);
});
```

### With Dashboard Widgets
```javascript
// Update metrics on lead changes
window.addEventListener('lead-realtime-update', (event) => {
  if (event.detail.type === 'created') {
    Alpine.store('dashboard').incrementLeadCount();
  }
});
```

## Future Enhancements

### Planned Features
- WebSocket fallback support
- Selective event subscriptions
- Event replay on reconnection
- Offline queue for updates
- Push notification integration

### API Improvements
- Event filtering by criteria
- Batch event delivery
- Custom event types
- Historical event access
- Connection quality metrics 