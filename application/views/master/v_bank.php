<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Bank</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("master/bank/save_bank", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>

            <div class="name_box">
                <input type="text" class="hidden" name="id_bank" id="id_bank" value="<?php echo $this->session->flashdata("id_bank"); ?>">
                <label for="nama_bank">Nama Bank*</label>
                <input type="text" name="nama_bank" id="nama_bank" required autocomplete="off" list="bank_list" autofocus
                value="<?php echo $this->session->flashdata('nama_bank'); ?>" />
                <datalist id="bank_list"></datalist>
            </div>
            
            <div class="name_box">
                <label for="an">AN*</label>
                <input type="text" name="an" id="an" required value="<?php echo $this->session->flashdata('an'); ?>" />
            </div>

            <div class="name_box">
                <label for="no_rek">No. Rekening*</label>
                <input type="text" name="no_rek" id="no_rek" required value="<?php echo $this->session->flashdata('no_rek'); ?>" />
            </div>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_bank">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "master/bank"); ?>
</html>