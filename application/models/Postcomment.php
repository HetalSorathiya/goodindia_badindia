<?php

/*
 * This class is used for as Model for Post comment table 
 */

class Model_Postcomment extends Model_Base {
	
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
     *  This function is used to get comment
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchPostcomment($where = "", $limit = "", $offset = "0", $order = "cmt_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_id'))
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
        } else {
            return array();
        }     
    }   

    /**
     *  This function is used to get total comment
     * @return int
     */
    public function getpostcommentCount($where) {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL), Array("cnt" => "count(*)"))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_id'))
                ->where("cmt_status != 2");
				//echo $select;exit;
        if ($where != "") {
            $select->where($where);
        }
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	 public function getfrontpostcommentCount($where) {
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
	
	 public function fetchfrontPostComment($where = "", $limit = "", $offset = "0", $order = "cmt_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_image'))
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
     *  This function is used to get total comment
     * @return int
     */
    public function getcommentCount() {
        $select = $this->select()
				//->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL), Array("cnt" => "count(*)"))
				//->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_id'))
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
	
	public function fetchcommentreply($where = "") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_image'))
                ->where("cmt_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        
       
       // echo $select; exit;
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
	public function fetchallcommentPost($where = "",$limit = "") {
        $select = $this->select()
				->setIntegrityCheck(false)          
				->from(array("postcomment" => $this->POST_COMMENT_TBL), Array("cnt" => "count('*')"))
				->joinleft(array('post' => $this->POST_TBL), "post.post_id = postcomment.cmt_post_id")
				->joinleft(array('user' => $this->USER_TBL), "user.usr_lgn_id = post.post_lgn_id", array('usr_name','usr_image'))		
				->where("post_status = 1")
				->group("cmt_post_id")
				->order("cnt DESC")
				->where("post_approve_status = 1");
				
        if ($where != "") {
            $select->where($where);
        }
		
       
		if ($limit != "") {
            $select->limit($limit);
        }
		//echo $select;exit;
		 //echo $select; exit;
        $data = parent::fetchAll($select);
		if (!empty($data)) {
			
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
			$postlikeModel = new Model_Postlike(); // Load Post Like Model
			$postcommentModel = new Model_Postcomment(); // Load Post Like Model
			
            foreach ($data->toArray() as $value) {
				
				$commentCount = "cmt_post_id = '".$value['post_id']."'";
				$PostcommentCount = $postcommentModel->getcommentpostlistCount($commentCount);
				
				$post_status = "";
				if ($value['post_status'] == 0) {
					$post_status = "Inactive";
				} else if ($value['post_status'] == 1) {
					$post_status = "Active";
				} else if ($value['post_status'] == 2) {
					$post_status = "Approval";
				}
				
				$post_type = "";
				if ($value['post_type'] == 2) {
					$post_type = "Bad";
				} else if ($value['post_type'] == 1) {
					$post_type = "Good";
				}
				
                $postDetailArray = Array(
					'post_id' => $value['post_id'],
					'post_cat_id' => $value['post_cat_id'],
					'usr_name' => $value['usr_name'],
					'usr_image' => $value['usr_image'],
					'post_title' => $value['post_title'],
					'cat_name' => $value['cat_name'],
					'post_desc' => $value['post_desc'],
					'post_type' => $post_type,
					'post_location' => $value['post_location'],
					'post_lattitude' => $value['post_lattitude'],
					'post_longitude' => $value['post_longitude'],
					'post_image' => $value['post_image'],
					'post_video' => $value['post_video'],
					'post_status' => $post_status,
					'post_comment' => $PostcommentCount,				
					'post_createddate' => $value['post_createddate'],
					'post_updateddate' => $value['post_updateddate'],
					'post_img_status' => $value['post_img_status'],
				);
				$finalArray[$value['post_id']] = $postDetailArray;
            }
            return array_values($finalArray);
        } else {
            return array();
        }      
	 }
	 
	 public function getcommentpostlistCount($where) {
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
	 
	public function fetchadminbpostComment($where = "", $limit = "", $offset = "0", $order = "cmt_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_image'))
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
	 
	public function fetchadminpostcommentreply($where = "") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("pcmt" => $this->POST_COMMENT_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = pcmt.cmt_lgn_id", array('usr_name','usr_image'))
                ->where("cmt_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        
       
       // echo $select; exit;
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
	public function deletepostComment($commentArray) {
        if (is_array($commentArray)) {
            foreach ($commentArray as $_post) {
                $commentData = Array();
                $commentData = $this->fetchEntryById($_post);
                if (count($commentData) > 0) {
                    $data = array("cmt_status" => "2");
                    $this->update($data, "cmt_id =" . (int) $_post);
                }
            }
        } else {
            $cmt_id = $commentArray;
            $commentData = $this->fetchEntryById($cmt_id);
            if (count($commentData) > 0) {
                $data = array("cmt_status" => "2");
                $this->update($data, "cmt_id =" . $cmt_id);
            }
        }
    }
	
}

?>