<?php
class Backup extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database("pbs");
        $this->load->dbutil();
        $this->load->helper("download");
    }

    function index() {
        $current_date = date("ymdHis");
        $format = "zip";
        $filename = "pbs-$current_date.$format";
        $config = array(
            "format" => $format,
            "filename" => $filename,
            "foreign_key_checks" => FALSE
        );
        $backup = $this->dbutil->backup($config);
        force_download($filename, $backup);
    }
}