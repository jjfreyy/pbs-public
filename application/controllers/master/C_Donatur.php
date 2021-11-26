<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_Donatur {
    private $donatur;
    private $id_donatur;
    private $kode_donatur;
    private $nama_id;
    private $nama_cn;
    private $alamat;
    private $kota_lahir;
    private $tgl_lahir;
    private $kota_domisili;
    private $no_hp1;
    private $no_hp2;
    private $email;
    private $ket;
    private $tgl_gabung;

    function __construct() {
        $this->set_id_donatur(func_get_arg(0));
        $this->set_kode_donatur(func_get_arg(1));
        $this->set_nama_id(func_get_arg(2));
        $this->set_nama_cn(func_get_arg(3));
        $this->set_alamat(func_get_arg(4));
        $this->set_kota_lahir(func_get_arg(5));
        $this->set_tgl_lahir(func_get_arg(6));
        $this->set_kota_domisili(func_get_arg(7));
        $this->set_no_hp1(func_get_arg(8));
        $this->set_no_hp2(func_get_arg(9));
        $this->set_email(func_get_arg(10));
        $this->set_ket(func_get_arg(11));
        $this->set_tgl_gabung(func_get_arg(12));
        $this->set_donatur();
    }

    function is_valid_donatur() {
        $is_valid_id_donatur = is_valid_angka($this->get_id_donatur(), "Id Donatur", 1, FALSE, TRUE);
        $is_valid_kode_donatur = is_valid_kode($this->get_kode_donatur(), "pbs", "donatur", "Kode donatur", TRUE);
        $is_valid_nama_id = is_valid_nama($this->get_nama_id(), "Nama indonesia donatur", 100);
        $is_valid_nama_cn = is_valid_nama_mandarin($this->get_nama_cn(), "Nama mandarin donatur", 100, TRUE);
        $is_valid_alamat = is_valid_str($this->get_alamat(), "Alamat", 150, TRUE);
        $is_valid_kota_lahir = is_valid_nama($this->get_kota_lahir(), "Kota lahir", 50, TRUE);
        $is_valid_tgl_lahir = is_valid_tanggal($this->get_tgl_lahir(), "Tanggal lahir", TRUE);
        $is_valid_kota_domisili = is_valid_nama($this->get_kota_domisili(), "Kota domisili", 50, TRUE);
        $is_valid_no_hp1 = is_valid_telepon($this->get_no_hp1(), TRUE);
        $is_valid_no_hp2 = is_valid_telepon($this->get_no_hp2(), TRUE);
        $is_valid_email = is_valid_email($this->get_email(), TRUE);
        $is_valid_ket = is_valid_str($this->get_ket(), "Keterangan", 100, TRUE);
        $is_valid_tgl_gabung = is_valid_tanggal($this->get_tgl_gabung(), "Tanggal gabung");
        
        if (!$is_valid_kode_donatur[0]) $errors[] = $is_valid_kode_donatur[1];
        if (!$is_valid_nama_id[0]) $errors[] = $is_valid_nama_id[1];
        if (!$is_valid_nama_cn[0]) $errors[] = $is_valid_nama_cn[1];
        if (!$is_valid_alamat[0]) $errors[] = $is_valid_alamat[1];
        if (!$is_valid_kota_lahir[0]) $errors[] = $is_valid_kota_lahir[1];
        if (!$is_valid_tgl_lahir[0]) $errors[] = $is_valid_tgl_lahir[1];
        if (!$is_valid_kota_domisili[0]) $errors[] = $is_valid_kota_domisili[1];
        if (!$is_valid_no_hp1[0]) $errors[] = $is_valid_no_hp1[1];
        if (!$is_valid_no_hp2[0]) $errors[] = $is_valid_no_hp2[1];
        if (!$is_valid_email[0]) $errors[] = $is_valid_email[1];
        if (!$is_valid_ket[0]) $errors[] = $is_valid_ket[1];
        if (!$is_valid_tgl_gabung[0]) $errors[] = $is_valid_tgl_gabung[1];

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    /** accessors and mutators */
    function get_donatur() {
        return $this->donatur;
    }

    function set_donatur() {
        $this->donatur["id_donatur"] = $this->get_id_donatur();
        $this->donatur["kode_donatur"] = $this->get_kode_donatur();
        $this->donatur["nama_id"] = $this->get_nama_id();
        $this->donatur["nama_cn"] = $this->get_nama_cn();
        $this->donatur["alamat"] = $this->get_alamat();
        $this->donatur["kota_lahir"] = $this->get_kota_lahir();
        $this->donatur["tgl_lahir"] = $this->get_tgl_lahir();
        $this->donatur["kota_domisili"] = $this->get_kota_domisili();
        $this->donatur["no_hp1"] = $this->get_no_hp1();
        $this->donatur["no_hp2"] = $this->get_no_hp2();
        $this->donatur["email"] = $this->get_email();
        $this->donatur["ket"] = $this->get_ket();
        $this->donatur["tgl_gabung"] = $this->get_tgl_gabung();
    }

    function get_id_donatur() {
        return $this->id_donatur;
    }

    function set_id_donatur($id_donatur) {
        $this->id_donatur = is_empty($id_donatur) ? NULL : $id_donatur;
    }

    function get_kode_donatur() {
        return $this->kode_donatur;
    }

    function set_kode_donatur($kode_donatur) {
        $this->kode_donatur = is_empty($kode_donatur) ? NULL : "D-$kode_donatur";
    }

    function get_nama_id() {
        return $this->nama_id;
    }

    function set_nama_id($nama_id) {
        $this->nama_id = $nama_id;
    }

    function get_nama_cn() {
        return $this->nama_cn;
    }

    function set_nama_cn($nama_cn) {
        $this->nama_cn = is_empty($nama_cn) ? NULL : $nama_cn;
    }

    function get_alamat() {
        return $this->alamat;
    }

    function set_alamat($alamat) {
        $this->alamat = is_empty($alamat) ? NULL : $alamat;
    }

    function get_kota_lahir() {
        return $this->kota_lahir;
    }

    function set_kota_lahir($kota_lahir) {
        $this->kota_lahir = is_empty($kota_lahir) ? NULL : $kota_lahir;
    }

    function get_tgl_lahir() {
        return $this->tgl_lahir;
    }

    function set_tgl_lahir($tgl_lahir) {
        $this->tgl_lahir = is_empty($tgl_lahir) ? NULL : $tgl_lahir;
    }

    function get_kota_domisili() {
        return $this->kota_domisili;
    }

    function set_kota_domisili($kota_domisili) {
        $this->kota_domisili = is_empty($kota_domisili) ? NULL : $kota_domisili;
    }

    function get_no_hp1() {
        return $this->no_hp1;
    }

    function set_no_hp1($no_hp1) {
        $this->no_hp1 = is_empty($no_hp1) ? NULL : $no_hp1;
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
        $this->email = is_empty($email) ? NULL : $email;;
    }

    function get_ket() {
        return $this->ket;
    }

    function set_ket($ket) {
        $this->ket = is_empty($ket) ? NULL : $ket;
    }

    function get_tgl_gabung() {
        return $this->tgl_gabung;
    }

    function set_tgl_gabung($tgl_gabung) {
        $this->tgl_gabung = is_empty($tgl_gabung) ? NULL : $tgl_gabung;
    }
}

