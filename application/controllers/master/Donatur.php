<?php
class Donatur extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        if (isset($_GET["id"])) {
            $donatur = $this->pbs->tdonatur_get(2, "", array("where", "id_donatur", sanitize($this->input->get("id"))));
            if ($donatur->num_rows() === 1) {
                $id_donatur = $donatur->row()->id_donatur;
                $kode_donatur = explode("-", $donatur->row()->kode_donatur)[1];
                $nama_id = $donatur->row()->nama_id;
                $nama_cn = $donatur->row()->nama_cn;
                $alamat = $donatur->row()->alamat;
                $kota_lahir = $donatur->row()->kota_lahir;
                $tgl_lahir = $donatur->row()->tgl_lahir;
                $kota_domisili = $donatur->row()->kota_domisili;
                $no_hp1 = $donatur->row()->no_hp1;
                $no_hp2 = $donatur->row()->no_hp2;
                $email = $donatur->row()->email;
                $ket = $donatur->row()->ket;
                $tgl_gabung = $donatur->row()->tgl_gabung;
                prepare_flashdata(
                    array("id_donatur", $id_donatur), array("kode_donatur", $kode_donatur), array("nama_id_donatur", $nama_id), 
                    array("nama_cn_donatur", $nama_cn), array("alamat_donatur", $alamat), array("kota_lahir_donatur", $kota_lahir), 
                    array("tgl_lahir_donatur", $tgl_lahir), array("kota_domisili_donatur", $kota_domisili), array("no_hp1_donatur", $no_hp1), 
                    array("no_hp2_donatur", $no_hp2), array("email_donatur", $email), array("ket_donatur", $ket), 
                    array("tgl_gabung_donatur", $tgl_gabung));
            }
            redirect("master/donatur");
        }
        $this->load->view("master/v_donatur");
    }

    function save_donatur() {
        if (isset($_POST["save_donatur"])) {
            $id_donatur = sanitize($this->input->post("id_donatur"));
            $kode_donatur = sanitize($this->input->post("kode_donatur"));
            $nama_id = sanitize($this->input->post("nama_id_donatur"));
            $nama_cn = sanitize($this->input->post("nama_cn_donatur"));
            $alamat = sanitize($this->input->post("alamat_donatur"));
            $kota_lahir = sanitize($this->input->post("kota_lahir_donatur"));
            $tgl_lahir = sanitize($this->input->post("tgl_lahir_donatur"));
            $kota_domisili = sanitize($this->input->post("kota_domisili_donatur"));
            $no_hp1 = sanitize($this->input->post("no_hp1_donatur"));
            $no_hp2 = sanitize($this->input->post("no_hp2_donatur"));
            $email = sanitize($this->input->post("email_donatur"));
            $ket = sanitize($this->input->post("ket_donatur"));
            $tgl_gabung = sanitize($this->input->post("tgl_gabung_donatur"));
            
            require_once("C_Donatur.php");
            $donatur = new C_Donatur($id_donatur, $kode_donatur, $nama_id, $nama_cn, $alamat, $kota_lahir, $tgl_lahir, $kota_domisili, 
            $no_hp1, $no_hp2, $email, $ket, $tgl_gabung);
            echo $donatur->get_id_donatur();
            $is_valid_donatur = $donatur->is_valid_donatur();
            if (!$is_valid_donatur[0]) {
                prepare_flashdata(
                    array("id_donatur", $id_donatur), array("kode_donatur", $kode_donatur), array("nama_id_donatur", $nama_id), 
                    array("nama_cn_donatur", $nama_cn), array("alamat_donatur", $alamat), array("kota_lahir_donatur", $kota_lahir), 
                    array("tgl_lahir_donatur", $tgl_lahir), array("kota_domisili_donatur", $kota_domisili), array("no_hp1_donatur", $no_hp1), 
                    array("no_hp2_donatur", $no_hp2), array("email_donatur", $email), array("ket_donatur", $ket), 
                    array("tgl_gabung_donatur", $tgl_gabung), array("report", get_form_report("error", $is_valid_donatur[1])));
            } else {
                $this->pbs->tdonatur_put($donatur->get_donatur());
                prepare_flashdata(array("report", get_form_report("success", "donatur")));
            }
            redirect("master/donatur");
        } else {
            redirect(get_error_page());
        }
    }

}