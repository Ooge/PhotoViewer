<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class UserPost extends TableObject {

    public $id;

    private $_favs_cache = null;
    private $_profile_cache = null;
    private $_user = null;

    public function __construct($id, $construct = true) {
        parent::__construct();

        $this->id = $id;
        $this->_tableName = 'user_posts';
        $this->_rawTableName .= $this->_tableName;
        $this->_uniqueIdentifierColumn = 'id';
        $this->_uniqueIdentifierValue = $id;
        if ($construct) {
            $this->constructed();
        }
        
    }

    public function get_user() {
        if(!$this->_user) {
            $this->_user = User::get_by_id($this->user_id);
        }
        return $this->_user;
    }

    public function get_profile() {
        if (!$this->_profile_cache) {
            $this->_profile_cache = User::get_by_id($this->user_id)->get_profile();
        }
        return $this->_profile_cache;
    }

    public function get_favs() {
        if (!$this->_favs_cache) {
            $this->_favs_cache = array();
            $this->CI->db->where('post_id', $this->id);
            $query = $this->CI->db->get('user_post_favs');
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                foreach ($result as $row) {
                    $this->_favs_cache[] = $row['faver_id'];
                }
            }
        }
        return $this->_favs_cache;
    }

    // $faver == object
    public function toggle_fav($faver) {
        $favs = $this->get_favs();
        if (in_array($faver->id, $favs)) {
            
            $this->CI->db->where('post_id', $this->id);
            $this->CI->db->where('faver_id', $faver->id);
            $this->CI->db->delete('user_post_favs');
            $newfavers = array();
            foreach ($this->_favs_cache as $fav) {
                if ($fav != $faver->id) {
                    $newfavers[] = $fav;
                }
            }
            $this->_favs_cache = $newfavers;
        } else {
            
            $this->CI->db->insert('user_post_favs', array('post_id' => $this->id, 'faver_id' => $faver->id, 'timestamp' => time()));
            $this->_favs_cache[] = $faver->id;
        }
        $this->favs = count($this->_favs_cache);
        return true;
    }

    // $faver == id | object
    public function has_faved($faver) {
        $faver_id = $faver;
        if (is_object($faver)) {
            $faver_id = $faver->id;
        }
        $favs = $this->get_favs();
        return in_array($faver_id, $favs);
    }

    /*****************************
    *   Static Functions
    *****************************/

    private static $_cache = array();

    public static function get_by_id($id) {
        $db = DB();
        $db->where('id', $id);
        $query = $db->get('user_posts', 1);
        if ($query->num_rows() == 0) {
            return false;
        }
        $result = $query->result_array();
        $row = $result[0];
        
        $retrieved_post = new UserPost($row['id'], false);
        $retrieved_post->set_table_values($row);
        return $retrieved_post;
    }

    public static function get_by_md5($md5) {
        $db = DB();
        $db->where('MD5(id)', $md5);
        $query = $db->get('user_posts', 1);
        if ($query->num_rows() == 0) {
            return false;
        }
        $result = $query->result_array();
        $row = $result[0];
        
        $retrieved_post = new UserPost($row['id'], false);
        $retrieved_post->set_table_values($row);
        return $retrieved_post;
    }

    public static function from_row_array($array) {
        if (isset(self::$_cache[$array['id']])) {
            self::$_cache[$array['id']]->constructed();
            return self::$_cache[$array['id']];
        }
        $instance = new UserPost($array['id'], false);
        $instance->set_table_values($array);
        return $instance;
    }
}