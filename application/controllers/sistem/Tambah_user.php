<?php
class Tambah_user extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
        if ($this->pbs->tuser_get($this->session->username)->row()->lev != 1) redirect(get_error_page());
    }
    
    function index() {
        $this->load->view("sistem/v_tambah_user");
    }
        
    function save_user() {
        if (isset($_POST["save_user"])) {
            $username = sanitize($this->input->post("username"));
            $pass = sanitize($this->input->post("pass"));
            $pass1 = sanitize($this->input->post("pass1"));
            
            require_once("C_User.php");
            $user = new C_User("INSERT", $username, $pass, $pass1);
            $is_valid_user = $user->is_valid_user();
            if (!$is_valid_user[0]) {
                prepare_flashdata(array("report", get_form_report("error", $is_valid_user[1])));
            } else {
                $this->pbs->tuser_put(array("option" => 1, "insert" => $user->get_user()));
                prepare_flashdata(array("report", get_form_report("success", "user")));
            }
            redirect("sistem/tambah_user");
        } else {
            redirect(get_error_page());
        }
    }

}