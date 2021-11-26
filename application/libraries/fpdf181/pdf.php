<?php
require_once("fpdf.php");
class PDF extends FPDF {
    private $margin;
    private $cell_height;

    function __construct($orientation = "P", $size = "A4", $margin = array(1,0), $cell_height = 5) {
        $this->margin = $margin;
        $this->cell_height = $cell_height;
        parent::__construct($orientation, "mm", $size);
        parent::AliasNBPages();
        parent::SetFont("times");
        parent::SetAutoPageBreak(TRUE);
        if (is_array($this->margin)) parent::setMargins($this->margin[0], $this->margin[1]);
        else parent::setMargins($this->margin, $this->margin);
        parent::setFillColor(220, 230, 241);
        parent::AddPage();
    }

    function get_computed_width($width) {
        return $this->GetPageWidth() * $width;
    }

    function draw_line() {
        parent::Line(parent::GetX(), parent::GetY(), $this->GetPageWidth(), parent::GetY());
    }

    /** Override */
    function GetPageWidth() {
        $margin = is_array($this->margin) ? $this->margin[0] : $this->margin;
        return parent::GetPageWidth() - $margin * 2;
    }

    function GetPageHeight() {
        $margin = is_array($this->margin) ? $this->margin[1] : $this->margin;
        return parent::GetPageHeight() - $margin * 2;
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