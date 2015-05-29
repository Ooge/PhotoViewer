<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Session extends CI_Model {

    private $token_cookie = 'ry_token';
    private $id_cookie = 'ry_id';
    private $_https = false;
    private $cookie_expiry = 6307200;

    private $user = null;

    public function __construct() {
        parent::__construct();
        $this->_https = is_https();
        $this->validate();
    }

    public function validate() {
        if ((!isset($_COOKIE[$this->token_cookie]) || !isset($_COOKIE[$this->id_cookie]))) {
            return;
        }

        $id = $_COOKIE[$this->id_cookie];
        $token = $_COOKIE[$this->token_cookie];
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->db->get_where('sessions', array('MD5(user_id)' => $id, 'session_token' => $token), 1);
        if ($query->num_rows() == 0) {
            $this->logout();
            return;
        }

        /* todo: bans here
        $this->db->select('active');
        $this->db->where('(MD5(user_id) = ' . $this->db->escape($id) . ' OR ip_address = ' . $this->db->escape($ip) . ') AND active = 1');
        $ban_query = $this->db->get('bans', 1);
        if ($ban_query->num_rows() > 0) {
            $this->logout();
            return;
        } */
        $this->user = User::get_by_md5($id);
    }

    public function login($id, $remember_me = true) {
        // Remove any old session stuff
        $this->logout();

        $this->load->helper('utilities');

        $new_token = generate_random(64);
        setcookie($this->token_cookie, $new_token, time() + ($remember_me ? $this->cookie_expiry : 10800), '/');
        setcookie($this->id_cookie, md5($id), time() + ($remember_me ? $this->cookie_expiry : 10800), '/');
        $_COOKIE[$this->token_cookie] = $new_token;
        $_COOKIE[$this->id_cookie] = md5($id);

        $last_ipv4 = $_SERVER['REMOTE_ADDR'];
        // update or insert into db
        $sql = 'INSERT INTO `og_sessions` (`user_id`, `session_token`, `last_ipv4`) VALUES (' . $this->db->escape($id) . ', ' . $this->db->escape($new_token) . ', ' . $this->db->escape($last_ipv4) . ') ON DUPLICATE KEY UPDATE `session_token`=VALUES(`session_token`), `last_ipv4`=VALUES(`last_ipv4`);';
        $this->db->query($sql);

        if($this->agent->is_browser()) {
            // User is visiting on a browser
            $agentData = array(
                'user_id'  => $id,
                'browser'  => $this->agent->browser(),
                'version'  => $this->agent->version(),
                'mobile'   => 0,
                'robot'    => 0,
                'platform' => $this->agent->platform()
            );
        } elseif($this->agent->is_robot()){
            // User is a robot (or spider)
            $agentData = array(
                'user_id'  => $id,
                'browser'  => $this->agent->robot(),
                'version'  => 0,
                'mobile'   => 0,
                'robot'    => 1,
                'platform' => $this->agent->platform()
            );
        } elseif($this->agent->is_mobile()){
            // User is on a mobile device
            $agentData = array(
                'user_id'  => $id,
                'browser'  => $this->agent->mobile(),
                'version'  => 0,
                'mobile'   => 1,
                'robot'    => 0,
                'platform' => $this->agent->platform()
            );
        } else {
            // Undefined User Agent
            $agentData = array(
                'user_id'  => $id,
                'browser'  => 'Undefined',
                'version'  => 0,
                'mobile'   => 0,
                'robot'    => 0,
                'platform' => $this->agent->platform()
            );
        }

        $this->db->insert('user_agents', $agentData);

        $this->user = new User($id);
    }

    public function logout() {
        setcookie($this->token_cookie, '', -1, '/');
        setcookie($this->id_cookie, '', -1, '/');
        $_COOKIE[$this->token_cookie] = null;
        $_COOKIE[$this->id_cookie] = null;
        $this->user = null;
    }

    public function get_user_ipv4($user) {
        if (is_object($user)) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        $this->db->where('user_id', $user_id);
        $this->db->select('last_ipv4');
        $query = $this->db->get('sessions', 1);
        if ($query->num_rows() == 0) {
            return false;
        }
        $result = $query->result_array();
        $row = $result[0];
        return $row['last_ipv4'];
    }

    public function get_current_user() {
        return ($this->user ? $this->user : false);
    }
}
