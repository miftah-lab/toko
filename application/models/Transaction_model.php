<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {
    
    public function createHeader(){
        $transaction_code = $this->generateTransactionCode();
        $customer_name = $this->input->post('customer_name');
        $sql = "INSERT INTO tt_transaction_header (transaction_code, customer_name, transaction_date, grand_total, admin_id) 
                VALUES ('$transaction_code', '$customer_name', NOW(), '0', 'admin@miftah-lab.xyz')";
        $rs = $this->db->query($sql);
        
        if ($rs) {
            $get_id = $this->db->query("SELECT id FROM tt_transaction_header WHERE transaction_code = '$transaction_code'")->row();
            echo json_encode(['success' => true, 'transaction_code' => $transaction_code, 'transaction_id' => $get_id->id]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    private function generateTransactionCode(){
        $sql = "INSERT t_code (code_name, `month`, `year`, last_counter) VALUES ('TRANSACTION_CODE', DATE_FORMAT(NOW(), '%m'), DATE_FORMAT(now(), '%Y'), last_counter + 1)
                ON DUPLICATE KEY UPDATE last_counter = last_counter + 1 ";
        $rs = $this->db->query($sql);
        
        $sql = "SELECT CONCAT('TR-', `year`, '-' ,`month`, '-', LPAD(last_counter, 5, 0)) as transaction_code from t_code 
        WHERE code_name='TRANSACTION_CODE' and `month` = DATE_FORMAT(now(), '%m') AND `year` = DATE_FORMAT(NOW(), '%Y')";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        if($row){
            return $row->transaction_code;
        }
    }
    
    public function getBook(){
        $sql = "select a.id as id, concat(b.isbn, '-', b.title) as `text` from tt_price a 
                INNER JOIN t_book b ON a.book_id = b.id 
                WHERE now() between a.start_date and a.end_date";
        $rs = $this->db->query($sql);
        if($rs->result_array()){
            return $rs->result_array();
        }
    }
    
    public function createDetailTransaction(){
        $price_id = $this->input->post('price_id');
        $quantity = $this->input->post('quantity');
        $transaction_header_id = $this->input->post('ref_transaction_id');
        $priceData = $this->getPriceById($price_id);
        $price = $priceData->price;
        $total_price = intval($quantity) * intval($price);
        $sql = "INSERT INTO tt_transaction_detail (transaction_header_id, price_id, price, quantity, total_price, transaction_date) 
                VALUES ('$transaction_header_id', '$price_id', '$price', '$quantity', '$total_price', NOW())";
        $rs = $this->db->query($sql);
        
        $rs_update_header = $this->updateGrandTotal($transaction_header_id);
        
        if ($rs && $rs_update_header) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    public function updateDetailTransaction($id){
        $price_id = $this->input->post('price_id');
        $quantity = $this->input->post('quantity');
        $transaction_header_id = $this->input->post('ref_transaction_id');
        $priceData = $this->getPriceById($price_id);
        $price = $priceData->price;
        $total_price = intval($quantity) * intval($price);
        $sql = "UPDATE tt_transaction_detail SET price_id = '$price_id', price = '$price', quantity = '$quantity', total_price = '$total_price', transaction_date = now() WHERE id='$id'";
        $rs = $this->db->query($sql);
        
        $rs_update_header = $this->updateGrandTotal($transaction_header_id);
        
        if ($rs && $rs_update_header) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    private function updateGrandTotal($transaction_header_id){
        $sql = "SELECT SUM(total_price) AS grand_total FROM tt_transaction_detail WHERE transaction_header_id = '$transaction_header_id'";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        $grand_total = $row->grand_total;
        
        $sql = "UPDATE tt_transaction_header SET grand_total = '$grand_total' WHERE id = '$transaction_header_id'";
        $rs = $this->db->query($sql);
        return $rs;
    }
    
    private function getPriceById($price_id){
        $sql = "SELECT * FROM tt_price WHERE id = '$price_id'";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        return $row;
    }
    
    private function getTotal($sql){
        $rs = $this->db->query($sql);
        return $rs->row()->total;
    }
    
    private function getCriteriaQuery(){
        $where_clause = " ";
        $isbn = $this->input->post('isbn');
        $title = $this->input->post('title');
        if($isbn){
            $where_clause .= " AND c.isbn like '%$isbn%' ";
        }
        if($title){
            $where_clause .= " AND c.title like '%$title%' ";
        }
        
        return $where_clause;
    }
    
    public function getDetailTransaction()
    { 
        $result = ['total' => 0, 'rows' => []];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $transaction_code = $_POST['transaction_code'];
        if(!$page) $page = 1;
        if(!$rows) $rows = 10;
        $start = ($page - 1) * $rows;
        $query_total = "SELECT COUNT(*) AS total
        FROM tt_transaction_detail a 
        INNER JOIN tt_transaction_header h ON a.transaction_header_id = h.id
        INNER JOIN tt_price b ON a.price_id = b.id
        INNER JOIN t_book c ON b.book_id = c.id
        WHERE h.transaction_code = '$transaction_code' " . $this->getCriteriaQuery();
        $result['total'] = $this->getTotal($query_total);
        
        $query = "SELECT 
        	a.id, c.isbn, c.title, a.quantity, a.price, a.total_price, a.price_id
            FROM tt_transaction_detail a 
            INNER JOIN tt_transaction_header h ON a.transaction_header_id = h.id
            INNER JOIN tt_price b ON a.price_id = b.id
            INNER JOIN t_book c ON b.book_id = c.id
            WHERE h.transaction_code = '$transaction_code'  " . $this->getCriteriaQuery() . " LIMIT $rows OFFSET $start";
        $books = $this->db->query($query)->result_array();
        $result['rows'] = $books;
        echo json_encode($result);
    }
    
    public function deleteTransaction($id) {
        $sql = "DELETE FROM tt_transaction_detail WHERE id = '$id'";
        $delete = $this->db->query($sql);
        
        if ($delete) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    public function getTransactionSummary(){
        $transaction_code = $this->input->post('transaction_code');
        $sql = "SELECT * FROM tt_transaction_header WHERE transaction_code = '$transaction_code'";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        if($row){
            echo json_encode($row);
        }
    }
    
    public function getTransactionReceipt($transaction_code){
        $data = [];
        $sql = "SELECT * FROM tt_transaction_header WHERE transaction_code = '$transaction_code'";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        $data['header'] = $row;
        
        $sql = "SELECT 
            	c.isbn, c.title, a.quantity, a.price, a.total_price
            FROM tt_transaction_detail a 
            INNER JOIN tt_transaction_header h ON a.transaction_header_id = h.id
            inner join tt_price b ON a.price_id = b.id
            INNER JOIN t_book c ON b.book_id = c.id
            WHERE h.transaction_code = '$transaction_code'";
        $rs = $this->db->query($sql);
        $arr = $rs->result_array();
        $data['detail'] = $arr;
        
        $data['payment'] = ['payment'=>$this->input->get('payment'), 'change' => $this->input->get('change')];
        return $data;
    }
    
    public function finishTransaction(){
        $transaction_code = $this->input->post('transaction_code');
        $sql = "UPDATE tt_transaction_header SET status = 'close' WHERE transaction_code = '$transaction_code'";
        $rs = $this->db->query($sql);
        if ($rs) {
            echo json_encode(['success' => true]);
        }else {
            echo json_encode(['Msg'=>'Some Error occured!.']);
        }
    }
    
    /*
    
    
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
    */
}