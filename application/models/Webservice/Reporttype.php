<?php

/*
 * This class is used for as Model for Location table 
 */

class Model_Webservice_Reporttype extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->REPORT_TYPE_TBL;
        $this->_primaryKey = 'report_id';
    }
		
	/**
     *  This function is used to get category
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchReporttype($where = "", $order = "report_id", $sort = "ASC") {
        $select = $this->select()
                ->from(array("loc" => $this->REPORT_TYPE_TBL));
               
        if ($where != "") {
            $select->where($where);
        }       
        $select->order($order . " " . $sort);
        //echo $select; exit;
        $data = parent::fetchAll($select);
		if (!empty($data)) {
			
			 return $data->toArray();           
        } else {
            return array();
        }       
    }   

    
		
}

?>