<?php
class Kolektor extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        if (isset($_GET["id"])) {
            $kolektor = $this->pbs->tkolektor_get(2, "", array("where", "id_kolektor", sanitize($this->input->get("id"))));
            if ($kolektor->num_rows() === 1) {
                $id_kolektor = $kolektor->row()->id_kolektor;
                $kode_kolektor = explode("-", $kolektor->row()->kode_kolektor)[1];
                $nama = $kolektor->row()->nama;
                $no_hp1 = $kolektor->row()->no_hp1;
                $no_hp2 = $kolektor->row()->no_hp2;
                $email = $kolektor->row()->email;
                $ket = $kolektor->row()->ket;
                prepare_flashdata(
                    array("id_kolektor", $id_kolektor), array("kode_kolektor", $kode_kolektor), array("nama_kolektor", $nama), 
                    array("no_hp1_kolektor", $no_hp1), array("no_hp2_kolektor", $no_hp2), array("email_kolektor", $email), 
                    array("ket_kolektor", $ket)
                );
            }
            redirect("master/kolektor");
        }
        $this->load->view("master/v_kolektor");
    }

    function save_kolektor() {
        if (isset($_POST["save_kolektor"])) {
            $id_kolektor = sanitize($this->input->post("id_kolektor"));
            $kode_kolektor = sanitize($this->input->post("kode_kolektor"));
            $nama = sanitize($this->input->post("nama_kolektor"));
            $no_hp1 = sanitize($this->input->post("no_hp1_kolektor"));
            $no_hp2 = sanitize($this->input->post("no_hp2_kolektor"));
            $email = sanitize($this->input->post("email_kolektor"));
            $ket = sanitize($this->input->post("ket_kolektor"));
            
            require_once("C_Kolektor.php");
            $kolektor = new C_Kolektor($id_kolektor, $kode_kolektor, $nama, $no_hp1, $no_hp2, $email, $ket);
            $is_valid_kolektor = $kolektor->is_valid_kolektor();
            if (!$is_valid_kolektor[0]) {
                prepare_flashdata(
                    array("id_kolektor", $id_kolektor), array("kode_kolektor", $kode_kolektor), array("nama_kolektor", $nama), 
                    array("no_hp1_kolektor", $no_hp1), array("no_hp2_kolektor", $no_hp2), array("email_kolektor", $email), 
                    array("ket_kolektor", $ket), array("report", get_form_report("error", $is_valid_kolektor[1])));
            } else {
                $this->pbs->tkolektor_put($kolektor->get_kolektor());
                prepare_flashdata(array("report", get_form_report("success", "kolektor")));
            }

            redirect("master/kolektor");
        } else {
            redirect(get_error_page());
        }
    }

}