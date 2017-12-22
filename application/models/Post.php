<?php

/*
 * This class is used for as Model for Post table 
 */

class Model_Post extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->POST_TBL;
        $this->_primaryKey = 'post_id';
    }
	
	public function getStatusArray() {
        return array(
            "" => "-- Select Status --",
            "1" => "Active",
            "0" => "Inactive"
        );
    }
	public function getPoststatusArray() {
        return array(
            "" => "-- Select Post Status --",
            "1" => "Approved",
            "2" => "Rejected",
			"0" => "Pending"
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
                ->from(array("post" => $this->POST_TBL))
				->setIntegrityCheck(false)
				->joinleft(array('cat' => $this->CATEGORY_TBL), "cat.cat_id = post.post_cat_id", array('cat_name'))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = post.post_lgn_id", array('usr_name'))
                ->where("post_id = ?", $id)
                ->where("post_status!= 3");
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
	public function fetchEntryByuserId($id) {
        $select = $this->select()
                ->from(array("post" => $this->POST_TBL))
				->setIntegrityCheck(false)
				->joinleft(array('cat' => $this->CATEGORY_TBL), "cat.cat_id = post.post_cat_id", array('cat_name'))
                ->where("post_lgn_id = ?", $id)
                ->where("post_status!= 3");
        $data = $this->fetchAll($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	/**
     *  This function is used to get post
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchPost($where = "", $limit = "", $offset = "0", $order = "post_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("post" => $this->POST_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = post.post_lgn_id", array('usr_name','usr_image'))
                ->where("post_status != 3");
        if ($where != "") {
            $select->where($where);
        }
        if ($limit != "") {
            $select->limit($limit, $offset);
        }

        $select->order($order . " " . $sort);
       // echo $select; exit;
        $data = parent::fetchAll($select);
		if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }     
    }   

	 public function fetchfrontPost($where = "",$limit = "",$offset = "0",$order = "post_id", $sort = "DESC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("post" => $this->POST_TBL))
				->joinleft(array('cat' => $this->CATEGORY_TBL), "cat.cat_id = post.post_cat_id", array('cat_name'))
				->joinleft(array('user' => $this->USER_TBL), "user.usr_lgn_id = post.post_lgn_id", array('usr_name','usr_image'))
                ->where("post_status != 3")
				->where("post_approve_status = 1");
				
        if ($where != "") {
            $select->where($where);
        }
		$select->order($order . " " . $sort);
       
		if ($limit != "") {
            $select->limit($limit, $offset);
        }
		// echo $select; exit;
        $data = parent::fetchAll($select);
		$finalArray = array();
		if (!empty($data)) {
			
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
			$postlikeModel = new Model_Postlike(); // Load Post Like Model
			$postcommentModel = new Model_Postcomment(); // Load Post Like Model
			
            foreach ($data->toArray() as $value) {
				
				$goodCount = "like_post_id = '".$value['post_id']."' And like_type = 1";
				$PostlikegoodCount = $postlikeModel->getpostlikeCount($goodCount);
				$badCount = "like_post_id = '".$value['post_id']."' And like_type = 2";
				$PostlikebadCount = $postlikeModel->getpostlikeCount($badCount);
				$commentCount = "cmt_post_id = '".$value['post_id']."' And cmt_ref = 0";
				$PostcommentCount = $postcommentModel->getfrontpostcommentCount($commentCount);
				
				$image_url = '';
				
				if($value['post_image'] != ''){
					$image_url = $config->BASE_URL . "public/upload/post/".$value['post_image'];
				}
				
				$video_url = '';
				
				if($value['post_video'] != ''){
					$video_url = $config->BASE_URL . "public/upload/post/".$value['post_video'];
				}
				
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
					'post_image' => $image_url,
					'post_video' => $video_url,
					'post_status' => $post_status,
					'post_good' => $PostlikegoodCount,
					'post_bad' => $PostlikebadCount,
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
    /**
     *  This function is used to get total post
     * @return int
     */
    public function getpostCount($where) {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("post" => $this->POST_TBL), Array("cnt" => "count(*)"))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = post.post_lgn_id", array('usr_name'))
                ->where("post_status != 3");
        if ($where != "") {
            $select->where($where);
        }
		
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	/**
     *  This function is used to delete post
     * 
     */
    public function deletePost($postArray) {
        if (is_array($postArray)) {
            foreach ($postArray as $_post) {
                $postData = Array();
                $postData = $this->fetchEntryById($_post);
                if (count($postData) > 0) {
                    $data = array("post_status" => "3");
                    $this->update($data, "post_id =" . (int) $_post);
                }
            }
        } else {
            $post_id = $postArray;
            $postData = $this->fetchEntryById($post_id);
            if (count($postData) > 0) {
                $data = array("post_status" => "3");
                $this->update($data, "post_id =" . $post_id);
            }
        }
    }
	
	public function fetchpostbyId($id) {
        $select = $this->select()
                ->from(array("post" => $this->POST_TBL))
                ->where("post_id = ?", $id);
                //->where("post_status!= 3");
		
        $data = $this->fetchRow($select);
		//echo $select; exit;
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	public function fetchfrontPostdetail($where = "") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("post" => $this->POST_TBL))
				->joinleft(array('cat' => $this->CATEGORY_TBL), "cat.cat_id = post.post_cat_id", array('cat_name'))
				->joinleft(array('user' => $this->USER_TBL), "user.usr_lgn_id = post.post_lgn_id", array('usr_name','usr_image'))
                ->where("post_status != 3");
        if ($where != "") {
            $select->where($where);
        }		
		 //echo $select; exit;
        $data = parent::fetchAll($select);
		$finalArray = array();
		if (!empty($data)) {
			
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
			$postlikeModel = new Model_Postlike(); // Load Post Like Model
			$postcommentModel = new Model_Postcomment(); // Load Post Like Model
			
            foreach ($data->toArray() as $value) {
				
				$goodCount = "like_post_id = '".$value['post_id']."' And like_type = 1";
				$PostlikegoodCount = $postlikeModel->getpostlikeCount($goodCount);
				$badCount = "like_post_id = '".$value['post_id']."' And like_type = 2";
				$PostlikebadCount = $postlikeModel->getpostlikeCount($badCount);
				$commentCount = "cmt_post_id = '".$value['post_id']."' And cmt_ref = 0";
				$PostcommentCount = $postcommentModel->getfrontpostcommentCount($commentCount);
				
				$image_url = '';
				
				if($value['post_image'] != ''){
					$image_url = $config->BASE_URL . "public/upload/post/".$value['post_image'];
				}
				
				$video_url = '';
				
				if($value['post_video'] != ''){
					$video_url = $config->BASE_URL . "public/upload/post/".$value['post_video'];
				}
				
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
					'post_image' => $image_url,
					'post_video' => $video_url,
					'post_status' => $post_status,
					'post_good' => $PostlikegoodCount,
					'post_bad' => $PostlikebadCount,
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
	 
	  public function fetchalllikePost($where = "",$limit = "") {
        $select = $this->select()
				->setIntegrityCheck(false)
                //->from(array("postlike" => $this->POST_LIKE_TBL))
				->from(array("postlike" => $this->POST_LIKE_TBL), Array("cnt_like" => "count('*')"))
				->joinleft(array('post' => $this->POST_TBL), "post.post_id = postlike.like_post_id")
				->joinleft(array('user' => $this->USER_TBL), "user.usr_lgn_id = post.post_lgn_id", array('usr_name','usr_image'))
				//->joinleft(array("likepost" => $this->POST_LIKE_TBL),"likepost.like_post_id=post.post_id", Array("cnt" => "count(*)"))
                ->where("like_type = 1")
				->where("post_status = 1")
				->group("like_post_id")
				->order("cnt_like DESC")
				->where("post_approve_status = 1");
				//->where("post_approve_status = 1");
			//echo $select;exit;	
        if ($where != "") {
            $select->where($where);
        }
		if ($limit != "") {
            $select->limit($limit);
        }
		 //echo $select; exit;
        $data = parent::fetchAll($select);
		$finalArray = array();
		if (!empty($data)) {
			
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
			$postlikeModel = new Model_Postlike(); // Load Post Like Model
			$postcommentModel = new Model_Postcomment(); // Load Post Like Model
			
            foreach ($data->toArray() as $value) {
				
				$goodCount = "like_post_id = '".$value['post_id']."' And like_type = 1";
				$PostlikegoodCount = $postlikeModel->getpostlikeCount($goodCount);
				
				
				
				
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
					'post_good' => $PostlikegoodCount,
					
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
	 
	 public function fetchalldislikePost($where = "",$limit = "") {
        $select = $this->select()
				->setIntegrityCheck(false)          
				->from(array("postlike" => $this->POST_LIKE_TBL), Array("cnt_like" => "count('*')"))
				->joinleft(array('post' => $this->POST_TBL), "post.post_id = postlike.like_post_id")
				->joinleft(array('user' => $this->USER_TBL), "user.usr_lgn_id = post.post_lgn_id", array('usr_name','usr_image'))		
                ->where("like_type = 2")
				->where("post_status = 1")
				->group("like_post_id")
				->order("cnt_like DESC")
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
				
				$badCount = "like_post_id = '".$value['post_id']."' And like_type = 2";
				$PostlikebadCount = $postlikeModel->getpostlikeCount($badCount);
				
				
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
					'post_bad' => $PostlikebadCount,				
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
	 public function fetchPostByuserId($id,$where = "", $limit = "", $offset = "0", $order = "post_id", $sort = "ASC") {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("post" => $this->POST_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = post.post_lgn_id", array('usr_name','usr_image'))
                ->where("post_status != 3")
				->where("post_lgn_id = ?", $id);
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

	public function getpostCountByuserId($id,$where) {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("post" => $this->POST_TBL), Array("cnt" => "count(*)"))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = post.post_lgn_id", array('usr_name'))
                ->where("post_status != 3")
				->where("post_lgn_id = ?", $id);
        if ($where != "") {
            $select->where($where);
        }
		
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }	
	
	
		
}

?>