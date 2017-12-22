<?php
/*
 * This class is used for as Model for Cms table 
 */
class Model_Cms extends Model_Base {
	
    //protected $_name = "tbl_cms";
    //protected $_primaryKey = "c_id";
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->CMS_TBL;
        $this->_primaryKey = 'c_id';
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
                ->from(array("c" => $this->CMS_TBL))
                ->where("c_id = ?", $id)
                ->where("c_status!= 2");
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
    

    /**
     *  This function is used to get cms
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchCms($where = "", $limit = "", $offset = "0", $order = "c_id", $sort = "ASC") {
        $select = $this->select()
                ->from(array("c" => $this->CMS_TBL))
                ->where("c_status != 2");
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
    

    /**
     *  This function is used to get total orders
     * @return int
     */
    public function getCmsCount($where) {
        $select = $this->select()
                ->from(array("c" => $this->CMS_TBL), Array("cnt" => "count(*)"))
                ->where("c_status != 2");
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
    public function deleteCms($cArray) {
        if (is_array($cArray)) {
            foreach ($cArray as $_cms) {
                $cmsData = Array();
                $cmsData = $this->fetchEntryById($_cms);
                if (count($cmsData) > 0) {
                    $data = array("c_status" => "2");
                    $this->update($data, "c_id =" . (int) $_cms);
                }
            }
        } else {
            $c_id = $cArray;
            $cmsData = $this->fetchEntryById($c_id);
            if (count($cmsData) > 0) {
                $data = array("c_status" => "2");
                $this->update($data, "c_id =" . $c_id);
            }
        }
    }
}
?>