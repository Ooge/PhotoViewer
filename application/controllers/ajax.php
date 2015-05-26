<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {


    function _exit($code, $reason = null, $add = array()) {
        header('Content-Type: application/json');
        header('X-PHP-Response-Code: ' . $code, true, $code);
        if ($reason) {
            header('X-VA-Error: ' . $reason);
        }
        $array = array('success' => ($code == 200));
        if ($code != 200) {
            $array['error_code'] = $code;
            $array['error_string'] = $reason;
        }
        exit(json_encode(array_merge($array, $add)));
    }
}