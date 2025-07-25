# Lead Filter API Controller Documentation

## Overview

The `LeadFilterController.php` implements the server-side API endpoints for Phase 2, Step 2 of the SuiteCRM modernization project - the Advanced Filter System for the Interactive Lead List View. This controller provides RESTful endpoints for campaign selection, industry filtering, activity-based filters, and complex lead data retrieval with server-side processing for optimal performance.

## Controller Architecture

### Core Responsibilities

1. **Campaign Data Endpoint**: Provides active campaigns for filter dropdown population
2. **Industry Data Endpoint**: Delivers marketing/advertising focused industry categories
3. **Lead Filtering Endpoint**: Processes complex filter criteria and returns paginated results
4. **Input Validation**: Comprehensive sanitization and validation of filter parameters
5. **Query Optimization**: Efficient database querying with proper indexing and joins

### Dependencies

- **Slim Framework 3**: HTTP routing and request/response handling
- **SuiteCRM BeanFactory**: Data access layer integration
- **SugarQuery**: Object-relational mapping for database operations
- **Phase 1 Middleware**: Authentication, validation, and security features
- **JSON:API Response Classes**: Standardized API response formatting

## API Endpoints

### 1. Campaign List Endpoint

#### Endpoint Details
- **Method**: `GET`
- **URL**: `/Api/V8/leads/campaigns/list`
- **Purpose**: Retrieve active campaigns with associated lead counts for filter dropdown

#### Response Format
```json
{
    "data": [
        {
            "id": "campaign-uuid-123",
            "name": "Q1 Marketing Campaign",
            "status": "Active",
            "lead_count": 145
        },
        {
            "id": "campaign-uuid-456", 
            "name": "Product Launch 2024",
            "status": "Planning",
            "lead_count": 67
        }
    ]
}
```

#### Implementation Details

##### Database Query
```sql
SELECT 
    c.id,
    c.name,
    c.status,
    COUNT(l.id) as lead_count
FROM campaigns c
LEFT JOIN prospect_lists_prospects plp ON c.id = plp.prospect_list_id
LEFT JOIN leads l ON plp.related_id = l.id AND plp.related_type = 'Leads'
WHERE c.deleted = 0 
    AND c.status IN ('Active', 'Planning')
GROUP BY c.id, c.name, c.status
HAVING COUNT(l.id) > 0
ORDER BY c.name ASC
```

##### Performance Considerations
- **Joins Optimization**: Left joins to maintain campaign visibility even without leads
- **Status Filtering**: Only active and planning campaigns to reduce irrelevant options
- **Lead Count Aggregation**: Provides context for campaign relevance
- **Sorting**: Alphabetical ordering for improved user experience

##### Error Handling
- Database connection failures
- Query execution errors
- Data formatting issues
- Empty result sets

### 2. Industry List Endpoint

#### Endpoint Details
- **Method**: `GET`
- **URL**: `/Api/V8/leads/industries/list`
- **Purpose**: Provide marketing/advertising focused industry categories for filtering

#### Response Format
```json
{
    "data": [
        {
            "value": "Advertising",
            "label": "Advertising & Marketing"
        },
        {
            "value": "Technology",
            "label": "Technology & Software" 
        },
        {
            "value": "Healthcare",
            "label": "Healthcare & Medical"
        }
    ]
}
```

#### Implementation Details

##### Predefined Industries
The endpoint provides a curated list of marketing/advertising focused industries:

```php
$industries = [
    ['value' => 'Advertising', 'label' => 'Advertising & Marketing'],
    ['value' => 'Media', 'label' => 'Media & Entertainment'],
    ['value' => 'Technology', 'label' => 'Technology & Software'],
    ['value' => 'E-commerce', 'label' => 'E-commerce & Retail'],
    ['value' => 'Healthcare', 'label' => 'Healthcare & Medical'],
    ['value' => 'Financial', 'label' => 'Financial Services'],
    ['value' => 'Real Estate', 'label' => 'Real Estate & Property'],
    ['value' => 'Education', 'label' => 'Education & Training'],
    ['value' => 'Hospitality', 'label' => 'Hospitality & Travel'],
    ['value' => 'Automotive', 'label' => 'Automotive & Transportation'],
    ['value' => 'Manufacturing', 'label' => 'Manufacturing & Industrial'],
    ['value' => 'Professional Services', 'label' => 'Professional Services'],
    ['value' => 'Non-Profit', 'label' => 'Non-Profit & Government'],
    ['value' => 'Other', 'label' => 'Other Industries']
];
```

##### Dynamic Industry Detection
```sql
SELECT DISTINCT primary_address_state as industry
FROM leads 
WHERE deleted = 0 
    AND primary_address_state IS NOT NULL 
    AND primary_address_state != ''
ORDER BY primary_address_state ASC
LIMIT 20
```

##### Data Merging Logic
- Combines predefined marketing-focused industries with database values
- Removes duplicates while preserving predefined priority
- Limits database results to prevent overwhelming dropdown

### 3. Filtered Leads Endpoint

#### Endpoint Details
- **Method**: `POST`
- **URL**: `/Api/V8/leads/filtered`
- **Purpose**: Apply complex filter criteria and return paginated lead data

#### Request Format
```json
{
    "search": "john smith",
    "campaign": "campaign-uuid-123",
    "industry": "Technology",
    "activity": {
        "days": 30,
        "type": "any"
    },
    "logic": "and",
    "offset": 0,
    "limit": 20
}
```

#### Response Format
```json
{
    "data": {
        "leads": [
            {
                "id": "lead-uuid-789",
                "name": "John Smith",
                "first_name": "John",
                "last_name": "Smith",
                "email": "john.smith@example.com",
                "phone": "+1-555-123-4567",
                "account_name": "Tech Solutions Inc",
                "title": "Marketing Director",
                "industry": "Technology",
                "lead_source": "Web Form",
                "status": "New",
                "assigned_user_name": "Sales Rep",
                "date_entered": "2024-01-15 10:30:00",
                "date_modified": "2024-01-15 14:20:00"
            }
        ],
        "pagination": {
            "offset": 0,
            "limit": 20,
            "total": 156,
            "has_more": true
        },
        "filters_applied": {
            "search": "john smith",
            "campaign": "campaign-uuid-123",
            "industry": "Technology",
            "logic": "and"
        }
    }
}
```

## Input Validation and Security

### Validation Rules

#### Search Term Validation
```php
if (!empty($requestData['search'])) {
    $filters['search'] = trim(strip_tags($requestData['search']));
}
```

#### Campaign ID Validation
```php
if (!empty($requestData['campaign'])) {
    $filters['campaign'] = preg_replace('/[^a-zA-Z0-9\-_]/', '', $requestData['campaign']);
}
```

#### Industry Validation
```php
if (!empty($requestData['industry'])) {
    $filters['industry'] = trim(strip_tags($requestData['industry']));
}
```

#### Activity Filter Validation
```php
if (!empty($requestData['activity'])) {
    $activity = $requestData['activity'];
    if (isset($activity['days']) && is_numeric($activity['days'])) {
        $filters['activity'] = [
            'days' => max(1, min(365, (int)$activity['days'])),
            'type' => in_array($activity['type'] ?? 'any', ['any', 'calls', 'emails', 'meetings', 'tasks']) 
                    ? $activity['type'] : 'any'
        ];
    }
}
```

#### Logic Validation
```php
$filters['logic'] = in_array($requestData['logic'] ?? 'and', ['and', 'or']) 
                  ? $requestData['logic'] : 'and';
```

### Security Measures

#### SQL Injection Prevention
- **Parameterized Queries**: All database interactions use SugarQuery ORM
- **Input Sanitization**: Strip HTML tags and validate data types
- **Whitelist Validation**: Enum values validated against allowed options
- **Length Limits**: Reasonable bounds on input parameters

#### Authentication Integration
- **User Context**: Leverages existing SuiteCRM user authentication
- **Permission Checking**: Respects module-level access controls
- **Rate Limiting**: Protected by Phase 1 rate limiting middleware
- **Audit Logging**: Request logging for security monitoring

## Query Optimization

### Lead Filtering Query Structure

#### Base Query Construction
```php
$leadBean = BeanFactory::newBean('Leads');
$query = new SugarQuery();
$query->select(['*']);
$query->from($leadBean);
```

#### Search Filter Implementation
```php
if (!empty($filters['search'])) {
    $searchTerm = $filters['search'];
    $searchCondition = $query->where()->queryOr();
    $searchCondition->contains('first_name', $searchTerm);
    $searchCondition->contains('last_name', $searchTerm);
    $searchCondition->contains('email1', $searchTerm);
    $searchCondition->contains('account_name', $searchTerm);
}
```

#### Campaign Filter with Joins
```php
if (!empty($filters['campaign'])) {
    $query->join('prospect_lists_prospects', ['alias' => 'plp'])
          ->on()->equalsField('plp.related_id', 'id')
          ->on()->equals('plp.related_type', 'Leads');
    $query->where()->equals('plp.prospect_list_id', $filters['campaign']);
}
```

#### Activity-Based Filtering
```php
if (!empty($filters['activity'])) {
    $days = $filters['activity']['days'];
    $activityType = $filters['activity']['type'];
    $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
    
    if ($activityType === 'any') {
        $activityCondition = $query->where()->queryOr();
        $activityCondition->lt('date_modified', $cutoffDate);
        $activityCondition->isNull('date_modified');
    } else {
        $query->where()->lt('date_modified', $cutoffDate);
    }
}
```

### Performance Optimizations

#### Pagination Implementation
```php
$offset = isset($requestData['offset']) ? (int)$requestData['offset'] : 0;
$limit = isset($requestData['limit']) ? min((int)$requestData['limit'], 100) : 20;

$query->limit($limit);
$query->offset($offset);
$query->orderBy('date_modified', 'DESC');
```

#### Count Query Optimization
```php
private function getTotalFilteredCount(array $filters): int
{
    $leadBean = BeanFactory::newBean('Leads');
    $query = new SugarQuery();
    $query->select(['COUNT(*) as total']);
    $query->from($leadBean);
    
    $this->applyFiltersToQuery($query, $filters);
    
    $result = $query->execute();
    return isset($result[0]['total']) ? (int)$result[0]['total'] : 0;
}
```

#### Database Indexing Recommendations
- **leads.first_name**: Index for name searches
- **leads.last_name**: Index for name searches  
- **leads.email1**: Index for email searches
- **leads.account_name**: Index for company searches
- **leads.primary_address_state**: Index for industry filtering
- **leads.date_modified**: Index for activity-based filtering
- **prospect_lists_prospects.prospect_list_id**: Index for campaign joins
- **prospect_lists_prospects.related_id**: Index for lead joins

## Error Handling

### Client Error Responses

#### Invalid Filter Parameters
```json
{
    "errors": [
        {
            "code": "INVALID_FILTER_PARAMS",
            "title": "Invalid filter parameters",
            "detail": "Activity days must be between 1 and 365",
            "status": "400"
        }
    ]
}
```

#### Authentication Errors
```json
{
    "errors": [
        {
            "code": "AUTHENTICATION_REQUIRED",
            "title": "Authentication required",
            "detail": "Valid authentication token required for this endpoint",
            "status": "401"
        }
    ]
}
```

### Server Error Responses

#### Database Query Failures
```json
{
    "errors": [
        {
            "code": "FILTER_LEADS_ERROR",
            "title": "Failed to filter leads",
            "detail": "An error occurred while filtering leads",
            "status": "500"
        }
    ]
}
```

#### Campaign List Errors
```json
{
    "errors": [
        {
            "code": "CAMPAIGN_LIST_ERROR",
            "title": "Failed to retrieve campaigns",
            "detail": "An error occurred while fetching the campaigns list",
            "status": "500"
        }
    ]
}
```

### Error Logging

All errors are logged with appropriate context for debugging:

```php
try {
    // API operation
} catch (\Exception $e) {
    error_log("LeadFilter API Error: " . $e->getMessage() . " | User: " . $GLOBALS['current_user']->id . " | Request: " . json_encode($requestData));
    
    $errorResponse = new ErrorResponse([
        'code' => 'OPERATION_FAILED',
        'title' => 'Operation failed',
        'detail' => 'An error occurred while processing your request',
        'status' => '500'
    ]);
    
    return $errorResponse->createResponse($response);
}
```

## Integration with Phase 1 Infrastructure

### Middleware Integration

The controller leverages all Phase 1 middleware enhancements:

#### Authentication Middleware
- OAuth2 token validation
- User context establishment
- Permission verification

#### Rate Limiting Middleware
- Request frequency limiting
- User-specific rate tracking
- Graceful degradation

#### Security Headers Middleware
- HTTP security headers
- CORS configuration
- Content type validation

#### Validation Middleware
- Request parameter validation
- Input sanitization
- Schema enforcement

#### Request Logging Middleware
- Complete request/response logging
- Performance timing
- Error correlation IDs

### Response Format Compliance

All responses comply with JSON:API specification established in Phase 1:

```php
$dataResponse = new DataResponse($campaigns);
return $dataResponse->createResponse($response);
```

```php
$errorResponse = new ErrorResponse([
    'code' => 'ERROR_CODE',
    'title' => 'Error Title',
    'detail' => 'Detailed error description',
    'status' => 'HTTP_STATUS_CODE'
]);
return $errorResponse->createResponse($response);
```

## Performance Monitoring

### Query Performance Metrics

#### Campaign List Query Performance
- **Target**: < 100ms response time
- **Typical Load**: 50-100 active campaigns
- **Optimization**: Indexed campaigns table with deleted flag filter

#### Industry List Performance  
- **Target**: < 50ms response time
- **Typical Load**: 14 predefined + 20 database industries
- **Optimization**: In-memory predefined list with minimal database query

#### Filtered Leads Query Performance
- **Target**: < 500ms response time for typical filters
- **Typical Load**: 1,000-10,000 lead records with multiple filter criteria
- **Optimization**: Strategic indexing, query optimization, pagination

### Caching Strategy

#### Campaign Data Caching
```php
// Future enhancement: Cache campaign list for 5 minutes
$cacheKey = 'lead_filter_campaigns_' . $userId;
$campaigns = $cache->get($cacheKey);
if (!$campaigns) {
    $campaigns = $this->fetchCampaignsFromDatabase();
    $cache->set($cacheKey, $campaigns, 300); // 5 minutes
}
```

#### Industry Data Caching
```php
// Static predefined list + cached database supplements
$cacheKey = 'lead_filter_industries_db';
$dbIndustries = $cache->get($cacheKey);
if (!$dbIndustries) {
    $dbIndustries = $this->fetchIndustriesFromDatabase();
    $cache->set($cacheKey, $dbIndustries, 3600); // 1 hour
}
```

### Load Testing Considerations

#### Concurrent User Testing
- **Target**: 50 concurrent users applying filters
- **Metrics**: Response time, memory usage, database connections
- **Bottlenecks**: Database query performance, memory allocation

#### Large Dataset Testing
- **Scenario**: 100,000+ lead records with complex filters
- **Optimizations**: Index optimization, query restructuring, pagination limits
- **Monitoring**: Query execution time, memory consumption

## Testing Strategy

### Unit Testing

#### Input Validation Testing
```php
public function testValidateFilterInput()
{
    $controller = new LeadFilterController();
    
    // Test search validation
    $input = ['search' => '<script>alert("xss")</script>John'];
    $filters = $controller->validateFilterInput($input);
    $this->assertEquals('John', $filters['search']);
    
    // Test activity days validation
    $input = ['activity' => ['days' => 500, 'type' => 'calls']];
    $filters = $controller->validateFilterInput($input);
    $this->assertEquals(365, $filters['activity']['days']); // Capped at max
}
```

#### Query Building Testing
```php
public function testApplyFiltersToQuery()
{
    $controller = new LeadFilterController();
    $query = new SugarQuery();
    $leadBean = BeanFactory::newBean('Leads');
    $query->from($leadBean);
    
    $filters = [
        'search' => 'test',
        'campaign' => 'campaign-123',
        'industry' => 'Technology',
        'logic' => 'and'
    ];
    
    $controller->applyFiltersToQuery($query, $filters);
    
    // Verify query structure
    $this->assertContains('prospect_lists_prospects', $query->getJoins());
    $this->assertContains('Technology', $query->getWhereConditions());
}
```

### Integration Testing

#### API Endpoint Testing
```php
public function testGetCampaignsListEndpoint()
{
    $request = $this->createMockRequest('GET', '/Api/V8/leads/campaigns/list');
    $response = $this->createMockResponse();
    
    $controller = new LeadFilterController();
    $result = $controller->getCampaignsList($request, $response, []);
    
    $this->assertEquals(200, $result->getStatusCode());
    
    $body = json_decode((string)$result->getBody(), true);
    $this->assertArrayHasKey('data', $body);
    $this->assertIsArray($body['data']);
}
```

#### Database Integration Testing  
```php
public function testGetFilteredLeadsWithRealData()
{
    // Create test data
    $campaign = SugarTestCampaignUtilities::createCampaign();
    $lead = SugarTestLeadUtilities::createLead();
    
    // Link lead to campaign
    $campaign->load_relationship('prospect_lists');
    $campaign->prospect_lists->add($lead);
    
    // Test filtering
    $request = $this->createRequestWithFilters(['campaign' => $campaign->id]);
    $response = $this->createMockResponse();
    
    $controller = new LeadFilterController();
    $result = $controller->getFilteredLeads($request, $response, []);
    
    $body = json_decode((string)$result->getBody(), true);
    $this->assertGreaterThan(0, count($body['data']['leads']));
    
    // Cleanup
    SugarTestLeadUtilities::removeAllCreatedLeads();
    SugarTestCampaignUtilities::removeAllCreatedCampaigns();
}
```

### Performance Testing

#### Load Testing Script
```php
public function testFilterPerformanceUnderLoad()
{
    $startTime = microtime(true);
    
    for ($i = 0; $i < 100; $i++) {
        $request = $this->createRequestWithFilters([
            'search' => 'test ' . $i,
            'limit' => 20
        ]);
        $response = $this->createMockResponse();
        
        $controller = new LeadFilterController();
        $result = $controller->getFilteredLeads($request, $response, []);
        
        $this->assertEquals(200, $result->getStatusCode());
    }
    
    $endTime = microtime(true);
    $avgTime = ($endTime - $startTime) / 100;
    
    $this->assertLessThan(0.5, $avgTime, 'Average response time should be under 500ms');
}
```

## Security Considerations

### Input Sanitization

All user inputs are thoroughly sanitized:

```php
// Strip HTML tags and scripts
$filters['search'] = trim(strip_tags($requestData['search']));

// Allow only alphanumeric and common ID characters
$filters['campaign'] = preg_replace('/[^a-zA-Z0-9\-_]/', '', $requestData['campaign']);

// Validate against whitelist for enum values
$filters['logic'] = in_array($requestData['logic'], ['and', 'or']) ? $requestData['logic'] : 'and';
```

### SQL Injection Prevention

All database queries use parameterized ORM methods:

```php
// Safe: Uses parameterized ORM
$query->where()->equals('plp.prospect_list_id', $filters['campaign']);

// Unsafe: Would be direct SQL injection risk
// $query->raw("WHERE plp.prospect_list_id = '" . $filters['campaign'] . "'");
```

### Access Control Integration

```php
// Verify user has access to Leads module
if (!ACLController::checkAccess('Leads', 'list', true)) {
    throw new AccessDeniedException('Insufficient permissions');
}

// Filter results based on user permissions
$query->where()->equals('assigned_user_id', $currentUser->id); // If needed
```

### Data Privacy Compliance

- **Field Selection**: Only expose necessary lead fields in responses
- **Permission Filtering**: Respect field-level ACL restrictions
- **Audit Logging**: Log access to sensitive lead data
- **Data Minimization**: Limit response data to required fields only

## Deployment Considerations

### Environment Configuration

#### Development Environment
- **Debug Mode**: Enabled for detailed error reporting
- **Query Logging**: Full SQL query logging for optimization
- **Response Timing**: Detailed performance metrics
- **Test Data**: Automated test data generation

#### Production Environment
- **Error Handling**: User-friendly error messages only
- **Query Optimization**: All recommended indexes in place
- **Caching**: Full caching strategy implemented
- **Monitoring**: Performance monitoring and alerting

### Database Migration Requirements

#### Required Indexes
```sql
-- Performance optimization indexes
CREATE INDEX idx_leads_first_name ON leads(first_name);
CREATE INDEX idx_leads_last_name ON leads(last_name);
CREATE INDEX idx_leads_email1 ON leads(email1);
CREATE INDEX idx_leads_account_name ON leads(account_name);
CREATE INDEX idx_leads_industry ON leads(primary_address_state);
CREATE INDEX idx_leads_date_modified ON leads(date_modified);
CREATE INDEX idx_plp_prospect_list_id ON prospect_lists_prospects(prospect_list_id);
CREATE INDEX idx_plp_related_id ON prospect_lists_prospects(related_id);
```

#### Configuration Updates
```php
// config_override.php additions for optimal performance
$sugar_config['lead_filter_config'] = [
    'max_results_per_page' => 100,
    'default_results_per_page' => 20,
    'cache_duration_campaigns' => 300, // 5 minutes
    'cache_duration_industries' => 3600, // 1 hour
    'enable_query_logging' => false, // Production: false
];
```

---

**Version**: 1.0.0  
**Last Updated**: January 15, 2024  
**Authors**: SuiteCRM Modernization Team  
**Dependencies**: Slim Framework 3, SuiteCRM BeanFactory, SugarQuery, Phase 1 Middleware Stack 