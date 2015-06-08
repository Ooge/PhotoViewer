<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Our Logging class to handle logging
class M_logging extends CI_Model {
    public function add_log($type, $message, $relating_user, $relating_file) {
        $logData = array(
            'type' => xss_clean($type),
            'message' => xss_clean($message),
            'relating_user' => xss_clean($relating_user),
            'relating_data' => xss_clean($relating_file),
            'relating_ip' => $_SERVER['REMOTE_ADDR'],
            'time' => time()
        );

        $this->db->insert('og_logs', $logData);
    }
}
