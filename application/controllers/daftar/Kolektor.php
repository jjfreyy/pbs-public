<?php
class Kolektor extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $this->load->view("daftar/v_kolektor");
    }

    function delete() {
        if (isset($_POST["key"]) && $_POST["key"] == "hapus_kolektor") {
            if ($this->pbs->tuser_get(array(
                "select" => "username, lev",
                "filter" => array("username" => $this->session->username)
            ))->row()->lev != 1) {
                echo get_json_response("delete", "error", "kolektor");
                return;
            }

            $id_kolektor = sanitize($this->input->post("data")["id"]);
            $result = $this->pbs->tpaket_sumbangan_get(1, "id_kolektor", array("id_kolektor", $id_kolektor))->num_rows();
            if ($result > 0) {
                echo get_json_response("custom", "error", "Gagal menghapus kolektor.<br>Kolektor telah terdaftar dalam paket sumbangan.");
                return;
            } 

            if ($this->pbs->tkolektor_delete($id_kolektor)) {
                echo get_json_response("delete", "success", "kolektor");
            } else {
                echo get_json_response("delete", "error", "kolektor");;
            }

        } else {
            redirect(get_error_page());
        }
    }

    function get_kolektor_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $filter = sanitize($data["filter"]);

            if ($key == "daftar_kolektor") {
                $page = sanitize($data["page"]);
                $display_per_page = sanitize($data["display_per_page"]);
                $data = $this->pbs->tkolektor_get(3, $filter, $page, $display_per_page);
                echo json_encode($data->result());
            }
            
            if ($key == "total_kolektor") {
                $data = $this->pbs->tkolektor_get(3, $filter, "");
                echo $data->num_rows();
            }

        } else {
            redirect(get_error_page());
        }
    }
    
}