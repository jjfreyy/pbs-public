<?php
class Souvenir_masuk extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        if (isset($_GET["id"])) {
            $souvenir = $this->pbs->tsouvenir1_get(3, sanitize($this->input->get("id")));
            if ($souvenir->num_rows() > 0) {
                $souvenir = $souvenir->row();
                $id = $souvenir->id;
                $id_souvenir = $souvenir->id_souvenir;
                $kode_souvenir = $souvenir->kode_souvenir;
                $nama_souvenir = $souvenir->nama;
                $stok_masuk_souvenir = $souvenir->stok_masuk;
                $ket_souvenir = $souvenir->ket;
                prepare_flashdata(
                    array("id", $id), array("id_souvenir", $id_souvenir), array("kode_souvenir", $kode_souvenir),
                    array("nama_souvenir", $nama_souvenir) ,array("stok_masuk_souvenir", $stok_masuk_souvenir), 
                    array("ket_souvenir", $ket_souvenir)
                );
            }
            redirect("input/souvenir_masuk");
        }
        $this->load->view("input/v_souvenir_masuk");
    }
    
    function save_souvenir_masuk() {
        if (isset($_POST["save_souvenir_masuk"])) {
            $id = sanitize($this->input->post("id"));
            $id_souvenir = sanitize($this->input->post("id_souvenir"));
            $kode_souvenir = sanitize($this->input->post("kode_souvenir"));
            $nama_souvenir = $this->input->post("nama_souvenir");
            $stok_masuk_souvenir = sanitize($this->input->post("stok_masuk_souvenir"));
            $ket_souvenir = sanitize($this->input->post("ket_souvenir"));
            
            require_once("C_Souvenir_Masuk.php");
            $souvenir1 = new C_Souvenir_Masuk($id, $id_souvenir, convert_currency_tonumber($stok_masuk_souvenir), $ket_souvenir);
            $is_valid_souvenir1 = $souvenir1->is_valid_souvenir1();
            if (!$is_valid_souvenir1[0]) {
                prepare_flashdata(
                    array("id", $id), array("id_souvenir", $id_souvenir), array("kode_souvenir", $kode_souvenir), array("nama_souvenir", $nama_souvenir), 
                    array("stok_masuk_souvenir", $stok_masuk_souvenir), array("ket_souvenir", $ket_souvenir), array("report", get_form_report("error", $is_valid_souvenir1[1])));
            } else {
                $id_paket = $this->pbs->tsouvenir1_put($souvenir1->get_souvenir1());
                prepare_flashdata(array("report", get_form_report("success", "souvenir masuk")));
            }

            redirect("input/souvenir_masuk");
        } else {
            redirect(get_error_page());
        }
    }

}