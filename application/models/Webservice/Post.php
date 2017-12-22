<?php

/*
 * This class is used for as Model for post table 
 */

class Model_Webservice_Post extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->POST_TBL;
        $this->_primaryKey = 'post_id';
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
     * Fetch an individual entry by post id
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryBypostId($id,$where) {
        $select = $this->select()
                ->from(array("post" => $this->POST_TBL))
                ->where("post_id = ?", $id)
                ->where("post_status!= 3");
		if ($where != "") {
            $select->where($where);
        }
        $data = $this->fetchRow($select);
		//echo $select; exit;
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
				->joinleft(array('cat' => $this->CATEGORY_TBL), "cat.cat_id = post.post_cat_id", array('cat_name'))
				->joinleft(array('user' => $this->USER_TBL), "user.usr_lgn_id = post.post_lgn_id")
                ->where("post_status != 3")
				->where("post_approve_status = 1");
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
			$postlikeModel = new Model_Webservice_Postlike(); // Load Post Like Model
			$postcommentModel = new Model_Webservice_Postcomment(); // Load Post Like Model
			$userModel = new Model_Webservice_User(); // Load Post Like Model
			
            foreach ($data->toArray() as $value) {
				
				$goodCount = "like_post_id = '".$value['post_id']."' And like_type = 1";
				$PostlikegoodCount = $postlikeModel->getpostlikeCount($goodCount);
				$badCount = "like_post_id = '".$value['post_id']."' And like_type = 2";
				$PostlikebadCount = $postlikeModel->getpostlikeCount($badCount);
				$commentCount = "cmt_post_id = '".$value['post_id']."' And cmt_ref = 0";
				$PostcommentCount = $postcommentModel->getpostcommentCount($commentCount);
				
				$gender=$value['usr_gender'];
				$edit_image = $value['usr_update_img'];
				$social_image = $value['usr_image'];				
				$fetchuserprofileimg = $userModel->fetchuserprofileimg($social_image,$edit_image,$gender);
				
				
				$image_url = '';
				
				if($value['post_image'] != ''){
					$image_url = $config->BASE_URL . "public/upload/post/".$value['post_image'];
				}
				
				$video_url = '';
				
				if($value['post_video'] != ''){
					$video_url = $config->BASE_URL . "public/upload/post/".$value['post_video'];
				}
				$post_share_url = $config->BASE_URL ."index/postdetail/post_id/".$value['post_id'] ."/post_cat/" .$value['post_cat_id'];
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
					'usr_image' => $fetchuserprofileimg,
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
					'post_share_link' => $post_share_url,
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
                ->from(array("post" => $this->POST_TBL), Array("cnt" => "count(*)"))
                ->where("post_status != 3");
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	/**
     *  This function is used to get total post
     * @return int
     */
    public function getposttotalCount() {
        $select = $this->select()
                ->from(array("post" => $this->POST_TBL), Array("cnt" => "count(*)"))
                ->where("post_status != 3");        
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
public function getgoodpostCount($where) {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("postgood" => $this->POST_TBL), Array("cnt" => "count(*)"))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = postgood.post_lgn_id")
                ->where("post_type = 1")
				->where("post_status = 1");
				
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    } 
	
	public function getbadpostCount($where) {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("postbad" => $this->POST_TBL), Array("cnt" => "count(*)"))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = postbad.post_lgn_id")
                ->where("post_type = 2")
				->where("post_status = 1");
				
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
        $rows = parent::fetchRow($select);
        return($rows->cnt);
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
}

?>