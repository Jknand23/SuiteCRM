# New Features Implementation Plan

## Overview
This document outlines the implementation plan for three new features replacing the remaining unimplemented features in the SuiteCRM modernization project:
1. Customer Health Score
2. Quick Note Capture 
3. Customer Interaction Summary Generator

---

## 1. Customer Health Score Feature

**Description**: Automated customer health scoring system that analyzes account activity, engagement, and opportunities to provide at-a-glance customer relationship status with color-coded indicators (green/yellow/red) and numerical scores (0-100).

### Implementation Checklist
- [ ] Define dropdown options for health_status_list
- [ ] Create custom fields for health_score and health_status on Accounts module
- [ ] Create custom fields for health_score and health_status on Contacts module
- [ ] Implement logic hook files with depth protection
- [ ] Create health score calculation service class with error handling
- [ ] Implement caching mechanism for performance optimization
- [ ] Implement scheduled job following RunnableSchedulerJob interface
- [ ] Create database migration script for existing records
- [ ] Configure ACL permissions for health score fields
- [ ] Add health indicators to list views (Accounts and Contacts)
- [ ] Add health score display to detail views
- [ ] Create health score legend/help documentation
- [ ] Add unit tests for health score calculations
- [ ] Create user documentation

### Scoring Algorithm Components
1. **Activity Frequency (40% weight)**
   - Last activity within 7 days: 100 points
   - Last activity within 30 days: 70 points
   - Last activity within 90 days: 40 points
   - No activity > 90 days: 0 points

2. **Email Engagement (30% weight)**
   - Email response rate > 50%: 100 points
   - Email response rate 25-50%: 70 points
   - Email response rate < 25%: 30 points

3. **Opportunity Progress (30% weight)**
   - Active opportunities in closing stage: 100 points
   - Active opportunities in mid-stage: 70 points
   - Stalled opportunities (>30 days no update): 30 points
   - No active opportunities: 0 points

---

## 2. Quick Note Capture Feature

**Description**: A floating action button (FAB) that appears on record detail views, allowing users to instantly capture notes with automatic parent record linking. Features keyboard shortcuts (Ctrl+Shift+N), auto-save functionality, and seamless integration with SuiteCRM's existing Notes module.

### Implementation Checklist
- [x] Extend PopupQuickCreate class for auto-population
- [x] Create AJAX endpoint for quick note creation  
- [x] Implement SUGAR namespace JavaScript integration
- [x] Build FAB component using SuiteCRM's popup infrastructure
- [x] Create quick note modal using existing modal systems
- [x] Add context detection for parent record identification
- [x] Implement ACL permission checks for note creation
- [x] Add SuiteCRM theme-compliant CSS styling
- [x] Add keyboard shortcuts (Ctrl+Shift+N) integration
- [x] Implement error handling and validation with standard SuiteCRM patterns
- [x] Add performance optimization with caching strategy
- [x] Add auto-save functionality with conflict resolution
- [x] Create user preference for FAB visibility
- [ ] Add unit tests and integration tests

---

## 3. Customer Interaction Summary Generator

**Description**: A comprehensive customer interaction timeline generator that aggregates activities (tasks, meetings, calls, emails, notes) from related records using SuiteCRM's existing relationship queries and display patterns, following the established Activities module conventions.

### Implementation Checklist
- [x] Extend existing Activities popup functionality instead of creating new module
- [x] Implement relationship-based data aggregation using SugarBean methods
- [x] Create activity date field mapping for proper chronological sorting
- [x] Integrate with existing export utilities from SuiteCRM
- [x] Add ACL integration for access control checks
- [x] Implement timezone handling using SuiteCRM's TimeDate utilities
- [x] Add input sanitization and SQL injection protection
- [x] Create comprehensive error handling for failed queries
- [ ] Extend Calendar module's activity aggregation patterns
- [x] Add filtering interface (date range, activity type, user)
- [x] Create timeline view template following SuiteCRM patterns
- [x] Integrate button using metadata approach (not JavaScript injection)
- [x] Create module registration files (vardefs, bean, loader)
- [ ] Add activity grouping by day/week with performance optimization
- [ ] Implement caching using existing SuiteCRM cache mechanisms

### Technical Implementation Details

#### Data Aggregation Service (Following SuiteCRM Patterns)
```php
// custom/modules/InteractionSummary/InteractionSummaryService.php
class InteractionSummaryService extends SugarBean {
    
    public function getInteractionTimeline($focus, $dateFrom, $dateTo, $activityTypes = array()) {
        global $current_user, $timedate;
        
        $interactions = array();
        
        // ACL Check - Critical security requirement
        if (!$focus->ACLAccess('view')) {
            throw new Exception('Access denied to parent record');
        }
        
        // Sanitize inputs to prevent SQL injection
        $dateFrom = $this->db->quote($timedate->to_db_date($dateFrom, false));
        $dateTo = $this->db->quote($timedate->to_db_date($dateTo, false));
        
        try {
            // Use SuiteCRM's existing relationship patterns instead of custom queries
            $activityModules = $this->getActivityModules($activityTypes);
            
            foreach ($activityModules as $module => $config) {
                if ($focus->load_relationship($config['relationship'])) {
                    $linkedBeans = $focus->get_linked_beans(
                        $config['relationship'], 
                        $config['beanClass'],
                        array(),
                        0,
                        -1,
                        0,
                        $this->buildDateWhereClause($config['dateField'], $dateFrom, $dateTo)
                    );
                    
                    foreach ($linkedBeans as $bean) {
                        if ($bean->ACLAccess('view')) { // ACL check for each record
                            $interactions[] = $this->formatInteraction($bean, $module, $config);
                        }
                    }
                }
            }
            
            // Sort using proper date fields per activity type
            usort($interactions, function($a, $b) {
                return strtotime($b['sort_date']) - strtotime($a['sort_date']);
            });
            
        } catch (Exception $e) {
            // Comprehensive error handling
            $GLOBALS['log']->error("InteractionSummary: Failed to aggregate interactions - " . $e->getMessage());
            throw new Exception('Failed to load interaction data');
        }
        
        return $interactions;
    }
    
    private function getActivityModules($activityTypes = array()) {
        // Follow SuiteCRM's existing activity module patterns from Activities popup
        $modules = array(
            'Emails' => array(
                'relationship' => 'emails',
                'beanClass' => 'Email',
                'dateField' => 'date_sent_received', // Different date fields per type
                'displayField' => 'name'
            ),
            'Calls' => array(
                'relationship' => 'calls',
                'beanClass' => 'Call', 
                'dateField' => 'date_start',
                'displayField' => 'name'
            ),
            'Meetings' => array(
                'relationship' => 'meetings',
                'beanClass' => 'Meeting',
                'dateField' => 'date_start',
                'displayField' => 'name'
            ),
            'Tasks' => array(
                'relationship' => 'tasks',
                'beanClass' => 'Task',
                'dateField' => 'date_due',
                'displayField' => 'name'
            ),
            'Notes' => array(
                'relationship' => 'notes',
                'beanClass' => 'Note',
                'dateField' => 'date_modified',
                'displayField' => 'name'
            )
        );
        
        // Filter by requested activity types if specified
        if (!empty($activityTypes)) {
            $modules = array_intersect_key($modules, array_flip($activityTypes));
        }
        
        return $modules;
    }
    
    private function buildDateWhereClause($dateField, $dateFrom, $dateTo) {
        global $timedate;
        
        $whereClause = "";
        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = "{$dateField} BETWEEN {$dateFrom} AND {$dateTo}";
        }
        
        return $whereClause;
    }
    
    private function formatInteraction($bean, $type, $config) {
        global $timedate, $current_user;
        
        // Get appropriate date field value for sorting
        $sortDate = $bean->{$config['dateField']};
        
        // Handle timezone conversion using SuiteCRM utilities
        $displayDate = $timedate->to_display_date_time($sortDate, true, true, $current_user);
        
        return array(
            'id' => $bean->id,
            'type' => $type,
            'name' => $bean->{$config['displayField']},
            'description' => isset($bean->description) ? $bean->description : '',
            'date_entered' => $bean->date_entered,
            'date_modified' => $bean->date_modified,
            'sort_date' => $sortDate, // For proper chronological sorting
            'display_date' => $displayDate,
            'assigned_user_name' => $bean->assigned_user_name,
            'icon' => $this->getIconForType($type),
            'color' => $this->getColorForType($type),
        );
    }
    
    // Leverage existing export utilities instead of custom logic
    public function exportToExcel($interactions) {
        require_once('include/export_utils.php');
        
        $export = new ExportUtils();
        // Use SuiteCRM's predefined field orders for activities
        $fieldOrder = $export->getFieldOrderMapping('Activities');
        
        return $export->exportToExcel($interactions, $fieldOrder);
    }
}
```

#### Timeline View Template (Following SuiteCRM Patterns)
```smarty
{* Extend existing Activities template patterns from PopupBody.tpl *}
{* custom/modules/InteractionSummary/tpls/TimelineView.tpl *}

{* Use SuiteCRM's standard form structure *}
<form id="interaction-timeline-form" class="edit view">
    <table class="tabForm" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="tabFormHeader" colspan="4">
                <h4>{$MOD.LBL_INTERACTION_TIMELINE}</h4>
            </td>
        </tr>
        <tr>
            <td class="tabDetailViewDF" width="20%">
                {$MOD.LBL_DATE_FROM}:
            </td>
            <td class="tabDetailViewDF" width="30%">
                {* Use SuiteCRM's existing date picker component *}
                <input type="text" name="date_from" id="date_from" 
                       value="{$dateFrom}" class="date-field" 
                       onchange="SUGAR.util.callOnChangeListers(this);">
                {sugar_getimage name="jscalendar" attr='onclick="showCalendar(\"date_from\");"'}
            </td>
            <td class="tabDetailViewDF" width="20%">
                {$MOD.LBL_DATE_TO}:
            </td>
            <td class="tabDetailViewDF" width="30%">
                <input type="text" name="date_to" id="date_to" 
                       value="{$dateTo}" class="date-field"
                       onchange="SUGAR.util.callOnChangeListers(this);">
                {sugar_getimage name="jscalendar" attr='onclick="showCalendar(\"date_to\");"'}
            </td>
        </tr>
        <tr>
            <td class="tabDetailViewDF">
                {$MOD.LBL_ACTIVITY_TYPE}:
            </td>
            <td class="tabDetailViewDF">
                {* Follow SuiteCRM's dropdown pattern *}
                <select name="activity_type" id="activity_type" onchange="filterActivities();">
                    <option value="">{$MOD.LBL_ALL_ACTIVITIES}</option>
                    <option value="Notes" {if $activityType == 'Notes'}selected{/if}>{$MOD.LBL_NOTES}</option>
                    <option value="Emails" {if $activityType == 'Emails'}selected{/if}>{$MOD.LBL_EMAILS}</option>
                    <option value="Calls" {if $activityType == 'Calls'}selected{/if}>{$MOD.LBL_CALLS}</option>
                    <option value="Meetings" {if $activityType == 'Meetings'}selected{/if}>{$MOD.LBL_MEETINGS}</option>
                    <option value="Tasks" {if $activityType == 'Tasks'}selected{/if}>{$MOD.LBL_TASKS}</option>
                </select>
            </td>
            <td class="tabDetailViewDF">
                {$MOD.LBL_ASSIGNED_USER}:
            </td>
            <td class="tabDetailViewDF">
                {* Use existing user selector component *}
                {html_options name="assigned_user_id" options=$ASSIGNED_USER_OPTIONS 
                             selected=$assignedUserId onchange="filterActivities();"}
            </td>
        </tr>
    </table>
</form>

{* Timeline container following SuiteCRM's list view patterns *}
<div class="list view" style="margin-top: 20px;">
    <table class="list view table-responsive" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr class="pagination">
            <td colspan="6">
                <table class="tabForm" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td>
                            {* Export buttons following SuiteCRM button patterns *}
                            <input type="button" class="button" value="{$MOD.LBL_EXPORT_EXCEL}" 
                                   onclick="exportInteractions('excel');">
                            <input type="button" class="button" value="{$MOD.LBL_EXPORT_PDF}" 
                                   onclick="exportInteractions('pdf');">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        {* Timeline header *}
        <tr class="listViewThS1">
            <th scope="col" width="15%">{$MOD.LBL_DATE}</th>
            <th scope="col" width="10%">{$MOD.LBL_TYPE}</th>
            <th scope="col" width="25%">{$MOD.LBL_SUBJECT}</th>
            <th scope="col" width="35%">{$MOD.LBL_DESCRIPTION}</th>
            <th scope="col" width="15%">{$MOD.LBL_ASSIGNED_TO}</th>
        </tr>
        
        {* Timeline content following Activities module display pattern *}
        {foreach from=$interactions item=interaction name=interactionLoop}
            <tr class="{cycle values="oddListRowS1,evenListRowS1"} interaction-row" data-type="{$interaction.type|lower}">
                <td class="listViewTdS1">
                    {* Use proper timezone-converted display date *}
                    <span class="sugar_field" data-date="{$interaction.sort_date}">
                        {$interaction.display_date}
                    </span>
                </td>
                <td class="listViewTdS1">
                    {* Activity type with icon following SuiteCRM icon patterns *}
                    <span class="suitepicon suitepicon-module-{$interaction.type|lower}"></span>
                    {$interaction.type}
                </td>
                <td class="listViewTdS1">
                    {* Clickable link to record detail view *}
                    <a href="index.php?module={$interaction.type}&action=DetailView&record={$interaction.id}"
                       class="listViewTdLinkS1">
                        {$interaction.name|escape:'html'}
                    </a>
                </td>
                <td class="listViewTdS1">
                    {* Truncated description with proper HTML escaping *}
                    <span class="description-text">
                        {$interaction.description|escape:'html'|truncate:150:"..."}
                    </span>
                </td>
                <td class="listViewTdS1">
                    {$interaction.assigned_user_name|escape:'html'}
                </td>
            </tr>
        {foreachelse}
            <tr class="oddListRowS1">
                <td colspan="5" class="listViewTdS1" style="text-align: center; padding: 20px;">
                    {$MOD.LBL_NO_INTERACTIONS_FOUND}
                </td>
            </tr>
        {/foreach}
    </table>
</div>

{* JavaScript following SuiteCRM's SUGAR namespace patterns *}
<script type="text/javascript">
{literal}
SUGAR.namespace('InteractionTimeline');

SUGAR.InteractionTimeline = {
    
    filterActivities: function() {
        // Use SUGAR.util for AJAX calls following SuiteCRM patterns
        var params = {
            'module': 'InteractionSummary',
            'action': 'GetTimeline',
            'to_pdf': true,
            'date_from': document.getElementById('date_from').value,
            'date_to': document.getElementById('date_to').value,
            'activity_type': document.getElementById('activity_type').value,
            'assigned_user_id': document.getElementById('assigned_user_id').value,
            'parent_type': '{/literal}{$parentType}{literal}',
            'parent_id': '{/literal}{$parentId}{literal}'
        };
        
        SUGAR.util.doWhen("typeof AjaxObject != 'undefined'", function() {
            AjaxObject.startRequest(callback, null, params);
        });
    },
    
    exportInteractions: function(format) {
        // Follow existing export patterns
        var exportUrl = 'index.php?module=InteractionSummary&action=Export&format=' + format;
        exportUrl += '&parent_type={/literal}{$parentType}{literal}&parent_id={/literal}{$parentId}{literal}';
        exportUrl += '&date_from=' + document.getElementById('date_from').value;
        exportUrl += '&date_to=' + document.getElementById('date_to').value;
        
        window.open(exportUrl, '_blank');
    }
};

// Bind to existing SuiteCRM event system
if (typeof SUGAR.util != 'undefined') {
    SUGAR.util.doWhen("document.getElementById('date_from')", function() {
        // Initialize date pickers using SuiteCRM's calendar
        Calendar.setup({
            inputField: 'date_from',
            ifFormat: '{/literal}{$CALENDAR_FORMAT}{literal}',
            button: 'date_from_trigger',
            singleClick: true,
            step: 1
        });
        
        Calendar.setup({
            inputField: 'date_to', 
            ifFormat: '{/literal}{$CALENDAR_FORMAT}{literal}',
            button: 'date_to_trigger',
            singleClick: true,
            step: 1
        });
    });
}
{/literal}
</script>
```

### Implementation Approach & SuiteCRM Integration Strategy

#### Key Architectural Decisions Based on SuiteCRM Conventions

1. **Relationship-Based Data Aggregation**
   - Leverage existing `$focus->get_linked_beans()` pattern instead of custom SQL queries
   - Use SuiteCRM's built-in relationship loading mechanisms from Activities module
   - Follow established patterns from `Popup_picker.php:110-119` for relationship queries

2. **Date Field Handling Per Activity Type**
   - Emails: Use `date_sent_received` field for chronological sorting
   - Calls/Meetings: Use `date_start` field for proper timeline ordering  
   - Tasks: Use `date_due` field for task-specific timeline placement
   - Notes: Use `date_modified` field for note timeline positioning
   - Implement proper timezone conversion using SuiteCRM's `TimeDate` utilities

3. **Security & Access Control Integration**
   - Implement ACL checks at both parent record and individual activity levels
   - Use `$bean->ACLAccess('view')` for each retrieved record
   - Add input sanitization using `$this->db->quote()` for SQL injection protection
   - Follow SuiteCRM's established security patterns from existing modules

4. **Performance Optimization Strategy**
   - Extend Calendar module's existing activity aggregation patterns
   - Use SuiteCRM's built-in caching mechanisms instead of custom cache implementation
   - Leverage existing relationship loading optimizations
   - Follow performance patterns from `Calendar.php:260-280`

5. **Export Integration**
   - Use existing `export_utils.php` instead of creating custom export logic
   - Leverage predefined field orders for activities from `export_utils.php:872-875`
   - Follow established export patterns for consistency with other modules

6. **Error Handling & Logging**
   - Use `$GLOBALS['log']->error()` for consistent logging with SuiteCRM patterns
   - Implement comprehensive exception handling for failed database queries
   - Add graceful degradation for missing relationships or permissions

#### Critical Implementation Notes

- **Extend, Don't Replace**: Build upon existing Activities popup functionality rather than creating entirely new module structure
- **Relationship Queries**: Use `$focus->load_relationship()` and `get_linked_beans()` patterns established in Activities module
- **Template Patterns**: Follow list view and form patterns from existing SuiteCRM modules for UI consistency
- **JavaScript Integration**: Use SUGAR namespace and existing AJAX patterns for frontend functionality
- **Module Structure**: Follow standard SuiteCRM MVC separation as seen in Calendar and Activities modules

This approach ensures seamless integration with SuiteCRM's existing architecture while maintaining performance, security, and user experience consistency.

---

## Implementation Timeline

### Week 1: Customer Health Score
- Day 1-2: Create custom fields and database schema
- Day 3-4: Implement calculation logic and hooks
- Day 5: UI integration and testing

### Week 2: Quick Note Capture
- Day 1-2: Create FAB component and modal
- Day 3-4: Backend integration and auto-linking
- Day 5: Polish and user testing

### Week 3: Customer Interaction Summary
- Day 1-2: Module creation and data aggregation
- Day 3-4: Timeline view and filtering
- Day 5: Export functionality and testing

---

## Testing Strategy

### Unit Tests
- Health score calculation algorithms
- Note auto-linking logic
- Data aggregation queries

### Integration Tests
- Health score updates on activity creation
- Quick note creation from various modules
- Timeline data accuracy

### User Acceptance Tests
- Health score visibility and understanding
- Quick note capture workflow efficiency
- Interaction summary usefulness

---

## Documentation Requirements

### User Documentation
- Health score interpretation guide
- Quick note capture tutorial
- Interaction summary report guide

### Technical Documentation
- API endpoints for health scores
- Quick note JavaScript API
- Interaction summary data structure

### Training Materials
- Video walkthrough of health scoring
- Quick note best practices
- Report generation tutorials 