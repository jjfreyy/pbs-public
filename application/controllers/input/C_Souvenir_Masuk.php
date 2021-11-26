<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Souvenir_Masuk {
    private $CI;

    private $souvenir1;
    private $id;
    private $id_souvenir;
    private $stok_masuk;
    private $ket;

    function __construct() {
        $this->CI =& get_instance();
    
        $this->set_id(func_get_arg(0));
        $this->set_id_souvenir(func_get_arg(1));
        $this->set_stok_masuk(func_get_arg(2));
        $this->set_ket(func_get_arg(3));
        $this->set_souvenir1();
    }

    function is_valid_souvenir1() {
        $is_valid_id = is_valid_angka($this->get_id(), "Id Souvenir Masuk", 1, FALSE, TRUE);
        $is_valid_id_souvenir = is_valid_angka($this->get_id_souvenir(), "Id Souvenir", 1, FALSE, TRUE);
        $is_valid_stok_masuk = is_valid_angka($this->get_stok_masuk(), "Stok masuk");
        $is_valid_ket = is_valid_str($this->get_ket(), "Keterangan", 100, TRUE);

        if (!$is_valid_id[0]) $errors[] = $is_valid_id[1];
        if (!$is_valid_id_souvenir[0]) {
            $errors[] = $is_valid_id_souvenir[1];
        } else if ($this->CI->pbs->tsouvenir_get(2, "id_souvenir", array("where", "id_souvenir", $this->get_id_souvenir()))->num_rows() === 0) {
            $errors[] = "Souvenir belum terdaftar. <br>";
        }
        if (!$is_valid_stok_masuk[0]) $errors[] = $is_valid_stok_masuk[1];
        if (!$is_valid_ket[0]) $errors[] = $is_valid_ket[1];

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    /** accessors and mutators */
    function get_souvenir1() {
        return $this->souvenir1;
    }

    function set_souvenir1() {
        $this->souvenir1["id"] = $this->get_id();
        $this->souvenir1["id_souvenir"] = $this->get_id_souvenir();
        $this->souvenir1["stok_masuk"] = $this->get_stok_masuk();
        $this->souvenir1["ket"] = $this->get_ket();
    }

    function get_id() {
        return $this->id;
    }

    function set_id($id) {
        $this->id = is_empty($id) ? NULL : $id;
    }

    function get_id_souvenir() {
        return $this->id_souvenir;
    }

    function set_id_souvenir($id_souvenir) {
        $this->id_souvenir = $id_souvenir;
    }
    
    function get_stok_masuk() {
        return $this->stok_masuk;
    }

    function set_stok_masuk($stok_masuk) {
        $this->stok_masuk = $stok_masuk;
    }

    function get_ket() {
        return $this->ket;
    }

    function set_ket($ket) {
        $this->ket = is_empty($ket) ? NULL : $ket;
    }
}