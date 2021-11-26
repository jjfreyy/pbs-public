<?php defined('BASEPATH') OR exit('No direct script access allowed');
function check_assets_file($file = "") {
    return file_exists("d://web/xampp/htdocs/src/$file") || file_exists("e://web/xampp/htdocs/src/$file");
}

function check_file($file) {
    return !empty(glob($file));
}

function check_session() {
    if (check_file("activation.txt")) {
        $CI =& get_instance();
        $username = $CI->session->username;
        $app_list = $CI->session->app_list;
        $app = explode(" ", file_get_contents("activation.txt"));
        if (!(!is_empty($username) && $CI->pbs->tuser_get(array("select" => "username", "filter" => array("username" => $username)))->num_rows() === 1 && 
        count($app) === 2 && in_array($app[0], $app_list) && $app[1] == 1)) redirect("login");
    } else {
        redirect("login");
    }
}

function convert_currency_tonumber($number) {
    $number = str_replace(".", "", $number);
    return str_replace(",", ".", $number);
}

function convert_number_tocurrency($number) {
    if (is_empty($number) || $number == "-") return "-";
    $number_arr = explode(".", $number);
    if (!isset($number_arr[1]) || (isset($number_arr[1]) && $number_arr[1] == 0)) $decimal = 0;
    else $decimal = 2;

    return number_format($number, $decimal, ",", ".");
}

function debug($text = "test") {
    echo "<script>console.log('$text')</script>";
}

function format_date($date, $separator = "-", $include_time = FALSE) {
    return strftime("%d$separator%m$separator%Y" .($include_time ? " %T" : ""), strtotime($date));
}

function generate_code($str) {
    $str = explode(" ", strtoupper($str));
    if (count($str) > 1) {
        return $str[0][0].$str[1][0].(isset($str[2]) ? $str[2][0] : "");
    } else if (is_empty($str[0])) {
        return NULL;
    } else {
        return substr($str[0], 0, 3);
    }
}

function get_abbreviation($str) {
    $str1 = explode(" ", $str);
    if (count($str1) > 1) {
        return strtoupper(substr($str1[0], 0, 1).substr($str1[1], 0, 1).(isset($str1[2]) ? substr($str1[2], 0, 1) : ""));
    } else {
        return strtoupper(substr($str, 0, 3));
    }
}

function get_company_info($company) {
    switch ($company) {
        case "PBS":
            return array("logo" => "logo.png", "company" => "PUNDI BERKAH SUKACITA", "address" => "Jl. Semeru No.1166 Palembang", 
            "phone" => "0711-352620 / 0853-5252-0200");
    }
}

function get_period($tgl1, $tgl2) {
    $tgl1_arr = explode("-", $tgl1);
    $tgl2_arr = explode("-", $tgl2);

    if ($tgl1_arr[0] == $tgl2_arr[0] && $tgl1_arr[1] == $tgl2_arr[1]) {
        $periode = get_month_name($tgl1_arr[1]). " " .$tgl1_arr[0];
    } else if ($tgl1_arr[0] == $tgl2_arr[0] && substr("0$tgl1_arr[1]", -2) == "01" && substr("0$tgl2_arr[1]", -2) == "12" && 
        $tgl1_arr[2] == "01" && $tgl2_arr[2] == "31") {
        $periode = $tgl1_arr[0];
    } else {
        $periode = $tgl1_arr[2]. " " .get_month_name($tgl1_arr[1]). " " .$tgl1_arr[0]. " Sampai Dengan " .$tgl2_arr[2]. " " .
        get_month_name($tgl2_arr[1]). " " .$tgl2_arr[0];
    }

    return "Periode $periode";
}

function get_days_in_month($tahun, $bulan) {
    return cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
}

function get_month_name($kode) {
    switch ($kode) {
        case "1": case "01": return "Januari";
        case "2": case "02": return "Februari";
        case "3": case "03": return "Maret";
        case "4": case "04": return "April";
        case "5": case "05": return "Mei";
        case "6": case "06": return "Juni";
        case "7": case "07": return "Juli";
        case "8": case "08": return "Agustus";
        case "9": case "09": return "September";
        case "10": return "Oktober";
        case "11": return "November";
        case "12": return "Desember";
    }
}

function get_error_page() {
    return "unknown";
}

function get_form_report($type, $contents) {
    if (is_array($contents)) {
        $contents = implode("", $contents);
    } else if ($type === "success") {
        $contents = "Data $contents berhasil ditambahkan / diubah. <br>";
    } else if ($type === "error") {
        $contents = "Gagal menambahkan data $contents. Silakan coba kembali. <br>";
    }

    return "<p id='report' class='$type'>$contents</p>";
}

function get_json_response($type, $status, $message) {
    $json["status"] = $status;
    switch ($type) {
        case "delete":
            switch ($status) {
                case "error":
                    $json["message"] = "Gagal menghapus $message. Silakan coba kembali.";
                    break;
                case "success":
                    $json["message"] = "Berhasil menghapus $message.";
            }
            break;
        case "custom":
            $json["message"] = $message;
            break;
    }

    return json_encode($json);
}

function if_empty_then($value, $check_zero_value = TRUE, $assign = "-") {
    if (is_empty($value)) return $assign;
    if ($check_zero_value && $value == 0) return $assign;
    return $value;
}

function load_css() {
    $path = "/src/css/";
    $ext = ".css";
    if (!check_assets_file()) {
        $path = base_url($path);
        $ext = ".min$ext";
    }

    for($i = 0; $i < func_num_args(); $i++) {
        $file = func_get_arg($i);
        echo "<link href='$path$file$ext' rel='stylesheet' type='text/css' />";        
    }
}

function load_js() {
    $path = "/src/js/";
    $ext = ".js";
    $is_exist_assets = check_assets_file();
    if (!$is_exist_assets) {
        $path = base_url($path);
        $ext = ".min$ext";
    }

    echo "<script src='$path/lib/jquery-3.4.1.min.js'></script>";
    echo "
    <script>
        var base_url = '" .base_url(). "';
        var src_base_url = '" .($is_exist_assets ? "/src/" : base_url("src/")). "';
    </script>";
    for($i = 0; $i < func_num_args(); $i++) {
        $file = func_get_arg($i);
        $path1 = "$path$file$ext";
        
        if ($is_exist_assets) {
            if (!check_assets_file("js/$file$ext")) {
                $path1 = base_url($path1);
            }
        }
        echo "<script src='$path1'></script>";
    }
}

function prepare_flashdata() {
    $CI =& get_instance();
    $length = func_num_args();
    for ($i = 0; $i < $length; $i++) {
        $name = func_get_arg($i)[0];
        $val = func_get_arg($i)[1];
        $CI->session->set_flashdata($name, $val);
    }
}

function sanitize($data) {
    return strip_tags(addslashes(trim($data)));
}