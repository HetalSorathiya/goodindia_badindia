<?php

/*
 * This class is used for common same function.
 */

class Model_Adminuser extends Model_Base {	
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->ADMIN_USER_TBL;
        $this->_primaryKey = 'admin_usr_id';
    }
	
	public function getStatusArray() {
        return array(
            "" => "-- Select Status --",
            "1" => "Active",
            "0" => "Inactive"
        );
    }
	
	public function getTypeArray() {
        return array(
            "" => "-- Select Status --",
            "1" => "Super Admin",
            "0" => "Normal Admin"
        );
    }

    /**
     * Function to check email exist for forgot password.
     *
     * @param  int|string $email
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function checkEmailForForgotPassword($email) {

        $select = $this->select()
				->setIntegrityCheck(false)
				->from(array('usr' => $this->ADMIN_USER_TBL))
                ->where('admin_email = ?', $email);
                //->where('lgn_active = 1');
        $result = $this->fetchRow($select);
        #echo $select;exit;
        if (!empty($result)) {
            return $result->toArray();
        } else {
            return array();
        }
    }

    /**
     * Function to check user exist with email
     *
     * @param  string $email
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function checkEmailExist($email) {

        $select = $this->select()
				->setIntegrityCheck(false)
				->from(array('usr' => $this->ADMIN_USER_TBL))
                ->where('admin_email = ?', $email)
				->where('admin_status != 2');
        $result = $this->fetchRow($select);
        if (!empty($result)) {
            return $result->admin_usr_id;
        } else {
            return false;
        }
    }

    /**
     * Function to check user exist with email other than current email used in edit mode
     *
     * @param  string $email
     * @param  string $email
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function checkEmail($currentEmail = '', $email) {

        $select = $this->select()
				->setIntegrityCheck(false)
				->from(array('usr' => $this->ADMIN_USER_TBL))
                ->where('admin_email = ? ', $email)
                ->orWhere('admin_email = ? ', $currentEmail);
        $result = $this->fetchAll($select);
        if (count($result) > 1) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Fetch an individual entry
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchEntryById($id) {
        $select = $this->select()
                ->from(array('usr' => $this->ADMIN_USER_TBL))
                ->where('admin_usr_id = ?', $id);
        // see reasoning in fetchEntries() as to why we return only an array
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	//fetch loggedin user data for edit profile
    public function fetchLoggedinUserData($admin_usr_id) {
        $select = $this->select()
                ->from(array("usr" => $this->ADMIN_USER_TBL))                
                ->where('admin_usr_id = ?', $admin_usr_id)
                ->where("admin_status = 1");

        $data = parent::fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	/**
     *  This function is used to get adminuser
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchAdminuser($where = "", $limit = "", $offset = "0", $order = "albm_id", $sort = "ASC") {
        $select = $this->select()
                ->from(array("usr" => $this->ADMIN_USER_TBL))
                ->where("admin_status != 2");
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
    public function getAdminuserCount($where) {
        $select = $this->select()
                ->from(array("usr" => $this->ADMIN_USER_TBL), Array("cnt" => "count(*)"))
                ->where("admin_status != 2");
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
    public function deleteAdminuser($adminArray) {
        if (is_array($adminArray)) {
            foreach ($adminArray as $_admin) {
                $adminuserData = Array();
                $adminuserData = $this->fetchEntryById($_admin);
                if (count($adminuserData) > 0) {
                    $data = array("admin_status" => "2");
                    $this->update($data, "admin_usr_id =" . (int) $_admin);
                }
            }
        } else {
            $admin_usr_id = $adminArray;
            $adminuserData = $this->fetchEntryById($admin_usr_id);
            if (count($adminuserData) > 0) {
                $data = array("admin_status" => "2");
                $this->update($data, "admin_usr_id =" . $admin_usr_id);
            }
        }
    }

}
