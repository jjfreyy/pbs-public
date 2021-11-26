<aside id="slide_menu" class="side_nav">
  <a href="#" class="btn_close" onclick="slide_menu_close()">&times;</a>

  <?php
  init_aside_navbar();
  function init_aside_navbar($kode_menu = "", $data = NULL) {
    $CI =& get_instance();
    $aside = !isset($data) ? $CI->pbs->tmenu_get((is_empty($kode_menu) ? 1 : strlen($kode_menu) + 1), $kode_menu) : $data;
    if (is_empty($kode_menu)) {
      foreach ($aside->result() as $row) {
        $kode = $row->kode;
        $nama = $row->nama;
        $link = $row->link === "#" ? "#" : base_url($row->link);
        $nav_icon = $CI->pbs->tmenu_get(strlen($kode) + 1, $kode)->num_rows() > 0 ? "<span class='nav_icon' />" : "";
        echo "<a href='$link' class='link_aside'>$nama $nav_icon</a>";
        init_aside_navbar($kode);
      }
    } else {
      $length = strlen($kode_menu);
      if (!isset($data) && $aside->num_rows() > 0) {
        echo "<ul class='submenu sub$length'>";
        init_aside_navbar($kode_menu, $aside);
        echo "</ul>";
      } else {
        foreach ($aside->result() as $row) {
          $kode = $row->kode;
          $nama = $row->nama;
          $link = $row->link === "#" ? "#" : base_url($row->link);
          if ($kode == "61" && $CI->pbs->tuser_get($CI->session->username)->row()->lev != 1) return;
          $nav_icon = $CI->pbs->tmenu_get(strlen($kode) + 1, $kode)->num_rows() > 0 ? "<span class='nav_icon' />" : "";
          echo "<li class='sub_list$length'><a href='$link' class='link_aside'>$nama $nav_icon</a>";
          init_aside_navbar($kode);
          echo "</li>";
        }
      }
    }
  }
  ?>

  <a href="<?php echo base_url("logout"); ?>" class="link_aside">Keluar</a>
</aside>
