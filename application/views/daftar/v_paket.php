<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Daftar Paket</title>
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
			<div class="search_container">
                <div class="search_box">
                <input type="text" class="search_field" placeholder="Kode/Nama Paket" onfocus="this.placeholder=''" 
                onblur="this.placeholder='Kode/Nama Paket'" />
                <span class="search_icon"></span>
                </div>
            </div>

			<table class="tb_daftar">
				<caption></caption>
        		<colgroup>
					<col span="1" width="50px">
					<col span="1" width="<?php echo $lev === "0" ? "50px" : "100px" ?>">
					<col span="1" width="150px">
					<col span="1" width="250px">
					<col span="1" width="150px">
					<col span="1" width="100px">
					<col span="1" width="300px">
        		</colgroup>

        		<thead>
					<tr>
						<th>No.</th>
						<th></th>
						<th>Kode Paket</th>
						<th>Nama Paket</th>
						<th>Nilai Paket</th>
						<th>Periode</th>
						<th>Rekening</th>
					</tr>
        		</thead>

        		<tbody>
        		</tbody>
      		</table>

            <div class="pagination"></div>
		</div>
	</section>
</body>
<?php load_js("script", "nav", "dialog", "global", "daftar/paket"); ?>
</html>
