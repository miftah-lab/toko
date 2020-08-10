<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Price extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('is_logged_in')){
		    redirect('welcome');
		}
		
		$this->load->model("Price_model", "price");
	}
	
	public function getBook()
	{
		$data = $this->price->getBook();
		if($data){
		    echo json_encode($data);
		}
	}
	
	public function getPrice(){
	    $data = $this->price->getPrice();
	}
	
	public function createPrice(){
	    $resut = $this->price->createPrice();
	}
	
	public function updatePrice($id){
	    $this->price->updatePrice($id);
	}
	
	public function deletePrice(){
	    $id = intval($this->input->post('id'));
	    $this->price->deletePrice($id);
	}
}
