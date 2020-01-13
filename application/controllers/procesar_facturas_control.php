<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Procesar_facturas_control extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('facturacionsendws_db2_model', '', TRUE);
	}

	function index() {

	}

	function procesar_facturas_nuevas(){
		//$datos['contenido'] = 'procesar_factura_view'; 
		$datos['titulo'] = 'Procesar Factura Electronica';		   
		$this->load->view('front_end/procesar_factura_view', $datos);		
	}

	function get_verificar_existe_fact(){
		$listdfe =array();
		$lstdocfe = $this->facturacionsendws_db2_model->get_verificar_existe_fact();		
		$numdata=odbc_num_rows($lstdocfe);
		if(($lstdocfe!=false)||($numdata>0)){
			while (odbc_fetch_row($lstdocfe)) {
				$SCTEFEC = trim(odbc_result($lstdocfe, 1)); 
				$SCTECIAA = trim(odbc_result($lstdocfe, 2)); 
				$SCTEPDCA = trim(odbc_result($lstdocfe, 3)); 
				$SCTESERI = trim(odbc_result($lstdocfe, 4));
				$SCTECORR = trim(odbc_result($lstdocfe, 5));
				$listdfe[] = array('SCTEFEC'=>$SCTEFEC,"SCTECIAA"=>$SCTECIAA,'SCTEPDCA'=>$SCTEPDCA,"SCTESERI"=>$SCTESERI,"SCTECORR"=>$SCTECORR);
			}
			$result = [
				"datos"=>$listdfe,
				"proceso"=>true
			];	
		}else{
			$result = [
				"datos"=>$listdfe,
				"proceso"=>false
			];
		}
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($result);
	}
function optener_status_webservice(){
			/*
            	$resulta='';
            	$datos_wsa=datos_webserviceAcceso($NUMIDENM);
				$usuario=$datos_wsa['usuario'];
				$clave=$datos_wsa['clave'];
            	$datossvr=dato_hostas();
            	$ip=$datossvr['ipas'];        
            	$datos_ws=datos_webservice($ip);
            	$url=$datos_ws['url'];
            	$urlref=$datos_ws['urlref'];
            	$ch = curl_init();
            	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            	curl_setopt($ch, CURLE_OPERATION_TIMEOUTED, 10);
            	curl_setopt($ch, CURLOPT_URL, $url);
            	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
            	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

            	$rslt = curl_exec ($ch);
            	$resulta = curl_getinfo($ch);

            	curl_close($ch);
            	
            	$httpcode=$resulta;
            	$dat['msg']='Con Datos';
            	$result['dato'] = ["tiempo"=>$httpcode["total_time"],"url"=>$httpcode["url"],"httpcode"=>$httpcode["http_code"],"data"=>$httpcode];	
            	$result['existe'] = 1;            	
            	header('Content-type: application/json; charset=utf-8');
            	echo json_encode($result); 
            	*/           	

            }

}
?>