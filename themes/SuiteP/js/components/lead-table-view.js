/**
 * @fileoverview Interactive Lead Table View Component with Column Management
 * 
 * Alpine.js reactive component for displaying lead data in a responsive table format
 * with advanced column management, filtering, sorting, and personalization capabilities.
 * Implements Phase 2, Feature 1, Step 3 of the SuiteCRM modernization project.
 * 
 * Key Features:
 * - Reactive data binding with automatic updates
 * - Responsive Bootstrap 5 table layout
 * - Advanced column management (show/hide, reorder, resize)
 * - Multi-column sorting with visual indicators
 * - User preferences persistence
 * - Loading states with skeleton screens
 * - Comprehensive error handling and fallback UI
 * - Integration with existing SuiteCRM lead data
 * - Mobile-friendly responsive design
 * - Drag-and-drop column reordering
 * 
 * Dependencies:
 * - Alpine.js 3.x for reactivity
 * - Bootstrap 5 for responsive styling
 * - SuiteCRM API endpoints for lead data
 * - lead-list-filter.js for filter integration
 * - SortableJS for drag-and-drop functionality
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.1.0
 * @since 2024-01-15
 */

document.addEventListener('alpine:init', () => {
    // Global lead data store with column management
    Alpine.store('leadData', {
        // Data state
        leads: [],
        totalCount: 0,
        currentPage: 1,
        pageSize: 20,
        
        // Loading and error state
        isLoading: false,
        hasError: false,
        errorMessage: '',
        isInitialLoad: true,
        
        // Enhanced sort state with multi-column support
        sortColumns: [
            { field: 'date_modified', direction: 'desc', priority: 1 }
        ],
        
        // Selection state
        selectedLeads: [],
        selectAll: false,
        
        // Client-side cache for filter results
        filterCache: new Map(),
        cacheTimeout: 5 * 60 * 1000, // 5 minutes cache validity
        
        // Bulk actions state
        bulkActionsVisible: false,
        bulkActionInProgress: false,
        
        // Export state
        exportInProgress: false,
        
        // Column configuration state
        columnConfig: {
            selection: { id: 'selection', label: 'Select', visible: true, width: 40, resizable: false, sortable: false, order: 0 },
            name: { id: 'name', label: 'Lead Name', visible: true, width: 200, resizable: true, sortable: true, order: 1 },
            email: { id: 'email', label: 'Email Address', visible: true, width: 180, resizable: true, sortable: true, order: 2 },
            account_name: { id: 'account_name', label: 'Company', visible: true, width: 150, resizable: true, sortable: true, order: 3 },
            status: { id: 'status', label: 'Status', visible: true, width: 120, resizable: true, sortable: true, order: 4 },
            industry: { id: 'industry', label: 'Industry', visible: false, width: 120, resizable: true, sortable: true, order: 5 },
            phone_work: { id: 'phone_work', label: 'Phone', visible: false, width: 130, resizable: true, sortable: false, order: 6 },
            lead_source: { id: 'lead_source', label: 'Lead Source', visible: false, width: 140, resizable: true, sortable: true, order: 7 },
            date_modified: { id: 'date_modified', label: 'Last Activity', visible: true, width: 130, resizable: true, sortable: true, order: 8 },
            actions: { id: 'actions', label: 'Actions', visible: true, width: 100, resizable: false, sortable: false, order: 9 }
        },
        
        // Column management state
        columnManagerVisible: false,
        columnResizing: null,
        draggedColumn: null,
        
        /**
         * Gets visible columns in order
         * 
         * @returns {Array} Ordered array of visible column configurations
         * @since 1.1.0
         */
        get visibleColumns() {
            return Object.values(this.columnConfig)
                .filter(col => col.visible)
                .sort((a, b) => a.order - b.order);
        },
        
        /**
         * Gets sortable columns for multi-column sorting
         * 
         * @returns {Array} Array of sortable column configurations
         * @since 1.1.0
         */
        get sortableColumns() {
            return Object.values(this.columnConfig)
                .filter(col => col.sortable)
                .sort((a, b) => a.order - b.order);
        },

        /**
         * Load leads data from server with enhanced error handling
         * 
         * @param {Object} filters Filter criteria object
         * @param {boolean} resetPagination Whether to reset pagination
         * @since 1.0.0
         */
        async loadLeads(filters = {}, resetPagination = false) {
            // Reset error state
            this.hasError = false;
            this.errorMessage = '';
            
            if (resetPagination) {
                this.currentPage = 1;
                this.leads = [];
            }
            
            this.isLoading = true;
            
            // Build API URL with debugging
            const apiUrl = '/Api/V8/leads/filtered';
            const queryParams = new URLSearchParams({
                page: this.currentPage.toString(),
                limit: this.pageSize.toString(),
                sort: this.sortColumns[0]?.field || 'date_modified',
                direction: this.sortColumns[0]?.direction || 'desc',
                ...filters
            });
            
            const fullUrl = `${apiUrl}?${queryParams.toString()}`;
            console.log('Loading leads from:', fullUrl);
            
            try {
                // Check if we're in a valid environment
                if (typeof fetch === 'undefined') {
                    throw new Error('Fetch API not available. This may be an older browser.');
                }
                
                // Enhanced fetch with better error handling
                const response = await fetch(fullUrl, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin' // Include cookies for session authentication
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', Object.fromEntries(response.headers.entries()));
                
                // Check if response is ok
                if (!response.ok) {
                    let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                    try {
                        const errorData = await response.json();
                        if (errorData.errors && errorData.errors.length > 0) {
                            errorMessage = errorData.errors[0].detail || errorMessage;
                        }
                    } catch (parseError) {
                        console.warn('Could not parse error response:', parseError);
                    }
                    throw new Error(errorMessage);
                }
                
                const data = await response.json();
                console.log('Received data:', data);
                
                // Validate response structure
                if (!data || typeof data !== 'object') {
                    throw new Error('Invalid response format: Expected JSON object');
                }
                
                if (!Array.isArray(data.data)) {
                    console.warn('Response data structure:', data);
                    throw new Error('Invalid response format: Expected data array');
                }
                
                // Update state with new data
                if (resetPagination) {
                    this.leads = data.data || [];
                } else {
                    this.leads = [...this.leads, ...(data.data || [])];
                }
                
                this.totalCount = data.totalCount || data.data.length;
                this.currentPage = data.page || this.currentPage;
                
                // Clear initial load flag
                this.isInitialLoad = false;
                
                // Success feedback
                if (data.data.length > 0) {
                    console.log(`Successfully loaded ${data.data.length} leads`);
                    this.announceToScreenReader(`Loaded ${data.data.length} more leads. Total ${this.leads.length} of ${this.totalCount} leads displayed.`);
                } else {
                    console.log('No leads found matching criteria');
                    this.announceToScreenReader('No leads found matching current criteria');
                }
                
            } catch (error) {
                console.error('Error loading leads:', error);
                console.error('Error stack:', error.stack);
                this.hasError = true;
                
                // Enhanced error messaging based on error type
                if (error.message?.includes('401')) {
                    this.errorMessage = 'Your session has expired. Please refresh the page and log in again.';
                } else if (error.message?.includes('403')) {
                    this.errorMessage = 'You do not have permission to view leads. Please contact your administrator.';
                } else if (error.message?.includes('404')) {
                    this.errorMessage = 'The lead data endpoint was not found. Please contact support.';
                } else if (error.message?.includes('500')) {
                    this.errorMessage = 'A server error occurred. Our team has been notified. Please try again later.';
                } else if (!navigator.onLine) {
                    this.errorMessage = 'You appear to be offline. Please check your internet connection and try again.';
                } else if (error.message?.includes('Fetch API not available')) {
                    this.errorMessage = 'Your browser may not support this feature. Please update your browser or contact support.';
                } else {
                    this.errorMessage = `Unable to load lead data: ${error.message}. Please check your connection and try again.`;
                }
                
                // Clear leads on error only if it's initial load
                if (this.isInitialLoad) {
                    this.leads = [];
                }
                
            } finally {
                this.isLoading = false;
            }
        },
        
        /**
         * Announces message to screen readers
         * 
         * @param {string} message Message to announce
         * @since 1.1.0
         */
        announceToScreenReader(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('role', 'status');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'visually-hidden';
            announcement.textContent = message;
            
            document.body.appendChild(announcement);
            
            // Remove after announcement
            setTimeout(() => {
                document.body.removeChild(announcement);
            }, 1000);
        },
        
        /**
         * Generates cache key for filter combination
         * 
         * @param {Object} filters Filter criteria
         * @param {number} page Page number
         * @returns {string} Cache key
         * @since 1.1.0
         */
        generateCacheKey(filters, page) {
            const sortKey = this.sortColumns.map(s => `${s.field}:${s.direction}`).join(',');
            return JSON.stringify({ filters, page, sort: sortKey });
        },
        
        /**
         * Gets cached data if valid
         * 
         * @param {string} cacheKey Cache key
         * @returns {Object|null} Cached data or null
         * @since 1.1.0
         */
        getCachedData(cacheKey) {
            const cached = this.filterCache.get(cacheKey);
            if (cached && Date.now() - cached.timestamp < this.cacheTimeout) {
                return cached.data;
            }
            // Remove expired cache entry
            if (cached) {
                this.filterCache.delete(cacheKey);
            }
            return null;
        },
        
        /**
         * Sets data in cache
         * 
         * @param {string} cacheKey Cache key
         * @param {Object} data Data to cache
         * @since 1.1.0
         */
        setCachedData(cacheKey, data) {
            // Limit cache size to prevent memory issues
            if (this.filterCache.size > 50) {
                // Remove oldest entries
                const firstKey = this.filterCache.keys().next().value;
                this.filterCache.delete(firstKey);
            }
            
            this.filterCache.set(cacheKey, {
                data: data,
                timestamp: Date.now()
            });
        },
        
        /**
         * Clears filter cache
         * 
         * @since 1.1.0
         */
        clearFilterCache() {
            this.filterCache.clear();
            console.log('Filter cache cleared');
        },
        
        /**
         * Builds sort parameters for API request with multi-column support
         * 
         * @returns {Object} Sort parameters for API
         * @since 1.1.0
         */
        buildSortParams() {
            if (this.sortColumns.length === 0) {
                return { sort: 'date_modified', direction: 'desc' };
            }
            
            const primarySort = this.sortColumns[0];
            const params = {
                sort: primarySort.field,
                direction: primarySort.direction
            };
            
            // Add secondary sorts if supported by API
            if (this.sortColumns.length > 1) {
                params.secondary_sorts = this.sortColumns.slice(1).map(sort => 
                    `${sort.field}:${sort.direction}`
                ).join(',');
            }
            
            return params;
        },
        
        /**
         * Builds filter parameters for API request
         * 
         * @param {Object} filters Filter criteria object
         * @returns {Object} API-compatible filter parameters
         * @since 1.0.0
         */
        buildFilterParams(filters) {
            const params = {};
            
            if (filters.search) params.search = filters.search;
            if (filters.campaign) params.campaign_id = filters.campaign;
            if (filters.industry) params.industry = filters.industry;
            if (filters.activity) {
                params.activity_days = filters.activity.days;
                params.activity_type = filters.activity.type;
            }
            if (filters.logic) params.filter_logic = filters.logic;
            
            return params;
        },
        
        /**
         * Adds or updates column sort with multi-column support
         * 
         * @param {string} field Field to sort by
         * @param {boolean} addToSort Whether to add to existing sorts (Ctrl+click)
         * @since 1.1.0
         */
        updateColumnSort(field, addToSort = false) {
            const existingIndex = this.sortColumns.findIndex(sort => sort.field === field);
            
            if (!addToSort) {
                // Single column sort - replace all
                if (existingIndex > -1) {
                    const currentDirection = this.sortColumns[existingIndex].direction;
                    this.sortColumns = [{
                        field,
                        direction: currentDirection === 'asc' ? 'desc' : 'asc',
                        priority: 1
                    }];
                } else {
                    this.sortColumns = [{ field, direction: 'asc', priority: 1 }];
                }
            } else {
                // Multi-column sort - add or update
                if (existingIndex > -1) {
                    // Toggle direction for existing sort
                    this.sortColumns[existingIndex].direction = 
                        this.sortColumns[existingIndex].direction === 'asc' ? 'desc' : 'asc';
                } else {
                    // Add new sort column
                    const nextPriority = Math.max(...this.sortColumns.map(s => s.priority), 0) + 1;
                    this.sortColumns.push({ field, direction: 'asc', priority: nextPriority });
                }
            }
            
            // Reload data with new sort
            this.loadLeads(Alpine.store('leadFilters')?.activeFilters || {}, true);
        },
        
        /**
         * Removes a sort column
         * 
         * @param {string} field Field to remove from sort
         * @since 1.1.0
         */
        removeSortColumn(field) {
            this.sortColumns = this.sortColumns.filter(sort => sort.field !== field);
            if (this.sortColumns.length === 0) {
                this.sortColumns = [{ field: 'date_modified', direction: 'desc', priority: 1 }];
            }
            this.loadLeads(Alpine.store('leadFilters')?.activeFilters || {}, true);
        },
        
        /**
         * Gets sort information for a column
         * 
         * @param {string} field Column field name
         * @returns {Object|null} Sort information or null
         * @since 1.1.0
         */
        getColumnSort(field) {
            return this.sortColumns.find(sort => sort.field === field) || null;
        },
        
        /**
         * Toggles column visibility
         * 
         * @param {string} columnId Column ID to toggle
         * @since 1.1.0
         */
        toggleColumnVisibility(columnId) {
            if (this.columnConfig[columnId]) {
                this.columnConfig[columnId].visible = !this.columnConfig[columnId].visible;
                this.saveColumnPreferences();
            }
        },
        
        /**
         * Updates column width
         * 
         * @param {string} columnId Column ID
         * @param {number} width New width in pixels
         * @since 1.1.0
         */
        updateColumnWidth(columnId, width) {
            if (this.columnConfig[columnId] && this.columnConfig[columnId].resizable) {
                this.columnConfig[columnId].width = Math.max(50, width);
                this.saveColumnPreferences();
            }
        },
        
        /**
         * Reorders columns based on drag and drop
         * 
         * @param {string} draggedId Dragged column ID
         * @param {string} targetId Target column ID
         * @param {string} position Position relative to target ('before' or 'after')
         * @since 1.1.0
         */
        reorderColumns(draggedId, targetId, position = 'after') {
            const draggedCol = this.columnConfig[draggedId];
            const targetCol = this.columnConfig[targetId];
            
            if (!draggedCol || !targetCol) return;
            
            const columns = Object.values(this.columnConfig).sort((a, b) => a.order - b.order);
            const newOrder = [];
            
            columns.forEach(col => {
                if (col.id === draggedId) return; // Skip dragged column
                
                if (col.id === targetId) {
                    if (position === 'before') {
                        newOrder.push(draggedCol);
                        newOrder.push(col);
                    } else {
                        newOrder.push(col);
                        newOrder.push(draggedCol);
                    }
                } else {
                    newOrder.push(col);
                }
            });
            
            // Update order values
            newOrder.forEach((col, index) => {
                this.columnConfig[col.id].order = index;
            });
            
            this.saveColumnPreferences();
        },
        
        /**
         * Saves column preferences to user settings
         * 
         * @since 1.1.0
         */
        async saveColumnPreferences() {
            try {
                const preferences = {
                    columnConfig: this.columnConfig,
                    sortColumns: this.sortColumns
                };
                
                await this.saveUserPreference('lead_table_columns', preferences);
            } catch (error) {
                console.warn('Failed to save column preferences:', error);
            }
        },
        
        /**
         * Loads column preferences from user settings
         * 
         * @since 1.1.0
         */
        async loadColumnPreferences() {
            try {
                const preferences = await this.loadUserPreference('lead_table_columns');
                if (preferences) {
                    if (preferences.columnConfig) {
                        this.columnConfig = { ...this.columnConfig, ...preferences.columnConfig };
                    }
                    if (preferences.sortColumns) {
                        this.sortColumns = preferences.sortColumns;
                    }
                }
            } catch (error) {
                console.warn('Failed to load column preferences:', error);
            }
        },
        
        /**
         * Saves user preference via API
         * 
         * @param {string} key Preference key
         * @param {any} value Preference value
         * @since 1.1.0
         */
        async saveUserPreference(key, value) {
            const response = await fetch('/Api/V8/user/preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ key, value })
            });
            
            if (!response.ok) {
                throw new Error('Failed to save preference');
            }
        },
        
        /**
         * Loads user preference via API
         * 
         * @param {string} key Preference key
         * @returns {any} Preference value
         * @since 1.1.0
         */
        async loadUserPreference(key) {
            const response = await fetch(`/Api/V8/user/preferences/${key}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                if (response.status === 404) return null;
                throw new Error('Failed to load preference');
            }
            
            const data = await response.json();
            return data.value;
        },

        /**
         * Loads next page of data
         * 
         * @since 1.0.0
         */
        loadNextPage() {
            if (!this.isLoading && this.hasMorePages) {
                this.currentPage++;
                this.loadLeads(Alpine.store('leadFilters')?.activeFilters || {}, false);
            }
        },
        
        /**
         * Checks if there are more pages to load
         * 
         * @returns {boolean} Whether more pages are available
         * @since 1.0.0
         */
        get hasMorePages() {
            return this.leads.length < this.totalCount;
        },
        
        /**
         * Toggles selection of a lead
         * 
         * @param {string} leadId Lead ID to toggle
         * @since 1.0.0
         */
        toggleLeadSelection(leadId) {
            const index = this.selectedLeads.indexOf(leadId);
            if (index > -1) {
                this.selectedLeads.splice(index, 1);
            } else {
                this.selectedLeads.push(leadId);
            }
            
            this.selectAll = this.selectedLeads.length === this.leads.length;
        },
        
        /**
         * Toggles selection of all visible leads
         * 
         * @since 1.0.0
         */
        toggleAllSelection() {
            if (this.selectAll) {
                this.selectedLeads = [];
                this.selectAll = false;
            } else {
                this.selectedLeads = this.leads.map(lead => lead.id);
                this.selectAll = true;
            }
            
            // Update bulk actions visibility
            this.bulkActionsVisible = this.selectedLeads.length > 0;
        },
        
        /**
         * Clears all selections
         * 
         * @since 1.0.0
         */
        clearSelection() {
            this.selectedLeads = [];
            this.selectAll = false;
            this.bulkActionsVisible = false;
        },
        
        /**
         * Executes bulk action on selected leads
         * 
         * @param {string} action Bulk action to perform
         * @since 1.1.0
         */
        async executeBulkAction(action) {
            if (this.selectedLeads.length === 0 || this.bulkActionInProgress) {
                return;
            }
            
            try {
                this.bulkActionInProgress = true;
                
                let endpoint, method, body;
                
                switch (action) {
                    case 'assign':
                        // Show user selection modal
                        const userId = await this.showUserSelectionModal();
                        if (!userId) return;
                        
                        endpoint = '/Api/V8/leads/bulk-assign';
                        method = 'POST';
                        body = {
                            lead_ids: this.selectedLeads,
                            assigned_user_id: userId
                        };
                        break;
                        
                    case 'update_status':
                        // Show status selection modal
                        const status = await this.showStatusSelectionModal();
                        if (!status) return;
                        
                        endpoint = '/Api/V8/leads/bulk-update-status';
                        method = 'POST';
                        body = {
                            lead_ids: this.selectedLeads,
                            status: status
                        };
                        break;
                        
                    case 'add_to_campaign':
                        // Show campaign selection modal
                        const campaignId = await this.showCampaignSelectionModal();
                        if (!campaignId) return;
                        
                        endpoint = '/Api/V8/leads/bulk-add-to-campaign';
                        method = 'POST';
                        body = {
                            lead_ids: this.selectedLeads,
                            campaign_id: campaignId
                        };
                        break;
                        
                    case 'delete':
                        // Confirm deletion
                        if (!confirm(`Are you sure you want to delete ${this.selectedLeads.length} lead(s)?`)) {
                            return;
                        }
                        
                        endpoint = '/Api/V8/leads/bulk-delete';
                        method = 'DELETE';
                        body = {
                            lead_ids: this.selectedLeads
                        };
                        break;
                        
                    default:
                        throw new Error(`Unknown bulk action: ${action}`);
                }
                
                const response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(body)
                });
                
                if (!response.ok) {
                    throw new Error(`Bulk action failed: ${response.status} ${response.statusText}`);
                }
                
                const result = await response.json();
                
                // Clear cache as data has changed
                this.clearFilterCache();
                
                // Reload current view
                await this.loadLeads(Alpine.store('leadFilters')?.activeFilters || {}, false);
                
                // Clear selections
                this.clearSelection();
                
                // Show success message
                this.showNotification(`Successfully performed ${action} on ${result.affected_count} lead(s)`, 'success');
                
            } catch (error) {
                console.error('Error executing bulk action:', error);
                this.showNotification(`Failed to perform bulk action: ${error.message}`, 'error');
            } finally {
                this.bulkActionInProgress = false;
            }
        },
        
        /**
         * Exports filtered leads to specified format
         * 
         * @param {string} format Export format (csv/excel)
         * @since 1.1.0
         */
        async exportFilteredLeads(format = 'csv') {
            if (this.exportInProgress) {
                return;
            }
            
            try {
                this.exportInProgress = true;
                
                const filters = Alpine.store('leadFilters')?.activeFilters || {};
                const params = new URLSearchParams({
                    format: format,
                    ...this.buildSortParams(),
                    ...this.buildFilterParams(filters)
                });
                
                // Include selected columns for export
                const visibleColumns = this.visibleColumns
                    .filter(col => col.id !== 'selection' && col.id !== 'actions')
                    .map(col => col.id);
                params.append('columns', visibleColumns.join(','));
                
                const response = await fetch(`/Api/V8/leads/export?${params}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    throw new Error(`Export failed: ${response.status} ${response.statusText}`);
                }
                
                // Get filename from Content-Disposition header
                const contentDisposition = response.headers.get('Content-Disposition');
                const fileNameMatch = contentDisposition?.match(/filename="(.+)"/);
                const fileName = fileNameMatch ? fileNameMatch[1] : `leads_export_${Date.now()}.${format}`;
                
                // Download file
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                this.showNotification(`Export completed: ${fileName}`, 'success');
                
            } catch (error) {
                console.error('Error exporting leads:', error);
                this.showNotification(`Failed to export leads: ${error.message}`, 'error');
            } finally {
                this.exportInProgress = false;
            }
        },
        
        /**
         * Shows notification to user
         * 
         * @param {string} message Notification message
         * @param {string} type Notification type (success/error/warning/info)
         * @since 1.1.0
         */
        showNotification(message, type = 'info') {
            // Dispatch custom event for notification system
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    message: message,
                    type: type,
                    duration: 5000
                }
            }));
        },
        
        /**
         * Shows user selection modal for assigning leads
         * 
         * @returns {Promise<string|null>} Selected user ID or null
         * @since 1.1.0
         */
        async showUserSelectionModal() {
            const users = await this.loadUsers();
            if (users.length === 0) {
                this.showNotification('No users found to assign leads to.', 'warning');
                return null;
            }

            const selectedUser = await new Promise(resolve => {
                const modal = new bootstrap.Modal(document.getElementById('userSelectionModal'));
                const form = document.getElementById('userSelectionForm');
                const userSelect = document.getElementById('userSelect');

                userSelect.innerHTML = ''; // Clear previous options
                users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = user.name;
                    userSelect.appendChild(option);
                });

                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const selectedUserId = userSelect.value;
                    if (selectedUserId) {
                        resolve(selectedUserId);
                        modal.hide();
                    } else {
                        resolve(null);
                        modal.hide();
                    }
                });

                modal.show();
            });

            return selectedUser;
        },
        
        /**
         * Shows status selection modal for bulk status updates
         * 
         * @returns {Promise<string|null>} Selected status or null
         * @since 1.1.0
         */
        async showStatusSelectionModal() {
            const statuses = ['New', 'Assigned', 'In Process', 'Converted', 'Recycled', 'Dead'];
            const selectedStatus = await new Promise(resolve => {
                const modal = new bootstrap.Modal(document.getElementById('statusSelectionModal'));
                const form = document.getElementById('statusSelectionForm');
                const statusSelect = document.getElementById('statusSelect');

                statusSelect.innerHTML = ''; // Clear previous options
                statuses.forEach(status => {
                    const option = document.createElement('option');
                    option.value = status;
                    option.textContent = status;
                    statusSelect.appendChild(option);
                });

                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const selectedStatus = statusSelect.value;
                    if (selectedStatus) {
                        resolve(selectedStatus);
                        modal.hide();
                    } else {
                        resolve(null);
                        modal.hide();
                    }
                });

                modal.show();
            });

            return selectedStatus;
        },
        
        /**
         * Shows campaign selection modal for bulk campaign updates
         * 
         * @returns {Promise<string|null>} Selected campaign ID or null
         * @since 1.1.0
         */
        async showCampaignSelectionModal() {
            const campaigns = await this.loadCampaigns();
            if (campaigns.length === 0) {
                this.showNotification('No campaigns found to add leads to.', 'warning');
                return null;
            }

            const selectedCampaign = await new Promise(resolve => {
                const modal = new bootstrap.Modal(document.getElementById('campaignSelectionModal'));
                const form = document.getElementById('campaignSelectionForm');
                const campaignSelect = document.getElementById('campaignSelect');

                campaignSelect.innerHTML = ''; // Clear previous options
                campaigns.forEach(campaign => {
                    const option = document.createElement('option');
                    option.value = campaign.id;
                    option.textContent = campaign.name;
                    campaignSelect.appendChild(option);
                });

                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const selectedCampaignId = campaignSelect.value;
                    if (selectedCampaignId) {
                        resolve(selectedCampaignId);
                        modal.hide();
                    } else {
                        resolve(null);
                        modal.hide();
                    }
                });

                modal.show();
            });

            return selectedCampaign;
        },
        
        /**
         * Loads users for assignment modal
         * 
         * @returns {Promise<Array>} Array of user objects
         * @since 1.1.0
         */
        async loadUsers() {
            const response = await fetch('/Api/V8/users/list', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to load users');
            }

            const data = await response.json();
            return data.data || [];
        },
        
        /**
         * Loads campaigns for campaign selection modal
         * 
         * @returns {Promise<Array>} Array of campaign objects
         * @since 1.1.0
         */
        async loadCampaigns() {
            const response = await fetch('/Api/V8/campaigns/list', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to load campaigns');
            }

            const data = await response.json();
            return data.data || [];
        }
    });
});

/**
 * Enhanced lead table view component with column management
 * 
 * @returns {Object} Alpine.js component configuration
 * @since 1.1.0
 */
Alpine.data('leadTableView', () => ({
        // Component state
        showMobileView: false,
        isResizing: false,
        resizeStartX: 0,
        resizeStartWidth: 0,
        resizingColumn: null,
        sortableInstance: null,

        /**
         * Component initialization with column management
         * 
         * @since 1.1.0
         */
        async init() {
            // Load user preferences first
            await this.$store.leadData.loadColumnPreferences();
            
            // Listen for filter changes
            window.addEventListener('lead-filters-changed', (event) => {
                this.handleFilterChange(event.detail.filters);
            });
            
            // Load initial data
            this.$store.leadData.loadLeads({}, true);
            
            // Set up responsive behavior
            this.checkMobileView();
            window.addEventListener('resize', () => this.checkMobileView());
            
            // Set up infinite scroll
            this.setupInfiniteScroll();
            
            // Set up column resizing
            this.setupColumnResizing();
            
            // Set up drag-and-drop column reordering
            this.setupColumnDragDrop();
        },

        /**
         * Sets up column resizing functionality
         * 
         * @since 1.1.0
         */
        setupColumnResizing() {
            document.addEventListener('mousemove', (e) => {
                if (this.isResizing && this.resizingColumn) {
                    const diff = e.clientX - this.resizeStartX;
                    const newWidth = this.resizeStartWidth + diff;
                    this.$store.leadData.updateColumnWidth(this.resizingColumn, newWidth);
                }
            });
            
            document.addEventListener('mouseup', () => {
                this.isResizing = false;
                this.resizingColumn = null;
                document.body.style.cursor = '';
            });
        },
        
        /**
         * Sets up drag-and-drop column reordering with SortableJS
         * 
         * @since 1.1.0
         */
        setupColumnDragDrop() {
            this.$nextTick(() => {
                const headerRow = this.$refs.tableHeaderRow;
                if (headerRow && window.Sortable) {
                    // Initialize SortableJS on table header row
                    this.sortableInstance = Sortable.create(headerRow, {
                        animation: 150,
                        handle: '.column-drag-handle',
                        draggable: 'th[data-column-id]',
                        ghostClass: 'column-ghost',
                        chosenClass: 'column-chosen',
                        dragClass: 'column-drag',
                        onStart: (evt) => {
                            document.body.classList.add('column-dragging');
                        },
                        onEnd: (evt) => {
                            document.body.classList.remove('column-dragging');
                            
                            // Get column IDs from data attributes
                            const draggedColumnId = evt.item.getAttribute('data-column-id');
                            const targetColumnId = evt.to.children[evt.newIndex].getAttribute('data-column-id');
                            const position = evt.newIndex > evt.oldIndex ? 'after' : 'before';
                            
                            // Only proceed if we have valid column IDs and the order actually changed
                            if (draggedColumnId && targetColumnId && evt.newIndex !== evt.oldIndex) {
                                this.$store.leadData.reorderColumns(draggedColumnId, targetColumnId, position);
                            }
                        }
                    });
                } else if (!window.Sortable) {
                    console.warn('SortableJS library not loaded. Drag-and-drop column reordering unavailable.');
                }
            });
        },
        
        /**
         * Cleans up SortableJS instance on component destroy
         * 
         * @since 1.1.0
         */
        cleanup() {
            if (this.sortableInstance) {
                this.sortableInstance.destroy();
                this.sortableInstance = null;
            }
        },
        
        /**
         * Starts column resize operation
         * 
         * @param {Event} event Mouse event
         * @param {string} columnId Column being resized
         * @since 1.1.0
         */
        startColumnResize(event, columnId) {
            if (!this.$store.leadData.columnConfig[columnId]?.resizable) return;
            
            this.isResizing = true;
            this.resizingColumn = columnId;
            this.resizeStartX = event.clientX;
            this.resizeStartWidth = this.$store.leadData.columnConfig[columnId].width;
            document.body.style.cursor = 'col-resize';
            event.preventDefault();
        },
        
        /**
         * Handles column sort with keyboard modifier support
         * 
         * @param {Event} event Click event
         * @param {string} field Field to sort by
         * @since 1.1.0
         */
        handleColumnSort(event, field) {
            const addToSort = event.ctrlKey || event.metaKey;
            this.$store.leadData.updateColumnSort(field, addToSort);
        },
        
        /**
         * Gets sort icon class for column with multi-sort indicators
         * 
         * @param {string} field Field name
         * @returns {string} Icon class and priority badge
         * @since 1.1.0
         */
        getSortIcon(field) {
            const sortInfo = this.$store.leadData.getColumnSort(field);
            if (!sortInfo) {
                return 'fa fa-sort text-muted';
            }
            
            const iconClass = sortInfo.direction === 'asc' 
                ? 'fa fa-sort-up text-primary' 
                : 'fa fa-sort-down text-primary';
                
            return iconClass;
        },
        
        /**
         * Gets sort priority badge for multi-column sorting
         * 
         * @param {string} field Field name
         * @returns {string} Priority number or empty string
         * @since 1.1.0
         */
        getSortPriority(field) {
            const sortInfo = this.$store.leadData.getColumnSort(field);
            return sortInfo && this.$store.leadData.sortColumns.length > 1 
                ? sortInfo.priority.toString() 
                : '';
        },

        /**
         * Handles filter changes from the filter component
         * 
         * @param {Object} filters New filter criteria
         * @since 1.0.0
         */
        handleFilterChange(filters) {
            this.$store.leadData.loadLeads(filters, true);
        },
        
        /**
         * Checks if mobile view should be enabled
         * 
         * @since 1.0.0
         */
        checkMobileView() {
            this.showMobileView = window.innerWidth < 768;
        },
        
        /**
         * Sets up infinite scroll for pagination
         * 
         * @since 1.0.0
         */
        setupInfiniteScroll() {
            const tableContainer = this.$refs.tableContainer;
            if (tableContainer) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && this.$store.leadData.hasMorePages && !this.$store.leadData.isLoading) {
                            this.$store.leadData.loadNextPage();
                        }
                    });
                }, { threshold: 0.1 });
                
                this.$watch('$store.leadData.leads', () => {
                    this.$nextTick(() => {
                        const lastRow = tableContainer.querySelector('.lead-row:last-child');
                        if (lastRow) {
                            observer.observe(lastRow);
                        }
                    });
                });
            }
        },
        
        /**
         * Handles lead row click navigation
         * 
         * @param {string} leadId Lead ID to view
         * @since 1.0.0
         */
        viewLeadDetail(leadId) {
            window.location.href = `index.php?module=Leads&action=DetailView&record=${leadId}`;
        },
        
        /**
         * Formats date for display
         * 
         * @param {string} dateString ISO date string
         * @returns {string} Formatted date
         * @since 1.0.0
         */
        formatDate(dateString) {
            if (!dateString) return '';
            
            try {
                return new Date(dateString).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch (error) {
                return dateString;
            }
        },
        
        /**
         * Gets status badge class for lead status
         * 
         * @param {string} status Lead status
         * @returns {string} Bootstrap badge class
         * @since 1.0.0
         */
        getStatusBadgeClass(status) {
            const statusClasses = {
                'New': 'badge bg-success',
                'Assigned': 'badge bg-primary',
                'In Process': 'badge bg-warning text-dark',
                'Converted': 'badge bg-info',
                'Recycled': 'badge bg-secondary',
                'Dead': 'badge bg-danger'
            };
            
            return statusClasses[status] || 'badge bg-secondary';
        }
})); 