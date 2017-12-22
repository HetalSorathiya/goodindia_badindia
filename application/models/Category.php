<?php
/*
 * This class is used for as Model for Category table 
 */
class Model_Category extends Model_Base {
    
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->CATEGORY_TBL;
        $this->_primaryKey = 'cat_id';
    }
	
    public function getStatusArray() {
        return array(
            "" => "-- Select Status --",
            "1" => "Active",
            "0" => "Inactive"
        );
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
     *  This function is used to get Category
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function CategoryArray($where = "") {
        $select = $this->select()->setIntegrityCheck(false)
                ->from(array("cat" => $this->CATEGORY_TBL))
                ->where("cat_status = 1");
        if ($where != '') {
            $select->where($where);
        }
        $result = $this->fetchAll($select);
        $finalArray = array('' => 'Select Category');
		
        foreach ($result as $res) {
            $finalArray[$res['cat_id']] = $res['cat_name'];
        }
        return $finalArray;
    }

    /**
     *  This function is used to get Category
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchCategory($where = "", $limit = "", $offset = "0", $order = "cat_sort", $sort = "ASC") {
        $select = $this->select()
                ->from(array("cat" => $this->CATEGORY_TBL))
                ->where("cat_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        if ($limit != "") {
            $select->limit($limit, $offset);
        }
		$select->order( "cat_sort  ASC ");
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
    /**
     *  This function is used to delete category
     * 
     */
    public function deleteCategory($catArray) {
        if (is_array($catArray)) {
            foreach ($catArray as $_cat) {
                $catData = Array();
                $catData = $this->fetchEntryById($_cat);
                if (count($catData) > 0) {
                    $data = array("cat_status" => "2");
                    $this->update($data, "cat_id =" . (int) $_cat);
                }
            }
        } else {
            $cat_id = $catArray;
            $catData = $this->fetchEntryById($cat_id);
            if (count($catData) > 0) {
                $data = array("cat_status" => "2");
                $this->update($data, "cat_id =" . $cat_id);
            }
        }
    }
	
	public function checkCategoryExist($cat) {

        $select = $this->select()
				->setIntegrityCheck(false)
				->from(array("post" => $this->POST_TBL))
                ->where('post_cat_id = ?', $cat)
				->where('post_status != 2');
        $result = $this->fetchRow($select);
		//echo$select;exit;
        if (!empty($result)) {
            return $result->post_id;
        } else {
            return false;
        }
    }


}?>