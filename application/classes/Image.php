<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends TableObject {
    public $id;
    private $db;

    public function __construct($id, $construct = true) {
        parent::__construct();

        $this->db = DB();

        $this->id = $id;
        $this->_tableName = 'uploads';
        $this->_rawTableName .= $this->_tableName;
        $this->_uniqueIdentifierColumn = 'id';
        $this->_uniqueIdentifierValue = $id;
        if ($construct) {
            $this->constructed();
        }
    }

    public function delete() {
        $this->deleted = 1;
    }

    public function reinstate() {
        $this->deleted = 0;
    }

    public function get_author() {
        return User::get_by_id($this->user_id);
    }


    public function get_thumbnail() {
        $imagick = new \Imagick($this->file);
        $imagick->thumbnailImage(219, 219, true);
        header("Content-Type: image/jpg");
        return $imagick->getImageBlob();
    }

    /* */ /* */ /* */ /* */ /* */
    /* Static method start     */
    /* */ /* */ /* */ /* */ /* */
    public static $_imageCache = array();

    public static function from_row_array($array) {
        $img = new Image($array['id'], false);
        $img->set_table_values($array);
        return $img;
    }

    public static function get_by_id($id) {
        if (isset(self::$_imageCache[$id])) {
            return self::$_imageCache[$id];
        }
        $img = new Image($id);
        self::$_imageCache[$id] = $img;
        return $img;
    }

    public static function get_by_gid($gid){
        $db = DB();
        $db->where('gid', $gid);
        $db->select('id');
        $query = $db->get('uploads', 1);
        if ($query->num_rows() == 0) {
            return false;
        }
        $result = $query->result_array();
        $row = $result[0];
        $img = new Image($row['id']);
        self::$_imageCache[$img->id] = $img;
        return $img;
    }
}
