<?php
class Home extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $this->load->view("v_home");
    }
}