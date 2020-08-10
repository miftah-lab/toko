<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Price_model extends CI_Model {
    
    public function getBook(){
        $sql = "SELECT id, CONCAT(isbn, '-', title) as text FROM t_book";
        $rs = $this->db->query($sql);
        if($rs->result_array()){
            return $rs->result_array();
        }
    }
    
    private function getTotal($sql){
        $rs = $this->db->query($sql);
        return $rs->row()->total;
    }
    
    private function getCriteriaQuery(){
        $where_clause = " WHERE NOW() BETWEEN a.start_date AND a.end_date ";
        $isbn = $this->input->post('isbn');
        $title = $this->input->post('title');
        if($isbn){
            $where_clause .= " AND b.isbn like '%$isbn%'";
        }
        if($title){
            $where_clause .= " AND b.title like '%$title%'";
        }
        
        return $where_clause;
    }
    
    public function getPrice()
    {
        $result = ['total' => 0, 'rows' => []];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        if(!$page) $page = 1;
        if(!$rows) $rows = 10;
        $start = ($page - 1) * $rows;
        $query_total = "SELECT COUNT(*) AS total FROM tt_price a INNER JOIN t_book b ON a.book_id = b.id " . $this->getCriteriaQuery();
        $result['total'] = $this->getTotal($query_total);
        
        $query = "SELECT a.id, a.book_id, a.price, b.isbn, b.title FROM tt_price a INNER JOIN t_book b ON a.book_id = b.id " . $this->getCriteriaQuery() . " LIMIT $rows OFFSET $start";
        $books = $this->db->query($query)->result_array();
        $result['rows'] = $books;
        echo json_encode($result);
    }
    
    public function checkExistingPrice($bookId){
        $sql = "SELECT count(*) as ctr FROM tt_price WHERE book_id = '$bookId' AND NOW() BETWEEN start_date and end_date";
        $row = $this->db->query($sql)->row();
        return $row->ctr;
    }
    
    public function createPrice()
    { 
        
        $isbn	= $this->input->post('isbn');
        $price	= $this->input->post('price');
        
        $exist = $this->checkExistingPrice($isbn);
        if($exist){
            echo json_encode(['Msg'=>'Harga untuk buku ini sudah ada!']);
            return;
        }
        
        $sql = "INSERT INTO tt_price (book_id, price, start_date, end_date) " .
                " VALUES ('$isbn', '$price', NOW(), '2038-01-19 03:14:07')";
        
        $input = $this->db->query($sql);
        
        if ($input) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    public function updatePrice($id)
    {

        $isbn	= $this->input->post('isbn');
        $price	= $this->input->post('price');
        
        $sql_update = "UPDATE tt_price SET end_date = now() WHERE id='$id'";
        $sql_insert = "INSERT INTO tt_price (book_id, price, start_date, end_date) ".
                        " VALUES ('$isbn', '$price', NOW(), '2038-01-19 03:14:07') ";
        $update = $this->db->query($sql_update);
        $insert = $this->db->query($sql_insert);
        
        if ($update && $insert) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    public function deletePrice($id) {
        $sql = "UPDATE tt_price SET end_date = now() WHERE id = '$id'";
        $delete = $this->db->query($sql);
        
        if ($delete) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
}