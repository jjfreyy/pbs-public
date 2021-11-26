<?php defined('BASEPATH') OR exit('No direct script access allowed');
class  C_Kolektor {
    private $kolektor;
    private $id_kolektor;
    private $kode_kolektor;
    private $nama;
    private $no_hp1;
    private $no_hp2;
    private $email;
    private $ket;

    function __construct() {
        $this->set_id_kolektor(func_get_arg(0));
        $this->set_kode_kolektor(func_get_arg(1));
        $this->set_nama(func_get_arg(2));
        $this->set_no_hp1(func_get_arg(3));
        $this->set_no_hp2(func_get_arg(4));
        $this->set_email(func_get_arg(5));
        $this->set_ket(func_get_arg(6));
        $this->set_kolektor();
    }

    function is_valid_kolektor() {
        $is_valid_id_kolektor = is_valid_angka($this->get_id_kolektor(), "Id Kolektor", 1, FALSE, TRUE);
        $is_valid_kode_kolektor = is_valid_kode($this->get_kode_kolektor(), "pbs", "kolektor", "Kode kolektor", TRUE);
        $is_valid_nama = is_valid_nama($this->get_nama(), "Nama kolektor");
        $is_valid_no_hp1 = is_valid_telepon($this->get_no_hp1());
        $is_valid_no_hp2 = is_valid_telepon($this->get_no_hp2(), TRUE);
        $is_valid_email = is_valid_email($this->get_email(), TRUE);
        $is_valid_ket = is_valid_str($this->get_ket(), "Keterangan", 100, TRUE);

        if (!$is_valid_id_kolektor[0]) $errors[] = $is_valid_id_kolektor[1];
        if (!$is_valid_kode_kolektor[0]) $errors[] = $is_valid_kode_kolektor[1];
        if (!$is_valid_nama[0]) $errors[] = $is_valid_nama[1];
        if (!$is_valid_no_hp1[0]) $errors[] = $is_valid_no_hp1[1];
        if (!$is_valid_no_hp2[0]) $errors[] = $is_valid_no_hp2[1];
        if (!$is_valid_email[0]) $errors[] = $is_valid_email[1];
        if (!$is_valid_ket[0]) $errors[] = $is_valid_ket[1];
        
        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        } 
    }
    
    /** accessors and mutators */
    function get_kolektor() {
        return $this->kolektor;
    }

    function set_kolektor() {
        $this->kolektor["id_kolektor"] = $this->get_id_kolektor();
        $this->kolektor["kode_kolektor"] = $this->get_kode_kolektor();
        $this->kolektor["nama"] = $this->get_nama();
        $this->kolektor["no_hp1"] = $this->get_no_hp1();
        $this->kolektor["no_hp2"] = $this->get_no_hp2();
        $this->kolektor["email"] = $this->get_email();
        $this->kolektor["ket"] = $this->get_ket();
    }

    function get_id_kolektor() {
        return $this->id_kolektor;
    }

    function set_id_kolektor($id_kolektor) {
        $this->id_kolektor = is_empty($id_kolektor) ? NULL : $id_kolektor;
    }

    function get_kode_kolektor() {
        return $this->kode_kolektor;
    }

    function set_kode_kolektor($kode_kolektor) {
        $this->kode_kolektor = is_empty($kode_kolektor) ? NULL : "K-$kode_kolektor";
    }

    function get_nama() {
        return $this->nama;
    }

    function set_nama($nama) {
        $this->nama = $nama;
    }

    function get_no_hp1() {
        return $this->no_hp1;
    }

    function set_no_hp1($no_hp1) {
        $this->no_hp1 = $no_hp1;
    }

    function get_no_hp2() {
        return $this->no_hp2;
    }

    function set_no_hp2($no_hp2) {
        $this->no_hp2 = is_empty($no_hp2) ? NULL : $no_hp2;
    }

    function get_email() {
        return $this->email;
    }

    function set_email($email) {
        $this->email = is_empty($email) ? NULL : $email;
    }

    function get_ket() {
        return $this->ket;
    }

    function set_ket($ket) {
        $this->ket = is_empty($ket) ? NULL : $ket;
    }
}