<?php
class Biaowen extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $this->load->view("daftar/v_biaowen");
    }

    function bakar_biaowen() {
        if (isset($_POST["key"]) && $_POST["key"] == "bakar_biaowen") {
            $this->pbs->trans_begin();
            $biaowen_list = $this->input->post("data");
            if (is_empty_array($biaowen_list)) {
                echo get_json_response("custom", "error", "Silakan pilih biaowen yg ingin dibakar.");
            } else {
                foreach ($biaowen_list as $biaowen) {
                    $no = $biaowen["no"];
                    $id_sumbangan = sanitize($biaowen["id_sumbangan"]);
                    $biaowen = sanitize($biaowen["biaowen"]);
                    $result = $this->pbs->tbiaowen_get(1, $id_sumbangan, $biaowen);
                    if ($result->num_rows() === 0) {
                        $error = "Gagal membakar biaowen.<br>$no. Biaowen $biaowen belum terdaftar.";
                    } else if ($result->row()->lunas != 1) {
                        $error = "Gagal membakar biaowen.<br>$no. Status biaowen $biaowen belum lunas.";
                    } else {
                        $success[] = "$no. Berhasil membakar biaowen $biaowen.<br>";
                    }

                    if (isset($error)) {
                        break;
                    } else {
                        $this->pbs->tbiaowen_update($id_sumbangan, $biaowen);
                    }
                }

                if (isset($error)) {
                    $this->pbs->rollback();
                    echo get_json_response("custom", "error", $error);
                } else {
                    $this->pbs->commit();
                    echo get_json_response("custom", "success", implode("", $success));
                }
            }
        } else {
            redirect(get_error_page());
        }
    }

    function get_biaowen_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $nama_paket = sanitize($data["nama_paket"]);
            $biaowen = sanitize($data["biaowen"]);
            $lunas = sanitize($data["lunas"]);
            $bakar = sanitize($data["bakar"]);

            switch ($key) {
                case "daftar_biaowen":
                    $page = sanitize($data["page"]);
                    $display_per_page = sanitize($data["display_per_page"]);
                    $data = $this->pbs->tbiaowen_get(2, $nama_paket, $biaowen, $lunas, $bakar, $page, $display_per_page);
                    echo json_encode($data->result());
                    break;
                case "total_biaowen":
                    $data = $this->pbs->tbiaowen_get(2, $nama_paket, $biaowen, $lunas, $bakar, "");
                    echo $data->num_rows();
                    break;
                default:
                    redirect(get_error_page());
            }

        } else {
            redirect(get_error_page());
        }
    }
}