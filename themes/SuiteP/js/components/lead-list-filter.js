/**
 * @fileoverview Interactive Lead List Advanced Filter Component
 * 
 * Alpine.js reactive component for advanced lead list filtering capabilities.
 * Provides campaign selection, activity-based filters, industry filtering,
 * and complex filter combination logic with AND/OR operators.
 * 
 * Key Features:
 * - Real-time filter application with debounced input
 * - Campaign dropdown with "All Campaigns" option
 * - Activity-based filters ("No activity in X days")
 * - Industry-specific filtering for marketing/advertising
 * - Filter combination logic with AND/OR operators
 * - Persistent user preferences via local storage
 * - Responsive Bootstrap 5 design
 * - Filter tag display and removal
 * - Saved filter functionality
 * 
 * Dependencies:
 * - Alpine.js 3.x for reactivity
 * - Bootstrap 5 for styling
 * - SuiteCRM API endpoints for data fetching
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

document.addEventListener('alpine:init', () => {
    // Global filter state store
    Alpine.store('leadFilters', {
        // Filter state
        activeFilters: {},
        savedFilters: [],
        isLoading: false,
        hasError: false,
        errorMessage: '',
        
        // Filter options
        availableCampaigns: [],
        availableIndustries: [],
        
        // UI state
        showAdvancedFilters: false,
        filterBarCollapsed: false,
        
        // Initialize store
        init() {
            this.loadSavedFilters();
            this.loadFilterOptions();
        },
        
        /**
         * Loads saved filters from local storage
         * 
         * @since 1.0.0
         */
        loadSavedFilters() {
            try {
                const saved = localStorage.getItem('lead_filters_saved');
                if (saved) {
                    this.savedFilters = JSON.parse(saved);
                }
            } catch (error) {
                console.warn('Error loading saved filters:', error);
                this.savedFilters = [];
            }
        },
        
        /**
         * Loads available filter options from API
         * 
         * @since 1.0.0
         */
        async loadFilterOptions() {
            try {
                this.isLoading = true;
                
                // Load campaigns
                const campaignResponse = await fetch('/Api/V8/leads/campaigns/list', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (campaignResponse.ok) {
                    const campaignData = await campaignResponse.json();
                    this.availableCampaigns = campaignData.data || [];
                }
                
                // Load industries
                const industryResponse = await fetch('/Api/V8/leads/industries/list', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (industryResponse.ok) {
                    const industryData = await industryResponse.json();
                    this.availableIndustries = industryData.data || [];
                }
                
            } catch (error) {
                console.error('Error loading filter options:', error);
                this.hasError = true;
                this.errorMessage = 'Failed to load filter options. Please refresh the page.';
            } finally {
                this.isLoading = false;
            }
        },
        
        /**
         * Applies active filters and triggers data update
         * 
         * @since 1.0.0
         */
        async applyFilters() {
            try {
                this.isLoading = true;
                this.hasError = false;
                
                // Trigger custom event for lead list component to listen
                window.dispatchEvent(new CustomEvent('lead-filters-changed', {
                    detail: {
                        filters: this.activeFilters,
                        timestamp: Date.now()
                    }
                }));
                
            } catch (error) {
                console.error('Error applying filters:', error);
                this.hasError = true;
                this.errorMessage = 'Failed to apply filters. Please try again.';
            } finally {
                this.isLoading = false;
            }
        },
        
        /**
         * Clears all active filters
         * 
         * @since 1.0.0
         */
        clearAllFilters() {
            this.activeFilters = {};
            this.applyFilters();
        },
        
        /**
         * Saves current filter combination
         * 
         * @param {string} filterName Name for the saved filter
         * @since 1.0.0
         */
        saveCurrentFilter(filterName) {
            if (!filterName || !Object.keys(this.activeFilters).length) {
                return;
            }
            
            const savedFilter = {
                id: Date.now().toString(),
                name: filterName,
                filters: { ...this.activeFilters },
                createdAt: new Date().toISOString()
            };
            
            this.savedFilters.push(savedFilter);
            
            try {
                localStorage.setItem('lead_filters_saved', JSON.stringify(this.savedFilters));
            } catch (error) {
                console.warn('Error saving filter:', error);
            }
        },
        
        /**
         * Loads a saved filter
         * 
         * @param {string} filterId ID of the saved filter
         * @since 1.0.0
         */
        loadSavedFilter(filterId) {
            const savedFilter = this.savedFilters.find(f => f.id === filterId);
            if (savedFilter) {
                this.activeFilters = { ...savedFilter.filters };
                this.applyFilters();
            }
        },
        
        /**
         * Deletes a saved filter
         * 
         * @param {string} filterId ID of the saved filter to delete
         * @since 1.0.0
         */
        deleteSavedFilter(filterId) {
            this.savedFilters = this.savedFilters.filter(f => f.id !== filterId);
            
            try {
                localStorage.setItem('lead_filters_saved', JSON.stringify(this.savedFilters));
            } catch (error) {
                console.warn('Error deleting saved filter:', error);
            }
        }
    });
});

/**
 * Main lead list filter component
 * 
 * Provides the main filter interface with campaign selection, activity filters,
 * industry filtering, and complex filter logic.
 * 
 * @returns {Object} Alpine.js component configuration
 * @since 1.0.0
 */
function leadListFilter() {
    return {
        // Component state
        searchTerm: '',
        selectedCampaign: '',
        selectedIndustry: '',
        activityDays: null,
        activityType: 'any',
        filterLogic: 'and', // 'and' or 'or'
        
        // UI state
        showSaveFilterModal: false,
        newFilterName: '',
        
        // Debounce timer
        searchDebounceTimer: null,
        
        /**
         * Component initialization
         * 
         * @since 1.0.0
         */
        init() {
            // Initialize filter store
            this.$store.leadFilters.init();
            
            // Watch for search term changes
            this.$watch('searchTerm', (value) => {
                this.debouncedSearch(value);
            });
            
            // Watch for filter changes
            this.$watch('selectedCampaign', () => this.updateFilters());
            this.$watch('selectedIndustry', () => this.updateFilters());
            this.$watch('activityDays', () => this.updateFilters());
            this.$watch('activityType', () => this.updateFilters());
            this.$watch('filterLogic', () => this.updateFilters());
        },
        
        /**
         * Debounced search function to prevent excessive API calls
         * 
         * @param {string} searchValue Current search term
         * @since 1.0.0
         */
        debouncedSearch(searchValue) {
            clearTimeout(this.searchDebounceTimer);
            
            this.searchDebounceTimer = setTimeout(() => {
                this.updateFilters();
            }, 300); // 300ms debounce
        },
        
        /**
         * Updates the active filters in the store
         * 
         * @since 1.0.0
         */
        updateFilters() {
            const filters = {};
            
            // Add search filter
            if (this.searchTerm && this.searchTerm.trim()) {
                filters.search = this.searchTerm.trim();
            }
            
            // Add campaign filter
            if (this.selectedCampaign && this.selectedCampaign !== 'all') {
                filters.campaign = this.selectedCampaign;
            }
            
            // Add industry filter
            if (this.selectedIndustry && this.selectedIndustry !== 'all') {
                filters.industry = this.selectedIndustry;
            }
            
            // Add activity filter
            if (this.activityDays && this.activityDays > 0) {
                filters.activity = {
                    days: this.activityDays,
                    type: this.activityType
                };
            }
            
            // Set filter logic
            filters.logic = this.filterLogic;
            
            // Update store
            this.$store.leadFilters.activeFilters = filters;
            this.$store.leadFilters.applyFilters();
        },
        
        /**
         * Gets count of active filters for display
         * 
         * @returns {number} Number of active filters
         * @since 1.0.0
         */
        get activeFilterCount() {
            const filters = this.$store.leadFilters.activeFilters;
            let count = 0;
            
            if (filters.search) count++;
            if (filters.campaign) count++;
            if (filters.industry) count++;
            if (filters.activity) count++;
            
            return count;
        },
        
        /**
         * Clears all filters
         * 
         * @since 1.0.0
         */
        clearAllFilters() {
            this.searchTerm = '';
            this.selectedCampaign = '';
            this.selectedIndustry = '';
            this.activityDays = null;
            this.activityType = 'any';
            this.$store.leadFilters.clearAllFilters();
        },
        
        /**
         * Shows the save filter modal
         * 
         * @since 1.0.0
         */
        showSaveFilter() {
            if (this.activeFilterCount > 0) {
                this.newFilterName = '';
                this.showSaveFilterModal = true;
            }
        },
        
        /**
         * Saves the current filter combination
         * 
         * @since 1.0.0
         */
        saveFilter() {
            if (this.newFilterName && this.newFilterName.trim()) {
                this.$store.leadFilters.saveCurrentFilter(this.newFilterName.trim());
                this.showSaveFilterModal = false;
                this.newFilterName = '';
            }
        },
        
        /**
         * Loads a saved filter
         * 
         * @param {string} filterId ID of saved filter
         * @since 1.0.0
         */
        loadSavedFilter(filterId) {
            const savedFilter = this.$store.leadFilters.savedFilters.find(f => f.id === filterId);
            if (savedFilter) {
                const filters = savedFilter.filters;
                
                // Set component state from saved filter
                this.searchTerm = filters.search || '';
                this.selectedCampaign = filters.campaign || '';
                this.selectedIndustry = filters.industry || '';
                this.activityDays = filters.activity ? filters.activity.days : null;
                this.activityType = filters.activity ? filters.activity.type : 'any';
                this.filterLogic = filters.logic || 'and';
                
                // Load in store
                this.$store.leadFilters.loadSavedFilter(filterId);
            }
        }
    };
} 