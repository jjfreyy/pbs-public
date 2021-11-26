<?php
class Donatur extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $this->load->view("daftar/v_donatur");
    }

    function delete() {
        if (isset($_POST["key"]) && $_POST["key"] == "hapus_donatur") {
            if ($this->pbs->tuser_get(array(
                "select" => "username, lev",
                "filter" => array("username" => $this->session->username)
            ))->row()->lev != 1) {
                echo get_json_response("delete", "error", "donatur");
                return;
            }

            $id_donatur = sanitize($this->input->post("data")["id"]);
            $result = $this->pbs->tpaket_sumbangan_get(1, "id_donatur", array("id_donatur", $id_donatur))->num_rows();
            if ($result > 0) {
                echo get_json_response("custom", "error", "Gagal menghapus donatur.<br>Donatur telah terdaftar dalam paket sumbangan.");
                return;
            }

            if ($this->pbs->tdonatur_delete($id_donatur)) {
                echo get_json_response("delete", "success", "donatur");
            } else {
                echo get_json_response("delete", "error", "donatur");;
            }

        } else {
            redirect(get_error_page());
        }
    }

    function get_donatur_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $donatur = sanitize($data["donatur"]);
            $tgl_terakhir = sanitize($data["tgl_terakhir"]);
            
            if ($key == "daftar_donatur") {
                $page = sanitize($data["page"]);
                $display_per_page = sanitize($data["display_per_page"]);
                $data = $this->pbs->tdonatur_get(3, $donatur, $tgl_terakhir, $page, $display_per_page);
                echo json_encode($data->result());
            }

            if ($key == "total_donatur") {
                $data = $this->pbs->tdonatur_get(3, $donatur, $tgl_terakhir, "");
                echo $data->num_rows();
            }

        } else {
            redirect(get_error_page());
        }
    }

}