<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Our UserProfile class which loads the user profile data
// This doesnt extend TableObject as there is no table for specific
// User profiles.
class UserProfile {
    // The user of the user profile
    private $user;
    // codeigniter function
    private $CI;
    // Image cache for the images on a profile
    private $_imageCache = null;
    // Constructor to set the CI instance and User
    public function __construct($user) {
        $this->CI =& get_instance();
        $this->user = $user;
    }
    // Get the images for the users profile and return them in an array
    public function get_images($limit) {
        // If the images are not in the image cache, then return.
        if(!$this->_imageCache) {
            $this->_imageCache = array();
            // Select where the user ID is this->id
            $this->CI->db->where('user_id', $this->user->id);
            // and the image is not deleted
            $this->CI->db->where('deleted', 0);
            // Order by newer ones first
            $this->CI->db->order_by('time', 'desc');
            // Get from uploads
            $query = $this->CI->db->get('uploads', $limit);
            // If we find a result, add the images to an array that will be returned.
            if($query->num_rows() > 0) {
                $result = $query->result_array();
                foreach($result as $image) {
                    $this->_imageCache[] = Image::from_row_array($image);
                }
            } else {
                // If we find no images, return 0
                return 0;
            }
        }
        // return the image cache
        return $this->_imageCache;
    }
    // Remove an image from a profile. call the delete function of the image supplied.
    public function remove_image($imageGID) {
        $image = Image::get_by_gid($imageGID);

        if($image->user_id == $this->user->id)
            $image->delete();
    }
}
