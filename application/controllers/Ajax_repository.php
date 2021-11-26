<?php
class Ajax_repository extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function get_bank_list() {
        if (isset($_GET["get_bank_list"])) {
            echo json_encode($this->pbs->tbank_get(1, sanitize($this->input->get("field_list")))->result());
        } else {
            redirect(get_error_page());
        }
    }

    function get_donatur_list() {
        if (isset($_GET["get_donatur_list"])) {
            echo json_encode($this->pbs->tdonatur_get(1, sanitize($this->input->get("field_list")))->result());
        } else {
            redirect(get_error_page());
        }
    }
    
    function get_jenis_souvenir_list() {
        if (isset($_GET["get_jenis_souvenir_list"])) {
            echo json_encode($this->pbs->tjenis_souvenir_get()->result());
        } else {
            redirect(get_error_page());
        }
    }

    function get_kolektor_list() {
        if (isset($_GET["get_kolektor_list"])) {
            echo json_encode($this->pbs->tkolektor_get(1, sanitize($this->input->get("field_list")))->result());
        } else {
            redirect(get_error_page());
        }
    }

    function get_kota_list() {
        if (isset($_GET["get_kota_list"])) {
            echo json_encode($this->pbs->tkota_get()->result());
        } else {
            redirect(get_error_page());
        }
    }

    function get_paket_list() {
        if (isset($_GET["get_paket_list"])) {
            echo json_encode($this->pbs->tpaket_get(1, sanitize($this->input->get("field_list")))->result());
        } else {
            redirect(get_error_page());
        }
    }

    function get_satuan_list() {
        if (isset($_GET["get_satuan_list"])) {
            echo json_encode($this->pbs->tsatuan_get()->result());
        } else {
            redirect(get_error_page());
        }
    }

    function get_souvenir_list() {
        if (isset($_GET["get_souvenir_list"])) {
            echo json_encode($this->pbs->tsouvenir_get(1, sanitize($this->input->get("field_list")))->result());
        } else {
            redirect(get_error_page());
        }
    }

}