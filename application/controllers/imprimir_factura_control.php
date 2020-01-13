
<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Imprimir_factura_control extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('facturacionsendws_db2_model', '', TRUE);
		$this->load->library('fpdf_lib');
	}

	function index() {

	}
	

	function print_automaticofe1(){
		$pdf = new fpdf_lib(); 
		$pdf->AddPage(); 
		$pdf->SetFont('Arial','',16); 
		$pdf->Ln(); 
		$x_axis=$pdf->getx(); 
		$c_width=20; 
		$c_height=6; 
		$text="aim success "; 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hi1');
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hi2'); 
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hi3'); 
		$pdf->Ln(); 
		$x_axis=$pdf->getx(); 
		$c_width=20; 
		$c_height=12; 
		$text="aim success "; 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hi4'); 
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hi5(xtra)'); 
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hi5'); 
		$pdf->Ln(); 
		$x_axis=$pdf->getx(); 
		$c_width=20; 
		$c_height=12; 
		$text="All the best"; 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hai'); 
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,'VICKY'); 
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,$text); 
		$pdf->Ln(); 
		$x_axis=$pdf->getx(); 
		$c_width=20; 
		$c_height=6; 
		$text="Good"; 
		$pdf->vcell($c_width,$c_height,$x_axis,'Hai'); 
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,'vignesh'); 
		$x_axis=$pdf->getx(); 
		$pdf->vcell($c_width,$c_height,$x_axis,$text); 


		$pdf->Output();
	}
function print_automaticofe(){
	$pdf = new fpdf_lib(); 
		$pdf->AddPage(); 
		$pdf->SetFont('Arial','',16); 
		$pdf->Ln();
$miCabecera = array('Nombre de campo', 'Apellido', 'Matrícula campo');
$misDatos = array(
            array('nombre' => 'Esperbeneplatoledo', 'apellido' => 'Martínez', 'matricula' => '20420423'),
            array('nombre' => 'Araceli', 'apellido' => 'Morales', 'matricula' =>  '204909'),
            array('nombre' => 'Georginadavabulus', 'apellido' => 'Galindo', 'matricula' =>  '2043442'),
            array('nombre' => 'Luis', 'apellido' => 'Dolores', 'matricula' => '20411122'),
            array('nombre' => 'Mario', 'apellido' => 'Linares', 'matricula' => '2049990'),
            array('nombre' => 'Viridianapaliragama', 'apellido' => 'Badillo', 'matricula' => '20418855'),
            array('nombre' => 'Yadiramentoladosor', 'apellido' => 'García', 'matricula' => '20443335')
            );


$pdf->tablaHorizontal($miCabecera, $misDatos);


$pdf->Output();
}


}
?>