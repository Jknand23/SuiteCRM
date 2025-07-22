# SuiteCRM Codebase Documentation Plan

## Objective
Document every PHP file in the SuiteCRM codebase with corresponding `<filename>_docs.md` files following the established documentation standards.

## Documentation Standards
- File naming: `<filename.extension>_docs.md` in same directory as source file
- Include PHPDoc-style header with @fileoverview, @package, @copyright, @license
- Structure sections in order: database operations, internal API calls, external API calls, UI functionality, associated tests
- Api directory already completed - exclude from this plan

## Implementation Plan

### Phase 1: Root Directory Files
- [x] campaign_tracker.php
- [x] index.php
- [x] sugar_version.php
- [x] suitecrm_version.php
- [x] cron.php
- [x] install.php
- [x] download.php
- [x] export.php
- [x] soap.php
- [x] run_job.php
- [x] SugarSecurity.php
- [x] pdf.php
- [x] vcal_server.php
- [x] vCard.php
- [x] php_version.php
- [x] maintenance.php
- [x] json_server.php
- [x] ical_server.php
- [x] emailmandelivery.php
- [x] dictionary.php
- [x] deprecated.php
- [x] TreeData.php
- [x] RoboFile.php
- [x] HandleAjaxCall.php

### Phase 2: Core Infrastructure
- [ ] data/ directory files:
  - [x] BeanFactory.php
  - [x] Link.php
  - [x] Link2.php  
  - [x] SugarBean.php (6337 lines - large file)
  - [ ] Relationships/*.php:
    - [x] RelationshipFactory.php
    - [x] SugarRelationship.php
    - [x] M2MRelationship.php (693 lines)
    - [x] One2MRelationship.php (189 lines)
    - [x] One2MBeanRelationship.php (418 lines)
    - [x] One2OneRelationship.php (86 lines)
    - [x] One2OneBeanRelationship.php (128 lines)
    - [x] EmailAddressRelationship.php (165 lines)
- [ ] include/ directory core files (major framework files):
  - [x] entryPoint.php (212 lines)
  - [x] utils.php (6361 lines - large file)
- [x] lib/ directory files (library components):
  - [x] Enumerator/ExceptionCode.php (69 lines)
  - [x] Exception/Exception.php (83 lines)
  - [x] Exception/AccessDeniedException.php (63 lines)
  - [x] Exception/InvalidArgumentException.php (63 lines)
  - [x] Exception/MalwareFoundException.php (84 lines)
  - [x] Exception/NotAllowedException.php (63 lines)
  - [x] Exception/NotFoundException.php (63 lines)
  - [x] Interfaces/AntiMalwareFileScanner.php (66 lines)
  - [x] Log/SugarLoggerHandler.php (104 lines)
  - [x] Log/CliLoggerFormatter.php (210 lines)
  - [x] Log/CliLoggerHandler.php (70 lines)
  - [x] PDF/ directory files:
    - [x] PDFWrapper.php (202 lines)
    - [x] PDFConfigurator.php (111 lines)
    - [x] PDFEngine.php (101 lines)
  - [ ] Robo/ directory files
  - [ ] Search/ directory files
  - [ ] Utility/ directory files
- [x] Zend/ directory files (completed all backwards compatibility stubs)

### Phase 3: Installation and Configuration
- [ ] install/ directory files
- [ ] ModuleInstall/ directory files

### Phase 4: Module Documentation (Alphabetical Order)
- [ ] modules/Accounts/
- [ ] modules/ACL/
- [ ] modules/ACLActions/
- [ ] modules/ACLRoles/
- [ ] modules/Activities/
- [ ] modules/Administration/
- [ ] modules/Alerts/
- [ ] modules/AM_ProjectTemplates/
- [ ] modules/AM_TaskTemplates/
- [ ] modules/AOBH_BusinessHours/
- [ ] modules/AOD_Index/
- [ ] modules/AOD_IndexEvent/
- [ ] modules/AOK_Knowledge_Base_Categories/
- [ ] modules/AOK_KnowledgeBase/
- [ ] modules/AOP_Case_Events/
- [ ] modules/AOP_Case_Updates/
- [ ] modules/AOR_Charts/
- [ ] modules/AOR_Conditions/
- [ ] modules/AOR_Fields/
- [ ] modules/AOR_Reports/
- [ ] modules/AOR_Scheduled_Reports/
- [ ] modules/AOS_Contracts/
- [ ] modules/AOS_Invoices/
- [ ] modules/AOS_Line_Item_Groups/
- [ ] modules/AOS_PDF_Templates/
- [ ] modules/AOS_Product_Categories/
- [ ] modules/AOS_Products/
- [ ] modules/AOS_Products_Quotes/
- [ ] modules/AOS_Quotes/
- [ ] modules/AOW_Actions/
- [ ] modules/AOW_Conditions/
- [ ] modules/AOW_Processed/
- [ ] modules/AOW_WorkFlow/
- [ ] modules/Audit/
- [ ] modules/Bugs/
- [ ] modules/Calendar/
- [ ] modules/Calls/
- [ ] modules/Calls_Reschedule/
- [ ] modules/CampaignLog/
- [ ] modules/Campaigns/
- [ ] modules/CampaignTrackers/
- [ ] modules/Cases/
- [ ] modules/Charts/
- [ ] modules/Configurator/
- [ ] modules/Connectors/
- [ ] modules/Contacts/
- [ ] modules/Currencies/
- [ ] modules/Delegates/
- [ ] modules/DocumentRevisions/
- [ ] modules/Documents/
- [ ] modules/DynamicFields/
- [ ] modules/EAPM/
- [ ] modules/EmailAddresses/
- [ ] modules/EmailMan/
- [ ] modules/EmailMarketing/
- [ ] modules/Emails/
- [ ] modules/EmailTemplates/
- [ ] modules/EmailText/
- [ ] modules/Employees/
- [ ] modules/ExternalOAuthConnection/
- [ ] modules/ExternalOAuthProvider/
- [ ] modules/Favorites/
- [ ] modules/FP_Event_Locations/
- [ ] modules/FP_events/
- [ ] modules/Groups/
- [ ] modules/Help/
- [ ] modules/History/
- [ ] modules/Home/
- [ ] modules/iCals/
- [ ] modules/Import/
- [ ] Additional modules (continuing alphabetically)

### Phase 5: Support Files
- [ ] metadata/ directory files
- [ ] jssource/ directory files
- [ ] service/ directory files
- [ ] soap/ directory files
- [ ] tests/ directory files
- [ ] themes/ directory files
- [ ] XTemplate/ directory files

### Phase 6: Quality Assurance
- [ ] Review all documentation for consistency
- [ ] Ensure cross-references between related files are accurate
- [ ] Verify all sections follow the required order
- [ ] Check for integration conflicts between documentation files
- [ ] Update documentation where conflicts are found

## Notes
- Focus on PHP files only
- Each documentation file should be comprehensive and self-contained
- When conflicts arise between documentation files, investigate and update both files
- Prioritize core framework files and frequently used modules
- Maintain consistent terminology and structure across all documentation 