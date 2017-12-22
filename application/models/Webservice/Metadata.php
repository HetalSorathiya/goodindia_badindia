<?php

/*
 * This class is used for as Model for Metadata table 
 */

class Model_Webservice_Metadata extends Model_Base {

    protected $_name = 'tbl_metadata';
    protected $_primaryKey = 'mtd_id';

    /**
     * Fetch an individual entry
     *
     * @param  int|string $key
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchEntryByKey($key) {
        $select = $this->select()
                ->from(array('job' => 'tbl_metadata'))
                ->where('mtd_key = ?', $key);
        // see reasoning in fetchEntries() as to why we return only an array
        $data = $this->fetchRow($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }

}
