<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Kolektor</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("master/kolektor/save_kolektor", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>

            <div class="code_box">
                <label for="kode_kolektor">Kode Kolektor</label>
                <div>
                    <input type="text" class="hidden" name="id_kolektor" id="id_kolektor" value="<?php $this->session->flashdata("id_kolektor"); ?>">
                    <input type="text" value="K" readonly />
                    <input type="text" name="kode_kolektor" id="kode_kolektor" autocomplete="off" list="kolektor_list" autofocus 
                    value="<?php echo $this->session->flashdata('kode_kolektor'); ?>" />
                    <datalist id="kolektor_list"></datalist>
                </div>
            </div>
            
            <div class="name_box">
                <label for="nama_kolektor">Nama*</label>
                <input type="text" name="nama_kolektor" id="nama_kolektor" required value="<?php echo $this->session->flashdata('nama_kolektor'); ?>" />
            </div>

            <div class="name_box">
                <label for="kolektor_no_hp1">No. HP1*</label>
                <input type="text" name="no_hp1_kolektor" id="no_hp1_kolektor" required value="<?php echo $this->session->flashdata('no_hp1_kolektor'); ?>" />
            </div>

            <div class="name_box">
                <label for="no_hp2_kolektor">No. HP2</label>
                <input type="text" name="no_hp2_kolektor" id="no_hp2_kolektor" value="<?php echo $this->session->flashdata('no_hp2_kolektor'); ?>" />
            </div>

            <div class="name_box">
                <label for="email_kolektor">E-mail</label>
                <input type="email" name="email_kolektor" id="email_kolektor" value="<?php echo $this->session->flashdata('email_kolektor'); ?>" />
            </div>

            <div class="description_box">
                <label for="ket_kolektor">Keterangan</label>
                <textarea name="ket_kolektor" id="ket_kolektor"><?php echo $this->session->flashdata('ket_kolektor'); ?></textarea>
            </div>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_kolektor">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>

</body>
<?php load_js("script", "nav", "global", "master/kolektor"); ?>
</html>