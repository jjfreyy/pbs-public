<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Ubah Password</title>
	<?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view('templates/header'); ?>
    <?php $this->load->view('templates/aside'); ?>

	<section id="scol1" class="main">
        <?php echo form_open("sistem/ubah_password/update_user", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>

            <div class="name_box">
                <label for="username">Username*</label>
                <input type="text" name="username" id="username" required value="<?php echo $this->session->username; ?>" />
            </div>
            
            <div class="name_box">
                <label for="pass">Password*</label>
                <input type="password" name="pass" id="pass" required autofocus />
            </div>

            <div class="name_box">
                <label for="pass1">Konfirmasi Password*</label>
                <input type="password" name="pass1" id="pass1" required />
            </div>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="update_user">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav"); ?>
</html>
