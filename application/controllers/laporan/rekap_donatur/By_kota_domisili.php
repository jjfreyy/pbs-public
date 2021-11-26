<?php
class By_kota_domisili extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->lrekapan_donatur_get(3);
        $this->load->view("laporan/rekap_donatur/v_by_kota_domisili", $data);
    }

    function print_laporan() {
        if (isset($_GET["tgl1"]) && isset($_GET["tgl2"]) && isset($_GET["f"])) {
            $tgl1 = sanitize($this->input->get("tgl1"));
            $tgl2 = sanitize($this->input->get("tgl2"));
            $filter = sanitize($this->input->get("f"));
            $data = $this->pbs->lrekapan_donatur_get(4, $tgl1, $tgl2, $filter);
            if ($data->num_rows() > 0) {
                require_once(APPPATH. "libraries/tcpdf/pdf.php");
                $pdf = new PDF();

                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Laporan Rekapan Donatur Berdasarkan Kota Domisili", 0, 1, "C");
                $pdf->SetFont("cid0cs", "B", 9);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height(), get_period($tgl1, $tgl2), 0, 1, "C");
                
                $pdf->Ln(5);
                $t1_cell = array(
                    "width" => array(
                        $pdf->get_computed_width(.15), 
                        $pdf->get_computed_width(.35), 
                        $pdf->get_computed_width(.25), 
                        $pdf->get_computed_width(.25)),
                        "height" => array($pdf->get_cell_height() * 2, $pdf->get_cell_height()) 
                ); 
                
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Kode Donatur", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][0], "Nama Donatur", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][0], "Kota Domisili", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][3], $t1_cell["height"][0], "Jumlah Donasi", 1, 1, "C", TRUE);
                $pdf->SetFont("cid0cs", "", 9);
                $jumlah_donasi_per_kota = array();
                $total_donasi = 0;
                foreach ($data->result() as $row) {
                    $kode_donatur = $row->kode_donatur;
                    $nama_donatur = $row->nama_id;
                    $kota_domisili = $row->kota_domisili;
                    $jumlah_donasi = $row->jumlah_donasi;
                    $total_donasi += $jumlah_donasi;

                    if (array_key_exists(strtoupper($kota_domisili), $jumlah_donasi_per_kota)) {
                        $jumlah_donasi_per_kota[strtoupper($kota_domisili)] += $jumlah_donasi;
                    } else {
                        $jumlah_donasi_per_kota[strtoupper($kota_domisili)] = $jumlah_donasi;
                    }

                    $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], $kode_donatur, 1, 0, "C");
                    $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], $nama_donatur, 1, 0, "C");
                    $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], $kota_domisili, 1, 0, "C");
                    $pdf->cell($t1_cell["width"][3], $t1_cell["height"][1], convert_number_tocurrency($jumlah_donasi), 1, 1, "R");
                }

                $pdf->SetFont("cid0cs", "B", 11);
                $pdf->cell($t1_cell["width"][0] + $t1_cell["width"][1] + $t1_cell["width"][2], $t1_cell["height"][1], "Total", 1, 0, "R");
                $pdf->cell($t1_cell["width"][3], $t1_cell["height"][1], convert_number_tocurrency($total_donasi), 1, 0, "R");

                $pdf->Ln(10);
                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Total Donasi Per Kota Domisili", 0, 1, "C");

                $pdf->Ln(5);
                $pdf->SetFont("cid0cs", "B", 9);
                $t2_cell = array(
                    "width" => array($pdf->get_computed_width(.5), $pdf->get_computed_width(.5)),
                    "height" => array($pdf->get_cell_height() * 2, $pdf->get_cell_height())
                ); 
                
                $pdf->cell($t2_cell["width"][0], $t2_cell["height"][0], "Kota", 1, 0, "C", TRUE);
                $pdf->cell($t2_cell["width"][1], $t2_cell["height"][0], "Total", 1, 1, "C", TRUE);
                $pdf->SetFont("cid0cs", "", 9);
                foreach ($jumlah_donasi_per_kota as $kota => $total) {
                    $pdf->cell($t2_cell["width"][0], $t2_cell["height"][1], $kota, 1, 0, "C");
                    $pdf->cell($t2_cell["width"][1], $t2_cell["height"][1], convert_number_tocurrency($total), 1, 1, "R");
                }

                $pdf->Output("RekapanDonatur_" .format_date($tgl1). "_" .format_date($tgl2). ".pdf", "I");
            } else {
                echo "<script>alert('Data tidak dapat ditemukan.'); window.close();</script>";
            }
        } else {
            redirect(get_error_page());
        }
    }
}