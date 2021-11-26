<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>404 - Page Not Found</title>
  <?php load_css("base"); ?>
  <style>
    body {
      background: var(--nav-color);
    }

    div.col {
      position: absolute;
      padding: 15vh 0;
      top: 20vh;
      width: 100%;
      background: var(--color6);
      color: white;
      text-align: center;
    }

    a {
      color: var(--color3);
      margin-top: 50px;
      transition: color 0.5s;
    }

    a:hover {
      color: white;
    }
  </style>
</head>
<body>
  <div class="col">
    <?php echo $message; ?>
  </div>
</body>
</html>