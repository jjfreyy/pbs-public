<?php
class Harian extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->lhb_get(1);
        $data["cbo_bank"] = $this->pbs->tbank_get(1, "id_bank, CONCAT(an, ' / ', no_rek) AS detail_bank");
        $this->load->view("laporan/penerimaan/v_harian", $data);
    }

    function print_laporan() {
        if (isset($_GET["tgl1"]) && isset($_GET["tgl2"]) && isset($_GET["idb"])) {
            $tgl1 = sanitize($this->input->get("tgl1"));
            $tgl2 = sanitize($this->input->get("tgl2"));
            $id_bank = sanitize($this->input->get("idb"));
            $data = $this->pbs->lhb_get(2, $tgl1, $tgl2, $id_bank);

            require_once(APPPATH. "libraries/tcpdf/pdf.php");
            $pdf = new PDF(TRUE, TRUE, "P", "A3");

            $pdf->SetFont("cid0cs", "B", 12);
            $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Laporan Donatur Harian", 0, 1, "C");

            $kode_bank = $data->row(0)->kode_bank;
            $tgl_terakhir_donatur = format_date($data->row(0)->tgl_terakhir_donatur);
            $tgl_terakhir_paket = format_date($data->row(0)->tgl_terakhir_paket);
            $tgl_terakhir_tunai = format_date($data->row(0)->tgl_terakhir_tunai);
            $tgl_terakhir_transfer = format_date($data->row(0)->tgl_terakhir_transfer);
            $tgl_terakhir = format_date($data->row(0)->tgl_terakhir);

            $t1_cell["height"] = array($pdf->get_cell_height() * 2, $pdf->get_cell_height());
            switch ($id_bank) {
                case "":
                    $width = $pdf->get_computed_width(100 / 8 / 100);
                    $t1_cell["width"] = array($width, $width, $width, $width, $width, array($width, 0), array($width, 0), $width);
                    break;
                case "0":
                    $width = $pdf->get_computed_width(100 / 6 / 100);
                    $t1_cell["width"] = array($width, $width, $width, $width, $width, array($width, 1), 0, 0);
                    break;
                default:
                    $width = $pdf->get_computed_width(100 / 6 / 100);
                    $t1_cell["width"] = array($width, $width, $width, $width, $width, 0, array($width, 1), 0); 
                    break;
            }
            
            $pdf->Ln(5);
            $pdf->SetFont("cid0cs", "B", 9);
            $pdf->SetTextColor(200, 100, 100);
            $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "Catatan Terakhir", 0, 0, "C");
            $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], $tgl_terakhir_donatur, 0, 0, "C");
            $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], $tgl_terakhir_paket, 0, 0, "C");
            $pdf->cell($t1_cell["width"][3], $t1_cell["height"][1], "", 0, 0, "C");
            $pdf->cell($t1_cell["width"][4], $t1_cell["height"][1], "", 0, 0, "C");
            if (is_empty($id_bank) || $id_bank == 0) {
                $pdf->cell($t1_cell["width"][5][0], $t1_cell["height"][1], $tgl_terakhir_tunai, 0, $t1_cell["width"][5][1], "C");
            }
            if (is_empty($id_bank) || $id_bank > 0) {
                $pdf->cell($t1_cell["width"][6][0], $t1_cell["height"][1], $tgl_terakhir_transfer, 0, $t1_cell["width"][6][1], "C");
            }
            if (is_empty($id_bank)) {
                $pdf->cell($t1_cell["width"][7], $t1_cell["height"][1], $tgl_terakhir, 0, 1, "C");
            }
            
            $pdf->Ln(2);
            $pdf->SetTextColor(0, 1, -1);
            $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Tanggal", 1, 0, "C", TRUE);
            $pdf->cell($t1_cell["width"][1], $t1_cell["height"][0], "Jumlah Donatur Daftar", 1, 0, "C", TRUE);
            $pdf->cell($t1_cell["width"][2], $t1_cell["height"][0], "Jumlah Paket Daftar", 1, 0, "C", TRUE);
            $pdf->cell($t1_cell["width"][3], $t1_cell["height"][0], "Jumlah Donatur Bayar", 1, 0, "C", TRUE);
            $pdf->cell($t1_cell["width"][4], $t1_cell["height"][0], "Jumlah Paket Bayar", 1, 0, "C", TRUE);
            if (is_empty($id_bank) || $id_bank == 0) {
                $pdf->cell($t1_cell["width"][5][0], $t1_cell["height"][0], "Tunai", 1, $t1_cell["width"][5][1], "C", TRUE);
            }
            if (is_empty($id_bank) || $id_bank > 0) {
                $pdf->cell($t1_cell["width"][6][0], $t1_cell["height"][0], $kode_bank, 1, $t1_cell["width"][6][1], "C", TRUE);
            }
            if (is_empty($id_bank)) {
                $pdf->cell($t1_cell["width"][7], $t1_cell["height"][0], "Tunai+$kode_bank", 1, 1, "C", TRUE);
            }

            $pdf->SetFont("cid0cs", "", 9);
            $j = 1;
            $total_donatur_daftar = $total_paket_daftar = $total_donatur_bayar = $total_paket_bayar = $total_tunai = $total_transfer = 0;
            for ($i = $tgl1; $i <= $tgl2; $i = date("Y-m-d", strtotime($i. " + 1 day"))) {
                $row = $data->row($j);
                $jumlah_donatur_daftar = $jumlah_paket_daftar = $jumlah_donatur_bayar = $jumlah_paket_bayar = $donasi_tunai = $donasi_transfer = $donasi_total = "-";
                if ($i === $row->tgl) {
                    $jumlah_donatur_daftar = $row->jumlah_donatur_daftar;
                    $jumlah_paket_daftar = $row->jumlah_paket_daftar;
                    $jumlah_donatur_bayar = $row->jumlah_donatur_bayar;
                    $jumlah_paket_bayar = $row->jumlah_paket_bayar;
                    $donasi_tunai = $row->donasi_tunai;
                    $donasi_transfer = $row->donasi_transfer;
                    $donasi_total = convert_number_tocurrency($donasi_tunai + $donasi_transfer);

                    $total_donatur_daftar += $jumlah_donatur_daftar;
                    $total_paket_daftar += $jumlah_paket_daftar;
                    $total_donatur_bayar += $jumlah_donatur_bayar;
                    $total_paket_bayar += $jumlah_paket_bayar;
                    $total_tunai += $donasi_tunai;
                    $total_transfer += $donasi_transfer;

                    $jumlah_donatur_daftar = convert_number_tocurrency($jumlah_donatur_daftar);
                    $jumlah_paket_daftar = convert_number_tocurrency($jumlah_paket_daftar);
                    $jumlah_donatur_bayar = convert_number_tocurrency($jumlah_donatur_bayar);
                    $jumlah_paket_bayar = convert_number_tocurrency($jumlah_paket_bayar);
                    $donasi_tunai = convert_number_tocurrency($donasi_tunai);
                    $donasi_transfer = convert_number_tocurrency($donasi_transfer);
                    $j++;
                }

                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], format_date($i), 1, 0, "C");
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], $jumlah_donatur_daftar, 1, 0, "R");
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], $jumlah_paket_daftar, 1, 0, "R");
                $pdf->cell($t1_cell["width"][3], $t1_cell["height"][1], $jumlah_donatur_bayar, 1, 0, "R");
                $pdf->cell($t1_cell["width"][4], $t1_cell["height"][1], $jumlah_paket_bayar, 1, 0, "R");
                if (is_empty($id_bank) || $id_bank == 0) {
                    $pdf->cell($t1_cell["width"][5][0], $t1_cell["height"][1], $donasi_tunai, 1, $t1_cell["width"][5][1], "R");
                }
                if (is_empty($id_bank) || $id_bank > 0) {
                    $pdf->cell($t1_cell["width"][6][0], $t1_cell["height"][1], $donasi_transfer, 1, $t1_cell["width"][6][1], "R");
                }
                if (is_empty($id_bank)) {
                    $pdf->cell($t1_cell["width"][7], $t1_cell["height"][1], $donasi_total, 1, 1, "R");
                }
            }

            $pdf->SetFont("cid0cs", "B", 9);
            $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "Total", 1, 0, "R");
            $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], convert_number_tocurrency($total_donatur_daftar), 1, 0, "R");
            $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($total_paket_daftar), 1, 0, "R");
            $pdf->cell($t1_cell["width"][3], $t1_cell["height"][1], convert_number_tocurrency($total_donatur_bayar), 1, 0, "R");
            $pdf->cell($t1_cell["width"][4], $t1_cell["height"][1], convert_number_tocurrency($total_paket_bayar), 1, 0, "R");
            if (is_empty($id_bank) || $id_bank === "0") {
                $pdf->cell($t1_cell["width"][5][0], $t1_cell["height"][1], convert_number_tocurrency($total_tunai), 1, $t1_cell["width"][5][1], "R");
            }
            if (is_empty($id_bank) || $id_bank > 0) {
                $pdf->cell($t1_cell["width"][6][0], $t1_cell["height"][1], convert_number_tocurrency($total_transfer), 1, $t1_cell["width"][6][1], "R");
            }
            if (is_empty($id_bank)) {
                $pdf->cell($t1_cell["width"][7], $t1_cell["height"][1], convert_number_tocurrency($total_tunai + $total_transfer), 1, 0, "R");
            }

            $pdf->Output("LaporanHarian_" .format_date($tgl1). "_" .format_date($tgl2). ".pdf", "I");
        } else {
            redirect(get_error_page());
        }
    }
}