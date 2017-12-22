<?php

/*
 * This class is used for as Model for Report Abuse table 
 */

class Model_Webservice_Reportabuse extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->REPORT_ABUSE_TBL;
        $this->_primaryKey = 'report_abuse_id';
    }
		
		
}

?>