<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Bank {
    private $bank;
    private $id_bank;
    private $nama_bank;
    private $an;
    private $no_rek;

    function __construct() {
        $this->set_id_bank(func_get_arg(0));
        $this->set_nama_bank(func_get_arg(1));
        $this->set_an(func_get_arg(2));
        $this->set_no_rek(func_get_arg(3));
        $this->set_bank();
    }

    function is_valid_bank() {
        $is_valid_id_bank = is_valid_angka($this->get_id_bank(), "Id Bank", 1, FALSE, TRUE);
        $is_valid_nama_bank = is_valid_nama($this->get_nama_bank(), "Nama bank");
        $is_valid_an = is_valid_nama($this->get_an(), "AN.");
        $is_valid_no_rek = is_valid_angka(preg_replace("/[\s\-]/", "", $this->get_no_rek()), "No. rekening");
        
        if (!$is_valid_id_bank[0]) $errors[] = $is_valid_id_bank[1];
        if (!$is_valid_nama_bank[0]) $errors[] = $is_valid_nama_bank[1];
        if (!$is_valid_an[0]) $errors[] = $is_valid_an[1];
        if (!$is_valid_no_rek[0]) $errors[] = $is_valid_no_rek[1];
        
        $CI =& get_instance();
        $bank = $CI->pbs->tbank_get(2, "id_bank", array("where", "no_rek", $this->get_no_rek()));
        if ($bank->num_rows() > 0 && $bank->row()->id_bank !== $this->get_id_bank()) $errors[] = "No. rekening telah terdaftar. <br>";

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    /** accessors and mutators */
    function get_bank() {
        return $this->bank;
    }

    function set_bank() {
        $this->bank["id_bank"] = $this->get_id_bank();
        $this->bank["nama_bank"] = $this->get_nama_bank();
        $this->bank["an"] = $this->get_an();
        $this->bank["no_rek"] = $this->get_no_rek();
    }

    function get_id_bank() {
        return $this->id_bank;
    }

    function set_id_bank($id_bank) {
        $this->id_bank = is_empty($id_bank) ? NULL : $id_bank;
    }

    function get_nama_bank() {
        return $this->nama_bank;
    }

    function set_nama_bank($nama_bank) {
        $this->nama_bank = $nama_bank;
    }

    function get_an() {
        return $this->an;
    }

    function set_an($an) {
        $this->an = $an;
    }

    function get_no_rek() {
        return $this->no_rek;
    }

    function set_no_rek($no_rek) {
        $this->no_rek = $no_rek;
    }
}

