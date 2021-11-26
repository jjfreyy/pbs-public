<?php
class Souvenir_keluar extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        if (isset($_GET["id"])) {
            $souvenir = $this->pbs->tsouvenir_get(4, sanitize($this->input->get("id")));
            if ($souvenir->num_rows() === 1) {
                $souvenir = $souvenir->row();
                $id = $souvenir->id;
                $id_paket_sumbangan = $souvenir->id_paket_sumbangan;
                $nama_paket = "$id_paket_sumbangan#$souvenir->nama_paket#$souvenir->nama_donatur";
                $total_donasi = convert_number_tocurrency($souvenir->total_donasi);
                $penerima_souvenir = $souvenir->penerima_souvenir;
                $id_souvenir = $souvenir->id_souvenir;
                $kode_souvenir = $souvenir->kode_souvenir;
                $nama_souvenir = $souvenir->nama_souvenir;
                $stok_tersedia_souvenir = convert_number_tocurrency($souvenir->stok_tersedia);
                $stok_keluar_souvenir = convert_number_tocurrency($souvenir->stok_keluar);
                $tgl_serah_souvenir = $souvenir->tgl_serah;
                $ket_souvenir = $souvenir->ket;
                prepare_flashdata(
                    array("id", $id), array("id_paket_sumbangan", $id_paket_sumbangan), array("nama_paket", $nama_paket), 
                    array("total_donasi", $total_donasi), array("penerima_souvenir", $penerima_souvenir), array("id_souvenir", $id_souvenir), 
                    array("kode_souvenir", $kode_souvenir), array("nama_souvenir", $nama_souvenir), array("stok_tersedia_souvenir", $stok_tersedia_souvenir), 
                    array("stok_keluar_souvenir", $stok_keluar_souvenir), array("tgl_serah_souvenir", $tgl_serah_souvenir), array("ket_souvenir", $ket_souvenir));
            }
            redirect("input/souvenir_keluar");
        }
        $this->load->view("input/v_souvenir_keluar");
    }

    function save_souvenir_keluar() {
        if (isset($_POST["save_souvenir_keluar"])) {
            $id = sanitize($this->input->post("id"));
            $id_paket_sumbangan = sanitize($this->input->post("id_paket_sumbangan"));
            $nama_paket = sanitize($this->input->post("nama_paket"));
            $total_donasi = sanitize($this->input->post("total_donasi"));
            $penerima_souvenir = sanitize($this->input->post("penerima_souvenir"));
            $id_souvenir = sanitize($this->input->post("id_souvenir"));
            $kode_souvenir = sanitize($this->input->post("kode_souvenir"));
            $nama_souvenir = sanitize($this->input->post("nama_souvenir"));
            $stok_tersedia_souvenir = sanitize($this->input->post("stok_tersedia_souvenir"));
            $stok_keluar_souvenir = sanitize($this->input->post("stok_keluar_souvenir"));
            $tgl_serah_souvenir = sanitize($this->input->post("tgl_serah_souvenir"));
            $ket_souvenir = sanitize($this->input->post("ket_souvenir"));
            
            require_once("C_Souvenir_Keluar.php");
            $souvenir2 = new C_Souvenir_Keluar($id, $id_paket_sumbangan, $id_souvenir, $penerima_souvenir, 
            convert_currency_tonumber($stok_keluar_souvenir), $tgl_serah_souvenir, $ket_souvenir);
            $is_valid_souvenir2 = $souvenir2->is_valid_souvenir2();
            if (!$is_valid_souvenir2[0]) {
                prepare_flashdata(
                    array("id", $id), array("id_paket_sumbangan", $id_paket_sumbangan), array("nama_paket", $nama_paket), 
                    array("total_donasi", $total_donasi), array("penerima_souvenir", $penerima_souvenir), array("id_souvenir", $id_souvenir), 
                    array("kode_souvenir", $kode_souvenir), array("nama_souvenir", $nama_souvenir), array("stok_tersedia_souvenir", $stok_tersedia_souvenir), 
                    array("stok_keluar_souvenir", $stok_keluar_souvenir), array("tgl_serah_souvenir", $tgl_serah_souvenir), array("ket_souvenir", $ket_souvenir),
                    array("report", get_form_report("error", $is_valid_souvenir2[1])));  
            } else {
                $this->pbs->tsouvenir2_put($souvenir2->get_souvenir2());
                prepare_flashdata(array("report", get_form_report("success", "souvenir keluar")));
            }
            redirect("input/souvenir_keluar");
        } else {
            redirect(get_error_page());
        }
    }

    function get_paket_sumbangan_list() {
        if (isset($_GET["get_paket_sumbangan_list"])) {
            echo json_encode($this->pbs->tpaket_sumbangan_get(5)->result());
        } else {
            redirect(get_error_page());
        }
    }

    function get_souvenir_list() {
        if (isset($_GET["get_souvenir_list"])) {
            echo json_encode($this->pbs->tsouvenir_get(3, "IN", "", sanitize($this->input->get("id")), "")->result());
        } else {
            redirect(get_error_page());
        }
    }
}