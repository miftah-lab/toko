<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('is_logged_in')){
		    redirect('welcome');
		}
		
		$this->load->model("Book_model", "book");
	}
	
	public function getBook()
	{
		$this->book->getBook();
	}
	
	public function createBook(){
	    $this->book->createBook();
	}
	
	public function updateBook($id){
	    $this->book->updateBook($id);
	}
	
	public function deleteBook(){
	    $id = intval($this->input->post('id'));
	    $this->book->deleteBook($id);
	}
}
