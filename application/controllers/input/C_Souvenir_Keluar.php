<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Souvenir_Keluar {
    private $CI;

    private $souvenir2;
    private $id;
    private $id_paket_sumbangan;
    private $id_souvenir;
    private $penerima_souvenir;
    private $stok_keluar;
    private $tgl_serah;
    private $ket;

    function __construct() {
        $this->CI =& get_instance();
        
        $this->set_id(func_get_arg(0));
        $this->set_id_paket_sumbangan(func_get_arg(1));
        $this->set_id_souvenir(func_get_arg(2));
        $this->set_penerima_souvenir(func_get_arg(3));
        $this->set_stok_keluar(func_get_arg(4));
        $this->set_tgl_serah(func_get_arg(5));
        $this->set_ket(func_get_arg(6));
        $this->set_souvenir2();
    }

    function is_valid_souvenir2() {
        $is_valid_id = is_valid_angka($this->get_id(), "Id Souvenir Keluar", 1, FALSE, TRUE);
        $is_valid_id_paket_sumbangan = is_valid_angka($this->get_id_paket_sumbangan(), "Id Paket Sumbangan", 1, FALSE);
        $is_valid_id_souvenir = is_valid_angka($this->get_id_souvenir(), "Id Souvenir", 1, FALSE);
        $is_valid_penerima_souvenir = is_valid_nama($this->get_penerima_souvenir(), "Penerima souvenir");
        $is_valid_stok_keluar = is_valid_angka($this->get_stok_keluar(), "Stok keluar", 2);
        $is_valid_tgl_serah = is_valid_tanggal($this->get_tgl_serah(), "Tanggal serah souvenir");
        $is_valid_ket = is_valid_str($this->get_ket(), "Keterangan", 100, TRUE);

        if (!$is_valid_id[0]) $errors[] = $is_valid_id[1];
        if (!$is_valid_id_paket_sumbangan[0]) $errors[] = $is_valid_id_paket_sumbangan[1];
        else if ($this->CI->pbs->tpaket_sumbangan_get(6, $this->get_id_paket_sumbangan())->num_rows() === 0) 
        $errors[] = "Paket belum lunas / Donatur telah menerima souvenir. <br>";
        if (!$is_valid_id_souvenir[0]) $errors[] = $is_valid_id_souvenir[1];
        if (!$is_valid_penerima_souvenir[0]) $errors[] = $is_valid_penerima_souvenir[1];
        if (!$is_valid_stok_keluar[0]) $errors[] = $is_valid_stok_keluar[1];
        if (!$is_valid_tgl_serah[0]) $errors[] = $is_valid_tgl_serah[1];
        if (!$is_valid_ket[0]) $errors[] = $is_valid_ket[1];
        if ($is_valid_id_souvenir[0] && $is_valid_stok_keluar[0]) {
            $result = $this->CI->pbs->tsouvenir_get(3, "=", $this->get_id_souvenir(), $this->get_id());
            if ($result->num_rows() === 0) $errors[] = "Souvenir belum terdaftar. <br>";
            else if ($result->row()->stok_akhir - $this->stok_keluar < 0) $errors[] = "Stok keluar lebih besar dari stok tersedia. <br>";
        }

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    /** accessors and mutators */
    function get_souvenir2() {
        return $this->souvenir2;
    }

    function set_souvenir2() {
        $this->souvenir2["id"] = $this->get_id();
        $this->souvenir2["id_paket_sumbangan"] = $this->get_id_paket_sumbangan();
        $this->souvenir2["id_souvenir"] = $this->get_id_souvenir();
        $this->souvenir2["penerima_souvenir"] = $this->get_penerima_souvenir();
        $this->souvenir2["stok_keluar"] = $this->get_stok_keluar();
        $this->souvenir2["tgl_serah"] = $this->get_tgl_serah();
        $this->souvenir2["ket"] = $this->get_ket();
    }

    function get_id() {
        return $this->id;
    }

    function set_id($id) {
        $this->id = is_empty($id) ? NULL : $id;
    }

    function get_id_paket_sumbangan() {
        return $this->id_paket_sumbangan;
    }

    function set_id_paket_sumbangan($id_paket_sumbangan) {
        $this->id_paket_sumbangan = $id_paket_sumbangan;
    }

    function get_id_souvenir() {
        return $this->id_souvenir;
    }

    function set_id_souvenir($id_souvenir) {
        $this->id_souvenir = $id_souvenir;
    }

    function get_penerima_souvenir() {
        return $this->penerima_souvenir;
    }

    function set_penerima_souvenir($penerima_souvenir) {
        $this->penerima_souvenir = $penerima_souvenir;
    }
    
    function get_stok_keluar() {
        return $this->stok_keluar;
    }

    function set_stok_keluar($stok_keluar) {
        $this->stok_keluar = $stok_keluar;
    }

    function get_tgl_serah() {
        return $this->tgl_serah;
    }

    function set_tgl_serah($tgl_serah) {
        $this->tgl_serah = $tgl_serah;
    }

    function get_ket() {
        return $this->ket;
    }

    function set_ket($ket) {
        $this->ket = is_empty($ket) ? NULL : $ket;
    }
}