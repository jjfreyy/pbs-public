<?php
class Paket extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        if (isset($_GET["id"])) {
            $paket = $this->pbs->tpaket_get(2, "", array("where", "tp.id_paket", sanitize($this->input->get("id"))), "");
            if ($paket->num_rows() === 1) {
                $id_paket = $paket->row()->id_paket;
                $nama_perusahaan = $paket->row()->nama_perusahaan;
                $kode_paket = $paket->row()->kode_paket;
                $nama_paket = $paket->row()->nama_paket;
                $nilai_paket = $paket->row()->nilai_paket;
                $periode = $paket->row()->periode;
                $periode = is_empty($periode) ? array("", "T") : explode(" ", $periode);
                $bank_list = is_empty($paket->row()->bank_list) ? "" : explode("#", $paket->row()->bank_list);
                prepare_flashdata(
                    array("id_paket", $id_paket), array("nama_perusahaan", $nama_perusahaan), array("kode_paket", $kode_paket),
                    array("nama_paket", $nama_paket), array("nilai_paket", $nilai_paket), array("periode", $periode[0]), 
                    array("periode1", $periode[1]), array("bank_list", $bank_list));
            }
            redirect("master/paket");
        }
        $this->load->view("master/v_paket");
    }
    
    function save_paket() {
        if (isset($_POST["save_paket"])) {
            $id_paket = sanitize($this->input->post("id_paket"));
            $nama_perusahaan = sanitize($this->input->post("nama_perusahaan"));
            $kode_paket = sanitize($this->input->post("kode_paket"));
            $nama_paket = sanitize($this->input->post("nama_paket"));
            $nilai_paket = sanitize($this->input->post("nilai_paket"));
            $periode = sanitize($this->input->post("periode"));
            $periode1 = sanitize($this->input->post("periode1"));
            $periode2 = is_empty($periode) ? "" : $periode. " " .$periode1;
            $bank_list = $this->input->post("bank_list");
            
            require_once("C_Paket.php");
            $paket = new C_Paket($id_paket, $nama_perusahaan, $kode_paket, $nama_paket, convert_currency_tonumber($nilai_paket), $periode2, $bank_list);
            $is_valid_paket = $paket->is_valid_paket();
            if (!$is_valid_paket[0]) {
                $errors = $is_valid_paket[1];
            } else {
                $id_paket1 = $this->pbs->tpaket_put($paket->get_id_paket(), $paket->get_paket())->row()->id_paket;
                $this->pbs->tpaket1_delete($id_paket1);
                if (is_empty_array($bank_list)) {
                    $this->pbs->commit();
                    prepare_flashdata(array("report", get_form_report("success", "paket")));
                } else {
                    $is_valid_bank_list = $paket->is_valid_bank_list($id_paket1);
                    if (!$is_valid_bank_list[0]) {
                        $errors = $is_valid_bank_list[1];
                        $this->pbs->tpaket_rollback($id_paket1);
                    } else {
                        $result = $this->pbs->tpaket1_put($is_valid_bank_list[1]);
                        if (!$result) {
                            $this->pbs->tpaket_rollback($id_paket1);
                            $errors[] = get_form_report("error", "paket");
                        } else {
                            $this->pbs->commit();
                            prepare_flashdata(array("report", get_form_report("success", "paket")));
                        }
                    }
                }
            }

            if (isset($errors)) {
                prepare_flashdata(array("id_paket", $id_paket), array("nama_perusahaan", $nama_perusahaan), array("kode_paket", $kode_paket),
                array("nama_paket", $nama_paket), array("nilai_paket", $nilai_paket), array("periode", $periode),
                array("periode1", $periode1), array("bank_list", $bank_list), array("report", get_form_report("error", $errors)));
            }

            redirect("master/paket");
        } else {
            redirect(get_error_page());
        }
    }
}