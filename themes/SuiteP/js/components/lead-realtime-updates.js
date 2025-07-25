/**
 * @fileoverview Lead Real-time Updates Component
 * 
 * Handles Server-Sent Events (SSE) connections for real-time lead data updates.
 * Manages connection lifecycle, reconnection logic, and event distribution to
 * other components for live data synchronization.
 * 
 * Key Features:
 * - SSE connection management with automatic reconnection
 * - Event parsing and distribution
 * - Connection state monitoring
 * - Exponential backoff for reconnection attempts
 * - Memory-efficient event handling
 * - Cross-component communication
 * - Browser compatibility checks
 * 
 * Dependencies:
 * - Alpine.js for state management
 * - EventSource API for SSE
 * - Lead data store for updates
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

document.addEventListener('alpine:init', () => {
    // Global SSE connection state
    Alpine.store('leadSSE', {
        // Connection state
        eventSource: null,
        isConnected: false,
        isConnecting: false,
        connectionError: null,
        
        // Reconnection state
        reconnectAttempts: 0,
        maxReconnectAttempts: 10,
        reconnectDelay: 1000, // Start with 1 second
        maxReconnectDelay: 30000, // Max 30 seconds
        reconnectTimer: null,
        
        // Statistics
        eventsReceived: 0,
        lastEventTime: null,
        connectionStartTime: null,
        
        /**
         * Initializes SSE connection
         * 
         * @since 1.0.0
         */
        init() {
            // Check browser support
            if (!window.EventSource) {
                console.warn('Server-Sent Events not supported in this browser');
                this.connectionError = 'Browser does not support real-time updates';
                return;
            }
            
            // Connect automatically
            this.connect();
            
            // Handle page visibility changes
            document.addEventListener('visibilitychange', () => {
                if (document.hidden && this.isConnected) {
                    this.disconnect();
                } else if (!document.hidden && !this.isConnected) {
                    this.connect();
                }
            });
        },
        
        /**
         * Establishes SSE connection
         * 
         * @since 1.0.0
         */
        connect() {
            if (this.isConnected || this.isConnecting) {
                return;
            }
            
            this.isConnecting = true;
            this.connectionError = null;
            
            try {
                // Create EventSource connection
                this.eventSource = new EventSource('/Api/V8/leads/sse-stream', {
                    withCredentials: true
                });
                
                // Connection opened
                this.eventSource.onopen = () => {
                    console.log('SSE connection established');
                    this.isConnected = true;
                    this.isConnecting = false;
                    this.reconnectAttempts = 0;
                    this.reconnectDelay = 1000;
                    this.connectionStartTime = Date.now();
                    
                    // Notify other components
                    this.dispatchConnectionEvent('connected');
                };
                
                // Handle errors
                this.eventSource.onerror = (error) => {
                    console.error('SSE connection error:', error);
                    this.handleConnectionError();
                };
                
                // Set up event handlers
                this.setupEventHandlers();
                
            } catch (error) {
                console.error('Failed to create SSE connection:', error);
                this.connectionError = error.message;
                this.isConnecting = false;
                this.scheduleReconnect();
            }
        },
        
        /**
         * Sets up event handlers for SSE messages
         * 
         * @since 1.0.0
         */
        setupEventHandlers() {
            if (!this.eventSource) return;
            
            // Handle connection event
            this.eventSource.addEventListener('connected', (event) => {
                const data = JSON.parse(event.data);
                console.log('Connected to SSE stream:', data);
            });
            
            // Handle heartbeat
            this.eventSource.addEventListener('heartbeat', (event) => {
                const data = JSON.parse(event.data);
                this.lastEventTime = Date.now();
                // console.log('SSE heartbeat:', data);
            });
            
            // Handle lead created
            this.eventSource.addEventListener('lead_created', (event) => {
                this.handleLeadEvent('created', event);
            });
            
            // Handle lead updated
            this.eventSource.addEventListener('lead_updated', (event) => {
                this.handleLeadEvent('updated', event);
            });
            
            // Handle lead deleted
            this.eventSource.addEventListener('lead_deleted', (event) => {
                this.handleLeadEvent('deleted', event);
            });
            
            // Handle timeout
            this.eventSource.addEventListener('timeout', (event) => {
                const data = JSON.parse(event.data);
                console.log('SSE timeout:', data);
                this.reconnect();
            });
            
            // Handle errors
            this.eventSource.addEventListener('error', (event) => {
                const data = JSON.parse(event.data);
                console.error('SSE error:', data);
                this.connectionError = data.message;
            });
        },
        
        /**
         * Handles lead events
         * 
         * @param {string} type Event type (created/updated/deleted)
         * @param {MessageEvent} event SSE event
         * @since 1.0.0
         */
        handleLeadEvent(type, event) {
            try {
                const data = JSON.parse(event.data);
                this.eventsReceived++;
                this.lastEventTime = Date.now();
                
                console.log(`Lead ${type}:`, data);
                
                // Clear filter cache as data has changed
                if (Alpine.store('leadData')) {
                    Alpine.store('leadData').clearFilterCache();
                }
                
                // Dispatch custom event for other components
                window.dispatchEvent(new CustomEvent('lead-realtime-update', {
                    detail: {
                        type: type,
                        lead: data.lead,
                        timestamp: data.timestamp
                    }
                }));
                
                // Show notification
                this.showUpdateNotification(type, data.lead);
                
                // Update lead data if currently visible
                this.updateLeadInView(type, data.lead);
                
            } catch (error) {
                console.error('Error handling lead event:', error);
            }
        },
        
        /**
         * Updates lead in current view
         * 
         * @param {string} type Event type
         * @param {Object} lead Lead data
         * @since 1.0.0
         */
        updateLeadInView(type, lead) {
            const leadStore = Alpine.store('leadData');
            if (!leadStore) return;
            
            switch (type) {
                case 'created':
                    // Add to beginning of list if filters match
                    if (this.leadMatchesCurrentFilters(lead)) {
                        leadStore.leads.unshift(lead);
                        leadStore.totalCount++;
                    }
                    break;
                    
                case 'updated':
                    // Update existing lead
                    const index = leadStore.leads.findIndex(l => l.id === lead.id);
                    if (index !== -1) {
                        leadStore.leads[index] = { ...leadStore.leads[index], ...lead };
                    }
                    break;
                    
                case 'deleted':
                    // Remove from list
                    leadStore.leads = leadStore.leads.filter(l => l.id !== lead.id);
                    leadStore.totalCount--;
                    break;
            }
        },
        
        /**
         * Checks if lead matches current filters
         * 
         * @param {Object} lead Lead data
         * @returns {boolean} Whether lead matches filters
         * @since 1.0.0
         */
        leadMatchesCurrentFilters(lead) {
            const filters = Alpine.store('leadFilters')?.activeFilters || {};
            
            // Check search filter
            if (filters.search) {
                const searchTerm = filters.search.toLowerCase();
                const leadName = `${lead.first_name} ${lead.last_name}`.toLowerCase();
                if (!leadName.includes(searchTerm) && 
                    !lead.email?.toLowerCase().includes(searchTerm)) {
                    return false;
                }
            }
            
            // Check industry filter
            if (filters.industry && lead.industry !== filters.industry) {
                return false;
            }
            
            // Check campaign filter - would need additional data
            // Skip for now as it requires campaign association check
            
            return true;
        },
        
        /**
         * Shows notification for lead update
         * 
         * @param {string} type Event type
         * @param {Object} lead Lead data
         * @since 1.0.0
         */
        showUpdateNotification(type, lead) {
            const leadName = `${lead.first_name} ${lead.last_name}`.trim() || 'Lead';
            let message = '';
            
            switch (type) {
                case 'created':
                    message = `New lead created: ${leadName}`;
                    break;
                case 'updated':
                    message = `Lead updated: ${leadName}`;
                    break;
                case 'deleted':
                    message = `Lead deleted: ${leadName}`;
                    break;
            }
            
            // Dispatch notification event
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    message: message,
                    type: 'info',
                    duration: 3000
                }
            }));
        },
        
        /**
         * Handles connection errors
         * 
         * @since 1.0.0
         */
        handleConnectionError() {
            this.isConnected = false;
            this.isConnecting = false;
            
            if (this.eventSource) {
                this.eventSource.close();
                this.eventSource = null;
            }
            
            this.scheduleReconnect();
        },
        
        /**
         * Schedules reconnection attempt
         * 
         * @since 1.0.0
         */
        scheduleReconnect() {
            if (this.reconnectAttempts >= this.maxReconnectAttempts) {
                console.error('Max reconnection attempts reached');
                this.connectionError = 'Unable to establish real-time connection';
                this.dispatchConnectionEvent('failed');
                return;
            }
            
            // Clear existing timer
            if (this.reconnectTimer) {
                clearTimeout(this.reconnectTimer);
            }
            
            this.reconnectAttempts++;
            const delay = Math.min(
                this.reconnectDelay * Math.pow(2, this.reconnectAttempts - 1),
                this.maxReconnectDelay
            );
            
            console.log(`Reconnecting in ${delay}ms (attempt ${this.reconnectAttempts})`);
            
            this.reconnectTimer = setTimeout(() => {
                this.connect();
            }, delay);
        },
        
        /**
         * Manually reconnects
         * 
         * @since 1.0.0
         */
        reconnect() {
            this.disconnect();
            this.reconnectAttempts = 0;
            this.connect();
        },
        
        /**
         * Disconnects SSE connection
         * 
         * @since 1.0.0
         */
        disconnect() {
            if (this.reconnectTimer) {
                clearTimeout(this.reconnectTimer);
                this.reconnectTimer = null;
            }
            
            if (this.eventSource) {
                this.eventSource.close();
                this.eventSource = null;
            }
            
            this.isConnected = false;
            this.isConnecting = false;
            this.dispatchConnectionEvent('disconnected');
        },
        
        /**
         * Dispatches connection event
         * 
         * @param {string} status Connection status
         * @since 1.0.0
         */
        dispatchConnectionEvent(status) {
            window.dispatchEvent(new CustomEvent('sse-connection-change', {
                detail: {
                    status: status,
                    timestamp: Date.now()
                }
            }));
        },
        
        /**
         * Gets connection statistics
         * 
         * @returns {Object} Connection stats
         * @since 1.0.0
         */
        getStats() {
            return {
                isConnected: this.isConnected,
                eventsReceived: this.eventsReceived,
                lastEventTime: this.lastEventTime,
                uptime: this.connectionStartTime ? Date.now() - this.connectionStartTime : 0,
                reconnectAttempts: this.reconnectAttempts
            };
        }
    });
});

/**
 * Initializes SSE connection when DOM is ready
 * 
 * @since 1.0.0
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize SSE connection if lead list is present
    if (document.querySelector('[x-data*="leadTableView"]')) {
        Alpine.store('leadSSE')?.init();
    }
}); 