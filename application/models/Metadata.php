<?php

/*
 * This class is used for as Model for Metadata table 
 */

class Model_Metadata extends Model_Base {

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
	
	/**
     *  This function is used to get metadata
     * @return null|Zend_Db_Table_Row_Abstract
     */
	public function fetchall_metadata($where = "") {
        $select = $this->select()
                ->from(array('set' => "tbl_metadata"));
        // see reasoning in fetchEntries() as to why we return only an array
        if ($where != '') {
            $select->where($where);
        }
        $data = $this->fetchAll($select);
        if (!empty($data)) {
            return $data->toArray();
        } else {
            return array();
        }
    }
    /**
     *  This function is used to get metadata
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchall_metadataArray($where = "") {
        $fetchData = $this->fetchall_metadata($where);
        $finalArray = array();
        foreach($fetchData as $_key => $_value){
            $finalArray[$_value['mtd_key']] = $_value['mtd_value'];
        }
        return $finalArray;
    }
    /**
     *  This function is used to update metadata
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function save_update_data($data) {
        foreach ($data as $key => $value):
            $where = "'" . $key . "'";
            $data1 = array('mtd_value' => $value);
            $Update = $this->update($data1, 'mtd_key =' . $where);
        endforeach;
    }

}
