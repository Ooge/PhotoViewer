<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Our TableObject class which is a type of ORM allowing us to directly access
// MySQL table data for an individual row from within our class object.
class TableObject {
    // CodeIgniter variable
    protected $CI;

    // The variables that will be set from inside the sub-class that extends this class
    protected $_tableName;
    protected $_rawTableName;
    protected $_uniqueIdentifierColumn;
    protected $_uniqueIdentifierValue;
    protected $_tableValues = array();

    // Our constructor
    public function __construct() {
        $this->CI =& get_instance();
        $this->_rawTableName = $this->CI->db->dbprefix;
    }

    // If we have constructed using the above constructor, we will then run this which will gather the
    // Data from the selected MySQL database. This is not ran when we use the from_row_array() function
    // as we already have the data we need.
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
    // We make our getter return the key that is supplied from the database data.
    function __get($key) {
        if (isset($this->_tableValues[$key])) {
            return $this->_tableValues[$key];
        }
        return null;
    }
    // We make our setter set the value of the data inside the database and then update the database.
    function __set($name, $val) {
        if ($name == $this->_uniqueIdentifierColumn) {
            return false;
        }
        $this->CI->db->update($this->_tableName, array($name => $val), array($this->_uniqueIdentifierColumn => $this->_uniqueIdentifierValue));
        $this->_tableValues[$name] = $val;
        return $this->CI->db->affected_rows() > 0;
    }
    // Here we can force our tableValues
    public function set_table_values($values) {
        $this->_tableValues = $values;
    }
    // This allows us to get the entire data as an array.
    public function get_data_array() {
        return $this->_tableValues;
    }
}
