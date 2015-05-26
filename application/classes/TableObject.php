<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class TableObject {
    protected $CI;

    protected $_tableName;
    protected $_rawTableName;
    protected $_uniqueIdentifierColumn;
    protected $_uniqueIdentifierValue;
    protected $_tableValues = array();

    public function __construct() {
        $this->CI =& get_instance();
        $this->_rawTableName = $this->CI->db->dbprefix;
    }

    protected function constructed() {
        $this->CI->db->where($this->_uniqueIdentifierColumn, $this->_uniqueIdentifierValue);
        $get = $this->CI->db->get($this->_tableName, 1);

        $result_array = $get->result_array();
        if (count($result_array) == 0) {
            return;
        }
        foreach ($result_array[0] as $column => $field) {
            $this->_tableValues[$column] = $field;
        }
    }

    function __get($key) {
        if (isset($this->_tableValues[$key])) {
            return $this->_tableValues[$key];
        }
        return null;
    }

    function __set($name, $val) {
        if ($name == $this->_uniqueIdentifierColumn) {
            return false;
        }
        $this->CI->db->update($this->_tableName, array($name => $val), array($this->_uniqueIdentifierColumn => $this->_uniqueIdentifierValue));
        $this->_tableValues[$name] = $val;
        return $this->CI->db->affected_rows() > 0;
    }

    public function set_table_values($values) {
        $this->_tableValues = $values;
    }

    public function get_data_array() {
        return $this->_tableValues;
    }
}