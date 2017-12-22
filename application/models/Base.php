<?php

/*
 * This class is used for common same function.
 */

class Model_Base extends Zend_Db_Table {

    //protected $_name = '';
	
	protected $ADMIN_USER_TBL = 'tbl_admin_user';
	protected $USER_TBL = 'tbl_user';
	protected $CMS_TBL = 'tbl_cms';
    protected $LOGIN_TBL = 'tbl_login';
	protected $GOOGLE_RESPONSE_TBL = 'tbl_google_response';
	protected $FACEBOOK_RESPONSE_TBL = 'tbl_facebook_response';
    protected $CATEGORY_TBL = 'tbl_category';
	protected $POST_TBL = 'tbl_post';
	protected $POST_LIKE_TBL = 'tbl_post_like';
	protected $POST_COMMENT_TBL = 'tbl_post_comment';
	protected $COMMENTPOST_NOTIFY_TBL = 'tbl_commentpost_notification';
	protected $COMMENT_LIKE_TBL='tbl_post_comment_like';
	protected $LOCATION_TBL='tbl_location';
	protected $REPORT_TYPE_TBL='tbl_report_type';
	protected $REPORT_ABUSE_TBL='tbl_report_abuse';
	protected $REPORT_ABUSE_LOG_TBL='tbl_report_abuse_log';
	
	
    /**
     * Smart save
     */
    public function save($param, $primaryKeyColumn = '') {

	$this->registry = Zend_Registry::getInstance();
	$fields = $this->info(Zend_Db_Table_Abstract::COLS);
	foreach ($param as $field => $value) {
	    if (!in_array($field, $fields)) {
		unset($param[$field]);
	    }
	}

	try {
	    if (isset($param[$primaryKeyColumn]) && $param[$primaryKeyColumn] != "") {
		$where = $this->getAdapter()->quoteInto($primaryKeyColumn . ' = ?', $param[$primaryKeyColumn]);
		$idValue = $param[$primaryKeyColumn];
		$this->update($param, $where);
	    } else {
		$idValue = $this->insert($param);
	    }
	} catch (Exception $e) {
	    throw new Exception($e);
	}
	return $idValue;
    }

}
