<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function index() {
        if(($user = $this->m_session->get_current_user())) {
            // Show logged in page TODO: make seperate pages, for now just loading home.
            $data['main_content'] = 'home';
            $this->load->view('template', $data);
        } else {
            // Show logged out page
            $data['main_content'] = 'home';
            $this->load->view('template', $data);
        }
	}

	public function login() {
		if(($user = $this->m_session->get_current_user())) {
            // TODO: Redirect to home rather than showing an error.
            redirect(base_url('/'));
			show_error('You appear to be logged in already, ' . $user->username . '.');
			return;
		}
		$data['main_content'] = 'login/login';
		$this->load->view('template', $data);
	}

	public function login_user() {
		if(($user = $this->m_session->get_current_user())) {
            // TODO: Again redirect rather than error out.
            redirect(base_url('/'));
			show_error('You appear to be logged in already, ' . $user->username . '.');
			return;
		}

		if(!$this->input->post('username') || !$this->input->post('password')) {
            // TODO: Use actual validation rather than showing an error.

			show_error('Error: Username or Password not entered!');
			return;
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|max_length[64]');
	    $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|max_length[255]');

	    if($this->form_validation->run()) {
	    	$username = @$this->input->post('username');
	    	$password = @$this->input->post('password');
	    	$remember_me = @$this->input->post('remember_me');
	    	$remember_me = ($remember_me == '1');

	    	$this->db->select('id, username, password');
	    	$this->db->where('username', $username);
	    	$query = $this->db->get('users');

	    	if($query->num_rows() == 0){
	    		redirect(base_url('login?code=1'));
	            return;
	    	}

	    	$result = $query->result_array();
	    	$row = $result[0];

	    	if(password_verify($password, $row['password'])) {
	    		$id = $row['id'];

	    		// todo: Check bans here

	    		$this->m_session->login($id, $remember_me);
	    		redirect(base_url('/'));
	    	} else {
		      redirect(base_url('login?code=1'));
		      return;
	    	}
    	}
	}

    public function logout() {
        $this->m_session->logout();
        redirect(base_url());
    }

	public function register() {
		if(($user = $this->m_session->get_current_user())) {
            redirect(base_url('/'));
			show_error('You Silly! You already have an account, ' . $user->username . '.');
			return;
		}
		$data['main_content'] = 'login/register';
		$this->load->view('template', $data);
	}

    public function register_user() {
        if (($user = $this->m_session->get_current_user())) {
            redirect(base_url('/'));
            show_error('You Silly! You already have an account, ' . $user->username . '.');
            return;
        }

        // We use $_POST instead of codeigniters input->post() function because isset does not like function returns.
        if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password_repeat']) || !isset($_POST['email'])) {
            show_error('Error. Direct Access Forbidden.', 403);
            return;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean|max_length[64]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean|max_length[255]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|max_length[64]|is_unique[users.username]|alpha_dash');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|max_length[255]|is_unique[users.email]|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|max_length[255]|min_length[8]');
        $this->form_validation->set_rules('password_repeat', 'Password Confirmation', 'required|trim|xss_clean|max_length[255]|matches[password]');
        $this->form_validation->set_rules('day', 'Date of Birth', 'required|trim|xss_clean|callback_absdyb_is_drop_error');
        $this->form_validation->set_rules('month', 'Date of Birth', 'required|trim|xss_clean|callback_absdyb_is_drop_error');
        $this->form_validation->set_rules('year', 'Date of Birth', 'required|trim|xss_clean|callback_b4d229de_is_legal_age');

        $this->form_validation->set_message('is_unique', 'The %s you have used has already been taken.');

        if($this->form_validation->run() != false) {
            // collect form data and hash pass
            // todo: check ip ban here

            $dob = $this->input->post('year') . "-" . $this->input->post('month') . "-" . ($this->input->post('day') + 1);
            $form_data = array(
                'username' => @$this->input->post('username'),
                'firstname' => @$this->input->post('first_name'),
                'lastname' => @$this->input->post('last_name'),
                'email' => @$this->input->post('email'),
                'password' => ry_password_hash(@$this->input->post('password')),
                'dateofbirth' => $dob,
                'registration_date' => time()
                );

            $this->db->insert('users', $form_data);
            $id = $this->db->insert_id();

            $this->m_session->login($id);
            $user = $this->m_session->get_current_user();

            $user->set_rank('user');

            header('Refresh:5;url=' . base_url());
            $data['main_content'] = 'login/register_success';
            $this->load->view('template', $data);
        } else {
            $data['main_content'] = 'login/register';
            $this->load->view('template', $data);
        }
    }

    public function b4d229de_is_legal_age($year) {
        if (!@$this->input->post('day') || !@$this->input->post('month')) {
            return false;
        }
        $dob = ($this->input->post('day') + 1) . "-" . $this->input->post('month') . "-" . $year;
        if(under_thirteen($dob)){
            $this->form_validation->set_message(__FUNCTION__, 'You must be 13 or older to create an account.');
            return false;
        } else {
            return true;
        }
    }

    public function absdyb_is_drop_error($drop) {
        if($drop == 'err') {
            $this->form_validation->set_message(__FUNCTION__, 'Please select a day or month in the dropdown.');
            return false;
        } else {
            return true;
        }
    }

    public function forgot() {
        if (($user = $this->m_session->get_current_user())) {
            redirect(base_url('/'));
            show_error('How could you forget? You are already logged in , ' . $user->username . '.');
            return;
        }

        $data['main_content'] = 'login/forgot';
        $this->load->view('template', $data);
    }

    public function forgot_password() {
        $email = @$this->input->post('email');
        $email = $this->security->xss_clean($email);

        if(!$email){
            show_404(uri_string());
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            show_error('You entered an invalid email. Go back and try again.', 400);
            return;
        }


        $this->db->select('id');
        $this->db->where('email', $email);
        $query = $this->db->get('users', 1);

        if($query->num_rows() > 0) {
            $this->load->library('email');
            $row = $query->row_array();
            $id = $row['id'];
            $code = generate_random(16);

            $this->db->insert('user_forgot_password', array('user_id' => $id, 'code' => $code, 'timestamp' => time()));

            $this->email->from('noreply@ryanthorn.uk', 'Ooge No-reply');
            $this->email->to($email);
            $this->email->subject('Ooge | Forgot Password');
            $this->email->message('Someone (hopefully you), has requested for a password change through our forgotten password system.</br></br>To reset your password, visit the link below:</br><a href="http://i.ooge.uk/recover_password/' . $code .'">http://i.ooge.uk/recover_password/' . $code .'</a></br></br>Never share this link with anyone, unless you want to get hacked...</br></br>If you did not request this then don\'t worry, it will expire after 10 minutes.');
            $this->email->send();

            $data['main_content'] = 'login/forgot';
            $data['message'] = '<span class="green">If the email you supplied is correct, you will receive an email with a link that expires in 10 minutes.</span>';
            $this->load->view('template', $data);
        }
    }

    public function recover_password() {
        $code = $this->uri->segment(2);
        if(!$code){
            show_error('nope');
            return;
        }

        $this->db->where('code', $code);
        $query = $this->db->get('user_forgot_password', 1);

        if($query->num_rows() > 0){
            $row = $query->row_array();
            if(time() - $row['timestamp'] > 10 * 60) {
                // 10 minutes has passed, don't let them use this code.
                show_error('This code has expired, please request a new code from http://ooge.uk/forgot');
                $this->db->where('code', $code);
                $this->db->limit(1);
                $this->db->delete('user_forgot_password');
                return;
            }
            // 10 minutes has not passed, let them use it.

            $data['main_content'] = 'login/set_pass';
            $data['code'] = $code;
            $this->load->view('template', $data);

        } else {
            show_error('This code was not found in the database!');
        }
    }

    public function set_new_password() {
        $code = @$this->input->post('code');
        $code = $this->security->xss_clean($code);

        $this->db->where('code', $code);
        $query = $this->db->get('user_forgot_password', 1);

        if($query->num_rows() == 0) {
            show_error('Error');
            return;
        }
        $row = $query->row_array();
        $id = $row['user_id'];

        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|max_length[255]|min_length[8]');
        $this->form_validation->set_rules('password_repeat', 'Password Confirmation', 'required|trim|xss_clean|max_length[255]|matches[password]');

        if($this->form_validation->run() != false) {

            $new_password = ry_password_hash(@$this->input->post('password'));

            $this->db->where('id', $id);
            $this->db->update('users', array('password' => $new_password));
            $this->db->delete('user_forgot_password', array('user_id' => $id));

            header('Refresh:5;url=' . base_url('login'));
            $data['main_content'] = 'login/forgot_success';
            $this->load->view('template', $data);
        } else {
            $data['main_content'] = 'login/set_pass';
            $data['code'] = $code;
            $this->load->view('template', $data);
        }
    }

    public function fourohfour_override() {
        $username = $this->uri->segment(1);
        $username = strtolower($username);
        if($username){
            $this->db->where('username', $username);
            $query = $this->db->get('users', 1);

            if($query->num_rows() > 0){
                $profile_user = User::get_by_name($username);

                if($profile_user) {
                    $self = false;
                    if(($logged_in_user = $this->m_session->get_current_user())){
                        if($logged_in_user->id == $profile_user->id)
                            $self = true;
                        else
                            $self = false;
                    }

                    $page_data = array(
                        'main_content' => 'profile/view',
                        'profile_user' => $profile_user,
                        'is_self'      => $self
                        );
                    $this->load->view('template', $page_data);
                }
            } else {
                show_404(uri_string());
                return;
            }
        } else {
            show_404(uri_string());
            return;
        }
    }
}
