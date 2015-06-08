<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This is the user view controller. It handles user profiles on the website
class User_v extends CI_Controller {
    public function index() {
        // We use routing to make this controller accessable through
        // http://domain/user/<userName>
        // We get the 2nd segment which is the user name
        $request = $this->uri->segment(2);
        // we then get the profile user by the requested name
        $profileUser = User::get_by_name($request);
        // If we find a profile
        if($profileUser) {
            // check if this is your own profile, or someone elses.
            if(($user = $this->m_session->get_current_user())){
                $self = ($profileUser->id == $user->id);
            } else {
                $self = false;
            }
            // Get their profile data
            $profile = $profileUser->get_profile();
            // Data to load into the profile page
            $page_data = array(
                'main_content' => 'profile',
                'profileUser' => $profileUser,
                'profile' => $profile,
                'is_self' => $self
                );
            $this->load->view('template', $page_data);
        } else {
            //show 404 if user is not found
            show_404();
        }
    }
}
