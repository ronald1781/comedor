
<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class graficofe_control extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('general_model', '', TRUE);
		$this->load->model('graficofe_model', '', TRUE);
	}

	function index() {

	}
	function grafico_ventaxtipodocu(){
		$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'-01-01';
			$fechaaniofin=$anho.'-12-31';
			$bodygrafico=[];
			$txt_mes=array( "01"=>"Ene","02"=>"Feb","03"=>"Mar","04"=>"Abr","05"=>"May","06"=>"Jun",
				"07"=>"Jul",	"08"=>"Ago","09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dic"
			);

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
				$bodygrafico[]=array("Documento","Generados $anho","Enviados $anho");
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.'-'.$mes.'-'.$inidia;
				$fechamesfin=$anho.'-'.$mes.'-'.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.'-'.$dm.'-'.$dia;
				$fechamesfin=$anho.'-'.$dm.'-'.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
				$bodygrafico[]=array("Documento","Generados $mesd $anho","Enviados $mesd $anho");
			}else{
				$fechamesini=$anho.'-'.$mes.'-'.$dia;
				$fechamesfin=$anho.'-'.$mes.'-'.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
				$bodygrafico[]=array("Documento","Generados $dia $mes $anho","Enviados $dia $mes $anho");
			}
			$rsvxdx=$this->graficofe_model->ventaxdocumento($resultado);

			if($rsvxdx!=false){	
				/*
				$canti=(int)trim(odbc_result($rsvxdx, 2));
				if($canti>0){
					*/
					while (odbc_fetch_row($rsvxdx)) {					
						$documento = trim(odbc_result($rsvxdx, 1));
						$cantidad = (int)trim(odbc_result($rsvxdx, 2));
						$totalimporte = floatval(trim(odbc_result($rsvxdx, 3)));
						$bodygrafico[]=array($documento,$cantidad,$totalimporte);
					}
				/*}else{
					$documento = 'Sin Documentos';
					$cantidad = 0;
					$totalimporte = 0;
					$bodygrafico[]=array($documento,$cantidad,$totalimporte);
				}*/
			}else{
				$documento = 'Sin Datos';
				$cantidad = 0;
				$totalimporte = 0;
				$bodygrafico[]=array($documento,$cantidad,$totalimporte);
			}
			echo json_encode(($bodygrafico));
	/*	
$periodo=intval($_REQUEST['periodo']);
$txt_mes=array( "1"=>"Ene","2"=>"Feb","3"=>"Mar","4"=>"Abr","5"=>"May","6"=>"Jun",
				"7"=>"Jul",	"8"=>"Ago","9"=>"Sep","10"=>"Oct","11"=>"Nov",	"12"=>"Dic"
			 );//Arreglo que contiene las abreviaturas de los meses del año
 
	$periodo='2019';	
 
$categorias []= array('Mes',"Ingresos $periodo", "Egresos $periodo ");//Nombre de la primer fila del grafico
for ($inicio = 1; $inicio <= 12; $inicio++) {
    $mes=$txt_mes[$inicio];//Obtengo la abreviatura del mes
	$ingresos=monto('ingresos',$inicio,$periodo);//Obtengo el  monto de los ingresos
	$egresos=monto('egresos',$inicio,$periodo);//Obtengo el monto de los egresos
	$categorias []= array($mes,$ingresos,$egresos);//Agrego elementos al arreglo
	
	
}
echo json_encode( ($categorias) );//Convierto el arreglo a formato json
*/

}
function grafico_documento_importe(){
	$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$fecha=$anho.' '.$mes;
			$fechaanioini=$anho.'-01-01';
			$fechaaniofin=$anho.'-12-31';
			$bodygrafico=[];
			

			$resultado='';
			if($mes==''){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else{
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.'-'.$mes.'-'.$inidia;
				$fechamesfin=$anho.'-'.$mes.'-'.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;

			}

			$rsvxdx=$this->graficofe_model->grafico_documento_importe($resultado);

			if($rsvxdx!=false){	
				
				while (odbc_fetch_row($rsvxdx)) {					
					$documento = trim(odbc_result($rsvxdx, 1));
					$totalimporte = floatval(trim(odbc_result($rsvxdx, 2)));
					$bodygrafico['cols'][] = array('type' => 'string'); 
					$bodygrafico['rows'][] = array('c' => array( array('v'=> $documento), array('v'=>$totalimporte) ));

				}
				
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				$bodygrafico['cols'][] = array('type' => 'string'); 
				$bodygrafico['rows'][] = array('c' => array( array('v'=> $documento), array('v'=>$totalimporte) ));
			}
			echo json_encode(($bodygrafico));
		}
		function grafico_documento_cantidad(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}
			$data[] = array('Documentos','Cantidad');
			$rsvxdx=$this->graficofe_model->grafico_cantidad_anulados_x_tipodocu($resultado);
			if($rsvxdx!=false){					
				while (odbc_fetch_row($rsvxdx)) {					
					$documento = (trim(odbc_result($rsvxdx, 1))=='')?'Sin Datos':trim(odbc_result($rsvxdx, 1));
					$totalimporte = (floatval(trim(odbc_result($rsvxdx, 2)))==0)?0:floatval(trim(odbc_result($rsvxdx, 2)));
					$data[] = array($documento,(int)$totalimporte);
				}				
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				$data[] = array($documento,(int)$totalimporte);
			}

			echo json_encode($data);
		}
		function grafico_cantidad_activo_x_tipodocu(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}
			$data[] = array('Documentos','Cantidad');
			$rsvxdx=$this->graficofe_model->grafico_cantidad_activo_x_tipodocu($resultado);
			if($rsvxdx!=false){					
				while (odbc_fetch_row($rsvxdx)) {					
					$documento = (trim(odbc_result($rsvxdx, 1))=='')?'Sin Datos':trim(odbc_result($rsvxdx, 1));
					$totalimporte = (floatval(trim(odbc_result($rsvxdx, 2)))==0)?0:floatval(trim(odbc_result($rsvxdx, 2)));
					$data[] = array($documento,(int)$totalimporte);
				}				
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				$data[] = array($documento,(int)$totalimporte);
			}

			echo json_encode($data);
		}
		function grafico_cantidad_anulados_x_sucursal(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];	
			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}
			$data[] = array('Sucursal','Cantidad');
			$rsvxdx=$this->graficofe_model->grafico_cantidad_anulados_x_sucursal($resultado);
			if($rsvxdx!=false){					
				while (odbc_fetch_row($rsvxdx)) {					
					$documento = (trim(odbc_result($rsvxdx, 1))=='')?'No_hay_Datos':ucwords(strtolower(trim(odbc_result($rsvxdx, 1))));
					$totalimporte = (floatval(trim(odbc_result($rsvxdx, 2)))==0)?0.0:floatval(trim(odbc_result($rsvxdx, 2)));
					$data[] = array($documento,$totalimporte);
				}				
			}else{
				$documento = 'Sin Sucursal';
				$totalimporte = (int)0.0;
				$data[] = array($documento,$totalimporte);
			}
			echo json_encode($data);
		}
		function grafico_total_documentos(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}
			$rows = array();
			$table = array();
			$table['cols'] = array(

				array('label' => 'Indicador', 'type' => 'string'),
				array('label' => 'Cantidad', 'type' => 'number')

			);
			$rsvxdx=$this->graficofe_model->grafico_total_documentos($resultado);
			$totaldocunu=0;
			if($rsvxdx!=false){					
				while (odbc_fetch_row($rsvxdx)) {
					$totaldocucar = (string)number_format((trim(odbc_result($rsvxdx, 1))=='')?0:trim(odbc_result($rsvxdx, 1)),2,".",",");
					//$totaldocucar = "TotalDocu. :$totaldocuca";//.number_format($convtc,2,".",",");
					$totaldocunum =(int)(trim(odbc_result($rsvxdx, 1))==0)?0:trim(odbc_result($rsvxdx, 1));
					//$totaldocunum = floatval($totaldocunu);
					//$bodygrafico['cols'][] = array('type' => 'string'); 
					//$bodygrafico['rows'][] = array('c' => array( array('v'=> $totaldocucar), array('v'=>$totaldocunum) ));
					$temp = array();
					$temp[] = array('v' => (string) $totaldocucar); 
					$temp[] = array('v' => (int) $totaldocunum); 
					$rows[] = array('c' => $temp);
				}
				
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				//$bodygrafico['cols'][] = array('type' => 'string'); 
				//$bodygrafico['rows'][] = array('c' => array( array('v'=> $documento), array('v'=>$totalimporte) ));
				$temp = array();    
				$temp[] = array('v' => (string) $totaldocucar); 
				$temp[] = array('v' => (int) $totaldocunum); 
				$rows[] = array('c' => $temp);
			}
			//print_r($bodygrafico);
			//echo json_encode(($bodygrafico));
			/* Extract the information from $result */
   // foreach($result as $r) {     
   // }
			$table['rows'] = $rows;
			echo json_encode($table);
		}

		function grafico_total_documentos_activos(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}
			$rows = array();
			$table = array();
			$table['cols'] = array(
				array('label' => 'Indicador', 'type' => 'string'),
				array('label' => 'Cantidad', 'type' => 'number')
			);
			$rsvxda=$this->graficofe_model->grafico_total_documentos_activos($resultado);
			$totaldocunu=0;
			if($rsvxda!=false){					
				while (odbc_fetch_row($rsvxda)) {
					$totaldocucar = (string)number_format((trim(odbc_result($rsvxda, 1))==0)?0:trim(odbc_result($rsvxda, 1)),2,".",",");
					$totaldocunum =(int)(trim(odbc_result($rsvxda, 1))==0)?0:trim(odbc_result($rsvxda, 1));
					$temp = array();
					$temp[] = array('v' => (string) $totaldocucar); 
					$temp[] = array('v' => (int) $totaldocunum); 
					$rows[] = array('c' => $temp);
				}
				
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				$temp = array();    
				$temp[] = array('v' => (string) $totaldocucar); 
				$temp[] = array('v' => (int) $totaldocunum); 
				$rows[] = array('c' => $temp);
			}
			
			$table['rows'] = $rows;
			echo json_encode($table);
		}

		function grafico_total_documentos_inactivos(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}
			$rows = array();
			$table = array();
			$table['cols'] = array(
				array('label' => 'Indicador', 'type' => 'string'),
				array('label' => 'Cantidad', 'type' => 'number')
			);
			$rsvxda=$this->graficofe_model->grafico_total_documentos_inactivos($resultado);
			$totaldocunu=0;
			if($rsvxda!=false){					
				while (odbc_fetch_row($rsvxda)) {
					$totaldocucar = (string)number_format((trim(odbc_result($rsvxda, 1))==0)?0:trim(odbc_result($rsvxda, 1)),2,".",",");
					$totaldocunum =(int)(trim(odbc_result($rsvxda, 1))==0)?0:trim(odbc_result($rsvxda, 1));
					$temp = array();
					$temp[] = array('v' => (string) $totaldocucar); 
					$temp[] = array('v' => (int) $totaldocunum); 
					$rows[] = array('c' => $temp);
				}
				
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				$temp = array();    
				$temp[] = array('v' => (string) $totaldocucar); 
				$temp[] = array('v' => (int) $totaldocunum); 
				$rows[] = array('c' => $temp);
			}
			
			$table['rows'] = $rows;
			echo json_encode($table);
		}

		function grafico_donus_documentos(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
			}
			$rows = array();
			$table = array(); 
			$datas=array();
			$table['cols'] = array(
				array('label' => 'Indicador', 'type' => 'string'),
				array('label' => 'Cantidad', 'type' => 'number')
			);
			$totaldocemid="Emitidos";
			$totaldocanud="Activos";
			$totaldocinad="Anulados";
			$rsvxdx=$this->graficofe_model->grafico_total_documentos($resultado);
			$rsvxda=$this->graficofe_model->grafico_total_documentos_activos($resultado);
			$rsvxdi=$this->graficofe_model->grafico_total_documentos_inactivos($resultado);

			if(($rsvxdx<>false)||($rsvxda<>false)||($rsvxdi<>false)){
				
				$totaldocemi =(int)(trim(odbc_result($rsvxdx, 1))==0)?'0':trim(odbc_result($rsvxdx, 1));
				$totaldocanu =(int)(trim(odbc_result($rsvxda, 1))==0)?'0':trim(odbc_result($rsvxda, 1));
				$totaldocina =(int)(trim(odbc_result($rsvxdi, 1))==0)?'0':trim(odbc_result($rsvxdi, 1));							

				$datas[]=array('desc'=>$totaldocemid,"cant"=>$totaldocemi);
				$datas[]=array('desc'=>$totaldocanud,'cant'=>$totaldocanu);
				$datas[]=array('desc'=>$totaldocinad,'cant'=>$totaldocina);
				
			}else{
				
				$totaldocemi =0;
				$totaldocanu =0;
				$totaldocina =0;

				$datas[]=array('desc'=>$totaldocemid,"cant"=>$totaldocemi);
				$datas[]=array('desc'=>$totaldocanud,'cant'=>$totaldocanu);
				$datas[]=array('desc'=>$totaldocinad,'cant'=>$totaldocina);			

			}
			for($i=0;$i<count($datas);$i ++){
				$cad=$datas[$i];
				$desc=(string) $cad['desc'];
				$cant=(int) $cad['cant'];
				$temp = array();
				$temp[] = array('v' => (string) $desc); 
				$temp[] = array('v' => (int) $cant); 
				$rows[] = array('c' => $temp);
				
			}

			
			$table['rows'] = $rows;
			//print_r($table);
			echo json_encode($table);
		}


		function grafico_total_documentos_activos_linea(){
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			$flg='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN '".$fechaanioini."' AND '".$fechaaniofin."'";
				$resultado=$ranfoconsulta;
				$flg=0;//año
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
				$flg=1;//mes
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
				$flg=2;//dia
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
				$resultado=$ranfoconsulta;
				$flg=2;//dia
			}
			$rows = array();
			$table = array();
			$table['cols'] = array(
				array('label' => 'Indicador', 'type' => 'string'),
				array('label' => 'Anulados', 'type' => 'number'),
				array('label' => 'Activos', 'type' => 'number')
			);
			$rsvda=$this->graficofe_model->grafico_total_documentos_activos_linea($resultado,$flg);
			$totaldocunu=0;
			//and YHJTM between ".."0000 and ".."5959

			if($rsvda!=false){
				while (odbc_fetch_row($rsvda)) {
					$param = trim(odbc_result($rsvda, 1));
					if($flg==0){
						$mess=$param;
						$findia=$this->UltimoDia($anho,$mess);
						$fechamesini=$anho.$mess.$inidia;
						$fechamesfin=$anho.$mess.$findia;
						$ranfconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";

					}else if($flg==1)
					{
						$dias=$param;
						$findia=$this->UltimoDia($anho,$mes);
						$fechamesini=$anho.$mes.$dias;
						$fechamesfin=$anho.$mes.$dias;
						$ranfconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."'";
					}else{
						$hora=(int)$param;
						$findia=$this->UltimoDia($anho,$mes);
						$fechamesini=$anho.$mes.$dia;
						$fechamesfin=$anho.$mes.$dia;
						$ranfconsulta="BETWEEN '".$fechamesini."' AND '".$fechamesfin."' and YHJTM between ".$hora."0000 and ".$hora."5959";
					}
					$rsvdadg=$this->graficofe_model->grafico_total_documentos_activos_linea_detag($ranfconsulta,$flg);					
					$totaldocunumdg =(int)(trim(odbc_result($rsvdadg, 1))==0)?0:trim(odbc_result($rsvdadg, 1));
					$rsvdada=$this->graficofe_model->grafico_total_documentos_activos_linea_detaa($ranfconsulta,$flg);					
					$totaldocunumda =(int)(trim(odbc_result($rsvdada, 1))==0)?0:trim(odbc_result($rsvdada, 1));					
					$temp = array();
					$temp[] = array('v' => (string) str_pad($param,2,"0",STR_PAD_LEFT)); 
					$temp[] = array('v' => (int) $totaldocunumdg); 
					$temp[] = array('v' => (int) $totaldocunumda); 
					$rows[] = array('c' => $temp);
				}
				
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				$temp = array();    
				$temp[] = array('v' => (string) $totaldocucar); 
				$temp[] = array('v' => (int) $totaldocunum); 
				$rows[] = array('c' => $temp);
			}
			
			$table['rows'] = $rows;
			echo json_encode($table);
		}
		function grafico_total_documentos_inactivos_vendedor(){
			$inidia='01';
			$inidia='01';
			$anho =$_REQUEST['anio'];//$this->input->post('anio', true);
			$mes = $_REQUEST['mes'];//$this->input->post('mes', true);
			$dia = $_REQUEST['dia'];
			$fecha=$anho.' '.$mes;
			$fechad=$anho.' '.$mes.' '.$dia;
			$fechaanioini=$anho.'0101';
			$fechaaniofin=$anho.'1231';
			$bodygrafico=[];			

			$resultado='';
			if(($mes=='')&&($dia=='')){
				$ranfoconsulta="BETWEEN ".$fechaanioini." AND ".$fechaaniofin;
				$resultado=$ranfoconsulta;
			}else if(($mes!='')&&($dia=='')){
				$findia=$this->UltimoDia($anho,$mes);
				$fechamesini=$anho.$mes.$inidia;
				$fechamesfin=$anho.$mes.$findia;
				$ranfoconsulta="BETWEEN ".$fechamesini." AND ".$fechamesfin;
				$resultado=$ranfoconsulta;
			}else if(($mes=='')&&($dia!='')){
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fechamesini=$anho.$dm.$dia;
				$fechamesfin=$anho.$dm.$dia;
				$ranfoconsulta="BETWEEN ".$fechamesini." AND '".$fechamesfin;
				$resultado=$ranfoconsulta;
			}else{
				$fechamesini=$anho.$mes.$dia;
				$fechamesfin=$anho.$mes.$dia;
				$ranfoconsulta="BETWEEN ".$fechamesini." AND ".$fechamesfin;
				$resultado=$ranfoconsulta;
			}
			$rows = array();
			$table = array();
			$table['cols'] = array(
				array('label' => 'Vendedor', 'type' => 'string'),
				array('label' => 'Factura', 'type' => 'number'),
				array('role'=>'annotation','type'=> 'string'),
				array('role'=>'style','type'=> 'string'),
				array('label' => 'Boleta', 'type' => 'number'),
				array('role'=>'annotation','type'=> 'string'),
				array('role'=>'style','type'=> 'string'),
				array('label' => 'N.Credito', 'type' => 'number'),
				array('role'=>'annotation','type'=> 'string'),
				array('role'=>'style','type'=> 'string'),
				array('label' => 'N.Debito', 'type' => 'number'),
				array('role'=>'annotation','type'=> 'string'),
				array('role'=>'style','type'=> 'string'),
			);

			//$data[] = array('Vendedor','Factura','Boleta','N.Credito','N.Debito');

			$rsdoc=$this->graficofe_model->grafico_lista_documen($resultado);
			$rsvnd=$this->graficofe_model->grafico_lista_vendedor($resultado);
			$totaldocunu=0;
			$totaldocu1=0; 
			$totaldocu3=0; 
			$totaldocu7=0; 
			$totaldocu8=0;
			$colordocu1='';
						$colordocu3='';
									$colordocu7='';
												$colordocu8='';
			if($rsdoc!=false){
				while (odbc_fetch_row($rsdoc)) {
					$CODDOC = trim(odbc_result($rsdoc, 1));
					if($rsvnd!=false){
						while (odbc_fetch_row($rsvnd)) {							
							$CODVEN = trim(odbc_result($rsvnd, 1));
							$NOMVEN = trim(odbc_result($rsvnd, 2));
							$APEVEN = trim(odbc_result($rsvnd, 3));
							$USRVEN = trim(odbc_result($rsvnd, 4));

							switch ($CODDOC) {
								case '01':
								$rsvcnt1=$this->graficofe_model->grafico_cantidad_anulados_x_vendedor($resultado,$CODDOC,$CODVEN);
								$totaldocu1 =(int)(trim(odbc_result($rsvcnt1, 1))==0)?0:trim(odbc_result($rsvcnt1, 1));
								$colordocu1='#C0392B';
								break;
								case '03':
								$rsvcnt3=$this->graficofe_model->grafico_cantidad_anulados_x_vendedor($resultado,$CODDOC,$CODVEN);
								$totaldocu3 =(int)(trim(odbc_result($rsvcnt3, 1))==0)?0:trim(odbc_result($rsvcnt3, 1));
								$colordocu3='#E74C3C';
								break;
								case '07':
								$rsvcnt7=$this->graficofe_model->grafico_cantidad_anulados_x_vendedor($resultado,$CODDOC,$CODVEN);
								$totaldocu7 =(int)(trim(odbc_result($rsvcnt7, 1))==0)?0:trim(odbc_result($rsvcnt7, 1));
								$colordocu7='#D35400';
								break;
								case '08':
								$CODVEN1='';
								$rsvcnt8=$this->graficofe_model->grafico_cantidad_anulados_x_vendedor($resultado,$CODDOC,$CODVEN1);
								$totaldocu8 =(int)(trim(odbc_result($rsvcnt8, 1))==0)?0:trim(odbc_result($rsvcnt8, 1));
								$colordocu8='#E67E22';
								break;
							}							
							$temp = array();
							$temp[] = array('v' => (string) $USRVEN); 
							$temp[] = array('v' => (int) $totaldocu1); 							
							$temp[] = array('v' => (string) $totaldocu1);
							$temp[] = array('v' => (string) $colordocu1);
							$temp[] = array('v' => (int) $totaldocu3); 
							$temp[] = array('v' => (string) $totaldocu3);
							$temp[] = array('v' => (string) $colordocu3);
							$temp[] = array('v' => (int) $totaldocu7); 
							$temp[] = array('v' => (string) $totaldocu7);
							$temp[] = array('v' => (string) $colordocu7);
							$temp[] = array('v' => (int) $totaldocu8);
							$temp[] = array('v' => (string) $totaldocu8);
							$temp[] = array('v' => (string) $colordocu8);
							$rows[] = array('c' => $temp);
						}
					}
				}
			}else{
				$documento = 'Sin Datos';
				$totalimporte = 0;
				$temp = array();    
				$temp[] = array('v' => (string) $totaldocucar); 
				$temp[] = array('v' => (int) $totaldocunum); 
				$rows[] = array('c' => $temp);
			}
			
			$table['rows'] = $rows;
			echo json_encode($table);
		}

		function UltimoDia($anho,$mes){ 
			if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
				$dias_febrero = 29; 
			} else { 
				$dias_febrero = 28; 
			} 
			switch($mes) { 
				case '01': return 31; break; 
				case '02': return $dias_febrero; break; 
				case '03': return 31; break; 
				case '04': return 30; break; 
				case '05': return 31; break; 
				case '06': return 30; break; 
				case '07': return 31; break; 
				case '08': return 31; break; 
				case '09': return 30; break; 
				case '10': return 31; break; 
				case '11': return 30; break; 
				case '12': return 31; break; 
			} 

		}


	}
	?>