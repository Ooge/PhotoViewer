<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Stops direct access to this script.

// Create our AJAX handler class, extend codeigniter's controller class.
class Ajax extends CI_Controller {

    public function file_handler($type) {
        // If a type is not set, exit with an error.
        if(!$type){
            $this->_exit(400, 'Action not specified');
        }

        // Only users who are logged in can upload. Also get the user
        if (!($user = $this->m_session->get_current_user())) {
            $this->_exit(401, 'You are not authorized to access this resource');
        }

        // Switch through the different types of AJAX calls, file_handler accepts
        switch($type){
            case 'upload':
                // Get the file extension through exploding the file name by . and
                // getting the last element.
                $fileExtension = end(explode('.',$_FILES['userfile']['name']));
                $imageGID = generate_random(7);
                $newFileName = $imageGID . '.' .  strtolower($fileExtension);

                // Here we setup our upload configuration
                $config['upload_path'] = FCPATH . 'uploads';    // Where we want to upload
                $config['allowed_types'] = 'gif|jpg|png';       // Filetypes we allow
                $config['max_size'] = 10000;                    // Allow up to 10MB files
                // Using a helper function, generate a random name for the file
                $config['file_name'] =  $newFileName;

                // Load the upload class, parsing in the config values set above.
                $this->load->library('upload', $config);
                // Do our upload, encapsulate it in an if statement to see if it
                // was successful or not.
                if(!$this->upload->do_upload('userfile')) {
                    // Error occurred while uploading
                    $this->_exit(400,'Error uploading File', array('error' => $this->upload->display_errors()));
                } else {
                    // Was successful. Add upload to MySQL table and exit the user, parsing image info.
                    $imgInfo = $this->upload->data();  // Get image data
                    // Create an array of data to be inserted into the MySQL table.
                    $insertData = array(
                        'user_id' => $user->id,
                        'gid' => $imageGID,
                        'title' => $_POST['image-title'],
                        'description' => $_POST['image-desc'],
                        'file' => '/uploads/' . $newFileName,
                        'time' => time()
                    );
                    // Insert the array into the uploads MySQL table.
                    $this->db->insert('uploads', $insertData);
                    // Exit.
                    $this->_exit(200, null, $imgInfo);
                }
                break;
        }
    }

    public function stats($type) {
        // If a type is not set, exit with an error.
        if(!$type){
            $this->_exit(400, 'Action not specified');
        }

        // Only users who are logged in can view stats. Also get the user
        if (!($user = $this->m_session->get_current_user())) {
            $this->_exit(401, 'You are not authorized to access this resource');
        }

        // Switch through the different types of AJAX calls, file_handler accepts
        switch($type){
            case 'browser_data':
                $sql = 'SELECT browser, COUNT(*) AS count FROM og_user_agents GROUP BY browser';
                $query = $this->db->query($sql);
                if($query->num_rows() > 0){
                    $results = $query->result_array();
                    $this->_exit(200, null, array('data' => $results, 'total' => $query->num_rows()));
                }
                break;
        }
    }


    // Our custom exit() function
    function _exit($code, $reason = null, $add = array()) {
        // Set our headers correctly to the datatype we are sending
        header('Content-Type: application/json');
        // Send our response code
        header('X-PHP-Response-Code: ' . $code, true, $code);
        // If we have a reason, parse an error reason
        if ($reason) {
            header('X-OG-Error: ' . $reason);
        }
        // Set success to true of false, depending on if the code == 200
        $array = array('success' => ($code == 200));
        // if its not 200 then set the code and reason in our error report
        if ($code != 200) {
            $array['error_code'] = $code;
            $array['error_string'] = $reason;
        }
        // Exit with the data set.
        exit(json_encode(array_merge($array, $add)));
    }
}
