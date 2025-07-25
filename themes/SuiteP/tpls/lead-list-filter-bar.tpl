{*
 * @fileoverview Lead List Advanced Filter Bar Template
 * 
 * Responsive Bootstrap 5 filter interface for the lead list view with Alpine.js
 * reactive components. Provides campaign selection, activity filters, industry
 * filtering, and filter combination logic.
 * 
 * Key Features:
 * - Responsive design with collapsible advanced filters
 * - Bootstrap 5 components with custom styling
 * - Alpine.js reactive data binding
 * - Filter tag display and removal
 * - Saved filter management
 * - Clear all filters functionality
 * 
 * Dependencies:
 * - Bootstrap 5 CSS framework
 * - Alpine.js for reactivity
 * - lead-list-filter.js component
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 *}

<div x-data="leadListFilter" class="lead-filter-bar bg-light p-3 mb-3 rounded shadow-sm">
    {* Filter Bar Header *}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-primary">
            <i class="fa fa-filter me-2"></i>
            Lead Filters
            <span x-show="hasActiveFilters" 
                  class="badge bg-primary ms-2" 
                  x-text="activeFilterCount"></span>
        </h5>
        
        <div class="btn-toolbar" role="toolbar">
            {* Toggle Advanced Filters *}
            <button type="button" 
                    class="btn btn-outline-secondary btn-sm me-2"
                    x-on:click="$store.leadFilters.showAdvancedFilters = !$store.leadFilters.showAdvancedFilters">
                <i class="fa fa-cog me-1"></i>
                <span x-text="advancedFiltersToggleText"></span>
            </button>
            
            {* Clear All Filters *}
            <button type="button" 
                    class="btn btn-outline-danger btn-sm me-2"
                    x-show="hasActiveFilters"
                    x-on:click="clearAllFilters">
                <i class="fa fa-times me-1"></i>
                Clear All
            </button>
            
            {* Save Filter *}
            <button type="button" 
                    class="btn btn-outline-success btn-sm"
                    x-show="hasActiveFilters"
                    x-on:click="showSaveFilter">
                <i class="fa fa-save me-1"></i>
                Save Filter
            </button>
        </div>
    </div>
    
    {* Error Display *}
    <div x-show="$store.leadFilters.hasError" 
         class="alert alert-danger alert-dismissible fade show mb-3" 
         role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i>
        <span x-text="$store.leadFilters.errorMessage"></span>
        <button type="button" 
                class="btn-close" 
                x-on:click="$store.leadFilters.hasError = false"></button>
    </div>
    
    {* Basic Filters Row *}
    <div class="row g-3 mb-3">
        {* Search Input *}
        <div class="col-md-4">
            <label for="lead-search" class="form-label small fw-bold">Search Leads</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       id="lead-search"
                       placeholder="Name, email, company..."
                       x-model="searchTerm"
                       autocomplete="off">
                <button class="btn btn-outline-secondary" 
                        type="button"
                        x-show="searchTerm"
                        x-on:click="searchTerm = ''">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        
        {* Campaign Filter *}
        <div class="col-md-4">
            <label for="campaign-filter" class="form-label small fw-bold">Campaign</label>
            <select class="form-select" 
                    id="campaign-filter"
                    x-model="selectedCampaign">
                <option value="">All Campaigns</option>
                <template x-for="campaign in $store.leadFilters.availableCampaigns" :key="campaign.id">
                    <option :value="campaign.id" x-text="campaign.name"></option>
                </template>
            </select>
        </div>
        
        {* Industry Filter *}
        <div class="col-md-4">
            <label for="industry-filter" class="form-label small fw-bold">
                Industry 
                <small class="text-muted">(Marketing/Advertising Priority)</small>
            </label>
            <select class="form-select" 
                    id="industry-filter"
                    x-model="selectedIndustry">
                <option value="">All Industries</option>
                <optgroup label="Marketing/Advertising Focus">
                    <template x-for="industry in $store.leadFilters.availableIndustries.filter(i => i.isPriority)" :key="'priority-' + industry.value">
                        <option :value="industry.value" x-text="industry.label"></option>
                    </template>
                </optgroup>
                <optgroup label="Other Industries">
                    <template x-for="industry in $store.leadFilters.availableIndustries.filter(i => !i.isPriority)" :key="'other-' + industry.value">
                        <option :value="industry.value" x-text="industry.label"></option>
                    </template>
                </optgroup>
            </select>
        </div>
    </div>
    
    {* Advanced Filters *}
    <div x-show="$store.leadFilters.showAdvancedFilters" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="border-top pt-3">
         
        <h6 class="text-secondary mb-3">
            <i class="fa fa-cogs me-2"></i>
            Advanced Filters
        </h6>
        
        <div class="row g-3 mb-3">
            {* Activity Filter *}
            <div class="col-md-6">
                <label for="activity-days" class="form-label small fw-bold">No Activity In</label>
                <div class="input-group">
                    <input type="number" 
                           class="form-control" 
                           id="activity-days"
                           placeholder="Number of days"
                           min="1"
                           max="365"
                           x-model="activityDays">
                    <span class="input-group-text">days</span>
                </div>
            </div>
            
            {* Activity Type *}
            <div class="col-md-6">
                <label for="activity-type" class="form-label small fw-bold">Activity Type</label>
                <select class="form-select" 
                        id="activity-type"
                        x-model="activityType">
                    <option value="any">Any Activity</option>
                    <option value="calls">Calls</option>
                    <option value="emails">Emails</option>
                    <option value="meetings">Meetings</option>
                    <option value="tasks">Tasks</option>
                </select>
            </div>
        </div>
        
        {* Filter Logic *}
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Filter Logic</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" 
                           class="btn-check" 
                           name="filter-logic" 
                           id="logic-and" 
                           value="and"
                           x-model="filterLogic">
                    <label class="btn btn-outline-primary" for="logic-and">
                        <i class="fa fa-plus-circle me-1"></i>
                        AND (All conditions)
                    </label>
                    
                    <input type="radio" 
                           class="btn-check" 
                           name="filter-logic" 
                           id="logic-or" 
                           value="or"
                           x-model="filterLogic">
                    <label class="btn btn-outline-primary" for="logic-or">
                        <i class="fa fa-circle-o me-1"></i>
                        OR (Any condition)
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    {* Active Filter Tags *}
    <div x-show="hasActiveFilters" class="mt-3">
        <h6 class="text-secondary mb-2">Active Filters:</h6>
        <div class="d-flex flex-wrap gap-2">
            {* Search Tag *}
            <span x-show="searchTerm" 
                  class="badge bg-primary d-flex align-items-center">
                <span>Search: "<span x-text="searchTerm"></span>"</span>
                <button type="button" 
                        class="btn-close btn-close-white ms-2 small"
                        x-on:click="searchTerm = ''"></button>
            </span>
            
            {* Campaign Tag *}
            <span x-show="selectedCampaign" 
                  class="badge bg-info d-flex align-items-center">
                <span>Campaign: <span x-text="$store.leadFilters.availableCampaigns.find(c => c.id === selectedCampaign)?.name || selectedCampaign"></span></span>
                <button type="button" 
                        class="btn-close btn-close-white ms-2 small"
                        x-on:click="selectedCampaign = ''"></button>
            </span>
            
            {* Industry Tag *}
            <span x-show="selectedIndustry" 
                  class="badge bg-success d-flex align-items-center">
                <span>Industry: <span x-text="$store.leadFilters.availableIndustries.find(i => i.value === selectedIndustry)?.label || selectedIndustry"></span></span>
                <button type="button" 
                        class="btn-close btn-close-white ms-2 small"
                        x-on:click="selectedIndustry = ''"></button>
            </span>
            
            {* Activity Tag *}
            <span x-show="activityDays" 
                  class="badge bg-warning text-dark d-flex align-items-center">
                <span>No <span x-text="activityType"></span> in <span x-text="activityDays"></span> days</span>
                <button type="button" 
                        class="btn-close ms-2 small"
                        x-on:click="activityDays = null"></button>
            </span>
        </div>
    </div>
    
    {* Saved Filters *}
    <div x-show="$store.leadFilters.savedFilters.length > 0" class="mt-3">
        <h6 class="text-secondary mb-2">Saved Filters:</h6>
        <div class="d-flex flex-wrap gap-2">
            <template x-for="savedFilter in $store.leadFilters.savedFilters" :key="savedFilter.id">
                <div class="btn-group" role="group">
                    <button type="button" 
                            class="btn btn-outline-secondary btn-sm"
                            x-on:click="loadSavedFilter(savedFilter.id)"
                            x-text="savedFilter.name"></button>
                    <button type="button" 
                            class="btn btn-outline-danger btn-sm"
                            x-on:click="$store.leadFilters.deleteSavedFilter(savedFilter.id)">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </template>
        </div>
    </div>
    
    {* Loading Indicator *}
    <div x-show="$store.leadFilters.isLoading" 
         class="text-center mt-3">
        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="text-muted">Applying filters...</span>
    </div>
</div>

{* Save Filter Modal *}
<div x-show="showSaveFilterModal" 
     class="modal fade show d-block" 
     tabindex="-1" 
     style="background-color: rgba(0,0,0,0.5);"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Save Current Filter</h5>
                <button type="button" 
                        class="btn-close" 
                        x-on:click="showSaveFilterModal = false"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="filter-name" class="form-label">Filter Name</label>
                    <input type="text" 
                           class="form-control" 
                           id="filter-name"
                           placeholder="Enter a name for this filter..."
                           x-model="newFilterName"
                           x-on:keyup.enter="saveFilter()">
                </div>
                <div class="text-muted small">
                    <strong>Current filters:</strong>
                    <ul class="list-unstyled mt-2">
                        <li x-show="searchTerm">• Search: "<span x-text="searchTerm"></span>"</li>
                        <li x-show="selectedCampaign">• Campaign: <span x-text="$store.leadFilters.availableCampaigns.find(c => c.id === selectedCampaign)?.name || selectedCampaign"></span></li>
                        <li x-show="selectedIndustry">• Industry: <span x-text="$store.leadFilters.availableIndustries.find(i => i.value === selectedIndustry)?.label || selectedIndustry"></span></li>
                        <li x-show="activityDays">• No <span x-text="activityType"></span> in <span x-text="activityDays"></span> days</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-secondary" 
                        x-on:click="showSaveFilterModal = false">Cancel</button>
                <button type="button" 
                        class="btn btn-primary"
                        x-on:click="saveFilter()"
                        :disabled="!newFilterName || !newFilterName.trim()">
                    <i class="fa fa-save me-1"></i>
                    Save Filter
                </button>
            </div>
        </div>
    </div>
</div> 