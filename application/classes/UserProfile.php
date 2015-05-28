<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserProfile {
    private $user;
    private $CI;

    private $_imageCache = null;

    public function __construct($user) {
        $this->CI =& get_instance();
        $this->user = $user;
    }

    public function get_images($limit) {
        if(!$this->_imageCache) {
            $this->_imageCache = array();
            $this->CI->db->where('user_id', $this->user->id);
            $this->CI->db->where('deleted', 0);
            $this->CI->db->order_by('time', 'desc');
            $query = $this->CI->db->get('uploads', $limit);

            if($query->num_rows() > 0) {
                $result = $query->result_array();
                foreach($result as $image) {
                    $this->_imageCache[] = Image::from_row_array($image);
                }
            } else {
                return 0;
            }
        }
        return $this->_imageCache;
    }

    public function remove_image($imageGID) {
        $image = Image::get_by_gid($imageGID);

        if($image->user_id == $this->user->id)
            $image->delete();
    }
}
