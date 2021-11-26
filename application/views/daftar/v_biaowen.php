<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Daftar Biaowen</title>
	<?php load_css("base", "style", "dialog"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view('templates/header'); ?>
    <?php $this->load->view('templates/aside'); ?>

	<section id="scol1" class="main">
	    <div class="tb_container">
			<aside class="search_container3">
				<div class="accordion"><span class="nav_icon1"></span></div>
				<div class="search_box2">
					<div class="name_box">
						<label for="nama_paket">Nama Donatur / Paket</label>
						<input type="text" nama="nama_paket" id="nama_paket" />
					</div>

					<div class="name_box">
						<label for="biaowen">Biaowen</label>
						<input type="text" name="biaowen" id="biaowen" />
					</div>

					<div class="select_box">
						<label for="lunas">Lunas</label>
						<select name="lunas" id="lunas">
							<option value="">Semua</option>
							<option value="1">Sudah</option>
							<option value="0">Belum</option>
						</select>
					</div>

					<div class="select_box">
						<label for="bakar">Bakar</label>
						<select name="bakar" id="bakar">
							<option value="">Semua</option>
							<option value="1">Sudah</option>
							<option value="0">Belum</option>
						</select>
					</div>

					<span class="search_icon search_icon2" id="search_tampil_mutasi_stok"></span>
				</div>
			</aside>

			<div class="button_secondary">
				<button id="save_btn" class="i_btn btn_secondary">Bakar Biaowen</button>
			</div>

			<table class="tb_daftar">
				<caption></caption>
        		<colgroup>
					<col span="1" width="50px">
					<col span="1" width="150px">
					<col span="1" width="150px">
					<col span="1" width="150px">
					<col span="1" width="50px">
					<col span="1" width="50px">
					<col span="1" width="100px">
					<col span="1" width="150px">
					<col span="1" width="50px">
        		</colgroup>

        		<thead>
					<tr>
						<th>No.</th>
						<th>Nama Paket</th>
						<th>Nama Donatur</th>
						<th>Biaowen</th>
						<th>Lunas</th>
						<th>Bakar</th>
						<th>Tanggal Bakar</th>
						<th>Total Donasi</th>
						<th></th>
					</tr>
        		</thead>

        		<tbody>
        		</tbody>
      		</table>

            <div class="pagination"></div>
		</div>
	</section>
</body>
<?php load_js("script", "nav", "dialog", "global", "daftar/biaowen"); ?>
</html>
