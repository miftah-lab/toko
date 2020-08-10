<!DOCTYPE html>
<html>
   <head>
   <title>Admin</title>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/jeasyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/jeasyui/themes/icon.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- EasyUI -->
    <link rel="stylesheet" href="<?= base_url('assets/jeasyui/themes/bootstrap/easyui.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/jeasyui/themes/icon.css'); ?>">
        <script type="text/javascript" src="<?php echo base_url()?>assets/jeasyui/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>assets/jeasyui/jquery.easyui.min.js"></script>
        
        <script type="text/javascript">
        	const BASE_URL = "<?php echo base_url("transaction")?>";
        	var transaction_code = '';
        </script>
   </head>
   
   <body>
      <?php echo $menu;?>
    
    
    <div class="container mt-4">
	<div id="detail">
      <!-- Datagrid -->
      <table id="dg" class="easyui-datagrid" style="height:500px" pagination="true"></table>

      <!-- Toolbar -->
      <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newTransaction()">Tambah</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editTransaction()">Edit</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyTransaction()">Hapus</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="finishTransaction()">Selesai</a>
        
      </div>
	</div>
      <!-- Dialog -->
      <div id="dlg" class="easyui-dialog">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
          <div style="margin-bottom:10px">
              <input name="price_id" id="isbn" class="easyui-combobox" required="true" label="ISBN" style="width:100%">
          </div>
          <div style="margin-bottom:10px">
              <input name="quantity" class="easyui-textbox" required="true" label="Jumlah" style="width:100%">
          </div>
          <input type="hidden" name="ref_transaction_id" id="ref_transaction_id" >
        </form>
      </div>

      <!-- Dialog Button -->
      <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveTransactionDetail()" style="width:90px">Simpan</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Batal</a>
      </div>
      
        <!-- Dialog -->
      <div id="dlgHeader" class="easyui-dialog">
        <form id="fmHeader" method="post" novalidate style="margin:0;padding:20px 50px">
          <div style="margin-bottom:10px">
              <input name="customer_name" id="customer_name" class="easyui-textbox" required="true" label="Pembeli" style="width:100%">
          </div>
          <input type="hidden" id="transaction_code">
          <input type="hidden" id="transaction_id">
        </form>
      </div>

      <!-- Dialog Button -->
      <div id="dlgHeader-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="createHeader()" style="width:90px">OK</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgHeader').dialog('close')" style="width:90px">Batal</a>
      </div>
      
      
        <!-- Dialog -->
      <div id="dlgFinish" class="easyui-dialog">
        <form id="fmFinish" method="post" novalidate style="margin:0;padding:20px 50px">
          <div style="margin-bottom:10px">
              <input name="grand_total" id="grand_total" class="easyui-textbox" required="true" label="Total" style="width:100%">
          </div>
          <div style="margin-bottom:10px">
              <input name="payment" id="payment" class="easyui-textbox" label="Pembayaran" style="width:100%">
          </div>
          <div style="margin-bottom:10px">
              <input name="change" id="change" class="easyui-textbox" label="Kembalian" style="width:100%">
          </div>
          <input type="hidden" id="finish_transaction_header_id">
        </form>
      </div>

      <!-- Dialog Button -->
      <div id="dlgFinish-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="finish()" style="width:90px">OK</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgFinish').dialog('close')" style="width:90px">Batal</a>
      </div>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- EasyUI -->
    <script src="<?= base_url('assets/jeasyui/easyloader.js'); ?>"></script>
    <script src="<?= base_url('assets/jeasyui/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/jeasyui/jquery.easyui.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/transaction.js'); ?>"></script>
   </body>
</html>