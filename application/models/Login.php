<?php

/*
 * This class is used for common same function.
 */

class Model_Login extends Model_Base {

    protected $_name = 'tbl_login';
    protected $_primaryKey = 'lgn_id';

    /**
     * Function to check email exist for forgot password.
     *
     * @param  int|string $email
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function checkEmailForForgotPassword($email) {

        $select = $this->select()->setIntegrityCheck(false)->from(array('lgn' => 'tbl_login'))
                ->where('lgn_email = ?', $email)
                ->where('lgn_active = 1');
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

        $select = $this->select()->setIntegrityCheck(false)->from(array('usr' => 'tbl_login'))
                ->where('lgn_email = ?', $email);
        $result = $this->fetchRow($select);
        if (!empty($result)) {
            return $result->lgn_id;
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

        $select = $this->select()->setIntegrityCheck(false)->from(array('lgn' => 'tbl_login'))
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
     * Function to check user exist with usr_id
     *
     * @param  int|string $email
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function checkUserIdExist($userId) {

        $select = $this->select()->setIntegrityCheck(false)->from(array('usr' => 'tbl_user'))
                ->where('usr_id = ?', $userId);
        $result = $this->fetchRow($select);
        if (!empty($result)) {
            return true;
        } else {
            return false;
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
                ->from(array('lgn' => 'tbl_login'))
                ->where('lgn_id = ?', $id);
        // see reasoning in fetchEntries() as to why we return only an array
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }

}
