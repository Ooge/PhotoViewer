<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class UserProfile {
    private $user;
    private $CI;

    private $_post_cache = null;

    public function __construct($user) {
        $this->CI =& get_instance();
        $this->user = $user;
    }

    public function get_posts() {
        if(!$this->_post_cache) {
            $this->_post_cache = array();
            $this->CI->db->where('user_id', $this->user->id);
            $this->CI->db->order_by('time_posted', 'desc');
            $query = $this->CI->db->get('user_posts', 30);
            
            if($query->num_rows() > 0) {
                $result = $query->result_array();
                foreach($result as $post) {
                    $this->_post_cache[] = UserPost::from_row_array($post);
                }
            }
        }
        return $this->_post_cache;
    }

    public function add_post($post_message) {
        $post_message = $this->CI->security->xss_clean($post_message);
        $time = time();
        $this->CI->db->insert('user_posts', array('user_id' => $this->user->id, 'post_message' => $post_message, 'time_posted' => $time));

        $new_post_id = $this->CI->db->insert_id();
        $new_post = UserPost::get_by_id($new_post_id);
        $this->_post_cache[] = $new_post;
        return $new_post;
    }

    public function remove_post($post_id, $user_id) {
        $post_to_remove = UserPost::get_by_md5($post_id);
        
        $this->CI->db->where('MD5(id)', $post_id);
        $this->CI->db->where('user_id', $user_id);
        $this->CI->db->delete('user_posts');
    }
}