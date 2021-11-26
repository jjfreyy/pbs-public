<?php
class Sumbangan extends CI_Controller {
    function __construct() {
        parent::__construct();
        check_session();
    }
    
    function index() {
        if (isset($_GET["id"])) {
            $sumbangan = $this->pbs->tsumbangan_get(3, sanitize($this->input->get("id")));
            if (isset($sumbangan->row()->id_sumbangan)) {
                $id_sumbangan = $sumbangan->row()->id_sumbangan;
                $no_kwitansi = $sumbangan->row()->no_kwitansi;
                $kode_donatur = $sumbangan->row()->kode_donatur;
                $nama_donatur = $sumbangan->row()->nama_donatur;
                $nama_penyumbang = $sumbangan->row()->nama_penyumbang;
                $tgl_donasi = $sumbangan->row()->tgl_donasi;
                $id_paket_sumbangan = $sumbangan->row()->id_paket_sumbangan;
                $nama_paket = $id_paket_sumbangan."#".$sumbangan->row()->nama_paket;
                $sisa_nilai_paket = !isset($sumbangan->row()->sisa_nilai_paket) ? "∞" : 
                convert_number_tocurrency($sumbangan->row()->sisa_nilai_paket - $sumbangan->row()->jumlah_donasi);
                $jumlah_donasi = convert_number_tocurrency($sumbangan->row()->jumlah_donasi);
                $sisa_nilai_paket1 = $sumbangan->row()->sisa_nilai_paket;
                $metode_pembayaran = $sumbangan->row()->metode_pembayaran;
                $id_bank = $sumbangan->row()->id_bank;
                $bank = isset($sumbangan->row()->an) ? $sumbangan->row()->an. " / " .$sumbangan->row()->no_rek : "";
                $rek_pengirim = $sumbangan->row()->rek_pengirim;
                $ket_sumbangan = $sumbangan->row()->ket;
                $biaowen = explode(";", $sumbangan->row()->biaowen_list);
                
                prepare_flashdata(
                    array("id_sumbangan", $id_sumbangan), array("no_kwitansi", $no_kwitansi), array("kode_donatur", $kode_donatur), 
                    array("nama_donatur", $nama_donatur), array("nama_penyumbang", $nama_penyumbang), array("tgl_donasi", $tgl_donasi), 
                    array("id_paket_sumbangan", $id_paket_sumbangan), array("nama_paket", $nama_paket), array("sisa_nilai_paket1", $sisa_nilai_paket1), 
                    array("sisa_nilai_paket", $sisa_nilai_paket), array("jumlah_donasi", $jumlah_donasi), array("metode_pembayaran", $metode_pembayaran), 
                    array("id_bank", $id_bank), array("bank", $bank), array("rek_pengirim", $rek_pengirim), 
                    array("ket_sumbangan", $ket_sumbangan), array("biaowen", $biaowen));
            }
            redirect("input/sumbangan");   
        }
        $this->load->view("input/v_sumbangan");
    }
    
    function save_sumbangan() {
        if (isset($_POST["save_sumbangan"])) {
            $id_sumbangan = sanitize($this->input->post("id_sumbangan"));
            $no_kwitansi = sanitize($this->input->post("no_kwitansi"));
            $index = sanitize($this->input->post("index"));
            $kode_donatur = $this->input->post("kode_donatur");
            $nama_donatur = $this->input->post("nama_donatur");
            $nama_penyumbang = $this->input->post("nama_penyumbang");
            $tgl_donasi = sanitize($this->input->post("tgl_donasi"));
            $id_paket_sumbangan = sanitize($this->input->post("id_paket_sumbangan"));
            $nama_paket = $this->input->post("nama_paket");
            $sisa_nilai_paket = $this->input->post("sisa_nilai_paket");
            $sisa_nilai_paket1 = $this->input->post("sisa_nilai_paket1");
            $jumlah_donasi = sanitize($this->input->post("jumlah_donasi"));
            $metode_pembayaran = sanitize($this->input->post("metode_pembayaran"));
            $id_bank = sanitize($this->input->post("id_bank"));
            $bank = $this->input->post("bank");
            $rek_pengirim = sanitize($this->input->post("rek_pengirim"));
            $ket_sumbangan = sanitize($this->input->post("ket_sumbangan"));
            $biaowen = $this->input->post("biaowen");
            
            require_once("C_Sumbangan.php");
            $sumbangan = new C_Sumbangan($id_sumbangan, $no_kwitansi, $id_paket_sumbangan, $nama_penyumbang, $tgl_donasi, 
            convert_currency_tonumber($jumlah_donasi), $metode_pembayaran, $id_bank, $rek_pengirim, $ket_sumbangan, $biaowen);
            $is_valid_sumbangan = $sumbangan->is_valid_sumbangan();
            if (!$is_valid_sumbangan[0]) {
                $errors = $is_valid_sumbangan[1];
            } else {
                $id_sumbangan1 = $this->pbs->tsumbangan_put($sumbangan->get_id_sumbangan(), $sumbangan->get_sumbangan())->row()->id_sumbangan;
                $is_valid_biaowen = $sumbangan->is_valid_biaowen($id_sumbangan1);
                if (!$is_valid_biaowen[0]) {
                    $this->pbs->tsumbangan_rollback($id_sumbangan1);
                    $errors = $is_valid_biaowen[1];
                } else {
                    $this->pbs->tsumbangan1_delete($id_sumbangan);
                    $result = $this->pbs->tsumbangan1_put($is_valid_biaowen[1]);
                    if (!$result) {
                        $this->pbs->tsumbangan_rollback($id_sumbangan1);
                        $errors[] = get_form_report("error", "sumbangan");
                    } else {
                        $this->pbs->commit();
                        prepare_flashdata(array("report", "<p id='report' class='success'>Data sumbangan berhasil ditambahkan / diubah. <br>
                        <a href='" .base_url("tampil/sumbangan/print_sumbangan?id=$id_sumbangan1"). "' target='_blank'>Cetak Kwitansi</a></p>"));
                    }
                }
            }

            if (isset($errors)) {
                prepare_flashdata(
                    array("id_sumbangan", $id_sumbangan), array("no_kwitansi", $no_kwitansi), array("index", $index), 
                    array("kode_donatur", $kode_donatur), array("nama_donatur", $nama_donatur), array("nama_penyumbang", $nama_penyumbang), 
                    array("tgl_donasi", $tgl_donasi), array("id_paket_sumbangan", $id_paket_sumbangan), array("nama_paket", $nama_paket), 
                    array("sisa_nilai_paket1", $sisa_nilai_paket1), array("sisa_nilai_paket", $sisa_nilai_paket), array("jumlah_donasi", $jumlah_donasi),
                    array("metode_pembayaran", $metode_pembayaran), array("id_bank", $id_bank), array("bank", $bank), 
                    array("rek_pengirim", $rek_pengirim), array("ket_sumbangan", $ket_sumbangan), array("biaowen", $biaowen), 
                    array("report", get_form_report("error", $errors)));
            }

            redirect("input/sumbangan");
        } else {
            redirect(get_error_page());
        }
    }

    /** ajax */
    function get_donatur_list() {
        if (isset($_GET["get_donatur_list"])) {
            echo json_encode($this->pbs->tdonatur_get(4)->result());
        } else {
            redirect(get_error_page());
        }
    }

    // function get_bank_list() {
    //     if (isset($_GET["get_bank_list"])) {
    //         echo json_encode($this->pbs->tbank_get(3)->result());
    //     } else {
    //         redirect(get_error_page());
    //     }
    // }

}