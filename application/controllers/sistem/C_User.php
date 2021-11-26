<?php defined('BASEPATH') OR exit('No direct script access allowed');
class C_User {
    private $method;
    private $user;
    private $username;
    private $pass;
    private $pass1;

    function __construct() {
        $this->set_method(func_get_arg(0));
        $this->set_username(func_get_arg(1));
        $this->set_pass(func_get_arg(2));
        $this->set_pass1(func_get_arg(3));
        $this->set_user();
    }

    function is_valid_user() {
        $is_valid_username = is_valid_alphanumeric($this->username, "Username", 20);
        $is_valid_pass = is_valid_str($this->pass, "Password", 30);

        if (!$is_valid_username[0]) $errors[] = $is_valid_username[1]; 
        if (!$is_valid_pass[0]) $errors[] = $is_valid_pass[1];
        else if ($this->pass !== $this->pass1) $errors[] = "Password dan konfirmasi password tidak sama. <br>"; 

        $CI =& get_instance();
        $result = $CI->pbs->tuser_get(array(
            "select" => "username", 
            "filter" => array("username" => $this->username)
        ))->num_rows();
        if (($this->method === "INSERT" || ($this->method === "UPDATE" && $this->username != $CI->session->username)) && $result > 0) {
            $errors[] = "Username telah terdaftar. <br>";
        }

        if (isset($errors)) {
            return array(FALSE, $errors);
        } else {
            return array(TRUE);
        }
    }

    /** accessors and mutators */
    function get_user() {
        return $this->user;
    }

    function set_user() {
        $this->user["username"] = $this->get_username();
        $this->user["pass"] = $this->get_pass();
    }

    function get_method() {
        return $this->method;
    }

    function set_method($method) {
        $this->method = $method;
    }

    function get_username() {
        return $this->username;
    }

    function set_username($username) {
        $this->username = $username;
    }

    function get_pass() {
        return $this->pass;
    }

    function set_pass($pass) {
        $this->pass = $pass;
    }

    function get_pass1() {
        return $this->pass1;
    }

    function set_pass1($pass1) {
        $this->pass1 = $pass1;
    }
}

