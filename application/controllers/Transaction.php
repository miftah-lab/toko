<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('is_logged_in')){
		    redirect('welcome');
		}
		
		$this->load->model("Transaction_model", "tran");
	}
	
	public function createHeader(){
	    $this->tran->createHeader();
	}
	
	public function getDetailTransaction($id = NULL){
	    $transaction_code = $this->input->post('transaction_code');
	    if(! $transaction_code){ 
	        echo json_encode(['total' => 0, 'rows'=>[]] );
	    } else {
	        $this->tran->getDetailTransaction($transaction_code);
	    }
	}
	
	public function getBook(){
	    $data = $this->tran->getBook();
	    if($data){
	        echo json_encode($data);
	    }
	}
	
	public function createDetailTransaction(){
	    $this->tran->createDetailTransaction();
	}
	
	public function updateTransaction($id){
	    $this->tran->updateDetailTransaction($id);
	}
	
	public function deleteTransaction(){
	    $id = $this->input->post('id');
	    $this->tran->deleteTransaction($id);
	}
	
	public function getTransactionSummary(){
	    $this->tran->getTransactionSummary();
	}
	
	public function finishTransaction(){
	    $this->tran->finishTransaction();
	}
	
	public function printReceipt(){
	    $transaction_code = $this->input->get('transaction_code');
	    $payment = $this->input->get('payment');
	    $change = $this->input->get('change');
	    $transaction = $this->tran->getTransactionReceipt($transaction_code);
	    $this->load->view('admin/receipt', $transaction);
	}
}
