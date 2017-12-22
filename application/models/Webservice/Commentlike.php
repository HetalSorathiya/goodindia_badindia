<?php

/*
 * This class is used for as Model for Post Like table 
 */

class Model_Webservice_Commentlike extends Model_Base {
	
	protected $_name, $_primaryKey;

    public function init() {
        $this->_name = $this->COMMENT_LIKE_TBL;
        $this->_primaryKey = 'like_id';
    }	
	
	public function fetchCommentLike($where) {
        $select = $this->select()
				->setIntegrityCheck(false)
                ->from(array("postgood" => $this->COMMENT_LIKE_TBL))
				->joinleft(array('usr' => $this->USER_TBL), "usr.usr_lgn_id = postgood.like_lgn_id")
               // ->where("post_type = 1")
				->where("like_status = 1");
				
        if ($where != "") {
            $select->where($where);
        }
		//echo $select;exit;
        $rows = parent::fetchRow($select);
        return $rows;
    } 
	public function getcommentlikeCount($where) {
        $select = $this->select()
                ->from(array("comment" => $this->COMMENT_LIKE_TBL), Array("cnt" => "count(*)"))
                ->where("like_status != 2");
        if ($where != "") {
            $select->where($where);
        }
			//echo $select;exit;
        $rows = parent::fetchRow($select);
        return($rows->cnt);
    }
		
}

?>