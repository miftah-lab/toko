<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Authentication_model', 'auth');
	}
	
	public function index()
	{
		print_r($this->session);
		echo "";
	}

	public function authentication(){
		$data = [
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password'))
		];

		$user = $this->auth->getUser($data);
		if($user){
			$data['is_logged_in'] = true;
			$this->session->set_userdata($data);
			$this->output->set_status_header(200);
			echo json_encode(['message' => 'Logged In', 'email' => $user->email, 'isLoggedIn' => true]);
		} else {
			$this->output->set_status_header(403);
			echo json_encode(['message' => 'Failed', 'email' => '', 'isLoggedIn' => false]);
		}
	}

	public function unauthentication(){
		$data['is_logged_in'] = false;
		$this->session->set_userdata($data);
	}
}
