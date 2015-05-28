<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends TableObject {
    public $id;

    private $db;
    private $_profile_cache;
    private $_rank_cache;

    public function __construct($id, $construct = true) {
        parent::__construct();

        $this->db = DB();

        $this->id = $id;
        $this->_tableName = 'users';
        $this->_rawTableName .= $this->_tableName;
        $this->_uniqueIdentifierColumn = 'id';
        $this->_uniqueIdentifierValue = $id;
        if ($construct) {
            $this->constructed();
        }
    }

    public function get_profile() {
        if (!$this->_profile_cache) {
            $this->_profile_cache = new UserProfile($this);
        }
        return $this->_profile_cache;
    }

    public function get_rank() {
        if(!$this->_rank_cache) {
            $this->db->where('user_id', $this->id);
            $query = $this->db->get('user_ranks', 1);

            if($query->num_rows() == 0){
                return 'user';
            }

            $result = $query->result_array();
            $row = $result[0];
            $this->_rank_cache = $row['rank'];
        }

        return $this->_rank_cache;
    }

    public function set_rank($rank) {
        $this->db->where('user_id', $this->id);
        $query = $this->db->get('user_ranks');

        if($query->num_rows() > 0) {
            $result = $query->result_array();
            $row = $result[0];
            $cur_rank = $row['rank'];

            if($cur_rank == $rank) {
                return $this->_rank_cache;
            } else {
                $this->db->where('user_id', $this->id);
                $query = $this->db->update('user_ranks', array('rank' => $rank));
                $this->_rank_cache = $rank;
                return $this->_rank_cache;
            }
        } else {
            $this->db->insert('user_ranks', array('user_id' => $this->id, 'rank' => $rank));
            $this->_rank_cache = $rank;
            return $this->_rank_cache;
        }
    }

    public function toggle_follow($following) {
        if(is_object($following)) {
            $following = $following->id;
        } else if(!is_numeric($following)) {
            return false;
        }

        $this->db->where('user_id', $this->id);
        $this->db->where('following_id', $following);
        $query = $this->db->get('user_followers');

        if($query->num_rows() > 0) {
            $this->db->where('user_id', $this->id);
            $this->db->where('following_id', $following);
            $this->db->delete('user_followers');
            return true;
        } else {

            $data = array(
                'user_id' => $this->id,
                'following_id' => $following,
                'timestamp' => time()
                );

            $query = $this->db->insert('user_followers', $data);
            return true;
        }
    }

    public function is_following($following) {
        if(!$following) {
            return false;
        }

        $this->db->where('user_id', $this->id);
        $this->db->where('following_id', $following);
        $query = $this->db->get('user_followers');

        if($query->num_rows() == 0) {
            return false;
        } else {
            return true;
        }
        return false;
    }

    public function follower_count() {
        $this->db->where('following_id', $this->id);
        $query = $this->db->get('user_followers');

        return $query->num_rows();
    }

    public function following_count() {
        $this->db->where('user_id', $this->id);
        $query = $this->db->get('user_followers');

        return $query->num_rows();
    }

    public function post_count() {
        $this->db->where('user_id', $this->id);
        $query = $this->db->get('user_posts');

        return $query->num_rows();
    }
    /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */
    /* Static method start                                               */
    /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */


    public static $_userCache = array();

    public static function from_row_array($array) {
        $user = new User($array['id'], false);
        $user->set_table_values($array);
        return $user;
    }

    public static function get_by_id($id) {
        if (isset(self::$_userCache[$id])) {
            return self::$_userCache[$id];
        }
        $user = new User($id);
        self::$_userCache[$id] = $user;
        return $user;
    }

    public static function get_by_md5($md5) {
        if (count(self::$_userCache) > 0) {
            foreach (self::$_userCache as $id => $u) {
                if (md5($id) == $md5) {
                    return $u;
                }
            }
        }

        $db = DB();
        $db->where('MD5(id)', $md5);
        $db->select('id');
        $query = $db->get('users', 1);
        if ($query->num_rows() == 0) {
            return false;
        }
        $result = $query->result_array();
        $row = $result[0];
        $u = new User($row['id']);
        self::$_userCache[$u->id] = $u;
        return $u;
    }

    public static function get_by_name($name) {
        $name = strtolower($name);
        $db = DB();
        $db->where('LOWER(username)', $name);
        $db->select('id');
        $query = $db->get('users', 1);
        if ($query->num_rows() == 0) {
            return false;
        }
        $result = $query->result_array();
        $row = $result[0];
        $u = new User($row['id']);
        self::$_userCache[$u->id] = $u;
        return $u;
    }


    private static $_nameCache = array();
    public static function get_user_name($id) {
        if (isset(self::$_nameCache[$id])) {
            return self::$_nameCache[$id];
        }
        $db = DB();
        $db->where('id', $id);
        $db->select('username');
        $query = $db->get('users', 1);
        if ($query->num_rows() == 0) {
            return false;
        }
        $result = $query->result_array();
        $row = $result[0];
        self::$_nameCache[$id] = $row['username'];
        return $row['username'];
    }
}
