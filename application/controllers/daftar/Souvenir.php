<?php
class Souvenir extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $this->load->view("daftar/v_souvenir");
    }

    /** ajax */
    function delete() {
        if (isset($_POST["key"]) && $_POST["key"] == "hapus_souvenir") {
            if ($this->pbs->tuser_get(array(
                "select" => "username, lev",
                "filter" => array("username" => $this->session->username)
            ))->row()->lev != 1) {
                echo get_json_response("delete", "error", "souvenir");
                return;
            }

            $id_souvenir = sanitize($this->input->post("data")["id"]);
            $result = $this->pbs->tsouvenir1_get(1, "id_souvenir", array("id_souvenir", $id_souvenir))->num_rows();
            if ($result > 0) {
                echo get_json_response("custom", "error", "Souvenir telah terdaftar dalam souvenir masuk.");
                return;
            }
            $result = $this->pbs->tsouvenir2_get(1, "id_souvenir", array("id_souvenir", $id_souvenir))->num_rows();
            if ($result > 0) {
                echo get_json_response("custom", "error", "Gagal menghapus souvenir.<br>Souvenir telah terdaftar dalam souvenir keluar.");
                return;
            }
            
            if ($this->pbs->tsouvenir_delete($id_souvenir)) {
                echo get_json_response("delete", "success", "souvenir");
            } else {
                echo get_json_response("delete", "error", "souvenir");
            }

        } else {
            redirect(get_error_page());
        }
    }

    function get_souvenir_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $filter = sanitize($data["filter"]);

            if ($key == "daftar_souvenir") {
                $page = sanitize($data["page"]);
                $display_per_page = sanitize($data["display_per_page"]);
                $data = $this->pbs->tsouvenir_get(3, "IN", $filter, "", $page, $display_per_page);
                echo json_encode($data->result());            
            }

            if ($key == "total_souvenir") {
                $data = $this->pbs->tsouvenir_get(3, "IN", $filter, "", "");
                echo $data->num_rows();
            }

        } else {
            redirect(get_error_page());
        }
    }
}