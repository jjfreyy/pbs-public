<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Souvenir Masuk</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("input/souvenir_masuk/save_souvenir_masuk", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>
            <input type="hidden" name="id" value="<?php echo $this->session->flashdata("id"); ?>" />
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
                <label for="stok_masuk_souvenir">Qty*</label>
                <input type="text" name="stok_masuk_souvenir" id="stok_masuk_souvenir" required
                value="<?php echo $this->session->flashdata('stok_masuk_souvenir'); ?>" />
            </div>

            <div class="description_box">
                <label for="ket_souvenir">Keterangan</label>
                <textarea name="ket_souvenir" id="ket_souvenir"><?php echo $this->session->flashdata('ket_souvenir'); ?></textarea>
            </div>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_souvenir_masuk">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "input/souvenir_masuk"); ?>
</html>