<?php

require_once('modules/Leads/LeadsListViewSmarty.php');

#[\AllowDynamicProperties]
class LeadsViewList extends ViewList
{
    /**
     * @see ViewList::preDisplay()
     */
    public function preDisplay()
    {
        require_once('modules/AOS_PDF_Templates/formLetter.php');
        formLetter::LVPopupHtml('Leads');
        parent::preDisplay();

        $this->lv = new LeadsListViewSmarty();
        
        // Ensure we're using the enhanced template for main list view
        // (not popup view)
        if (!isset($_REQUEST['action']) || $_REQUEST['action'] !== 'Popup') {
            $this->lv->forceEnhancedTemplate = true;
        }
    }
}
