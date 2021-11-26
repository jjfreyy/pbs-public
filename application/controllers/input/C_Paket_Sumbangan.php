<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Paket_Sumbangan {
    private $paket_sumbangan;
    private $id_paket_sumbangan;
    private $id_donatur;
    private $id_kolektor;
    private $id_paket;
    private $jumlah_paket;
    private $ket;
    private $tgl_jatuh_tempo;
    private $biaowen;

    function __construct() {
        $this->set_id_paket_sumbangan(func_get_arg(0));
        $this->set_id_donatur(func_get_arg(1));
        $this->set_id_kolektor(func_get_arg(2));
        $this->set_id_paket(func_get_arg(3));
        $this->set_jumlah_paket(func_get_arg(4));
        $this->set_ket(func_get_arg(5));
        $this->set_tgl_jatuh_tempo(func_get_arg(6));
        $this->set_biaowen(func_get_arg(7));
        $this->set_paket_sumbangan();
    }

    function is_valid_paket_sumbangan() {
        $is_valid_id_paket_sumbangan = is_valid_angka($this->get_id_paket_sumbangan(), "Id Paket Sumbangan", 1, FALSE, TRUE);
        $is_valid_jumlah_paket = is_valid_angka($this->get_jumlah_paket(), "Jumlah paket", 2, FALSE);
        $is_valid_ket = is_valid_str($this->get_ket(), "Keterangan", 100, TRUE);
        $is_valid_tgl_jatuh_tempo = is_valid_tanggal($this->get_tgl_jatuh_tempo(), "Tanggal jatuh tempo", TRUE);
        
        if (!$is_valid_id_paket_sumbangan[0]) $errors[] = $is_valid_id_paket_sumbangan[1];
        if (!$is_valid_jumlah_paket[0]) $errors[] = $is_valid_jumlah_paket[1];
        if (!$is_valid_ket[0]) $errors[] = $is_valid_ket[1];
        if (!$is_valid_tgl_jatuh_tempo[0]) $errors[] = $is_valid_tgl_jatuh_tempo[1];
        
        $CI = get_instance();
        if ($CI->pbs->tdonatur_get(2, "id_donatur", array("where", "id_donatur", $this->get_id_donatur()))->num_rows() === 0) {
            $errors[] = "Donatur belum terdaftar. <br>";
        }
        if ($CI->pbs->tkolektor_get(2, "id_kolektor", array("where", "id_kolektor", $this->get_id_kolektor()))->num_rows() === 0) {
            $errors[] = "Kolektor belum terdaftar. <br>";
        }
        if ($CI->pbs->tpaket_get(2, "tp.id_paket", array("where", "tp.id_paket", $this->get_id_paket()), "")->num_rows() === 0) {
            $errors[] = "Paket belum terdaftar. <br>";
        }

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    function is_valid_biaowen($id_paket_sumbangan) {
        if (is_empty_array($this->get_biaowen())) {
            return array(FALSE, array("Silakan isi data biaowen. <br>"));
        }
        
        $biaowen1 = array();
        $length = count($this->get_biaowen());
        for ($i = 0; $i < $length; $i++) {
            $nama_biaowen = sanitize($this->biaowen[$i]);
            $is_valid_biaowen = is_valid_nama_mandarin($nama_biaowen, "Nama biaowen");
            if (!$is_valid_biaowen[0]) {
                return array(FALSE, array(($i+1). ". " .$is_valid_biaowen[1]));
            } 
            
            if (in_array($nama_biaowen, $biaowen1)) {
                return array(FALSE, array(($i+1). ". Nama biaowen telah terdaftar. <br>"));
            } else {
                $biaowen1[] = $nama_biaowen;
            }

            $biaowen[$i] = array("id_paket_sumbangan" => $id_paket_sumbangan, "nmr" => $i+1, "biaowen" => $nama_biaowen);
        }

        return array(TRUE, $biaowen);
    }

    /** accessors and mutators */
    function get_paket_sumbangan() {
        return $this->paket_sumbangan;
    }

    function set_paket_sumbangan() {
        $this->paket_sumbangan["id_donatur"] = $this->get_id_donatur();
        $this->paket_sumbangan["id_kolektor"] = $this->get_id_kolektor();
        $this->paket_sumbangan["id_paket"] = $this->get_id_paket();
        $this->paket_sumbangan["jumlah_paket"] = $this->get_jumlah_paket();
        $this->paket_sumbangan["ket"] = $this->get_ket();
        $this->paket_sumbangan["tgl_jatuh_tempo"] = $this->get_tgl_jatuh_tempo();
    }

    function get_id_paket_sumbangan() {
        return $this->id_paket_sumbangan;
    }

    function set_id_paket_sumbangan($id_paket_sumbangan) {
        $this->id_paket_sumbangan = is_empty($id_paket_sumbangan) ? NULL : $id_paket_sumbangan;
    }

    
    function get_id_donatur() {
        return $this->id_donatur;
    }
    
    function set_id_donatur($id_donatur) {
        $this->id_donatur = $id_donatur;
    }

    
    function get_id_kolektor() {
        return $this->id_kolektor;
    }
    
    function set_id_kolektor($id_kolektor) {
        $this->id_kolektor = $id_kolektor;
    }

    function get_id_paket() {
        return $this->id_paket;
    }
    
    function set_id_paket($id_paket) {
        $this->id_paket = $id_paket;
    }
    
    function get_jumlah_paket() {
        return $this->jumlah_paket;
    }
    
    function set_jumlah_paket($jumlah_paket) {
        $this->jumlah_paket = $jumlah_paket;
    }

    function get_ket() {
        return $this->ket;
    }

    function set_ket($ket) {
        $this->ket = is_empty($ket) ? NULL : $ket;
    }

    function get_tgl_jatuh_tempo() {
        return $this->tgl_jatuh_tempo;
    }

    function set_tgl_jatuh_tempo($tgl_jatuh_tempo) {
        $this->tgl_jatuh_tempo = is_empty($tgl_jatuh_tempo) ? NULL : $tgl_jatuh_tempo;
    }

    function get_biaowen() {
        return $this->biaowen;
    }

    function set_biaowen($biaowen) {
        $this->biaowen = $biaowen;
    }
}