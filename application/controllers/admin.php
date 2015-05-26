<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function index() {
        if((!$user = $this->m_session->get_current_user())){
            show_404();
        }
    }
}