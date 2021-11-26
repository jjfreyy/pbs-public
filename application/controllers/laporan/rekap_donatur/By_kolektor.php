<?php
class By_kolektor extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->lrekapan_donatur_get(1);
        $this->load->view("laporan/rekap_donatur/v_by_kolektor", $data);
    }

    function print_laporan() {
        if (isset($_GET["tgl1"]) && isset($_GET["tgl2"]) && isset($_GET["k"]) && isset($_GET["d"]) && isset($_GET["l"])) {
            $tgl1 = sanitize($this->input->get("tgl1"));
            $tgl2 = sanitize($this->input->get("tgl2"));
            $paket = sanitize($this->input->get("p"));
            $donatur = sanitize($this->input->get("d"));
            $kolektor = sanitize($this->input->get("k"));
            $lunas = sanitize($this->input->get("l"));
            $data = $this->pbs->lrekapan_donatur_get(2, $tgl1, $tgl2, $paket, $donatur, $kolektor, $lunas);
            // print_r($data);
            // return;
            if ($data->num_rows() > 0) {
                require_once(APPPATH. "libraries/tcpdf/pdf.php");
                $pdf = new PDF(TRUE, TRUE, "P", "A3");

                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Laporan Rekapan Donatur Berdasarkan Kolektor", 0, 1, "C");
                $pdf->SetFont("cid0cs", "B", 9);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height(), get_period($tgl1, $tgl2), 0, 1, "C");
                
                $t1_cell = array(
                    "width" => array($pdf->get_computed_width(.334)),
                    "height" => array($pdf->get_cell_height())
                );
                $t2_cell = array(
                    "width" => array(
                        $pdf->get_computed_width(.03),
                        $pdf->get_computed_width(.07),
                        $pdf->get_computed_width(.27),
                        $pdf->get_computed_width(.1),
                        $pdf->get_computed_width(.08),
                        $pdf->get_computed_width(.05),
                        $pdf->get_computed_width(.08),
                        $pdf->get_computed_width(.08),
                        $pdf->get_computed_width(.08),
                        $pdf->get_computed_width(.08),
                        $pdf->get_computed_width(.08)),
                    "height" => array($pdf->get_cell_height() * 2, $pdf->get_cell_height())
                );
                for($i = 0; $i < $data->num_rows(); $i++) {
                    $row = $data->row($i);
                    if (!is_empty($row->kode_kolektor)) {
                        $no_donatur = 1;
                        $kode_kolektor = $row->kode_kolektor;
                        $jumlah_donatur = convert_number_tocurrency($row->jumlah_donatur);
                        $total_nilai_paket_k = convert_number_tocurrency($row->total_nilai_paket_k);
                        $nama_kolektor = $row->nama_kolektor;
                        $jumlah_paket_k = convert_number_tocurrency($row->jumlah_paket_k);
                        $sisa_k = convert_number_tocurrency($row->sisa_k);

                        $pdf->Ln(5);
                        $pdf->draw_line();
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Kode Kolektor: $kode_kolektor", 0, 0);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Jumlah Donatur: $jumlah_donatur", 0, 0);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Nilai Paket: $total_nilai_paket_k", 0, 1);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Nama Kolektor: $nama_kolektor", 0, 0);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Jumlah Paket: $jumlah_paket_k", 0, 0);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Sisa: $sisa_k", 0, 1);
                        $pdf->draw_line();
                        $pdf->Ln(2.5);
                        
                        $pdf->SetFont("cid0cs", "B", 9);
                        $pdf->cell($t2_cell["width"][0], $t2_cell["height"][0], "No.", 1, 0, "C", TRUE);
                        $pdf->cell($t2_cell["width"][1], $t2_cell["height"][0], "Kode Donatur", 1, 0, "C", TRUE);
                        $pdf->cell($t2_cell["width"][2], $t2_cell["height"][0], "Nama Donatur", 1, 0, "C", TRUE);
                        $pdf->cell($t2_cell["width"][3], $t2_cell["height"][0], "No. HP", 1, 0, "C", TRUE);
                        
                        $pdf->cell($t2_cell["width"][4], $t2_cell["height"][1], "Tanggal", "TRL", 0, "C", TRUE);
                        $pdf->SetXY($t2_cell["width"][0] + $t2_cell["width"][1] + $t2_cell["width"][2] + $t2_cell["width"][3] + 1, 
                        $pdf->GetY() + $t2_cell["height"][1]);
                        $pdf->cell($t2_cell["width"][4], $t2_cell["height"][1], "Gabung", "RBL", 0, "C", TRUE);
                        $pdf->SetXY($t2_cell["width"][0] + $t2_cell["width"][1] + $t2_cell["width"][2] + $t2_cell["width"][3] + $t2_cell["width"][4] + 1, 
                        $pdf->GetY() - $t2_cell["height"][1]);
                        
                        $pdf->cell($t2_cell["width"][5], $t2_cell["height"][1], "Jumlah", "TRL", 0, "C", TRUE);
                        $pdf->SetXY($t2_cell["width"][0] + $t2_cell["width"][1] + $t2_cell["width"][2] + $t2_cell["width"][3] + $t2_cell["width"][4] + 1, 
                        $pdf->GetY() + $t2_cell["height"][1]);
                        $pdf->cell($t2_cell["width"][5], $t2_cell["height"][1], "Paket", "RBL", 0, "C", TRUE);
                        $pdf->SetXY($t2_cell["width"][0] + $t2_cell["width"][1] + $t2_cell["width"][2] + $t2_cell["width"][3] + $t2_cell["width"][4] +
                        $t2_cell["width"][5] + 1, $pdf->GetY() - $t2_cell["height"][1]);
                        
                        $pdf->cell($t2_cell["width"][6], $t2_cell["height"][0], "Nilai Paket", 1, 0, "C", TRUE);
                        $pdf->cell($t2_cell["width"][7], $t2_cell["height"][0], "Total Donasi", 1, 0, "C", TRUE);
                        $pdf->cell($t2_cell["width"][8], $t2_cell["height"][0], "Sisa", 1, 0, "C", TRUE);
                        $pdf->cell($t2_cell["width"][9], $t2_cell["height"][0], "Terakhir", 1, 0, "C", TRUE);
                        $pdf->cell($t2_cell["width"][10], $t2_cell["height"][0], "Jatuh Tempo", 1, 1, "C", TRUE);
                        $pdf->SetFont("cid0cs", "", 9);
                    } else {
                        $kode_donatur = $row->kode_donatur;
                        $nama_donatur = $row->nama_donatur;
                        $kota_domisili = $row->kota_domisili;
                        $no_hp1 = $row->no_hp1;
                        $tgl_gabung = format_date($row->tgl_gabung);
                        $jumlah_paket_d = $row->jumlah_paket_d;
                        $total_nilai_paket_d = convert_number_tocurrency($row->total_nilai_paket_d);
                        $total_donasi_d = convert_number_tocurrency($row->total_donasi_d);
                        $sisa_d = convert_number_tocurrency($row->sisa_d);
                        $pembayaran_terakhir = format_date($row->pembayaran_terakhir);
                        $tgl_jatuh_tempo = format_date($row->tgl_jatuh_tempo);

                        $pdf->cell($t2_cell["width"][0], $t2_cell["height"][1], $no_donatur++, 1, 0);
                        $pdf->cell($t2_cell["width"][1], $t2_cell["height"][1], $kode_donatur, 1, 0);
                        $pdf->cell($t2_cell["width"][2], $t2_cell["height"][1], $nama_donatur, 1, 0);
                        $pdf->cell($t2_cell["width"][3], $t2_cell["height"][1], $no_hp1, 1, 0);
                        $pdf->cell($t2_cell["width"][4], $t2_cell["height"][1], $tgl_gabung, 1, 0);
                        $pdf->cell($t2_cell["width"][5], $t2_cell["height"][1], $jumlah_paket_d, 1, 0, "R");
                        $pdf->cell($t2_cell["width"][6], $t2_cell["height"][1], $total_nilai_paket_d, 1, 0, "R");
                        $pdf->cell($t2_cell["width"][7], $t2_cell["height"][1], $total_donasi_d, 1, 0, "R");
                        $pdf->cell($t2_cell["width"][8], $t2_cell["height"][1], $sisa_d, 1, 0, "R");
                        $pdf->cell($t2_cell["width"][9], $t2_cell["height"][1], $pembayaran_terakhir, 1, 0);
                        $pdf->cell($t2_cell["width"][10], $t2_cell["height"][1], $tgl_jatuh_tempo, 1, 1);
                    }
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