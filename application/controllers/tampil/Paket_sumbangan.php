<?php
class Paket_sumbangan extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->tpaket_sumbangan_get_month();
        $this->load->view("tampil/v_paket_sumbangan", $data);
    }

    function print_paket_sumbangan() {
        if (isset($_GET["id"])) {
            $paket_sumbangan = $this->pbs->tpaket_sumbangan_get(4, sanitize($this->input->get("id")));
            if ($paket_sumbangan->num_rows() > 0) {
                $id_paket_sumbangan = $paket_sumbangan->row()->id_paket_sumbangan;
                $nama_paket = $paket_sumbangan->row()->nama_paket;
                $id_paket_sumbangan = strlen($id_paket_sumbangan) > 5 ? $id_paket_sumbangan : substr("00000$id_paket_sumbangan", -5);
                $kode_kolektor = $paket_sumbangan->row()->kode_kolektor;
                $nama_kolektor = $paket_sumbangan->row()->nama_kolektor;
                $kode_donatur = $paket_sumbangan->row()->kode_donatur;
                $alamat = $paket_sumbangan->row()->alamat;
                $total_donasi = convert_number_tocurrency($paket_sumbangan->row()->total_donasi);
                $nama_donatur = $paket_sumbangan->row()->nama_donatur;
                $jumlah_biaowen = $paket_sumbangan->row()->jumlah_biaowen;
                $sisa = ($paket_sumbangan->row()->sisa == 0) ? "-" : convert_number_tocurrency($paket_sumbangan->row()->sisa);
                $kota_domisili = $paket_sumbangan->row()->kota_domisili;
                $nilai_paket = $paket_sumbangan->row()->nilai_paket == 0 ? "-" : convert_number_tocurrency($paket_sumbangan->row()->nilai_paket);
                $pembayaran_terakhir = is_empty($paket_sumbangan->row()->pembayaran_terakhir) ? "-" : format_date($paket_sumbangan->row()->pembayaran_terakhir);

                require_once(APPPATH. "libraries/tcpdf/pdf.php");
                $pdf = new PDF();

                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->cell(0, $pdf->get_cell_height() + 2.5, $nama_paket, 0, 1, "C");
                $pdf->draw_line();

                $pdf->SetFont("cid0cs", "", 9);
                $t1_cell_width = 0.334;
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Kode Kolektor: $kode_kolektor", 0, 0);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Nama Kolektor: $nama_kolektor", 0, 1);
                $pdf->draw_line();

                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Kode Donatur: $kode_donatur", 0, 0);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Alamat: $alamat", 0, 0);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Total Donasi: $total_donasi", 0, 1);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Nama Donatur: $nama_donatur", 0, 0);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Jumlah Biaowen: $jumlah_biaowen", 0, 0);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Sisa: $sisa", 0, 1);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Kota Domisili: $kota_domisili", 0, 0);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Nilai Paket: $nilai_paket", 0, 0);
                $pdf->cell($pdf->get_computed_width($t1_cell_width), $pdf->get_cell_height(), "Pembayaran Terakhir: $pembayaran_terakhir", 0, 1);
                $pdf->draw_line();

                $t2_cell_width = 0.25;
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Daftar Biaowen", 0, 1, "C");
                $biaowen_list = explode("#", $paket_sumbangan->row()->biaowen_list);
                for ($i = 0; $i < count($biaowen_list); $i++) {
                    $pdf->cell($pdf->get_computed_width($t2_cell_width), $pdf->get_cell_height(), ($i+1). ". $biaowen_list[$i]", 1, (($i + 1) % 4 === 0) ? 1 : 0, "L", TRUE);
                }
                $pdf->Ln($pdf->get_cell_height());
                if (count($biaowen_list) % 4 !== 0) $pdf->Ln($pdf->get_cell_height());
                
                $t3_cell1_width = .14;
                $t3_cell2_width = .14;
                $t3_cell3_width = .2;
                $t3_cell4_width = .1;
                $t3_cell5_width = .42;
                $pdf->cell($pdf->get_computed_width($t3_cell1_width), $pdf->get_cell_height() + 2.5, "Tanggal Donasi", 1, 0, "C", TRUE);
                $pdf->cell($pdf->get_computed_width($t3_cell2_width), $pdf->get_cell_height() + 2.5, "No. Kwitansi", 1, 0, "C", TRUE);
                $pdf->cell($pdf->get_computed_width($t3_cell3_width), $pdf->get_cell_height() + 2.5, "Jumlah Donasi", 1, 0, "C", TRUE);
                $pdf->cell($pdf->get_computed_width($t3_cell4_width), $pdf->get_cell_height() + 2.5, "Via", 1, 0, "C", TRUE);
                $pdf->cell($pdf->get_computed_width($t3_cell5_width), $pdf->get_cell_height() + 2.5, "Keterangan", 1, 1, "C", TRUE);
                for ($i = 1; $i < $paket_sumbangan->num_rows(); $i++) {
                    $pdf->cell($pdf->get_computed_width($t3_cell1_width), $pdf->get_cell_height(), format_date($paket_sumbangan->row($i)->tgl_donasi), "BL");
                    $pdf->cell($pdf->get_computed_width($t3_cell2_width), $pdf->get_cell_height(), $paket_sumbangan->row($i)->no_kwitansi, "BL");
                    $pdf->cell($pdf->get_computed_width($t3_cell3_width), $pdf->get_cell_height(), convert_number_tocurrency($paket_sumbangan->row($i)->jumlah_donasi), "BL", 0, "R");
                    $pdf->cell($pdf->get_computed_width($t3_cell4_width), $pdf->get_cell_height(), $paket_sumbangan->row($i)->metode_pembayaran, "BL");
                    $pdf->cell($pdf->get_computed_width($t3_cell5_width), $pdf->get_cell_height(), is_empty($paket_sumbangan->row($i)->ket) ? "-" : $paket_sumbangan->row($i)->ket, "RBL", 1);
                }

                $pdf->output("PS-$id_paket_sumbangan.pdf", "I");
            } else {
                redirect(get_error_page());
            }
        } else {
            redirect(get_error_page());
        }
    }

    /** ajax */
    function delete() {
        if (isset($_POST["key"]) && $_POST["key"] == "hapus_paket_sumbangan") {
            if ($this->pbs->tuser_get(array(
                "select" => "username, lev",
                "filter" => array("username" => $this->session->username)
            ))->row()->lev != 1) {
                echo get_json_response("delete", "error", "paket sumbangan");
                return;
            }

            $id_paket_sumbangan = sanitize($this->input->post("data")["id"]);
            $result = $this->pbs->tsumbangan_get(5, "id_paket_sumbangan", array("id_paket_sumbangan", $id_paket_sumbangan))->num_rows();
            if ($result > 0) {
                echo get_json_response("custom", "error", "Gagal menghapus paket sumbangan.<br>Paket sumbangan telah terdaftar dalam sumbangan.");
                return;
            }

            if ($this->pbs->tpaket_sumbangan_delete($id_paket_sumbangan)) {
                echo get_json_response("delete", "success", "paket_sumbangan");
            } else {
                echo get_json_response("delete", "error", "paket sumbangan");
            }

        } else {
            redirect(get_error_page());
        }
    }

    function get_paket_sumbangan_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $filter = sanitize($data["filter"]);
            $lunas = sanitize($data["lunas"]);
            $tgl1 = sanitize($data["tgl1"]);
            $tgl2 = sanitize($data["tgl2"]);

            if ($key == "daftar_paket_sumbangan") {
                $page = sanitize($data["page"]);
                $display_per_page = sanitize($data["display_per_page"]);
                $data = $this->pbs->tpaket_sumbangan_get(2, $filter, $lunas, $tgl1, $tgl2, $page, $display_per_page);
                echo json_encode($data->result());
            }
            
            if ($key == "total_paket_sumbangan") {
                $data = $this->pbs->tpaket_sumbangan_get(2, $filter, $lunas, $tgl1, $tgl2, "")->num_rows();
                echo $data;
            }

        } else {
            redirect(get_error_page());
        }
    }
}