{*
 * @fileoverview Lead Table View Component Template with Column Management
 * 
 * Enhanced responsive Bootstrap 5 template for displaying lead data with advanced
 * column management capabilities including show/hide, reordering, resizing, and
 * multi-column sorting. Integrates with Alpine.js lead-table-view.js component.
 * 
 * Key Features:
 * - Dynamic column configuration with show/hide controls
 * - Resizable columns with drag handles
 * - Multi-column sorting with priority indicators
 * - Column reordering with drag-and-drop support
 * - Responsive table design with horizontal scrolling
 * - Loading states with skeleton screen placeholders
 * - Comprehensive error handling with user-friendly messages
 * - Row selection with bulk actions support
 * - Infinite scroll pagination
 * - Mobile-optimized card view for small screens
 * 
 * Dependencies:
 * - Bootstrap 5 CSS framework
 * - Alpine.js for reactivity
 * - lead-table-view.js component (v1.1.0)
 * - Font Awesome for icons
 * - SortableJS for column drag-and-drop
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.1.0
 * @since 2024-01-15
 *}

{* Load SortableJS library for drag-and-drop column reordering *}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<div x-data="leadTableView" class="lead-table-container">
    {* Column Management Panel *}
    <div x-show="$store.leadData.columnManagerVisible" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="column-manager-panel card border-primary mb-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fa fa-columns me-2"></i>
                Manage Columns
            </h6>
            <button type="button" 
                    class="btn btn-sm btn-outline-light"
                    x-on:click="$store.leadData.columnManagerVisible = false">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Column Visibility</h6>
                    <div class="column-visibility-list">
                        <template x-for="column in Object.values($store.leadData.columnConfig).filter(c => c.id !== 'selection' && c.id !== 'actions').sort((a, b) => a.order - b.order)" :key="column.id">
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       :id="'col-' + column.id"
                                       :checked="column.visible"
                                       x-on:change="$store.leadData.toggleColumnVisibility(column.id)">
                                <label class="form-check-label" :for="'col-' + column.id">
                                    <span x-text="column.label"></span>
                                    <small class="text-muted ms-1" x-show="column.resizable">
                                        (<span x-text="column.width"></span>px)
                                    </small>
                                </label>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Active Sorts</h6>
                    <div class="active-sorts-list">
                        <template x-if="$store.leadData.sortColumns.length === 0">
                            <p class="text-muted small">No active sorting</p>
                        </template>
                        <template x-for="sort in $store.leadData.sortColumns.sort((a, b) => a.priority - b.priority)" :key="sort.field">
                            <div class="d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                <div>
                                    <span class="badge bg-primary me-2" x-text="sort.priority"></span>
                                    <span x-text="$store.leadData.columnConfig[sort.field]?.label || sort.field"></span>
                                    <i :class="sort.direction === 'asc' ? 'fa fa-sort-up' : 'fa fa-sort-down'" 
                                       class="ms-1 text-primary"></i>
                                </div>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger"
                                        x-on:click="$store.leadData.removeSortColumn(sort.field)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fa fa-info-circle me-1"></i>
                            Hold Ctrl/Cmd while clicking column headers for multi-column sort
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* Error State *}
    <div x-show="$store.leadData.hasError && !$store.leadData.isLoading" 
         class="alert alert-danger alert-dismissible fade show mb-4" 
         role="alert">
        <div class="d-flex align-items-start">
            <i class="fa fa-exclamation-triangle fa-2x me-3 mt-1"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2">Error Loading Leads</h5>
                <p class="mb-2" x-text="$store.leadData.errorMessage"></p>
                <div class="mt-3">
                    <button type="button" 
                            class="btn btn-outline-danger btn-sm me-2"
                            x-on:click="$store.leadData.loadLeads({}, true)">
                        <i class="fa fa-refresh me-1"></i>
                        Try Again
                    </button>
                    <button type="button" 
                            class="btn btn-outline-secondary btn-sm"
                            x-on:click="$store.leadData.hasError = false">
                        <i class="fa fa-times me-1"></i>
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    {* Results Summary and Controls *}
    <div x-show="!$store.leadData.hasError" class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <div class="lead-count-summary me-4">
                <span x-show="!$store.leadData.isInitialLoad" class="text-muted">
                    Showing <strong x-text="$store.leadData.leads.length"></strong> 
                    of <strong x-text="$store.leadData.totalCount"></strong> leads
                </span>
                <span x-show="$store.leadData.isInitialLoad" class="text-muted">
                    Loading leads...
                </span>
            </div>
            
            {* Column Manager Toggle *}
            <button type="button" 
                    class="btn btn-outline-secondary btn-sm me-2"
                    x-on:click="$store.leadData.columnManagerVisible = !$store.leadData.columnManagerVisible"
                    :class="{ 'active': $store.leadData.columnManagerVisible }">
                <i class="fa fa-columns me-1"></i>
                Columns
            </button>
        </div>
        
        {* Bulk Actions *}
        <div x-show="$store.leadData.selectedLeads.length > 0" class="bulk-actions">
            <div class="btn-group" role="group">
                <button type="button" 
                        class="btn btn-outline-primary btn-sm dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        :disabled="$store.leadData.bulkActionInProgress">
                    <i class="fa fa-users me-1"></i>
                    Actions (<span x-text="$store.leadData.selectedLeads.length"></span>)
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" 
                           href="#"
                           x-on:click.prevent="$store.leadData.executeBulkAction('assign')">
                            <i class="fa fa-user-plus me-2"></i>Assign to User
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                           href="#"
                           x-on:click.prevent="$store.leadData.executeBulkAction('update_status')">
                            <i class="fa fa-flag me-2"></i>Update Status
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                           href="#"
                           x-on:click.prevent="$store.leadData.executeBulkAction('add_to_campaign')">
                            <i class="fa fa-bullhorn me-2"></i>Add to Campaign
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" 
                           href="#"
                           x-on:click.prevent="$store.leadData.executeBulkAction('delete')">
                            <i class="fa fa-trash me-2"></i>Delete Selected
                        </a>
                    </li>
                </ul>
                <button type="button" 
                        class="btn btn-outline-secondary btn-sm ms-2"
                        x-on:click="$store.leadData.clearSelection()">
                    <i class="fa fa-times me-1"></i>
                    Clear Selection
                </button>
            </div>
        </div>
        
        {* Export Options *}
        <div class="export-actions">
            <div class="btn-group" role="group">
                <button type="button" 
                        class="btn btn-outline-success btn-sm dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        :disabled="$store.leadData.exportInProgress">
                    <i class="fa fa-download me-1"></i>
                    Export
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" 
                           href="#"
                           x-on:click.prevent="$store.leadData.exportFilteredLeads('csv')">
                            <i class="fa fa-file-text-o me-2"></i>Export as CSV
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                           href="#"
                           x-on:click.prevent="$store.leadData.exportFilteredLeads('excel')">
                            <i class="fa fa-file-excel-o me-2"></i>Export as Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    {* Desktop Table View *}
    <div x-show="!showMobileView" class="table-responsive" x-ref="tableContainer">
        <table class="table table-hover table-sm lead-table">
            <thead class="table-light sticky-top">
                <tr x-ref="tableHeaderRow">
                    {* Dynamic Column Headers *}
                    <template x-for="column in $store.leadData.visibleColumns" :key="column.id">
                        <th class="position-relative"
                            :class="{ 'sortable-header': column.sortable }"
                            :style="'width: ' + column.width + 'px; min-width: ' + column.width + 'px;'"
                            :data-column-id="column.id">
                            
                            {* Column Content Container *}
                            <div class="column-header-content d-flex align-items-center justify-content-between"
                                 x-on:click="column.sortable ? handleColumnSort($event, column.id) : null">
                                
                                {* Drag Handle for reorderable columns *}
                                <template x-if="column.id !== 'selection' && column.id !== 'actions'">
                                    <div class="column-drag-handle me-2" 
                                         title="Drag to reorder columns"
                                         x-on:click.stop>
                                        <i class="fa fa-grip-vertical text-muted"></i>
                                    </div>
                                </template>
                                
                                {* Selection Column Special Case *}
                                <template x-if="column.id === 'selection'">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               :checked="$store.leadData.selectAll"
                                               x-on:change="$store.leadData.toggleAllSelection()"
                                               aria-label="Select all leads">
                                    </div>
                                </template>
                                
                                {* Regular Columns *}
                                <template x-if="column.id !== 'selection'">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <span x-text="column.label"></span>
                                        
                                        {* Sort Indicators *}
                                        <template x-if="column.sortable">
                                            <div class="sort-indicators ms-2">
                                                <i :class="getSortIcon(column.id)"></i>
                                                <span x-show="getSortPriority(column.id)" 
                                                      class="sort-priority-badge badge bg-primary ms-1"
                                                      x-text="getSortPriority(column.id)"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            
                            {* Column Resize Handle *}
                            <template x-if="column.resizable">
                                <div class="column-resize-handle"
                                     x-on:mousedown="startColumnResize($event, column.id)"
                                     title="Resize column">
                                </div>
                            </template>
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                {* Loading Skeleton Rows *}
                <template x-if="$store.leadData.isLoading && $store.leadData.leads.length === 0">
                    <template x-for="i in 5" :key="'skeleton-' + i">
                        <tr class="skeleton-row">
                            <template x-for="column in $store.leadData.visibleColumns" :key="'skeleton-' + column.id + '-' + i">
                                <td :style="'width: ' + column.width + 'px;'">
                                    <div class="skeleton-content"
                                         :class="{
                                             'skeleton-checkbox': column.id === 'selection',
                                             'skeleton-badge': column.id === 'status', 
                                             'skeleton-actions': column.id === 'actions',
                                             'skeleton-text': !['selection', 'status', 'actions'].includes(column.id)
                                         }"></div>
                                </td>
                            </template>
                        </tr>
                    </template>
                </template>
                
                {* Lead Data Rows *}
                <template x-for="lead in $store.leadData.leads" :key="lead.id">
                    <tr class="lead-row" 
                        :class="{ 'table-active': $store.leadData.selectedLeads.includes(lead.id) }">
                        
                        {* Dynamic Column Cells *}
                        <template x-for="column in $store.leadData.visibleColumns" :key="column.id + '-' + lead.id">
                            <td :style="'width: ' + column.width + 'px;'">
                                
                                {* Selection Column *}
                                <template x-if="column.id === 'selection'">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               :checked="$store.leadData.selectedLeads.includes(lead.id)"
                                               x-on:change="$store.leadData.toggleLeadSelection(lead.id)"
                                               :aria-label="'Select lead: ' + (lead.first_name || '') + ' ' + (lead.last_name || '')">
                                    </div>
                                </template>
                                
                                {* Name Column *}
                                <template x-if="column.id === 'name'">
                                    <a href="#" 
                                       class="text-decoration-none fw-bold text-primary"
                                       x-on:click.prevent="viewLeadDetail(lead.id)"
                                       x-text="(lead.first_name || '') + ' ' + (lead.last_name || '')"></a>
                                </template>
                                
                                {* Email Column *}
                                <template x-if="column.id === 'email'">
                                    <a :href="'mailto:' + lead.email" 
                                       class="text-decoration-none"
                                       x-text="lead.email || 'No email'"></a>
                                </template>
                                
                                {* Company Column *}
                                <template x-if="column.id === 'account_name'">
                                    <span x-text="lead.account_name || 'No company'"></span>
                                </template>
                                
                                {* Status Column *}
                                <template x-if="column.id === 'status'">
                                    <span :class="getStatusBadgeClass(lead.status)" 
                                          x-text="lead.status || 'Unknown'"></span>
                                </template>
                                
                                {* Industry Column *}
                                <template x-if="column.id === 'industry'">
                                    <span x-text="lead.industry || 'Not specified'"></span>
                                </template>
                                
                                {* Phone Column *}
                                <template x-if="column.id === 'phone_work'">
                                    <a :href="'tel:' + lead.phone_work" 
                                       class="text-decoration-none"
                                       x-text="lead.phone_work || 'No phone'"></a>
                                </template>
                                
                                {* Lead Source Column *}
                                <template x-if="column.id === 'lead_source'">
                                    <span x-text="lead.lead_source || 'Unknown'"></span>
                                </template>
                                
                                {* Date Modified Column *}
                                <template x-if="column.id === 'date_modified'">
                                    <span x-text="formatDate(lead.date_modified)"></span>
                                </template>
                                
                                {* Actions Column *}
                                <template x-if="column.id === 'actions'">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" 
                                                class="btn btn-outline-primary btn-sm"
                                                x-on:click="viewLeadDetail(lead.id)"
                                                title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-sm"
                                                title="Edit Lead">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                </template>
                            </td>
                        </template>
                    </tr>
                </template>
                
                {* Empty State *}
                <template x-if="!$store.leadData.isLoading && $store.leadData.leads.length === 0 && !$store.leadData.hasError">
                    <tr>
                        <td :colspan="$store.leadData.visibleColumns.length" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fa fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No leads found</h5>
                                <p class="text-muted">Try adjusting your filters or search criteria.</p>
                            </div>
                        </td>
                    </tr>
                </template>
                
                {* Loading More Indicator *}
                <template x-if="$store.leadData.isLoading && $store.leadData.leads.length > 0">
                    <tr>
                        <td :colspan="$store.leadData.visibleColumns.length" class="text-center py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="text-muted">Loading more leads...</span>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    
    {* Mobile Card View *}
    <div x-show="showMobileView" class="mobile-lead-cards">
        {* Loading Skeleton Cards *}
        <template x-if="$store.leadData.isLoading && $store.leadData.leads.length === 0">
            <template x-for="i in 3" :key="'mobile-skeleton-' + i">
                <div class="card mb-3 skeleton-card">
                    <div class="card-body">
                        <div class="skeleton-text skeleton-name mb-2"></div>
                        <div class="skeleton-text skeleton-email mb-2"></div>
                        <div class="skeleton-badge mb-2"></div>
                        <div class="skeleton-text skeleton-company"></div>
                    </div>
                </div>
            </template>
        </template>
        
        {* Lead Cards *}
        <template x-for="lead in $store.leadData.leads" :key="'mobile-' + lead.id">
            <div class="card mb-3 lead-card" 
                 :class="{ 'border-primary': $store.leadData.selectedLeads.includes(lead.id) }">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">
                            <a href="#" 
                               class="text-decoration-none text-primary"
                               x-on:click.prevent="viewLeadDetail(lead.id)"
                               x-text="(lead.first_name || '') + ' ' + (lead.last_name || '')"></a>
                        </h6>
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   :checked="$store.leadData.selectedLeads.includes(lead.id)"
                                   x-on:change="$store.leadData.toggleLeadSelection(lead.id)"
                                   :aria-label="'Select lead: ' + (lead.first_name || '') + ' ' + (lead.last_name || '')">
                        </div>
                    </div>
                    
                    <div class="card-text">
                        <div class="mb-1">
                            <small class="text-muted">Email:</small>
                            <a :href="'mailto:' + lead.email" 
                               class="text-decoration-none ms-1"
                               x-text="lead.email || 'No email'"></a>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">Company:</small>
                            <span class="ms-1" x-text="lead.account_name || 'No company'"></span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span :class="getStatusBadgeClass(lead.status)" 
                                  x-text="lead.status || 'Unknown'"></span>
                            <small class="text-muted" x-text="formatDate(lead.date_modified)"></small>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        
        {* Mobile Empty State *}
        <template x-if="!$store.leadData.isLoading && $store.leadData.leads.length === 0 && !$store.leadData.hasError">
            <div class="text-center py-5">
                <i class="fa fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No leads found</h5>
                <p class="text-muted">Try adjusting your filters or search criteria.</p>
            </div>
        </template>
        
        {* Mobile Loading More *}
        <template x-if="$store.leadData.isLoading && $store.leadData.leads.length > 0">
            <div class="text-center py-3">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-muted">Loading more leads...</span>
                </div>
            </div>
        </template>
    </div>
</div>

{* After the Mobile Card View section, add Modal Templates for Bulk Actions *}

{* User Selection Modal *}
<div class="modal fade" id="userSelectionModal" tabindex="-1" aria-labelledby="userSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userSelectionModalLabel">Assign Leads to User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userSelectionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userSelect" class="form-label">Select User</label>
                        <select class="form-select" id="userSelect" required>
                            <option value="">Choose a user...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Leads</button>
                </div>
            </form>
        </div>
    </div>
</div>

{* Status Selection Modal *}
<div class="modal fade" id="statusSelectionModal" tabindex="-1" aria-labelledby="statusSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusSelectionModalLabel">Update Lead Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusSelectionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Select Status</label>
                        <select class="form-select" id="statusSelect" required>
                            <option value="">Choose a status...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

{* Campaign Selection Modal *}
<div class="modal fade" id="campaignSelectionModal" tabindex="-1" aria-labelledby="campaignSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="campaignSelectionModalLabel">Add Leads to Campaign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="campaignSelectionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="campaignSelect" class="form-label">Select Campaign</label>
                        <select class="form-select" id="campaignSelect" required>
                            <option value="">Choose a campaign...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add to Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>

{* Real-time Update Indicator *}
<div x-data="{ show: $store.leadSSE && $store.leadSSE.isConnected }" 
     x-show="show"
     class="position-fixed bottom-0 end-0 p-3"
     style="z-index: 1000;">
    <div class="toast show" role="status" aria-live="polite" aria-atomic="true">
        <div class="toast-header">
            <i class="fa fa-refresh fa-spin text-success me-2"></i>
            <strong class="me-auto">Real-time Updates</strong>
            <small>Connected</small>
        </div>
        <div class="toast-body small">
            <div x-show="$store.leadSSE">
                <span x-text="$store.leadSSE.eventsReceived"></span> updates received
            </div>
        </div>
    </div>
</div>

{* Load Real-time Updates Component *}
<script src="themes/SuiteP/js/components/lead-realtime-updates.js"></script>

{* Enhanced Component Styles *}
<style>
/* Lead Table Styles */
.lead-table-container {
    background: var(--theme-surface, #ffffff);
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

/* Column Manager Panel */
.column-manager-panel {
    border: 2px solid var(--theme-primary, #007bff) !important;
}

.column-visibility-list {
    max-height: 300px;
    overflow-y: auto;
}

.active-sorts-list {
    max-height: 300px;
    overflow-y: auto;
}

/* Column Header Enhancements */
.sortable-header {
    cursor: pointer;
    user-select: none;
    position: relative;
}

.sortable-header:hover {
    background-color: var(--theme-surface-variant, #f8f9fa);
}

.column-header-content {
    padding: 0.5rem 0.75rem;
    min-height: 40px;
}

.sort-indicators {
    display: flex;
    align-items: center;
}

.sort-priority-badge {
    font-size: 0.6rem;
    min-width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Column Resize Handle */
.column-resize-handle {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    cursor: col-resize;
    background: transparent;
    border-right: 2px solid transparent;
    transition: border-color 0.2s ease;
}

.column-resize-handle:hover {
    border-right-color: var(--theme-primary, #007bff);
}

/* Column Drag and Drop Styles */
.column-drag-handle {
    cursor: grab;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
    opacity: 0.6;
}

.column-drag-handle:hover {
    background-color: var(--theme-surface-variant, #f8f9fa);
    opacity: 1;
}

.column-drag-handle:active {
    cursor: grabbing;
}

/* SortableJS drag states */
.column-ghost {
    opacity: 0.4;
    background-color: var(--theme-primary, #007bff);
    color: white;
}

.column-chosen {
    background-color: var(--theme-primary-light, #e3f2fd);
    box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.15);
}

.column-drag {
    opacity: 0.8;
    transform: rotate(2deg);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.2);
}

/* Body state during column dragging */
.column-dragging {
    user-select: none;
}

.column-dragging .sortable-header:not(.column-chosen):not(.column-ghost) {
    opacity: 0.7;
}

/* Drag indicator */
.column-dragging .column-drag-handle {
    opacity: 1;
    background-color: var(--theme-primary, #007bff);
    color: white;
}

/* Table Column Widths */
.lead-table th,
.lead-table td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Enhanced Skeleton Loading Styles */
.skeleton-row td {
    padding: 1rem 0.75rem;
}

.skeleton-content {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 0.25rem;
    height: 1rem;
    width: 100%;
}

.skeleton-text { width: 80%; }
.skeleton-badge { width: 60%; height: 1.5rem; }
.skeleton-checkbox { width: 1rem; height: 1rem; }
.skeleton-actions { width: 70%; }

.skeleton-card .skeleton-content {
    margin-bottom: 0.5rem;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Mobile Card Styles */
.lead-card {
    transition: all 0.2s ease-in-out;
}

.lead-card:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
}

/* Empty State Styles */
.empty-state {
    padding: 2rem;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .bulk-actions .btn-group {
        flex-direction: column;
    }
    
    .bulk-actions .btn {
        margin-bottom: 0.25rem;
    }
    
    .column-manager-panel .row {
        margin: 0;
    }
    
    .column-manager-panel .col-md-6 {
        padding: 0 0.5rem;
        margin-bottom: 1rem;
    }
}

/* Column manager active state */
.btn.active {
    background-color: var(--theme-primary, #007bff);
    border-color: var(--theme-primary, #007bff);
    color: white;
}
</style> 