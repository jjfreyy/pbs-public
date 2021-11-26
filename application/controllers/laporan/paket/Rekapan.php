<?php
class Rekapan extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->lpaket_rekapan_get(1);
        $data["daftar_bank"] = $this->pbs->tbank_get(1, "id_bank, CONCAT(nama_bank, ' / ', an) AS nama_bank");
        $this->load->view("laporan/paket/v_rekapan", $data);
    }

    function print_laporan() {
        if (isset($_GET["tgl1"]) && isset($_GET["tgl2"]) && isset($_GET["f"])) {
            $tgl1 = sanitize($this->input->get("tgl1"));
            $tgl2 = sanitize($this->input->get("tgl2"));
            $f = sanitize($this->input->get("f"));
            $data = $this->pbs->lpaket_rekapan_get(2, $tgl1, $tgl2, $f);
            if ($data->num_rows() > 0) {
                require_once(APPPATH. "libraries/tcpdf/pdf.php");
                $pdf = new PDF(TRUE, TRUE, "L");

                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Laporan Rekapan Paket", 0, 1, "C");
                $pdf->SetFont("cid0cs", "B", 9);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height(), get_period($tgl1, $tgl2), 0, 1, "C");
                
                $pdf->Ln(5);
                $t1_cell = array(
                    "width" => array(
                        $pdf->get_computed_width(.05), 
                        $pdf->get_computed_width(.1), 
                        $pdf->get_computed_width(.2), 
                        $pdf->get_computed_width(.1), 
                        $pdf->get_computed_width(.1), 
                        $pdf->get_computed_width(.1), 
                        $pdf->get_computed_width(.1),
                        $pdf->get_computed_width(.125), 
                        $pdf->get_computed_width(.125)), 
                    "height" => array($pdf->get_cell_height() * 2, $pdf->get_cell_height()),
                );
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "No.", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][0], "Kode Paket", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][0], "Nama Paket", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][3], $t1_cell["height"][0], "Nilai Paket", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][4], $t1_cell["height"][0], "Periode", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][5], $t1_cell["height"][0], "Jumlah Paket", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][6], $t1_cell["height"][0], "Total Nilai Paket", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][7], $t1_cell["height"][0], "Total Donasi", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][8], $t1_cell["height"][0], "Sisa", 1, 1, "C", TRUE);
                
                $jumlah_paket_keseluruhan = 0;
                $total_nilai_paket_keseluruhan = 0;
                $total_donasi_keseluruhan = 0;
                $sisa_keseluruhan = 0;
                $pdf->SetFont("cid0cs", "", 9);
                for ($i = 0; $i < $data->num_rows(); $i++) {
                    $row = $data->row($i);
                    $no = $i + 1;
                    $kode_paket = $row->kode_paket;
                    $nama_paket = $row->nama_paket;
                    $nilai_paket = $row->nilai_paket;
                    $periode = $row->periode === "-" ? "-" : explode(" ", $row->periode);
                    if ($periode !== "-") {
                        switch ($periode[1]) {
                            case "H": $periode = "$periode[0] Hari"; break;
                            case "B": $periode = "$periode[0] Bulan"; break;
                            case "T": $periode = "$periode[0] Tahun"; break;
                            default: $periode = "$periode[0] ?"; break;
                        }
                    }
                    $jumlah_paket = $row->jumlah_paket;
                    $total_nilai_paket = $row->total_nilai_paket;
                    $total_donasi = $row->total_donasi;
                    $sisa = $row->sisa;

                    $jumlah_paket_keseluruhan += $jumlah_paket;
                    $total_nilai_paket_keseluruhan += $total_nilai_paket === "-" ? 0 : $total_nilai_paket;
                    $total_donasi_keseluruhan += $total_donasi;
                    $sisa_keseluruhan += $sisa === "-" ? 0 : $sisa;

                    $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], $no, 1, 0);
                    $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], $kode_paket, 1, 0);
                    $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], $nama_paket, 1, 0);
                    $pdf->cell($t1_cell["width"][3], $t1_cell["height"][1], convert_number_tocurrency($nilai_paket), 1, 0, "R");
                    $pdf->cell($t1_cell["width"][4], $t1_cell["height"][1], $periode, 1, 0);
                    $pdf->cell($t1_cell["width"][5], $t1_cell["height"][1], convert_number_tocurrency($jumlah_paket), 1, 0, "R");
                    $pdf->cell($t1_cell["width"][6], $t1_cell["height"][1], convert_number_tocurrency($total_nilai_paket), 1, 0, "R");
                    $pdf->cell($t1_cell["width"][7], $t1_cell["height"][1], convert_number_tocurrency($total_donasi), 1, 0, "R");
                    $pdf->cell($t1_cell["width"][8], $t1_cell["height"][1], convert_number_tocurrency($sisa), 1, 1, "R");
                }

                $pdf->SetFont("cid0cs", "B", 10);
                $cell_temp1 = $t1_cell["width"][0] + $t1_cell["width"][1] + $t1_cell["width"][2] + $t1_cell["width"][3] + $t1_cell["width"][4] + 
                $t1_cell["width"][5];
                $cell_temp2 = $t1_cell["width"][6] + $t1_cell["width"][7];
                $cell_temp3 = $t1_cell["width"][8];
                $t1_cell["width"] = array($cell_temp1, $cell_temp2, $cell_temp3);
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Jumlah Paket Keseluruhan", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($jumlah_paket_keseluruhan), "RLB", 1, "R");
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Total Nilai Paket Keseluruhan", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($total_nilai_paket_keseluruhan), "RLB", 1, "R");
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Total Donasi Keseluruhan", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($total_donasi_keseluruhan), "RLB", 1, "R");
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Sisa Keseluruhan", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($sisa_keseluruhan), "RLB", 1, "R");

                $pdf->Output("LaporanRekapanPaket_" .format_date($tgl1). "_" .format_date($tgl2). ".pdf", "I");
            } else {
                echo "<script>alert('Data tidak dapat ditemukan.'); window.close();</script>";
            }
        } else {
            redirect(get_error_page());
        }
    }
}