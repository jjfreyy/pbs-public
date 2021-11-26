<?php
class Bulanan_detail extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->lhb_get(1);
        $this->load->view("laporan/paket/v_bulanan_detail", $data);
    }

    function print_laporan() {
        if (isset($_GET["tgl1"]) && isset($_GET["tgl2"]) && isset($_GET["mp"]) && isset($_GET["f"])) {
            $tgl1 = sanitize($this->input->get("tgl1"));
            $tgl2 = sanitize($this->input->get("tgl2"));
            $mp = sanitize($this->input->get("mp"));
            $f = sanitize($this->input->get("f"));
            $data = $this->pbs->lhb_get(5, $tgl1, $tgl2, $mp, $f);

            if ($data->num_rows() > 0) {
                require_once(APPPATH. "libraries/tcpdf/pdf.php");
                $pdf = new PDF(TRUE, TRUE, "L");

                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Laporan Bulanan Detail", 0, 1, "C");
                $pdf->SetFont("cid0cs", "B", 9);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height(), get_period($tgl1, $tgl2), 0, 1, "C");
                
                $pdf->SetFont("cid0cs", "", 9);
                $t1_cell = array(
                    "width" => array($pdf->get_computed_width(.334), $pdf->get_computed_width(.334), $pdf->get_computed_width(.334)),
                    "height" => array($pdf->get_cell_height() * 2, $pdf->get_cell_height())
                );
                for($i = 0; $i < $data->num_rows(); $i++) {
                    $row = $data->row($i);
                    if ($row->id == 0) {
                        $no = 1;
                        $kode_paket = $row->kode_paket;
                        $nama_paket = $row->nama_paket;
                        $nilai_paket = convert_number_tocurrency($row->nilai_paket);
                        $periode = $row->periode;
                        $total_donasi = convert_number_tocurrency($row->total_donasi);
                        $total_donasi_tunai = convert_number_tocurrency($row->total_donasi_tunai);
                        $total_donasi_transfer = convert_number_tocurrency($row->total_donasi_transfer);
                        $total = convert_number_tocurrency($row->total);
                        $total_tunai = convert_number_tocurrency($row->total_tunai);
                        $total_transfer = convert_number_tocurrency($row->total_transfer);
                        $total_transfer = convert_number_tocurrency($row->total_transfer);
                        $pembayaran_terakhir = format_date($row->pembayaran_terakhir);
                        
                        $pdf->Ln(5);
                        $pdf->draw_line();
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "Kode Paket: $kode_paket", 0, 0);
                        $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Nama Paket: $nama_paket", 0, 0);
                        $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], "Nilai Paket: $nilai_paket", 0, 1);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "Total Donasi: $total_donasi", 0, 0);
                        $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Donasi Tunai: $total_donasi_tunai", 0, 0);
                        $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], "Donasi Transfer: $total_donasi_transfer", 0, 1);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "Total: $total", 0, 0);
                        $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Total Tunai: $total_tunai", 0, 0);
                        $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], "Total Transfer: $total_transfer", 0, 1);
                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "Periode: $periode", 0, 0);
                        $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Pembayaran Terakhir: $pembayaran_terakhir", 0, 1);
                        $pdf->draw_line();
                        $pdf->Ln(2.5);

                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "Bulan", 1, 0, "C", TRUE);
                        $pdf->cell($t1_cell["width"][1], $t1_cell["height"][0], "Tunai", 1, 0, "C", TRUE);
                        $pdf->cell($t1_cell["width"][2], $t1_cell["height"][0], "Transfer", 1, 1, "C", TRUE);
                    } else {
                        $jumlah_donasi_tunai = convert_number_tocurrency($row->jumlah_donasi_tunai);
                        $jumlah_donasi_transfer = convert_number_tocurrency($row->jumlah_donasi_transfer);
                        $tgl_donasi = explode("-", $row->tgl_donasi);
                        $tgl_donasi = get_month_name($tgl_donasi[1]). " $tgl_donasi[0]"; 

                        $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], $tgl_donasi, 1, 0);
                        $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], $jumlah_donasi_tunai, 1, 0, "R");
                        $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], $jumlah_donasi_transfer, 1, 1, "R");
                    }
                }

                $pdf->Output("LaporanHarianDetail_" .format_date($tgl1). "_" .format_date($tgl2). ".pdf", "I");
            } else {
                echo "<script>alert('Data tidak dapat ditemukan.'); window.close();</script>";
            }
        } else {
            redirect(get_error_page());
        }
    }
}
