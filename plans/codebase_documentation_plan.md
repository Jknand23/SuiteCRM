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
  - [x] BeanFactory.php (✓ Reviewed and updated for better Link2 integration)
  - [x] Link.php (✓ Reviewed for consistency with Link2 distinction)
  - [x] Link2.php (✓ Reviewed and updated to clarify Link vs Link2 usage)
  - [x] SugarBean.php (6337 lines - large file) (✓ Reviewed for BeanFactory integration)
  - [x] Relationships/*.php: (✓ All relationship files completed)
    - [x] RelationshipFactory.php (✓ Reviewed and updated for hierarchy integration)
    - [x] SugarRelationship.php (✓ Reviewed and updated for base class clarity)
    - [x] M2MRelationship.php (693 lines) (✓ Reviewed for parent class integration)
    - [x] One2MRelationship.php (189 lines)
    - [x] One2MBeanRelationship.php (418 lines)
    - [x] One2OneRelationship.php (86 lines)
    - [x] One2OneBeanRelationship.php (128 lines)
    - [x] EmailAddressRelationship.php (165 lines)
- [ ] include/ directory core files (major framework files):
  - [x] entryPoint.php (212 lines) (✓ Reviewed for integration points)
  - [x] utils.php (6361 lines - large file) (✓ Reviewed partial structure)
  - [ ] **MISSING CORE FILES - Need Documentation:**
    - [ ] Imap.php (83 lines)
    - [ ] JSON.php (115 lines)
    - [ ] tabs.php (73 lines)
    - [ ] clean.php (66 lines)
    - [ ] vCard.php (409 lines)
    - [ ] dir_inc.php (324 lines)
    - [ ] modules.php (574 lines)
    - [ ] CleanCSV.php
    - [ ] formbase.php (527 lines)
    - [ ] export_utils.php (983 lines)
    - [ ] TimeDate.php (2114 lines - large file)
    - [ ] MassUpdate.php (1554 lines - large file)
    - [ ] Additional core files requiring review
- [ ] lib/ directory files (library components):
  - [x] Enumerator/ExceptionCode.php (69 lines) ✅
  - [x] Exception/ directory files (✓ All completed) ✅
    - [x] Exception.php (83 lines) (✓ Reviewed and updated format)
    - [x] AccessDeniedException.php (63 lines)
    - [x] InvalidArgumentException.php (63 lines)
    - [x] MalwareFoundException.php (84 lines)
    - [x] NotAllowedException.php (63 lines)
    - [x] NotFoundException.php (63 lines)
  - [x] Interfaces/AntiMalwareFileScanner.php (66 lines) ✅
  - [x] Log/ directory files (✓ All completed) ✅
    - [x] SugarLoggerHandler.php (104 lines) (✓ Reviewed and updated integration)
    - [x] CliLoggerFormatter.php (210 lines)
    - [x] CliLoggerHandler.php (70 lines)
  - [x] PDF/ directory files (✓ All completed) ✅
    - [x] PDFWrapper.php (202 lines)
    - [x] PDFConfigurator.php (111 lines)
    - [x] PDFEngine.php (101 lines)
  - [ ] **Robo/ directory files (PARTIALLY COMPLETE):**
    - [x] config.php (57 lines) ✅
    - [ ] Plugin/ subdirectory files
    - [ ] Traits/ subdirectory files (only RoboTrait.php documented)
  - [ ] **Search/ directory files (NOT STARTED):**
    - [ ] SearchResults.php (374 lines)
    - [ ] SearchWrapper.php (246 lines)
    - [ ] SearchQuery.php (389 lines)
    - [ ] SearchEngine.php (123 lines)
    - [ ] SearchModules.php (344 lines)
    - [ ] SearchConfigurator.php (136 lines)
    - [ ] Subdirectories: UI/, SqlSearch/, Index/, Exceptions/, ElasticSearch/, AOD/, BasicSearch/
  - [ ] **Utility/ directory files (NOT STARTED):**
    - [ ] StringValidator.php (89 lines)
    - [ ] SuiteLogger.php (129 lines)
    - [ ] SuiteValidator.php (125 lines)
    - [ ] Paths.php (87 lines)
    - [ ] StringUtils.php (115 lines)
    - [ ] ModuleLanguage.php (58 lines)
    - [ ] OperatingSystem.php (119 lines)
    - [ ] Configuration.php (110 lines)
    - [ ] CurrentLanguage.php (52 lines)
    - [ ] BeanJsonSerializer.php (423 lines)
    - [ ] ApplicationLanguage.php (61 lines)
    - [ ] ArrayMapper.php (506 lines)
    - [ ] AntiMalware/ subdirectory
- [x] Zend/ directory files (completed all backwards compatibility stubs) (✓ Reviewed format consistency) ✅

**Phase 2 Review Status: ❌ INCOMPLETE**
*CORRECTION: Phase 2 is NOT completed. Significant gaps remain:*
- *Multiple core include/ files lack documentation*
- *lib/Search/ directory completely undocumented*
- *lib/Utility/ directory completely undocumented* 
- *lib/Robo/ directory only partially documented*
- *Data and include sections still marked incomplete pending these files*

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