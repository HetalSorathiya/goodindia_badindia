<?php
/*
 * This class is used for as Model for location table 
 */
class Model_Location extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->LOCATION_TBL;
        $this->_primaryKey = 'loc_id';
    }
     
    public function getStatusArray() {
        return array(
            "" => "-- Select Status --",
            "1" => "Active",
            "0" => "Inactive"
        );
    }
    
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
     *  This function is used to get location
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchLocation($where = "", $limit = "", $offset = "0", $order = "ploc_id", $sort = "ASC") {
        $select = $this->select()
                ->from(array("loc" => $this->LOCATION_TBL))
                ->where("loc_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        if ($limit != "") {
            $select->limit($limit, $offset);
        }

        $select->order($order . " " . $sort);
        #echo $select; exit;
        $data = parent::fetchAll($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
    
	public function LocationArray($where = "",$order = "loc_name",$sort = "ASC") {
        $select = $this->select()->setIntegrityCheck(false)
                ->from(array("loc" => $this->LOCATION_TBL))
                ->where("loc_status = 1");
        if ($where != '') {
            $select->where($where);
        }
		$select->order($order . " " . $sort);
        $result = $this->fetchAll($select);
		#echo $select;exit;
        $finalArray = array('' => 'Select Location');
		
        foreach ($result as $res) {
            $finalArray[$res['loc_id']] = $res['loc_name'];
        }
        return $finalArray;
    }
	
    /**
     *  This function is used to get total location
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
    /**
     *  This function is used to delete record
     * 
     */
    public function deleteLocation($locArray) {
        if (is_array($locArray)) {
            foreach ($locArray as $_loc) {
                $locData = Array();
                $locData = $this->fetchEntryById($_loc);
                if (count($locData) > 0) {
                    $data = array("loc_status" => "2");
                    $this->update($data, "loc_id =" . (int) $_loc);
                }
            }
        } else {
            $loc_id = $locArray;
            $locData = $this->fetchEntryById($loc_id);
            if (count($locData) > 0) {
                $data = array("loc_status" => "2");
                $this->update($data, "loc_id =" . $loc_id);
            }
        }
    }


}?>