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
                // Using a helper function, generate a random name for the file
                $imageGID = generate_random(7);
                $newFileName = $imageGID . '.' .  strtolower($fileExtension);

                // Here we setup our upload configuration
                $config['upload_path'] = FCPATH . 'uploads';    // Where we want to upload
                $config['allowed_types'] = 'gif|jpg|png';       // Filetypes we allow
                $config['max_size'] = 10000;                    // Allow up to 10MB files
                // Setting the filename to the generated file name
                $config['file_name'] =  $newFileName;

                // Load the upload class, parsing in the config values set above.
                $this->load->library('upload', $config);
                // Do our upload, encapsulate it in an if statement to see if it
                // was successful or not.
                if(!$this->upload->do_upload('userfile')) {
                    // Error occurred while uploading sent error to log database and exit with error
                    // Load our logging model
                    $this->load->model('m_logging');
                    // Send the log message to the DB
                    $this->m_logging->add_log('UPLOADERR', $this->upload->display_errors(), $user->id, $newFileName);
                    // Exit
                    $this->_exit(400,'Error uploading File', array('error' => $this->upload->display_errors()));
                } else {
                    // Was successful. Add upload to MySQL table and exit the user, parsing image info.
                    // We are also going to use the Imagick API to create a smaller, square thumbnail of the image
                    $imagick = new Imagick(realpath(FCPATH . 'uploads/' . $newFileName));
                    // Resize the image so bigger images are cropped correctly
                    $imagick->resizeImage(300, 0, imagick::FILTER_CATROM, 0.6, false);
                    // Crop the image so that it is 219x219px
                    $imagick->cropImage(219, 219, 0, 0);
                    // Write the newly cropped image to the thumbs directory
                    $imagick->writeImage(FCPATH . 'thumbs/' . $newFileName);

                    $imgInfo = $this->upload->data();  // Get image data
                    // Create an array of data to be inserted into the MySQL table.
                    $insertData = array(
                        'user_id' => $user->id,
                        'gid' => $imageGID,
                        'title' => $_POST['image-title'],
                        'description' => $_POST['image-desc'],
                        'file_location' => '/uploads/' . $newFileName,
                        'thumb_location' => '/thumbs/' . $newFileName,
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
            // Our case to get browser data
            case 'browser_data':
                // Our SQL query to get the count of all browsers used to log into the website
                $sql = 'SELECT browser, COUNT(*) AS count FROM og_user_agents GROUP BY browser';
                // Run the query
                $query = $this->db->query($sql);
                // If we have a row found
                if($query->num_rows() > 0){
                    // Select the ID from user agents
                    $this->db->select('id');
                    $number = $this->db->get('user_agents');
                    // Get the results
                    $results = $query->result_array();
                    // Create a data array to hold our results
                    $data = array();
                    foreach($results as $res) {
                        // Looping through the results and working out the percentage
                        $data[] = array($res['browser'], round((($res['count'] / $number->num_rows()) * 100 ), 2));
                    }
                    // Exiting with the percentage data.
                    $this->_exit(200, null, array('results' => $data, 'total' => $number->num_rows()));
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
