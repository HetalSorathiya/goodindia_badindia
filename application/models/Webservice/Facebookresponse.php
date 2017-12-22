<?php

/*
 * This class is used for as Model for Facebook response table 
 */

class Model_Webservice_Facebookresponse extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->FACEBOOK_RESPONSE_TBL;
        $this->_primaryKey = 'fr_id';
    }
    
}

?>
