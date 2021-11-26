<?php
class Unknown extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["message"] = "
        <h1>Oops. . .</h1>
        <p>Halaman yang anda cari tidak dapat ditemukan.</p>
        " .anchor("home", "Kembali ke halaman utama.");

        $this->load->view("errors/error_page", $data);
    }
}