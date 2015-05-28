<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    public function index() {
        $request = $this->uri->segment(2);
        if(($profileUser = User::get_by_name($request))) {
            $profile = $profileUser->get_profile();

            $page_data = array(
                'main_content' => 'profile',
                'profileUser' => $profileUser,
                'profile' => $profile,
                'is_self' => $self
                );
            $this->load->view('template', $page_data);
        } else {
            show_404();
        }
    }
}
