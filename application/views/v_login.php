<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Halaman Login</title>
  <?php load_css("base", "login"); ?>
</head>
<body>

  <section class="login_page">
    <div class="b_container">
      <div class="container">
        <div class="col header">
          <h2>Welcome</h2>
        </div>

        <div class="col">
          <?php echo $this->session->flashdata('report'); ?>
        </div>

        <div class="col">
          <?php echo form_open('login/process_login', 'class="form_login"'); ?>
            <div class="col form_container">

              <div class="row input_row">
                <input type="text" class="i_login" name="username" id="username" autocomplete="off" autofocus required>
                <span class="placeholder">Username</span>
              </div>

              <div class="row input_row">
                <input type="password" class="i_login" name="password" id="password" required>
                <span class="placeholder">Password</span>
              </div>

              <div class="row btn_row">
                <button type="submit" name="login_btn">
                  <img src="<?php 
                  $url = "/src/img/login.png";
                  if (check_assets_file()) echo $url;
                  else echo base_url($url);
                  ?>" alt="">
                  <span>Login</span>
                </button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

</body>
<?php load_js("script", "nav"); ?>
</html>
