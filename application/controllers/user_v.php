<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_v extends CI_Controller {
    public function index() {
        $request = $this->uri->segment(2);
        $profileUser = User::get_by_name($request);
        if($profileUser) {
            if(($user = $this->m_session->get_current_user())){
                $self = ($profileUser->id == $user->id);
            } else {
                $self = false;
            }

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
