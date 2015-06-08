<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Our Admin Controller, handles all admin requests
class Admin extends CI_Controller {
    // On the index page of the admin site, load this function
    public function index() {
        // If no user is logged in, show 404
        if((!$user = $this->m_session->get_current_user())){
            show_404();
        }
        // If the user is an admin, let them see the admin page
        if($user->get_rank() == 'admin'){
            // Get the last 18 IP addresses that logged into the website
            $this->db->select('last_ipv4');
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get('sessions', 18);

            if($query->num_rows() > 0) {
                $last_ips = $query->result_array();
            }
            // Data array to parse into the view, we want to load the admin page and
            // parse the $last_ips variable into the page.
            $data = array(
                'main_content' => 'admin',
                'last_ips' => $last_ips
            );
            // Load the template
            $this->load->view('template', $data);
        } else {
            // If they are not an admin, load the 404 page
            show_404();
        }
    }
}
