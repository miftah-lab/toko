<html>
	<head>
		<title>Toko Buku ABC</title>
		<style type="text/css">
		  .shop-name{
		      width: 100%;
		      font-size: 32;
		      text-align: center;
		  }
		  
		  .customer{
		      text-align: left;
		      font-size: 20;
		  }
		  
		  .right{
		      text-align: right;
		  } 
		  
		  .head{
		      font-weight: bold;
		  }
		  
		  @media print 
            {
               @page
               {
                size: 2.25in 5.5in;
                size: portrait;
              }
            }
		</style>
	</head>
	
	<body>
		<div class="shop-name">TOKO ABC</div>
		<hr>
		<div class="customer">
			<table width="100%" border="0">
				<tr>
					<td>Nama Pelanggan</td>
					<td>: <?php echo $header->customer_name;?></td>
				</tr>
				<tr>
					<td>Kode Transaksi</td>
					<td>: <?php echo $header->transaction_code;?></td>
				</tr>
			</table>
			<hr>
			
			<table width="100%" border="0">
				<tr>
					<td width="60%" class="head">Judul</td>
					<td width="10%" class="head">Jumlah</td>
					<td width="30%" class="head"><div class="right">Harga</div></td>
				</tr>
				<?php foreach($detail as $row): ?>
				<tr>
					<td><?php echo $row['title'];?></td>
					<td><?php echo $row['quantity'];?></td>
					<td><div class="right"><?php echo $row['total_price'];?></div></td>
				</tr>
				<?php endforeach;?>
			</table>
			
			<hr>
			<table width="100%" border="0">
				<tr>
					<td width="70%">Total</td>
					<td><div class="right"> <?php echo $header->grand_total;?></div></td>
				</tr>
				<tr>
					<td width="70%">Pembayaran</td>
					<td><div class="right"> <?php echo $payment['payment'];?></div></td>
				</tr>
				<tr>
					<td width="70%">Kembalian</td>
					<td><div class="right"> <?php echo $payment['change'];?></div></td>
				</tr>
			</table>
			
			
		</div>
	</body>
</html>