<?php
class Souvenir_keluar extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->tsouvenir2_get_month();
        $this->load->view("tampil/v_souvenir_keluar", $data);
    }

    /** ajax */
    function delete() {
        if (isset($_POST["key"]) && $_POST["key"] == "hapus_souvenir2") {
            if ($this->pbs->tuser_get(array(
                "select" => "username, lev",
                "filter" => array("username" => $this->session->username)
            ))->row()->lev != 1) {
                echo get_json_response("delete", "error", "souvenir keluar");
                return;
            }

            $id = sanitize($this->input->post("data")["id"]);
            if ($this->pbs->tsouvenir2_delete($id)) {
                echo get_json_response("delete", "success", "souvenir keluar");
            } else {
                echo get_json_response("delete", "error", "souvenir keluar");
            }

        } else {
            redirect(get_error_page());
        }
    }

    function get_souvenir2_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $filter = sanitize($data["filter"]);
            $tgl1 = sanitize($data["tgl1"]);
            $tgl2 = sanitize($data["tgl2"]);

            if ($key == "daftar_souvenir2") {
                $page = sanitize($data["page"]);
                $display_per_page = sanitize($data["display_per_page"]);
                $data = $this->pbs->tsouvenir2_get(2, $filter, $tgl1, $tgl2, $page, $display_per_page);
                echo json_encode($data->result());
            }

            if ($key == "total_souvenir2") {
                $data = $this->pbs->tsouvenir2_get(2, $filter, $tgl1, $tgl2, "");
                echo $data->num_rows();
            }

        } else {
            redirect(get_error_page());
        }
    }
}