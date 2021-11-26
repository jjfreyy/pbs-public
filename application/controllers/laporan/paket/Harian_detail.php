<?php
class Harian_detail extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }

    function index() {
        $data["cbo_bulan"] = $this->pbs->lhb_get(1);
        $data["daftar_bank"] = $this->pbs->tbank_get(1, "id_bank, CONCAT(nama_bank, ' / ', an) AS nama_bank");
        $this->load->view("laporan/paket/v_harian_detail", $data);
    }

    function print_laporan() {
        if (isset($_GET["tgl1"]) && isset($_GET["tgl2"]) && isset($_GET["mp"]) && isset($_GET["f"])) {
            $tgl1 = sanitize($this->input->get("tgl1"));
            $tgl2 = sanitize($this->input->get("tgl2"));
            $mp = sanitize($this->input->get("mp"));
            $f = sanitize($this->input->get("f"));
            $data = $this->pbs->lhb_get(4, $tgl1, $tgl2, $mp, $f);

            if ($data->num_rows() > 0) {
                require_once(APPPATH. "libraries/tcpdf/pdf.php");
                $pdf = new PDF(TRUE, TRUE, "L", "A3");

                $pdf->SetFont("cid0cs", "B", 12);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height() + 2.5, "Laporan Harian Detail", 0, 1, "C");
                $pdf->SetFont("cid0cs", "B", 9);
                $pdf->cell($pdf->GetPageWidth(), $pdf->get_cell_height(), get_period($tgl1, $tgl2), 0, 1, "C");
                
                $pdf->Ln(5);
                $t1_cell = array(
                    "width" => array(
                        $pdf->get_computed_width(.05), 
                        $pdf->get_computed_width(.05), 
                        $pdf->get_computed_width(.2), 
                        $pdf->get_computed_width(.06), 
                        $pdf->get_computed_width(.13), 
                        $pdf->get_computed_width(.06), 
                        $pdf->get_computed_width(.05),
                        $pdf->get_computed_width(.15), 
                        $pdf->get_computed_width(.25)),
                    "height" => array($pdf->get_cell_height() * 2, $pdf->get_cell_height()),
                );
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][0], "No. Kwitansi", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][0], "Kode Donatur", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][0], "Nama Donatur", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][3], $t1_cell["height"][0], "Tanggal Donasi", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][4], $t1_cell["height"][0], "Nama Paket", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][5], $t1_cell["height"][0], "Jumlah Donasi", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][6], $t1_cell["height"][0], "Via", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][7], $t1_cell["height"][0], "Bank", 1, 0, "C", TRUE);
                $pdf->cell($t1_cell["width"][8], $t1_cell["height"][0], "Keterangan", 1, 1, "C", TRUE);
                
                $unique_donatur_list = array();
                $unique_paket_list = array();
                $total_donasi = 0;
                $total_tunai = 0;
                $total_transfer = 0;
                $pdf->SetFont("cid0cs", "", 9);
                foreach ($data->result() as $row) {
                    $no_kwitansi = $row->no_kwitansi;
                    $kode_donatur = $row->kode_donatur;
                    $nama_donatur = $row->nama_donatur;
                    $tgl_donasi = format_date($row->tgl_donasi);
                    $nama_paket = $row->nama_paket;
                    $jumlah_donasi = convert_number_tocurrency($row->jumlah_donasi);
                    $metode_pembayaran = $row->metode_pembayaran;
                    $bank = $row->bank;
                    $ket = is_empty($row->ket) ? "-" : $row->ket;

                    if (!in_array($kode_donatur, $unique_donatur_list)) $unique_donatur_list[] = $kode_donatur;
                    if (!in_array($nama_paket, $unique_paket_list)) $unique_paket_list[] = $nama_paket;
                    $total_donasi += $row->jumlah_donasi;
                    if ($metode_pembayaran === "Tunai") $total_tunai++;
                    if ($metode_pembayaran === "Transfer") $total_transfer++;

                    $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], $no_kwitansi, 1, 0);
                    $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], $kode_donatur, 1, 0);
                    $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], $nama_donatur, 1, 0);
                    $pdf->cell($t1_cell["width"][3], $t1_cell["height"][1], $tgl_donasi, 1, 0);
                    $pdf->cell($t1_cell["width"][4], $t1_cell["height"][1], $nama_paket, 1, 0);
                    $pdf->cell($t1_cell["width"][5], $t1_cell["height"][1], $jumlah_donasi, 1, 0, "R");
                    $pdf->cell($t1_cell["width"][6], $t1_cell["height"][1], $metode_pembayaran, 1, 0);
                    $pdf->cell($t1_cell["width"][7], $t1_cell["height"][1], $bank, 1, 0);
                    $pdf->cell($t1_cell["width"][8], $t1_cell["height"][1], $ket, 1, 1);
                }

                $pdf->SetFont("cid0cs", "B", 11);
                $cell_temp1 = $t1_cell["width"][0] + $t1_cell["width"][1] + $t1_cell["width"][2] + $t1_cell["width"][3] + $t1_cell["width"][4] + 
                $t1_cell["width"][5] + $t1_cell["width"][6];
                $cell_temp2 = $t1_cell["width"][7];
                $cell_temp3 = $t1_cell["width"][8];
                $t1_cell["width"] = array($cell_temp1, $cell_temp2, $cell_temp3);
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Jumlah Donatur", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency(count($unique_donatur_list)), "RLB", 1, "R");
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Jumlah Paket", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency(count($unique_paket_list)), "RLB", 1, "R");
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Total Donasi", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($total_donasi), "RLB", 1, "R");
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Total Tunai", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($total_tunai), "RLB", 1, "R");
                $pdf->cell($t1_cell["width"][0], $t1_cell["height"][1], "", "LB", 0);
                $pdf->cell($t1_cell["width"][1], $t1_cell["height"][1], "Total Transfer", "RB", 0);
                $pdf->cell($t1_cell["width"][2], $t1_cell["height"][1], convert_number_tocurrency($total_transfer), "RLB", 1, "R");

                $pdf->Output("LaporanHarianDetail_" .format_date($tgl1). "_" .format_date($tgl2). ".pdf", "I");
            } else {
                echo "<script>alert('Data tidak dapat ditemukan.'); window.close();</script>";
            }
        } else {
            redirect(get_error_page());
        }
    }
}