<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Souvenir Keluar</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("input/souvenir_keluar/save_souvenir_keluar", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>
            <input type="hidden" name="id" id="id" value="<?php echo $this->session->flashdata("id"); ?>" />
            <div class="name_box">
                <input type="hidden" name="id_paket_sumbangan" id="id_paket_sumbangan" value="<?php echo $this->session->flashdata("id_paket_sumbangan");?>" />
                <label for="nama_paket">Nama Paket*</label>
                <input type="text" name="nama_paket" id="nama_paket" autocomplete="off" list="paket_list" autofocus required
                value="<?php echo $this->session->flashdata('nama_paket'); ?>" />
                <datalist id="paket_list"></datalist>
            </div>

            <div class="name_box">
                <label for="total_donasi">Total Donasi</label>
                <input type="text" name="total_donasi" id="total_donasi" readonly value="<?php echo $this->session->flashdata('total_donasi'); ?>" />
            </div>

            <div class="name_box">
                <label for="penerima_souvenir">Penerima Souvenir*</label>
                <input type="text" name="penerima_souvenir" id="penerima_souvenir" value="<?php echo $this->session->flashdata('penerima_souvenir'); ?>" />
            </div>

            <div class="name_box">
                <input type="hidden" name="id_souvenir" id="id_souvenir" value="<?php echo $this->session->flashdata("id_souvenir"); ?>" />
                <label for="kode_souvenir">Kode Souvenir*</label>
                <input type="text" name="kode_souvenir" id="kode_souvenir" autocomplete="off" list="souvenir_list" autofocus required
                value="<?php echo $this->session->flashdata('kode_souvenir'); ?>" />
                <datalist id="souvenir_list"></datalist>
            </div>
            
            <div class="name_box">
                <label for="nama_souvenir">Nama Souvenir</label>
                <input type="text" name="nama_souvenir" id="nama_souvenir" readonly value="<?php echo $this->session->flashdata('nama_souvenir'); ?>" />
            </div>

            <div class="name_box">
                <label for="stok_tersedia_souvenir">Stok Tersedia</label>
                <input type="text" name="stok_tersedia_souvenir" id="stok_tersedia_souvenir" readonly value="<?php echo $this->session->flashdata('stok_tersedia_souvenir'); ?>" />
            </div>

            <div class="name_box">
                <label for="stok_keluar_souvenir">Qty*</label>
                <input type="text" name="stok_keluar_souvenir" id="stok_keluar_souvenir" value="<?php echo $this->session->flashdata('stok_keluar_souvenir'); ?>" />
            </div>

            <div class="name_box">
                <label for="tgl_serah_souvenir">Tanggal Serah</label>
                <input type="date" name="tgl_serah_souvenir" id="tgl_serah_souvenir" 
                value="<?php
                $tgl_serah_souvenir = $this->session->flashdata('tgl_serah_souvenir'); 
                echo empty($tgl_serah_souvenir) ? date("Y-m-d") : $tgl_serah_souvenir; ?>" />
            </div>

            <div class="description_box">
                <label for="ket_souvenir">Keterangan</label>
                <textarea name="ket_souvenir" id="ket_souvenir"><?php echo $this->session->flashdata('ket_souvenir'); ?></textarea>
            </div>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_souvenir_keluar">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "input/souvenir_keluar"); ?>
</html>