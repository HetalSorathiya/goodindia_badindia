<?php

/*
 * This class is used for as Model for post table 
 */

class Model_Webservice_Notificationlog extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->COMMENTPOST_NOTIFY_TBL;
        $this->_primaryKey = 'notf_id';
    }
	
	
}

?>