<?php
/*
 * This class is used for as Model for User table 
 */
class Model_User extends Model_Base {
    protected $_name = "tbl_user";
    protected $_primaryKey = "usr_id";
     
    public function getStatusArray() {
        return array(
            "" => "-- Select Status --",
            "1" => "Active",
            "0" => "Inactive"
        );
    }
	
	 public function getTypeArray() {
        return array(
            "" => "-- Select Type --",
            "1" => "Android",
            "0" => "IOS"
        );
    }
	
	 public function getgenderArray() {
        return array(
            "" => "-- Select Gender --",
            "1" => "Male",
            "0" => "Female"
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
				->setIntegrityCheck(false)
                ->from(array("usr" => $this->USER_TBL))
				->joinleft(array('lgn' => "tbl_login"), "lgn.lgn_id = usr.usr_lgn_id")
                ->where("usr_id = ?", $id)
                ->where("usr_status!= 2");
        $data = $this->fetchRow($select);
		//echo $select;exit;
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
    

    /**
     *  This function is used to get user
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchUser($where = "", $limit = "", $offset = "0", $order = "usr_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("usr" => $this->USER_TBL))
				->join(array('lgn' => "tbl_login"), "lgn.lgn_id = usr.usr_lgn_id")
                ->where("usr_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        if ($limit != "") {
            $select->limit($limit, $offset);
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
    

    /**
     *  This function is used to get total user
     * @return int
     */
    public function getUserCount($where) {
        $select = $this->select()
                ->from(array("usr" => $this->USER_TBL), Array("cnt" => "count(*)"))
				
                ->where("usr_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	/**
     *  This function is used to get total user post
     * @return int
     */
    public function getuserPostCount($where) {
        $select = $this->select()
                ->from(array("usr" => $this->USER_TBL), Array("cnt" => "count(*)"))
                ->setIntegrityCheck(false)
                ->join(array('post' => $this->POST_TBL), "post.post_lgn_id = usr.usr_lgn_id", array('post_id', 'post_status'))
                ->where("usr_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	/**
     *  This function is used to get user profile Data
     * @return int
     */
	public function fetchUserprofileData($where = "") {
        $select = $this->select()
                ->from(array("usr" => $this->USER_TBL))
				->setIntegrityCheck(false)
				->join(array('lgn' => "tbl_login"), "lgn.lgn_id = usr.usr_lgn_id")
                ->where("usr_status!= 2");
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
            $data = parent::fetchAll($select);
        if (!empty($data)) {
             $finalArray = array();
            foreach($data->toArray() as $key => $value){
                $finalArray[$value['usr_id']] = $value;
            }
            return $finalArray;
        } else {
            return array();
        }
    }
	
    /**
     *  This function is used to delete user
     * 
     */
    public function deleteUser($usrArray) {
        if (is_array($usrArray)) {
            foreach ($usrArray as $_user) {
                $userData = Array();
                $userData = $this->fetchEntryById($_user);
                if (count($userData) > 0) {
                    $data = array("usr_status" => "2");
                    $this->update($data, "usr_id =" . (int) $_user);
                }
            }
        } else {
            $usr_id = $usrArray;
            $userData = $this->fetchEntryById($usr_id);
            if (count($userData) > 0) {
                $data = array("usr_status" => "2");
                $this->update($data, "usr_id =" . $usr_id);
            }
        }
    }
}
?>