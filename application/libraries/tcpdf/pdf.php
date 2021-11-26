<?php
require_once("tcpdf.php");
class PDF extends TCPDF {
    private $margin;
    private $cell_height;

    function __construct($show_header = TRUE, $show_footer = TRUE, $orientation = "P", $size = "A4", $margin = array(1,20), $auto_page_break_margin = 7, 
    $cell_height = 5) {
        $this->margin = $margin;
        $this->cell_height = $cell_height;
        parent::__construct($orientation, "mm", $size);
        parent::setPrintHeader($show_header);
        parent::setPrintFooter($show_footer);
        parent::SetAutoPageBreak(TRUE, $auto_page_break_margin);
        parent::setMargins($this->margin[0], $this->margin[1]);
        parent::SetLineStyle(array("color" => array(150, 150, 150)));
        parent::SetFillColor(225);
        parent::AddPage();
    }

    function get_computed_width($width) {
        return $this->GetPageWidth() * $width;
    }

    function draw_line() {
        parent::Line($this->margin[0], parent::GetY(), $this->GetPageWidth() + $this->margin[0], parent::GetY());
    }

    /** Override */
    function Header() {
        $header_data = get_company_info("PBS");
        $x = $this->margin[0];
        if (check_file($header_data["logo"])) {
            parent::Image($header_data["logo"], 0, 0, 20, 15);
            $x = 20;
        }
        parent::SetFont("cid0cs", "B", 12);
        parent::setX($x);
        parent::cell(0, 5, $header_data["company"], 0, 1);
        parent::SetFont("cid0cs", "", 11);
        parent::setX($x);
        parent::cell(0, 5, "Alamat: " .$header_data["address"], 0, 1);
        parent::setX($x);
        parent::cell(0, 5, "Telp / Wa: " .$header_data["phone"], 0, 1);
        parent::SetLineStyle(array("color" => array(0, 64, 128)));
        parent::Ln(1);
        $this->draw_line();
    }

    function Footer() {
        parent::setY(-6);
        parent::SetLineStyle(array("color" => array(0, 64, 128)));
        $this->draw_line();
        parent::SetFont("Times", "", 9);
        parent::cell($this->get_computed_width(.5), $this->cell_height, get_instance()->session->username. " (" .date("d-m-Y H:i:s"). ")");
        parent::cell($this->get_computed_width(.5), $this->cell_height, "Page " .parent::GetAliasNumPage(). " / " .parent::GetAliasNbPages(), 0, 0, "R");
    }

    function GetPageWidth($pagenum = "") {
        return parent::GetPageWidth($pagenum) - $this->margin[0] * 2;
    }

    function GetPageHeight($pagenum = "") {
        return parent::GetPageHeight($pagenum) - $this->margin[1] * 2;
    }

    /** accessors and mutators */
    function get_margin() {
        return $this->margin;
    }

    function set_margin($margin) {
        $this->margin = $margin;
    }

    function get_cell_height() {
        return $this->cell_height;
    }

    function set_cell_height($cell_height) {
        $this->cell_height = $cell_height;
    }
}