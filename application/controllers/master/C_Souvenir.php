<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Souvenir {
    private $souvenir;
    private $id_souvenir;
    private $kode_souvenir;
    private $nama;
    private $stok_awal;
    private $jenis;
    private $satuan;
    private $ket;

    function __construct() {
        $this->set_id_souvenir(func_get_arg(0));
        $this->set_kode_souvenir(func_get_arg(1));
        $this->set_nama(func_get_arg(2));
        $this->set_stok_awal(func_get_arg(3));
        $this->set_jenis(func_get_arg(4));
        $this->set_satuan(func_get_arg(5));
        $this->set_ket(func_get_arg(6));
        $this->set_souvenir();
    }

    function is_valid_souvenir() {
        $is_valid_id_souvenir = is_valid_angka($this->get_id_souvenir(), "Id Souvenir", 1, FALSE, TRUE);
        $is_valid_kode_souvenir = is_valid_kode($this->get_kode_souvenir(), "pbs", "souvenir", "Kode souvenir", TRUE);
        $is_valid_nama = is_valid_str($this->get_nama(), "Nama souvenir");
        $is_valid_stok_awal = is_valid_angka($this->get_stok_awal(), "Stok awal");
        $is_valid_jenis = is_valid_nama($this->get_jenis(), "Jenis souvenir", 20);
        $is_valid_satuan = is_valid_nama($this->get_satuan(), "Nama satuan", 10);
        $is_valid_ket = is_valid_str($this->get_ket(), "Keterangan", 100, TRUE);
        
        if (!$is_valid_id_souvenir[0]) $errors[] = $is_valid_id_souvenir[1];
        if (!$is_valid_kode_souvenir[0]) $errors[] = $is_valid_kode_souvenir[1];
        if (!$is_valid_nama[0]) $errors[] = $is_valid_nama[1];
        if (!$is_valid_stok_awal[0]) $errors[] = $is_valid_stok_awal[1];
        if (!$is_valid_jenis[0]) $errors[] = $is_valid_jenis[1];
        if (!$is_valid_satuan[0]) $errors[] = $is_valid_satuan[1];
        if (!$is_valid_ket[0]) $errors[] = $is_valid_ket[1];

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    /** accessors and mutators */
    function get_souvenir() {
        return $this->souvenir;
    }

    function set_souvenir() {
        $this->souvenir["id_souvenir"] = $this->get_id_souvenir();
        $this->souvenir["kode_souvenir"] = $this->get_kode_souvenir();
        $this->souvenir["nama"] = $this->get_nama();
        $this->souvenir["stok_awal"] = $this->get_stok_awal();
        $this->souvenir["jenis"] = $this->get_jenis();
        $this->souvenir["satuan"] = $this->get_satuan();
        $this->souvenir["keterangan"] = $this->get_ket();
    }

    function get_id_souvenir() {
        return $this->id_souvenir;
    }

    function set_id_souvenir($id_souvenir) {
        $this->id_souvenir = is_empty($id_souvenir) ? NULL : $id_souvenir;
    }

    function get_kode_souvenir() {
        return $this->kode_souvenir;
    }

    function set_kode_souvenir($kode_souvenir) {
        $this->kode_souvenir = is_empty($kode_souvenir) ? NULL : $kode_souvenir;
    }

    function get_nama() {
        return $this->nama;
    }

    function set_nama($nama) {
        $this->nama = $nama;
    }

    function get_stok_awal() {
        return $this->stok_awal;
    }

    function set_stok_awal($stok_awal) {
        $this->stok_awal = $stok_awal;
    }

    function get_jenis() {
        return $this->jenis;
    }

    function set_jenis($jenis) {
        $this->jenis = $jenis;
    }

    function get_satuan() {
        return $this->satuan;
    }

    function set_satuan($satuan) {
        $this->satuan = $satuan;
    }

    function get_ket() {
        return $this->ket;
    }

    function set_ket($ket) {
        $this->ket = is_empty($ket) ? NULL : $ket;
    }

}

