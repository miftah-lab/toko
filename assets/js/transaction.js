// Datagrid
$('#dg').datagrid({
  url: BASE_URL + '/getDetailTransaction',
  title: 'Transaksi',
  fitColumns: true,
  rownumbers: true,
  singleSelect: true,
  toolbar: '#toolbar',
  columns: [[
    {field:	'isbn', title: 'ISBN', width:100},
	{field: 'title', title: 'Judul Buku', width: 200},
    {field:	'price', title:	'Harga', width:150},
	{field: 'quantity', title: 'Jumlah', width: 42},
	{field: 'total_price', title: 'Harga', width: 80}
  ]]
});

// Dialog
$('#dlg').dialog({
  title: 'Tambah Item',
  width: 400,
  border:'thin',
  buttons:'#dlg-buttons',
  closed: true,
  cache: false,
  modal: true
});

$('#dlgFinish').dialog({
  title: 'Menyelesaikan Transaksi',
  width: 400,
  border:'thin',
  buttons:'#dlgFinish-buttons',
  closed: true,
  cache: false,
  modal: true
});

$('#dlgHeader').dialog({
  title: 'Transaksi Baru',
  width: 400,
  border:'thin',
  buttons:'#dlgHeader-buttons',
  closed: false,
  closeAble: false,
  cache: false,
  modal: true
});

$('#isbn').combobox({
    url: BASE_URL + '/getBook',
    valueField:'id',
    textField:'text'
});


var url;

function createHeader(){
	url = BASE_URL + '/createHeader';
	
	$('#fmHeader').form('submit', {
		url: url,
		onSubmit: function() {
			return $(this).form('validate');
		},
		success: function(result) {
			var result = eval('('+result+')');
			if (result.errorMsg) {
				$.messager.show({
					title: 'Error',
					msg: result.errorMsg
				});
			} else {
				$('#dlgHeader').dialog('close');		// close the dialog
				$('#detail').show();
				setTimeout(function(){
					console.log(result);
					$('#transaction_code').val(result.transaction_code);
					$('#transaction_id').val(result.transaction_id);
					$('#dg').datagrid('reload', {
						transaction_code: $('#transaction_code').val(),
						transaction_id: $('#transaction_id').val()
					});	
				}, 1000);
			}
		}
	});
	
}

function newTransaction() {
  $('#dlg').dialog('open').dialog('center').dialog('setTitle','Tambah Item Belanja');
  $('#fm').form('clear');
  url = BASE_URL + '/createDetailTransaction';
}

function finishTransaction(){
  $('#dlgFinish').dialog('open').dialog('center').dialog('setTitle','Menyelesaikan Transaksi');
  $('#fmFinish').form('clear');
  url = BASE_URL + '/finishTransaction';
  $.ajax({
	type: 'post',
	url: BASE_URL + '/getTransactionSummary',
	data: {
		transaction_code: $('#transaction_code').val()
	},
	success: function(response){
		let tranHeader = JSON.parse(response);
		//console.log(tranHeader);
		$('#grand_total').textbox('setValue', tranHeader.grand_total);
		$('#payment').textbox({
			onChange: function(){
				let payment = $('#payment').textbox('getValue');
				let total = $('#grand_total').textbox('getValue');
				let change = payment - total;
				if(change < 0) 
					return;
				else 
					$('#change').textbox('setValue', change);
			}
		});
	}
  });
}

function finish(){
	$.ajax({
		type: 'post',
		url: BASE_URL + '/finishTransaction',
		data: {
			transaction_code: $('#transaction_code').val()
		},
		success: function(response){
			data = {
				transaction_code: $('#transaction_code').val(),
				payment: $('#payment').textbox('getValue'),
				change: $('#change').textbox('getValue')
			};
			printReceipt(data);
		}
	});
}

function printReceipt(data){
	let str = BASE_URL + "/printReceipt?transaction_code="+data.transaction_code+"&payment="+data.payment+"&change="+data.change;
	window.open(str);
}

function editTransaction() {
	var row = $('#dg').datagrid('getSelected');
	if (row) {
		$('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Edit Item');
		$('#fm').form('load', row);
		$('#isbn').combobox('setValue', row.price_id);
		url = BASE_URL + '/updateTransaction/' + row.id;
	}
}

function saveTransactionDetail(){
	$('#ref_transaction_id').val( $('#transaction_id').val() );
	$('#fm').form('submit', {
		url: url,
		onSubmit: function() {
			return $(this).form('validate');
		},
		success: function(result) {
			var result = eval('('+result+')');
			if (result.errorMsg) {
				$.messager.show({
					title: 'Error',
					msg: result.errorMsg
				});
			} else {
				$('#dlg').dialog('close');		
				setTimeout(function(){
					$('#dg').datagrid('reload', {
						transaction_code: $('#transaction_code').val()
					});	
				}, 1000);
					// reload the data
			}
		}
	});
}

function destroyTransaction(){
  var row = $('#dg').datagrid('getSelected');
  if (row) {
    $.messager.confirm('Konfirmasi', 'Yakin ingin menghapus data ini?', function(r) {
      if (r) {
        $.post(BASE_URL + '/deleteTransaction', {id:row.id}, function(result) {
          if (result.success) {
			setTimeout(function(){
				$('#dg').datagrid('reload');    // reload the data	
			}, 1000);
            
          } else {
            $.messager.show({    // show error message
              title: 'Error',
              msg: result.errorMsg
            });
          }
        }, 'json');
      }
    });
  }
}

function doSearch(){
	$('#dg').datagrid('load', {
		isbn: $('#q_isbn').val(),
		title: $('#q_title').val()
	});
}

