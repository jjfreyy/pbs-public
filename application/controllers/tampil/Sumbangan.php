<?php
class Sumbangan extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->tsumbangan_get_month();
        $this->load->view("tampil/v_sumbangan", $data);
    }

    function print_sumbangan() {
        if (isset($_GET["id"])) {
            $sumbangan = $this->pbs->tsumbangan_get(1, sanitize($this->input->get("id")));
            if ($sumbangan->num_rows() > 0) {
                $no_kwitansi = $sumbangan->row()->no_kwitansi;
                $nama_penyumbang = $sumbangan->row()->nama_penyumbang;
                $kode_donatur = $sumbangan->row()->kode_donatur;
                $nama_donatur = $sumbangan->row()->nama_donatur;
                $nama_paket = $sumbangan->row()->nama_paket;
                $jumlah_donasi = convert_number_tocurrency($sumbangan->row()->jumlah_donasi);
                $tgl_donasi = format_date($sumbangan->row()->tgl_donasi);

                require_once(APPPATH. "libraries/tcpdf/pdf.php");
                $pdf = new PDF(FALSE, FALSE, "L", array(210, 99), array(1,0), 0);

                if (check_assets_file("img/frame.gif")) {
                    $pdf->Image("http://localhost/src/img/frame.gif", 0, 5, 60, 90);
                } else {
                    $pdf->Image(base_url("src/img/frame.gif"), 0, 5, 60, 90);
                }
                
                $pdf->SetFont("cid0cs", "B", 14);
                $pdf->SetXY(6, 6);
                $pdf->cell(50, $pdf->get_cell_height(), "YAYASAN", 0, 1, "C");
                $pdf->SetX(6);
                $pdf->cell(50, $pdf->get_cell_height(), "DUTA BAHAGIA", 0, 1, "C");
                $pdf->SetX(6);
                $pdf->cell(50, $pdf->get_cell_height(), "BERSAMA", 0, 1, "C");
                
                $pdf->SetFont("cid0cs", "", 10);
                $pdf->Ln(3);
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "JL. Residen H. Abdul Rozak", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "Lr. Sebatok No. 37B/2", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "8 Ilir, Ilir Timur 2", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "Palembang", 0, 1, "C");

                $pdf->SetFont("cid0cs", "B", 10);
                $pdf->Ln(8);
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "Rekening Donasi:", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "BCA", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "ac. 021 3 565688", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "BNI", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "ac. 888 201 9882", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "Mandiri", 0, 1, "C");
                $pdf->SetX(4);
                $pdf->cell(53.2, $pdf->get_cell_height(), "ac. 113 002 888 5881", 0, 1, "C");

                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->SetXY(60, 5);
                $pdf->cell(145, $pdf->get_cell_height(), "TANDA TERIMA DONASI", 0, 1, "C");
                $pdf->SetX(60);
                $pdf->cell(145, $pdf->get_cell_height(), "PEMBANGUNAN TAMAN EDUKASI DAN BUDAYA CINTA ALAM", 0, 1, "C");
                
                $pdf->SetFont("cid0cs", "", 11);
                $pdf->SetX(60);
                $pdf->cell(145 * .2, $pdf->get_cell_height() + 2.5, "No. Kwitansi", 0, 0);
                $pdf->cell(145 * .05, $pdf->get_cell_height() + 2.5, ":", 0, 0);
                $pdf->cell(145 * .75, $pdf->get_cell_height() + 2.5, $no_kwitansi, 0, 1);
                $pdf->Line(95, $pdf->GetY(), $pdf->GetPageWidth() - 5, $pdf->GetY());
                $pdf->SetX(60);
                $pdf->cell(145 * .2, $pdf->get_cell_height() + 2.5, "Telah terima dari", 0, 0);
                $pdf->cell(145 * .05, $pdf->get_cell_height() + 2.5, ":", 0, 0);
                $pdf->cell(145 * .75, $pdf->get_cell_height() + 2.5, $nama_penyumbang, 0, 1);
                $pdf->Line(95, $pdf->GetY(), $pdf->GetPageWidth() - 5, $pdf->GetY());
                $pdf->SetX(60);
                $pdf->cell(145 * .2, $pdf->get_cell_height() + 2.5, "No. Donatur", 0, 0);
                $pdf->cell(145 * .05, $pdf->get_cell_height() + 2.5, ":", 0, 0);
                $pdf->cell(145 * .75, $pdf->get_cell_height() + 2.5, $kode_donatur, 0, 1);
                $pdf->Line(95, $pdf->GetY(), $pdf->GetPageWidth() - 5, $pdf->GetY());
                $pdf->SetX(60);
                $pdf->cell(145 * .2, $pdf->get_cell_height() + 2.5, "Paket Donasi", 0, 0);
                $pdf->cell(145 * .05, $pdf->get_cell_height() + 2.5, ":", 0, 0);
                $pdf->cell(145 * .75, $pdf->get_cell_height() + 2.5, $nama_paket, 0, 1);
                $pdf->Line(95, $pdf->GetY(), $pdf->GetPageWidth() - 5, $pdf->GetY());
                $pdf->SetX(60);
                $pdf->cell(145 * .2, $pdf->get_cell_height() + 2.5, "Uang Senilai", 0, 0);
                $pdf->cell(145 * .05, $pdf->get_cell_height() + 2.5, ":", 0, 0);
                $pdf->cell(145 * .75, $pdf->get_cell_height() + 2.5, $jumlah_donasi, 0, 1);
                $pdf->Line(95, $pdf->GetY(), $pdf->GetPageWidth() - 5, $pdf->GetY());

                $pdf->SetXY(60, 55);
                $pdf->cell(145, $pdf->get_cell_height(), "Palembang, $tgl_donasi", 0, 1);
                $pdf->SetFont("cid0cs", "I", 10);
                $pdf->SetX(60);
                $pdf->cell(145, $pdf->get_cell_height(), "Bukti pembayaran elektronik ini adalah sah, dan diterbitkan langsung oleh Taman", 0, 1);
                $pdf->SetX(60);
                $pdf->cell(145, $pdf->get_cell_height(), "Edukasi Budaya Cinta Alam sebagai kwitansi resmi sehingga tidak memerlukan cap", 0, 1);
                $pdf->SetX(60);
                $pdf->cell(145, $pdf->get_cell_height(), "dan tanda tangan basah.", 0, 1);
                $pdf->SetFont("cid0cs", "BI", 9);
                $pdf->Ln(5);
                $pdf->SetX(60);
                $pdf->cell(145, $pdf->get_cell_height(), "Kami ucapkan terima kasih atas donasi Anda. Semoga Tuhan YME senantiasa memberkati", 0, 1, "C");
                $pdf->SetX(60);
                $pdf->cell(145, $pdf->get_cell_height(), "kesehatan, kebahagiaan, dan kesuksesan untuk Anda sekeluarga.", 0, 1, "C");

                $pdf->SetFont("Times", "I", 9);
                $pdf->SetY(-6);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height(), $this->session->username. ", " .date("H:i:s"), 0, 0, "R");

                $pdf->Output("$no_kwitansi-$nama_donatur.pdf", "I");
            } else {
                redirect(get_error_page());   
            }
        } else {
            redirect(get_error_page());
        }
    }

    /** ajax */
    function delete() {
        if (isset($_POST["key"]) && $_POST["key"] == "hapus_sumbangan") {
            if ($this->pbs->tuser_get(array(
                "select" => "username, lev",
                "filter" => array("username" => $this->session->username)
            ))->row()->lev != 1) {
                echo get_json_response("delete", "error", "sumbangan");
                return;
            }

            $id_sumbangan = sanitize($this->input->post("data")["id"]);
            if ($this->pbs->tsumbangan_delete($id_sumbangan)) {
                echo get_json_response("delete", "success", "sumbangan");
            } else {
                echo get_json_response("delete", "error", "sumbangan");
            }

        } else {    
            redirect(get_error_page());
        }
    }

    function get_sumbangan_list() {
        if (isset($_GET["key"])) {
            $key = $this->input->get("key");
            $data = $this->input->get("data");
            $filter = sanitize($data["filter"]);
            $tgl1 = sanitize($data["tgl1"]);
            $tgl2 = sanitize($data["tgl2"]);

            if ($key == "daftar_sumbangan") {
                $page = sanitize($data["page"]);
                $display_per_page = sanitize($data["display_per_page"]);
                $data = $this->pbs->tsumbangan_get(2, $filter, $tgl1, $tgl2, $page, $display_per_page);
                echo json_encode($data->result());
            }

            if ($key == "total_sumbangan") {
                $data = $this->pbs->tsumbangan_get(2, $filter, $tgl1, $tgl2, "");
                echo $data->num_rows();
            }

        } else {
            redirect(get_error_page());
        }
    }

    function get_sumbangan1_list() {
        if (isset($_GET["key"]) && $_GET["key"] == "daftar_sumbangan1") {
            $id_sumbangan = sanitize($this->input->get("data")["id"]);
            $data = $this->pbs->tsumbangan1_get(2, $id_sumbangan);
            echo json_encode($data->result());
        } else {
            redirect(get_error_page());
        }
    }
}