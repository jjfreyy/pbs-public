<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Sumbangan  {
    private $sumbangan;
    private $id_sumbangan;
    private $no_kwitansi;
    private $id_paket_sumbangan;
    private $nama_penyumbang;
    private $tgl_donasi;
    private $jumlah_donasi;
    private $metode_pembayaran;
    private $id_bank;
    private $rek_pengirim;
    private $ket;
    private $biaowen;

    private $sisa_nilai_paket;

    function __construct() {
        $this->set_id_sumbangan(func_get_arg(0));
        $this->set_no_kwitansi(func_get_arg(1));
        $this->set_id_paket_sumbangan(func_get_arg(2));
        $this->set_nama_penyumbang(func_get_arg(3));
        $this->set_tgl_donasi(func_get_arg(4));
        $this->set_jumlah_donasi(func_get_arg(5));
        $this->set_metode_pembayaran(func_get_arg(6));
        $this->set_id_bank(func_get_arg(7));
        $this->set_rek_pengirim(func_get_arg(8));
        $this->set_ket(func_get_arg(9));
        $this->set_biaowen(func_get_arg(10));
        $this->set_sumbangan();
    }

    function is_valid_sumbangan() {
        $CI =& get_instance();
        $is_valid_id_sumbangan = is_valid_angka($this->get_id_sumbangan(), "Id Sumbangan", 1, FALSE, TRUE);
        $is_valid_no_kwitansi = is_valid_kode($this->get_no_kwitansi(), "pbs", "kwitansi", "Kode kwitansi", TRUE);
        $is_valid_id_paket_sumbangan = $CI->pbs->tpaket_sumbangan_get(1, "id_paket_sumbangan", array("id_paket_sumbangan", $this->get_id_paket_sumbangan()))->num_rows() > 0;
        $is_valid_nama_penyumbang = is_valid_nama_mandarin($this->get_nama_penyumbang(), "Nama penyumbang", 100);
        $is_valid_tgl_donasi = is_valid_tanggal($this->get_tgl_donasi(), "Tanggal donasi");
        $is_valid_jumlah_donasi = is_valid_angka($this->get_jumlah_donasi(), "Jumlah donasi", 2);
        $is_valid_metode_pembayaran = isset($this->metode_pembayaran) && ($this->metode_pembayaran === "Tunai" || $this->metode_pembayaran === "Transfer");
        $is_valid_id_bank = $is_valid_metode_pembayaran && (($this->metode_pembayaran === "Tunai" && is_empty($this->id_bank)) ||
        ($this->metode_pembayaran === "Transfer" && $CI->pbs->tbank_get(2, "id_bank", array("where", "id_bank", $this->get_id_bank()))->num_rows() > 0));
        $is_valid_rek_pengirim = is_valid_str($this->get_rek_pengirim(), "Rekening Pengirim", 100, TRUE);
        $is_valid_ket = is_valid_str($this->get_ket(), "Keterangan", 100, TRUE);
        
        if (!$is_valid_id_sumbangan[0]) $errors[] = $is_valid_id_sumbangan[1];
        if (!$is_valid_no_kwitansi[0]) $errors[] = $is_valid_no_kwitansi[1];
        if (!$is_valid_id_paket_sumbangan)  $errors[] = "Paket sumbangan belum terdaftar. <br>";
        if (!$is_valid_nama_penyumbang[0]) $errors[] = $is_valid_nama_penyumbang[1];
        if (!$is_valid_tgl_donasi[0]) $errors[] = $is_valid_tgl_donasi[1];
        if (!$is_valid_jumlah_donasi[0]) $errors[] = $is_valid_jumlah_donasi[1];
        else if ($is_valid_id_paket_sumbangan) {
            $this->sisa_nilai_paket = $CI->pbs->tpaket_sumbangan_get(7, $this->get_id_sumbangan(), $this->get_id_paket_sumbangan())->row()->sisa_nilai_paket;
            $this->sisa_nilai_paket = isset($this->sisa_nilai_paket) ? $this->sisa_nilai_paket - $this->get_jumlah_donasi() : NULL;
            if (isset($this->sisa_nilai_paket) && $this->sisa_nilai_paket < 0) $errors[] = "Jumlah donasi lebih dari sisa nilai paket. <br>";
        }
        if (!$is_valid_metode_pembayaran) $errors[] = "Metode pembayaran tidak valid. <br>";
        if (!$is_valid_id_bank) $errors[] = "Rekening penerima belum terdaftar. <br>";
        if (!$is_valid_rek_pengirim) $errors[] = $is_valid_rek_pengirim[1];
        if (!$is_valid_ket[0]) $errors[] = $is_valid_ket[1];

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    function is_valid_biaowen($id_sumbangan) {
        if (is_empty_array($this->get_biaowen())) {
            return array(FALSE, array("Silakan isi data biaowen. <br>"));
        }
        
        $CI =& get_instance();
        $biaowen1 = array();
        $length = count($this->get_biaowen());
        for ($i = 0; $i < $length; $i++) {
            $biaowen0 = explode("|", $this->biaowen[$i]);
            if (count($biaowen0) !== 2) return array(FALSE, array("Gagal menginput data biaowen. Silakan coba kembali."));
            $lunas = sanitize($biaowen0[0]);
            $nama_biaowen = sanitize($biaowen0[1]);
            
            $is_valid_lunas = $lunas != 0 || $lunas != 1;
            $is_valid_biaowen = is_valid_nama_mandarin($nama_biaowen, "Nama biaowen");

            if (!$is_valid_lunas) return array(FALSE, array("Gagal menginput data biaowen. Silakan coba kembali."));
            if (!$is_valid_biaowen[0]) return array(FALSE, array(($i+1). ". " .$is_valid_biaowen[1]));
            
            if (in_array($nama_biaowen, $biaowen1)) {
                return array(FALSE, array(($i+1). ". Nama biaowen telah terdaftar."));
            } else {
                $biaowen1[] = $nama_biaowen;
            }

            if ($CI->pbs->tsumbangan1_get(1, $this->get_id_paket_sumbangan(), $this->get_id_sumbangan(), $nama_biaowen)->num_rows() > 0) {
                return array(FALSE, array(($i+1). ". Tidak dapat mendonasikan biaowen yang telah lunas."));
            }

            // if (!isset($this->sisa_nilai_paket) || $this->sisa_nilai_paket == 0) $lunas = 1;
            
            $biaowen[$i] = array(
                "id_sumbangan" => $id_sumbangan, "nmr" => $i+1, "biaowen" => $nama_biaowen, "lunas" => $lunas);
        }

        return array(TRUE, $biaowen);
    }

    /** accessors and mutators */
    function get_sumbangan() {
        return $this->sumbangan;
    }

    function set_sumbangan() {
        $this->sumbangan["no_kwitansi"] = $this->get_no_kwitansi();
        $this->sumbangan["id_paket_sumbangan"] = $this->get_id_paket_sumbangan();
        $this->sumbangan["nama_penyumbang"] = $this->get_nama_penyumbang();
        $this->sumbangan["tgl_donasi"] = $this->get_tgl_donasi();
        $this->sumbangan["jumlah_donasi"] = $this->get_jumlah_donasi();
        $this->sumbangan["metode_pembayaran"] = $this->get_metode_pembayaran();
        $this->sumbangan["id_bank"] = $this->get_id_bank();
        $this->sumbangan["rek_pengirim"] = $this->get_rek_pengirim();
        $this->sumbangan["ket"] = $this->get_ket();
    }

    function get_id_sumbangan() {
        return $this->id_sumbangan;
    }

    function set_id_sumbangan($id_sumbangan) {
        $this->id_sumbangan = is_empty($id_sumbangan) ? NULL : $id_sumbangan;
    }

    function get_no_kwitansi() {
        return $this->no_kwitansi;
    }

    function set_no_kwitansi($no_kwitansi) {
        return $this->no_kwitansi = is_empty($no_kwitansi) ? NULL : $no_kwitansi;
    }

    function get_id_paket_sumbangan() {
        return $this->id_paket_sumbangan;
    }

    function set_id_paket_sumbangan($id_paket_sumbangan) {
        $this->id_paket_sumbangan = $id_paket_sumbangan;
    }

    function get_nama_penyumbang() {
        return $this->nama_penyumbang;
    }

    function set_nama_penyumbang($nama_penyumbang) {
        $this->nama_penyumbang = $nama_penyumbang;
    }

    function get_tgl_donasi() {
        return $this->tgl_donasi;
    }

    function set_tgl_donasi($tgl_donasi) {
        $this->tgl_donasi = $tgl_donasi;
    }

    function get_jumlah_donasi() {
        return $this->jumlah_donasi;
    }

    function set_jumlah_donasi($jumlah_donasi) {
        $this->jumlah_donasi = $jumlah_donasi;
    }

    function get_metode_pembayaran() {
        return $this->metode_pembayaran;
    }

    function set_metode_pembayaran($metode_pembayaran) {
        $this->metode_pembayaran = $metode_pembayaran;
    }

    function get_id_bank() {
        return $this->id_bank;
    }

    function set_id_bank($id_bank) {
        $this->id_bank = (isset($this->metode_pembayaran) && $this->metode_pembayaran === "Tunai") || is_empty($id_bank) ? NULL : $id_bank;
    }

    function get_rek_pengirim() {
        return $this->rek_pengirim;
    }

    function set_rek_pengirim($rek_pengirim) {
        $this->rek_pengirim = (isset($this->metode_pembayaran) && $this->metode_pembayaran === "Tunai") || is_empty($rek_pengirim) ? NULL : $rek_pengirim;
    }

    function get_ket() {
        return $this->ket;
    }

    function set_ket($ket) {
        $this->ket = is_empty($ket) ? NULL : $ket;
    }

    function get_biaowen() {
        return $this->biaowen;
    }

    function set_biaowen($biaowen) {
        $this->biaowen = $biaowen;
    }
}