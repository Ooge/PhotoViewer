<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function index() {
        if((!$user = $this->m_session->get_current_user())){
            show_404();
        }
        if($user->get_rank() == 'admin'){
            $data = array(
                'main_content' => 'admin'
            );

            $this->load->view('template', $data);
        } else {
            show_404();
        }
    }
}
