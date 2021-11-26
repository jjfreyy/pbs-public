<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Halaman Utama</title>
  <?php load_css("base", "nav"); ?>
  <style>
    body {
      transition: background 2s;
    }

    div.col {
      position: absolute;
      width: 100%;
      top: 25vh;
      padding: 20vh 0;
      background: var(--nav-color);
      color: white;
      display: none;
      text-align: center;
      transition: background 2.5s;
      z-index: -1;
    }

    h1 {
      opacity: 0;
      transition: opacity 3s;
    }
  </style>
</head>
<body>
  <?php $this->load->view('templates/header'); ?>

  <?php $this->load->view('templates/aside'); ?>

  <section id="main">
    <div class="col" id="welcome">
      <h1>Selamat Datang.</h1>
    </div>
  </section>
</body>
<?php load_js("script", "nav"); ?>
<script>
  $('#welcome').slideDown(2000);
  setTimeout(function() {
    $('body').css('background', 'var(--color7)');
    setTimeout(function() {
      $('h1').css('opacity', '1');
      setTimeout(function() {
        $('#welcome').css('background', 'var(--color6)');
      }, 1000);
    }, 1000);
  }, 2000);
</script>
</html>
