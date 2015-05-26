<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class M_user_manager extends CI_Model {
    
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

    public function get_all_groups() {
        $query = $this->db->get('groups');
        if($query->num_rows() > 0){
            $results = $query->result_array();
            $groups = array();
            foreach($results as $row){
                $groups[] = Group::from_row_array($row);
            }
            return $groups;
        }
        return false;
    }
}