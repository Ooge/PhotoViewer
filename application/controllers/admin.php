<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function index() {
        if((!$user = $this->m_session->get_current_user())){
            show_404();
        }
        if($user->get_rank() == 'admin'){
            $this->db->select('last_ipv4');
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get('sessions', 20);

            if($query->num_rows() > 0) {
                $last_ips = $query->result_array();
            }

            $data = array(
                'main_content' => 'admin',
                'last_ips' => $last_ips
            );

            $this->load->view('template', $data);
        } else {
            show_404();
        }
    }
}
