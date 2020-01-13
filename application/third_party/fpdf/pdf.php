<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH . "/third_party/fpdf/fpdf.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Pdf extends FPDF {

    public function __construct() {
        parent::__construct();
    }

    public function Header() {

        $this->image('assest\imagen\logo.jpg', 20, 20, 25);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(54, 20, utf8_decode('Sistema Gestion de Calidad'), 0, 0, 'R');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(36, 20, utf8_decode('M&M-GTI-F-009'), 0, 0, 'R');
        $this->Ln('5');
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(30);
        $this->Cell(120, 20, utf8_decode('REGISTRO DE ENTREGAS '), 0, 0, 'C');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(27, 20, utf8_decode('Versión 01'), 0, 0, 'L');
        $this->Ln('5');
        $this->Cell(30);
        $this->Cell(120, 20, utf8_decode('(EQUIPOS INFORMÁTICOS)'), 0, 0, 'C');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(27, 20, utf8_decode('18/09/2013'), 0, 0, 'L');
        $this->ln(6);
        $this->Ln(20);
    }

    // El pie del pdf
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0);
        $this->Cell(0, 20, "Tecnologia de Informacion !", 'T', 0, 'C');
        $this->Ln('2');
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}
?>

