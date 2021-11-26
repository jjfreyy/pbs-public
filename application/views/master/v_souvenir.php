<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Souvenir</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("master/souvenir/save_souvenir", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>

            <div class="name_box">
                <input type="text" class="hidden" name="id_souvenir" id="id_souvenir" value="<?php echo $this->session->flashdata("id_souvenir"); ?>">
                <label for="kode_souvenir">Kode Souvenir</label>
                <input type="text" name="kode_souvenir" id="kode_souvenir" autocomplete="off" list="souvenir_list" autofocus 
                value="<?php echo $this->session->flashdata('kode_souvenir'); ?>" />
                <datalist id="souvenir_list"></datalist>
            </div>
            
            <div class="name_box">
                <label for="nama_souvenir">Nama Souvenir*</label>
                <input type="text" name="nama_souvenir" id="nama_souvenir" required value="<?php echo $this->session->flashdata('nama_souvenir'); ?>" />
            </div>

            <div class="name_box">
                <label for="stok_awal_souvenir">Stok Awal*</label>
                <input type="text" name="stok_awal_souvenir" id="stok_awal_souvenir" required value="<?php echo $this->session->flashdata('stok_awal_souvenir'); ?>" />
            </div>

            <div class="name_box">
                <label for="jenis_souvenir">Jenis*</label>
                <input type="text" name="jenis_souvenir" id="jenis_souvenir" autocomplete="off" required list="jenis_souvenir_list" 
                value="<?php echo $this->session->flashdata('jenis_souvenir'); ?>" />
                <datalist id="jenis_souvenir_list"></datalist>
            </div>

            <div class="name_box">
                <label for="satuan_souvenir">Satuan*</label>
                <input type="text" name="satuan_souvenir" id="satuan_souvenir" autocomplete="off" required list="satuan_list" 
                value="<?php echo $this->session->flashdata('satuan_souvenir'); ?>" />
                <datalist id="satuan_list"></datalist>
            </div>

            <div class="description_box">
                <label for="ket_souvenir">Keterangan</label>
                <textarea name="ket_souvenir" id="ket_souvenir"><?php echo $this->session->flashdata("ket_souvenir"); ?></textarea>
            </div>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_souvenir">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "master/souvenir"); ?>
</html>