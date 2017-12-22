<?php

/*
 * This class is used for as Model for Location table 
 */

class Model_Webservice_Location extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->LOCATION_TBL;
        $this->_primaryKey = 'loc_id';
    }
	
	/**
     * Fetch an individual entry
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryById($id) {
        $select = $this->select()
                ->from(array("loc" => $this->LOCATION_TBL))
                ->where("loc_id = ?", $id)
                ->where("loc_status!= 2");
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	/**
     *  This function is used to get category
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchLocation($where = "", $limit = "", $offset = "0", $order = "loc_id", $sort = "ASC") {
        $select = $this->select()
                ->from(array("loc" => $this->LOCATION_TBL))
                ->where("loc_status = 1");
        if ($where != "") {
            $select->where($where);
        }
        if ($limit != "") {
            $select->limit($limit, $offset);
        }

        $select->order($order . " " . $sort);
        //echo $select; exit;
        $data = parent::fetchAll($select);
		$finalArray = array();
		if (!empty($data)) {
			
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
				
            foreach ($data->toArray() as $value) {
				
				$loc_status = "";
				if ($value['loc_status'] == 0) {
					$loc_status = "Inactive";
				} else if ($value['loc_status'] == 1) {
					$loc_status = "Active";
				}
				
                $locDetailArray = Array(
					'loc_id' => $value['loc_id'],
					'loc_name' => $value['loc_name'],
					'loc_latitude' => $value['loc_latitude'],
					'loc_longitude' => $value['loc_longitude'],
					'loc_status' => $loc_status,
					'loc_createddate' => $value['loc_createddate'],
					'loc_updateddate' => $value['loc_updateddate'],
				);
				$finalArray[$value['loc_id']] = $locDetailArray;
            }
            return array_values($finalArray);
        } else {
            return array();
        }       
    }   

    /**
     *  This function is used to get total category
     * @return int
     */
    public function getLocationCount($where) {
        $select = $this->select()
                ->from(array("loc" => $this->LOCATION_TBL), Array("cnt" => "count(*)"))
                ->where("loc_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
		
}

?>