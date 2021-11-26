<header class="header_navbar">
  <nav class="navbar">
    <a href="#" id="navicon" onclick="slide_menu_open()">
      <svg width="30" height="25">
      <path d="M0,5 30,5" stroke="#fff" stroke-width="5" />
      <path d="M0,14 30,14" stroke="#fff" stroke-width="5" />
      <path d="M0,23 30,23" stroke="#fff" stroke-width="5" />
      </svg>
    </a>

    <ul id="nav_menu" class="navbar_list">
      <?php
      init_header_navbar();
      function init_header_navbar($kode_menu = "", $data = NULL) {
        $CI =& get_instance();
        $header = !isset($data) ? $CI->pbs->tmenu_get((is_empty($kode_menu) ? 1 : strlen($kode_menu) + 1), $kode_menu) : $data;
        if (is_empty($kode_menu)) {
          foreach ($header->result() as $row) {
            $kode = $row->kode;
            $nama = $row->nama;
            $link = $row->link === "#" ? "#" : base_url($row->link);
            echo "<li class='submenu_title'><a href='$link' class='link_header'>$nama</a>";
            init_header_navbar($kode);
            echo "</li>";
          }
        } else {
          $length = strlen($kode_menu);
          if (!isset($data) && $header->num_rows() > 0) {
            echo "<ul class='submenu sub$length'>";
            init_header_navbar($kode_menu, $header);
            echo "</ul>";
          } else {
            foreach ($header->result() as $row) {
              $kode = $row->kode;
              $nama = $row->nama;
              $link = $row->link === "#" ? "#" : base_url($row->link);
              if ($kode == "61" && $CI->pbs->tuser_get($CI->session->username)->row()->lev != 1) return;
              $nav_icon = $CI->pbs->tmenu_get(strlen($kode) + 1, $kode)->num_rows() > 0 ? "<span class='nav_icon2' />" : "";
              echo "<li class='sub_list$length'><a href='$link'>$nama $nav_icon</a>";
              init_header_navbar($kode);
              echo "</li>";
            }
          }
        }
      }
      ?>
      
      <li><?php echo anchor('logout', 'Keluar'); ?></li>
    </ul>

  </nav>
</header>
