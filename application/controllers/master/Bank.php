<?php
class Bank extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        if (isset($_GET["id"])) {
            $bank = $this->pbs->tbank_get(2, "*", array("where", "id_bank", sanitize($this->input->get("id"))));
            var_dump($bank->result());
            if ($bank->num_rows() === 1) {
                $id_bank = $bank->row()->id_bank;
                $nama_bank = $bank->row()->nama_bank;
                $an = $bank->row()->an;
                $no_rek = $bank->row()->no_rek;
                prepare_flashdata(
                    array("id_bank", $id_bank), array("nama_bank", $nama_bank), array("an", $an),
                    array("no_rek", $no_rek));
            }
            redirect("master/bank");
            
        }
        $this->load->view("master/v_bank");
    }

    function test() {
        echo func_get_arg(0) == null;
    }
        
    function save_bank() {
        if (isset($_POST["save_bank"])) {
            $id_bank = sanitize($this->input->post("id_bank"));
            $nama_bank = sanitize($this->input->post("nama_bank"));
            $an = sanitize($this->input->post("an"));
            $no_rek = sanitize($this->input->post("no_rek"));
            
            require_once("C_Bank.php");
            $bank = new C_Bank($id_bank, $nama_bank, $an, $no_rek);
            $is_valid_bank = $bank->is_valid_bank();
            if (!$is_valid_bank[0]) {
                prepare_flashdata(array("id_bank", $id_bank), array("nama_bank", $nama_bank), array("an", $an),
                array("no_rek", $no_rek), array("report", get_form_report("error", $is_valid_bank[1])));
            } else {
                $this->pbs->tbank_put($bank->get_bank());
                prepare_flashdata(array("report", get_form_report("success", "bank")));
            }
            redirect("master/bank");
        } else {
            redirect(get_error_page());
        }
    }
}