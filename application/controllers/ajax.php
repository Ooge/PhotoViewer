<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function file_handler($type) {
        if(!$type){
            $this->_exit(400, 'Action not specified');
        }

        if (!($user = $this->m_session->get_current_user())) {
            $this->_exit(401, 'You are not authorized to access this resource');
        }

        switch($type){
            case 'upload':
                $config['upload_path'] = '../../uploads';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 10000;

                $this->load->library('upload', $config);

                if(!$this->upload->do_upload('userfile')) {
                    // Error occurred while uploading
                    $this->_exit(400,'Error uploading File', array('error' => $this->upload->display_errors()));
                } else {
                    $this->_exit(200, null, $this->upload->data());
                }
                break;
        }
    }

    function _exit($code, $reason = null, $add = array()) {
        header('Content-Type: application/json');
        header('X-PHP-Response-Code: ' . $code, true, $code);
        if ($reason) {
            header('X-OG-Error: ' . $reason);
        }
        $array = array('success' => ($code == 200));
        if ($code != 200) {
            $array['error_code'] = $code;
            $array['error_string'] = $reason;
        }
        exit(json_encode(array_merge($array, $add)));
    }
}
