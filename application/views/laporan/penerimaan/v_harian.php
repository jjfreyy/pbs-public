<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Laporan Harian</title>
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

                <div class="select_box">
                    <label for="bank">Bank</label>
                    <select name="bank" id="bank">
                        <option value="">Semua</option>
                        <option value="0">Tunai</option>
                        <?php
                            foreach ($cbo_bank->result() as $bank) {
                                $id_bank = $bank->id_bank;
                                $detail_bank = $bank->detail_bank;
                                echo "<option value='$id_bank'>$detail_bank</option>";
                            }
                        ?>
                    </select>
                </div>

                <span class="search_icon search_icon2"></span>
            </div>
        </aside>
	</section>
</body>
<?php load_js("script", "nav", "laporan/penerimaan/harian"); ?>
</html>
