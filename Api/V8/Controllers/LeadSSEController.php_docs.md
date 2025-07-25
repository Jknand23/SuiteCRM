# Lead Server-Sent Events (SSE) Controller Documentation

## Overview

The `LeadSSEController` provides real-time updates for lead data changes using Server-Sent Events (SSE). This enables live synchronization of lead information across all connected clients without the need for polling or page refreshes. The controller streams events for lead creation, updates, and deletions with automatic connection management.

## Key Features

### 1. Real-time Event Streaming
- Pushes lead changes instantly to connected clients
- Supports multiple event types (created, updated, deleted)
- User-specific event filtering based on permissions
- Automatic detection of lead modifications

### 2. Connection Management
- Heartbeat messages to maintain connection health
- Automatic timeout after 5 minutes with graceful reconnection
- Client disconnect detection
- Connection state monitoring

### 3. Performance Optimization
- Efficient database queries with 2-second check intervals
- Limited result sets (50 records max) to prevent overload
- Memory-efficient streaming implementation
- Proper output buffer management

### 4. Security & Permissions
- User-based event filtering
- ACL permission checks before streaming
- Secure connection handling
- Session validation

## API Endpoint

### GET /Api/V8/leads/sse-stream

Establishes a Server-Sent Events connection for real-time lead updates.

**Request Headers:**
```
Accept: text/event-stream
Cache-Control: no-cache
```

**Response Headers:**
```
Content-Type: text/event-stream
Cache-Control: no-cache
Connection: keep-alive
X-Accel-Buffering: no
```

## Event Types

### 1. Connection Events

**connected**
Sent when SSE connection is established.
```
event: connected
data: {"message":"SSE connection established","timestamp":"2024-01-15T14:30:00+00:00"}
```

**heartbeat**
Periodic keep-alive message (every 30 seconds).
```
event: heartbeat
data: {"timestamp":"2024-01-15T14:30:30+00:00","uptime":30}
```

**timeout**
Connection timeout notification.
```
event: timeout
data: {"message":"Connection timeout, please reconnect","timestamp":"2024-01-15T14:35:00+00:00"}
```

### 2. Lead Events

**lead_created**
New lead added to the system.
```
event: lead_created
data: {
  "lead": {
    "id": "123e4567-e89b-12d3-a456-426614174000",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "status": "New",
    "industry": "Technology",
    "account_name": "Acme Corp",
    "date_entered": "2024-01-15 14:30:00",
    "date_modified": "2024-01-15 14:30:00"
  },
  "timestamp": "2024-01-15T14:30:00+00:00",
  "change_type": "created"
}
```

**lead_updated**
Existing lead information modified.
```
event: lead_updated
data: {
  "lead": {
    "id": "123e4567-e89b-12d3-a456-426614174000",
    "status": "In Process",
    "date_modified": "2024-01-15 14:31:00"
  },
  "timestamp": "2024-01-15T14:31:00+00:00",
  "change_type": "updated"
}
```

**lead_deleted**
Lead removed from the system.
```
event: lead_deleted
data: {
  "lead": {
    "id": "123e4567-e89b-12d3-a456-426614174000"
  },
  "timestamp": "2024-01-15T14:32:00+00:00",
  "change_type": "deleted"
}
```

### 3. Error Events

**error**
Error occurred during streaming.
```
event: error
data: {"message":"Error description","timestamp":"2024-01-15T14:30:00+00:00"}
```

## Client Implementation

### JavaScript EventSource Example
```javascript
// Establish SSE connection
const eventSource = new EventSource('/Api/V8/leads/sse-stream', {
  withCredentials: true
});

// Handle connection established
eventSource.addEventListener('connected', (event) => {
  const data = JSON.parse(event.data);
  console.log('Connected to lead updates:', data);
});

// Handle lead creation
eventSource.addEventListener('lead_created', (event) => {
  const data = JSON.parse(event.data);
  console.log('New lead created:', data.lead);
  // Update UI with new lead
});

// Handle lead updates
eventSource.addEventListener('lead_updated', (event) => {
  const data = JSON.parse(event.data);
  console.log('Lead updated:', data.lead);
  // Update existing lead in UI
});

// Handle lead deletion
eventSource.addEventListener('lead_deleted', (event) => {
  const data = JSON.parse(event.data);
  console.log('Lead deleted:', data.lead.id);
  // Remove lead from UI
});

// Handle errors
eventSource.onerror = (error) => {
  console.error('SSE error:', error);
  // Implement reconnection logic
};
```

### Alpine.js Integration
```javascript
Alpine.store('leadSSE', {
  eventSource: null,
  isConnected: false,
  
  connect() {
    this.eventSource = new EventSource('/Api/V8/leads/sse-stream', {
      withCredentials: true
    });
    
    this.eventSource.onopen = () => {
      this.isConnected = true;
    };
    
    this.eventSource.addEventListener('lead_created', (event) => {
      const data = JSON.parse(event.data);
      // Clear cache and update lead list
      Alpine.store('leadData').clearFilterCache();
      Alpine.store('leadData').addLead(data.lead);
    });
    
    this.eventSource.onerror = () => {
      this.isConnected = false;
      // Reconnection logic
    };
  },
  
  disconnect() {
    if (this.eventSource) {
      this.eventSource.close();
      this.eventSource = null;
      this.isConnected = false;
    }
  }
});
```

## Connection Lifecycle

### 1. Connection Establishment
```
Client → GET /Api/V8/leads/sse-stream
Server → Headers: Content-Type: text/event-stream
Server → Event: connected
Client → Connection established
```

### 2. Active Connection
```
Server → Event: lead_* (as changes occur)
Server → Event: heartbeat (every 30 seconds)
Client → Maintains open connection
```

### 3. Connection Timeout
```
Server → Event: timeout (after 5 minutes)
Server → Close connection
Client → Detect disconnect
Client → Initiate reconnection
```

### 4. Client Disconnect
```
Client → Close connection
Server → Detect disconnect
Server → Clean up resources
```

## Performance Considerations

### Server Configuration
```php
// Timing configuration
private int $heartbeatInterval = 30;    // Seconds between heartbeats
private int $checkInterval = 2;         // Seconds between DB checks
private int $maxExecutionTime = 300;    // 5 minutes max connection

// Query limits
LIMIT 50  // Maximum changes per check
```

### Database Optimization
- Indexed columns: `date_entered`, `date_modified`, `assigned_user_id`
- Efficient change detection query
- Minimal data transfer per event
- Batch processing of multiple changes

### Memory Management
```php
// Disable output buffering
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', false);
@ob_end_flush();

// No execution time limit
set_time_limit(0);
ignore_user_abort(true);
```

## Security Best Practices

### Authentication
- Session validation before streaming
- User permission checks
- Secure cookie handling

### Data Filtering
- User-specific lead visibility
- Role-based event filtering
- No sensitive data in events

### Connection Security
- HTTPS required in production
- CORS headers properly configured
- Rate limiting on connections

## Troubleshooting

### Common Issues

1. **Connection Drops Frequently**
   - Check proxy/firewall settings
   - Verify keepalive configuration
   - Monitor network stability
   - Review server timeout settings

2. **Events Not Received**
   - Verify user permissions
   - Check lead assignment/ownership
   - Confirm database triggers
   - Review error logs

3. **High Server Load**
   - Adjust check interval
   - Limit concurrent connections
   - Optimize database queries
   - Implement connection pooling

4. **Browser Compatibility**
   - Verify EventSource support
   - Check CORS configuration
   - Test fallback mechanisms
   - Monitor console errors

### Debug Mode
```javascript
// Enable debug logging
eventSource.addEventListener('message', (event) => {
  console.log('SSE Raw:', event);
});

// Monitor all events
['connected', 'heartbeat', 'lead_created', 'lead_updated', 'lead_deleted', 'timeout', 'error'].forEach(eventType => {
  eventSource.addEventListener(eventType, (event) => {
    console.log(`SSE ${eventType}:`, JSON.parse(event.data));
  });
});
```

### Server Logs
Monitor these log locations:
- Application logs for SSE errors
- Web server logs for connection issues
- Database logs for query performance
- PHP error logs for script issues

## Browser Support

### Compatible Browsers
- Chrome 6+
- Firefox 6+
- Safari 5+
- Edge 79+
- Opera 11+

### Internet Explorer
Not supported. Implement polyfill or fallback:
```javascript
if (!window.EventSource) {
  // Fallback to polling
  console.warn('EventSource not supported, using polling fallback');
  // Implement polling mechanism
}
```

## Best Practices

### Client Implementation
- Implement automatic reconnection
- Handle connection state properly
- Process events asynchronously
- Maintain event order

### Error Handling
```javascript
let reconnectAttempts = 0;
const maxReconnectAttempts = 10;

function connect() {
  const eventSource = new EventSource('/Api/V8/leads/sse-stream');
  
  eventSource.onerror = () => {
    eventSource.close();
    
    if (reconnectAttempts < maxReconnectAttempts) {
      reconnectAttempts++;
      const delay = Math.min(1000 * Math.pow(2, reconnectAttempts), 30000);
      setTimeout(connect, delay);
    }
  };
  
  eventSource.onopen = () => {
    reconnectAttempts = 0;
  };
}
```

### Resource Management
- Close connections on page unload
- Limit concurrent connections per user
- Implement connection pooling
- Monitor resource usage

## Future Enhancements

### Planned Features
- WebSocket upgrade option
- Message acknowledgment system
- Event replay functionality
- Custom event subscriptions
- Batch event delivery
- Compression support

### API Improvements
- Event filtering parameters
- Historical event access
- Connection statistics endpoint
- Administrative controls 