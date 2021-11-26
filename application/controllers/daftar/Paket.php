<?php
class Paket extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $this->load->view("daftar/v_paket");
    }

    /** ajax */
    function delete() {
        if (isset($_POST["key"]) && $_POST["key"] == "hapus_paket") {
            if ($this->pbs->tuser_get(array(
                "select" => "username, lev",
                "filter" => array("username" => $this->session->username)
            ))->row()->lev != 1) {
                echo get_json_response("delete", "error", "paket");
                return;
            }

            $id_paket = sanitize($this->input->post("data")["id"]);
            $result = $this->pbs->tpaket_sumbangan_get(1, "id_paket", array("id_paket", $id_paket))->num_rows();
            if ($result > 0) {
                echo get_json_response("custom", "error", "Gagal menghapus paket.<br>Paket telah terdaftar dalam paket sumbangan");
                return;
            }
            
            if ($this->pbs->tpaket_delete($id_paket)) {
                echo get_json_response("delete", "success", "paket");
            } else {
                echo get_json_response("delete", "error", "paket");
            }

        } else {
            redirect(get_error_page());
        }
    }

    function get_paket_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $filter = sanitize($data["filter"]);

            if ($key == "daftar_paket") {
                $page = sanitize($data["page"]);
                $display_per_page = sanitize($data["display_per_page"]);
                $data = $this->pbs->tpaket_get(2, "", array("like", $filter), $page, $display_per_page);
                echo json_encode($data->result());
            }

            if ($key == "total_paket") {
                $data = $this->pbs->tpaket_get(2, "", array("like", $filter), "");
                echo $data->num_rows();
            }

        } else {
            redirect(get_error_page());
        }
    }
}