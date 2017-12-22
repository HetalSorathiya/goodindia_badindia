<?php

/*
 * This class is used for as Model for User table 
 */

class Model_Webservice_User extends Model_Base {

    protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->USER_TBL;
        $this->_primaryKey = 'usr_id';
    }
	
	/**
     * Fetch an individual entry
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchEntryById($id) {
        $select = $this->select()
                ->from(array("d" => $this->USER_TBL))
                ->setIntegrityCheck(false)
                ->join(array('lgn' => $this->LOGIN_TBL), "lgn.lgn_id  = d.usr_lgn_id", array("lgn_id", "lgn_email", 'lgn_status'))
                ->where("usr_lgn_id = ?", $id)
                ->where("usr_status!= 2");

        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	/**
     * Function to check user exist with phone
     *
     * @param  string $where
     * @return null|Zend_Db_Table_Row_Abstract
     */
	 public function checkPhoneExist($where = "") {
        $select = $this->select()
                ->from(array("r" => "tbl_user"));
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
	
	/**
     * Fetch an individual entry by Login id
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryByLoginId($where, $id) {
		
		$config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $ImageUrl = $config->BASE_URL.'public/upload/user/';
		$defaultUrl = $config->BASE_URL.'public/upload/user/default.png';
		
        $select = $this->select()
                ->from(array("d" => "tbl_user"), array('usr_id','usr_name',"usr_phone","usr_image",'usr_gender'))
                ->setIntegrityCheck(false)
                ->join(array('lgn' => "tbl_login"), "lgn.lgn_id  = d.usr_lgn_id", array('lgn_id','lgn_type'))
                ->where("lgn_id = ?", $id)
                ->where("lgn_status = 1")
                ->where("usr_status = 1");
	
        if ($where != "") {
            $select->where($where);
        }
		
        $data = $this->fetchAll($select);
		
		$finalArray = array();
		
		if (count($data) == 0) {
			return array();
		}else{
			$finalArray = $data->toArray();
				#echo "<pre>";print_r($finalArray);exit;
			
			if($finalArray[0]['lgn_type'] == 2){			
				if($finalArray[0]['usr_image'] == ""){
					$finalArray[0]['usr_image'] = $defaultUrl;
				}else{
					$finalArray[0]['usr_image'] = $ImageUrl.$finalArray[0]['usr_image'];
				}
			}else{
				$finalArray[0]['usr_image'] = $finalArray[0]['usr_image'];
			}			
			#echo "<pre>";print_r($finalArray);exit;
			
            return $finalArray;
		}	
    }
	public function fetchUserprofileData($where = "") {
        $select = $this->select()
                ->from(array("r" => "tbl_user"))
				->setIntegrityCheck(false)
				->join(array('lgn' => "tbl_login"), "lgn.lgn_id = r.usr_lgn_id")
                ->where("usr_status!= 2");
        if ($where != "") {
            $select->where($where);
        }
		
		
		//echo $select;exit;
            $data = parent::fetchRow($select);
        if (!empty($data)) {
            
           return $data->toArray();
        } else {
            return array();
        }
    }
	
	public function fetchuserprofilebyId($id) {
        $select = $this->select()
                ->from(array("r" => "tbl_user"))
                ->where("usr_id = ?", $id);
                //->where("post_status!= 3");
		
        $data = $this->fetchRow($select);
		//echo $select; exit;
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	public function fetchuserprofileimg($social_image, $edit_image, $gender ) {
		$config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
		$male_image=$config->BASE_URL ."public/upload/user/man_avatar.png";
		$female_image=$config->BASE_URL ."public/upload/user/woman_avatar.png";
		$default_image=$config->BASE_URL ."public/upload/user/user.png";
		$edited_image=$config->BASE_URL ."public/upload/user/".$edit_image;
		 if($edit_image != '') {
		  return $edited_image;
		 }

		 if($social_image != '') {
		  return $social_image;
		 }		 
		 if($gender =='1') {
		  return $male_image;
		 }
		 
		 
		 if($gender == '2') {
		  return $female_image;
		 }
		 
		 return $default_image; 
	}

}

?>