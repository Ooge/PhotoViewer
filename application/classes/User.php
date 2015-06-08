<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Our user class that extends the TableObject
class User extends TableObject {
    // The ID of the user
    public $id;

    // Our DB variable, user profile cache and rank cache.
    private $db;
    private $_profile_cache;
    private $_rank_cache;

    // A constructor that sets up the TableObject variables.
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

    // A function to return the users profile object.
    public function get_profile() {
        if (!$this->_profile_cache) {
            // If their profile is not in the cache, create a new UserProfile
            $this->_profile_cache = new UserProfile($this);
        }
        // Return it.
        return $this->_profile_cache;
    }
    // A function that returns the users rank. (What we use to see if a user is an admin.)
    public function get_rank() {
        if(!$this->_rank_cache) {
            // If we dont have their rank stored in our cache, search for it in the DB
            $this->db->where('user_id', $this->id);
            // Get their rank from the user_ranks table.
            $query = $this->db->get('user_ranks', 1);
            // If there is no result, return them as a user.
            if($query->num_rows() == 0){
                return 'user';
            }
            // Get the result and then add it to the rank cache
            $result = $query->result_array();
            $row = $result[0];
            $this->_rank_cache = $row['rank'];
        }
        // return the rank cache.
        return $this->_rank_cache;
    }
    // Set a users rank
    public function set_rank($rank) {
        // Get the current rank
        $this->db->where('user_id', $this->id);
        $query = $this->db->get('user_ranks');
        // If we get a result
        if($query->num_rows() > 0) {
            // get the result array
            $result = $query->result_array();
            $row = $result[0];
            $cur_rank = $row['rank'];
            // If cur rank == rank we are setting
            if($cur_rank == $rank) {
                return $this->_rank_cache;
            } else {
                // Update rank if difference is occuring
                $this->db->where('user_id', $this->id);
                $query = $this->db->update('user_ranks', array('rank' => $rank));
                $this->_rank_cache = $rank;
                return $this->_rank_cache;
            }
        } else {
            // If there is no rank set for the user - at all - insert a new one for them.
            $this->db->insert('user_ranks', array('user_id' => $this->id, 'rank' => $rank));
            $this->_rank_cache = $rank;
            return $this->_rank_cache;
        }
    }

    /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */
    /* Static method start                                               */
    /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */ /* */

    // Here are our static functions to get the user object through mutliple methods
    // This works similar to how the Image class works, however we can also get users
    // via usernames
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
