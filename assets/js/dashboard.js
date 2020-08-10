// Datagrid
$('#dg').datagrid({
  url:'book/getBook',
	title: 'Data Buku',
  fitColumns: true,
  rownumbers: true,
  singleSelect: true,
  toolbar: '#toolbar',
  columns: [[
    {field:'isbn', title:'ISBN', width:100},
    {field:'title', title:'Judul', width:150},
    {field:'author', title:'Pengarang', width:150},
    {field:'publish_year', title:'Tahun Terbit', width:150}
  ]]
});

// Dialog
$('#dlg').dialog({
  title: 'Tambah Buku',
  width: 400,
  border:'thin',
  buttons:'#dlg-buttons',
  closed: true,
  cache: false,
  modal: true
});


var url;

function newBook() {
  $('#dlg').dialog('open').dialog('center').dialog('setTitle','Tambah Buku');
  $('#fm').form('clear');
  url = 'book/createBook';
}

function editBook() {
	var row = $('#dg').datagrid('getSelected');
	if (row) {
		$('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Edit Book');
		$('#fm').form('load', row);
		url = 'book/updateBook/' + row.id;
	}
}

function saveBook(){
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
				$('#dg').datagrid('reload');	// reload the data
			}
		}
	});
}

function destroyBook(){
  var row = $('#dg').datagrid('getSelected');
  if (row) {
    $.messager.confirm('Konfirmasi', 'Yakin ingin menghapus data ini?', function(r) {
      if (r) {
        $.post('book/deleteBook', {id:row.id}, function(result) {
          if (result.success) {
            $('#dg').datagrid('reload');    // reload the data
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