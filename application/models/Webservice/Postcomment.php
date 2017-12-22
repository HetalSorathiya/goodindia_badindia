<?php

/*
 * This class is used for as Model for post comment table 
 */

class Model_Webservice_Postcomment extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->POST_COMMENT_TBL;
        $this->_primaryKey = 'cmt_id';
    }
	
	/**
     * Fetch an individual entry
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryById($id) {
        $select = $this->select()
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
                ->where("cmt_id = ?", $id)
                ->where("cmt_status!= 2");
        $data = $this->fetchRow($select);
		if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	/**
     * Fetch an individual entry
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryBycmtrefId($id) {
        $select = $this->select()
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
                ->where("cmt_ref = ?", $id)
                ->where("cmt_status!= 2");
        $data = $this->fetchRow($select);
		if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	/**
     * Fetch an individual entry by ref id
     *
     * @param  int|string $where
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryByrefId($where) {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_image'))
               //->where("cmt_ref = ?", $id)
                ->where("cmt_status!= 2");
		if ($where != "") {
            $select->where($where);
        }
//echo $select;exit;		
        $data = $this->fetchAll($select);
		//echo "<pre>";print_r($data);exit;
		if (!empty($data)) {
            $final = array();
			$i=1;
			foreach($data->toArray() as $key => $val){
				$final[$val['cmt_ref']][$i] = $val;
			$i++;}
			return $final;
        } else {
            return array();
        }
    }
	
	/**
     *  This function is used to get Post Comment
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchPostComment($where = "", $limit = "", $offset = "0", $order = "cmt_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_image','usr_gender','usr_update_img'))
                ->where("cmt_status != 2");
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
			
			
			/* foreach($data->toArray() as $key => $val){
			echo "<pre>";print_r($val);
				$finalArray = $val;
				$where = '';
				$Postrefcomment = $this->fetchEntryByrefId($val['cmt_ref'],$where);
				
				
			}exit; */
        } else {
            return array();
        }     
    }   
	
    /**
     *  This function is used to get total Post Comment
     * @return int
     */
    public function getpostcommentCount($where) {
        $select = $this->select()
                ->from(array("post" => $this->POST_COMMENT_TBL), Array("cnt" => "count(*)"))
                ->where("cmt_status != 2")
 				->where("cmt_ref = 0"); 
				
				
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	public function getpostcommentreplyCount($where) {
        $select = $this->select()
                ->from(array("post" => $this->POST_COMMENT_TBL), Array("cnt" => "count(*)"))
                ->where("cmt_status != 2");
				
				
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	/**
     *  This function is used to get total Post Comment
     * @return int
     */
    public function getposttotalCount() {
        $select = $this->select()
                ->from(array("post" => $this->POST_COMMENT_TBL), Array("cnt" => "count(*)"))
                ->where("cmt_status != 2");        
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	public function getcommentreplyCount($where) {
        $select = $this->select()
                ->from(array("post" => $this->POST_COMMENT_TBL), Array("cnt" => "count(*)"))
                ->where("cmt_status != 2");
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	
	/**
     *  This function is used to get post
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchcommentreply($where = "", $limit = "", $offset = "0", $order = "cmt_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_image','usr_gender','usr_update_img'))
                ->where("cmt_status != 2");
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
				
				
				/* $image_url = '';
				
				if($value['usr_image'] != ""){
					$image_url = $config->BASE_URL . "public/upload/user/".$value['usr_image'];
				} else {
					$image_url = $config->BASE_URL . "public/img/default-user.jpg";
				}	 */				
					
				$cmt_status = "";
				if ($value['cmt_status'] == 0) {
					$cmt_status = "Inactive";
				} else if ($value['cmt_status'] == 1) {
					$cmt_status = "Active";
				}		
                $postDetailArray = Array(
					'cmt_id' => $value['cmt_id'],
					'cmt_lgn_id' => $value['cmt_lgn_id'],
					'cmt_post_id' => $value['cmt_post_id'],
					'cmt_msg' => $value['cmt_msg'],
					'cmt_ref' => $value['cmt_ref'],
					'cmt_status' => $cmt_status,
					'cmt_createddate' => $value['cmt_createddate'],
					'cmt_updateddate' => $value['cmt_updateddate'],
					'usr_name' => $value['usr_name'],
					'usr_image' => $value['usr_image'],
				);
				$finalArray[$value['cmt_id']] = $postDetailArray;
            }
            return array_values($finalArray);
        } else {
            return array();
        }      
    } 
		
}

?>