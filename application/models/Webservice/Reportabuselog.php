<?php

/*
 * This class is used for as Model for Report Abuse table 
 */

class Model_Webservice_Reportabuselog extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->REPORT_ABUSE_LOG_TBL;
        $this->_primaryKey = 'id';
    }
		
		
}

?>