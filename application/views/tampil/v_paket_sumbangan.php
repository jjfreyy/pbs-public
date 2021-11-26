<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Tampil Paket</title>
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
					<div class="radio_date_box">
						<div class="checkbox_reverse">
							<input type="radio" name="date_search_method" id="tanggal_check" checked="checked" />
							<label for="">Tanggal</label>
						</div>
						<div class="input_container_box">
							<input type="date" name="tanggal1" id="tanggal1" value="<?php echo date("Y-m-d"); ?>" required />
							<input type="date" name="tanggal2" id="tanggal2" value="<?php echo date("Y-m-d"); ?>" required />
						</div>
					</div>

					<div class="radio_select_box">
						<div class="checkbox_reverse">
							<input type="radio" name="date_search_method" id="bulan_check" />
							<label for="">Bulan</label>
						</div>
						<div class="select_box">
							<select name="bulan" id="bulan" disabled>
								<?php
								foreach ($cbo_bulan->result() as $row) {
								$thn = $row->thn;
								$bln = $row->bln;
								$nm_bln = $row->nm_bln;
								echo "<option value='$thn-$bln'>$nm_bln $thn</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="name_box">
						<label for="filter">Pencarian</label>
						<input type="text" name="filter" id="filter" />
					</div>
					<div class="select_box">
					<label for="lunas">Lunas</label>
						<select name="lunas" id="lunas">
							<option value="0">Semua</option>
							<option value="1">Sudah</option>
							<option value="2">Belum</option>
						</select>
					</div>

					<span class="search_icon search_icon2"></span>
				</div>
			</aside>

			<table class="tb_daftar">
				<caption></caption>
        		<colgroup>
					<col span="1" width="50px">
					<col span="1" width="<?php echo $lev === "0" ? "100px" : "150px" ?>">
					<col span="1" width="150px">
					<col span="1" width="200px">
					<col span="1" width="150px">
					<col span="1" width="200px">
					<col span="1" width="150px">
					<col span="1" width="200px">
					<col span="1" width="150px">
					<col span="1" width="50px">
					<col span="1" width="150px">
					<col span="1" width="150px">
					<col span="1" width="250px">
					<col span="1" width="150px">
					<col span="1" width="250px">
        		</colgroup>

        		<thead>
					<tr>
						<th>No.</th>
						<th></th>
						<th>Kode Donatur</th>
						<th>Nama Donatur</th>
						<th>Kode Kolektor</th>
						<th>Nama Kolektor</th>
						<th>Kode Paket</th>
						<th>Nama Paket</th>
						<th>Nilai Paket</th>
						<th>Jumlah Paket</th>
						<th>Total Donasi</th>
						<th>Sisa</th>
						<th>Keterangan</th>
						<th>Jatuh Tempo</th>
						<th>Biaowen</th>
					</tr>
        		</thead>

        		<tbody></tbody>
      		</table>

            <div class="pagination"></div>
		</div>
	</section>
</body>
<?php load_js("script", "nav", "dialog", "global", "tampil/paket_sumbangan"); ?>
</html>
