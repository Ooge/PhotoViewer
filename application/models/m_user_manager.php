<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user_manager extends CI_Model {
    // Return all users registered on the website
    public function get_all_users() {
        $query = $this->db->get('users');
        if($users->num_rows() == 0) {
            return false;
        }
        $results = $query->result_array();
        $users = array();
        foreach($results as $row){
            $users[] = User::from_row_array($row);
        }
        return $users;
    }
}
