<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_model extends CI_Model {
    
    private function getTotal($sql){
        $rs = $this->db->query($sql);
        return $rs->row()->total;
    }
    
    private function getCriteriaQuery(){
        $where_clause = " WHERE 1 = 1 ";
        $isbn = $this->input->post('isbn');
        $title = $this->input->post('title');
        if($isbn){
            $where_clause .= " AND isbn like '%$isbn%'";
        }
        if($title){
            $where_clause .= " AND title like '%$title%'";
        }
        
        return $where_clause;
    }
    
    public function getBook()
    {
        $result = ['total' => 0, 'rows' => []];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        if(!$page) $page = 1;
        if(!$rows) $rows = 10;
        $start = ($page - 1) * $rows;
        $query_total = "SELECT COUNT(*) AS total FROM t_book " . $this->getCriteriaQuery();
        $result['total'] = $this->getTotal($query_total);
        
        $query = "SELECT * FROM t_book " . $this->getCriteriaQuery() . " LIMIT $rows OFFSET $start";
        $books = $this->db->query($query)->result_array();
        $result['rows'] = $books;
        echo json_encode($result);
    }
    
    public function createBook()
    { 
        $data = [
            'isbn'	=> $this->input->post('isbn'),
            'title'	=> $this->input->post('title'),
            'author'	=> $this->input->post('author'),
            'publish_year'	=> $this->input->post('publish_year')
        ];
        
        $input = $this->db->insert('t_book', $data);
        
        if ($input) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    public function updateBook($id)
    {
        $data = [
            'isbn'	=> $this->input->post('isbn'),
            'title'	=> $this->input->post('title'),
            'author'	=> $this->input->post('author'),
            'publish_year'	=> $this->input->post('publish_year'),
        ];
        
        $this->db->set($data);
        $this->db->where('id', $id);
        $update = $this->db->update('t_book');
        
        if ($update) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    public function deleteBook($id) {
        $this->db->where('id', $id);
        $delete = $this->db->delete('t_book');
        
        if ($delete) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
}