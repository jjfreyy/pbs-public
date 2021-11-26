<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Donatur</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("master/donatur/save_donatur", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>

            <div class="code_box">
                <label for="kode_donatur">Kode Donatur</label>
                <div>
                    <input type="text" class="hidden" name="id_donatur" id="id_donatur" value="<?php echo $this->session->flashdata("id_donatur"); ?>">
                    <input type="text" value="D" readonly />
                    <input type="text" name="kode_donatur" id="kode_donatur" autocomplete="off" list="donatur_list" autofocus 
                    value="<?php echo $this->session->flashdata('kode_donatur'); ?>" />
                    <datalist id="donatur_list"></datalist>
                </div>
            </div>
            
            <div class="name_box">
                <label for="nama_id_donatur">Nama Indonesia*</label>
                <input type="text" name="nama_id_donatur" id="nama_id_donatur" required value="<?php echo $this->session->flashdata('nama_id_donatur'); ?>" />
            </div>

            <div class="name_box">
                <label for="nama_cn_donatur">Nama Mandarin</label>
                <input type="text" name="nama_cn_donatur" id="nama_cn_donatur" value="<?php echo $this->session->flashdata('nama_cn_donatur'); ?>" />
            </div>

            <div class="name_box">
                <label for="alamat_donatur">Alamat</label>
                <input type="text" name="alamat_donatur" id="alamat_donatur" value="<?php echo $this->session->flashdata('alamat_donatur'); ?>" />
            </div>

            <div class="code_box">
                <label for="kota_lahir_donatur">Tempat/Tanggal Lahir</label>
                <div>
                    <input type="text" name="kota_lahir_donatur" id="kota_lahir_donatur" autocomplete="off" list="kota_lahir_list" 
                    value="<?php echo $this->session->flashdata('kota_lahir_donatur'); ?>" />
                    <datalist id="kota_lahir_list"></datalist>
                    <input type="date" name="tgl_lahir_donatur" id="tgl_lahir_donatur" value="<?php echo $this->session->flashdata('tgl_lahir_donatur'); ?>" />
                </div>
            </div>

            <div class="name_box">
                <label for="kota_domisili_donatur">Kota Domisili</label>
                <input type="text" name="kota_domisili_donatur" id="kota_domisili_donatur" autocomplete="off" list="kota_domisili_list" 
                value="<?php echo $this->session->flashdata('kota_domisili_donatur'); ?>" />
                <datalist id="kota_domisili_list"></datalist>
            </div>

            <div class="name_box">
                <label for="no_hp1_donatur">No. HP1</label>
                <input type="text" name="no_hp1_donatur" id="no_hp1_donatur" value="<?php echo $this->session->flashdata('no_hp1_donatur'); ?>" />
            </div>

            <div class="name_box">
                <label for="no_hp2_donatur">No. HP2</label>
                <input type="text" name="no_hp2_donatur" id="no_hp2_donatur" value="<?php echo $this->session->flashdata('no_hp2_donatur'); ?>" />
            </div>

            <div class="name_box">
                <label for="email_donatur">E-mail</label>
                <input type="email" name="email_donatur" id="email_donatur" value="<?php echo $this->session->flashdata('email_donatur'); ?>" />
            </div>

            <div class="description_box">
                <label for="ket_donatur">Keterangan</label>
                <textarea name="ket_donatur" id="ket_donatur"><?php echo $this->session->flashdata('ket_donatur'); ?></textarea>
            </div>

            <div class="name_box">
                <label for="tgl_gabung_donatur">Tanggal Gabung*</label>
                <input type="date" name="tgl_gabung_donatur" id="tgl_gabung_donatur" required 
                value="<?php
                $tgl_gabung = $this->session->flashdata('tgl_gabung_donatur'); 
                echo empty($tgl_gabung) ? date("Y-m-d") : $tgl_gabung; ?>" />
            </div>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_donatur">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "master/donatur"); ?>
</html>