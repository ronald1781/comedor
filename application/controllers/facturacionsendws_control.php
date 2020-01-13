
<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Facturacionsendws_control extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('general_model', '', TRUE);
		$this->load->model('facturacionsendws_db2_model', '', TRUE);
		$this->load->model('Facturacionsendbajaws_db2_model', '', TRUE);
		$this->load->library('Services_JSON');
		//$this->load->library('curl'); 
		//$this->load->library('html2pdf');
		$this->load->library('fpdf_lib');
	}

	function index() {

	}

	function vista_fesendws(){
		$valid = $this->session->userdata('validated');
		if ($valid == TRUE) {  
			$lsttd = $this->facturacionsendws_db2_model->listatpodocu();
			$json = array();
			$dato = array();
			$listd = array();
			while (odbc_fetch_row($lsttd)) {	
				$SCTETDOC = trim(odbc_result($lsttd, 1)); 
				$EUDSCCOR = trim(odbc_result($lsttd, 2)); 
				$listd[] = array('SCTETDOC'=>$SCTETDOC,"EUDSCCOR"=>$EUDSCCOR);
			}
			$lstseri = $this->facturacionsendws_db2_model->listaseri();
			$json = array();
			$dato = array();
			$lisser = array();
			while (odbc_fetch_row($lstseri)) {
				$SCTESERI = trim(odbc_result($lstseri, 1)); 
				$lisser[] = array('SCTESERI'=>$SCTESERI);
			}
			$lstanio = $this->facturacionsendws_db2_model->listaanio();
			$json = array();
			$dato = array();
			$lisanio = array();
			while (odbc_fetch_row($lstanio)) {	
				$anio = trim(odbc_result($lstanio, 1));  
				$lisanio[] = array('anio'=>$anio);
			}
			
			$lsttd = $this->Facturacionsendbajaws_db2_model->listatpodocubaja();
			$json = array();
			$dato = array();
			$listdb = array();
			while (odbc_fetch_row($lsttd)) {	
				$SCTETDOC = trim(odbc_result($lsttd, 1)); 
				$EUDSCCOR = trim(odbc_result($lsttd, 2)); 
				$listdb[] = array('SCTETDOC'=>$SCTETDOC,"EUDSCCOR"=>$EUDSCCOR);
			}
			
			$lstseri = $this->Facturacionsendbajaws_db2_model->listaseribaja();
			$json = array();
			$dato = array();
			$lisserb = array();
			while (odbc_fetch_row($lstseri)) {
				$SCTESERI = trim(odbc_result($lstseri, 1)); 
				$lisserb[] = array('SCTESERI'=>$SCTESERI);
			}
			
			$lstanio = $this->Facturacionsendbajaws_db2_model->listaaniobaja();
			$json = array();
			$dato = array();
			$lisaniob = array();
			while (odbc_fetch_row($lstanio)) {	
				$anio = trim(odbc_result($lstanio, 1));  
				$lisaniob[] = array('anio'=>$anio);
			}
			
			$lstmes = $this->facturacionsendws_db2_model->listames();
			$json = array();
			$dato = array();
			$lismes = array();
			while (odbc_fetch_row($lstmes)) {
				$mes = trim(odbc_result($lstmes, 1));
				$dscmes=mes_letra($mes);  
				$lismes[] = array('mes'=>$mes,'dscmes'=>$dscmes);
			}
			$lststs = $this->facturacionsendws_db2_model->listaestado();
			$json = array();
			$dato = array();
			$lissts = array();
			while (odbc_fetch_row($lststs)) {
				$estacod = trim(odbc_result($lststs, 1));
				$estades = trim(odbc_result($lststs, 1));
				switch ($estades) {
					case 'A':
					$estades='ACEPTADO';
					break;
					case 'E':
					$estades='ENVIADO';
					break;
					case 'N':
					$estades='POR ANULAR';
					break;
					case 'G':
					$estades='GENERADO';
					break;
					case 'R':
					$estades='RECHAZADO';
					break;
					default:
					$estades='ESTADO?';
					break;
				}  
				$lissts[] = array('estacod'=>$estacod,'estades'=>$estades);
			}
			$lststs = $this->facturacionsendws_db2_model->listaestadobaja();
			$json = array();
			$dato = array();
			$lisstsb = array();
			while (odbc_fetch_row($lststs)) {
				$estacod = trim(odbc_result($lststs, 1));
				$estades = trim(odbc_result($lststs, 1));
				switch ($estades) {
					case 'A':
					$estades='ACEPTADO';
					break;
					case 'E':
					$estades='ENVIADO';
					break;
					case 'N':
					$estades='POR ANULAR';
					break;
					case 'G':
					$estades='GENERADO';
					break;
					case 'R':
					$estades='RECHAZADO';
					break;
					default:
					$estades='ESTADO?';
					break;
				}  
				$lisstsb[] = array('estacod'=>$estacod,'estades'=>$estades);
			}
			$datos['lisstsb'] = $lisstsb;
			$datos['lissts'] = $lissts;
			$datos['lstmes'] = $lismes;
			$datos['lstanio'] = $lisanio;
			$datos['lstaser'] = $lisser;
			$datos['lstatpdc'] = $listd;
			$datos['lstaniob'] = $lisaniob;
			$datos['lstaserb'] = $lisserb;
			$datos['lstatpdcb'] = $listdb;
			$datos['titulo'] = 'Monitor Factura Electronica';
			$datos['contenido'] = 'fesendws_view';    
			$this->load->view('includes/plantilla', $datos);
		} else {           
			redirect('loginin');
		}

	}
	function get_documentosfe(){

		$valid = $this->session->userdata('validated');
		$body=array();
		$fechae='';
		if ($valid == TRUE) { 
			$seltd = $this->input->post('seltd', true);
			$selseri = $this->input->post('selseri', true);
			$fechaad = $this->input->post('fechaad', true);
			$fechaah = $this->input->post('fechaah', true);			
			$selstsd=$this->input->post('selstsd', true);
			if(($fechaad!='')&&($fechaah!='')){
				$dd=substr($fechaad, 0,2);
				$dm=substr($fechaad, 3,2);
				$da=substr($fechaad, 6,4);

				$hd=substr($fechaah, 0,2);
				$hm=substr($fechaah, 3,2);
				$ha=substr($fechaah, 6,4);
				$fechae= "BETWEEN '".$da."-".$dm."-".$dd."' and '".$ha."-".$hm."-".$hd."'";
			}else{
				$fechae='';
			}

			$rsdocxgj=$this->facturacionsendws_db2_model->get_documentosfe($fechae,$seltd,$selseri,$selstsd);			
			if($rsdocxgj!=false){
				while (odbc_fetch_row($rsdocxgj)) {
					$SCTFECEM=trim(odbc_result($rsdocxgj, 1));
					$SCTESUCA=trim(odbc_result($rsdocxgj, 2));
					$SCTETDOC=trim(odbc_result($rsdocxgj, 3));
					$SCTEPDCA=trim(odbc_result($rsdocxgj, 4));
					$SCTCRZSO=trim(utf8_encode(odbc_result($rsdocxgj, 5)));
					$SCTESERI=trim(odbc_result($rsdocxgj, 6));
					$SCTECORR=trim(odbc_result($rsdocxgj, 7));
					$SCTCTMON=trim(odbc_result($rsdocxgj, 8));
					$SCTGNETO=trim(odbc_result($rsdocxgj, 9));
					$SCTCSTST=trim(odbc_result($rsdocxgj, 10));
					$SCTECIAA=trim(odbc_result($rsdocxgj, 11));
					$SCTEFEC=trim(odbc_result($rsdocxgj, 12));
					$SCTGTOTA=trim(odbc_result($rsdocxgj, 13));
					$nroevento=	trim(odbc_result($rsdocxgj, 14));
					$SCTIPFAC=	trim(odbc_result($rsdocxgj, 15));				
					$body[]=array("SCTFECEM"=>$SCTFECEM,"SCTESUCA"=>$SCTESUCA,"SCTETDOC"=>$SCTETDOC,"SCTEPDCA"=>$SCTEPDCA,"SCTCRZSO"=>$SCTCRZSO,"SCTESERI"=>$SCTESERI,"SCTECORR"=>$SCTECORR,"SCTCTMON"=>$SCTCTMON,"SCTGNETO"=>number_format($SCTGTOTA,2,".",","),"SCTCSTST"=>$SCTCSTST,"SCTECIAA"=>$SCTECIAA,"SCTEFEC"=>$SCTEFEC,"nroevento"=>$nroevento,"SCTIPFAC"=>$SCTIPFAC);
				}	
				$data['msg']='Con Datos';
				$data['datos']=$body;
				$data['existe'] = 0;
			}else{
				$data['msg']='Sin Datos';
				$data['datos']='';
				$data['existe'] = 1;
			}

			$result = $data;	
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($result);
		}else{
			redirect('loginin');	
		}

	}

	function get_documentosfe_clie(){

		$valid = $this->session->userdata('validated');
		$body=array();
		$fechae='';
		$fecha1='';
		$fecha2='';

		if ($valid == TRUE) { 
			$seltd = $this->input->post('seltd', true);
			$selseri = $this->input->post('selseri', true);
			$fechadc = $this->input->post('fechadc', true);
			$fechahc = $this->input->post('fechahc', true);
			$codclie=$this->input->post('codclie', true);
			if(($fechadc!='')||($fechahc!='')){
				$dd=substr($fechadc, 0,2);
				$dm=substr($fechadc, 3,2);
				$da=substr($fechadc, 6,4);
				$fecha1=$da.'-'.$dm.'-'.$dd;

				$dd=substr($fechahc, 0,2);
				$dm=substr($fechahc, 3,2);
				$da=substr($fechahc, 6,4);
				$fecha2=$da.'-'.$dm.'-'.$dd;
				$fechae="BETWEEN '".$fecha1."' AND '".$fecha2."'";
			}else{
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fecha=$da.'-'.$dm.'-'.$dd;
				$fechae="BETWEEN '".$fecha."' AND '".$fecha."'";
			}

			$rsdocxgj=$this->facturacionsendws_db2_model->get_documentosfe_clie($fechae,$seltd,$selseri,$codclie);			
			if($rsdocxgj!=false){
				while (odbc_fetch_row($rsdocxgj)) {
					$SCTFECEM=trim(odbc_result($rsdocxgj, 1));
					$SCTESUCA=trim(odbc_result($rsdocxgj, 2));
					$SCTETDOC=trim(odbc_result($rsdocxgj, 3));
					$SCTEPDCA=trim(odbc_result($rsdocxgj, 4));
					$SCTCRZSO=trim(utf8_encode(odbc_result($rsdocxgj, 5)));
					$SCTESERI=trim(odbc_result($rsdocxgj, 6));
					$SCTECORR=trim(odbc_result($rsdocxgj, 7));
					$SCTCTMON=trim(odbc_result($rsdocxgj, 8));
					$SCTGNETO=trim(odbc_result($rsdocxgj, 9));
					$SCTCSTST=trim(odbc_result($rsdocxgj, 10));
					$SCTECIAA=trim(odbc_result($rsdocxgj, 11));
					$SCTEFEC=trim(odbc_result($rsdocxgj, 12));
					$SCTGTOTA=trim(odbc_result($rsdocxgj, 13));
					$nroevento=	trim(odbc_result($rsdocxgj, 14));
					$SCTIPFAC=	trim(odbc_result($rsdocxgj, 15));				
					$body[]=array("SCTFECEM"=>$SCTFECEM,"SCTESUCA"=>$SCTESUCA,"SCTETDOC"=>$SCTETDOC,"SCTEPDCA"=>$SCTEPDCA,"SCTCRZSO"=>$SCTCRZSO,"SCTESERI"=>$SCTESERI,"SCTECORR"=>$SCTECORR,"SCTCTMON"=>$SCTCTMON,"SCTGNETO"=>number_format($SCTGTOTA,2,".",","),"SCTCSTST"=>$SCTCSTST,"SCTECIAA"=>$SCTECIAA,"SCTEFEC"=>$SCTEFEC,"nroevento"=>$nroevento,"SCTIPFAC"=>$SCTIPFAC);
				}	
				$data['msg']='Con Datos';
				$data['datos']=$body;
				$data['existe'] = 0;
			}else{
				$data['msg']='Sin Datos';
				$data['datos']='';
				$data['existe'] = 1;
			}

			$result = $data;	
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($result);
			
		}else{
			redirect('loginin');	
		}

	}
	
	function get_vereventosdocumentosfe(){

		$valid = $this->session->userdata('validated');
		$body=array();
		if ($valid == TRUE) { 
			$nropdc=$this->input->post('nropdc', true);
			$nrosere=$this->input->post('nrosere', true);
			$nrocor=$this->input->post('nrocor', true);
			$tipdoc=$this->input->post('tipdoc', true);
			$rsdocxgj=$this->facturacionsendws_db2_model->get_vereventosdocumentosfe($nropdc,$nrosere,$nrocor,$tipdoc);	
			if(($tipdoc=='08')||($tipdoc=='07')){
				$rsdocref=$this->facturacionsendws_db2_model->get_verdocumentosrefncnd($nropdc,$nrosere,$nrocor,$tipdoc);
				$SCTTDREF=trim(utf8_encode(odbc_result($rsdocref, 1)));
				$SCTSERRF=trim(utf8_encode(odbc_result($rsdocref, 2)));
				$SCTCORRF=trim(utf8_encode(odbc_result($rsdocref, 3)));
				$SCTFECRF=trim(utf8_encode(odbc_result($rsdocref, 4)));
				switch ($SCTTDREF) {
					case '01':
					$SCTTDREF='Factura';
					break;
					case '03':
					$SCTTDREF='Boleta';
					break;
					case '07':
					$SCTTDREF='NotaCredito';
					break;
					case '08':
					$SCTTDREF='NotaDebito';
					break;
					default:
					$SCTTDREF='Sin Datos';
					break;
				}
				$data['datoad']='Doc Ref. '.$SCTTDREF.' Nro: '.$SCTSERRF.' '.$SCTCORRF.' Fecha: '.$SCTFECRF;
			}else{
				$data['datoad']='';
			}		
			if($rsdocxgj!=false){
				while (odbc_fetch_row($rsdocxgj)) {	
					$SACODRPT=trim(odbc_result($rsdocxgj, 1));				
					$SAMSGRPT=trim(utf8_encode(odbc_result($rsdocxgj, 2)));
					$body[]=array("SACODRPT"=>$SACODRPT,"SAMSGRPT"=>$SAMSGRPT);
				}	
				$data['msg']='Con Datos';
				$data['datos']=$body;
				$data['existe'] = 0;
			}else{
				$data['msg']='Sin Datos';
				$data['datos']='';
				$data['existe'] = 1;
			}
			$result = $data;	
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($result);
		}else{
			redirect('loginin');	
		}

	}
	
	function buscar_cliente_db2as400(){
		$codclieas400 = $this->input->get('codclieas400');
		$codclieas400 = strtoupper($this->security->xss_clean($codclieas400));
		$lstcli=$this->facturacionsendws_db2_model->buscar_cliente_db2as400($codclieas400);
		$lismail=array();
		$lispb = array();
		while (odbc_fetch_row($lstcli)) {
			$SCTCCLIE = trim(odbc_result($lstcli, 1));
			$SCTCNRUC = trim(odbc_result($lstcli, 2));
			$SCTCRZSO = trim(utf8_encode(odbc_result($lstcli, 3))); 
			$mailcli=$this->facturacionsendws_db2_model->buscar_correo_cliente_db2as400($SCTCCLIE);
			if($mailcli!=false) {
				$I6CODDAT = trim(odbc_result($mailcli, 1));
				$I6DSCDAT = strtolower(trim(utf8_encode(odbc_result($mailcli, 2))));
			}else{
				$I6CODDAT='';
				$I6DSCDAT='';
			}		

			$lispb[] = array('AKCODCLI'=>$SCTCCLIE,'AKRAZSOC'=>$SCTCRZSO,'NUMIDEN'=>$SCTCNRUC);
		}
		if (count($lispb) > 0) {
			$result['datos'] = $lispb;
			$result['existe'] = 1;
		} else {

			$result['existe'] = 0;
			$result['men'] = 'No hay cliente buscado, verificar datos!!!';
                // $result = $dato;
		}
		echo json_encode($result);
	}
	function generadatafe($datos){
		
		//$mymclieas400='010077';
		$data='';
		$datas='';
		$filejs='';
		$documentofe='';
		$sendfe='';
		$result="";
		$detapdc='';
		$fe='';
		$documentofersl='';
		$infoAnticipo=array();$datadet=array();
		$stfedataanti='';
		$totalAnticipos=0.00;
		$SCTGTOTAA='';
		$HR='';					$MN='';					$SG='';
		$EIFECVCT='';				$JQNROSER='';				$JQNROCOR='';				$NOMDEPAM='';				$NOMPROVM='';				$NOMDISTM='';
		$SCTEPER='';$SCTEFEC='';$SCTECIAA='';$SCTESUCA='';$SCTEALMA='';$SCTEPDCA='';$SCTESERA='';$SCTECORA='';$SCTESERI='';$SCTECORR='';$SCTERZSO='';$SCTECODE='';$SCTECRUC='';$SCTEUBIG='';$SCTEDIRE='';$SCTENCOM='';$SCTETDOC='';$SCTETPDO='';$SCTVENDE='';$SCTCCLIE='';$SCTCNRUC='';$SCTCRZSO='';$SCTCDIRE='';$SCTCTMON='';$SCTCODAU='';$SCTCSUST='';$SCTCTPNO='';$SCTGNETO='';$SCTGGEXE='';$SCTGNEXO='';$SCTGIGV='';$SCTGTOTA='';$SCTFECEM='';$SCTCCIMP='';$SCTMTIMP='';$SCTTASAI='';$SCTCSTS='';$SCTCUSUR='';$SCTCFECR='';$SCTCHORR='';$IWODCPRV='';$SCTIPFAC='';
		$TVIACABR='';				$ALDSCDIR='';				$ALNRODIR='';				$NOMDEPA='';				$NOMPROV='';				$NOMDIST='';				$I6CODDAT='';$I6DSCDAT='';
		$SDTSERRF='';				$SDTCORRF='';				$SDTTDREF=''; 	$PYTXTADIPVH='';$rsttipocambio='';$CBFRMPAG='';$tipocambio='';

		$BMCODCIA=(int)$datos['codcia'];

		$rslstcia = $this->facturacionsendws_db2_model->listarCia($BMCODCIA);
		$EUCODCIA = trim(odbc_result($rslstcia, 1));
		$EUDSCCOM = trim(odbc_result($rslstcia, 2));
		$EUDSCDES = trim(odbc_result($rslstcia, 3));
		$mymclieas400 = trim(odbc_result($rslstcia, 4));

		$rsdocxgj=$this->facturacionsendws_db2_model->documentoxgenerarjson($datos);
		$numfil=odbc_num_rows($rsdocxgj);			
		if(($rsdocxgj!=false)||($numfil>0)){
			
			while (odbc_fetch_row($rsdocxgj)) {	
				$SCTEPER=trim(odbc_result($rsdocxgj, 1));
				$SCTEFEC=trim(odbc_result($rsdocxgj, 2));
				$SCTECIAA=trim(odbc_result($rsdocxgj, 3));
				$SCTESUCA=trim(odbc_result($rsdocxgj, 4));
				$SCTEALMA=trim(odbc_result($rsdocxgj, 5));
				$SCTEPDCA=trim(odbc_result($rsdocxgj, 6));
				$SCTESERA=trim(odbc_result($rsdocxgj, 7));
				$SCTECORA=trim(odbc_result($rsdocxgj, 8));
				$SCTESERI=trim(odbc_result($rsdocxgj, 9));
				$SCTECORR=trim(odbc_result($rsdocxgj, 10));
				$SCTERZSO=trim(utf8_encode(odbc_result($rsdocxgj, 11)));
				$SCTECODE=trim(odbc_result($rsdocxgj, 12));
				$SCTECRUC=trim(odbc_result($rsdocxgj, 13));
				$SCTEUBIG=trim(odbc_result($rsdocxgj, 14));
				$SCTEDIRE=trim(odbc_result($rsdocxgj, 15));
				$SCTENCOM=trim(utf8_encode(odbc_result($rsdocxgj, 16)));
				$SCTETDOC=trim(odbc_result($rsdocxgj, 17));
				$SCTETPDO=trim(odbc_result($rsdocxgj, 18));
				$SCTVENDE=trim(odbc_result($rsdocxgj, 19));
				$SCTCCLIE=trim(odbc_result($rsdocxgj, 20));
				$SCTCNRUC=trim(odbc_result($rsdocxgj, 21));
				$SCTCRZSO=trim(odbc_result($rsdocxgj, 22));
				$SCTCDIRE=trim(odbc_result($rsdocxgj, 23));
				$SCTCTMON=trim(odbc_result($rsdocxgj, 24));
				$SCTCODAU=trim(odbc_result($rsdocxgj, 25));
				$SCTCSUST=trim(odbc_result($rsdocxgj, 26));
				$SCTCTPNO=trim(odbc_result($rsdocxgj, 27));
				$SCTGNETO=trim(odbc_result($rsdocxgj, 28));
				$SCTGGEXE=trim(odbc_result($rsdocxgj, 29));
				$SCTGNEXO=trim(odbc_result($rsdocxgj, 30));
				$SCTGIGV=trim(odbc_result($rsdocxgj, 31));
				$SCTGTOTA=trim(odbc_result($rsdocxgj, 32));
				$SCTFECEM=trim(odbc_result($rsdocxgj, 33));
				$SCTCCIMP=trim(odbc_result($rsdocxgj, 34));
				$SCTMTIMP=trim(odbc_result($rsdocxgj, 35));
				$SCTTASAI=trim(odbc_result($rsdocxgj, 36));
				$SCTCSTS=trim(odbc_result($rsdocxgj, 37));
				$SCTCUSUR=trim(odbc_result($rsdocxgj, 38));
				$SCTCFECR=trim(odbc_result($rsdocxgj, 39));
				$SCTCHORR=trim(odbc_result($rsdocxgj, 40));
				$SCTIPFAC=trim(odbc_result($rsdocxgj, 41)); 

				$rsocxgj=$this->facturacionsendws_db2_model->ocdocxgenerarjson($SCTECIAA,$SCTESUCA,$SCTEPDCA);			
				if($rsocxgj!=false){
					while (odbc_fetch_row($rsocxgj)) {	
						$IWODCPRV=trim(odbc_result($rsocxgj, 1));
					}
				}else{
					$IWODCPRV='';
				}
// +---------------------------------------------------------------------------+    
// |Datos CLiente mym
// +---------------------------------------------------------------------------+
				$rsmymcli=$this->facturacionsendws_db2_model->buscar_cliente_uni_db2as400($mymclieas400);   
				while (odbc_fetch_row($rsmymcli)) {
					$AKCODCLIM = trim(odbc_result($rsmymcli, 1));
					$AKRAZSOCM = trim(utf8_encode(odbc_result($rsmymcli, 2))); 
					$IFNVORUCM = trim(odbc_result($rsmymcli, 3));
					$AKTIPIDEM = trim(odbc_result($rsmymcli, 4)); 
					$AKNROIDEM = trim(odbc_result($rsmymcli, 5)); 
					$NUMIDENM=($AKTIPIDEM=='02')?$IFNVORUCM:$AKNROIDEM;
					$AKIMPLMTM= trim(odbc_result($rsmymcli, 6)); 
				}
				
				$dirclimym=$this->facturacionsendws_db2_model->buscar_direccion_cliente_mym_db2as400($mymclieas400);   
				while (odbc_fetch_row($dirclimym)) {
					$ALVIADIRM = trim(odbc_result($dirclimym, 1));
					$tvaclim=$this->facturacionsendws_db2_model->buscar_tipovia_cliente_db2as400($ALVIADIRM);   
					while (odbc_fetch_row($tvaclim)) {
						$TVIACABRM = trim(odbc_result($tvaclim, 1));
					}
					$ALDSCDIRM = trim(utf8_encode(odbc_result($dirclimym, 2))); 
					$ALNRODIRM = (trim(odbc_result($dirclimym, 3))==0)?'':trim(odbc_result($dirclimym, 3));
					$ALDEPARTM = trim(odbc_result($dirclimym, 4));
					$dpaclim=$this->facturacionsendws_db2_model->buscar_departamento_cliente_db2as400($ALDEPARTM);   
					while (odbc_fetch_row($dpaclim)) {
						$NOMDEPAM = trim(odbc_result($dpaclim, 1));
					} 
					$ALPROVINM = trim(odbc_result($dirclimym, 5));
					$prvclim=$this->facturacionsendws_db2_model->buscar_provincia_cliente_db2as400($ALDEPARTM,$ALPROVINM);   
					while (odbc_fetch_row($prvclim)) {
						$NOMPROVM = trim(odbc_result($prvclim, 1));
					} 
					$ALDISTRIM = trim(odbc_result($dirclimym, 6));
					$dstclim=$this->facturacionsendws_db2_model->buscar_distrito_cliente_db2as400($ALDEPARTM,$ALPROVINM,$ALDISTRIM);   
					while (odbc_fetch_row($dstclim)) {
						$NOMDISTM = trim(odbc_result($dstclim, 1));
					}    
				}
				//Datos del cliente como direccion
// +---------------------------------------------------------------------------+    
// |Datos CLiente
// +---------------------------------------------------------------------------+				
				$rscli=$this->facturacionsendws_db2_model->buscar_cliente_uni_db2as400($SCTCCLIE);   
				while (odbc_fetch_row($rscli)) {
					$AKCODCLI = trim(odbc_result($rscli, 1));
					$AKRAZSOC = trim(utf8_encode(odbc_result($rscli, 2))); 
					$IFNVORUC = trim(odbc_result($rscli, 3));
					$AKTIPIDE = trim(odbc_result($rscli, 4)); 
					$AKNROIDE = trim(odbc_result($rscli, 5)); 
					$NUMIDEN=($AKTIPIDE=='02')?$IFNVORUC:$AKNROIDE;
					$AKIMPLMT= trim(odbc_result($rscli, 6)); 
				}
				
				$dircli=$this->facturacionsendws_db2_model->buscar_direccion_cliente_db2as400($SCTCCLIE);   
				while (odbc_fetch_row($dircli)) {
					$ALVIADIR = trim(odbc_result($dircli, 1));
					$tvacli=$this->facturacionsendws_db2_model->buscar_tipovia_cliente_db2as400($ALVIADIR);   
					while (odbc_fetch_row($tvacli)) {
						$TVIACABR = trim(odbc_result($tvacli, 1));
					}
					$ALDSCDIR = trim(utf8_encode(odbc_result($dircli, 2)));
					$ALNRODIR = (trim(odbc_result($dircli, 3))==0)?'':trim(odbc_result($dircli, 3));
					$ALDEPART = trim(odbc_result($dircli, 4));
					$dpacli=$this->facturacionsendws_db2_model->buscar_departamento_cliente_db2as400($ALDEPART);   
					while (odbc_fetch_row($dpacli)) {
						$NOMDEPA = trim(odbc_result($dpacli, 1));
					} 
					$ALPROVIN = trim(odbc_result($dircli, 5));
					$prvcli=$this->facturacionsendws_db2_model->buscar_provincia_cliente_db2as400($ALDEPART,$ALPROVIN);   
					while (odbc_fetch_row($prvcli)) {
						$NOMPROV = trim(odbc_result($prvcli, 1));
					} 
					$ALDISTRI = trim(odbc_result($dircli, 6));
					$dstcli=$this->facturacionsendws_db2_model->buscar_distrito_cliente_db2as400($ALDEPART,$ALPROVIN,$ALDISTRI);   
					while (odbc_fetch_row($dstcli)) {
						$NOMDIST = trim(odbc_result($dstcli, 1));
					}    
				}

				$dtfvcli=$this->facturacionsendws_db2_model->buscar_fvpdc_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTCCLIE,$SCTETDOC);   
				while (odbc_fetch_row($dtfvcli)) {						
					//$EIFECVCT = trim(odbc_result($dtfvcli, 1));
				}
				
				$dtgrcli=$this->facturacionsendws_db2_model->buscar_grpdc_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA);   
				while (odbc_fetch_row($dtgrcli)) {						
					$JQNROSER = trim(odbc_result($dtgrcli, 1));
					$JQNROCOR = trim(odbc_result($dtgrcli, 2));
				}
				$SDTNITEM='';$SDTCDART='';$SDTDSCAR='';$SDTCANTI='';$SDTUNIME='';$SDTPUSIG='';$SDTPUCIG='';$SDTCTIGV='';$SDTPIIGV='';$SDTCAFEC='';$SDTPTIGV='';$SDTPSITE='';

				$dtgrcli=$this->facturacionsendws_db2_model->buscar_detallepdc_cliente_db2as400($SCTECIAA,$SCTEPDCA,$SCTESERI,$SCTECORR);   
				while (odbc_fetch_row($dtgrcli)) {
					$SDTNITEM = trim(odbc_result($dtgrcli, 1));
					$SDTCDART = trim(utf8_encode(odbc_result($dtgrcli, 2)));
					$SDTDSCAR = trim(utf8_encode(odbc_result($dtgrcli, 3)));
					$SDTCANTI = trim(odbc_result($dtgrcli, 4));
					$SDTUNIME = trim(odbc_result($dtgrcli, 5));
					$SDTPUSIG = trim(odbc_result($dtgrcli, 6));
					$SDTPUCIG = trim(odbc_result($dtgrcli, 7));
					$SDTCTIGV = trim(odbc_result($dtgrcli, 8));
					$SDTPIIGV = trim(odbc_result($dtgrcli, 9));
					$SDTCAFEC = trim(odbc_result($dtgrcli, 10));
					$SDTPTIGV = trim(odbc_result($dtgrcli, 11));
					$SDTPSITE = trim(odbc_result($dtgrcli, 12));
					$NROITEMS = str_pad($SDTNITEM,3,"000",STR_PAD_LEFT);
					$TotalImpuestos=$SDTPSITE*$SDTPTIGV/100;
					$montoTotalImpuestos=number_format($TotalImpuestos,2,".",",");	
					$detapdc[] = array("numeroItem"=>$NROITEMS,
						"codigoProducto"=>mb_convert_encoding($SDTCDART,"UTF-8", "iso-8859-1"),
						"descripcionProducto"=>mb_convert_encoding($SDTDSCAR,"UTF-8", "iso-8859-1"),
						"cantidadItems"=>$SDTCANTI,
						"unidad"=>$SDTUNIME,
						"valorUnitario"=>$SDTPUSIG,
						"precioVentaUnitario"=>$SDTPUCIG,
						"idImpuesto"=>$SDTCTIGV,
						"montoImpuesto"=>$SDTPIIGV,
						"tipoAfectacion"=>$SDTCAFEC,
						"montoBase"=>$SDTPUSIG,
						"porcentaje"=>$SDTPTIGV,
						"valorVenta"=>$SDTPSITE,
						"montoTotalImpuestos"=>$montoTotalImpuestos);					
					$datadet[]=array("NROITEMS"=>$NROITEMS,"SDTCDART"=>$SDTCDART,"SDTDSCAR"=>$SDTDSCAR,"SDTCANTI"=>$SDTCANTI,"SDTUNIME"=>$SDTUNIME,"SDTPUCIG"=>$SDTPUCIG,"SDTPSITE"=>$SDTPSITE,"SDTPIIGV"=>$SDTPIIGV,"SDTPUSIG"=>$SDTPUSIG);//SDTPUSIG
				}

				$dtrefcli=$this->facturacionsendws_db2_model->buscar_drf_cliente_db2as400($SCTECIAA,$SCTEPDCA,$SCTETDOC,$SCTEFEC,$SCTESERI,$SCTECORR); 
				while (odbc_fetch_row($dtrefcli)) {						
					$SDTSERRF = trim(odbc_result($dtrefcli, 1));
					$SDTCORRF = trim(utf8_encode(odbc_result($dtrefcli, 2)));
					$SDTTDREF = trim(utf8_encode(odbc_result($dtrefcli, 3)));
				}
				$DKIMPVTA='';
				$drsttpcb=$this->facturacionsendws_db2_model->buscar_tcmb_cliente_db2as400($SCTEFEC,$SCTCTMON);
				while (odbc_fetch_row($drsttpcb)) {						
					$DKIMPVTA = trim(odbc_result($drsttpcb, 1));
				}				
				$tc=number_format((float)$DKIMPVTA,3,".",",");
				
				if($SCTCTMON=='02'){
					$convtc=$SCTGTOTA/$tc;
					$rsttipocambio='$'.number_format($convtc,2,".",",");
				}else{
					$convtc=$SCTGTOTA*$tc;
					$rsttipocambio='S/ '.number_format($convtc,2,".",",");
				}
				
				if(strlen($SCTCHORR)==8){
					$HR=substr($SCTCHORR, 0,2);
					$MN=substr($SCTCHORR, 2,2);
					$SG=substr($SCTCHORR, 4,2);
				}else{
					$HR=substr($SCTCHORR, 0,1);
					$MN=substr($SCTCHORR, 1,2);
					$SG=substr($SCTCHORR, 3,2);	
				}	

				$placavh=$this->facturacionsendws_db2_model->buscar_placavh_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA);		
				while (odbc_fetch_row($placavh)) {						
					$PYTXTADIPVH = trim(odbc_result($placavh, 1));
				}
				
				$rsmailc=$this->facturacionsendws_db2_model->buscar_correo_cliente_db2as400($SCTCCLIE);

				if($rsmailc!=false){
					$contamail=0;
					while (odbc_fetch_row($rsmailc)) {	
						$contamail=$contamail+1;					
						$I6CODDAT = trim(odbc_result($rsmailc, 1));
						$comass=($contamail>1)?';':'';
						$I6DSCDAT .= $comass.trim(utf8_encode(odbc_result($rsmailc, 2)));				
					}

				}else{

				}


				$stfeanti=$this->facturacionsendws_db2_model->get_anticiporelacionado_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC);
				$ASCTESERI = '';$ASCTECORR = '';$ASCTETDOC = '';$ASCTGTOTA = '';$ASCTCTMON = '';$ASCTFECEM = '';$ASCTECRUC = '';$ASCTETPDO = '';
				if($stfeanti>0){
					$stfedataanti=$this->facturacionsendws_db2_model->get_anticipos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC);
					$ident=0;
					while (odbc_fetch_row($stfedataanti)) {	
						$ident=str_pad($ident+1,2,"0", STR_PAD_LEFT);
						$ASCTESERI = trim(odbc_result($stfedataanti, 1));
						$ASCTECORR = trim(odbc_result($stfedataanti, 2));
						$ASCTETDOC = trim(odbc_result($stfedataanti, 3));
						$ASCTGTOTA = trim(odbc_result($stfedataanti, 4));
						$ASCTCTMON = trim(odbc_result($stfedataanti, 5));
						$ASCTFECEM = trim(odbc_result($stfedataanti, 6));
						$ASCTECRUC = trim(odbc_result($stfedataanti, 7));
						$ASCTETPDO = trim(odbc_result($stfedataanti, 8));
						$infoAnticipo[]=array(
							"serieNumero"=>$ASCTESERI.'-'.$ASCTECORR,
							"tipoComprobante"=> "02",
							"monto"=>$ASCTGTOTA,
							"tipoMoneda"=>$ASCTCTMON,
							"fechaAnticipo"=>$ASCTFECEM,
							"numeroDocEmisor"=>$ASCTECRUC,
							"tipoDocumentoEmisor"=> $ASCTETPDO,
							"identificador"=> $ident
						);
						$totalAnticipos=$totalAnticipos+$ASCTGTOTA;
					}
					$SCTGTOTAA=$SCTGTOTA-$totalAnticipos;
				}else{
					$infoAnticipo[]=array(
						"serieNumero"=>'',
						"tipoComprobante"=> '',
						"monto"=>'',
						"tipoMoneda"=>'',
						"fechaAnticipo"=>'',
						"numeroDocEmisor"=>'',
						"tipoDocumentoEmisor"=> '',
						"identificador"=> ''
					);
					$totalAnticipos=0.00;
					$SCTGTOTAA=$SCTGTOTA;
				}

				$rslfpgo=$this->facturacionsendws_db2_model->buscar_formapgo_db2as400($SCTECIAA,$SCTEFEC,$SCTEPDCA);
				if(($SCTETDOC=='01')||($SCTETDOC=='03')){
					$CBFRMPAG = (trim(odbc_result($rslfpgo, 1))=='C')?'Contado':'Credito';	
				}else{
					$CBFRMPAG='';
				}
				$frmpgo=trim(odbc_result($rslfpgo, 1));
				$tipocambio= ($frmpgo=='C')?$tc." ".$rsttipocambio:'';
			

			$data['SCTEFEC']=$SCTEFEC;
			$data['SCTESUCA']=$SCTESUCA;
			$data['SCTECIAA']=$SCTECIAA;
			$data['SCTESERI']=$SCTESERI;
			$data['SCTECORR']=$SCTECORR;
			$data['SCTIPFAC']=$SCTIPFAC;
			$data['SCTCUSUR']=$SCTCUSUR;
			$data['numeracion']=$SCTESERI.'-'.$SCTECORR;
			$data['fechaEmision']=$SCTFECEM;
			$data['infoAnticipo']=$infoAnticipo;
			$data['totalAnticipos']=(string)$totalAnticipos;
			$data['horaEmision']=$HR.':'.$MN.':'.$SG;
			$data['codTipoDocumento']=$SCTETDOC;
			$data['tipoMoneda']=$SCTCTMON;
			$data['numeroOrdenCompra']=$IWODCPRV;
			$data['fechaVencimiento']=$EIFECVCT;
			$data['tipoDocId']="6";
			$data['numeroDocId']=$SCTECRUC;
			$data['nombreComercial']=$SCTENCOM;
			$data['razonSocial']=$SCTERZSO;
			$data['ubigeo']=$SCTEUBIG;
			$data['direccion']=$SCTEDIRE;//$TVIACABRM.' '.$ALDSCDIRM;
			$data['urbanizacion']="Urb. Fortis";
			$data['provincia']=$NOMPROVM;
			$data['departamento']=$NOMDEPAM;
			$data['distrito']=$NOMDISTM;
			$data['codigoPais']="PE";
			$data['telefono']="016131500";
			$data['correoElectronico']="ventas@mym.com.pe";
			$data['codigoAsigSUNAT']=str_pad($SCTCODAU,4,"0000",STR_PAD_LEFT);
			$data['tipoDocIdc']=$SCTETPDO;
			$data['numeroDocIdc']=$SCTCNRUC;
			$data['razonSocialc']=$SCTCRZSO;
			$data['direccionc']=$TVIACABR.' '.$ALDSCDIR.' '.$ALNRODIR;
			$data['departamentoc']=$NOMDEPA;
			$data['provinciac']=$NOMPROV;
			$data['distritoc']=$NOMDIST;
			$data['codigoPaisc']="PE";
			$data['telefonoc']="555-5555";	
			$mailcli=($I6DSCDAT=='')?'':$I6DSCDAT.';rramos@mym.com.pe';
			$data['correoElectronicoc']=$mailcli;
			//$data['correoElectronicoc']=$DMAILC;
			if(($JQNROSER=='')&&($JQNROCOR=='')){
				$data['tipoDocRelacionado']="";
				$data['numeroDocRelacionado']= "";
			}else{
				$data['tipoDocRelacionado']="09";
				$data['numeroDocRelacionado']= "G".$JQNROSER.'-'.$JQNROCOR;
			}		

			$data['totalVentasc']=$SCTGNETO;
			$data['idImpuestoc']=$SCTCCIMP;
			$data['montoImpuestoc']=$SCTGIGV;
			$data['importeTotalc']=$SCTGTOTAA;
			$SCTIPFAC2=$SCTIPFAC;
			switch ($SCTIPFAC2) {
				case 'FN':
				$data['codigoc']="1001";
				$data['tipoOperacionc']="0101";
				$data['codigolc']="1000";
				break;
				case 'FS':
				$data['codigoc']="1001";
				$data['tipoOperacionc']="0101";//"1001";
				$data['codigolc']="1000";
				break;
				case 'FG':
				$data['codigoc']="1004";
				$data['tipoOperacionc']="0101";
				$data['codigolc']="1000";
				break;
				case 'FE':
				$data['codigoc']="1000";
				$data['tipoOperacionc']="0200";
				$data['codigolc']="1000";
				break;
				case 'FA':
				$data['codigoc']="1001";
				$data['tipoOperacionc']="0101";
				$data['codigolc']="1000";
				break;
				case 'FF':
				$data['codigoc']="1001";
				$data['tipoOperacionc']="0101";
				$data['codigolc']="1000";
				break;
				default:
				$data['codigoc']="1001";
				$data['tipoOperacionc']="0101";
				$data['codigolc']="1000";
				break;
			}
			$data['descripcionlc']=trim(utf8_encode(num_to_letras($SCTGTOTAA,$SCTCTMON)));
			$data['codigold']="2006";
			$data['descripcionld']="Operación sujeta a detracción";
			$data['codigolgc']="1002";
			$data['descripciongc']="TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE";
			$data['montoTotalImpuestos']=$SCTMTIMP;
			$data['detallepcd']=$detapdc;
			$data['tipoDocRelacionadonc']=($SDTTDREF!='')?str_pad($SDTTDREF,2,"00",STR_PAD_LEFT):'';
			$data['numeroDocRelacionadonc'] =(($SDTSERRF!='')&&($SDTCORRF!=''))?$SDTSERRF.'-'.$SDTCORRF:'';
			$data['codigoMotivonc']=$SCTCTPNO;
			$data['descripcionMotivonc']=$SCTCSUST;
			$data['datadet']=$datadet;
			$data['placavh']=$PYTXTADIPVH;
			$data['tituloAdicional1']="T.C. Referencia del dia"; 
			$data['valorAdicional1']=$tipocambio;
			$data['tituloAdicional2']="Mensaje de Retencion "; 
			$data['valorAdicional2']="NO RETENER EL 3% DE ESTE COMPROBANTE POR SER AGENTE DE RETENCION - R.S. N 378-2013/SUNAT";
			$data['tituloAdicional3']="CodigoCliente";
			$data['valorAdicional3']=$SCTCCLIE;
			$data['tituloAdicional4']="CodigoVendedor"; 
			$data['valorAdicional4']=$SCTVENDE;
			$data['tituloAdicional5']="NumeroInterno"; 
			$data['valorAdicional5']=$SCTEPDCA;
			$data['tituloAdicional6']="PlacaVehiculo"; 
			$data['valorAdicional6']=$PYTXTADIPVH;
			$data['tituloAdicional7']="FormaPago"; 
			$data['valorAdicional7']=$CBFRMPAG;
			$data['tituloAdicionaln']=" ";
			$data['valorAdicionaln']='EN CASO DE NO SER PAGADO A SU VENCIMIENTO ESTE DOCUMENTO GENERARÁ INTERES COMPENSATORIOS Y MORATORIOS A LAS TASAS MAXIMAS QUE FIJE LA LEY. EL PAGO DE ESTE DOCUMENTO PUEDE SER EFECTUADO DEPOSITANDO EL IMPORTE EN NUESTRAS CTAS. CTES. O EN CASO DE PAGAR CON CHEQUE GIRARLO UNICAMENTE A LA ORDEN DE M&M REPUESTOS Y SERVICIOS S.A. LA MERCADERIA VIAJA A CUENTA Y RIESGO DEL COMPRADOR.  UNA VEZ SALIDA LA MERCADERIA NO SE ACEPTANCAMBIOS NI DEVOLUCIONES, PENALIDAD DEL 10%';
			 //factura servicio data adicional inicio
			/*
			$data['datacodsdt']="codBienServDetr";
			$data['datacodsdv']="022";
			$data['datanroctabnsdt']="numCuentaBcoNacionDetr";
			$data['datanroctabnsdv']="9999-999999-9999";
			$data['datamdiopgosdt']="medioPagoDetr";
			$data['datamdiopgosdv']="001";
			$data['datamtopgodsdt']="montoDetr";
			$data['datamtopgodsdv']="999.99";
			$data['dataprtjpgodsdt']="porcDetr";
			$data['dataprtjpgodsdv']="99.99"; 
			*/
			//factura servicio data adicional fin
//FN=Factura Normal,FS=Factura Servicio,FG=Factura Gratuita,FE=Factura Exportacion,FA=Factura Anticipo

			$datas=$data;
		}
		}else{
			$datas=false;
		}
		return $datas;
		//print_r($datas);
	}


	function plantillaJSONFE(){		
		$datos['nropdc'] = $this->security->xss_clean($this->input->post('nropdc', true));
		$datos['nropdc'] = $this->security->xss_clean($this->input->post('nropdc', true));
		$datos['serie'] = $this->security->xss_clean($this->input->post('serie', true));
		$datos['correl'] = $this->security->xss_clean($this->input->post('corr', true));
		$datos['fecha'] = $this->security->xss_clean($this->input->post('fecha', true));
		$datos['codcia']=$this->security->xss_clean($this->input->post('cia', true));
		$data=$this->generadatafe($datos);
		
		if($data!=""){
			$SCTIPFAC=$data['SCTIPFAC'];
			$SCTECRUC=$data['numeroDocId'];
			$SCTETDOC=$data['codTipoDocumento'];
			$SCTESERI=$data['SCTESERI'];
			$SCTECORR=$data['SCTECORR'];
			$SCTECIAA=$data['SCTECIAA'];
			$SCTEPDCA=$data['valorAdicional5'];
			$SCTERZSO=$data['razonSocial'];
			$SCTFECEM=$data['fechaEmision'];
			$SCTESUCA=$data['SCTESUCA'];
			$SCTEFEC=$data['SCTEFEC'];
			$SCTCUSUR=$data['SCTCUSUR'];
			$correoElectronicoc=$data['correoElectronicoc'];
			if($SCTETDOC=='01'){
				$filejs=$this->data_fact01($data,$SCTIPFAC);				
			}else if($SCTETDOC=='03'){
				$filejs=$this->armarjsonfe03($data);
			}else if($SCTETDOC=='07'){
				$filejs=$this->armarjsonfe07($data);
			}else if($SCTETDOC=='08'){
				$filejs=$this->armarjsonfe08($data);				
			}else{
			}
			$filesjs=($filejs=='')?'Sin Datos':$filejs;
			$resultprintfe='';	
			$docufersl='';
			$http_status='';
			$http_statuscod='';
			$docufeerror='';
			$update_data='';
			$fechadata=gmdate("d-m-Y H:i:s", time() - 18000);			
			if($filesjs!=''){
				$filecontent=base64_encode($filejs);

				$rslstcia = $this->facturacionsendws_db2_model->listarCia($SCTECIAA);
				if($rslstcia!=false){
					$EUCODCIA = trim(odbc_result($rslstcia, 1));
					$EUDSCCOM = trim(odbc_result($rslstcia, 2));
					$EUDSCDES = trim(odbc_result($rslstcia, 3));
					$mymclieas400 = trim(odbc_result($rslstcia, 4));
				}	

				$rsmymcli=$this->facturacionsendws_db2_model->buscar_cliente_uni_db2as400($mymclieas400);   
				if($rsmymcli!=false){
					$AKCODCLIM = trim(odbc_result($rsmymcli, 1));
					$AKRAZSOCM = trim(utf8_encode(odbc_result($rsmymcli, 2))); 
					$IFNVORUCM = trim(odbc_result($rsmymcli, 3));
					$AKTIPIDEM = trim(odbc_result($rsmymcli, 4)); 
					$AKNROIDEM = trim(odbc_result($rsmymcli, 5)); 
					$NUMIDENM=($AKTIPIDEM=='02')?$IFNVORUCM:$AKNROIDEM;
					$AKIMPLMTM= trim(odbc_result($rsmymcli, 6)); 
				}
				$datos_wsa=datos_webserviceAcceso($NUMIDENM);
				$usuario=$datos_wsa['usuario'];
				$clave=$datos_wsa['clave'];
				$datossvr=dato_hostas();
				$ip=$datossvr['ipas'];        
				$datos_ws=datos_webservice($ip);
				$url=$datos_ws['url'];
				$urlref=$datos_ws['urlref'];
				$filename=$SCTECRUC.'-'.$SCTETDOC.'-'.$SCTESERI.'-'.$SCTECORR.'.json';
				$documentofe=array("customer"=>array("username"=>$usuario,"password"=>$clave),"fileName"=>$filename,"fileContent"=>$filecontent);
				$json=new Services_JSON();
				$documentfe=$json->encode($documentofe);
				$fj=fopen('assest\fejs\\'.$SCTECRUC.'-'.$SCTETDOC.'-'.$SCTESERI.'-'.$SCTECORR.'.json', 'w');
				fwrite($fj, $filejs);
				fclose($fj);

				$fe='';
				$header=array('Accept:application/json','Content-Type:application/json;charset=UTF-8','Content-Length:'.strlen($documentfe));
				$cr=curl_init();
				curl_setopt($cr, CURLOPT_URL, $url);
				curl_setopt($cr, CURLOPT_REFERER, $urlref);
				curl_setopt($cr, CURLINFO_HEADER_OUT, true);
				curl_setopt($cr, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($cr, CURLOPT_FRESH_CONNECT, true);			
				curl_setopt($cr, CURLOPT_POST,true);
				curl_setopt($cr, CURLOPT_MAXREDIRS,10);
				curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($cr, CURLOPT_HTTPHEADER, $header);
				curl_setopt($cr, CURLOPT_POSTFIELDS,$documentfe);
				curl_setopt($cr, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($cr, CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($cr, CURLOPT_VERBOSE,true);
				curl_setopt($cr, CURLOPT_TIMEOUT, 0);
				curl_setopt($cr, CURLOPT_FAILONERROR, true);
				$http_statuscod=curl_getinfo($cr,CURLINFO_HTTP_CODE);
				$http_status=curl_getinfo($cr,CURLINFO_EFFECTIVE_URL);
				$gtfecli=$this->facturacionsendws_db2_model->get_estadosend_fe($SCTECIAA,$SCTEPDCA,$SCTESERI,$SCTECORR);
				$numfil=($gtfecli==false)?0:odbc_num_rows($gtfecli);
				$responseCod='';
				$responseConten='';
				if($numfil>0){
					$documentofersl='';
					$errorfe='';
				}else{
					$documentofersl=curl_exec($cr);
					$errorfe=curl_error($cr);

					$docufeerror='';
					$errno='';
					if($documentofersl==false){
						$docufeerror=curl_error($cr);					

						$fe['codigo']='';
						$fe['dato']='';
					}else{
						$docufeerror='';
						$docufersl=json_decode($documentofersl);
						$responseCod=$docufersl->responseCode;
						$responseConten=$docufersl->responseContent.' '.$fechadata;

						$fe['codigo']=$responseCod;
						$fe['dato']=$responseConten;

					}					
					curl_close($cr);

					$ESTADO='';
					$upfecli='';
					$stfecli='';
					$resultprintfe='';
					$nomTipoDOcu=$SCTETDOC;
					switch ($nomTipoDOcu) {
						case '01':
						$nomTipoDOcu='FACTURA';
						break;
						case '03':
						$nomTipoDOcu='BOLETA';
						break;
						case '07':
						$nomTipoDOcu='NOTA DE CREDITO';
						break;
						case '08':
						$nomTipoDOcu='NOTA DE DEBITO';
						break;
						default:
						$nomTipoDOcu='NO definido';
						break;
					}

					if(($http_statuscod=='200')||($http_statuscod=='0')&&($errorfe=='')){
						$resultprintfe=$this->fesendprint($data);
						if(($responseCod=='0')||($responseCod=='1033')){

							//if($DMAILC!=''){

							$destinos=array(
								array('correo'=>'rramos@mym.com.pe','nombres'=>'Ronald Ramos'),
							);
							$responseContent1='';
							$datm["dat0"]='Ha recibido un nuevo documento electrónico ';
							$datm["dat1"]=$SCTERZSO;
							$datm["dat2"]=$SCTFECEM;
							$datm["dat3"]=$nomTipoDOcu;
							$datm["dat4"]=$SCTESERI.' '.$SCTECORR;
							//$DMAILC //SCTCRZSO $responseCod,$responseContent1
							$datm['asunto']='Envío de Comprobante Electrónico '.$SCTESERI.'-'.$SCTECORR;
							$datm['emaildestino']=$destinos;
							$datm['adjunto']=$SCTECRUC.'_'.$SCTETDOC.'_'.$SCTESERI.'-'.$SCTECORR.".pdf";
							$datm['corusures']=strtolower($SCTCUSUR).'@mym.com.pe';
							$datm['nomusures']=strtoupper($SCTCUSUR);

							//$rptmail=$this->send_mail_fe($datm);

							$responseContent1=$responseConten.' '.$correoElectronicoc;//.' '.$rptmail['message'];
						//}else{

						//}

							$ESTADO="A";
							$upfecli=$this->facturacionsendws_db2_model->update_fe_trama($SCTECIAA,$SCTEPDCA,$SCTEFEC,$SCTESERI,$SCTECORR,$ESTADO);
							$stfecli=$this->facturacionsendws_db2_model->set_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCod,$responseContent1); 
							$update_data=$upfecli.'##'.$stfecli;
						}else{
							$destinos=array(
								//array('correo'=>'rramos@mym.com.pe','nombres'=>'Ronald Ramos'),
								array('correo'=>strtolower($SCTCUSUR).'@mym.com.pe','nombres'=>$SCTCUSUR),								
								array('correo'=>'ahuaman@mym.com.pe','nombres'=>'Angela Huaman'),
								array('correo'=>'malvarado@mym.com.pe','nombres'=>'Myrian Alvarado'),
								array('correo'=>'cdavila@mym.com.pe','nombres'=>'Cindy Davila'),
								array('correo'=>'jmunoz@mym.com.pe','nombres'=>'Jamez Munoz'),
								array('correo'=>'gflores@mym.com.pe','nombres'=>'Giulinna Flores')
							);
							$reponame='';
							$responmail='';
							if(($responseCod=='2119')||($responseCod=='2209')){
								$datm["dat0"]=$nomTipoDOcu.' con Incidencia Referencia no Ubicado';
								$datm["dat1"]=$responseCod;
								$datm["dat2"]=$responseConten;
								$datm["dat3"]=$SCTEPDCA.' '.$SCTETDOC.' '.$SCTFECEM.' '.$SCTESERI.' '.$SCTECORR;
								$datm["dat4"]='';
								$datm['corusures']=strtolower($SCTCUSUR).'@mym.com.pe';
								$datm['nomusures']=strtoupper($SCTCUSUR);
								$datm['asunto']=$nomTipoDOcu.' '.$SCTESERI.'-'.$SCTECORR.' Rechazado por '.$responseConten;
								$datm['emaildestino']=$destinos;
								$datm['adjunto']=$SCTECRUC.'_'.$SCTETDOC.'_'.$SCTESERI.'-'.$SCTECORR.".pdf";
								$rptmail=$this->send_mail_fe($datm);

							}else{
								$datm["dat0"]=$nomTipoDOcu.' RECHAZADO ';
								$datm["dat1"]=$responseCod;
								$datm["dat2"]=$responseConten;
								$datm["dat3"]=$SCTEPDCA.' '.$SCTETDOC.' '.$SCTFECEM.' '.$SCTESERI.' '.$SCTECORR;
								$datm["dat4"]='';	
								$datm['corusures']=strtolower($SCTCUSUR).'@mym.com.pe';
								$datm['nomusures']=strtoupper($SCTCUSUR);
								$datm['emaildestino']=$destinos;
								$datm['asunto']=$nomTipoDOcu.' '.$SCTESERI.'-'.$SCTECORR.' Rechazado por '.$responseConten;
								$datm['adjunto']=$SCTECRUC.'_'.$SCTETDOC.'_'.$SCTESERI.'-'.$SCTECORR.".pdf";
								$rptmail=$this->send_mail_fe($datm);

							}
							
							$ESTADO="R";							
							$gtfecli=$this->facturacionsendws_db2_model->get_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$responseCod);
							$numfil=($gtfecli==false)?0:odbc_num_rows($gtfecli);
							if($numfil==0){
								$upfecli=$this->facturacionsendws_db2_model->update_fe_trama($SCTECIAA,$SCTEPDCA,$SCTEFEC,$SCTESERI,$SCTECORR,$ESTADO);
								$stfecli=$this->facturacionsendws_db2_model->set_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCod,$responseConten);
							}else{
								$upfecli=$this->facturacionsendws_db2_model->update_fe_trama($SCTECIAA,$SCTEPDCA,$SCTEFEC,$SCTESERI,$SCTECORR,$ESTADO);
								$stfecli=$this->facturacionsendws_db2_model->set_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCod,$responseConten);
							}
							$update_data=$upfecli.'##'.$stfecli;
						} 
					}else{
						$destinos=array(
							array('correo'=>'rramos@mym.com.pe','nombres'=>'Ronald Ramos'),
							array('correo'=>'ahuaman@mym.com.pe','nombres'=>'Angela Huaman'),
							array('correo'=>'malvarado@mym.com.pe','nombres'=>'Myrian Alvarado'),
							array('correo'=>'dtello@mym.com.pe','nombres'=>'Daniel Tello')
						);
						$ESTADO="E";
						$upfecli=$this->facturacionsendws_db2_model->update_fe_trama($SCTECIAA,$SCTEPDCA,$SCTEFEC,$SCTESERI,$SCTECORR,$ESTADO);

						$gtfecli=$this->facturacionsendws_db2_model->get_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$responseCod);
						$numfil=($gtfecli==false)?0:odbc_num_rows($gtfecli);
						if($numfil==0){
							$stfecli=$this->facturacionsendws_db2_model->set_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCod,$responseConten);
						}else{
							$stfecli=$this->facturacionsendws_db2_model->set_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCod,$responseConten);						
						}
						$update_data=$upfecli.'##'.$stfecli;
						$datm["dat0"]='Error en Plataforma del proveedor';
						$datm["dat1"]=$http_status;
						$datm["dat2"]=$http_statuscod;
						$datm["dat3"]=$docufeerror;
						$datm["dat4"]='';
						$datm['corusures']=strtolower($SCTCUSUR).'@mym.com.pe';
						$datm['nomusures']=strtoupper($SCTCUSUR);
						$datm['asunto']='Falla en la Comunicacion de F.E';
						$datm['emaildestino']=$destinos;
						$datm['adjunto']='';
						$this->send_mail_fe($datm);
					} 
				}
				
				$sendfe=["docufe"=>$documentofe,"docufersl"=>$fe,"http_status"=>$http_status,"docujson"=>$filejs,"http_statuscod"=>$http_statuscod,"docufeerror"=>$docufeerror,"prinfe"=>$resultprintfe,"errorcomunbd"=>$update_data,'d1'=>$documentofersl];
				$result['dato'] = $sendfe;	
				$result['existe'] = 1;
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($result);

			}else{
				$dat['msg']='Sin Datos';
				$sendfe=$dat;
				$result['dato'] = $sendfe;	
				$result['existe'] = 0;
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($result);
			}
		}else{
			$dat['msg']='Sin Datos';
			$sendfe=$dat;
			$result['dato'] = $sendfe;	
			$result['existe'] = 0;
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($result);	
		}
		
	}
	function data_fact01($data,$SCTIPFAC){

		$filejs01='';
		$SCTECIAA=$data['SCTECIAA'];
		$SCTESUCA=$data['SCTESUCA'];
		$SCTEPDCA=$data['valorAdicional5'];
		$SCTETDOC=$data['codTipoDocumento'];
		$stfeanti=$this->facturacionsendws_db2_model->get_anticiporelacionado_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC);
		switch ($SCTIPFAC) {
			case 'FN':
			if($stfeanti>0){
				$filejs01=$this->armarjsonfe01f($data);
			}else{
				$filejs01=$this->armarjsonfe01n($data);
			}
			break;
			case 'FS':
			$filejs01=$this->armarjsonfe01s($data);
			break;
			case 'FG':
			$filejs01=$this->armarjsonfe01g($data);
			break;
			case 'FE':
			$filejs01=$this->armarjsonfe01e($data);
			break;
			case 'FA':
			$filejs01=$this->armarjsonfe01a($data);
			break;			
		}
		return $filejs01;
	}

	
	function send_mail_fe($data){
		$htmle='';
		$this->load->library('My_PHPMailer_5');
		$codcia = $this->session->userdata('codcia');
		$f= gmdate("d-m-Y", time() - 18000);
		$hh= gmdate("H-i-s", time() - 18000);
		$destino=$data['emaildestino']; 
		$adjunto=$data['adjunto'];
		$asunto=$data['asunto'];
		$dat0=$data["dat0"];
		$dat1=$data["dat1"];
		$dat2=$data["dat2"];
		$dat3=$data["dat3"];
		$dat4=$data["dat4"];
		$corusures=$data['corusures'];
		$nomusures=$data['nomusures'];
		$Host = 'smtp.office365.com';
		$Usernamenam='Factura Electronica MYM';	
	$Username = 'facturacion@mym.com.pe';//'transfer@mym.com.pe';
	$Password = 'Abc123xyz';//'M&MF4ctvrac10n';
	$SMTPSecure = 'tls';
	$Port = 587;
	$wrapper = 'width:100%;margin:0 auto 50px auto;min-height:100px;padding:2%;background:##48D1CC;border:1px solid #c1c1c1;';
	$font = 'font-family: Helvetica Neue,Helvetica,Arial,sans-serif;';
	$color = 'color:#424242';
	$color2 = 'color:#3f3f3f';
	$tb = 'font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;    margin: 45px;     width: 480px; text-align: left;    border-collapse: collapse;';
	$tah = 'font-size: 14px;     font-weight: normal;     padding: 8px;     background: #808080;
	border-top: 4px solid #aabcfe;    border-bottom: 1px solid #fff; color: #039; ';
	$tad = 'padding: 8px;     background: #e8edff;     border-bottom: 1px solid #fff;
	color: #669;    border-top: 1px solid transparent;';
	$trh = 'tr:hover td { background: #d0dafd; color: #339; }';

	$htmle .='<div style="' . $wrapper . $font . '"><strong>'.$dat0.'</strong></br><table  style="' . $tb . '"><tr style="' . $trh . '"><td><strong>Descripción</strong></td><td><strong>Información</strong></td></tr><tr><td><strong>Estatus:</strong></td><td>'.$dat1.'</td></tr><tr><td><strong>MensajeEstatus:</strong></td><td>'.$dat2.'</td>
	</tr><tr><td><strong>MensajeError:</strong></td><td>'.$dat3.'</td></tr></table></div>';
	//$archivo=($adjunto!='')?"assest/pdffe/".$adjunto.",".$adjunto:"";

	$mail = new PHPMailer;
	try {
		$mail->isSMTP(); 



		$mail->CharSet = 'UTF-8';
		$mail->SMTPDebug = 0;	 	 
		$mail->Host = $Host;		 	  
		$mail->SMTPAuth = true;
		$mail->Username = $Username;
		$mail->Password = $Password;
		$mail->SMTPAutoTLS = true;
		$mail->SMTPSecure = $SMTPSecure;
		$mail->Port = 587;
		$mail->setFrom($Username, $Usernamenam);  
		 		 	//$mail->AddAddress($destino,$nomuser); 
		foreach ($destino as $cad) {
			$mail->AddAddress(trim($cad['correo']),trim($cad['nombres']));
		}
		$mail->AddBCC('ronald1781@gmail.com','Ronald Ramos');
		$mail->ClearReplyTos();
		$mail->AddReplyTo($corusures, $nomusures);                              
		$mail->isHTML(true); 
		$mail->Subject = $asunto; 
		$mail->Body = $htmle; 
		$mail->AltBody = "Cuerpo en texto plano";
                    //$this->mail->AddStringAttachment($doc, 'doc.pdf', 'base64', 'application/pdf'); 
		$mail->AddAttachment("assest/pdffe/".$adjunto,$adjunto);                    
		if (!$mail->send()) {
			$result["message"] = "Error en el envío: " . $mail->ErrorInfo;
			$result['existe'] = 0;
		} else {
			$correo='';
			foreach ($destino as $cad) {
				$correo.=trim($cad['correo']).' '.trim($cad['nombres']);
			}
			$result["message"] = "¡Mensaje enviado correctamente! a ".$correo;
			$result['existe'] = 1;
		}

	} catch (phpmailerException $e) {
		$result['message'] =$e->errorMessage();
		$result['existe'] = 0;
	} catch (Exception $e) {
		$result['message'] = $e->getMessage();
		$result['existe'] = 0;
	}
	return $result;
	//print_r($result);
}

function send_mail_fe_demo(){
	$result='';
	$codcia = $this->session->userdata('codcia');
	$f= gmdate("d-m-Y", time() - 18000);
	$hh= gmdate("H-i-s", time() - 18000);
		 $destino='rramos@mym.com.pe'; //helpdesk
		 $nomuser='Ronald MYM';
		 $cuerpohtml='<div style="width:100%;margin:0 auto 50px auto;min-height:100px;padding:2%;background:##48D1CC;border:1px solid #c1c1c1;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;">Documento con Incidencia </br><table  style="font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
		 font-size: 12px;    margin: 45px;     width: 480px; text-align: left;    border-collapse: collapse;">
		 <tr style="tr:hover td { background: #d0dafd; color: #339; }">
		 <td><strong>Descripción</strong></td><td><strong>Información</strong></td>
		 </tr>
		 <tr><td><strong>Codigo:</strong></td><td>2108</td>
		 </tr>
		 <tr>
		 <td><strong>Mensaje:</strong></td><td>Presentacion fuera de fecha 15-08-2019 11:52:05</td>
		 </tr>                                         
		 <tr>
		 <td><strong>Documento:</strong></td><td>14048 07 2019-08-02 FN01 00000006</td>
		 </tr>
		 </table></div>';
		 $htmle='';
		 $adjunto='20101759688_07_FN01-00000006.pdf';
		 $this->load->library('My_PHPMailer_5');
		 $mail = new PHPMailer;
		 $mail->isSMTP(); 
		 $mail->Host = 'smtp.office365.com';
		 $mail->SMTPAuth = true;
		 $mail->Username = 'facturacion@mym.com.pe';
		 $mail->Password = 'Abc123xyz';
		 $mail->SMTPSecure = 'tls';
		 $mail->Port = 587;
		 $mail->setFrom('facturacion@mym.com.pe', 'Facturacion mym');
		 $mail->AddAddress($destino,$nomuser);
		 $mail->AddBCC('ronald1781@gmail.com','Ronald Ramos');
		 $mail->isHTML(true); 
		 $mail->Subject = 'TEST ENVIO '.$f.' '.$hh; 
		 $htmle=$cuerpohtml;
		 $mail->Body = $htmle; 
		 $mail->AltBody = "Cuerpo en texto plano";  
		 $mail->AddAttachment("assest/pdffe/".$adjunto,$adjunto);           
		 if(!$mail->send()) {
		 	$result= 'El mensaje no pudo ser enviado.';
		 	$result.='Error en el envío: ' . $mail->ErrorInfo;
		 } else {
		 	$result= "¡Mensaje enviado correctamente! a ".$destino;

		 }
		 return $result;
		}
		
		

		function armarjsonfe01n($dato){
			$detallepcdfe=array();
			$datadet=$dato['detallepcd'];
			foreach ($datadet as $cad ) {
				$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
					"codigoProducto"=>$cad['codigoProducto'],
					"descripcionProducto"=>$cad['descripcionProducto'],
					"cantidadItems"=>$cad['cantidadItems'],
					"unidad"=>$cad['unidad'],
					"valorUnitario"=>$cad['valorUnitario'],
					"precioVentaUnitario"=>$cad['precioVentaUnitario'],
					"totalImpuestos"=>array(
						array(
							"idImpuesto"=>$cad['idImpuesto'],
							"montoImpuesto"=>$cad['montoImpuesto'],
							"tipoAfectacion"=>$cad['tipoAfectacion'],
							"montoBase"=>$cad['montoBase'],
							"porcentaje"=>$cad['porcentaje'])),
					"valorVenta"=>$cad['valorVenta'],
					"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
			}
			$json=new Services_JSON();
			$documentofe01 = ['factura'=>array(
				"IDE"=>array("numeracion"=>$dato['numeracion'],
					"fechaEmision"=>$dato['fechaEmision'],
					"horaEmision"=>$dato['horaEmision'],
					"codTipoDocumento"=>$dato['codTipoDocumento'],
					"tipoMoneda"=>$dato['tipoMoneda'],
					"numeroOrdenCompra"=>$dato['numeroOrdenCompra'],
					"fechaVencimiento"=>$dato['fechaVencimiento']
				),
				"EMI"=>array(
					"tipoDocId"=>$dato['tipoDocId'],
					"numeroDocId"=>$dato['numeroDocId'],
					"nombreComercial"=>$dato['nombreComercial'],
					"razonSocial"=>$dato['razonSocial'],
					"ubigeo"=>$dato['ubigeo'],
					"direccion"=>$dato['direccion'],
					"urbanizacion"=>$dato['urbanizacion'],
					"provincia"=>$dato['provincia'],
					"departamento"=>$dato['departamento'],
					"distrito"=>$dato['distrito'],
					"codigoPais"=>$dato['codigoPais'],
					"telefono"=>$dato['telefono'],
					"correoElectronico"=>$dato['correoElectronico'],
					"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
				),
				"REC"=>array( 
					"tipoDocId"=>$dato['tipoDocIdc'],
					"numeroDocId"=>$dato['numeroDocIdc'],
					"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
					"direccion"=>mb_convert_encoding($dato['direccionc'],"UTF-8", "iso-8859-1"),
					"departamento"=>mb_convert_encoding($dato['departamentoc'],"UTF-8", "iso-8859-1"),
					"provincia"=>mb_convert_encoding($dato['provinciac'],"UTF-8", "iso-8859-1"),
					"distrito"=>mb_convert_encoding($dato['distritoc'],"UTF-8", "iso-8859-1"),
					"codigoPais"=>$dato['codigoPaisc'],
					"telefono"=>$dato['telefonoc'],
					"correoElectronico"=>mb_convert_encoding($dato['correoElectronicoc'],"UTF-8", "iso-8859-1")
				),
				"DRF"=>array(array("tipoDocRelacionado"=>$dato['tipoDocRelacionado'],
					"numeroDocRelacionado"=>$dato['numeroDocRelacionado'])
			),
				"CAB"=>array("gravadas"=>array("codigo"=>$dato['codigoc'],
					"totalVentas"=>$dato['totalVentasc']
				),
				"totalImpuestos"=>array(array("idImpuesto"=>$dato['idImpuestoc'],
					"montoImpuesto"=>$dato['montoImpuestoc']
				)),
				"importeTotal"=>$dato['importeTotalc'],
				"tipoOperacion"=>$dato['tipoOperacionc'],
				"leyenda"=>array(array("codigo"=>$dato['codigolc'],
					"descripcion"=>$dato['descripcionlc'])
			),
				"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
			),
				"DET"=>$detallepcdfe,
				"ADI"=>array(
					array( "tituloAdicional"=>mb_convert_encoding($dato['tituloAdicional1'],"UTF-8", "iso-8859-1"), 
						"valorAdicional"=> $dato['valorAdicional1']),
					array( "tituloAdicional"=>$dato['tituloAdicional2'], 
						"valorAdicional"=> mb_convert_encoding($dato['valorAdicional2'],"UTF-8", "iso-8859-1")),
					array("tituloAdicional"=> $dato['tituloAdicional3'], 
						"valorAdicional"=> $dato['valorAdicional3']),
					array("tituloAdicional"=> $dato['tituloAdicional4'], 
						"valorAdicional"=> $dato['valorAdicional4']),
					array("tituloAdicional"=> $dato['tituloAdicional5'], 
						"valorAdicional"=> $dato['valorAdicional5']),
					array("tituloAdicional"=> $dato['tituloAdicional6'], 
						"valorAdicional"=>mb_convert_encoding($dato['valorAdicional6'],"UTF-8", "iso-8859-1")),
					array("tituloAdicional"=> $dato['tituloAdicional7'], 
						"valorAdicional"=> $dato['valorAdicional7']),
					array("tituloAdicional"=> $dato['tituloAdicionaln'], 
						"valorAdicional"=> $dato['valorAdicionaln'])
				)
			)];
			header('Content-type: text/x-json; UTF-8');
			header('Content-type: application/json; charset=utf-8');
			return json_encode($documentofe01,true);

		}

		function armarjsonfe01s($dato){
			$detallepcdfe=array();
			$datadet=$dato['detallepcd'];
			foreach ($datadet as $cad ) {
				$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
					"codigoProducto"=>$cad['codigoProducto'],
					"descripcionProducto"=>$cad['descripcionProducto'],
					"cantidadItems"=>$cad['cantidadItems'],
					"unidad"=>$cad['unidad'],
					"valorUnitario"=>$cad['valorUnitario'],
					"precioVentaUnitario"=>$cad['precioVentaUnitario'],
					"totalImpuestos"=>array(
						array(
							"idImpuesto"=>$cad['idImpuesto'],
							"montoImpuesto"=>$cad['montoImpuesto'],
							"tipoAfectacion"=>$cad['tipoAfectacion'],
							"montoBase"=>$cad['montoBase'],
							"porcentaje"=>$cad['porcentaje'])),
					"valorVenta"=>$cad['valorVenta'],
					"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
			}
			$json=new Services_JSON();
			$documentofe01 = ['factura'=>array(
				"IDE"=>array("numeracion"=>$dato['numeracion'],
					"fechaEmision"=>$dato['fechaEmision'],
					"horaEmision"=>$dato['horaEmision'],
					"codTipoDocumento"=>$dato['codTipoDocumento'],
					"tipoMoneda"=>$dato['tipoMoneda'],
					"numeroOrdenCompra"=>$dato['numeroOrdenCompra'],
					"fechaVencimiento"=>$dato['fechaVencimiento']
				),
				"EMI"=>array(
					"tipoDocId"=>$dato['tipoDocId'],
					"numeroDocId"=>$dato['numeroDocId'],
					"nombreComercial"=>$dato['nombreComercial'],
					"razonSocial"=>$dato['razonSocial'],
					"ubigeo"=>$dato['ubigeo'],
					"direccion"=>$dato['direccion'],
					"urbanizacion"=>$dato['urbanizacion'],
					"provincia"=>$dato['provincia'],
					"departamento"=>$dato['departamento'],
					"distrito"=>$dato['distrito'],
					"codigoPais"=>$dato['codigoPais'],
					"telefono"=>$dato['telefono'],
					"correoElectronico"=>$dato['correoElectronico'],
					"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
				),
				"REC"=>array( 
					"tipoDocId"=>$dato['tipoDocIdc'],
					"numeroDocId"=>$dato['numeroDocIdc'],
					"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
					"direccion"=>mb_convert_encoding($dato['direccionc'],"UTF-8", "iso-8859-1"),
					"departamento"=>mb_convert_encoding($dato['departamentoc'],"UTF-8", "iso-8859-1"),
					"provincia"=>mb_convert_encoding($dato['provinciac'],"UTF-8", "iso-8859-1"),
					"distrito"=>mb_convert_encoding($dato['distritoc'],"UTF-8", "iso-8859-1"),
					"codigoPais"=>$dato['codigoPaisc'],
					"telefono"=>$dato['telefonoc'],
					"correoElectronico"=>mb_convert_encoding($dato['correoElectronicoc'],"UTF-8", "iso-8859-1")
				),

				"CAB"=>array("gravadas"=>array("codigo"=>$dato['codigoc'],
					"totalVentas"=>$dato['totalVentasc']
				),
				"totalImpuestos"=>array(array("idImpuesto"=>$dato['idImpuestoc'],
					"montoImpuesto"=>$dato['montoImpuestoc']
				)),
				"importeTotal"=>$dato['importeTotalc'],
				"tipoOperacion"=>$dato['tipoOperacionc'],
				"leyenda"=>array(array("codigo"=>$dato['codigolc'],
					"descripcion"=>$dato['descripcionlc']),
				array("codigo"=>$dato['codigold'],
					"descripcion"=>$dato['descripcionld'])
			),
				"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
			),
				"DET"=>$detallepcdfe,
				"ADI"=>array(
					array( "tituloAdicional"=>mb_convert_encoding($dato['tituloAdicional1'],"UTF-8", "iso-8859-1"), 
						"valorAdicional"=> $dato['valorAdicional1']),
					array( "tituloAdicional"=>$dato['tituloAdicional2'], 
						"valorAdicional"=> mb_convert_encoding($dato['valorAdicional2'],"UTF-8", "iso-8859-1")),
					array("tituloAdicional"=> $dato['tituloAdicional3'], 
						"valorAdicional"=> $dato['valorAdicional3']),
					array("tituloAdicional"=> $dato['tituloAdicional4'], 
						"valorAdicional"=> $dato['valorAdicional4']),
					array("tituloAdicional"=> $dato['tituloAdicional5'], 
						"valorAdicional"=> $dato['valorAdicional5']),
					array("tituloAdicional"=> $dato['tituloAdicional6'], 
						"valorAdicional"=>mb_convert_encoding($dato['valorAdicional6'],"UTF-8", "iso-8859-1")),
					array("tituloAdicional"=> $dato['tituloAdicional7'], 
						"valorAdicional"=> $dato['valorAdicional7']),
/*
			array("tituloAdicional"=> $dato['datacodsdt'], 
				"valorAdicional"=> $dato['datacodsdv']),
			array("tituloAdicional"=> $dato['datanroctabnsdt'], 
				"valorAdicional"=> $dato['datanroctabnsdv']),
			array("tituloAdicional"=> $dato['datamdiopgosdt'], 
				"valorAdicional"=> $dato['datamdiopgosdv']),
			array("tituloAdicional"=> $dato['datamtopgodsdt'], 
				"valorAdicional"=> $dato['datamtopgodsdv']),
			array("tituloAdicional"=> $dato['dataprtjpgodsdt'], 
				"valorAdicional"=> $dato['dataprtjpgodsdv']),
*/
				array("tituloAdicional"=> $dato['tituloAdicionaln'], 
					"valorAdicional"=> $dato['valorAdicionaln'])
			)
			)
		];
		header('Content-type: text/x-json; UTF-8');
		header('Content-type: application/json; charset=utf-8');
		return json_encode($documentofe01,true);

	}
	function armarjsonfe01g($dato){
		$detallepcdfe=array();
		$datadet=$dato['detallepcd'];
//se debe revisar este caso con james para levantar las observaciones 20191031 1824
		$totalVentasc=0;
		foreach ($datadet as $cad ) {
			$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
				"codigoProducto"=>$cad['codigoProducto'],
				"descripcionProducto"=>$cad['descripcionProducto'],
				"cantidadItems"=>$cad['cantidadItems'],
				"unidad"=>$cad['unidad'],
			"valorUnitario"=>'0.00',//0.00 $cad['valorUnitario']
			"precioVentaUnitario"=>$cad['precioVentaUnitario'],//0.00
			"totalImpuestos"=>array(
				array(
					"idImpuesto"=>$cad['idImpuesto'],//9996
					"montoImpuesto"=>$cad['montoImpuesto'],// 
					"tipoAfectacion"=>$cad['tipoAfectacion'],// en as400 esta con 13 se debe corregir
					"montoBase"=>$cad['valorVenta'],//montoBase 
					"porcentaje"=>$cad['porcentaje'])),//
			"valorVenta"=>$cad['valorVenta'], //si es un producto el valor total de este
			"valorRefOpOnerosas"=>$cad['valorVenta'],
			"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
			$totalVentasc=$totalVentasc+$cad['valorVenta'];
		}

		$json=new Services_JSON();
		$documentofe01 = ['factura'=>array(
			"IDE"=>array("numeracion"=>$dato['numeracion'],
				"fechaEmision"=>$dato['fechaEmision'],
				"horaEmision"=>$dato['horaEmision'],
				"codTipoDocumento"=>$dato['codTipoDocumento'],
				"tipoMoneda"=>$dato['tipoMoneda'],
				"numeroOrdenCompra"=>$dato['numeroOrdenCompra'],
				"fechaVencimiento"=>$dato['fechaVencimiento']
			),
			"EMI"=>array(
				"tipoDocId"=>$dato['tipoDocId'],
				"numeroDocId"=>$dato['numeroDocId'],
				"nombreComercial"=>$dato['nombreComercial'],
				"razonSocial"=>$dato['razonSocial'],
				"ubigeo"=>$dato['ubigeo'],
				"direccion"=>$dato['direccion'],
				"urbanizacion"=>$dato['urbanizacion'],
				"provincia"=>$dato['provincia'],
				"departamento"=>$dato['departamento'],
				"distrito"=>$dato['distrito'],
				"codigoPais"=>$dato['codigoPais'],
				"telefono"=>$dato['telefono'],
				"correoElectronico"=>$dato['correoElectronico'],
				"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
			),
			"REC"=>array( 
				"tipoDocId"=>$dato['tipoDocIdc'],
				"numeroDocId"=>$dato['numeroDocIdc'],
				"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
				"direccion"=>mb_convert_encoding($dato['direccionc'],"UTF-8", "iso-8859-1"),
				"departamento"=>mb_convert_encoding($dato['departamentoc'],"UTF-8", "iso-8859-1"),
				"provincia"=>mb_convert_encoding($dato['provinciac'],"UTF-8", "iso-8859-1"),
				"distrito"=>mb_convert_encoding($dato['distritoc'],"UTF-8", "iso-8859-1"),
				"codigoPais"=>$dato['codigoPaisc'],
				"telefono"=>$dato['telefonoc'],
				"correoElectronico"=>mb_convert_encoding($dato['correoElectronicoc'],"UTF-8", "iso-8859-1")
			),
			"DRF"=>array(array("tipoDocRelacionado"=>$dato['tipoDocRelacionado'],
				"numeroDocRelacionado"=>$dato['numeroDocRelacionado'])
		),
			"CAB"=>array("gratuitas"=>array("codigo"=>$dato['codigoc'],
			"totalVentas"=>(String)$totalVentasc,//$dato['totalVentasc'] Aqui esta apareciendo total venta gratuita 0.00 en as400 
		),
			"totalImpuestos"=>array(array("idImpuesto"=>$dato['idImpuestoc'],
				"montoImpuesto"=>$dato['montoImpuestoc']
			)),
			"importeTotal"=>$dato['importeTotalc'],
		"tipoOperacion"=>$dato['tipoOperacionc'],//0101
		"leyenda"=>array(array("codigo"=>$dato['codigolc'],
			"descripcion"=>$dato['descripcionlc']),
		array("codigo"=>$dato['codigolgc'],
			"descripcion"=>$dato['descripciongc'])
	),
		"montoTotalImpuestos"=>$dato['montoTotalImpuestos']//0.00
	),
			"DET"=>$detallepcdfe,
			"ADI"=>array(
				array( "tituloAdicional"=>mb_convert_encoding($dato['tituloAdicional1'],"UTF-8", "iso-8859-1"), 
					"valorAdicional"=> $dato['valorAdicional1']),
				array( "tituloAdicional"=>$dato['tituloAdicional2'], 
					"valorAdicional"=> mb_convert_encoding($dato['valorAdicional2'],"UTF-8", "iso-8859-1")),
				array("tituloAdicional"=> $dato['tituloAdicional3'], 
					"valorAdicional"=> $dato['valorAdicional3']),
				array("tituloAdicional"=> $dato['tituloAdicional4'], 
					"valorAdicional"=> $dato['valorAdicional4']),
				array("tituloAdicional"=> $dato['tituloAdicional5'], 
					"valorAdicional"=> $dato['valorAdicional5']),
				array("tituloAdicional"=> $dato['tituloAdicional6'], 
					"valorAdicional"=>mb_convert_encoding($dato['valorAdicional6'],"UTF-8", "iso-8859-1")),
				array("tituloAdicional"=> $dato['tituloAdicional7'], 
					"valorAdicional"=> $dato['valorAdicional7']),
				array("tituloAdicional"=> $dato['tituloAdicionaln'], 
					"valorAdicional"=> $dato['valorAdicionaln'])
			)
		)
	];
	header('Content-type: text/x-json; UTF-8');
	header('Content-type: application/json; charset=utf-8');
	return json_encode($documentofe01,true);
}
function armarjsonfe01e($dato){
	$detallepcdfe=array();
	$datadet=$dato['detallepcd'];
	foreach ($datadet as $cad ) {
		$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
			"codigoProducto"=>$cad['codigoProducto'],
			"codProductoSunat"=>$cad['codigoProducto'],
			"descripcionProducto"=>$cad['descripcionProducto'],
			"cantidadItems"=>$cad['cantidadItems'],
			"unidad"=>$cad['unidad'],
			"valorUnitario"=>$cad['valorUnitario'],
			"precioVentaUnitario"=>$cad['precioVentaUnitario'],
			"totalImpuestos"=>array(
				array(
					"idImpuesto"=>$cad['idImpuesto'],
					"montoImpuesto"=>$cad['montoImpuesto'],
					"tipoAfectacion"=>$cad['tipoAfectacion'],
					"montoBase"=>$cad['montoBase'],
					"porcentaje"=>$cad['porcentaje'])),
			"valorVenta"=>$cad['valorVenta'],
			"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
	}
	$json=new Services_JSON();
	$documentofe01 = ['factura'=>array(
		"IDE"=>array("numeracion"=>$dato['numeracion'],
			"fechaEmision"=>$dato['fechaEmision'],
			"horaEmision"=>$dato['horaEmision'],
			"codTipoDocumento"=>$dato['codTipoDocumento'],
			"tipoMoneda"=>$dato['tipoMoneda'],
			"numeroOrdenCompra"=>$dato['numeroOrdenCompra'],
			"fechaVencimiento"=>$dato['fechaVencimiento']
		),
		"EMI"=>array(
			"tipoDocId"=>$dato['tipoDocId'],
			"numeroDocId"=>$dato['numeroDocId'],
			"nombreComercial"=>$dato['nombreComercial'],
			"razonSocial"=>$dato['razonSocial'],
			"ubigeo"=>$dato['ubigeo'],
			"direccion"=>$dato['direccion'],
			"urbanizacion"=>$dato['urbanizacion'],
			"provincia"=>$dato['provincia'],
			"departamento"=>$dato['departamento'],
			"distrito"=>$dato['distrito'],
			"codigoPais"=>$dato['codigoPais'],
			"telefono"=>$dato['telefono'],
			"correoElectronico"=>$dato['correoElectronico'],
			"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
		),
		"REC"=>array( 
			"tipoDocId"=>$dato['tipoDocIdc'],
			"numeroDocId"=>$dato['numeroDocIdc'],
			"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
			"direccion"=>mb_convert_encoding($dato['direccionc'],"UTF-8", "iso-8859-1"),
			"departamento"=>mb_convert_encoding($dato['departamentoc'],"UTF-8", "iso-8859-1"),
			"provincia"=>mb_convert_encoding($dato['provinciac'],"UTF-8", "iso-8859-1"),
			"distrito"=>mb_convert_encoding($dato['distritoc'],"UTF-8", "iso-8859-1"),
			"codigoPais"=>$dato['codigoPaisc'],
			"telefono"=>$dato['telefonoc'],
			"correoElectronico"=>mb_convert_encoding($dato['correoElectronicoc'],"UTF-8", "iso-8859-1")
		),
		"DRF"=>array(array("tipoDocRelacionado"=>$dato['tipoDocRelacionado'],
			"numeroDocRelacionado"=>$dato['numeroDocRelacionado'])
	),
		"CAB"=>array("exportadas"=>array("codigo"=>$dato['codigoc'],
			"totalVentas"=>$dato['totalVentasc']
		),
		"totalImpuestos"=>array(array("idImpuesto"=>$dato['idImpuestoc'],
			"montoImpuesto"=>$dato['montoImpuestoc']
		)),
		"importeTotal"=>$dato['importeTotalc'],
		"tipoOperacion"=>$dato['tipoOperacionc'],
		"leyenda"=>array(array("codigo"=>$dato['codigolc'],
			"descripcion"=>$dato['descripcionlc'])
	),
		"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
	),
		"DET"=>$detallepcdfe,
		"ADI"=>array(
			array( "tituloAdicional"=>mb_convert_encoding($dato['tituloAdicional1'],"UTF-8", "iso-8859-1"), 
				"valorAdicional"=> $dato['valorAdicional1']),
			array( "tituloAdicional"=>$dato['tituloAdicional2'], 
				"valorAdicional"=> mb_convert_encoding($dato['valorAdicional2'],"UTF-8", "iso-8859-1")),
			array("tituloAdicional"=> $dato['tituloAdicional3'], 
				"valorAdicional"=> $dato['valorAdicional3']),
			array("tituloAdicional"=> $dato['tituloAdicional4'], 
				"valorAdicional"=> $dato['valorAdicional4']),
			array("tituloAdicional"=> $dato['tituloAdicional5'], 
				"valorAdicional"=> $dato['valorAdicional5']),
			array("tituloAdicional"=> $dato['tituloAdicional6'], 
				"valorAdicional"=>mb_convert_encoding($dato['valorAdicional6'],"UTF-8", "iso-8859-1")),
			array("tituloAdicional"=> $dato['tituloAdicional7'], 
				"valorAdicional"=> $dato['valorAdicional7']),
			array("tituloAdicional"=> "TIPO DE FACTURA", 
				"valorAdicional"=>"EXPORTACION DE BIENES"),			
			array("tituloAdicional"=> $dato['tituloAdicionaln'], 
				"valorAdicional"=> $dato['valorAdicionaln'])
		)
	)
];
header('Content-type: text/x-json; UTF-8');
header('Content-type: application/json; charset=utf-8');
return json_encode($documentofe01,true);
}

function armarjsonfe01a($dato){
	$detallepcdfe=array();
	$datadet=$dato['detallepcd'];
	foreach ($datadet as $cad ) {
		$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
			"codigoProducto"=>$cad['codigoProducto'],
			"descripcionProducto"=>$cad['descripcionProducto'],
			"cantidadItems"=>$cad['cantidadItems'],
			"unidad"=>$cad['unidad'],
			"valorUnitario"=>$cad['valorUnitario'],
			"precioVentaUnitario"=>$cad['precioVentaUnitario'],
			"totalImpuestos"=>array(
				array(
					"idImpuesto"=>$cad['idImpuesto'],
					"montoImpuesto"=>$cad['montoImpuesto'],
					"tipoAfectacion"=>$cad['tipoAfectacion'],
					"montoBase"=>$cad['montoBase'],
					"porcentaje"=>$cad['porcentaje'])),
			"valorVenta"=>$cad['valorVenta'],
			"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
	}
	$json=new Services_JSON();
	$documentofe01 = ['factura'=>array(
		"IDE"=>array("numeracion"=>$dato['numeracion'],
			"fechaEmision"=>$dato['fechaEmision'],
			"horaEmision"=>$dato['horaEmision'],
			"codTipoDocumento"=>$dato['codTipoDocumento'],
			"tipoMoneda"=>$dato['tipoMoneda'],
			"numeroOrdenCompra"=>$dato['numeroOrdenCompra'],
			"fechaVencimiento"=>$dato['fechaVencimiento']
		),
		"EMI"=>array(
			"tipoDocId"=>$dato['tipoDocId'],
			"numeroDocId"=>$dato['numeroDocId'],
			"nombreComercial"=>$dato['nombreComercial'],
			"razonSocial"=>$dato['razonSocial'],
			"ubigeo"=>$dato['ubigeo'],
			"direccion"=>$dato['direccion'],
			"urbanizacion"=>$dato['urbanizacion'],
			"provincia"=>$dato['provincia'],
			"departamento"=>$dato['departamento'],
			"distrito"=>$dato['distrito'],
			"codigoPais"=>$dato['codigoPais'],
			"telefono"=>$dato['telefono'],
			"correoElectronico"=>$dato['correoElectronico'],
			"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
		),
		"REC"=>array( 
			"tipoDocId"=>$dato['tipoDocIdc'],
			"numeroDocId"=>$dato['numeroDocIdc'],
			"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
			"direccion"=>mb_convert_encoding($dato['direccionc'],"UTF-8", "iso-8859-1"),
			"departamento"=>mb_convert_encoding($dato['departamentoc'],"UTF-8", "iso-8859-1"),
			"provincia"=>mb_convert_encoding($dato['provinciac'],"UTF-8", "iso-8859-1"),
			"distrito"=>mb_convert_encoding($dato['distritoc'],"UTF-8", "iso-8859-1"),
			"codigoPais"=>$dato['codigoPaisc'],
			"telefono"=>$dato['telefonoc'],
			"correoElectronico"=>mb_convert_encoding($dato['correoElectronicoc'],"UTF-8", "iso-8859-1")
		),
		"DRF"=>array(array("tipoDocRelacionado"=>$dato['tipoDocRelacionado'],
			"numeroDocRelacionado"=>$dato['numeroDocRelacionado'])
	),
		"CAB"=>array("gravadas"=>array("codigo"=>$dato['codigoc'],
			"totalVentas"=>$dato['totalVentasc']
		),
		"totalImpuestos"=>array(array("idImpuesto"=>$dato['idImpuestoc'],
			"montoImpuesto"=>$dato['montoImpuestoc']
		)),
		"importeTotal"=>$dato['importeTotalc'],
		"tipoOperacion"=>$dato['tipoOperacionc'],
		"leyenda"=>array(array("codigo"=>$dato['codigolc'],
			"descripcion"=>$dato['descripcionlc'])
	),
		"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
	),
		"DET"=>$detallepcdfe,
		"ADI"=>array(
			array( "tituloAdicional"=>mb_convert_encoding($dato['tituloAdicional1'],"UTF-8", "iso-8859-1"), 
				"valorAdicional"=> $dato['valorAdicional1']),
			array( "tituloAdicional"=>$dato['tituloAdicional2'], 
				"valorAdicional"=> mb_convert_encoding($dato['valorAdicional2'],"UTF-8", "iso-8859-1")),
			array("tituloAdicional"=> $dato['tituloAdicional3'], 
				"valorAdicional"=> $dato['valorAdicional3']),
			array("tituloAdicional"=> $dato['tituloAdicional4'], 
				"valorAdicional"=> $dato['valorAdicional4']),
			array("tituloAdicional"=> $dato['tituloAdicional5'], 
				"valorAdicional"=> $dato['valorAdicional5']),
			array("tituloAdicional"=> $dato['tituloAdicional6'], 
				"valorAdicional"=>mb_convert_encoding($dato['valorAdicional6'],"UTF-8", "iso-8859-1")),
			array("tituloAdicional"=> $dato['tituloAdicional7'], 
				"valorAdicional"=> $dato['valorAdicional7']),
			array("tituloAdicional"=> $dato['tituloAdicionaln'], 
				"valorAdicional"=> $dato['valorAdicionaln'])
		)
	)
];
header('Content-type: text/x-json; UTF-8');
header('Content-type: application/json; charset=utf-8');
return json_encode($documentofe01,true);
}

function armarjsonfe01f($dato){
	$detallepcdfe=array();
	$datoAnticipo =array();
	$infoAnticipo=$dato['infoAnticipo'];	
	$datadet=$dato['detallepcd'];
	foreach ($datadet as $cad ) {
		$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
			"codigoProducto"=>$cad['codigoProducto'],
			"descripcionProducto"=>$cad['descripcionProducto'],
			"cantidadItems"=>$cad['cantidadItems'],
			"unidad"=>$cad['unidad'],
			"valorUnitario"=>$cad['valorUnitario'],
			"precioVentaUnitario"=>$cad['precioVentaUnitario'],
			"totalImpuestos"=>array(
				array(
					"idImpuesto"=>$cad['idImpuesto'],
					"montoImpuesto"=>$cad['montoImpuesto'],
					"tipoAfectacion"=>$cad['tipoAfectacion'],
					"montoBase"=>$cad['montoBase'],
					"porcentaje"=>$cad['porcentaje'])),
			"valorVenta"=>$cad['valorVenta'],
			"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
	}
	foreach ($infoAnticipo as $cade ) {
		$datoAnticipo[]=array(
			"serieNumero"=>$cade['serieNumero'],
			"tipoComprobante"=> $cade['tipoComprobante'],
			"monto"=>$cade['monto'],
			"tipoMoneda"=>$cade['tipoMoneda'],
			"fechaAnticipo"=>$cade['fechaAnticipo'],
			"numeroDocEmisor"=>$cade['numeroDocEmisor'],
			"tipoDocumentoEmisor"=> $cade['tipoDocumentoEmisor'],
			"identificador"=> $cade['identificador']
		);
	}
	$json=new Services_JSON();
	$documentofe01 = ['factura'=>array(
		"IDE"=>array("numeracion"=>$dato['numeracion'],
			"fechaEmision"=>$dato['fechaEmision'],
			"horaEmision"=>$dato['horaEmision'],
			"codTipoDocumento"=>$dato['codTipoDocumento'],
			"tipoMoneda"=>$dato['tipoMoneda'],
			"numeroOrdenCompra"=>$dato['numeroOrdenCompra'],
			"fechaVencimiento"=>$dato['fechaVencimiento']
		),
		"EMI"=>array(
			"tipoDocId"=>$dato['tipoDocId'],
			"numeroDocId"=>$dato['numeroDocId'],
			"nombreComercial"=>$dato['nombreComercial'],
			"razonSocial"=>$dato['razonSocial'],
			"ubigeo"=>$dato['ubigeo'],
			"direccion"=>$dato['direccion'],
			"urbanizacion"=>$dato['urbanizacion'],
			"provincia"=>$dato['provincia'],
			"departamento"=>$dato['departamento'],
			"distrito"=>$dato['distrito'],
			"codigoPais"=>$dato['codigoPais'],
			"telefono"=>$dato['telefono'],
			"correoElectronico"=>$dato['correoElectronico'],
			"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
		),
		"REC"=>array( 
			"tipoDocId"=>$dato['tipoDocIdc'],
			"numeroDocId"=>$dato['numeroDocIdc'],
			"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
			"direccion"=>mb_convert_encoding($dato['direccionc'],"UTF-8", "iso-8859-1"),
			"departamento"=>mb_convert_encoding($dato['departamentoc'],"UTF-8", "iso-8859-1"),
			"provincia"=>mb_convert_encoding($dato['provinciac'],"UTF-8", "iso-8859-1"),
			"distrito"=>mb_convert_encoding($dato['distritoc'],"UTF-8", "iso-8859-1"),
			"codigoPais"=>$dato['codigoPaisc'],
			"telefono"=>$dato['telefonoc'],
			"correoElectronico"=>mb_convert_encoding($dato['correoElectronicoc'],"UTF-8", "iso-8859-1")
		),
		"DRF"=>array(array("tipoDocRelacionado"=>$dato['tipoDocRelacionado'],
			"numeroDocRelacionado"=>$dato['numeroDocRelacionado'])
	),
		"CAB"=>array("gravadas"=>array("codigo"=>$dato['codigoc'],
			"totalVentas"=>$dato['totalVentasc']
		),
		"totalImpuestos"=>array(array("idImpuesto"=>$dato['idImpuestoc'],
			"montoImpuesto"=>$dato['montoImpuestoc']
		)),
		"importeTotal"=>$dato['importeTotalc'],
		"informacionAnticipo"=>$datoAnticipo,
		"totalAnticipos"=>$dato['totalAnticipos'],
		"tipoOperacion"=>$dato['tipoOperacionc'],
		"leyenda"=>array(array("codigo"=>$dato['codigolc'],
			"descripcion"=>$dato['descripcionlc'])
	),
		"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
	),
		"DET"=>$detallepcdfe,
		"ADI"=>array(
			array( "tituloAdicional"=>mb_convert_encoding($dato['tituloAdicional1'],"UTF-8", "iso-8859-1"), 
				"valorAdicional"=> $dato['valorAdicional1']),
			array( "tituloAdicional"=>$dato['tituloAdicional2'], 
				"valorAdicional"=> mb_convert_encoding($dato['valorAdicional2'],"UTF-8", "iso-8859-1")),
			array("tituloAdicional"=> $dato['tituloAdicional3'], 
				"valorAdicional"=> $dato['valorAdicional3']),
			array("tituloAdicional"=> $dato['tituloAdicional4'], 
				"valorAdicional"=> $dato['valorAdicional4']),
			array("tituloAdicional"=> $dato['tituloAdicional5'], 
				"valorAdicional"=> $dato['valorAdicional5']),
			array("tituloAdicional"=> $dato['tituloAdicional6'], 
				"valorAdicional"=>mb_convert_encoding($dato['valorAdicional6'],"UTF-8", "iso-8859-1")),
			array("tituloAdicional"=> $dato['tituloAdicional7'], 
				"valorAdicional"=> $dato['valorAdicional7']),
			array("tituloAdicional"=> $dato['tituloAdicionaln'], 
				"valorAdicional"=> $dato['valorAdicionaln'])
		)
	)
];
header('Content-type: text/x-json; UTF-8');
header('Content-type: application/json; charset=utf-8');
return json_encode($documentofe01,true);
}

function armarjsonfe03($dato){
	$detallepcdfe=array();
	$datadet=$dato['detallepcd'];
	foreach ($datadet as $cad ) {
		$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
			"codigoProducto"=>$cad['codigoProducto'],
			"descripcionProducto"=>$cad['descripcionProducto'],
			"cantidadItems"=>$cad['cantidadItems'],
			"unidad"=>$cad['unidad'],
			"valorUnitario"=>$cad['valorUnitario'],
			"precioVentaUnitario"=>$cad['precioVentaUnitario'],
			"totalImpuestos"=>array(
				array(
					"idImpuesto"=>$cad['idImpuesto'],
					"montoImpuesto"=>$cad['montoImpuesto'],
					"tipoAfectacion"=>$cad['tipoAfectacion'],
					"montoBase"=>$cad['montoBase'],
					"porcentaje"=>$cad['porcentaje'])),
			"valorVenta"=>$cad['valorVenta'],
			"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
	}
	$json=new Services_JSON();
	$documentofe03 = ['boleta'=>array(
		"IDE"=>array("numeracion"=>$dato['numeracion'],
			"fechaEmision"=>$dato['fechaEmision'],
			"horaEmision"=>$dato['horaEmision'],
			"codTipoDocumento"=>$dato['codTipoDocumento'],
			"tipoMoneda"=>$dato['tipoMoneda'],
			"numeroOrdenCompra"=>$dato['numeroOrdenCompra'],
			"fechaVencimiento"=>$dato['fechaVencimiento']
		),
		"EMI"=>array("tipoDocId"=>$dato['tipoDocId'],
			"numeroDocId"=>$dato['numeroDocId'],
			"nombreComercial"=>$dato['nombreComercial'],
			"razonSocial"=>$dato['razonSocial'],
			"ubigeo"=>$dato['ubigeo'],
			"direccion"=>$dato['direccion'],
			"urbanizacion"=>$dato['urbanizacion'],
			"provincia"=>$dato['provincia'],
			"departamento"=>$dato['departamento'],
			"distrito"=>$dato['distrito'],
			"codigoPais"=>$dato['codigoPais'],
			"telefono"=>$dato['telefono'],
			"correoElectronico"=>$dato['correoElectronico'],
			"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
		),
		"REC"=>array("tipoDocId"=>$dato['tipoDocIdc'],
			"numeroDocId"=>$dato['numeroDocIdc'],
			"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
			"direccion"=>mb_convert_encoding($dato['direccionc'],"UTF-8", "iso-8859-1"),
			"codigoPais"=>$dato['codigoPaisc']
		),
		/*if($dato['tipoDocRelacionado']!=''){"DRF"=>array(array("tipoDocRelacionado"=>$dato['tipoDocRelacionado'],
			"numeroDocRelacionado"=>$dato['numeroDocRelacionado'])	),
			}else{}
		*/
			"CAB"=>array(
				"gravadas"=>array(
					"codigo"=>$dato['codigoc'],
					"totalVentas"=>$dato['totalVentasc']
				),
				"totalImpuestos"=>array(array(
					"idImpuesto"=>$dato['idImpuestoc'],
					"montoImpuesto"=>$dato['montoImpuestoc']
				)),
				"importeTotal"=>$dato['importeTotalc'],
				"tipoOperacion"=>$dato['tipoOperacionc'],
				"leyenda"=>array(array("codigo"=>$dato['codigolc'],
					"descripcion"=>mb_convert_encoding($dato['descripcionlc'], "UTF-8", "iso-8859-1"))
			),
				"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
			),
			"DET"=>$detallepcdfe,	

			"ADI"=>array(
				array( "tituloAdicional"=>$dato['tituloAdicional1'], 
					"valorAdicional"=>mb_convert_encoding($dato['valorAdicional1'],"UTF-8", "iso-8859-1")),
				array( "tituloAdicional"=>$dato['tituloAdicional2'], 
					"valorAdicional"=>mb_convert_encoding($dato['valorAdicional2'],"UTF-8", "iso-8859-1")),
				array("tituloAdicional"=> $dato['tituloAdicional3'], 
					"valorAdicional"=> $dato['valorAdicional3']),
				array("tituloAdicional"=> $dato['tituloAdicional4'], 
					"valorAdicional"=> $dato['valorAdicional4']),
				array("tituloAdicional"=> $dato['tituloAdicional5'], 
					"valorAdicional"=> $dato['valorAdicional5']),
				array("tituloAdicional"=> $dato['tituloAdicional6'], 
					"valorAdicional"=> $dato['valorAdicional6']),
				array("tituloAdicional"=> $dato['tituloAdicionaln'], 
					"valorAdicional"=> $dato['valorAdicionaln'])
			)
		)
];
header('Content-type: text/x-json; UTF-8');
return $json->encode($documentofe03,true);
}

function armarjsonfe07($dato){
	$detallepcdfe=array();
	$datadet=$dato['detallepcd'];
	foreach ($datadet as $cad ) {
		$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
			"codigoProducto"=>$cad['codigoProducto'],
			"descripcionProducto"=>$cad['descripcionProducto'],
			"cantidadItems"=>$cad['cantidadItems'],
			"unidad"=>$cad['unidad'],
			"valorUnitario"=>$cad['valorUnitario'],
			"precioVentaUnitario"=>$cad['precioVentaUnitario'],
			"totalImpuestos"=>array(
				array(
					"idImpuesto"=>$cad['idImpuesto'],
					"montoImpuesto"=>$cad['montoImpuesto'],
					"tipoAfectacion"=>$cad['tipoAfectacion'],
					"montoBase"=>$cad['montoBase'],
					"porcentaje"=>$cad['porcentaje'])),
			"valorVenta"=>$cad['valorVenta'],
			"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
	}
	$json=new Services_JSON();
	$documentofe07 = ['notaCredito'=>array(
		"IDE"=>array(
			"numeracion"=>$dato['numeracion'],
			"fechaEmision"=>$dato['fechaEmision'],
			"horaEmision"=>$dato['horaEmision'],
			"tipoMoneda"=>$dato['tipoMoneda']
		),
		"EMI"=>array(
			"tipoDocId"=>$dato['tipoDocId'],
			"numeroDocId"=>$dato['numeroDocId'],
			"nombreComercial"=>$dato['nombreComercial'],
			"razonSocial"=>$dato['razonSocial'],
			"ubigeo"=>$dato['ubigeo'],
			"direccion"=>$dato['direccion'],
			"urbanizacion"=>$dato['urbanizacion'],
			"provincia"=>$dato['provincia'],
			"departamento"=>$dato['departamento'],
			"distrito"=>$dato['distrito'],
			"codigoPais"=>$dato['codigoPais'],
			"telefono"=>$dato['telefono'],
			"correoElectronico"=>$dato['correoElectronico'],
			"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
		),
		"REC"=>array(
			"tipoDocId"=>$dato['tipoDocIdc'],
			"numeroDocId"=>$dato['numeroDocIdc'],
			"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
			"direccion"=>mb_convert_encoding($dato['direccionc'], "UTF-8", "iso-8859-1"),
			"departamento"=>mb_convert_encoding($dato['departamentoc'], "UTF-8", "iso-8859-1"),
			"provincia"=>mb_convert_encoding($dato['provinciac'], "UTF-8", "iso-8859-1"),
			"distrito"=>mb_convert_encoding($dato['distritoc'], "UTF-8", "iso-8859-1"),
			"codigoPais"=>$dato['codigoPaisc'],
			"telefono"=>$dato['telefonoc'],
			"correoElectronico"=>mb_convert_encoding($dato['correoElectronicoc'], "UTF-8", "iso-8859-1")
		),
		"DRF"=>array(array("tipoDocRelacionado"=> $dato['tipoDocRelacionadonc'],
			"numeroDocRelacionado"=>$dato['numeroDocRelacionadonc'] ,
			"codigoMotivo"=>mb_convert_encoding($dato['codigoMotivonc'],"UTF-8", "iso-8859-1"),
			"descripcionMotivo"=>mb_convert_encoding($dato['descripcionMotivonc'],"UTF-8", "iso-8859-1")
		),
			/*array("tipoRelacionado"=>$dato['tipoRelacionado'],
			"numeroDocRelacionado"=>$dato['numeroDocRelacionado'])*/
		),
		"CAB"=>array("gravadas"=>array("codigo"=>$dato['codigoc'],
			"totalVentas"=>$dato['totalVentasc']
		),
		"totalImpuestos"=>array(array("idImpuesto"=>$dato['idImpuestoc'],
			"montoImpuesto"=>$dato['montoImpuestoc']
		)
	),
		"importeTotal"=>$dato['importeTotalc'],
		"leyenda"=>array(array("codigo"=>$dato['codigolc'],
			"descripcion"=>$dato['descripcionlc'])
	),

		"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
	),
		"DET"=>$detallepcdfe,	

		"ADI"=>array(array( "tituloAdicional"=>$dato['tituloAdicional1'], 
			"valorAdicional"=> $dato['valorAdicional1'])
	)
	)
];
header('Content-type: text/x-json; UTF-8');
return $json->encode($documentofe07,true);

}
function armarjsonfe08($dato){
	$detallepcdfe=array();
	$datadet=$dato['detallepcd'];
	foreach ($datadet as $cad ) {
		$detallepcdfe[]=array("numeroItem"=>$cad['numeroItem'],
			"codigoProducto"=>$cad['codigoProducto'],
			"descripcionProducto"=>$cad['descripcionProducto'],
			"cantidadItems"=>$cad['cantidadItems'],
			"unidad"=>$cad['unidad'],
			"valorUnitario"=>$cad['valorUnitario'],
			"precioVentaUnitario"=>$cad['precioVentaUnitario'],
			"totalImpuestos"=>array(
				array(
					"idImpuesto"=>$cad['idImpuesto'],
					"montoImpuesto"=>$cad['montoImpuesto'],
					"tipoAfectacion"=>$cad['tipoAfectacion'],
					"montoBase"=>$cad['montoBase'],
					"porcentaje"=>$cad['porcentaje'])),
			"valorVenta"=>$cad['valorVenta'],
			"montoTotalImpuestos"=>$cad['montoTotalImpuestos']);
	}
	$json=new Services_JSON();
	$documentofe08 = ['notaDebito'=>array(
		"IDE"=>array("numeracion"=>$dato['numeracion'],
			"fechaEmision"=>$dato['fechaEmision'],
			"horaEmision"=>$dato['horaEmision'],
			"tipoMoneda"=>$dato['tipoMoneda']
		),
		"EMI"=>array("tipoDocId"=>$dato['tipoDocId'],
			"numeroDocId"=>$dato['numeroDocId'],
			"nombreComercial"=>$dato['nombreComercial'],
			"razonSocial"=>$dato['razonSocial'],
			"ubigeo"=>$dato['ubigeo'],
			"direccion"=>$dato['direccion'],
			"urbanizacion"=>$dato['urbanizacion'],
			"provincia"=>$dato['provincia'],
			"departamento"=>$dato['departamento'],
			"distrito"=>$dato['distrito'],
			"codigoPais"=>$dato['codigoPais'],
			"telefono"=>$dato['telefono'],
			"correoElectronico"=>$dato['correoElectronico'],
			"codigoAsigSUNAT"=>$dato['codigoAsigSUNAT']
		),
		"REC"=>array(
			"tipoDocId"=>$dato['tipoDocIdc'],
			"numeroDocId"=>$dato['numeroDocIdc'],
			"razonSocial"=>mb_convert_encoding($dato['razonSocialc'], "UTF-8", "iso-8859-1"),
			"direccion"=>mb_convert_encoding($dato['direccionc'], "UTF-8", "iso-8859-1"),				
			"codigoPais"=>$dato['codigoPaisc']
		),
		"DRF"=>array(array("tipoDocRelacionado"=> $dato['tipoDocRelacionadonc'],
			"numeroDocRelacionado"=>$dato['numeroDocRelacionadonc'] ,
			"codigoMotivo"=>mb_convert_encoding($dato['codigoMotivonc'],"UTF-8", "iso-8859-1"),
			"descripcionMotivo"=>mb_convert_encoding($dato['descripcionMotivonc'],"UTF-8", "iso-8859-1")
		)
	),
		"CAB"=>array(
			"gravadas"=>array(
				"codigo"=>$dato['codigoc'],
				"totalVentas"=>$dato['totalVentasc']
			),
			"totalImpuestos"=>array(array(
				"idImpuesto"=>$dato['idImpuestoc'],
				"montoImpuesto"=>$dato['montoImpuestoc']
			)),
			"importeTotal"=>$dato['importeTotalc'],
			"leyenda"=>array(
				array("codigo"=>$dato['codigolc'],
					"descripcion"=>$dato['descripcionlc'])
			),

			"montoTotalImpuestos"=>$dato['montoTotalImpuestos']
		),
		"DET"=>$detallepcdfe,	

		"ADI"=>array(
			array( "tituloAdicional"=>$dato['tituloAdicional1'], 
				"valorAdicional"=> $dato['valorAdicional1'])			
		)
	)
];
header('Content-type: text/x-json; UTF-8');
return $json->encode($documentofe08,true);
}

function mostrar_pdf($data){

$dataget = explode("_", $data);
	$codcia=$dataget[0];
	$datos['codcia']=$dataget[0];
	$datos['fecha'] = $dataget[1];
	$datos['tipdoc'] = $dataget[2];
	$datos['nropdc'] = $dataget[3];
	$datos['serie'] = $dataget[4];
	$datos['correl'] = $dataget[5];		
	$datos['titles']=$dataget[6];

	$rslstcia = $this->facturacionsendws_db2_model->listarCia($codcia);
	if($rslstcia!=false){
		$EUCODCIA = trim(odbc_result($rslstcia, 1));
		$EUDSCCOM = trim(odbc_result($rslstcia, 2));
		$EUDSCDES = trim(odbc_result($rslstcia, 3));
		$mymclieas400 = trim(odbc_result($rslstcia, 4));
	}
	$rsmymcli=$this->facturacionsendws_db2_model->buscar_cliente_uni_db2as400($mymclieas400);   
	if($rsmymcli!=false){
		$AKCODCLIM = trim(odbc_result($rsmymcli, 1));
		$AKRAZSOCM = trim(utf8_encode(odbc_result($rsmymcli, 2))); 
		$IFNVORUCM = trim(odbc_result($rsmymcli, 3));
		$AKTIPIDEM = trim(odbc_result($rsmymcli, 4)); 
		$AKNROIDEM = trim(odbc_result($rsmymcli, 5)); 
		$NUMIDENM=($AKTIPIDEM=='02')?$IFNVORUCM:$AKNROIDEM;
		$AKIMPLMTM= trim(odbc_result($rsmymcli, 6)); 
	}
	$dato=$this->generadatafe($datos);	
	$RUCEMPRESA=$NUMIDENM;
	$nomTipoDOcu=$dato['codTipoDocumento'];
	switch ($nomTipoDOcu) {
		case '01':
		$nomTipoDOcu='FACTURA ELECTRONICA';
		break;
		case '03':
		$nomTipoDOcu='BOLETA ELECTRONICA';
		break;
		case '07':
		$nomTipoDOcu='NOTA DE CREDITO ELECTRONICA';
		break;
		case '08':
		$nomTipoDOcu='NOTA DE DEBITO ELECTRONICA';
		break;
		default:
		$nomTipoDOcu='NO definido';
		break;
	}

	$fpdf=new fpdf_lib('P','mm','A4',true,'UTF-8',false);
	$fpdf->setMargins(15,15,15);
	$fpdf->AliasNbPages();
	$fpdf->AddPage('P');
	$fpdf->image('assest/imagen/'.$codcia.'.png',38,15,44,0,'png');
	$fpdf->SetFont('Arial','B',10);
	$fpdf->Cell(123,2,'','',0,'C',0);
	$fpdf->Cell(60,2,'','TRL',0,'C',0);
	$fpdf->ln(2);
	$fpdf->Cell(123,6,'','',0,'',0);
	$fpdf->Cell(60,6,utf8_decode('RUC:'.$RUCEMPRESA),'RL',0,'C',0);
	$fpdf->ln(6);
	$fpdf->Cell(123,6,'','',0,'',0);
	$fpdf->Cell(60,6,utf8_decode($nomTipoDOcu),'RL',0,'C',0);
	$fpdf->ln(6);
	$fpdf->Cell(123,6,'','',0,'',0);
	$fpdf->Cell(60,6,utf8_decode('Nro:'.$dato['numeracion']),'RL',0,'C',0);
	$fpdf->ln(6);
	$fpdf->Cell(123,4,'','',0,'C',0);
	$fpdf->Cell(60,4,'','T',0,'C',0);		
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','B',10);
	$fpdf->Cell(90,4,$AKRAZSOCM,'',0,'C',0);
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->ln(3);
	$fpdf->SetFont('Arial','',8);
		//$fpdf->Cell(90,4,'Av. Nicolas Arriola 1723 Urb. Fortis La Victoria - Lima - Lima','',0,'C',0);
	$fpdf->Cell(90,4,utf8_decode($dato['direccion']),'',0,'C',0);		
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->ln(3);
	$fpdf->Cell(90,4,'Telf:(51)1 613-1500 ','',0,'C',0);
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->ln(3);
	$fpdf->Cell(90,4,'','',0,'C',0);//Suc y Direccion
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->SetFont('Arial','B',8);
	$fpdf->ln(10);	

	$fpdf->Cell(20,5,utf8_decode('Señor(es):'),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(114,5,utf8_decode($dato['razonSocialc']),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','B',8);	
	$TIPIDENTI=($dato['tipoDocIdc']=='1')?'DNI':'RUC';
	$fpdf->Cell(20,5,utf8_decode($TIPIDENTI.' :'),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(30,5,utf8_decode($dato['numeroDocIdc']),'TBRL',0,'L',0);
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','B',8);
	$fpdf->Cell(20,5,utf8_decode('Direccion:'),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(114,5,utf8_decode($dato['direccionc'].' '.$dato['distritoc'].' '.$dato['provinciac'].' '.$dato['departamentoc']),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','B',8);
	$fpdf->Cell(20,5,utf8_decode('Moneda:'),'TBRL',0,'L',0);
	$moneda=$dato['tipoMoneda'];
	switch ($moneda) {
		case 'USD':
		$moneda='Dolares';
		break;
		case 'PEN':
		$moneda='Soles';
		break;
		default:
		$moneda='-';
		break;
	}
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(30,5,utf8_decode($moneda),'TBRL',0,'L',0);
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','B',7);
	$fpdf->Cell(34,5,utf8_decode('Fecha de Emision'),'TBRL',0,'C',0);
	if(($dato['codTipoDocumento']=='01')||($dato['codTipoDocumento']=='03')){
		$fpdf->Cell(38,5,utf8_decode('Fecha Vencimiento'),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode('Numero O/Compra'),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode('Numero Guia'),'TBRL',0,'C',0);
		$fpdf->Cell(38,5,utf8_decode('Placa'),'TBRL',0,'C',0);
	}else{
		$fpdf->Cell(51,5,utf8_decode('CPE que Modifica'),'TBRL',0,'C',0);
		$fpdf->Cell(48,5,utf8_decode('Motivo'),'TBRL',0,'C',0);
		$fpdf->Cell(51,5,utf8_decode('Descripcion'),'TBRL',0,'C',0);
	}
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','',6);
	$fpdf->Cell(34,5,utf8_decode($dato['fechaEmision']),'TBRL',0,'C',0);
	if(($dato['codTipoDocumento']=='01')||($dato['codTipoDocumento']=='03')){
		$fpdf->Cell(38,5,utf8_decode($dato['fechaVencimiento']),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode($dato['numeroOrdenCompra']),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode($dato['numeroDocRelacionado']),'TBRL',0,'C',0);
		$fpdf->Cell(38,5,utf8_decode($dato['placavh']),'TBRL',0,'C',0);
	}else{
		$fpdf->Cell(51,5,utf8_decode($dato['numeroDocRelacionadonc']),'TBRL',0,'C',0);
		$fpdf->Cell(48,5,utf8_decode($dato['descripcionMotivonc']),'TBRL',0,'C',0);
		$fpdf->Cell(51,5,utf8_decode($dato['descripcionMotivonc']),'TBRL',0,'C',0);
	}
	$fpdf->SetFont('Arial','B',7);
	$fpdf->ln(7);
	$fpdf->Cell(34,5,utf8_decode('Numero Interno'),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode('Numero Pedido'),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode('Numero Cliente'),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode('Numero Vendedor'),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode('Condicion Pago'),'TBRL',0,'C',0);
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(34,5,utf8_decode($dato['valorAdicional5']),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode(''),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode($dato['valorAdicional3']),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode($dato['valorAdicional4']),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode($dato['valorAdicional7']),'TBRL',0,'C',0);
	$fpdf->ln(7);
	$fpdf->SetFont('Arial','B',7);
	$fpdf->Cell(6,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(25,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(61,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(14,5,utf8_decode('Valor'),'TRL',0,'C',0);
	$fpdf->Cell(15,5,utf8_decode('Valor Venta'),'TRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(11,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode('Precio Venta '),'TRL',0,'C',0);
	$fpdf->ln(2);
	$fpdf->Cell(6,5,utf8_decode('Item'),'BRL',0,'C',0);
	$fpdf->Cell(25,5,utf8_decode('Codigo'),'BRL',0,'C',0);
	$fpdf->Cell(61,5,utf8_decode('Descripcion'),'BRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode('Cant.'),'BRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode('UM'),'BRL',0,'C',0);
	$fpdf->Cell(14,5,utf8_decode('Unitario'),'BRL',0,'C',0);
	$fpdf->Cell(15,5,utf8_decode('Total'),'BRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode('Descuento'),'BRL',0,'C',0);
	$fpdf->Cell(11,5,utf8_decode('I.G.V'),'BRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode('Total'),'BRL',0,'C',0);
	$fpdf->SetFont('Arial','',5);
	$fpdf->ln(5);
	
	$datadet=$dato['datadet'];
	$codi='';
	$nroitel=25;
	$numite=0;
	foreach ($datadet as $cad ) {
		$c_height=3;
		$chardata=strlen($cad['SDTDSCAR']);
		
		if($chardata>55){			
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(6,$c_height,$x_axis,utf8_decode($cad['NROITEMS']));
			$x_axis=$fpdf->getx();
			$fpdf->vcell(25,$c_height,$x_axis,utf8_decode($cad['SDTCDART']));  
			$x_axis=$fpdf->getx();
			$fpdf->vcell(61,$c_height,$x_axis,utf8_decode($cad['SDTDSCAR'])); 
			$x_axis=$fpdf->getx();
			$fpdf->vcell(10,$c_height,$x_axis,utf8_decode($cad['SDTCANTI'])); 
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(10,$c_height,$x_axis,utf8_decode($cad['SDTUNIME'])); 
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(14,$c_height,$x_axis,utf8_decode($cad['SDTPUSIG']));
			$x_axis=$fpdf->getx();
			$fpdf->vcell(15,$c_height,$x_axis,utf8_decode($cad['SDTPUCIG'])); 
			$x_axis=$fpdf->getx();
			$fpdf->vcell(16,$c_height,$x_axis,''); 
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(11,$c_height,$x_axis,utf8_decode($cad['SDTPIIGV']));
			$x_axis=$fpdf->getx();
			$fpdf->vcell(16,$c_height,$x_axis,utf8_decode($cad['SDTPSITE'])); 

		}else{
			$fpdf->Cell(6,3,utf8_decode($cad['NROITEMS']),'RL',0,'L',0);
			$fpdf->Cell(25,3,utf8_decode($cad['SDTCDART'].' '.$chardata),'RL',0,'L',0);
			$fpdf->Cell(61,3,utf8_decode($cad['SDTDSCAR']),'RL',0,'L',0);
			$fpdf->Cell(10,3,utf8_decode($cad['SDTCANTI']),'RL',0,'R',0);
			$fpdf->Cell(10,3,utf8_decode($cad['SDTUNIME']),'RL',0,'C',0);
			$fpdf->Cell(14,3,utf8_decode($cad['SDTPUSIG']),'RL',0,'R',0);
			$fpdf->Cell(15,3,utf8_decode($cad['SDTPUCIG']),'RL',0,'R',0);
			$fpdf->Cell(16,3,utf8_decode(''),'RL',0,'R',0);
			$fpdf->Cell(11,3,utf8_decode($cad['SDTPIIGV']),'RL',0,'R',0);
			$valor=$cad['SDTPSITE']+$cad['SDTPUCIG'];
			$fpdf->Cell(16,3,utf8_decode($cad['SDTPSITE']),'RL',0,'R',0);
		}
		$fpdf->ln(3);
		$numite++;
	}
	if($numite==$nroitel||$numite>$nroitel){

	}else{
		$rest=$nroitel-$numite;
		for($i=0;$i<=$rest;$i++){
			$fpdf->Cell(6,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(25,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(61,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(10,3,utf8_decode(''),'RL',0,'R',0);
			$fpdf->Cell(10,3,utf8_decode(''),'RL',0,'C',0);
			$fpdf->Cell(14,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(15,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(16,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(11,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(16,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->ln(3);
		}
	}      	
	$importeTotalc=0.00;
	$fpdf->Cell(184,1,'','T',0,'L',0);
	$fpdf->ln(2);
	if($dato['totalAnticipos']>0.00){
		$fpdf->SetFont('Arial','B',7);
		$fpdf->Cell(184,5,utf8_decode('Documento Anticipo'),'TBRL',0,'C',0);
		$fpdf->ln(5);
		$fpdf->Cell(34,5,utf8_decode('Item'),'TBRL',0,'C',0);
		$fpdf->Cell(38,5,utf8_decode('Documento'),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode('Fecha Emision'),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode('Moneda'),'TBRL',0,'C',0);
		$fpdf->Cell(38,5,utf8_decode('Total'),'TBRL',0,'C',0);
		$fpdf->ln(5);
		$fpdf->SetFont('Arial','',7);
		$infoAnticipo=$dato['infoAnticipo'];
		foreach ($infoAnticipo as $cada) {
			$fpdf->Cell(34,5,utf8_decode($cada['identificador']),'TBRL',0,'C',0);
			$fpdf->Cell(38,5,utf8_decode($cada['serieNumero']),'TBRL',0,'C',0);
			$fpdf->Cell(37,5,utf8_decode($cada['fechaAnticipo']),'TBRL',0,'C',0);
			$fpdf->Cell(37,5,utf8_decode($cada['tipoMoneda']),'TBRL',0,'C',0);
			$fpdf->Cell(38,5,utf8_decode($cada['monto']),'TBRL',0,'C',0);
			$fpdf->ln(5);
		}
		$fpdf->SetFont('Arial','B',7);
		$fpdf->Cell(184,5,utf8_decode('Descuento Total Anticipo: '.number_format($dato['totalAnticipos'],2,".",",")),'TBRL',0,'R',0);
		$fpdf->ln(7);
		$fpdf->SetFont('Arial','B',7);	
		$fpdf->Cell(22,5,utf8_decode('Descuentos'),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Cargos'),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(21,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode(''),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Descuento'),'TRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode('Importe'),'TRL',0,'C',0);
		$fpdf->ln(3);
		$fpdf->Cell(22,5,utf8_decode('Globales'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Globales'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Exoneradas'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Gratuitas'),'BRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode('Gravadas'),'BRL',0,'C',0);
		$fpdf->Cell(21,5,utf8_decode('Inafectas'),'BRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode('I.G.V'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Total Anticipo'),'BRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode('Total'),'BRL',0,'C',0);
		$fpdf->ln(5);
		$fpdf->SetFont('Arial','',7);
		$fpdf->Cell(22,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode(number_format($dato['totalVentasc'],2,".",",")),'TBRL',0,'C',0);
		$fpdf->Cell(21,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode(number_format($dato['montoTotalImpuestos'],2,".",",")),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(number_format($dato['totalAnticipos'],2,".",",")),'TBRL',0,'C',0);
		$fpdf->Cell(18,5,utf8_decode(number_format($dato['importeTotalc'],2,".",",")),'TBRL',0,'C',0);
		$fpdf->ln(9);
	}else{		
		$fpdf->SetFont('Arial','B',7);	
		$fpdf->Cell(24,5,utf8_decode('Descuentos'),'TRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode('Cargos'),'TRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(''),'TRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Importe'),'TRL',0,'C',0);
		$fpdf->ln(3);
		$fpdf->Cell(24,5,utf8_decode('Globales'),'BRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode('Globales'),'BRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode('Exoneradas'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Gratuitas'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Gravadas'),'BRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode('Inafectas'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('I.G.V'),'BRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode('Total'),'BRL',0,'C',0);
		$fpdf->ln(5);
		$fpdf->SetFont('Arial','',7);
		$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(number_format($dato['totalVentasc'],2,".",",")),'TBRL',0,'C',0);
		$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(number_format($dato['montoTotalImpuestos'],2,".",",")),'TBRL',0,'C',0);
		$fpdf->Cell(22,5,utf8_decode(number_format($dato['importeTotalc'],2,".",",")),'TBRL',0,'C',0);
		$fpdf->ln(9);
	}
	$fpdf->SetFont('Arial','B',7);
	$fpdf->Cell(10,4,utf8_decode('SON:'),'',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(174,4,utf8_decode($dato['descripcionlc']),'',0,'L',0);
	$fpdf->ln(4);
	$fpdf->Cell(30,4,utf8_decode('T.C. Referencia del dia :'),'',0,'L',0);
	$fpdf->Cell(154,4,utf8_decode($dato['valorAdicional1']),'',0,'L',0);
	$fpdf->ln(4);
	$fpdf->Cell(30,4,utf8_decode('Mensaje de Retencion :'),'',0,'L',0);
	$fpdf->Cell(154,4,utf8_decode($dato['valorAdicional2']),'',0,'L',0);	
	$fpdf->ln(5);
	if(($dato['codTipoDocumento']=='01')||($dato['codTipoDocumento']=='03')||($dato['codTipoDocumento']=='07')){
		$fpdf->SetFont('Arial','B',4);
		$fpdf->Cell(184,3,utf8_decode('*EN CASO DE NO SER PAGADO A SU VENCIMIENTO ESTE DOCUMENTO GENERARÁ INTERES COMPENSATORIOS Y MORATORIOS A LAS TASAS MAXIMAS QUE FIJE LA LEY.'),'',0,'L',0);	
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('*EL PAGO DE ESTE DOCUMENTO PUEDE SER EFECTUADO DEPOSITANDO EL IMPORTE EN NUESTRAS CTAS. CTES. O EN CASO DE PAGAR CON CHEQUE GIRARLO '),'',0,'L',0);
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('  UNICAMENTE A LA ORDEN DE M&M REPUESTOS Y SERVICIOS S.A.'),'',0,'L',0);
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('*LA MERCADERIA VIAJA A CUENTA Y RIESGO DEL COMPRADOR.'),'',0,'L',0);
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('*UNA VEZ SALIDA LA MERCADERIA NO SE ACEPTANCAMBIOS NI DEVOLUCIONES, PENALIDAD DEL 10%.'),'',0,'L',0);
	}else{

	}
	$fpdf->ln(3);
	$fpdf->Cell(169,3,utf8_decode(''),'',0,'L',0);
	$imagenqr=$dato['numeroDocId'].'_'.$dato['codTipoDocumento'].'_'.'null'.'_'.$dato['importeTotalc'].'_'.$dato['fechaEmision'].'_'.'otrosDatos';
	$filename=$dato['numeroDocId'].'_'.$dato['codTipoDocumento'].'_'.$dato['numeracion'];
	$imagen=$this->generaqr($imagenqr,$filename);
	$fpdf->Cell(15,15, $fpdf->Image($imagen, $fpdf->GetX(), $fpdf->GetY(),15,15),'',0,'R',0);
	$fpdf->ln(7);
	$numautorizaSUNAT='0340050010017/SUNAT';
	$webconsulta='https://escondatagate.page.link/fwSnj';
	$fechaAutorizacion=gmdate("d/m/Y  h:m:s", time() - 18000);
	$fpdf->SetFont('Arial','',5);
	$fpdf->Cell(164,3,utf8_decode('Fecha y hora Autorizacion:'.$fechaAutorizacion),'',0,'R',0);
	$fpdf->ln(3);
	$fpdf->Cell(184,3,utf8_decode('Autorizado mediante resolucion Nro:'.$numautorizaSUNAT.' Para consultar el comprobante ingrese a '.$webconsulta.' Representacion impresora del comprobante electronico'),'',0,'L',0);
	header('Content-type: application/pdf');
	header('Cache-Control: private');
	header('Pragma: private');
	$fpdf->Output($filename.'.pdf','I');
	unlink($imagen);

}

function fesendprint($dato){
	$codcia=$data['SCTECIAA']; 
	$rslstcia = $this->facturacionsendws_db2_model->listarCia($codcia);
	if($rslstcia!=false){
		$EUCODCIA = trim(odbc_result($rslstcia, 1));
		$EUDSCCOM = trim(odbc_result($rslstcia, 2));
		$EUDSCDES = trim(odbc_result($rslstcia, 3));
		$mymclieas400 = trim(odbc_result($rslstcia, 4));
	}
	$rsmymcli=$this->facturacionsendws_db2_model->buscar_cliente_uni_db2as400($mymclieas400);   
	if($rsmymcli!=false){
		$AKCODCLIM = trim(odbc_result($rsmymcli, 1));
		$AKRAZSOCM = trim(utf8_encode(odbc_result($rsmymcli, 2))); 
		$IFNVORUCM = trim(odbc_result($rsmymcli, 3));
		$AKTIPIDEM = trim(odbc_result($rsmymcli, 4)); 
		$AKNROIDEM = trim(odbc_result($rsmymcli, 5)); 
		$NUMIDENM=($AKTIPIDEM=='02')?$IFNVORUCM:$AKNROIDEM;
		$AKIMPLMTM= trim(odbc_result($rsmymcli, 6)); 
	}

	$RUCEMPRESA=$NUMIDENM;
	$cia=$codcia; 
	$nomTipoDOcu=$dato['codTipoDocumento'];
	switch ($nomTipoDOcu) {
		case '01':
		$nomTipoDOcu='FACTURA ELECTRONICA';
		break;
		case '03':
		$nomTipoDOcu='BOLETA ELECTRONICA';
		break;
		case '07':
		$nomTipoDOcu='NOTA DE CREDITO ELECTRONICA';
		break;
		case '08':
		$nomTipoDOcu='NOTA DE DEBITO ELECTRONICA';
		break;
		default:
		$nomTipoDOcu='NO definido';
		break;
	}
	$fpdf=new fpdf_lib('P','mm','A4',true,'UTF-8',false);
	$fpdf->setMargins(15,15,15);
	$fpdf->AliasNbPages();
	$fpdf->AddPage('P');
	$fpdf->image('assest/imagen/'.$cia.'.png',38,15,44,0,'png');
	$fpdf->SetFont('Arial','B',10);
	$fpdf->Cell(123,2,'','',0,'C',0);
	$fpdf->Cell(60,2,'','TRL',0,'C',0);
	$fpdf->ln(2);
	$fpdf->Cell(123,6,'','',0,'',0);
	$fpdf->Cell(60,6,utf8_decode('RUC:'.$RUCEMPRESA),'RL',0,'C',0);
	$fpdf->ln(6);
	$fpdf->Cell(123,6,'','',0,'',0);
	$fpdf->Cell(60,6,utf8_decode($nomTipoDOcu),'RL',0,'C',0);
	$fpdf->ln(6);
	$fpdf->Cell(123,6,'','',0,'',0);
	$fpdf->Cell(60,6,utf8_decode('Nro:'.$dato['numeracion']),'RL',0,'C',0);
	$fpdf->ln(6);
	$fpdf->Cell(123,4,'','',0,'C',0);
	$fpdf->Cell(60,4,'','T',0,'C',0);		
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','B',10);
	$fpdf->Cell(90,4,'MYM REPUESTOS Y SERVICIOS S.A.','',0,'C',0);
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->ln(3);
	$fpdf->SetFont('Arial','',8);
	//$fpdf->Cell(90,4,'Av. Nicolas Arriola 1723 Urb. Fortis La Victoria - Lima - Lima','',0,'C',0);
	$fpdf->Cell(90,4,utf8_decode($dato['direccion']),'',0,'C',0);
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->ln(3);
	$fpdf->Cell(90,4,'Telf:(51)1 613-1500 ','',0,'C',0);
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->ln(3);
	$fpdf->Cell(90,4,'','',0,'C',0);//Suc y Direccion
	$fpdf->Cell(105,4,'','',0,'C',0);
	$fpdf->SetFont('Arial','B',8);
	$fpdf->ln(10);
	$fpdf->Cell(20,5,utf8_decode('Señor(es):'),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(114,5,utf8_decode($dato['razonSocialc']),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','B',8);	
	$TIPIDENTI=($dato['tipoDocIdc']=='1')?'DNI':'RUC';
	$fpdf->Cell(20,5,utf8_decode($TIPIDENTI.' :'),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(30,5,utf8_decode($dato['numeroDocIdc']),'TBRL',0,'L',0);
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','B',8);
	$fpdf->Cell(20,5,utf8_decode('Direccion:'),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(114,5,utf8_decode($dato['direccionc'].' '.$dato['distritoc'].' '.$dato['provinciac'].' '.$dato['departamentoc']),'TBRL',0,'L',0);
	$fpdf->SetFont('Arial','B',8);
	$fpdf->Cell(20,5,utf8_decode('Moneda:'),'TBRL',0,'L',0);
	$moneda=$dato['tipoMoneda'];
	switch ($moneda) {
		case 'USD':
		$moneda='Dolares';
		break;
		case 'PEN':
		$moneda='Soles';
		break;
		default:
		$moneda='-';
		break;
	}
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(30,5,utf8_decode($moneda),'TBRL',0,'L',0);
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','B',7);
	$fpdf->Cell(34,5,utf8_decode('Fecha de Emision'),'TBRL',0,'C',0);
	if(($dato['codTipoDocumento']=='01')||($dato['codTipoDocumento']=='03')){
		$fpdf->Cell(38,5,utf8_decode('Fecha Vencimiento'),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode('Numero O/Compra'),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode('Numero Guia'),'TBRL',0,'C',0);
		$fpdf->Cell(38,5,utf8_decode('Placa'),'TBRL',0,'C',0);
	}else{
		$fpdf->Cell(51,5,utf8_decode('CPE que Modifica'),'TBRL',0,'C',0);
		$fpdf->Cell(48,5,utf8_decode('Motivo'),'TBRL',0,'C',0);
		$fpdf->Cell(51,5,utf8_decode('Descripcion'),'TBRL',0,'C',0);
	}
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','',6);
	$fpdf->Cell(34,5,utf8_decode($dato['fechaEmision']),'TBRL',0,'C',0);
	if(($dato['codTipoDocumento']=='01')||($dato['codTipoDocumento']=='03')){
		$fpdf->Cell(38,5,utf8_decode($dato['fechaVencimiento']),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode($dato['numeroOrdenCompra']),'TBRL',0,'C',0);
		$fpdf->Cell(37,5,utf8_decode($dato['numeroDocRelacionado']),'TBRL',0,'C',0);
		$fpdf->Cell(38,5,utf8_decode($dato['placavh']),'TBRL',0,'C',0);
	}else{
		$fpdf->Cell(51,5,utf8_decode($dato['numeroDocRelacionadonc']),'TBRL',0,'C',0);
		$fpdf->Cell(48,5,utf8_decode($dato['descripcionMotivonc']),'TBRL',0,'C',0);
		$fpdf->Cell(51,5,utf8_decode($dato['descripcionMotivonc']),'TBRL',0,'C',0);
	}
	$fpdf->SetFont('Arial','B',7);
	$fpdf->ln(7);
	$fpdf->Cell(34,5,utf8_decode('Numero Interno'),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode('Numero Pedido'),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode('Numero Cliente'),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode('Numero Vendedor'),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode('Condicion Pago'),'TBRL',0,'C',0);
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(34,5,utf8_decode($dato['valorAdicional5']),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode(''),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode($dato['valorAdicional3']),'TBRL',0,'C',0);
	$fpdf->Cell(37,5,utf8_decode($dato['valorAdicional4']),'TBRL',0,'C',0);
	$fpdf->Cell(38,5,utf8_decode($dato['valorAdicional7']),'TBRL',0,'C',0);
	$fpdf->ln(7);
	$fpdf->SetFont('Arial','B',7);
	$fpdf->Cell(6,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(25,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(61,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(14,5,utf8_decode('Valor'),'TRL',0,'C',0);
	$fpdf->Cell(15,5,utf8_decode('Valor Venta'),'TRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(11,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode('Precio Venta '),'TRL',0,'C',0);
	$fpdf->ln(2);
	$fpdf->Cell(6,5,utf8_decode('Item'),'BRL',0,'C',0);
	$fpdf->Cell(25,5,utf8_decode('Codigo'),'BRL',0,'C',0);
	$fpdf->Cell(61,5,utf8_decode('Desciprcion'),'BRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode('Cant.'),'BRL',0,'C',0);
	$fpdf->Cell(10,5,utf8_decode('UM'),'BRL',0,'C',0);
	$fpdf->Cell(14,5,utf8_decode('Unitario'),'BRL',0,'C',0);
	$fpdf->Cell(15,5,utf8_decode('Total'),'BRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode('Descuento'),'BRL',0,'C',0);
	$fpdf->Cell(11,5,utf8_decode('I.G.V'),'BRL',0,'C',0);
	$fpdf->Cell(16,5,utf8_decode('Total'),'BRL',0,'C',0);
	$fpdf->SetFont('Arial','',5);
	$fpdf->ln(5);	
	$datadet=$dato['datadet'];
	$codi='';
	$nroitel=25;
	$numite=0;
	foreach ($datadet as $cad ) {
		$c_height=3;
		$chardata=strlen($cad['SDTDSCAR']);		
		if($chardata>55){			
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(6,$c_height,$x_axis,utf8_decode($cad['NROITEMS']));
			$x_axis=$fpdf->getx();
			$fpdf->vcell(25,$c_height,$x_axis,utf8_decode($cad['SDTCDART']));  
			$x_axis=$fpdf->getx();
			$fpdf->vcell(61,$c_height,$x_axis,utf8_decode($cad['SDTDSCAR'])); 
			$x_axis=$fpdf->getx();
			$fpdf->vcell(10,$c_height,$x_axis,utf8_decode($cad['SDTCANTI'])); 
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(10,$c_height,$x_axis,utf8_decode($cad['SDTUNIME'])); 
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(14,$c_height,$x_axis,utf8_decode($cad['SDTPUSIG']));
			$x_axis=$fpdf->getx();
			$fpdf->vcell(15,$c_height,$x_axis,utf8_decode($cad['SDTPUCIG'])); 
			$x_axis=$fpdf->getx();
			$fpdf->vcell(16,$c_height,$x_axis,''); 
			$x_axis=$fpdf->getx(); 
			$fpdf->vcell(11,$c_height,$x_axis,utf8_decode($cad['SDTPIIGV']));
			$x_axis=$fpdf->getx();
			$fpdf->vcell(16,$c_height,$x_axis,utf8_decode($cad['SDTPSITE']));
		}else{
			$fpdf->Cell(6,3,utf8_decode($cad['NROITEMS']),'RL',0,'L',0);
			$fpdf->Cell(25,3,utf8_decode($cad['SDTCDART'].' '.$chardata),'RL',0,'L',0);
			$fpdf->Cell(61,3,utf8_decode($cad['SDTDSCAR']),'RL',0,'L',0);
			$fpdf->Cell(10,3,utf8_decode($cad['SDTCANTI']),'RL',0,'R',0);
			$fpdf->Cell(10,3,utf8_decode($cad['SDTUNIME']),'RL',0,'C',0);
			$fpdf->Cell(14,3,utf8_decode($cad['SDTPUSIG']),'RL',0,'R',0);
			$fpdf->Cell(15,3,utf8_decode($cad['SDTPUCIG']),'RL',0,'R',0);
			$fpdf->Cell(16,3,utf8_decode(''),'RL',0,'R',0);
			$fpdf->Cell(11,3,utf8_decode($cad['SDTPIIGV']),'RL',0,'R',0);
			$fpdf->Cell(16,3,utf8_decode($cad['SDTPSITE']),'RL',0,'R',0);
		}
		$fpdf->ln(3);
		$numite++;
	}
	if($numite==$nroitel||$numite>$nroitel){}else{
		$rest=$nroitel-$numite;
		for($i=0;$i<=$rest;$i++){
			$fpdf->Cell(6,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(25,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(61,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(10,3,utf8_decode(''),'RL',0,'R',0);
			$fpdf->Cell(10,3,utf8_decode(''),'RL',0,'C',0);
			$fpdf->Cell(14,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(15,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(16,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(11,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->Cell(16,3,utf8_decode(''),'RL',0,'L',0);
			$fpdf->ln(3);
		}
	}      	

	$fpdf->Cell(184,1,'','T',0,'L',0);
	$fpdf->ln(2);
	$fpdf->SetFont('Arial','B',7);	
	$fpdf->Cell(24,5,utf8_decode('Descuentos'),'TRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode('Cargos'),'TRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode('Operaciones'),'TRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode(''),'TRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode('Importe'),'TRL',0,'C',0);
	$fpdf->ln(3);
	$fpdf->Cell(24,5,utf8_decode('Globales'),'BRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode('Globales'),'BRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode('Exoneradas'),'BRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode('Gratuitas'),'BRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode('Gravadas'),'BRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode('Inafectas'),'BRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode('I.G.V'),'BRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode('Total'),'BRL',0,'C',0);
	$fpdf->ln(5);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode(''),'TBRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode(number_format($dato['totalVentasc'],2,".",",")),'TBRL',0,'C',0);
	$fpdf->Cell(24,5,utf8_decode(''),'TBRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode(number_format($dato['montoTotalImpuestos'],2,".",",")),'TBRL',0,'C',0);
	$fpdf->Cell(22,5,utf8_decode(number_format($dato['importeTotalc'],2,".",",")),'TBRL',0,'C',0);
	$fpdf->ln(9);
	$fpdf->SetFont('Arial','B',7);
	$fpdf->Cell(10,4,utf8_decode('SON:'),'',0,'L',0);
	$fpdf->SetFont('Arial','',7);
	$fpdf->Cell(174,4,utf8_decode($dato['descripcionlc']),'',0,'L',0);
	$fpdf->ln(4);
	$fpdf->Cell(30,4,utf8_decode('T.C. Referencia del dia :'),'',0,'L',0);
	$fpdf->Cell(154,4,utf8_decode($dato['valorAdicional1']),'',0,'L',0);
	$fpdf->ln(4);
	$fpdf->Cell(30,4,utf8_decode('Mensaje de Retencion :'),'',0,'L',0);
	$fpdf->Cell(154,4,utf8_decode($dato['valorAdicional2']),'',0,'L',0);	
	$fpdf->ln(5);
	if(($dato['codTipoDocumento']=='01')||($dato['codTipoDocumento']=='03')||($dato['codTipoDocumento']=='07')){
		$fpdf->SetFont('Arial','B',4);
		$fpdf->Cell(184,3,utf8_decode('*EN CASO DE NO SER PAGADO A SU VENCIMIENTO ESTE DOCUMENTO GENERARÁ INTERES COMPENSATORIOS Y MORATORIOS A LAS TASAS MAXIMAS QUE FIJE LA LEY.'),'',0,'L',0);	
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('*EL PAGO DE ESTE DOCUMENTO PUEDE SER EFECTUADO DEPOSITANDO EL IMPORTE EN NUESTRAS CTAS. CTES. O EN CASO DE PAGAR CON CHEQUE GIRARLO '),'',0,'L',0);
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('  UNICAMENTE A LA ORDEN DE M&M REPUESTOS Y SERVICIOS S.A.'),'',0,'L',0);
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('*LA MERCADERIA VIAJA A CUENTA Y RIESGO DEL COMPRADOR.'),'',0,'L',0);
		$fpdf->ln(3);
		$fpdf->Cell(184,3,utf8_decode('*UNA VEZ SALIDA LA MERCADERIA NO SE ACEPTANCAMBIOS NI DEVOLUCIONES, PENALIDAD DEL 10%.'),'',0,'L',0);
	}else{

	}
	$fpdf->ln(3);
	$fpdf->Cell(169,3,utf8_decode(''),'',0,'L',0);
	$imagenqr=$dato['numeroDocId'].'_'.$dato['codTipoDocumento'].'_'.'null'.'_'.$dato['importeTotalc'].'_'.$dato['fechaEmision'].'_'.'otrosDatos';
	$filename=$dato['numeroDocId'].'_'.$dato['codTipoDocumento'].'_'.$dato['numeracion'];
	$imagen=$this->generaqr($imagenqr,$filename);
	$fpdf->Cell(15,15, $fpdf->Image($imagen, $fpdf->GetX(), $fpdf->GetY(),15,15),'',0,'R',0);
	$fpdf->ln(7);
	$numautorizaSUNAT='0340050010017/SUNAT';
	$webconsulta='https://escondatagate.page.link/fwSnj';
	$fechaAutorizacion=gmdate("d/m/Y  h:m:s", time() - 18000);
	$fpdf->SetFont('Arial','',5);
	$fpdf->Cell(164,3,utf8_decode('Fecha y hora Autorizacion:'.$fechaAutorizacion),'',0,'R',0);
	$fpdf->ln(3);
	$fpdf->Cell(184,3,utf8_decode('Autorizado mediante resolucion Nro:'.$numautorizaSUNAT.' Para consultar el comprobante ingrese a '.$webconsulta.' Representacion impresora del comprobante electronico'),'',0,'L',0);
	$fpdf->Output('assest/pdffe/'.$filename.'.pdf','F');
	$IMPRESORA='';
	$impresorauser='';
	$ffpri= gmdate("d-m-Y H:i:s ", time() - 18000);
	$SCTCUSUR=$dato['SCTCUSUR'];
	$rslprint=$this->facturacionsendws_db2_model->get_impresora_fe($SCTCUSUR);						
	$IMPRESORA = (($rslprint==false)||(trim(odbc_result($rslprint, 1))==''))?false:trim(odbc_result($rslprint, 1));
	$rptcmd='';
	if($IMPRESORA!=false){	

		$impresorauser=$IMPRESORA; 
		$filebat="assest/printer.bat";
		$filelogp="assest/log_printer.txt";
		$fileubi="C:\\wamp64\\www\\facturacionmym\\assest\\pdffe\\".$filename.".pdf";
		$archivo = fopen($filebat,"w+b");

		if( $archivo == false ) {

		}
		else
		{
		/*
		#@echo off
//C:\gs\gs9.27\bin\gswin64c.exe -dPrinted -dBATCH -dNOPAUSE -dNOSAFER -q -dNumCopies=1 -sDEVICE=ljet4 -sOutputFile="%printer%\\192.168.200.109\COMARCIALPISO3" "C:\wamp64\www\facturacionmym\assest\pdffe\20101759688_01_F077-00014449.pdf"
##-sDEVICE=mswinpr2_ENTANA_WINDWOS
*/		
		fwrite($archivo, "@echo off\r\n");
		fwrite($archivo, 'C:\gs\gs9.27\bin\gswin64c.exe -dPrinted -dBATCH -dNOPAUSE -dNOSAFER -q -dNumCopies=1 -sDEVICE=ljet4 -sOutputFile="%printer%'.$impresorauser.'" "'.$fileubi.'"');
		fflush($archivo);
	}
	fclose($archivo);

	$cmd="C:\\wamp64\\www\\facturacionmym\\assest\\printer.bat";
	$rptcmd=$this->execInBackground($cmd);
	/*
	copy($fileubi,$filenubi);
		copy($imagen,$filenubi);
	if($rptcmd==true){
		
		unlink($imagen);
		unlink($fileubi);
	}
	else{

	}*/

}
else
{
	$rptcmd=false;
}
$horad='23:54:59';
$hh= gmdate("H-i-s", time() - 18000);
if($horad==$hh){

	$filesfe="assest/pdffe/*";
	foreach($files as $file){
		if(is_file($file))
			unlink($file); 
	}
	$filesqr="assest/imagqr/*";
	foreach($filesqr as $file){
		if(is_file($file))
			unlink($file); 
	}
	$filesjs="assest/fejs/*";
	foreach($filesjs as $file){
		if(is_file($file))
			unlink($file); 
	}
}
else{

}
return $rptcmd;
}


function generaqr($imagenqr,$filenamepdf){
	$PNG_TEMP_DIR = 'assest/imagqr';
	$PNG_WEB_DIR = 'assest/imagqr';
	$image='';	
	require_once("application/libraries/qrcode/qrlib.php");
	if (!file_exists($PNG_TEMP_DIR))
		mkdir($PNG_TEMP_DIR);
	$filename = $PNG_TEMP_DIR;
	$matrixPointSize = 10;
	$errorCorrectionLevel = 'H';
	$filename = $PNG_TEMP_DIR.'/'.$filenamepdf.'.png';
	QRcode::png($imagenqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
	
	return $filename;
}


function execInBackground($cmd) { 
	$rptcmd=false;
	if (substr(php_uname(), 0, 7) == "Windows"){ 
		pclose(popen("start /B ". $cmd, "r"));  
		$rptcmd=true;
	} 
	else { 
		exec($cmd . " > /dev/null &");  
		$rptcmd=false; 
	} 
	return $rptcmd;

}

function armarjsonfe01a1(){

	$json=new Services_JSON();
	$armarjsonfe01_A=["factura"=>array(
		"IDE"=>array(
			"numeracion"=>"FA01-00001031",
			"fechaEmision"=>"2019-05-30",
			"codTipoDocumento"=>"01",
			"tipoMoneda"=>"PEN"
		),
		"EMI"=>array(
			"tipoDocId"=>"6",
			"numeroDocId"=>"20101759688",
			"razonSocial"=>"M&M REPUESTOS Y SERVICIOS S.A.",
			"direccion"=>"AV. Nicolas Arriola 1723",
			"codigoAsigSUNAT"=>"0000"
		),
		"REC"=>array(
			"tipoDocId"=>"6",
			"numeroDocId"=>"20381235051",
			"razonSocial"=>"GIFTCARD-ANTICIPO"
		),
		"CAB"=>array(
			"gravadas"=>array(
				"codigo"=> "1001",
				"totalVentas"=> "84.75"
			),
			"totalImpuestos"=>array(array(
				
				"idImpuesto"=> "1000",
				"montoImpuesto"=> "15.25"
				
			)),
			
			"importeTotal"=>"100.00",
			"tipoOperacion"=>"0101",

			"leyenda"=>array(array(
				"codigo"=> "1000",
				"descripcion"=>"CIEN CON 00/100 SOLES"
			)),
			"montoTotalImpuestos"=>"15.25"
		),
		"DET"=>array(array(
			"numeroItem"=>"1",
			"codigoProducto"=>"A0001",
			"descripcionProducto"=>"GIFTCARD ANTICIPO 1",
			"cantidadItems"=>"1.00",
			"unidad"=>"NIU",
			"valorUnitario"=> "84.75",
			"precioVentaUnitario"=> "100.00",
			"totalImpuestos"=>array(array(
				"idImpuesto"=> "1000",
				"montoImpuesto"=> "15.25",
				"tipoAfectacion"=> "10",
				"montoBase"=>"84.75",
				"porcentaje"=>"18.00"
			)),
			"valorVenta"=> "84.75",
			"montoTotalImpuestos"=>"15.25"
		)

	),
	)
];


header('Content-type: text/x-json; UTF-8');
return $json->encode($armarjsonfe01_A,true);

}
function armarjsonfe01f1(){

	$json=new Services_JSON();
	$armarjsonfe01_A=["factura"=>array(
		"IDE"=>array(
			"numeracion"=>"FA01-00001032",
			"fechaEmision"=>"2019-05-28",
			"codTipoDocumento"=>"01",
			"tipoMoneda"=>"PEN"
		),
		"EMI"=>array(
			"tipoDocId"=>"6",
			"numeroDocId"=>"20101759688",
			"razonSocial"=>"M&M REPUESTOS Y SERVICIOS S.A.",
			"direccion"=>"AV. Nicolas Arriola 1723",
			"codigoAsigSUNAT"=>"0000"
		),
		"REC"=>array(
			"tipoDocId"=>"6",
			"numeroDocId"=>"20381235051",
			"razonSocial"=>"GIFTCARD-ANTICIPO"
		),
		"CAB"=>array(
			"gravadas"=>array(
				"codigo"=> "1001",
				"totalVentas"=> "84.75"
			),
			"totalImpuestos"=>array(array(				
				"idImpuesto"=> "1000",
				"montoImpuesto"=> "15.25"				
			)),			
			"importeTotal"=>"0.00",
			"informacionAnticipo"=>array(array(
				"serieNumero"=> "FA01-00001031",
				"tipoComprobante"=> "02",
				"monto"=> "100.00",
				"tipoMoneda"=>"PEN",
				"fechaAnticipo"=> "2019-05-30",
				"numeroDocEmisor"=>"20101759688",
				"tipoDocumentoEmisor"=> "6",
				"identificador"=>"01"
			)),
			"totalAnticipos"=>"100.00",
			"tipoOperacion"=>"0101",
			"leyenda"=>array(array(
				"codigo"=> "1000",
				"descripcion"=>"CIEN CON 00/100 SOLES"
			)),
			"montoTotalImpuestos"=>"15.25"
		),
		"DET"=>array(array(
			"numeroItem"=>"1",
			"codigoProducto"=>"P001",
			"descripcionProducto"=>"PRODUCTO NRO 1",
			"cantidadItems"=>"3.00",
			"unidad"=>"NIU",
			"valorUnitario"=> "16.95",
			"precioVentaUnitario"=> "20.00",
			"totalImpuestos"=>array(array(
				"idImpuesto"=> "1000",
				"montoImpuesto"=> "9.15",
				"tipoAfectacion"=> "10",
				"montoBase"=>"50.85",
				"porcentaje"=>"18.00"
			)),
			"valorVenta"=> "50.85",
			"montoTotalImpuestos"=>"9.15"
		),
		array(
			"numeroItem"=>"2",
			"codigoProducto"=>"P002",
			"descripcionProducto"=>"PRODUCTO NRO 2",
			"cantidadItems"=>"2.00",
			"unidad"=>"NIU",
			"valorUnitario"=> "16.95",
			"precioVentaUnitario"=> "20.00",
			"totalImpuestos"=>array(array(
				"idImpuesto"=> "1000",
				"montoImpuesto"=> "6.10",
				"tipoAfectacion"=> "10",
				"montoBase"=>"33.90",
				"porcentaje"=>"18.00"
			)),
			"valorVenta"=> "33.90",
			"montoTotalImpuestos"=>"6.10"
		),

	),
	)
];

header('Content-type: text/x-json; UTF-8');
return $json->encode($armarjsonfe01_A,true);
}

function cargajson_3scon(){
	$codcia=$this->session->userdata('codcia');
	$rslstcia = $this->facturacionsendws_db2_model->listarCia($codcia);
	if($rslstcia!=false){
		$EUCODCIA = trim(odbc_result($rslstcia, 1));
		$EUDSCCOM = trim(odbc_result($rslstcia, 2));
		$EUDSCDES = trim(odbc_result($rslstcia, 3));
		$mymclieas400 = trim(odbc_result($rslstcia, 4));
	}

	$rsmymcli=$this->facturacionsendws_db2_model->buscar_cliente_uni_db2as400($mymclieas400);   
	if($rsmymcli!=false){
		$AKCODCLIM = trim(odbc_result($rsmymcli, 1));
		$AKRAZSOCM = trim(utf8_encode(odbc_result($rsmymcli, 2))); 
		$IFNVORUCM = trim(odbc_result($rsmymcli, 3));
		$AKTIPIDEM = trim(odbc_result($rsmymcli, 4)); 
		$AKNROIDEM = trim(odbc_result($rsmymcli, 5)); 
		$NUMIDENM=($AKTIPIDEM=='02')?$IFNVORUCM:$AKNROIDEM;
		$AKIMPLMTM= trim(odbc_result($rsmymcli, 6)); 
	}
	$datos_wsa=datos_webserviceAcceso($NUMIDENM);
	$usuario=$datos_wsa['usuario'];
	$clave=$datos_wsa['clave'];

	$ticket = $this->input->post('nrotk', true);
	$filejs='';
	$SCTECRUC=$NUMIDENM;
	$SCTETDOC="01";
	$filenam='';
	switch ($ticket) {
		case 'A':
		$filenam=$SCTECRUC.'-'.$SCTETDOC.'-FA01-00001031';
		$filejs=$this->armarjsonfe01_A();
		break;
		
		case 'D':
		$filejs=$this->armarjsonfe01_D();
		$filenam=$SCTECRUC.'-'.$SCTETDOC.'-FA01-00001032';
		break;
	}
	$filecontent=base64_encode($filejs);

	$datossvr=dato_hostas();
	$ip=$datossvr['ipas'];	
	$datos_ws=datos_webservice($ip);
	$url=$datos_ws['url'];
	$urlref=$datos_ws['urlref'];
	$filename=$filenam.'.json';
	$documentofe=array("customer"=>array("username"=>$usuario,"password"=>$clave),"fileName"=>$filename,"fileContent"=>$filecontent);
	$json=new Services_JSON();
	$documentfe=$json->encode($documentofe);			
	print_r($documentfe);
	$fe='';
	$header=array('Accept:application/json','Content-Type:application/json;charset=UTF-8','Content-Length:'.strlen($documentfe));
	$cr=curl_init();
	curl_setopt($cr, CURLOPT_URL, $url);
	curl_setopt($cr, CURLOPT_REFERER, $urlref);
	curl_setopt($cr, CURLINFO_HEADER_OUT, true);
	curl_setopt($cr, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($cr, CURLOPT_FRESH_CONNECT, true);			
	curl_setopt($cr, CURLOPT_POST,true);
	curl_setopt($cr, CURLOPT_MAXREDIRS,10);
	curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($cr, CURLOPT_HTTPHEADER, $header);
	curl_setopt($cr, CURLOPT_POSTFIELDS,$documentfe);
	curl_setopt($cr, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cr, CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($cr, CURLOPT_VERBOSE,true);
	curl_setopt($cr, CURLOPT_TIMEOUT, 0);
	curl_setopt($cr, CURLOPT_FAILONERROR, true);

	$documentofersl=curl_exec($cr);
	print_r($documentofersl);
	$docufeerror='';
	$errno='';
	if($documentofersl===false){
		$docufeerror=curl_error($cr);
	}else{
		$docufeerror='';
	}
	$http_statuscod=curl_getinfo($cr,CURLINFO_HTTP_CODE);
	$http_status=curl_getinfo($cr,CURLINFO_EFFECTIVE_URL);
	curl_close($cr);
	$docufersl=json_decode($documentofersl);
	print_r($docufersl);
}
function optener_cdr_fe(){

	$nrosere = $this->input->post('nrosere', true);
	$nrocor = $this->input->post('nrocor', true);
	$tipdoc = $this->input->post('tipdoc', true);
	$codcia=$this->session->userdata('codcia');

	$rslstcia = $this->facturacionsendws_db2_model->listarCia($codcia);
	if($rslstcia!=false){
		$EUCODCIA = trim(odbc_result($rslstcia, 1));
		$EUDSCCOM = trim(odbc_result($rslstcia, 2));
		$EUDSCDES = trim(odbc_result($rslstcia, 3));
		$mymclieas400 = trim(odbc_result($rslstcia, 4));
	}

	$rsmymcli=$this->facturacionsendws_db2_model->buscar_cliente_uni_db2as400($mymclieas400);   
	if($rsmymcli!=false){
		$AKCODCLIM = trim(odbc_result($rsmymcli, 1));
		$AKRAZSOCM = trim(utf8_encode(odbc_result($rsmymcli, 2))); 
		$IFNVORUCM = trim(odbc_result($rsmymcli, 3));
		$AKTIPIDEM = trim(odbc_result($rsmymcli, 4)); 
		$AKNROIDEM = trim(odbc_result($rsmymcli, 5)); 
		$NUMIDENM=($AKTIPIDEM=='02')?$IFNVORUCM:$AKNROIDEM;
		$AKIMPLMTM= trim(odbc_result($rsmymcli, 6)); 
	}
	$datos_wsa=datos_webserviceAcceso($NUMIDENM);
	$usuario=$datos_wsa['usuario'];
	$clave=$datos_wsa['clave'];
	$datossvr=dato_hostas();
	$ip=$datossvr['ipas'];        
	$datos_ws=datos_webserviceCdr($ip);
	
	$url=$datos_ws['url'];
	$urlref=$datos_ws['urlref'];
	$codCPE=$tipdoc;
	$numSerieCPE=$nrosere;
	$numCPE=$nrocor;
	$consultastatustk=["user"=>array("username"=>$usuario,"password"=>$clave),"codCPE"=>$codCPE,"numSerieCPE"=>$numSerieCPE,"numCPE"=>$numCPE];		
	$json=new Services_JSON();
	$documentfe=$json->encode($consultastatustk);
	$fe='';
	$header=array('Accept:application/json','Content-Type:application/json;charset=UTF-8','Content-Length:'.strlen($documentfe));
	$cr=curl_init();
	curl_setopt($cr, CURLOPT_URL, $url);
	curl_setopt($cr, CURLOPT_REFERER, $urlref);
	curl_setopt($cr, CURLINFO_HEADER_OUT, true);
	curl_setopt($cr, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($cr, CURLOPT_FRESH_CONNECT, true);			
	curl_setopt($cr, CURLOPT_POST,true);
	curl_setopt($cr, CURLOPT_MAXREDIRS,10);
	curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($cr, CURLOPT_HTTPHEADER, $header);
	curl_setopt($cr, CURLOPT_POSTFIELDS,$documentfe);
	curl_setopt($cr, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cr, CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($cr, CURLOPT_VERBOSE,true);
	curl_setopt($cr, CURLOPT_TIMEOUT, 0);
	curl_setopt($cr, CURLOPT_FAILONERROR, true);
	$documentofersl=curl_exec($cr);
	$docufeerror='';
	$errno='';
	if($documentofersl===false){
		$docufeerror=curl_error($cr);
	}else{
		$docufeerror='';
	}
	$http_statuscod=curl_getinfo($cr,CURLINFO_HTTP_CODE);
	$http_status=curl_getinfo($cr,CURLINFO_EFFECTIVE_URL);
	curl_close($cr);

	$body=array();
	$data='';
	if($documentofersl===false){
		$statusCode=$docufersl->statusCode;
		$responseCode=$docufersl->responseCode;
		$responseMessage=$docufersl->responseMessage;
		$body[]=array("dato1"=>$statusCode,"dato2"=>$responseCode.' '.$responseMessage);
		$data['msg']='Sin Datos';
		$data['datos']=$body;
		$data['existe'] = 1;

	}else{
		$docufersl=json_decode($documentofersl);
		$codigo=$docufersl->codigo;
		$mensaje=$docufersl->mensaje;
		$xmlcdr=base64_decode($docufersl->cdr);	
		$object = json_encode(simplexml_load_string($xmlcdr));
		$body[]=array("dato0"=>$codigo .' '.$mensaje,"dato1"=>$xmlcdr,"dato2"=>$object);
		$data['msg']='Con Datos';
		$data['datos']=$body;
		$data['existe'] = 0;
	}
	$result = $data;	
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($result);

}


}
?>