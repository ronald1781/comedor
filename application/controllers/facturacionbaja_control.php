<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Facturacionbaja_control extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('general_model', '', TRUE);
		$this->load->model('facturacionbaja_db2_model', '', TRUE);
		$this->load->library("nusoap_lib");
	}

	function index() {

	}

	function vista_bajas(){
		$valid = $this->session->userdata('validated');
		if ($valid == TRUE) {  
			$lstanio = $this->facturacionbaja_db2_model->listaanio();
			$json = array();
			$dato = array();
			$lisanio = array();
			while (odbc_fetch_row($lstanio)) {	
				$anio = trim(odbc_result($lstanio, 1));  
				$lisanio[] = array('anio'=>$anio);
			}
			$lstmes = $this->facturacionbaja_db2_model->listames();
			$json = array();
			$dato = array();
			$lismes = array();
			while (odbc_fetch_row($lstmes)) {
				$mes = trim(odbc_result($lstmes, 1));
				$dscmes=mes_letra($mes);  
				$lismes[] = array('mes'=>$mes,'dscmes'=>$dscmes);
			}
			$datos['lstmes'] = $lismes;
			$datos['lstanio'] = $lisanio;
			$datos['titulo'] = 'Baja documentos';
			$datos['contenido'] = 'febaja_documentos_view';    
			$this->load->view('includes/plantilla', $datos);
		} else {           
			redirect('login');
		}

	}

	function listar_bajas(){
		$iniciodia=1;
		$fecha='';
		$tbody='';
		$listadocumentos=array();
		$selanio = $this->input->post('selanio', true);
		$selmes = $this->input->post('selmes', true);
		$anho = strtoupper($this->security->xss_clean($selanio));
		$mes = strtoupper($this->security->xss_clean($selmes));
		$cantdias=$this->UltimoDia($anho,$mes);
		//$desde=$anho.$mes.'01';
		//$hasta=$anho.$mes.$cantdias;
		$conta=0;
		$stsctrm=array();
		$classtr='';
		while($iniciodia <= $cantdias) {
			$dia=str_pad($iniciodia,2,0,STR_PAD_LEFT);
			$fecha=$anho.$mes.$dia;
			$rscanrgtd=$this->facturacionbaja_db2_model->listadocumento($fecha);			
			if($rscanrgtd!=false){				
				while (odbc_fetch_row($rscanrgtd)) {					
					$FECHA = trim(odbc_result($rscanrgtd, 1));
					$CANFAC = trim(odbc_result($rscanrgtd, 2));
					$CANBOL = trim(odbc_result($rscanrgtd, 3));
					$CANND = trim(odbc_result($rscanrgtd, 4));
					$CANNC = trim(odbc_result($rscanrgtd, 5));
					$TOTDOC=$CANFAC+$CANBOL+$CANND+$CANNC;
					$rscanstsd=$this->facturacionbaja_db2_model->stsdocumentotrama($FECHA);
					
					if($rscanstsd!=FALSE){
						while (odbc_fetch_row($rscanstsd)) {					
							$SCTCSTS = trim(odbc_result($rscanstsd, 1));
							if($SCTCSTS=='E'){
								$rscanstsde=$this->facturacionbaja_db2_model->cantstsdocumentotrama($FECHA,$SCTCSTS);
								$TOTOSTSE = trim(odbc_result($rscanstsde, 1));
								$classtr=($TOTDOC==$TOTOSTSE)?'class="btn btn-warning"':'class="btn btn-info"';
							}elseif($SCTCSTS=='I'){
								$rscanstsdi=$this->facturacionbaja_db2_model->cantstsdocumentotrama($FECHA,$SCTCSTS);
								$TOTOSTSI = trim(odbc_result($rscanstsdi, 1));
								$classtr=($TOTDOC==$TOTOSTSI)?'class="btn btn-danger"':'class="btn btn-info"';

							}else{

								$classtr='class="btn btn-success"';

							}
						}


					}else{
						$classtr='class="btn btn-success"';
					}
					
					$ae=substr($FECHA, 0,4);
					$me=substr($FECHA, 4,2);
					$de=substr($FECHA, 6,2);
					$FECHAS=$de.'/'.$me.'/'.$ae;
					$conta=$conta+1;
					$tbody .= '<tr id="'.$conta.'" > <td align="center">' .$conta. '</td>
					<td align="center"> ' .$FECHAS.'</td>
					<td align="center">' .$CANFAC.'</td>
					<td align="center">' .$CANBOL.'</td>
					<td align="center">' .$CANNC. '</td>
					<td align="center">' .$CANND. ' </td>
					<td align="center">' .$TOTDOC. ' </td>'; 
					$tbody .='<td align="center"><a href="javascript:void()" title="Ver Detalle" '.$classtr.' onclick="get_DetalleAnulacion(' . "'" .$FECHA. "'," . $conta.')"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></a>  </td></tr>'; 
				}
			}else{
				$tbody .= '<tr><td colspan="8"> <font size="0px">Sin datos para mostrar</font> </td>  </tr>'; 	
			}	

			$iniciodia++;
		}
		$result = [
			"tbody" =>$tbody

		];
		$result['existe'] = 1;		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($result);
		
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
	function get_detalledocumentobaja(){

		$tbody='';
		$statusdoc='n';
		$listadocumentos=array();
		$fecha = $this->input->post('fecha', true);
		$fecha = strtoupper($this->security->xss_clean($fecha));

		$rsdtdocbaj=$this->facturacionbaja_db2_model->detalledocumentoanulado($fecha);
		$conta=0;
		$ae=substr($fecha, 0,4);
		$me=substr($fecha, 4,2);
		$de=substr($fecha, 6,2);
		$nommes=$de.' de '.mes_letra($me).' del '.$ae;
		$statusdoc='';
		$datas=array();
		if($rsdtdocbaj!=false){				
			while (odbc_fetch_row($rsdtdocbaj)) {
				$YHSUCDOC = trim(odbc_result($rsdtdocbaj, 1));
				$YHTIPDOC = trim(odbc_result($rsdtdocbaj, 2));
				$YHTIPDOCU = trim(odbc_result($rsdtdocbaj, 2));
				$YHNROPDC = trim(odbc_result($rsdtdocbaj, 3));
				$YHNUMSER = trim(odbc_result($rsdtdocbaj, 4));
				$YHNUMCOR = trim(odbc_result($rsdtdocbaj, 5));				
				$YHSTS=trim(odbc_result($rsdtdocbaj, 6));
				$YHCODSUC=trim(odbc_result($rsdtdocbaj, 7));
				
				if(($YHTIPDOC=='01') || ($YHTIPDOC=='03')){
					$rsdtdocbajscb=$this->facturacionbaja_db2_model->cbdocumentoanulado($fecha,$YHNROPDC,$YHSUCDOC,$YHNUMSER,$YHNUMCOR);
					
					if($rsdtdocbajscb==false){
						$statusdoc='N';
					}else{			
						$CBSTS=trim(odbc_result($rsdtdocbajscb, 1));			
						$statusdoc= ($CBSTS=='')?'I':$CBSTS;	
					}

				}elseif ($YHTIPDOC=='07') {
					$rsdtdocbajia=$this->facturacionbaja_db2_model->iadocumentoanulado($YHNROPDC,$YHSUCDOC);
					$IANROPDC = trim(odbc_result($rsdtdocbajia, 1));
					$YHNROPDC=$IANROPDC;
					$rsdtdocbajfx=$this->facturacionbaja_db2_model->fxdocumentoanulado($fecha,$IANROPDC,$YHSUCDOC,$YHNUMSER,$YHNUMCOR,$YHTIPDOC);
					
					if($rsdtdocbajfx==false){
						$statusdoc='N';
					}else{			
						$FXSTS=trim(odbc_result($rsdtdocbajfx, 1));			
						$statusdoc= ($FXSTS=='')?'I':$FXSTS;	
					}


				} elseif ($YHTIPDOC=='08') {
					$rsdtdocbajjr=$this->facturacionbaja_db2_model->jrdocumentoanulado($fecha,$YHNROPDC,$YHSUCDOC,$YHTIPDOC,$YHNUMSER,$YHNUMCOR);

					if($rsdtdocbajjr==false){
						$statusdoc='N';
					}else{			
						$JRSTS=trim(odbc_result($rsdtdocbajjr, 1));			
						$statusdoc= ($JRSTS=='')?'':$JRSTS;	
					}				

				}else{

				}
				$rsdtdocbajst=$this->facturacionbaja_db2_model->stdocumentoanulado($fecha,$YHNROPDC,$YHSUCDOC,$YHTIPDOC,$YHNUMSER,$YHNUMCOR);
				$SCTESERI = trim(odbc_result($rsdtdocbajst, 1));
				$SCTECORR = trim(odbc_result($rsdtdocbajst, 2));
				$SCTCCLIE = trim(odbc_result($rsdtdocbajst, 3));
				$SCTCRZSO = trim(utf8_encode(odbc_result($rsdtdocbajst, 4)));
				$SCTCTMON = trim(odbc_result($rsdtdocbajst, 5));
				$SCTGTOTA = trim(odbc_result($rsdtdocbajst, 6));
				$SCTCSTS =(trim(odbc_result($rsdtdocbajst, 7))=='')?'':trim(odbc_result($rsdtdocbajst, 7)); 
				$SCTCSTSA =(trim(odbc_result($rsdtdocbajst, 7))=='')?'':trim(odbc_result($rsdtdocbajst, 7));
				
				$conta=$conta+1;
//checked="checked"				
				$classtr=(($YHSTS==$statusdoc)&&($SCTESERI!=''))?'':'class="danger"';
				if(($YHSTS==$statusdoc)&&($SCTESERI!='')){
					$checked='';
				}elseif($SCTCSTSA==$YHSTS){
					$checked='disabled = "true"';
				}else{
					$checked='';
				}	
				if(($YHSTS==$statusdoc)&&($SCTESERI!='')){
					$checkeda='checked="checked"';
				}elseif($SCTCSTSA==$YHSTS){
					$checkeda='';
				}else{
					$checkeda='';
				}				
				$ESTADO=($YHSTS==$statusdoc)?$YHSTS:$statusdoc;	
				$DOCSERIE=($SCTESERI=='')?$YHNUMSER:$SCTESERI;
				$DOCCORRE=($SCTECORR=='')?$YHNUMCOR:$SCTECORR;
				$seriedoc=$DOCSERIE.' '.$DOCCORRE;
				$MONEDA=($SCTCTMON=='')?'':$SCTCTMON;
				$DATACLIE=($SCTCRZSO=='')?'':$SCTCRZSO;
				$IMPORTE=($SCTGTOTA=='')?0.00:$SCTGTOTA;
				$STATRAM=($SCTCSTS=='')?'':$SCTCSTS;
				switch ($ESTADO) {
					case 'A':
					$ESTADO='<span class="label label-success">'.$ESTADO.'</span>';
					break;
					case 'E':
					$ESTADO='<span class="label label-warning">'.$ESTADO.'</span>';
					break;
					case 'I':
					$ESTADO='<span class="label label-danger">'.$ESTADO.'</span>';
					break;
					
					default:
					$ESTADO='<span class="label label-default">'.$ESTADO.'</span>';
					break;
				}

				switch ($STATRAM) {
					case 'A':
					$STATRAM='<span class="label label-success">'.$STATRAM.'</span>';
					break;
					case 'E':
					$STATRAM='<span class="label label-warning">'.$STATRAM.'</span>';
					break;
					case 'I':
					$STATRAM='<span class="label label-danger">'.$STATRAM.'</span>';
					break;
					
					default:
					$STATRAM='<span class="label label-default">'.$STATRAM.'</span>';
					break;
				}

				$tbody .= '<tr '.$classtr.'>
				<input type="hidden" name="bajadoc[' .$conta. '][fecha]" id="fecha' .$conta. '" value="'.$fecha.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][nropdc]" id="nropdc' .$conta. '" value="'.$YHNROPDC.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][codsuc]" id="codsuc' .$conta. '" value="'.$YHSUCDOC.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][tipdoc]" id="tipdoc' .$conta. '" value="'.$YHTIPDOC.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][serdoc]" id="serdoc' .$conta. '" value="'.$SCTESERI.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][cordoc]" id="cordoc' .$conta. '" value="'.$SCTECORR.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][serdoca]" id="serdoca' .$conta. '" value="'.$YHNUMSER.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][cordoca]" id="cordoca' .$conta. '" value="'.$YHNUMCOR.'"></input>
				<input type="hidden" name="bajadoc[' .$conta. '][ststrm]" id="cordoca' .$conta. '" value="'.$SCTCSTSA.'"></input>
				<td align="center">' .$conta. '</td>
				<td align="center">'.$YHCODSUC.'</td>
				<td align="center">'.$YHTIPDOCU.'</td>
				<td><font size="0px">'.$seriedoc.'</font></td>
				<td align="center">'.$MONEDA.'</td>
				<td align="center">'.$YHNROPDC.'</td>				
				<td><font size="0px">'.$DATACLIE.'</font></td>
				<td align="center">'.number_format($IMPORTE,2,".",",").'</td>
				<td align="center">'.$ESTADO.' '.$STATRAM.'</td>
				<td align="center"><input type="checkbox" '.$checked.' name="bajadoc[' .$conta. '][ckcodpdc]" id="ckcodpdc' .$conta. '" '.$checkeda.' value="'.$YHNROPDC.'"></td>
				<td align="center"><input type="hidden" name="bajadoc[' .$conta. '][msgxml]" id="msgxml' .$conta. '" value=""><p id="msgxml'.$conta.'"></p></td>	
				</tr>';	
			}			
		}else{
			$tbody .= '<tr ><td colspan="8"> <font size="0px">No hay Datos para Mostrar</font> </td>  </tr>'; 	
		}
		$result = [
			"tbody" =>$tbody,
			"nommes"=>$nommes,
			"fechaas"=>$fecha
		];
		$result['existe'] = 1;
		header('Content-type: application/json; charset=utf-8');
		//print_r($result);
		echo json_encode($result);	
		
	}
	function set_senddocbaja(){
		$rsptaxml=array();
		$ptdata=array();
		$pllxmlh='';
		$pllxmlf='';
		$pllxmlb='';
		$rsendxml='';
		$sendxml='';
		$result='';
		$resultdb2='';
		$rsendxml ='';
		$msgxml ='';				
		$existe = 0;

		$RUC_EMP=$this->session->userdata('ruccia');
		//$RUC_CC='20101266819';
		$mtvanu='Error de generacion';
		$fechaas = $this->input->post('fechaas', true);
		$bajadoc = $this->input->post('bajadoc', true);
		$chksndxml = $this->input->post('chksndxml', true);
//$WSDL="http://ecomprobantes-test.com/wssCargaBajas/cargaBajas.asmx?WSDL";
			//$WSDL="http://ecomprobantes.net.pe/wssCargaBajas/cargaBajas.asmx?WSDL";

		$pllxmlh='<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		<soap:Body>
		<cargaBajas xmlns="http://www.dbnet.cl">
		<RUC>'.$RUC_EMP.'</RUC>
		<ArchivoBajas>';
		$pllxmlf='</ArchivoBajas>
		</cargaBajas>
		</soap:Body>
		</soap:Envelope>';
		$WSDL="http://ecomprobantes.net.pe/wssCargaBajas/cargaBajas.asmx?WSDL";
		$url = "http://www.dbnet.cl/cargaBajas";
		if( !empty($bajadoc) && (count($bajadoc)>0)) {
			$rsdtdocbajst='';
			$contado=0;
			$contadodb=0;
			foreach($bajadoc as $cad) {				
				$confir= isset($cad['ckcodpdc']) ? $cad['ckcodpdc'] : '';
				if ($confir != '') {															
					$fecha=$cad['fecha'];
					$fechaas=$cad['fecha'];
					$YHNROPDC=$cad['nropdc'];
					$YHSUCDOC=$cad['codsuc'];
					$tipdoc=$cad['tipdoc'];
					$YHTIPDOC=$cad['tipdoc'];
					$serdoc=$cad['serdoc'];					
					$cordoc=$cad['cordoc'];
					$YHNUMSER=$cad['serdoca'];					
					$YHNUMCOR=$cad['cordoca'];
					$ae=substr($fecha, 0,4);
					$me=substr($fecha, 4,2);
					$de=substr($fecha, 6,2);
					$Fech_Emis=$ae.'-'.$me.'-'.$de;
					$ststrm=($cad['ststrm']=='')?'':$cad['ststrm'];
					if($chksndxml=='1'){
						$pllxmlb.='<bajas>
						<Tipo_Docu>'.(int)$tipdoc.'</Tipo_Docu>
						<Serie_Inte>'.$serdoc.'</Serie_Inte>
						<Foli_Inte>'.(int)$cordoc.'</Foli_Inte>
						<Fech_Emis>'.$Fech_Emis.'</Fech_Emis>
						<Motiv_Anul>'.$mtvanu.'</Motiv_Anul>
						</bajas>';
					}else{
						
					}
					if(($ststrm=='A')&&($chksndxml=='')){
						$pllxmlb.='<bajas>
						<Tipo_Docu>'.(int)$tipdoc.'</Tipo_Docu>
						<Serie_Inte>'.$serdoc.'</Serie_Inte>
						<Foli_Inte>'.(int)$cordoc.'</Foli_Inte>
						<Fech_Emis>'.$Fech_Emis.'</Fech_Emis>
						<Motiv_Anul>'.$mtvanu.'</Motiv_Anul>
						</bajas>';
						$rsdtdocbajst=$this->facturacionbaja_db2_model->upstdocumentoanulado($fechaas,$YHNROPDC,$YHSUCDOC,$YHTIPDOC,$YHNUMSER,$YHNUMCOR);
					}elseif($ststrm=='E'){
						$rsdtdocbajste=$this->facturacionbaja_db2_model->upstdocumentoanuladoconfirma($fechaas,$YHNROPDC,$YHSUCDOC,$YHTIPDOC,$YHNUMSER,$YHNUMCOR);
						$contadodb=$contadodb +$rsdtdocbajste;
						$Mensajexml='Se actualizao en BD';
						$rsptaxml[]=array('rpta'=>$Mensajexml,'rslbd2'=>$contadodb,'datadocu'=>$YHNROPDC.' '.$YHSUCDOC.' '.$YHTIPDOC.' '.$YHNUMSER.' '. $YHNUMCOR);
						$resulta=$rsptaxml;
						$rsendxml = 'Actualizado en BD';
				//"msgxml" =>$resulta['Mensajes'],
						$msgxml =$resulta;
						$existe = 1;

					}else{
						
					}
				}
			}

			if($pllxmlb==''){
				
			}else{
				$sendxml=$pllxmlh.$pllxmlb.$pllxmlf;
				$client = new nusoap_client($WSDL,TRUE);
				$client->operation = "SomeOperation";
				$proxy = $client->getProxyClassCode();
				$client->use_curl = TRUE;                                               
				$client->soap_defencoding = 'UTF-8';
				$client->decode_utf8 = false;
				$client->useHTTPPersistentConnection();
				$response = $client->send($sendxml, $url);
				if ($client->fault) {
					$resulta='<h2>Fault (Expect - The request contains an invalid SOAP body)</h2><pre>'. print_r($response). '</pre>';
				} else {
					$err = $client->getError();
					if ($err) {
						$resulta='<h2>Error</h2><pre>' . $err . '</pre>';
					} else {
						$resultas=$response['cargaBajasResult'];
						$Mensajes=$resultas['Mensajes'];						
						$msjo1 = trim(substr($Mensajes, 0,2));
						$msjo2 = trim(substr($Mensajes, 3,3));
						$msjo3 = substr($Mensajes, 5);
						$msjo4 = trim(substr($Mensajes, 11,2));
						$Mensajexml=''.$msjo1.' '.$msjo2.''.$msjo4.' '.$Mensajes;
						$rsptaxml[]=array('rpta'=>$Mensajexml,'rslbd2'=>'','datadocu'=>'');
						$resulta=$rsptaxml;
					}
				}				
				$rsendxml = $sendxml;
				//"msgxml" =>$resulta['Mensajes'],
				$msgxml =$resulta;		
								$existe = 1;
			}	
		}
		$result = [
			"sendxml" =>$rsendxml,
			"msgxml" =>$msgxml,
		];
		$result['existe'] = $existe;

		header('Content-type: application/json; charset=utf-8');
		//print_r($result);
		echo json_encode($result);
	}

	



}
?>