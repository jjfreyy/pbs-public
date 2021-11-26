<?php
class Ubah_password extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        $this->load->view("sistem/v_ubah_password");
    }
        
    function update_user() {
        if (isset($_POST["update_user"])) {
            $username = sanitize($this->input->post("username"));
            $pass = sanitize($this->input->post("pass"));
            $pass1 = sanitize($this->input->post("pass1"));
            
            require_once("C_User.php");
            $user = new C_User("UPDATE", $username, $pass, $pass1);
            $is_valid_user = $user->is_valid_user();
            if (!$is_valid_user[0]) {
                prepare_flashdata(array("report", get_form_report("error", $is_valid_user[1])));
            } else {
                $this->pbs->tuser_put(array("option" => 2, "update" => array($this->session->username, $user->get_user())));
                $this->session->username = $user->get_username();
                prepare_flashdata(array("report", get_form_report("success", "user")));
            }
            redirect("sistem/ubah_password");
        } else {
            redirect(get_error_page());
        }
    }

}