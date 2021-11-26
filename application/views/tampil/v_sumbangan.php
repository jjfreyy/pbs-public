<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Tampil Sumbangan</title>
	<?php load_css("base", "dialog", "style"); ?>
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
							<input type="date" name="tanggal1" id="tanggal1" value="<?php echo date("Y-m-d"); ?>" />
							<input type="date" name="tanggal2" id="tanggal2" value="<?php echo date("Y-m-d"); ?>" />
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

					<span class="search_icon search_icon2"></span>
				</div>
			</aside>

			<table class="tb_daftar tb_main">
				<caption></caption>
        		<colgroup>
					<col span="1" width="50px">
					<col span="1" width="<?php echo $lev === "0" ? "100px" : "150px" ?>">
					<col span="1" width="150px">
					<col span="1" width="250px">
					<col span="1" width="150px">
					<col span="1" width="200px">
					<col span="1" width="150px">
					<col span="1" width="50px">
					<col span="1" width="250px">
        		</colgroup>

        		<thead>
					<tr>
						<th>No.</th>
						<th></th>
						<th>No. Kwitansi</th>
						<th>Nama Donatur</th>
						<th>Tanggal Donasi</th>
						<th>Nama Paket</th>
						<th>Jumlah Donasi</th>
						<th>Via</th>
						<th>Keterangan</th>
					</tr>
        		</thead>

        		<tbody>
        		</tbody>
      		</table>

            <div class="pagination"></div>
		</div>
	</section>

	<div class="dialog_background" style="display:none">
		<div class="dialog" style="display:none">
			<div class="dialog_header">
				<span class="dialog_close_btn" title="Tutup Dialog"></span>
			</div>
			<div class="dialog_body">
				<table class="td_daftar tb_dialog">
					<colgroup>
						<col span="1" width="50px">
						<col span="1" width="250px">
						<col span="1" width="50px">
					</colgroup>

					<thead>
						<tr>
							<th>No.</th>
							<th>Barang</th>
							<th>Qty</th>
						</tr>
					</thead>

					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
	
</body>
<?php load_js("script", "nav", "dialog", "global", "tampil/sumbangan"); ?>
</html>
