<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('is_logged_in')){
		    redirect('welcome');
		}
	}
	
	public function index()
	{
		$data['menu'] = $this->load->view('nav', NULL, TRUE);
		$this->load->view('admin/index', $data);
	}
	
	public function price()
	{
	    $data['menu'] = $this->load->view('nav', NULL, TRUE);
	    $this->load->view('admin/price', $data);
	}
	
	public function transaction()
	{
	    $data['menu'] = $this->load->view('nav', NULL, TRUE);
	    $this->load->view('admin/transaction', $data);
	}
}
