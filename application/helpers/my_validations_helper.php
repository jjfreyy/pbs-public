<?php defined('BASEPATH') OR exit('No direct script access allowed');
function is_valid_alphanumeric($str, $object, $letter_prefix = FALSE, $length = 50, $can_empty = FALSE) {
    if ($can_empty && is_empty($str)) {
        return array(TRUE);
    }

    if (!preg_match("/^" .($letter_prefix ? "[a-z]" : ""). "([a-z1-9]+)?$/i", $str) || strlen($str) > $length || strlen($str) === 0) {
        return array(FALSE, "$object " .($letter_prefix ? "harus diawali dengan huruf & " : ""). "harus berupa alphanumeric & 
        tidak boleh lebih dari $length karakter. <br>");
    } else {
        return array(TRUE);
    }
}

/** 
 * restrict 1: tidak boleh kurang dari 0
 * restrict 2: harus lebih dari 0 
 * */
function is_valid_angka($angka, $object, $restrict = 0, $allow_decimal = TRUE, $can_empty = FALSE) {
    if ($can_empty && is_empty($angka)) {
        return array(TRUE);
    }
    
    if (!is_numeric($angka)) {
        return array(FALSE, "$object harus berupa angka. <br>");
    }

    if (!$allow_decimal && preg_match("/[\.]/", $angka)) {
        return array(FALSE, "$object tidak boleh memiliki angka desimal. <br>");
    } 
    
    if ($restrict === 1 && $angka < 0) {
        return array(FALSE, "$object tidak boleh kurang dari 0. <br>");
    } else if ($restrict === 2 && !($angka > 0)) {
        return array(FALSE, "$object harus lebih dari 0. <br>");
    }
    
    return array(TRUE);
}

function is_valid_email($email, $can_empty = FALSE) {
    if ($can_empty && is_empty($email)) {
        return array(TRUE);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50) {
        return array(FALSE, "Format e-mail tidak valid / panjang e-mail lebih dari 50 karakter. <br>");
    } else {
        return array(TRUE);
    }
}

function is_empty($str) {
    return !isset($str) || (empty($str) && $str != "0");
}

function is_empty_array($arr) {
    return empty($arr) || !is_array($arr);
}

/**
 * $app = nama aplikasi yg menggunakan validasi kode
 * $object = tipe kode yg akan validasi
 * $text = text yg akan ditampilkan di pesan validasi
 */
function is_valid_kode($kode, $app, $object, $text, $can_empty = FALSE) {
    if ($can_empty && is_empty(($kode))) return array(TRUE, NULL);

    $kode0 = explode("-", $kode);
    if (count($kode0) !== 2) return array(FALSE, "$text harus mengikuti format {2~3 huruf}-{angka}. Contoh: A-1001, AB-976. <br>");
    $kode1 = $kode0[0];
    $kode2 = $kode0[1];

    switch ($app) {
        case "pbs":
            switch ($object) {
                case "donatur": $kode_prefix = "D"; break;
                case "kolektor": $kode_prefix = "K"; break;
                case "kwitansi": case "paket": case "souvenir": break;
            }
            break;
    }

    if (!isset($kode_prefix)) {
        $is_valid_kode_prefix = is_valid_kode_prefix($kode1, $text);
        if (!$is_valid_kode_prefix[0]) return array(FALSE, $is_valid_kode_prefix[1]);
    } else if ($kode1 !== $kode_prefix) {
        return array(FALSE, "$text harus diawali dengan huruf $kode_prefix. <br>");
    }
    
    $is_valid_angka = is_valid_angka($kode2, "$text");
    if (!$is_valid_angka[0]) return array(FALSE, "$text harus mengikuti format {2~3 huruf}-{angka}. Contoh: A-1001, AB-976. <br>");

    if (strlen($kode) > 15) return array(FALSE, "$text lebih dari 15 karakter. <br>");
    
    return array(TRUE, $kode);
}

function is_valid_kode_prefix($kode, $object) {
    if (!preg_match("/^[a-z]{2,3}$/i", $kode)) {
        return array(FALSE, "$object harus harus diawali dengan 2~3 huruf. <br>");
    } else {
        return array(TRUE);
    }
}

function is_valid_nama($str, $object, $length = 50, $can_empty = FALSE) {
    if ($can_empty && is_empty($str)) {
        return array(TRUE);
    }

    if (!preg_match("/^[a-z\s\.]+$/i", $str) || strlen($str) > $length || strlen($str) === 0) {
        return array(FALSE, "$object harus berupa huruf & tidak boleh lebih dari $length karakter. <br>");
    } else {
        return array(TRUE);
    }
}

function is_valid_nama_mandarin($str, $object, $length = 50, $can_empty = FALSE) {
    if ($can_empty && is_empty($str)) {
        return array(TRUE);
    }

    $is_valid_nama = is_valid_nama($str, $object);
    if ((!$is_valid_nama[0] && !preg_match("/^[\p{L}\s\.\/\(\)]+$/u", $str)) || strlen($str) > $length || strlen($str) === 0) {
        return array(FALSE, "$object tidak valid / atau lebih dari $length karakter.<br>");
    } else {
        return array(TRUE);
    }
}

function is_valid_periode($periode, $can_empty = FALSE) {
    if ($can_empty && is_empty($periode)) {
        return array(TRUE);
    }

    $periode = explode(" ", $periode);
    if (count($periode) !== 2) return array(FALSE, "Format periode tidak valid. <br>");
    
    $is_valid_angka = is_valid_angka($periode[0], "Periode", 2, FALSE);
    if (!$is_valid_angka[0]) return array(FALSE, $is_valid_angka[1]);
    else if ($periode[0] > 999) return array(FALSE, "Periode lebih dari 999. <br>");

    $is_valid_kode_periode = $periode[1] == "H" || $periode[1] == "B" || $periode[1] == "T";
    if (!$is_valid_kode_periode) return array(FALSE, "Kode periode tidak valid. <br>");
    
    return array(TRUE);
}

function is_valid_str($str, $object, $length = 50, $can_empty = FALSE) {
    if ($can_empty && is_empty($str)) {
        return array(TRUE);
    }

    if (strlen($str) > $length || strlen($str) === 0) {
        return array(FALSE, "$object lebih dari $length karakter. <br>");
    } else {
        return array(TRUE);
    }
}

function is_valid_tanggal($tanggal, $object, $can_empty = FALSE) {
    if ($can_empty && is_empty($tanggal)) {
        return array(TRUE);
    }

    $tgl_arr = explode("-", $tanggal);
    if (count($tgl_arr) != 3) {
        $is_valid_tgl = FALSE;
    } else {
        if (!is_numeric($tgl_arr[0]) || !is_numeric($tgl_arr[1]) || !is_numeric($tgl_arr[2])) {
            $is_valid_tgl = FALSE;
        } else if (checkdate($tgl_arr[1], $tgl_arr[2], $tgl_arr[0])) {
            $is_valid_tgl = TRUE;
        } else {
            $is_valid_tgl = FALSE;
        }
    }

    if (!$is_valid_tgl) {
        return array(FALSE, "$object tidak valid. <br>"); 
    } else {
        return array(TRUE);
    }
}

function is_valid_telepon($tlp, $can_empty = FALSE) {
    if ($can_empty && is_empty($tlp)) {
        return array(TRUE);
    }

    if (!preg_match("/^(\+62|0)([\s\-])?[\d]{2,3}([\s\-])?[\d]{3,4}([\s\-])?[\d]{3,4}([\s\-])?(([\s\-])?[\d]{3,4})?(([\s\-])?[\d]{1,})?$/", $tlp) ||
        strlen($tlp) > 20) {
        return array(FALSE, "Nomor telepon tidak valid / panjang nomor lebih dari 20 karakter. <br>");
    } else {
        return array(TRUE);
    }
}
