<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Our Sessions model class
class M_Session extends CI_Model {
    // This file handles all sessions in the website
    private $token_cookie = 'ry_token'; // Name of the cookie storing the session token
    private $id_cookie = 'ry_id';   // Name of the token storing the Id
    private $_https = false;    // Whether we are using HTTPS or not
    private $cookie_expiry = 6307200;   // Expiration date for the cookie

    private $user = null; // Our user variable
    // Constructor that validates our session and checks whether we are running HTTPS or not
    public function __construct() {
        parent::__construct();
        $this->_https = is_https();
        $this->validate();
    }

    // This function validates a session
    public function validate() {
        // Check if the cookies are set for a session
        if ((!isset($_COOKIE[$this->token_cookie]) || !isset($_COOKIE[$this->id_cookie]))) {
            return;
        }
        // Extracts the cookie data
        $id = $_COOKIE[$this->id_cookie];
        $token = $_COOKIE[$this->token_cookie];
        // Get the users IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        // get the session for this user ID and token
        $query = $this->db->get_where('sessions', array('MD5(user_id)' => $id, 'session_token' => $token), 1);
        // If it doesn't exist, log the user out immediately
        if ($query->num_rows() == 0) {
            $this->logout();
            return;
        }

        //TODO: bans here
        // Get the user by their MD5 ID
        $this->user = User::get_by_md5($id);
    }
    // Login function that logs in a user to the website
    public function login($id, $remember_me = true) {
        // Remove any old sessions by logging them out
        $this->logout();
        // Load our utilities helper
        $this->load->helper('utilities');
        // Generate a random 64 length session key
        $new_token = generate_random(64);
        // Set the cookies for the session key and the user ID thats logging in
        setcookie($this->token_cookie, $new_token, time() + ($remember_me ? $this->cookie_expiry : 10800), '/');
        setcookie($this->id_cookie, md5($id), time() + ($remember_me ? $this->cookie_expiry : 10800), '/');
        $_COOKIE[$this->token_cookie] = $new_token;
        $_COOKIE[$this->id_cookie] = md5($id);

        // Get the IP address of the user logging in
        $last_ipv4 = $_SERVER['REMOTE_ADDR'];
        // Update or insert into the database depending if it exists or not
        $sql = 'INSERT INTO `og_sessions` (`user_id`, `session_token`, `last_ipv4`) VALUES (' . $this->db->escape($id) . ', ' . $this->db->escape($new_token) . ', ' . $this->db->escape($last_ipv4) . ') ON DUPLICATE KEY UPDATE `session_token`=VALUES(`session_token`), `last_ipv4`=VALUES(`last_ipv4`);';
        $this->db->query($sql);

        // Managing user agents to see what browser the user is using
        if($this->agent->is_browser()) {
            // User is visiting on a browser
            // Get the data from their Agent, including browser name and version
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
            // They are a robot so most of this data we set to false
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
            // This sometimes is not detected for certain mobile devices
            // This is due to how some mobile user agents are laid out
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
            // we couldnt find data from their user agent so we act as if its undefined in the database.
            $agentData = array(
                'user_id'  => $id,
                'browser'  => 'Undefined',
                'version'  => 0,
                'mobile'   => 0,
                'robot'    => 0,
                'platform' => $this->agent->platform()
            );
        }
        // Insert the users browser data into the user_agents table
        $this->db->insert('user_agents', $agentData);
        // Create the user object from the ID that is logging in
        $this->user = new User($id);
        // Set their last IPv4 address in the user row relating to them
        $this->user->last_ipv4 = $last_ipv4;
    }

    // Log out a user
    public function logout() {
        // Remove the cookie data, therefore logging them out.
        setcookie($this->token_cookie, '', -1, '/');
        setcookie($this->id_cookie, '', -1, '/');
        $_COOKIE[$this->token_cookie] = null;
        $_COOKIE[$this->id_cookie] = null;
        $this->user = null;
    }

    // Get a users last IPv4 address
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
    // Return the current logged in user
    public function get_current_user() {
        return ($this->user ? $this->user : false);
    }
}
