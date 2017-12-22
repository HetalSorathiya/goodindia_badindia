<?php

/*
 * This class is used for as Model for Category table 
 */

class Model_Webservice_Category extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->CATEGORY_TBL;
        $this->_primaryKey = 'cat_id';
    }
	
	/**
     * Fetch an individual entry
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryById($id) {
        $select = $this->select()
                ->from(array("cat" => $this->CATEGORY_TBL))
                ->where("cat_id = ?", $id)
                ->where("cat_status!= 2");
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
    public function fetchCategory($where = "", $limit = "", $offset = "0", $order = "cat_id", $sort = "ASC") {
        $select = $this->select()
				//->setIntegrityCheck(false)
                ->from(array("cat" => $this->CATEGORY_TBL))
				//->joinleft(array('vid' => $this->VIDEO_TBL), "cat.cat_id = vid.vid_cat_id", array('vid_title'))
                ->where("cat_status != 2");
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
				$catDetailArray = Array(
					'cat_id' => 'ALL',
					'cat_name' => 'ALL',
					'cat_sort' => '',
					'cat_status' => '',
					'cat_createddate' => '',
					'cat_updateddate' => '',
				);
				$finalArray['ALL'] = $catDetailArray;
				$catDetailArray = Array(
					'cat_id' => 'GOOD',
					'cat_name' => 'GOOD',
					'cat_sort' => '',
					'cat_status' => '',
					'cat_createddate' => '',
					'cat_updateddate' => '',
				);
				$finalArray['GOOD'] = $catDetailArray;
				$catDetailArray = Array(
					'cat_id' => 'BAD',
					'cat_name' => 'BAD',
					'cat_sort' => '',
					'cat_status' => '',
					'cat_createddate' => '',
					'cat_updateddate' => '',
				);
				$finalArray['BAD'] = $catDetailArray;
            foreach ($data->toArray() as $value) {
				
				$cat_status = "";
				if ($value['cat_status'] == 0) {
					$cat_status = "Inactive";
				} else if ($value['cat_status'] == 1) {
					$cat_status = "Active";
				}
				
                $catDetailArray = Array(
					'cat_id' => $value['cat_id'],
					'cat_name' => $value['cat_name'],
					'cat_sort' => $value['cat_sort'],
					'cat_status' => $cat_status,
					'cat_createddate' => $value['cat_createddate'],
					'cat_updateddate' => $value['cat_updateddate'],
				);
				$finalArray[$value['cat_id']] = $catDetailArray;
            }
            return array_values($finalArray);
        } else {
            return array();
        }
       /* if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }*/
    }   

    /**
     *  This function is used to get total category
     * @return int
     */
    public function getCategoryCount($where) {
        $select = $this->select()
                ->from(array("cat" => $this->CATEGORY_TBL), Array("cnt" => "count(*)"))
                ->where("cat_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
		
}

?>