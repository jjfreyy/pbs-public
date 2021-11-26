<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Paket {
    private $paket;
    private $id_paket;
    private $nama_perusahaan;
    private $kode_paket;
    private $nama_paket;
    private $nilai_paket;
    private $periode;
    private $bank_list;

    function __construct() {
        $this->set_id_paket(func_get_arg(0));
        $this->set_nama_perusahaan(func_get_arg(1));
        $this->set_kode_paket(func_get_arg(2));
        $this->set_nama_paket(func_get_arg(3));
        $this->set_nilai_paket(func_get_arg(4));
        $this->set_periode(func_get_arg(5));
        $this->set_bank_list(func_get_arg(6));
        $this->set_paket();
    }

    function is_valid_paket() {
        $is_valid_id_paket = is_valid_angka($this->get_id_paket(), "Id Paket", 1, FALSE, TRUE);
        $is_valid_nama_perusahaan = is_valid_nama($this->get_nama_perusahaan(), "Nama perusahaan", 50, TRUE);
        $is_valid_kode_paket = is_valid_kode($this->get_kode_paket(), "pbs", "paket", "Kode paket", TRUE);
        $is_valid_nama_paket = is_valid_nama($this->get_nama_paket(), "Nama paket");
        $is_valid_nilai_paket = is_valid_angka($this->get_nilai_paket(), "Nilai paket", 2, TRUE, TRUE);
        $is_valid_periode = is_valid_periode($this->get_periode(), TRUE);
        
        if (!$is_valid_id_paket[0]) $errors[] = $is_valid_id_paket[1];
        if (!$is_valid_nama_perusahaan[0]) $errors[] = $is_valid_nama_perusahaan[1];
        if (!$is_valid_kode_paket[0]) $errors[] = $is_valid_kode_paket[1];
        if (!$is_valid_nama_paket[0]) $errors[] = $is_valid_nama_paket[1];
        if (!$is_valid_nilai_paket[0]) $errors[] = $is_valid_nilai_paket[1];
        if (!$is_valid_periode[0]) $errors[] = $is_valid_periode[1];

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    function is_valid_bank_list($id_paket) {
        if (is_empty_array($this->get_bank_list())) {
            return array(TRUE);
        }

        $CI =& get_instance();
        $id_bank1 = array();
        $length = count($this->get_bank_list());
        for ($i = 0; $i < $length; $i++) {
            $id_bank = sanitize(explode("|", $this->bank_list[$i])[0]);

            if (in_array($id_bank, $id_bank1)) return array(FALSE, array(($i+1). ". No. rekening telah terdaftar."));
            else $id_bank1[] = $id_bank;

            if ($CI->pbs->tbank_get(2, "id_bank", array("where", "id_bank", $id_bank))->num_rows() === 0) 
            return array(FALSE, array(($i+1). ". Bank belum terdaftar."));

            $bank_list[$i] = array("id_paket" => $id_paket, "id_bank" => $id_bank);
        }

        return array(TRUE, $bank_list);
    }

    /** accessors and mutators */
    function get_paket() {
        return $this->paket;
    }

    function set_paket() {
        $this->paket["nama_perusahaan"] = $this->get_nama_perusahaan();
        $this->paket["kode_paket"] = $this->get_kode_paket();
        $this->paket["nama_paket"] = $this->get_nama_paket();
        $this->paket["nilai_paket"] = $this->get_nilai_paket();
        $this->paket["periode"] = $this->get_periode();
    }

    function get_id_paket() {
        return $this->id_paket;
    }

    function set_id_paket($id_paket) {
        $this->id_paket = is_empty($id_paket) ? NULL : $id_paket;
    }

    function get_nama_perusahaan() {
        return $this->nama_perusahaan;
    }

    function set_nama_perusahaan($nama_perusahaan) {
        $this->nama_perusahaan = is_empty($nama_perusahaan) ? NULL : $nama_perusahaan;
    }

    function get_kode_paket() {
        return $this->kode_paket;
    }

    function set_kode_paket($kode_paket) {
        $this->kode_paket = is_empty($kode_paket) ? NULL : strtoupper($kode_paket);
    }

    function get_nama_paket() {
        return $this->nama_paket;
    }

    function set_nama_paket($nama_paket) {
        $this->nama_paket = $nama_paket;
    }

    function get_nilai_paket() {
        return $this->nilai_paket;
    }

    function set_nilai_paket($nilai_paket) {
        $this->nilai_paket = is_empty($nilai_paket) ? NULL : $nilai_paket;
    }

    function get_periode() {
        return $this->periode;
    }

    function set_periode($periode) {
        $this->periode = is_empty($periode) ? NULL : $periode;
    }

    function get_bank_list() {
        return $this->bank_list;
    }

    function set_bank_list($bank_list) {
        $this->bank_list = $bank_list;
    }
}

