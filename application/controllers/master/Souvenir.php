<?php
class Souvenir extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        if (isset($_GET["id"])) {
            $souvenir = $this->pbs->tsouvenir_get(2, "", array("where", "id_souvenir", sanitize($this->input->get("id"))));
            if ($souvenir->num_rows() === 1) {
                $id_souvenir = $souvenir->row()->id_souvenir;
                $kode_souvenir = $souvenir->row()->kode_souvenir;
                $nama = $souvenir->row()->nama;
                $stok_awal = convert_number_tocurrency($souvenir->row()->stok_awal);
                $jenis = $souvenir->row()->jenis;
                $satuan = $souvenir->row()->satuan;
                $ket = $souvenir->row()->ket;
                prepare_flashdata(
                    array("id_souvenir", $id_souvenir), array("kode_souvenir", $kode_souvenir), array("nama_souvenir", $nama), 
                    array("stok_awal_souvenir", $stok_awal), array("jenis_souvenir", $jenis), array("satuan_souvenir", $satuan),
                    array("ket_souvenir", $ket));
            }
            redirect("master/souvenir");
        }
        $this->load->view("master/v_souvenir");
    }
        
    function save_souvenir() {
        if (isset($_POST["save_souvenir"])) {
            $id_souvenir = sanitize($this->input->post("id_souvenir"));
            $kode_souvenir = sanitize($this->input->post("kode_souvenir"));
            $nama_souvenir = sanitize($this->input->post("nama_souvenir"));
            $stok_awal_souvenir = sanitize($this->input->post("stok_awal_souvenir"));
            $jenis_souvenir = sanitize($this->input->post("jenis_souvenir"));
            $satuan_souvenir = sanitize($this->input->post("satuan_souvenir"));
            $ket_souvenir = sanitize($this->input->post("ket_souvenir"));
            
            require_once("C_Souvenir.php");
            $souvenir = new C_Souvenir($id_souvenir, $kode_souvenir, $nama_souvenir, convert_currency_tonumber($stok_awal_souvenir), $jenis_souvenir, 
            $satuan_souvenir, $ket_souvenir);
            $is_valid_souvenir = $souvenir->is_valid_souvenir();
            if (!$is_valid_souvenir[0]) {
                prepare_flashdata(
                    array("id_souvenir", $id_souvenir), array("kode_souvenir", $kode_souvenir), array("nama_souvenir", $nama_souvenir),
                    array("stok_awal_souvenir", $stok_awal_souvenir), array("jenis_souvenir", $jenis_souvenir), array("satuan_souvenir", $satuan_souvenir), 
                    array("ket_souvenir", $ket_souvenir), array("report", get_form_report("error", $is_valid_souvenir[1])));
            } else {
                $this->pbs->tsouvenir_put($souvenir->get_souvenir());
                prepare_flashdata(array("report", get_form_report("success", "souvenir")));
            }
            redirect("master/souvenir");
        } else {
            redirect(get_error_page());
        }
    } 

}