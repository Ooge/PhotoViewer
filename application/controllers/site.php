<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Our main site controller, this controller is loaded by default when someone visits the page
class Site extends CI_Controller {
	// The index page of our website
	public function index() {
        // Get latest images
		$this->load->model('m_image');
		// Load the home page.
        $data['main_content'] = 'home';
		// Get our latest images
		$data['latestImages'] = $this->m_image->get_latest_images(20);
		// Send and load the data
        $this->load->view('template', $data);
	}
	// Our login page
	public function login() {
		// Check if they are already logged in
		if(($user = $this->m_session->get_current_user())) {
            // TODO: Redirect to home rather than showing an error.
            redirect(base_url('/'));
			show_error('You appear to be logged in already, ' . $user->username . '.');
			return;
		}
		// if they are not logged in, load the login page
		$data['main_content'] = 'login/login';
		$this->load->view('template', $data);
	}
	// The login function that is ran when the login form is submitted
	public function login_user() {
		// Check they are not already logged in
		if(($user = $this->m_session->get_current_user())) {
            // TODO: Again redirect rather than error out.
            redirect(base_url('/'));
			show_error('You appear to be logged in already, ' . $user->username . '.');
			return;
		}
		// Check if they have entered a username and password
		// This should never run because of the form_validation we are doing below here
		if(!$this->input->post('username') || !$this->input->post('password')) {
            // TODO: Use actual validation rather than showing an error.

			show_error('Error: Username or Password not entered!');
			return;
		}
		// Load CodeIgniter's form validation library
		$this->load->library('form_validation');
		// Set the validation rules for our form
		// For example we make the username required, we trim spaces off the end, we
		// protect against cross site scripting and we set the max length of the username to 64
		$this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|max_length[64]');
	    $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|max_length[255]');
		// Run the validation, if this returns true, validation past
	    if($this->form_validation->run()) {
			// Get the username, password and the remember me from the form's post data
	    	$username = @$this->input->post('username');
	    	$password = @$this->input->post('password');
	    	$remember_me = @$this->input->post('remember_me');
	    	$remember_me = ($remember_me == '1');

			// Select the id, username and password from the users table where username
			// is equal to what they put in.
	    	$this->db->select('id, username, password');
	    	$this->db->where('username', $username);
	    	$query = $this->db->get('users');

			// If we found no users, redirect to the login page with an error
	    	if($query->num_rows() == 0){
	    		redirect(base_url('login?code=1'));
	            return;
	    	}
			// Get the results from the search
	    	$result = $query->result_array();
	    	$row = $result[0];

			// Verify the BCRYPT password found in the table
	    	if(password_verify($password, $row['password'])) {
	    		$id = $row['id']; // Set the ID found to the $id variable
	    		// TODO: Check bans here

	    		$this->m_session->login($id, $remember_me); // Begin a new session using our m_session model
	    		redirect(base_url('/')); // Redirect to home.
	    	} else {
		    	redirect(base_url('login?code=1')); // The passwords did not match
				// Redirect and return
		    	return;
	    	}
    	}
	}
	// Our Logout function
    public function logout() {
		// We call the member function of our sessions model to logout the user
        $this->m_session->logout();
		// Redirect to the base url (home)
        redirect(base_url());
    }
	// Our register page
	public function register() {
		// Check if the user is already logged in
		if(($user = $this->m_session->get_current_user())) {
            redirect(base_url('/'));
			show_error('You Silly! You already have an account, ' . $user->username . '.');
			return;
		}
		// They are not logged in, let them register by showing the register page.
		$data['main_content'] = 'login/register';
		$this->load->view('template', $data);
	}
	// The function that is ran when the register form is submitted
    public function register_user() {
		// Check if they are already logged in
        if (($user = $this->m_session->get_current_user())) {
            redirect(base_url('/'));
            show_error('You Silly! You already have an account, ' . $user->username . '.');
            return;
        }

        // We use $_POST instead of codeigniters input->post() function because isset does not like function returns.
		// Here we check that they have inputted everything, again this shouldnt run as the form validation below does it for us
        if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password_repeat']) || !isset($_POST['email'])) {
            show_error('Error. Direct Access Forbidden.', 403);
            return;
        }
		// Load our form validation library from codeigniter
        $this->load->library('form_validation');
		// Setting up our rules for validation for all the fields
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean|max_length[64]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean|max_length[255]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|max_length[64]|is_unique[users.username]|alpha_dash');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|max_length[255]|is_unique[users.email]|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|max_length[255]|min_length[8]');
        $this->form_validation->set_rules('password_repeat', 'Password Confirmation', 'required|trim|xss_clean|max_length[255]|matches[password]');
		// We use a custom callback method called callback_absdyb_is_drop_error() that I have written to check if they have not selected anything
        $this->form_validation->set_rules('day', 'Date of Birth', 'required|trim|xss_clean|callback_absdyb_is_drop_error');
        $this->form_validation->set_rules('month', 'Date of Birth', 'required|trim|xss_clean|callback_absdyb_is_drop_error');
		// Again we use a custom callback method that I have written to check if they are over the legal age
        $this->form_validation->set_rules('year', 'Date of Birth', 'required|trim|xss_clean|callback_b4d229de_is_legal_age');
		// Set a custom message to let the users know if their username has already been taken.
        $this->form_validation->set_message('is_unique', 'The username %s is already in use.');
		// Run the form validation
        if($this->form_validation->run() != false) {
            // If the validation passes, run this
            // TODO: check ip ban here
			// Format our date of birth to be YYYY-MM-DD
            $dob = $this->input->post('year') . "-" . $this->input->post('month') . "-" . $this->input->post('day');
			// We get all the form data and put it into an array which we will use to insert into the users table.
            $form_data = array(
                'username' => @$this->input->post('username'),
                'firstname' => @$this->input->post('first_name'),
                'lastname' => @$this->input->post('last_name'),
                'email' => @$this->input->post('email'),
                'password' => ry_password_hash(@$this->input->post('password')), // Our custom password hashing function that uses BCRYPT
                'dateofbirth' => $dob,
                'registration_date' => time()
                );
			// Insert our new user into the users table with the above data.
            $this->db->insert('users', $form_data);
			// Get the ID of the user that was just inserted
            $id = $this->db->insert_id();
			// Login the new user
            $this->m_session->login($id);
			// Get the user object
            $user = $this->m_session->get_current_user();
			// Set the users rank to user
            $user->set_rank('user');
			// Redirect the user after 5 seconds to the home page.
            header('Refresh:5;url=' . base_url());
			// Load the success page
            $data['main_content'] = 'login/register_success';
            $this->load->view('template', $data);
        } else {
			// Reload the register page with the validation errors
            $data['main_content'] = 'login/register';
            $this->load->view('template', $data);
        }
    }
	// Our legal age callback function
    public function b4d229de_is_legal_age($year) {
		// Check that the day and month has been set
        if (!@$this->input->post('day') || !@$this->input->post('month')) {
            return false;
        }
		// format our date of birth
        $dob = ($this->input->post('day')) . "-" . $this->input->post('month') . "-" . $year;
		// Run our under_thirteen() function that we have coded in our utilities_helper.php
        if(under_thirteen($dob)){
			// If it returns true, the user is under 13 and cannot sign up.
            $this->form_validation->set_message('b4d229de_is_legal_age', 'You must be 13 or older to create an account.');
            return false;
        } else {
            return true;
        }
    }
	// The callback function for dropdown errors
    public function absdyb_is_drop_error($drop) {
		// If the dropdown value is equal to err, they have not selected anything.
        if($drop == 'err') {
			// Provide an error.
            $this->form_validation->set_message('absdyb_is_drop_error', 'Please select a day or month in the dropdown.');
            return false;
        } else {
            return true;
        }
    }
	// The forgot password page
    public function forgot() {
		// Check they are not already logged in
        if (($user = $this->m_session->get_current_user())) {
            redirect(base_url('/'));
            show_error('How could you forget? You are already logged in , ' . $user->username . '.');
            return;
        }
		// Load our forgot password page
        $data['main_content'] = 'login/forgot';
        $this->load->view('template', $data);
    }
	// Our forgot password function that is ran when the forgot password form is submitted
    public function forgot_password() {
		// Get and XSS clean the email
        $email = @$this->input->post('email');
        $email = $this->security->xss_clean($email);
		// If no email is set, 404
        if(!$email){
            show_404(uri_string());
            return;
        }
		// Validate our email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            show_error('You entered an invalid email. Go back and try again.', 400);
            return;
        }

		// Select ID where the email is equal to what was open
        $this->db->select('id');
        $this->db->where('email', $email);
        $query = $this->db->get('users', 1);
		// If there is a row found
        if($query->num_rows() > 0) {
			// Load the email sending library
			// This functionality doesnt actually work right now as I have not configured my email server to work
			// However this code does actually work.
            $this->load->library('email');
            $row = $query->row_array();
            $id = $row['id'];
            $code = generate_random(16);
			// Add our forgot password key and entry to the forgot password table.
            $this->db->insert('user_forgot_password', array('user_id' => $id, 'code' => $code, 'timestamp' => time()));

			// Setup and send the email msg with instructions to reset their password.
            $this->email->from('noreply@ryanthorn.uk', 'Ooge No-reply');
            $this->email->to($email);
            $this->email->subject('Ooge | Forgot Password');
            $this->email->message('Someone (hopefully you), has requested for a password change through our forgotten password system.</br></br>To reset your password, visit the link below:</br><a href="http://i.ooge.uk/recover_password/' . $code .'">http://i.ooge.uk/recover_password/' . $code .'</a></br></br>Never share this link with anyone, unless you want to get hacked...</br></br>If you did not request this then don\'t worry, it will expire after 10 minutes.');
            $this->email->send();
			// Load the forgot password page with a success message
            $data['main_content'] = 'login/forgot';
            $data['message'] = '<span class="green">If the email you supplied is correct, you will receive an email with a link that expires in 10 minutes.</span>';
            $this->load->view('template', $data);
        }
    }
	// This is the recover password page which is accompanied by the code generated
    public function recover_password() {
		// Get the code from the URL
        $code = $this->uri->segment(2);
		// If there is no code, show an error
        if(!$code){
            show_error('No forgot code supplied');
            return;
        }
		// Find the code in the table
        $this->db->where('code', $code);
        $query = $this->db->get('user_forgot_password', 1);
		// If we found a code
        if($query->num_rows() > 0){
			// Get the results
            $row = $query->row_array();
			// check the key hasnt expired
            if(time() - $row['timestamp'] > 10 * 60) {
                // 10 minutes has passed, don't let them use this code.
                show_error('This code has expired, please request a new code from http://i.ooge.uk/forgot');
                $this->db->where('code', $code);
                $this->db->limit(1);
                $this->db->delete('user_forgot_password');
                return;
            }
            // 10 minutes has not passed, let them use it.
			// Load the set pass page
            $data['main_content'] = 'login/set_pass';
            $data['code'] = $code;
            $this->load->view('template', $data);

        } else {
			// Error saying the code wasnt found
            show_error('This code was not found in the database!');
        }
    }
	// Our set new password page
    public function set_new_password() {
		// Get the validated code from the forgot password page
        $code = @$this->input->post('code');
        $code = $this->security->xss_clean($code);

        $this->db->where('code', $code);
        $query = $this->db->get('user_forgot_password', 1);
		// Check the code exists
        if($query->num_rows() == 0) {
            show_error('Error: code does not exist');
            return;
        }
		// If it exists let them set a new password
        $row = $query->row_array();
        $id = $row['user_id'];
		// Load the form validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|max_length[255]|min_length[8]');
        $this->form_validation->set_rules('password_repeat', 'Password Confirmation', 'required|trim|xss_clean|max_length[255]|matches[password]');
		// Validate the passwords
        if($this->form_validation->run() != false) {
			// If validation is successful, set the new password
            $new_password = ry_password_hash(@$this->input->post('password'));
			// Add new password to the users table, delete the forgot password code from the table
            $this->db->where('id', $id);
            $this->db->update('users', array('password' => $new_password));
            $this->db->delete('user_forgot_password', array('user_id' => $id));
			// Refresh and redirect after 5 seconds has passed.
            header('Refresh:5;url=' . base_url('login'));
            $data['main_content'] = 'login/forgot_success';
            $this->load->view('template', $data);
        } else {
			// If validation fails, reload the page with the errors
            $data['main_content'] = 'login/set_pass';
            $data['code'] = $code;
            $this->load->view('template', $data);
        }
    }
	// Our 404 override function, this function is ran whenever a 404 occurs on our webserver
    public function fourohfour_override() {
		// We get the 1st segment of the URI
        $imageGID = $this->uri->segment(1);
		// If the image GID is set from the URI
        if($imageGID){
			// Check the image GID exists in the uploads table
            $this->db->where('gid', $imageGID);
            $query = $this->db->get('uploads', 1);
			// If we get a result
            if($query->num_rows() > 0){
				// Load the image object from the GID
                $imageObj = Image::get_by_gid($imageGID);
				// If we have an image object
                if($imageObj) {
					// Set self as false by default (meaning the user does not own this image)
                    $self = false;
					// If the logged in user's ID is the same as the uploaded image's author's ID
					// Then its their image and we set self to true
                    if(($logged_in_user = $this->m_session->get_current_user())){
                        if($logged_in_user->id == $imageObj->user_id)
						// It is their image
                            $self = true;
                        else
						// If is not their image
                            $self = false;
                    }
					// The page data to load into the view image page
                    $page_data = array(
                        'main_content' => 'view_image', // View image page
                        'image_data' => $imageObj, // Image data in the form of an object
                        'is_self'      => $self // True if the user owns the image, false if they dont.
                        );
					// Load the page
                    $this->load->view('template', $page_data);
                }
            } else {
				// If the GID does not exist, it is probably just a 404 and not
				// looking for an image, show 404 page
                show_404(uri_string());
                return;
            }
        } else {
			// Again if no image GID is set and the 404 has been triggered
			// treat it as a 404.
            show_404(uri_string());
            return;
        }
    }
}
