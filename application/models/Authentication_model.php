<?php
class Authentication_model extends CI_Model {
	public $email;
	public $password;


	function getUser($data){
		$this->db->where('email', $data['email']);
		$this->db->where('password', $data['password']);
		$query = $this->db->get('users');
		return $query->row();
	}

}