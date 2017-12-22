<?php

/*
 * This class is used for as Model for Post like table 
 */

class Model_Postlike extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->POST_LIKE_TBL;
        $this->_primaryKey = 'like_id';
    }
	
	/**
     * Fetch an individual entry
     *
     * @param  int|string $id
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchEntryById($id) {
        $select = $this->select()
                ->from(array("post" => $this->POST_LIKE_TBL))
                ->where("like_id = ?", $id)
                ->where("like_status!= 2");
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
	
	/**
     *  This function is used to get postlike
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchPost($where = "", $limit = "", $offset = "0", $order = "like_id", $sort = "ASC") {
        $select = $this->select()
				//->setIntegrityCheck(false)
                ->from(array("post" => $this->POST_LIKE_TBL))
				//->joinleft(array('vid' => $this->VIDEO_TBL), "cat.like_id = vid.vid_like_id", array('vid_title'))
                ->where("like_status != 2");
        if ($where != "") {
            $select->where($where);
        }
        if ($limit != "") {
            $select->limit($limit, $offset);
        }

        $select->order($order . " " . $sort);
        #echo $select; exit;
        $data = parent::fetchAll($select);
		$finalArray = array();
		if (!empty($data)) {
			
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
			
            foreach ($data->toArray() as $value) {
				
				$image_url = '';
				
				if($value['post_image'] != ''){
					$image_url = $config->BASE_URL . "public/upload/post/".$value['post_image'];
				}
				
				$video_url = '';
				
				if($value['post_video'] != ''){
					$video_url = $config->BASE_URL . "public/upload/post/video/".$value['post_video'];
				}
				
				$like_status = "";
				if ($value['like_status'] == 0) {
					$like_status = "Inactive";
				} else if ($value['like_status'] == 1) {
					$like_status = "Active";
				} else if ($value['like_status'] == 2) {
					$like_status = "Approval";
				}
				
				$post_type = "";
				if ($value['post_type'] == 0) {
					$post_type = "Bad";
				} else if ($value['post_type'] == 1) {
					$post_type = "Good";
				}
				
                $postDetailArray = Array(
					'like_id' => $value['like_id'],
					'post_title' => $value['post_title'],
					'post_desc' => $value['post_desc'],
					'post_type' => $post_type,
					'post_location' => $value['post_location'],
					'post_lattitude' => $value['post_lattitude'],
					'post_longitude' => $value['post_longitude'],
					'post_image' => $image_url,
					'post_video' => $video_url,
					'like_status' => $like_status,
					'post_createddate' => $value['post_createddate'],
					'post_updateddate' => $value['post_updateddate'],
				);
				$finalArray[$value['like_id']] = $postDetailArray;
            }
            return array_values($finalArray);
        } else {
            return array();
        }      
    }   

    /**
     *  This function is used to get total postlike
     * @return int
     */
    public function getpostlikeCount($where) {
        $select = $this->select()
                ->from(array("post" => $this->POST_LIKE_TBL), Array("cnt" => "count(*)"))
                ->where("like_status != 2");
        if ($where != "") {
            $select->where($where);
        }
		
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
	
	
		
}

?>