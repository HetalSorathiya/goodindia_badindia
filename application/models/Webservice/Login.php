<?php

/*
 * This class is used for Tbl_login function.
 */

class Model_Webservice_Login extends Model_Base {

    protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->LOGIN_TBL;
        $this->_primaryKey = 'lgn_id';
    }

    /**
     * Function to check user exist with email
     *
     * @param  string $email
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function checkEmailExist($email) {

        $select = $this->select()->setIntegrityCheck(false)
                ->from(array('usr' => $this->LOGIN_TBL), array('lgn_id'))
                ->where('lgn_email = ?', $email)
                ->where('lgn_status != 2');

        $result = $this->fetchRow($select);
        if (!empty($result)) {
            return $result->lgn_id;
        } else {
            return false;
        }
    }

    /* Check Login details
     * * @param $where  string | Condition
     *  @param  $loginparam Array | Params of tbl_login
     *  @param  $userparam Array | Params of tbl_user
     * 
     */

    public function fetchLoginDetails($where = "", $loginparam = array('*'), $userparam = array('*')) {
        $select = $this->select()
                ->from(array("lgn" => $this->LOGIN_TBL), $loginparam)
                ->setIntegrityCheck(false)
                ->join(array('usr' => $this->USER_TBL), "lgn.lgn_id = usr.usr_lgn_id", $userparam)
                ->where("lgn_status != 2")
                ->where("usr_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        #echo $select; exit;
        $data = $this->fetchRow($select);

        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }

    /**
     * Function to check user exist with email other than current email used in edit mode
     *
     * @param  string $email
     * @param  string $currentEmail
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function checkEmail($currentEmail = '', $email) {

        $select = $this->select()->setIntegrityCheck(false)
                ->from(array('lgn' => $this->LOGIN_TBL), array('lgn_id'))
                ->where('lgn_email = ? ', $email)
                ->orWhere('lgn_email = ? ', $currentEmail);
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
                ->from(array('lgn' => $this->LOGIN_TBL))
                ->setIntegrityCheck(false)
                ->join(array('usr' => $this->USER_TBL), "lgn.lgn_id = usr.usr_lgn_id")
                ->where('lgn_id = ?', $id);
        // see reasoning in fetchEntries() as to why we return only an array
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	public function checkexistuserId($id) {
        $select = $this->select()
                ->from(array('lgn' => $this->LOGIN_TBL))
                ->setIntegrityCheck(false)
                ->join(array('usr' => $this->USER_TBL), "lgn.lgn_id = usr.usr_lgn_id", array('usr_name','usr_id'))
                ->where('lgn_id = ?', $id)
				 ->where("usr_status = 1");
        // see reasoning in fetchEntries() as to why we return only an array
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }

    /* Check FBAUTH/googleauth only for facebook/Google user */

    public function checkAuthExist($where = "") {

        $select = $this->select()
                ->from(array('lgn' => $this->LOGIN_TBL), array('lgn_id', 'lgn_fb_auth_id', 'lgn_email'))
                ->setIntegrityCheck(false)
                ->join(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = lgn.lgn_id", array('usr_id', 'usr_name'))
                ->where("usr_status!= 2")
                ->where("lgn_status!= 2");
        if ($where != "") {
            $select->where($where);
        }
        $data = $this->fetchRow($select);

        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }

}