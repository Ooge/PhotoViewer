<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Create our image class inheriting our TableObject class
class Image extends TableObject {
    // Public variable storing the ID of the row
    public $id;
    // Private DB variable to access DB functions inside this class
    private $db;
    // Our public constructer that uses the TableObject's constructor to collect the data from the
    // MySQL table.
    public function __construct($id, $construct = true) {
        parent::__construct(); // TableObject Constructor

        $this->db = DB(); // Setting up DB functions in this class

        $this->id = $id; //Set the ID as the ID parsed in the constructor
        $this->_tableName = 'uploads'; // The table we want our TableObject to load from
        $this->_rawTableName .= $this->_tableName; // The raw table name (which is the same as our table name)
        $this->_uniqueIdentifierColumn = 'id'; // The identifier column in our table
        $this->_uniqueIdentifierValue = $id; // The value we want to search for to select the row
        if ($construct) {
            $this->constructed(); // Only construct if we are not getting information from a static function
        }
    }
    // Set an image as deleted
    public function delete() {
        $this->deleted = 1;
    }
    // Reinstate an image from deletion
    public function reinstate() {
        $this->deleted = 0;
    }
    // Get the author of the image upload
    public function get_author() {
        return User::get_by_id($this->user_id);
    }

    /* */ /* */ /* */ /* */ /* */
    /*   Static method start   */
    /* */ /* */ /* */ /* */ /* */
    public static $_imageCache = array(); // Our temporary image cache

    // Get the Image object from a row array we already have
    public static function from_row_array($array) {
        // Create a new Image object using the Id from the row array
        $img = new Image($array['id'], false);
        // Run the TableObject set_table_values to assign the table values
        $img->set_table_values($array);
        return $img;
    }

    // Get the Image object by an ID we already have.
    public static function get_by_id($id) {
        // Check we dont have this entry in our cache already
        if (isset(self::$_imageCache[$id])) {
            return self::$_imageCache[$id];
        }
        // If its not in the cache, lets create a new one based off this ID
        $img = new Image($id);
        // Set it to our cache
        self::$_imageCache[$id] = $img;
        // Return it.
        return $img;
    }

    // Get an Image object by an image GID we already have
    public static function get_by_gid($gid){
        // Create a database object
        $db = DB();
        // Select the ID where the GID is equal to the GID supplied
        $db->where('gid', $gid);
        $db->select('id');
        // Run the query, limit the results by 1
        $query = $db->get('uploads', 1);
        // If there are no results, return 0
        if ($query->num_rows() == 0) {
            return false;
        }
        // Get the results
        $result = $query->result_array();
        // Get the first row
        $row = $result[0];
        // Create a new Image object using the ID found
        $img = new Image($row['id']);
        // Add it to our cache
        self::$_imageCache[$img->id] = $img;
        // Return image
        return $img;
    }
}
