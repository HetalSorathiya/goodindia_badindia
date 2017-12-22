<?php

/*
 * This class is used for as Model for Facebook response table 
 */

class Model_Webservice_Googleresponse extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->GOOGLE_RESPONSE_TBL;
        $this->_primaryKey = 'gr_id';
    }
    
}

?>
