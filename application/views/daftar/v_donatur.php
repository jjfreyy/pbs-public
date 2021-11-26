<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Daftar Donatur</title>
	<?php load_css("base", "style", "dialog"); ?>
</head>
<body>
	<?php $lev = $this->session->lev; ?>
	<input type="hidden" id="key" value="<?php echo $lev; ?>">
    <div class="background"></div>
    <?php $this->load->view('templates/header'); ?>
    <?php $this->load->view('templates/aside'); ?>

	<section id="scol1" class="main">
	    <div class="tb_container">
			<aside class="search_container3">
				<div class="accordion"><span class="nav_icon1"></span></div>
				<div class="search_box2">
					<div class="name_box">
						<label for="donatur">Donatur</label>
						<input type="text" id="donatur" name="donatur" />
					</div>
					
					<div class="name_box">
						<label for="tgl_terakhir">Setoran Terakhir (<=)</label>
						<input type="date" name="tgl_terakhir" id="tgl_terakhir" />
					</div>

					<span class="search_icon search_icon2"></span>
				</div>
			</aside>

			<table class="tb_daftar">
				<caption></caption>
        		<colgroup>
					<col span="1" width="50px">
					<col span="1" width="<?php echo $lev === "0" ? "50px" : "100px" ?>">
					<col span="1" width="150px">
					<col span="1" width="250px">
					<col span="1" width="250px">
					<col span="1" width="250px">
					<col span="1" width="150px">
					<col span="1" width="100px">
					<col span="1" width="150px">
					<col span="1" width="100px">
					<col span="1" width="100px">
					<col span="1" width="150px">
					<col span="1" width="250px">
					<col span="1" width="100px">
					<col span="1" width="100px">
					<col span="1" width="200px">
					<col span="1" width="100">
					<col span="1" width="100">
        		</colgroup>

        		<thead>
					<tr>
						<th>No.</th>
						<th></th>
						<th>Kode Donatur</th>
						<th>Nama Id.</th>
						<th>Nama Mandarin</th>
						<th>Alamat</th>
						<th>Kota Lahir</th>
						<th>Tanggal Lahir</th>
						<th>Kota Domisili</th>
						<th>No. Hp1</th>
						<th>No. Hp2</th>
						<th>E-mail</th>
						<th>Keterangan</th>
						<th>Tanggal Gabung</th>
						<th>Jumlah Paket</th>
						<th>Total Nilai Paket</th>
						<th>Jumlah Biaowen</th>
						<th>Pembayaran Terakhir</th>
					</tr>
        		</thead>

        		<tbody>
        		</tbody>
      		</table>

            <div class="pagination"></div>
		</div>
	</section>
</body>
<?php load_js("script", "nav", "dialog", "global", "daftar/donatur"); ?>
</html>
