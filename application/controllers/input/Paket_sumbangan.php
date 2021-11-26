<?php
class Paket_sumbangan extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        if (isset($_GET["id"])) {
            $paket_sumbangan = $this->pbs->tpaket_sumbangan_get(3, sanitize($this->input->get("id")));
            if ($paket_sumbangan->num_rows() === 1) {
                $id_paket_sumbangan = $paket_sumbangan->row()->id_paket_sumbangan;
                $id_donatur = $paket_sumbangan->row()->id_donatur;
                $kode_donatur = $paket_sumbangan->row()->kode_donatur;
                $nama_donatur = $paket_sumbangan->row()->nama_donatur;
                $id_kolektor = $paket_sumbangan->row()->id_kolektor;
                $kode_kolektor = $paket_sumbangan->row()->kode_kolektor;
                $nama_kolektor = $paket_sumbangan->row()->nama_kolektor;
                $id_paket = $paket_sumbangan->row()->id_paket;
                $kode_paket = $paket_sumbangan->row()->kode_paket;
                $nama_paket = $paket_sumbangan->row()->nama_paket;
                $nilai_paket = is_empty($paket_sumbangan->row()->nilai_paket) ? "∞" : convert_number_tocurrency($paket_sumbangan->row()->nilai_paket);
                $jumlah_paket = $paket_sumbangan->row()->jumlah_paket;
                $ket = $paket_sumbangan->row()->ket;
                $tgl_jatuh_tempo = $paket_sumbangan->row()->tgl_jatuh_tempo;
                $biaowen = is_empty($paket_sumbangan->row()->biaowen_list) ? NULL : explode("#", $paket_sumbangan->row()->biaowen_list);
                prepare_flashdata(
                    array("id_paket_sumbangan", $id_paket_sumbangan), array("id_donatur", $id_donatur), array("kode_donatur", $kode_donatur), 
                    array("nama_donatur", $nama_donatur), array("id_kolektor", $id_kolektor), array("kode_kolektor", $kode_kolektor), 
                    array("nama_kolektor", $nama_kolektor), array("id_paket", $id_paket), array("kode_paket", $kode_paket), 
                    array("nama_paket", $nama_paket), array("nilai_paket", $nilai_paket), array("jumlah_paket", $jumlah_paket), 
                    array("ket_paket", $ket), array("tgl_jatuh_tempo", $tgl_jatuh_tempo), array("biaowen", $biaowen));
                }
            redirect("input/paket_sumbangan");
        }
        $this->load->view("input/v_paket_sumbangan");
    }
    
    function save_paket_sumbangan() {
        if (isset($_POST["save_paket_sumbangan"])) {
            $id_paket_sumbangan = sanitize($this->input->post("id_paket_sumbangan"));
            $id_donatur = sanitize($this->input->post("id_donatur"));
            $kode_donatur = sanitize($this->input->post("kode_donatur"));
            $nama_donatur = $this->input->post("nama_donatur");
            $id_kolektor = sanitize($this->input->post("id_kolektor"));
            $kode_kolektor = sanitize($this->input->post("kode_kolektor"));
            $nama_kolektor = sanitize($this->input->post("nama_kolektor"));
            $id_paket = sanitize($this->input->post("id_paket"));
            $kode_paket = sanitize($this->input->post("kode_paket"));
            $nama_paket = sanitize($this->input->post("nama_paket"));
            $nilai_paket = sanitize($this->input->post("nilai_paket"));
            $jumlah_paket = sanitize($this->input->post("jumlah_paket"));
            $ket = sanitize($this->input->post("ket_paket"));
            $tgl_jatuh_tempo = sanitize($this->input->post("tgl_jatuh_tempo"));
            $biaowen = $this->input->post("biaowen");
            
            require_once("C_Paket_Sumbangan.php");
            $paket_sumbangan = new C_Paket_Sumbangan($id_paket_sumbangan, $id_donatur, $id_kolektor, $id_paket, $jumlah_paket, $ket, $tgl_jatuh_tempo, $biaowen);
            $is_valid_paket_sumbangan = $paket_sumbangan->is_valid_paket_sumbangan();
            if (!$is_valid_paket_sumbangan[0]) {
                $errors = $is_valid_paket_sumbangan[1];
            } else {
                $id_paket_sumbangan = $this->pbs->tpaket_sumbangan_put($paket_sumbangan->get_id_paket_sumbangan(), $paket_sumbangan->get_paket_sumbangan())->row()->id_paket_sumbangan;
                $is_valid_biaowen = $paket_sumbangan->is_valid_biaowen($id_paket_sumbangan);
                if (!$is_valid_biaowen[0]) {
                    $this->pbs->tpaket_sumbangan_rollback($id_paket_sumbangan);
                    $errors = $is_valid_biaowen[1];
                } else {
                    $this->pbs->tpaket_sumbangan1_delete($id_paket_sumbangan);
                    $result = $this->pbs->tpaket_sumbangan1_put($is_valid_biaowen[1]);
                    if (!$result) {
                        $this->pbs->tpaket_sumbangan_rollback($id_paket_sumbangan);
                        $errors[] = get_form_report("error", "paket sumbangan");
                    } else {
                        $this->pbs->commit();
                        prepare_flashdata(array("report", get_form_report("success", "paket sumbangan")));
                    }
                }
            }

            if (isset($errors)) {
                prepare_flashdata(
                    array("id_paket_sumbangan", $id_paket_sumbangan), array("id_donatur", $id_donatur), array("kode_donatur", $kode_donatur), 
                    array("nama_donatur", $nama_donatur), array("id_kolektor", $id_kolektor), array("kode_kolektor", $kode_kolektor), 
                    array("nama_kolektor", $nama_kolektor), array("id_paket", $id_paket), array("kode_paket", $kode_paket), 
                    array("nama_paket", $nama_paket), array("nilai_paket", $nilai_paket), array("jumlah_paket", $jumlah_paket),
                    array("ket_paket", $ket), array("tgl_jatuh_tempo", $tgl_jatuh_tempo), array("biaowen", $biaowen), 
                    array("report", get_form_report("error", $errors)));
            }
            redirect("input/paket_sumbangan");
        } else {
            redirect(get_error_page());
        }
    }
}