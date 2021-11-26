<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Laporan Bulanan Detail</title>
	<?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view('templates/header'); ?>
    <?php $this->load->view('templates/aside'); ?>

	<section id="scol1" class="main">
        <aside class="search_container3">
            <div class="search_box2">
                <div class="radio_date_box">
                    <label for="">Bulan</label>
                    <div class="input_container_box">
                        <div class="select_box">
                            <select name="bln1" id="bln1">
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
                        <div class="select_box">
                            <select name="bln2" id="bln2">
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
                </div>

                <div class="select_box">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran">
                        <option value="">Semua</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>

                <div class="name_box">
                    <label for="filter">Pencarian</label>
                    <input type="text" name="filter" id="filter" />
                </div>

                <span class="search_icon search_icon2"></span>
            </div>
        </aside>
	</section>
</body>
<?php load_js("script", "nav", "laporan/paket/bulanan_detail"); ?>
</html>
