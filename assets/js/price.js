// Datagrid
$('#dg').datagrid({
  url: BASE_URL + '/getPrice',
  title: 'Data Harga',
  fitColumns: true,
  rownumbers: true,
  singleSelect: true,
  toolbar: '#toolbar',
  columns: [[
    {field:	'isbn', title: 'ISBN', width:100},
	{field: 'title', title: 'Judul Buku', width: 200},
    {field:	'price', title:	'Harga', width:150}
  ]]
});

// Dialog
$('#dlg').dialog({
  title: 'Tambah Harga',
  width: 400,
  border:'thin',
  buttons:'#dlg-buttons',
  closed: true,
  cache: false,
  modal: true
});

$('#isbn').combobox({
    url: BASE_URL + '/getBook',
    valueField:'id',
    textField:'text'
});


var url;

function newPrice() {
  $('#dlg').dialog('open').dialog('center').dialog('setTitle','Tambah Harga');
  $('#fm').form('clear');
  url = BASE_URL + '/createPrice';
}

function editPrice() {
	var row = $('#dg').datagrid('getSelected');
	if (row) {
		$('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Edit Price');
		$('#fm').form('load', row);
		$('#isbn').combobox('setValue', row.book_id);
		url = BASE_URL + '/updatePrice/' + row.id;
	}
}

function savePrice(){
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
				$('#dlg').dialog('close');		// close the dialog
				setTimeout(function(){
					$('#dg').datagrid('reload');	
				}, 1000);
					// reload the data
			}
		}
	});
}

function destroyPrice(){
  var row = $('#dg').datagrid('getSelected');
  if (row) {
    $.messager.confirm('Konfirmasi', 'Yakin ingin menghapus data ini?', function(r) {
      if (r) {
        $.post(BASE_URL + '/deletePrice', {id:row.id}, function(result) {
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