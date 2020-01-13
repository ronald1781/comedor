<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Facturacionsendbajaws_baja_control extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('general_model', '', TRUE);
		$this->load->model('facturacionsendbajaws_db2_model', '', TRUE);
		$this->load->model('facturacionsendws_db2_model', '', TRUE);
		$this->load->library('Services_JSON');
		//$this->load->library('curl');
		//$this->load->library('html2pdf');
		$this->load->library('fpdf_lib');
	}

	function index() {
		$datap='';
	}

	function get_documentosfe(){

		$valid = $this->session->userdata('validated');
		$body=array();
		if ($valid == TRUE) { 
			$seltd = $this->input->post('seltd', true);
			$selseri = $this->input->post('selseri', true);
			$fechaa = $this->input->post('fechaa', true);
			$dd=substr($fechaa, 0,2);
			$dm=substr($fechaa, 3,2);
			$da=substr($fechaa, 6,4);
			$fechae=$da.$dm.$dd;
			$rsdocxgj=$this->facturacionsendws_db2_model->get_documentosfe($fechae,$seltd,$selseri);
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
					$body[]=array("SCTFECEM"=>$SCTFECEM,"SCTESUCA"=>$SCTESUCA,"SCTETDOC"=>$SCTETDOC,"SCTEPDCA"=>$SCTEPDCA,"SCTCRZSO"=>$SCTCRZSO,"SCTESERI"=>$SCTESERI,"SCTECORR"=>$SCTECORR,"SCTCTMON"=>$SCTCTMON,"SCTGNETO"=>number_format($SCTGNETO,2,".",","),"SCTCSTST"=>$SCTCSTST,"SCTECIAA"=>$SCTECIAA,"SCTEFEC"=>$SCTEFEC);
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
			header('Content-type: text/x-json; UTF-8');	
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($result);
		}else{
			redirect('loginin');	
		}
	}
	
	function get_detalledocumentobaja(){
		$tbody='';
		$statusdoc='N';
		$fechaas='';
		$result='';
		$listadocumentos=array();		
		$datas['seltd'] = $this->security->xss_clean($this->input->post('seltd', true));
		$datas['selseri'] = $this->security->xss_clean($this->input->post('selseri', true));
		$selstsdb = $this->security->xss_clean($this->input->post('selstsdb', true));
		$fechabd= $this->security->xss_clean($this->input->post('fechabd', true));
		$fechabh= $this->security->xss_clean($this->input->post('fechabh', true));		
		if(($fechabd!='')&&($fechabd!='')){
			$dd=substr($fechabd, 0,2);
			$dm=substr($fechabd, 3,2);
			$da=substr($fechabd, 6,4);

			$hd=substr($fechabh, 0,2);
			$hm=substr($fechabh, 3,2);
			$ha=substr($fechabh, 6,4);

			$fechaas=" between '".$da.$dm.$dd."' and '".$ha.$hm.$hd."'";
		}else{
			$f= gmdate("d-m-Y", time() - 18000);
			$dd=substr($f, 0,2);
			$dm=substr($f, 3,2);
			$da=substr($f, 6,4);
			$fechaas=" between '".$da.$dm.$dd."' and '".$da.$dm.$dd."'";
		}		
		$datas['fechaa']=$fechaas;
		$fecha=$fechaas;
		$rsdtdocbaj=$this->facturacionsendbajaws_db2_model->detalledocumentoanulado($datas);
		$conta=0;
		$STSO1='';$STSO2='';$STST1='';$STST2='';
		$YHSUCDOC = '';	$YHTIPDOC = '';	$YHTIPDOCU = '';$YHNROPDC = '';	$YHNUMSER = '';	$YHNUMCOR = '';	$YHSTS='';
		$YHCODSUC='';
		$ae=substr($fecha, 0,4);
		$me=substr($fecha, 4,2);
		$de=substr($fecha, 6,2);
		$nommes=$de.' de '.mes_letra($me).' del '.$ae;
		$statusdoc='';
		$ESTADO='';
		$datas=array();
		$CBSTS='';$CBSTSPDO='';$CBCODMON='';$FXSTS='';
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
					$rsdtdocbajscb=$this->facturacionsendbajaws_db2_model->cbdocumentoanulado($fecha,$YHNROPDC,$YHSUCDOC,$YHNUMSER,$YHNUMCOR);					
					if($rsdtdocbajscb==false){
						$$CBSTS='';
					}else{			
						$CBSTS=trim(odbc_result($rsdtdocbajscb, 1));						
						$CBSTSPDO=trim(odbc_result($rsdtdocbajscb, 2));
						$CBCODMON=trim(odbc_result($rsdtdocbajscb, 3));	
					}
					$ESTADO=$CBSTS;
				}elseif ($YHTIPDOC=='07') {
					$rsdtdocbajia=$this->facturacionsendbajaws_db2_model->iadocumentoanulado($YHNROPDC,$YHSUCDOC);
					$IANROPDC = trim(odbc_result($rsdtdocbajia, 1));
					$YHNROPDC=$IANROPDC;
					$rsdtdocbajfx=$this->facturacionsendbajaws_db2_model->fxdocumentoanulado($fecha,$IANROPDC,$YHSUCDOC,$YHNUMSER,$YHNUMCOR,$YHTIPDOC);				
					if($rsdtdocbajfx==false){
						$FXSTS='';
					}else{			
						$FXSTS=trim(odbc_result($rsdtdocbajfx, 1));	
						
					}
					$ESTADO=$FXSTS;
				} elseif ($YHTIPDOC=='08') {
					$rsdtdocbajjr=$this->facturacionsendbajaws_db2_model->jrdocumentoanulado($fecha,$YHNROPDC,$YHSUCDOC,$YHTIPDOC,$YHNUMSER,$YHNUMCOR);
					if($rsdtdocbajjr==false){
						$JRSTS='';
					}else{			
						$JRSTS=trim(odbc_result($rsdtdocbajjr, 1));			

					}
					$ESTADO= $JRSTS;
				}else{

				}
				$rsdtdocbajst=$this->facturacionsendbajaws_db2_model->stdocumentoanulado($fecha,$YHNROPDC,$YHSUCDOC,$YHTIPDOC,$YHNUMSER,$YHNUMCOR);
				$SCTESERI = trim(odbc_result($rsdtdocbajst, 1));
				$SCTECORR = trim(odbc_result($rsdtdocbajst, 2));
				$SCTCCLIE = trim(odbc_result($rsdtdocbajst, 3));
				$SCTCRZSO = trim(utf8_encode(odbc_result($rsdtdocbajst, 4)));
				$SCTCTMON = trim(odbc_result($rsdtdocbajst, 5));
				$SCTGTOTA = trim(odbc_result($rsdtdocbajst, 6));
				$SCTCSTS =(trim(odbc_result($rsdtdocbajst, 7))=='')?'':trim(odbc_result($rsdtdocbajst, 7)); 
				$SCTCSTSA =(trim(odbc_result($rsdtdocbajst, 7))=='')?'':trim(odbc_result($rsdtdocbajst, 7));
				$SCTFECEM=	trim(odbc_result($rsdtdocbajst, 8));
				$SCTETDOC=	trim(odbc_result($rsdtdocbajst, 9));
				$SCTCSTST=	trim(odbc_result($rsdtdocbajst, 10));
				$SCTEALMA= trim(odbc_result($rsdtdocbajst, 11));
				$SCTECIAA=trim(odbc_result($rsdtdocbajst, 12));
				$SCTEFEC=trim(odbc_result($rsdtdocbajst, 13));
				$conta=$conta+1;				
				$classtr=(($YHSTS==$ESTADO)&&($SCTESERI!=''))?'':'class="danger"';
				if(($YHSTS==$ESTADO)&&($SCTESERI!='')){
					$checked='';
				}elseif($SCTCSTSA==$YHSTS){
					$checked='disabled = "true"';
				}else{
					$checked='';
				}	
				if(($YHSTS==$ESTADO)&&($SCTESERI!='')){
					$checkeda='checked="checked"';
				}elseif($SCTCSTSA==$YHSTS){
					$checkeda='';
				}else{
					$checkeda='';
				}	
				$DOCSERIE=($SCTESERI=='')?$YHNUMSER:$SCTESERI;
				$DOCCORRE=($SCTECORR=='')?$YHNUMCOR:$SCTECORR;
				$seriedoc=$DOCSERIE.' '.$DOCCORRE;
				$MONEDA=($SCTCTMON=='')?'':$SCTCTMON;
				$DATACLIE=($SCTCRZSO=='')?'':$SCTCRZSO;
				$IMPORTE=($SCTGTOTA=='')?0.00:$SCTGTOTA;
				$STATRAM=($SCTCSTS=='')?'':$SCTCSTS;
				$NUMIMPORTE=number_format($IMPORTE,2,".",",");
				$STSO1=$YHSTS;
				$STSO2=($ESTADO=='')?'-':$ESTADO;
				$STST1=$STATRAM;
				$STST2=$SCTCSTST;
				$ESTADO1="";

				$listadocumentos[]=array('SCTFECEM'=>$SCTFECEM,'YHNROPDC'=>$YHNROPDC,'YHSUCDOC'=>$YHSUCDOC,'YHTIPDOC'=>$YHTIPDOC,'SCTESERI'=>$SCTESERI,'SCTECORR'=>$SCTECORR,'YHNUMSER'=>$YHNUMSER,'YHNUMCOR'=>$YHNUMCOR,'YHNROPDC'=>$YHNROPDC,'YHCODSUC'=>$YHCODSUC,'YHTIPDOCU'=>$YHTIPDOCU,'MONEDA'=>$MONEDA,'DATACLIE'=>$DATACLIE,'NUMIMPORTE'=>$NUMIMPORTE,'SCTCSTSA'=>$SCTCSTSA,'STATRAM'=>$STATRAM,'ESTADO'=>$ESTADO1,'SCTETDOC'=>$SCTETDOC,'SCTCSTST'=>$SCTCSTST,'SCTEALMA'=>$SCTEALMA,'YHSTS'=>$YHSTS,'SCTECIAA'=>$SCTECIAA,'SCTEFEC'=>$SCTEFEC);
			}
			
			$data['msg']='Con Datos';
			$data['datos']=$listadocumentos;
			$data['existe'] = 0;
			$result=$data;
		}else{		
			$data['msg']='Sin Datos';
			$data['datos']='';
			$data['existe'] = 1;
			$result=$data;
		}	
		header('Content-type: text/x-json; UTF-8');
		header('Content-type: application/json; charset=utf-8');
		//print_r($result);
		echo json_encode($result);			
		
	}


	function get_anulacionmanual(){
		$limit=1;
		$nropdc = $this->input->post('limit', true);
		$fechabd = $this->input->post('fechabd', true);
		$fechabh = $this->input->post('fechabh', true);
		$nropdc = $this->input->post('tipodoc', true);
		
		$fechaas='';
		if(($fechabd=='')&&($fechabh=='')){
			$f= gmdate("d-m-Y", time() - 18000);
			$dd=substr($f, 0,2);
			$dm=substr($f, 3,2);
			$da=substr($f, 6,4);
			$fechaas=" between ".$da.$dm.$dd." and ".$da.$dm.$dd;
			
		}else{
			$dd=substr($fechabd, 0,2);
			$dm=substr($fechabd, 3,2);
			$da=substr($fechabd, 6,4);

			$hd=substr($fechabh, 0,2);
			$hm=substr($fechabh, 3,2);
			$ha=substr($fechabh, 6,4);
			$fechaas=" between ".$da.$dm.$dd." and ".$ha.$hm.$hd;
		}
		
		//between 22-0-219 and 22-0-219
		$rpt=['rpta'=>true,'fecha'=>$fechaas];
		$rpta=$this->get_anulacionmanual1($rpt);
		$respta=$rpta['tiempo1'];
		$rptp=$rpta['rptp'];
		if($rptp==false){		
			$data['msg']='Con Datos';
			$data['datos']=$rpta;
			$data['existe'] = 0;
			$result=$data;
			header('Content-type: text/x-json; UTF-8');
			header('Content-type: application/json; charset=utf-8');
		//print_r($result);
			echo json_encode($result);
		}else{
			$data['msg']='Sin Datos';
			$data['datos']=$rpta;
			$data['existe'] = 1;
			$result=$data;
			header('Content-type: text/x-json; UTF-8');
			header('Content-type: application/json; charset=utf-8');
		//print_r($result);
			echo json_encode($result);
		}
		
	}

	function get_anulacionmanualindividual(){
		$time_start = microtime(true);
		$fechaf=$this->input->post('fecha', true);
		$da=substr($fechaf, 0,4);
		$dm=substr($fechaf, 5,2);
		$dd=substr($fechaf, 8,2);
		$fecha=" between ".$da.$dm.$dd." and ".$da.$dm.$dd;
		$data['nropdc'] = $this->input->post('nropdc', true);
		$data['serie'] = $this->input->post('serie', true);
		$data['correl'] = $this->input->post('corr', true);
		$data['fecha']=$fecha;
		$data['tipdoc'] = $this->input->post('tipdoc', true);
		$data['stsdoct'] = $this->input->post('stsdoct', true);
		$data['codalm']= $this->input->post('codalm', true);

		$colector=array();
		$dato='';
		$flg='m';
		$conta=0;
		$resul=false;
		$rdpej='';
		$rsltnroanu=0;
		
		$SCTEFEC='';$SCTESUCA='';$SCTEPDCA='';$SCTETDOC='';$SCTESERI='';$SCTECORR='';$SCTEALMA='';$SCTCSTST='';$SCTTDREF='';		
		$rltbjm=$this->facturacionsendbajaws_db2_model->get_anulacionmanual_unitario($data);
		if($rltbjm!=false){
			$resul=$rltbjm;
			while (odbc_fetch_row($rltbjm)) {	
				$SCTEFEC = trim(odbc_result($rltbjm, 1));
				$SCTESUCA = trim(odbc_result($rltbjm, 2));
				$SCTEPDCA = trim(odbc_result($rltbjm, 3));
				$SCTETDOC = trim(odbc_result($rltbjm, 4));
				$SCTESERI = trim(odbc_result($rltbjm, 5));
				$SCTECORR =trim(odbc_result($rltbjm, 6));				
				$SCTEALMA = trim(odbc_result($rltbjm, 7));
				$SCTCSTST = trim(odbc_result($rltbjm, 8));
				$SCTTDREF= trim(odbc_result($rltbjm, 9));
				$SCTECIAA=trim(odbc_result($rltbjm, 10));

				$ae=substr($SCTEFEC, 0,4);
				$me=substr($SCTEFEC, 4,2);
				$de=substr($SCTEFEC, 6,2);			
				$fechaas=$ae.'-'.$me.'-'.$de;
				if(($SCTCSTST=='A')||($SCTCSTST=='N')||($SCTCSTST=='E')){
					$conta++;
					$dato=[
						'conta'=>$conta,
						'flg'=>$flg,
						'codcia' =>$SCTECIAA,
						'nropdc' =>$SCTEPDCA,
						'serie' => $SCTESERI,
						'correl' => $SCTECORR,
						'fecha'=>$fechaas,
						'tipdoc' => $SCTETDOC,
						'stsdoct' =>$SCTCSTST,
						'codalm'=> $SCTEALMA,
						'docref'=>$SCTTDREF
					];
					$rspt=$this->validar($dato,$fecha);
					$rdpej=$rspt;
				}else{
					$dato=[
						'conta'=>$conta,
						'flg'=>$flg,
						'codcia' =>$SCTECIAA,
						'nropdc' =>$SCTEPDCA,
						'serie' => $SCTESERI,
						'correl' => $SCTECORR,
						'fecha'=>$fechaas,
						'tipdoc' => $SCTETDOC,
						'stsdoct' =>$SCTCSTST,
						'codalm'=> $SCTEALMA,
						'docref'=>$SCTTDREF
					];
				}
			}

		}else{			
			$rdpej=false;		

		}
		$time_end = microtime(true);
		$time_total = $time_end - $time_start;
		$rpta=['tiempo1'=>$time_total,'rptp'=>$rdpej,'numbaja'=>1];
		print_r($rpta);	
		
		header('Content-type: text/x-json; UTF-8');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($rpta);

	}
	function get_anulacionmanual1($rpt){

		$colector=array();
		$limit=
		$rptp=$rpt['rpta'];		
		$fecha=$rpt['fecha'];
		$time_start = microtime(true);
		$dato='';
		$flg='i';
		$conta=0;
		$resul=false;
		$rdpej='';
		$rsltnroanu=0;
		
		$SCTEFEC='';$SCTESUCA='';$SCTEPDCA='';$SCTETDOC='';$SCTESERI='';$SCTECORR='';$SCTEALMA='';$SCTCSTST='';$SCTTDREF='';
		if($rptp==true){
			$rsltnroanu=$this->facturacionsendbajaws_db2_model->cuentadocxanular($fecha);
			$rsltnroanu=($rsltnroanu==false)?0:odbc_result($rsltnroanu, 1);
			$limit=($rsltnroanu>0)?1:0;
			if(($rsltnroanu>0)&&($conta<=$rsltnroanu)){
				$rltbjm=$this->facturacionsendbajaws_db2_model->get_anulacionmanual($limit,$fecha);
				if($rltbjm!=false){
					$resul=$rltbjm;
					while (odbc_fetch_row($rltbjm)) {	
						$SCTEFEC = trim(odbc_result($rltbjm, 1));
						$SCTECIAA= trim(odbc_result($rltbjm, 2));
						$SCTESUCA = trim(odbc_result($rltbjm, 3));
						$SCTEPDCA = trim(odbc_result($rltbjm, 4));
						$SCTETDOC = trim(odbc_result($rltbjm, 5));
						$SCTESERI = trim(odbc_result($rltbjm, 6));
						$SCTECORR =trim(odbc_result($rltbjm, 7));				
						$SCTEALMA = trim(odbc_result($rltbjm, 8));
						$SCTCSTST = trim(odbc_result($rltbjm, 9));
						$SCTTDREF= trim(odbc_result($rltbjm, 10));
						$ae=substr($SCTEFEC, 0,4);
						$me=substr($SCTEFEC, 4,2);
						$de=substr($SCTEFEC, 6,2);			
						$fechaas=$ae.'-'.$me.'-'.$de;
						if(($SCTCSTST=='A')||($SCTCSTST=='N')||($SCTCSTST=='E')){
							$conta++;
							$dato=[
								'conta'=>$conta,
								'flg'=>$flg,
								'codcia' =>$SCTECIAA,
								'nropdc' =>$SCTEPDCA,
								'serie' => $SCTESERI,
								'correl' => $SCTECORR,
								'fecha'=>$fechaas,
								'tipdoc' => $SCTETDOC,
								'stsdoct' =>$SCTCSTST,
								'codalm'=> $SCTEALMA,
								'docref'=>$SCTTDREF
							];

							$rspt=$this->validar($dato,$fecha);
							$rdpej=$rspt;
						}else{
							$dato=[
								'conta'=>$conta,
								'flg'=>$flg,
								'codcia' =>$SCTECIAA,
								'nropdc' =>$SCTEPDCA,
								'serie' => $SCTESERI,
								'correl' => $SCTECORR,
								'fecha'=>$fechaas,
								'tipdoc' => $SCTETDOC,
								'stsdoct' =>$SCTCSTST,
								'codalm'=> $SCTEALMA,
								'docref'=>$SCTTDREF
							];
						}
					}
				}else{
					$dato=$rltbjm;
					$rspt=$this->validar($dato,$fecha);
					$rdpej=$rspt;
				}
			}else{
				$rdpej=false;	
			}
		}else{			
			$rdpej=false;		

		}

		$time_end = microtime(true);
		$time_total = $time_end - $time_start;
		$respta=['tiempo1'=>$time_total,'rptp'=>$rdpej,'numbaja'=>$rsltnroanu.' '.$conta];
		//	header('Content-type: text/x-json; UTF-8');
		//	header('Content-type: application/json; charset=utf-8');
		//print_r($result);
			//echo json_encode($dato);	
		return $rdpej;
		

	}
	function validar($dato = false,$fecha){

		if($dato!=false){

			$result=$this->procesarbajafe($dato);
			
			if($result==true){				
				$rpta=['rpta'=>$result,'fecha'=>$fecha];
				$this->get_anulacionmanual1($rpta);
			}else{
				$rpta=['rpta'=>$result,'fecha'=>'']; 
				$this->get_anulacionmanual1($rpta);
			}
		}else{
			$rpta=['rpta'=>false,'fecha'=>$fecha]; 
			$this->get_anulacionmanual1($rpta);	
		}		

	}
	function procesarbajafe($datap=false){
		$time_start = microtime(true);
		$flg='';
		$nropdc ='';
		$serie = '';
		$correl = '';
		$fecha='';
		$tipdoc = '';
		$stsdoct = '';
		$codalm= '';
		$docref='';

		$filebaja='';
		$data='';
		$documentofe='';
		$sendfe='';
		$result="";
		$docuanubol='';
		$datos='';
		$filejs='';
		$numbaja='';
		$resultprintfe='';	
		$docufersl='';

		$docufeerror='';
		$update_data='';
		$documentofe='';

		$docufersl='';
		$http_status='';
		$http_statuscod='';
		$documentofersl='';
		$filename='';
		$documentof='';

		$docufeerror='';
		$resulta='';
		$rsl='';
		$docuanubol='';
		$time='';
		$tdf='';
		$rpt='';
		$rpta=false;
		$rptrg='';
		$rspta='';
		$tdf='';
		$info='';
		$adi='';

		$f= gmdate("d-m-Y", time() - 18000);
		$fechadatargev=gmdate("d-m-Y H:i:s", time() - 18000);
		$dd=substr($f, 0,2);
		$dm=substr($f, 3,2);
		$da=substr($f, 6,4);
		$fechan=$da.$dm.$dd;

		$codcia=$datap['codcia'];
		$nropdc=$datap['nropdc'];
		$serie=$datap['serie'];
		$correl=$datap['correl'];
		$fecha=$datap['fecha'];
		$tipdoc=$datap['tipdoc'];
		$stsdoct=$datap['stsdoct'];
		$codalm=$datap['codalm'];
		$docref=$datap['docref'];			

		if(($stsdoct=="N")||($stsdoct=="E")){
			$info['SCTESUCA']=$datap['codalm']; 
			$info['SCTECIAA']=$datap['codcia'];
			$info['SCTEPDCA']=$datap['nropdc'];
			$info['SCTESERI']=$datap['serie'];
			$info['SCTECORR']=$datap['correl'];
			$info['SCTETDOC']=$datap['tipdoc'];
			$info['SCTFECEM']=$datap['fecha'];						
			$info['SCTCSTST']=$datap['stsdoct'];
			$SCTECIAA=$datap['codcia'];
			$SCTEPDCA=$datap['nropdc'];
			$responseCode='98';
			$SACODSUC=$datap['codalm'];
			$gtfeev=$this->facturacionsendbajaws_db2_model->get_ticket_eventos_fe($SCTECIAA,$SCTEPDCA,$responseCode);
			$SACODRPT=trim(odbc_result($gtfeev, 1));
			$SAMSGRPT=trim(odbc_result($gtfeev, 2));
			$dataget = explode("##", $SAMSGRPT);
			$ticket=$dataget[0];
			$msj = $dataget[1];				
			$dataticket=$this->optener_status_ticket($ticket,$datap['codcia']);
			$responseCodertk=$dataticket['responseCode'];
			if($responseCodertk=='0'){					
				$info['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
				$info['responseContent']=$dataticket['responseMessage'];
				$rptrg=$this->upatestsdoc_anu($info);
				$rpta=true;	
			}else{
				$info['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
				$info['responseContent']=$dataticket['responseMessage'];
				$rptrg=$this->upatestsdoc_anu($info);
				$rpta=true;	
			}
			
		}else{

			if(($datap['tipdoc']=='01')&&($datap['docref']=='0')) {				
				$da=$this->genera_filejson_ra($datap);
				$file=$da['filename'];
				$filejs=$da['filejs'];
				$tdf=$da['tdra'];
				$time=["tf"=>$tdf["tra"],'tde'=>$tdf["tde"],'fljs'=>$filejs];
				
			}elseif(($datap['tipdoc']=='03')&&($datap['docref']=='0')) {				
				$da=$this->genera_filejson_rc($datap);
				$file=$da['filename'];
				$filejs=$da['filejs'];
				$docuanubol=$da['docuanul'];
				$tdf=$da['tdrc'];
				$time=["tf"=>$tdf["trc"],'tde'=>$tdf["tde"],'fljs'=>$filejs];
			}elseif(($datap['tipdoc']=='07')&&($datap['docref']=='3')) {				
				$da=$this->genera_filejson_rc($datap);
				$file=$da['filename'];
				$filejs=$da['filejs'];
				$docuanubol=$da['docuanul'];
				$tdf=$da['tdrc'];
				$time=["tf"=>$tdf["trc"],'tde'=>$tdf["tde"],'fljs'=>$filejs];
			}elseif(($datap['tipdoc']=='08')&&($datap['docref']=='3')) {				
				$da=$this->genera_filejson_rc($datap);
				$file=$da['filename'];
				$filejs=$da['filejs'];
				$docuanubol=$da['docuanul'];
				$tdf=$da['tdrc'];
				$time=["tf"=>$tdf["trc"],'tde'=>$tdf["tde"],'fljs'=>$filejs];
			}elseif(($datap['tipdoc']=='07')&&($datap['docref']=='1')) {				
				$da=$this->genera_filejson_ra($datap);
				$file=$da['filename'];
				$filejs=$da['filejs'];
				$tdf=$da['tdra'];
				$time=["tf"=>$tdf["tra"],'tde'=>$tdf["tde"]];
			}elseif(($datap['tipdoc']=='08')&&($datap['docref']=='1')) {				
				$da=$this->genera_filejson_ra($datap);
				$file=$da['filename'];
				$filejs=$da['filejs'];
				$tdf=$da['tdra'];
				$time=["tf"=>$tdf["tra"],'tde'=>$tdf["tde"],'fljs'=>$filejs];
			}			

			$filesjs=($filejs=='')?'':$filejs;
			if($filesjs!=''){
				$dataenvws=['file'=>$file,'filejs'=>$filejs,'codcia'=>$datap['codcia']];

				$rsltsdws=$this->send_webservicefa_baja($dataenvws);
				$ticket=$rsltsdws['ticket'];
				$responseCode=$rsltsdws['responseCode'];
				$responseContent=$rsltsdws['responseContent'];
				$adi=$rsltsdws['adi'];				
				$info['SCTESUCA']=$datap['codalm']; 
				$info['SCTECIAA']=$datap['codcia'];
				$info['SCTEPDCA']=$datap['nropdc'];
				$info['SCTESERI']=$datap['serie'];
				$info['SCTECORR']=$datap['correl'];
				$info['SCTETDOC']=$datap['tipdoc'];
				$info['SCTFECEM']=$datap['fecha'];						
				$info['SCTCSTST']=$datap['stsdoct'];
				$info['responseCode']=$responseCode;
				$info['responseContent']=$ticket.'##'.$responseContent.' '.$fechadatargev.' '.$file;
				$rptrg=$this->upatestsdoc_anu($info);
				$rpta=true;			
			}else{
				$rpta=false;
			}
						
		}

		$flagretur=($datap['flg']=='i')?false:$rpta;	
		//print_r($flagretur);	
		return $flagretur;
		
		
	}
	function datos_emisor($codcia,$fecha){
		$time_start = microtime(true);
// +---------------------------------------------------------------------------+    
// |Datos CLiente mym
// +---------------------------------------------------------------------------+
		//$mymclieas400='010077';
		$rslstcia = $this->facturacionsendws_db2_model->listarCia($codcia);
		$EUCODCIA = trim(odbc_result($rslstcia, 1));
		$EUDSCCOM = trim(odbc_result($rslstcia, 2));
		$EUDSCDES = trim(odbc_result($rslstcia, 3));
		$mymclieas400 = trim(odbc_result($rslstcia, 4));
		$IFNVORUCM='';
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
		$NOMDEPAM='';
		$NOMPROVM='';
		$NOMDISTM='';
		$dirclimym=$this->facturacionsendws_db2_model->buscar_direccion_cliente_mym_db2as400($mymclieas400);   
		while (odbc_fetch_row($dirclimym)) {
			$ALVIADIRM = trim(odbc_result($dirclimym, 1));
			$tvaclim=$this->facturacionsendws_db2_model->buscar_tipovia_cliente_db2as400($ALVIADIRM);   
			while (odbc_fetch_row($tvaclim)) {
				$TVIACABRM = trim(odbc_result($tvaclim, 1));
			}
			$ALDSCDIRM = trim(utf8_encode(odbc_result($dirclimym, 2))); 
			$ALNRODIRM = trim(odbc_result($dirclimym, 3));
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
		$rsmymclitc=$this->facturacionsendbajaws_db2_model->datos_emisor($codcia,$fecha);
		$SCTERZSO='';$SCTECRUC='';$SCTEUBIG='';$SCTEDIRE='';$SCTENCOM='';
		$SCTFECEM='';
		if($rsmymclitc!=false){
			while (odbc_fetch_row($rsmymclitc)) {
				$SCTERZSO = trim(odbc_result($rsmymclitc, 1));
				$SCTECRUC = trim(utf8_encode(odbc_result($rsmymclitc, 2)));
				$SCTEUBIG = trim(odbc_result($rsmymclitc, 3));
				$SCTEDIRE = trim(odbc_result($rsmymclitc, 4)); 
				$SCTENCOM = trim(odbc_result($rsmymclitc, 5)); 
				$SCTFECEM= trim(odbc_result($rsmymclitc, 6));
			}
		}else{

		}
		$datos["tipoDocId"]='6';
		$datos["numeroDocId"]=$NUMIDENM;
		$datos["nombreComercial"]=$SCTENCOM;
		$datos["razonSocial"]=$AKRAZSOCM;
		$datos["ubigeo"]=$SCTEUBIG;
		$datos["direccion"]=$SCTEDIRE;
		$datos["urbanizacion"]="URB";
		$datos["provincia"]=$NOMPROVM;
		$datos["departamento"]=$NOMDEPAM;
		$datos["distrito"]=$NOMDISTM;
		$datos["codigoPais"]= "PE";
		$datos["telefono"]="016131500";
		$datos["correoElectronico"]="ventas@mym.com.pe";
		$time_end = microtime(true);
		$time_total = $time_end - $time_start;
		$datos["tde"]=$time_total;
		return $datos;
	}	
	function genera_filejson_rc($data){
		$time_start = microtime(true);
		$codcia=$data['codcia'];
		$nropdc=$data['nropdc'];
		$serie=$data['serie'];
		$correl=$data['correl'];
		$fecha=$data['fecha'];
		$tipdoc=$data['tipdoc'];
		$stsdoct=$data['stsdoct'];
		$codalm=$data['codalm'];
		$docref=$data['docref'];
		$docuanubol=array();
		$f= gmdate("d-m-Y", time() - 18000);
		$dd=substr($f, 0,2);
		$dm=substr($f, 3,2);
		$da=substr($f, 6,4);
		$fechan=$da.$dm.$dd;
		$fechae=$da.'-'.$dm.'-'.$dd;	
		$rsdocxgj=$this->facturacionsendbajaws_db2_model->documentoxgenerarjson($data);
		$SCTEFEC='';$SCTCTMON='';$SCTESERI='';$SCTECORR='';$SCTETDOC='';
		$SCTETPDO='';$SCTCNRUC='';$SCTGNETO='';$SCTCCIMP='';$SCTMTIMP='';$SCTGTOTA='';$SCTTDREF='';$SCTEPDCA='';
		if($rsdocxgj!=false){
			while (odbc_fetch_row($rsdocxgj)) {	
				$SCTEFEC=trim(odbc_result($rsdocxgj, 1));
				$SCTCTMON=trim(odbc_result($rsdocxgj, 2));
				$SCTESERI=trim(odbc_result($rsdocxgj, 3));
				$SCTECORR=trim(odbc_result($rsdocxgj, 4));
				$SCTETDOC=trim(odbc_result($rsdocxgj, 5));
				$SCTETPDO=trim(odbc_result($rsdocxgj, 6));
				$SCTCNRUC=trim(odbc_result($rsdocxgj, 7));
				$SCTGNETO=trim(odbc_result($rsdocxgj, 8));
				$SCTCCIMP=trim(odbc_result($rsdocxgj, 9));
				$SCTMTIMP=trim(odbc_result($rsdocxgj, 10));	
				$SCTGTOTA=trim(odbc_result($rsdocxgj, 11));
				$SCTTDREF=trim(odbc_result($rsdocxgj, 12));
				$SCTCSTST=trim(odbc_result($rsdocxgj, 13));
				$SCTEALMA=trim(odbc_result($rsdocxgj, 14));
				$SCTEPDCA=trim(odbc_result($rsdocxgj, 15));
				$SCTFECEM=trim(odbc_result($rsdocxgj, 16));
				$docuanubol[]=array('SCTEFEC'=>$SCTEFEC,'SCTCTMON'=>$SCTCTMON,'SCTESERI'=>$SCTESERI,'SCTECORR'=>$SCTECORR,'SCTETDOC'=>$SCTETDOC,'SCTETPDO'=>$SCTETPDO,'SCTCNRUC'=>$SCTCNRUC,'SCTGNETO'=>$SCTGNETO,'SCTCCIMP'=>$SCTCCIMP,'SCTMTIMP'=>$SCTMTIMP,'SCTGTOTA'=>$SCTGTOTA,'SCTCSTST'=>$SCTCSTST,'SCTEALMA'=>$SCTEALMA,'SCTEPDCA'=>$SCTEPDCA,'SCTFECEM'=>$SCTFECEM);					
			}
		}else{
			$docuanubol[]=false;
		}	
		
		$datos=$this->datos_emisor($codcia,$fecha);
		$tde=$datos['tde'];
		$rsnrorca=$this->facturacionsendbajaws_db2_model->cuentadocurc($codcia,$fecha);
		$numtotalbola=($rsnrorca!=false)?odbc_result($rsnrorca, 1):0;
		$numcont=$numtotalbola+1;
		$numbaja=str_pad($numcont, 5, '0', STR_PAD_LEFT);
		$filebaja="RC-".$fechan."-".$numbaja;
		$datos["numeracion"]=$filebaja;
		$datos["fechaEmision"]=$fechae;
		$datos["fechaReferencia"]=$fecha;
		$datos["docuanul"]=$docuanubol;
		$filejs=$this->armarjsonferc($datos);
		$time_end = microtime(true);
		$time_total = $time_end - $time_start;
		$filename=$datos["numeroDocId"].'-RC-'.$fechan.'-'.$numbaja;
		$time=["trc"=>$time_total,'tde'=>$tde];
		$dat=['filename'=>$filename,'filejs'=>$filejs,'docuanul'=>$docuanubol,'tdrc'=>$time];
		return $dat;
	}
	function genera_filejson_ra($data){
		$codcia=$data['codcia'];
		$nropdc=$data['nropdc'];
		$serie=$data['serie'];
		$correl=$data['correl'];
		$fecha=$data['fecha'];
		$tipdoc=$data['tipdoc'];
		$stsdoct=$data['stsdoct'];
		$codalm=$data['codalm'];
		$docref=$data['docref'];
		$time_start = microtime(true);
		$f= gmdate("d-m-Y", time() - 18000);
		$dd=substr($f, 0,2);
		$dm=substr($f, 3,2);
		$da=substr($f, 6,4);
		$fechan=$da.$dm.$dd;
		$fechae=$da.'-'.$dm.'-'.$dd;
		$datos=$this->datos_emisor($codcia,$fecha);
		$tde=$datos['tde'];
		$rsnrorca=$this->facturacionsendbajaws_db2_model->cuentadocura($codcia,$fecha);
		$numtotalanu=($rsnrorca!=false)?odbc_result($rsnrorca, 1):0;
		$numcont=$numtotalanu+1;
		$numbaja=str_pad($numcont, 5, '0', STR_PAD_LEFT);
		$filebaja="RA-".$fechan."-".$numbaja;
		$datos["numeracion"]=$filebaja;
		$datos["fechaEmision"]=$fechae;
		$datos["numeroItem"]="1";
		$datos["fechaReferencia"]= $fecha;
		$datos["tipoComprobanteItem"]= $tipdoc;
		$datos["serieItem"]= $serie;
		$datos["correlativoItem"]= $correl;
		$datos["motivoBajaItem"]= "CANCELADO";
		$docbj='RA';
		$filename=$datos["numeroDocId"].'-RA-'.$fechan.'-'.$numbaja;
		$filejs=$this->armarjsonfera($datos);
		$time_end = microtime(true);
		$time_total = $time_end - $time_start;
		$time=["tra"=>$time_total,'tde'=>$tde];
		$dat=['filename'=>$filename,'filejs'=>$filejs,'tdra'=>$time];
		return $dat;

	}
	
	function send_webservicefa_baja($data){
		;
		$filecontent=base64_encode($data['filejs']);

		$SCTECIAA=$data['codcia'];
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
		$datos_ws=datos_webserviceBaja($ip);
		$url=$datos_ws['url']; 
		$urlref=$datos_ws['urlref'];
		$filename=$data['file'].'.json';
		$documentofe=array("customer"=>array("username"=>$usuario,"password"=>$clave),"fileName"=>$filename,"fileContent"=>$filecontent);
		$json=new Services_JSON();
		$documentfe=$json->encode($documentofe);

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
		
		$docufersl=json_decode($documentofersl);
		$ticket=($docufersl->responseCode!='98')?'':$docufersl->ticket;
		$responseCode=$docufersl->responseCode;
		$responseContent=$docufersl->responseContent;
		$adicional='filejs: '.$data['filejs'].' file:'.$data['file'].' docfe:'.$documentfe;
		$rptws=['ticket'=>$ticket,'responseCode'=>$responseCode,'responseContent'=>$responseContent,'adi'=>$adicional];
		return $rptws;	
			
	}

	function upatestsdoc_anu($info){
		$upfecli='';
		$stfecli='';
		$numfi='';
		$result='';
		$stfecli='';
		$rpt='';
		$rsl='';
		$ESTADO='';
		$SCTCSTSB='';
		$SCTESUCA=$info['SCTESUCA'];			
		$SCTECIAA=$info['SCTECIAA'];
		$SCTEPDCA=$info['SCTEPDCA'];		
		$SCTESERI=$info['SCTESERI'];
		$SCTECORR=$info['SCTECORR'];		
		$SCTETDOC=$info['SCTETDOC'];
		$SCTFECEM=$info['SCTFECEM'];
		$SCTCSTST=$info['SCTCSTST'];
		$responseCode=$info['responseCode'];
		$responseContent=$info['responseContent'];	

		if($responseCode=='2324'){
			$ESTADO=($SCTCSTST=='A')?"A":"A";
			$SCTCSTSB=($ESTADO=="A")?"G":"";
			$gtfecli=$this->facturacionsendbajaws_db2_model->get_eventos_fe($SCTECIAA,$SCTEPDCA,$responseCode);

			$numfil=($gtfecli!=false)?odbc_result($gtfecli, 1):0;
			if($numfil==0){
				$stfecli=$this->facturacionsendbajaws_db2_model->set_eventos_fe($SCTECIAA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCode,$responseContent,$SCTESUCA);				
				$upfecli=$this->facturacionsendbajaws_db2_model->update_fe_tramaa($SCTECIAA,$SCTEPDCA,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB);
				
			}else{
				$upfecli=$this->facturacionsendbajaws_db2_model->update_fe_tramaa($SCTECIAA,$SCTEPDCA,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB);				
			}
			
		}elseif(($responseCode=='0')||($responseCode=='00')){			
			
			$ESTADO=(($responseCode=='0')&&($SCTCSTST=='A'))?"I":"I";
			$SCTCSTSB=($ESTADO=="N")?"E":"A";
			$upfecli=$this->facturacionsendbajaws_db2_model->update_fe_traman($SCTECIAA,$SCTEPDCA,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB);
			$stfecli=$this->facturacionsendbajaws_db2_model->set_eventos_fe($SCTECIAA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCode,$responseContent,$SCTESUCA);		
			$rpt=true;
		}else{
			$ESTADO=(($SCTCSTST=='N')&&(($responseCode=='0')||($responseCode=='00')||($responseCode='099')||($responseCode='098')))?"I":'N';
			$SCTCSTSB=($ESTADO=="N")?"E":"A";
			$gtfecli=$this->facturacionsendbajaws_db2_model->get_eventos_fe($SCTECIAA,$SCTEPDCA,$responseCode);
			$numfil=($gtfecli!=false)?odbc_result($gtfecli, 1):0;
			if($numfil==0){
				$upfecli=$this->facturacionsendbajaws_db2_model->update_fe_tramaa($SCTECIAA,$SCTEPDCA,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB);
				$stfecli=$this->facturacionsendbajaws_db2_model->set_eventos_fe($SCTECIAA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCode,$responseContent,$SCTESUCA);
			}else{
				$upfecli=$this->facturacionsendbajaws_db2_model->update_fe_tramaa($SCTECIAA,$SCTEPDCA,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB);				
			}
			$rpt=true;
		}
		return $rpt;
		
	}

	function optener_status_ticket($ticket,$SCTECIAA){


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
		$datos_ws=datos_webserviceTicket($ip);
		$url=$datos_ws['url'];
		$urlref=$datos_ws['urlref'];
		$consultastatustk=["user"=>array("username"=>$usuario,"password"=>$clave),"ticket"=>$ticket];
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
		$docufersl=json_decode($documentofersl);
		$rsp['codigo']=$docufersl->codigo;
		$rsp['mensaje']=$docufersl->mensaje;
		$rsp['statusCode']=$docufersl->statusCode;
		$rsp['responseCode']=$docufersl->responseCode;
		$rsp['responseMessage']=$docufersl->responseMessage;
		return $rsp;

	}

	function armarjsonfera($dato){
		$json=new Services_JSON();
		$armarjsonfera = ['comunicacionBaja'=>array(
			"IDE"=>array("numeracion"=>$dato['numeracion'], 
				"fechaEmision"=>$dato['fechaEmision']
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
				"correoElectronico"=>$dato['correoElectronico']
			),
			"CBR"=>array("fechaReferencia"=>$dato['fechaReferencia']
		),
			"DBR"=>array(array("numeroItem"=>$dato['numeroItem'],
				"tipoComprobanteItem"=>$dato['tipoComprobanteItem'],
				"serieItem"=>$dato['serieItem'],
				"correlativoItem"=>$dato['correlativoItem'],
				"motivoBajaItem"=>$dato['motivoBajaItem']
			)
		),	
		)
	];

	header('Content-type: text/x-json; UTF-8');
	header('Content-type: application/json; charset=utf-8');
	return json_encode($armarjsonfera,true);
}

function armarjsonferc($dato){

	$json=new Services_JSON();
	$docuanul=$dato['docuanul'];
	$det=array();
	$cont=0;
	for($i=0;$i<count($docuanul);$i++){
		$cont=$cont+1;
		$cad=$docuanul[$i];
		$det[]=array(
			"numeroItem"=>(string)$cont,
			"monedaItem"=>$cad['SCTCTMON'],
			"numeracionItem"=>$cad['SCTESERI'].'-'.$cad['SCTECORR'],
			"tipoComprobanteItem"=>$cad['SCTETDOC'],
			"numeroDocIdAdq"=>$cad['SCTCNRUC'],
			"tipoDocIdAdq"=>$cad['SCTETPDO'],				
			"estadoItem"=>"3",
			"importeTotal"=>$cad['SCTGTOTA'],
			"gravadas"=>array("codigo"=>'01',
				"totalVentas"=>$cad['SCTGNETO']
			),
			"totalImpuestos"=>array(array("idImpuesto"=>$cad['SCTCCIMP'],
				"montoImpuesto"=>$cad['SCTMTIMP']
			)),	
		);
	}
	$armarjsonferc = ['resumenComprobantes'=>array(
		"IDE"=>array("numeracion"=>$dato['numeracion'], 
			"fechaEmision"=>$dato['fechaEmision'],
			"fechaReferencia"=>$dato['fechaReferencia']
		),
		"EMI"=>array(
			"tipoDocId"=>$dato['tipoDocId'],
			"numeroDocId"=>$dato['numeroDocId'],
			"nombreComercial"=>$dato['nombreComercial'],
			"razonSocial"=>$dato['razonSocial'],
			"ubigeo"=>$dato['ubigeo'],
			"direccion"=>$dato['direccion']
		),

		"DET"=>$det	
	)
];

header('Content-type: text/x-json; UTF-8');
header('Content-type: application/json; charset=utf-8');
return json_encode($armarjsonferc,true);

}

function procesarbajafe_vn($data=null){
	$codcia=$this->session->userdata('codcia'); 
	$time_start = microtime(true);
	$flg='';
	$nropdc ='';
	$serie = '';
	$correl = '';
	$fecha='';
	$tipdoc = '';
	$stsdoct = '';
	$codalm= '';
	$docref='';
	$flg=($data==null)?'':$data['flg'];
	if($flg=='m'){
		$codcia=$data['codcia'];
		$nropdc =$data['nropdc'];
		$serie = $data['serie'];
		$correl = $data['correl'];
		$fecha=$data['fecha'];
		$tipdoc =$data['tipdoc'];
		$stsdoct = $data['stsdoct'];
		$codalm= $data['codalm'];
		$docref=$data['docref'];	
	}else{
		$codcia=$this->session->userdata('codcia'); 
		$nropdc = $this->input->post('nropdc', true);
		$serie = $this->input->post('serie', true);
		$correl = $this->input->post('corr', true);
		$fecha=$this->input->post('fecha', true);
		$tipdoc = $this->input->post('tipdoc', true);
		$stsdoct = $this->input->post('stsdoct', true);
		$codalm= $this->input->post('codalm', true);
		$docref='';
	}
	$datosc['codcia'] = $codcia;
	$datosc['nropdc'] = $nropdc;
	$datosc['serie'] = $serie;
	$datosc['correl'] = $correl;			
	$datosc['fecha'] = $fecha;
	$datosc['tipdoc'] = $tipdoc;
	$datosc['stsdoct']=$stsdoct ;
	$datosc['codalm']=$codalm;
	$datosc['docref']=$docref;
	$filebaja='';
	$data='';
	$documentofe='';
	$sendfe='';
	$result="";
	$docuanubol=array();
	$datos='';
	$filejs='';
	$numbaja='';
	$resultprintfe='';	
	$docufersl='';
	$http_status='';
	$http_statuscod='';
	$docufeerror='';
	$update_data='';
	$rsltan0='';
	$rsltan1='';
	$rsltan2='';
	$rsltan3='';
	$rsltan4='';
	$documentofe='';
	$docufersl='';
	$http_status='';
	$http_statuscod='';
	$docufeerror='';
	$rsltan0='';
	$resulta='';

	$rspta='';

	$f= gmdate("d-m-Y", time() - 18000);
	$dd=substr($f, 0,2);
	$dm=substr($f, 3,2);
	$da=substr($f, 6,4);
	$fechan=$da.$dm.$dd;	
	if($stsdoct=="N"){
		$dat['SCTESUCA']=$codalm; 
		$dat['SCTECIAA']=$codcia;
		$dat['SCTEPDCA']=$nropdc;
		$dat['SCTESERI']=$serie;
		$dat['SCTECORR']=$correl;
		$dat['SCTETDOC']=$tipdoc;
		$dat['SCTFECEM']=$fecha;						
		$dat['SCTCSTST']=$stsdoct;

		$SCTECIAA=$codcia;
		$SCTEPDCA=$nropdc;
		$responseCode='98';
		$SACODSUC=$codalm;
		$gtfeev=$this->facturacionsendbajaws_db2_model->get_ticket_eventos_fe($SCTECIAA,$SCTEPDCA,$responseCode,$SACODSUC);
		$SACODRPT=trim(odbc_result($gtfeev, 1));
		$SAMSGRPT=trim(odbc_result($gtfeev, 2));
		$dataget = explode("##", $SAMSGRPT);
		$ticket=$dataget[0];
		$msj = $dataget[1];
		$dataticket=$this->optener_status_ticket($ticket,$codcia);
		$responseCodertk=$dataticket['responseCode'];		
		if($responseCodertk=='0'){					
			$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
			$dat['responseContent']=$dataticket['responseMessage'];
			$rsltan0=$this->upatestsdoc_anu($dat);
		}else{
			$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
			$dat['responseContent']=$dataticket['responseMessage'];
			$rsltan0=$this->upatestsdoc_anu($dat);
		}
		$rspta=['d1'=>$rsltan0];
	}else{
		$rsl='';
		$docuanubol='';
		$time='';
		$tdf='';
		$rpt='';
		$rptrg='';
		if((($tipdoc=='03')&&($docref=='0'))||(($tipdoc=='07')&&($docref=='3'))||(($tipdoc=='08')&&($docref=='3'))){
			$da=$this->genera_filejson_ra($datosc);
			$file=$da['filename'];
			$filejs=$da['filejs'];
			$docuanubol=$da['docuanul'];
			$tdf=$da['tdrc'];
			$time=["tf"=>$tdf["tdrc"],'tde'=>$tdf["tde"]];

		}else{	
			$da=$this->genera_filejson_ra($datosc);
			$file=$da['filename'];
			$filejs=$da['filejs'];
			$tdf=$da['tdra'];
			$time=["tf"=>$tdf["tdra"],'tde'=>$tdf["tde"]];
		}

		$filesjs=($filejs=='')?'':$filejs;

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
			$datos_ws=datos_webserviceBaja($ip);
			$url=$datos_ws['url']; 
			$urlref=$datos_ws['urlref'];
			$filename=$file.'.json';
			$documentofe=array("customer"=>array("username"=>$usuario,"password"=>$clave),"fileName"=>$filename,"fileContent"=>$filecontent);
			$json=new Services_JSON();
			$documentfe=$json->encode($documentofe);					
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
			$rspta=['d1'=>$filename,'d2'=>$documentofe,'d3'=>$filesjs];
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
			$docufersl=json_decode($documentofersl);
			$ticket=$docufersl->ticket;
			$responseCode=$docufersl->responseCode;			
			if($responseCode=='98'){
				if((($tipdoc=='03')&&($docref=='0'))||(($tipdoc=='07')&&($docref=='3'))||(($tipdoc=='08')&&($docref=='3'))){
					$rptrg3=array();$rp3=array();

					for($i=0;$i<=count($docuanubol);$i++){
						$cad=$docuanubol[$i];
						$dat['SCTESUCA']=$cad['SCTEALMA']; 
						$dat['SCTECIAA']=$cad['SCTECIAA'];
						$dat['SCTEPDCA']=$cad['SCTETPDO'];
						$dat['SCTESERI']=$cad['SCTESERI'];
						$dat['SCTECORR']=$cad['SCTECORR'];
						$dat['SCTETDOC']=$cad['SCTETDOC'];
						$dat['SCTFECEM']=$fecha;						
						$dat['SCTCSTST']=$cad['SCTCSTST'];
						$dat['responseCode']=$docufersl->responseCode;
						$dat['responseContent']=$docufersl->ticket.'##'.$docufersl->responseContent;
						$rptrg=$this->upatestsdoc_anu($dat);
						$dataticket=$this->optener_status_ticket($ticket,$cad['SCTECIAA']);
						$responseCodertk=$dataticket['responseCode'];
						if($responseCodertk=='0'){
							$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
							$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
							$rpt=$this->upatestsdoc_anu($dat);
						}else{
							$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
							$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
							$rpt=$this->upatestsdoc_anu($dat);
						}
						$rptrg3[]=array('rptrg3'=>$rptrg);
						$rp3[]=array('rpt3'=>$rpt);

					}
					$data=array('rptrg'=>$rptrg3,'rpt'=>$rpt3);
					$rsl=['d'=>'t031','data'=>$data];

				}else{

					$dat['SCTESUCA']=$codalm;
					$dat['SCTECIAA']=$codcia;
					$dat['SCTEPDCA']=$nropdc;
					$dat['SCTESERI']=$serie;
					$dat['SCTECORR']=$correl;
					$dat['SCTETDOC']=$tipdoc;
					$dat['SCTFECEM']=$fecha;
					$dat['responseCode']=$docufersl->responseCode;
					$dat['responseContent']=$docufersl->ticket.'##'.$docufersl->responseContent;
					$dat['SCTCSTST']=$stsdoct;					

					$rptrg=$this->upatestsdoc_anu($dat);
					$dataticket=$this->optener_status_ticket($ticket);
					$responseCodertk=$dataticket['responseCode'];

					if($responseCodertk=='0'){
						$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
						$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
						$rpt=$this->upatestsdoc_anu($dat);
					}else{

						$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
						$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
						$rpt=$this->upatestsdoc_anu($dat);
					}

					$rsl=['d'=>'t011','data'=>$rptrg.''.$rpt];
				}

			}else{

				if((($tipdoc=='03')&&($docref=='0'))||(($tipdoc=='07')&&($docref=='3'))||(($tipdoc=='08')&&($docref=='3'))){
					$rptrg3=array();$rp3=array();
					for($i=0;$i<=count($docuanubol);$i++){
						$cad=$docuanubol[$i];
						$dat['SCTESUCA']=$cad['SCTEALMA'];
						$dat['SCTECIAA']=$dat['SCTECIAA'];
						$dat['SCTEPDCA']=$cad['SCTETPDO'];
						$dat['SCTESERI']=$cad['SCTESERI'];
						$dat['SCTECORR']=$cad['SCTECORR'];
						$dat['SCTETDOC']=$cad['SCTETDOC'];
						$dat['SCTFECEM']=$fecha;
						$dat['responseCode']=$docufersl->responseCode;
						$dat['responseContent']=$docufersl->ticket.'##'.$docufersl->responseContent.'##'.$filebaja;						
						$dat['SCTCSTST']=$cad['SCTCSTST'];
						$rptrg=$this->upatestsdoc_anu($dat);
						$dataticket=$this->optener_status_ticket($ticket,$cad['SCTECIAA']);
						$responseCodertk=$dataticket['responseCode'];
						if($responseCodertk=='0'){
							$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
							$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
							$rpt=$this->upatestsdoc_anu($dat);
						}else{
							$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
							$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
							$rpt=$this->upatestsdoc_anu($dat);
						}
						$rptrg3[]=array('rptrg3'=>$rptrg);
						$rp3[]=array('rpt3'=>$rpt);

					}
					$data=array('rptrg'=>$rptrg3,'rpt'=>$rpt3);
					$rsl=['d'=>'t032','data'=>$data];

				}else{

					$dat['SCTESUCA']=$codalm;
					$dat['SCTECIAA']=$codcia;
					$dat['SCTEPDCA']=$nropdc;
					$dat['SCTESERI']=$serie;
					$dat['SCTECORR']=$correl;
					$dat['SCTETDOC']=$tipdoc;
					$dat['SCTFECEM']=$fecha;
					$dat['responseCode']=$docufersl->responseCode;
					$dat['responseContent']=$docufersl->ticket.'##'.$docufersl->responseContent.'##'.$filebaja;
					$dat['SCTCSTST']=$stsdoct;						
					$rptrg=$this->upatestsdoc_anu($dat);
					$dataticket=$this->optener_status_ticket($ticket,$codcia);
					$responseCodertk=$dataticket['responseCode'];
					if($responseCodertk=='0'){
						$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
						$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
						$rpt=$this->upatestsdoc_anu($dat);
					}else{
						$dat['responseCode']=$dataticket['codigo'].$dataticket['statusCode'];
						$dat['responseContent']=$dataticket['responseMessage'].' '.$filebaja;
						$rpt=$this->upatestsdoc_anu($dat);
					}

					$rsl=['d'=>'t012','data'=>$rptrg.''.$rpt];
				}

			}

			$resulta=['d1'=>$documentofersl,'d2'=>$http_statuscod,'d3'=>$http_status,'D8'=>$dat,'d9'=>$filename,'d6'=>$rsl,'d7'=>$time];
				//$resulta=['d1'=>$documentofersl,'d2'=>$http_statuscod,'d3'=>$http_status,'d4'=>$dataticket,'d5'=>$rsltan0.' '.$rsltan1.' '.$rsltan2.' '.$rsltan3.' '.$rsltan4,'D8'=>$dat,'d9'=>$filename,'d6'=>$rsl];	

			
			$ESTADO='';
			$upfecli='';
			$stfecli='';

		}else{
			$documentofe='';
			$docufersl='';
			$http_status='';
			$http_statuscod='';
			$docufeerror='';
		}

	}

	$time_end = microtime(true);
	$time_total = $time_end - $time_start;
	//print_r($resulta);
	return false;

}

function get_vereventosbajafe(){

	$valid = $this->session->userdata('validated');
	$codcia=$this->session->userdata('codcia'); 
	$body=array();
	if ($valid == TRUE) { 
		$nropdc=$this->input->post('nropdc', true);
		$nrosere=$this->input->post('nrosere', true);
		$nrocor=$this->input->post('nrocor', true);
		$tipdoc=$this->input->post('tipdoc', true);
		$rsdocxgj=$this->facturacionsendbajaws_db2_model->get_vereventosbajafe($codcia,$nropdc,$nrosere,$nrocor,$tipdoc);	
		if(($tipdoc=='08')||($tipdoc=='07')){
			$rsdocref=$this->facturacionsendbajaws_db2_model->get_verdocumentosrefncnd($codcia,$nropdc,$nrosere,$nrocor,$tipdoc);
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
function get_verticketbajafe(){
	$codcia=$this->session->userdata('codcia');
	$valid = $this->session->userdata('validated');
	$info='';
	$data='';
	if ($valid == TRUE) { 
		$SCTECIAA=$codcia;
		$responseCode='98';
		$SCTEPDCA=$this->input->post('nropdc', true);
		$SACODSUC=$this->input->post('codalm', true);
		$tipdoc=$this->input->post('tipdoc', true);
		$SACODSUC=$this->input->post('codalm', true);
		$gtfeev=$this->facturacionsendbajaws_db2_model->get_ticket_eventos_fe($SCTECIAA,$SCTEPDCA,$responseCode);			
		if($gtfeev!=false){
			$SACODRPT=trim(odbc_result($gtfeev, 1));
			$SAMSGRPT=trim(odbc_result($gtfeev, 2));
			if(($SACODRPT!='')&&($SAMSGRPT!='')){		

				$dataget = explode("##", $SAMSGRPT);
				$ticket=$dataget[0];
				$dataticket=$this->optener_status_ticket($ticket,$SCTECIAA);				
				$responseCodertk=$dataticket['responseCode'];
				if($responseCodertk=="0"){					
					$info='Ticket N° '.$ticket.' '.$dataticket['codigo'].' '.$dataticket['responseCode'].' '.$dataticket['responseMessage'];	
				}else{
					$info=' Ticket N° '.$ticket.' '.$dataticket['codigo'].' '.$dataticket['responseCode'].' '.$dataticket['responseMessage'];	
				}				
				$data['msg']='Con Datos';
				$data['datos']=$info;
				$data['existe'] = 1;
			}else{
				$AN01='AN01';
				$rsltk=$this->facturacionsendbajaws_db2_model->get_ticket_eventos_fe($SCTECIAA,$SCTEPDCA,$AN01);
				$SACODRPT=trim(odbc_result($rsltk, 1));
				$SAMSGRPT=trim(odbc_result($rsltk, 2));
				$data['msg']='Con Datos';
				$data['datos']='Sin Ticket '.$SACODRPT.' '.$SAMSGRPT;
				$data['existe'] = 1;
			}				
		}else{
			$data['msg']='Sin Datos';
			$data['datos']=$info;
			$data['existe'] = 0;
		}
		$resulta = $data;
			//print_r($data);	
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($resulta);
	}else{
		redirect('loginin');	
	}
}

function set_darbajafeas400(){
	$fechadata=gmdate("d-m-Y H:i:s", time() - 18000);
	$valid = $this->session->userdata('validated');
	$codcia=$this->session->userdata('codcia');
	$info=array();
	if ($valid == TRUE) { 
		$ESTADO="I";
		$SCTCSTSB="A";
		$SCTECIAA=$codcia;
		$responseCode='AN01';
		$responseContent='Anulacion Manual mym desde web '.$fechadata ;
		$SCTEPDCA=$this->input->post('nropdc', true);
		$SCTESUCA=$this->input->post('codsuc', true);
		$SCTETDOC=$this->input->post('tipdoc', true);
		$SCTFECEM=$this->input->post('femi', true);
		$SCTESERI=$this->input->post('nrosere', true);
		$SCTECORR=$this->input->post('nrocor', true);						
		$upfecli=$this->facturacionsendbajaws_db2_model->update_fe_tramae($SCTECIAA,$SCTEPDCA,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB);
		if($upfecli!=0){
			$data['msg']='Con Datos';
			$data['datos']=' Se realizo Anulacion de documento en as400';
			$data['existe'] = 0;
			$stfecli=$this->facturacionsendbajaws_db2_model->set_eventos_fe($SCTECIAA,$SCTEPDCA,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCode,$responseContent,$SCTESUCA);
		}else{
			$data['msg']='Sin Datos';
			$data['datos']='Error';
			$data['existe'] = 1;
		}
		$result = $data;	
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($result);
	}else{
		redirect('loginin');	
	}

}

function mostraranulado_pdf($dato){
	$this->load->library('fpdfanu_lib');
	$dataget = explode("_", $dato);
	$codcia=$dataget[0];
	$datos['cia']=$dataget[0];
	$datos['fecha'] = $dataget[1];
	$datos['nropdc'] = $dataget[2];
	$datos['serie'] = $dataget[3];
	$datos['correl'] = $dataget[4];		
	$datos['titles']=$dataget[5];

	$rslstcia = $this->facturacionsendws_db2_model->listarCia($codcia);
	if($rslstcia!=false){
		$EUCODCIA = trim(odbc_result($rslstcia, 1));
		$EUDSCCOM = trim(odbc_result($rslstcia, 2));
		$EUDSCDES = trim(odbc_result($rslstcia, 3));
		$mymclieas400 = trim(odbc_result($rslstcia, 4));
	}
	$datos['codclimym']=$mymclieas400;
	$dato=$this->generadataanuladofe($datos);
	
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
	$cia=$EUCODCIA;
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

	$fpdf=new fpdfanu_lib('P','mm','A4',true,'UTF-8',false);
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
	$filename=$dato['numeroDocId'].'_'.$dato['codTipoDocumento'].'_'.$dato['numeracion'].'_'.'ANU';
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

//}else{

//}

	

}
function generadataanuladofe($datos){
	$codcia=$datos['cia'];
	$mymclieas400=$datos['codclimym'];  
	$data='';
	$filejs='';
	$documentofe='';
	$sendfe='';
	$result="";
	$detapdc='';
	$fe='';
	$documentofersl='';
	$rsdocxgj=$this->facturacionsendbajaws_db2_model->generadataanuladofe($datos);
	$numfil=odbc_num_rows($rsdocxgj);			
	if(($rsdocxgj!=false)||($numfil>0)){
		$SCTEPER='';
		$SCTEFEC='';
		$SCTECIAA='';
		$SCTESUCA='';
		$SCTEALMA='';
		$SCTEPDCA='';
		$SCTESERA='';
		$SCTECORA='';
		$SCTESERI='';
		$SCTECORR='';
		$SCTERZSO='';
		$SCTECODE='';
		$SCTECRUC='';
		$SCTEUBIG='';
		$SCTEDIRE='';
		$SCTENCOM='';
		$SCTETDOC='';
		$SCTETPDO='';
		$SCTVENDE='';
		$SCTCCLIE='';
		$SCTCNRUC='';
		$SCTCRZSO='';
		$SCTCDIRE='';
		$SCTCTMON='';
		$SCTCODAU='';
		$SCTCSUST='';
		$SCTCTPNO='';
		$SCTGNETO='';
		$SCTGGEXE='';
		$SCTGNEXO='';
		$SCTGIGV='';
		$SCTGTOTA='';
		$SCTFECEM='';
		$SCTCCIMP='';
		$SCTMTIMP='';
		$SCTTASAI='';
		$SCTCSTS='';
		$SCTCUSUR='';
		$SCTCFECR='';
		$SCTCHORR='';	
		$IWODCPRV='';
		$SCTIPFAC='';
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
			$NOMDEPAM='';
			$NOMPROVM='';
			$NOMDISTM='';
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
			$NOMDEPA='';
			$NOMPROV='';
			$NOMDIST='';
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
			$EIFECVCT='';
			$dtfvcli=$this->facturacionsendws_db2_model->buscar_fvpdc_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTCCLIE,$SCTETDOC);   
			while (odbc_fetch_row($dtfvcli)) {						
					//$EIFECVCT = trim(odbc_result($dtfvcli, 1));
			}
			$JQNROSER='';
			$JQNROCOR='';
			$dtgrcli=$this->facturacionsendws_db2_model->buscar_grpdc_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA);   
			while (odbc_fetch_row($dtgrcli)) {						
				$JQNROSER = trim(odbc_result($dtgrcli, 1));
				$JQNROCOR = trim(odbc_result($dtgrcli, 2));
			}
			$SDTNITEM='';$SDTCDART='';$SDTDSCAR='';$SDTCANTI='';$SDTUNIME='';$SDTPUSIG='';$SDTPUCIG='';$SDTCTIGV='';$SDTPIIGV='';$SDTCAFEC='';$SDTPTIGV='';$SDTPSITE='';				
			$datadet=array();

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

				$SDTSERRF='';
				$SDTCORRF='';
				$SDTTDREF='';

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
				$rsttipocambio='';
				if($SCTCTMON=='02'){
					$convtc=$SCTGTOTA/$tc;
					$rsttipocambio='$'.number_format($convtc,2,".",",");
				}else{
					$convtc=$SCTGTOTA*$tc;
					$rsttipocambio='S/ '.number_format($convtc,2,".",",");
				}
				$data['numeracion']=$SCTESERI.'-'.$SCTECORR;
				$data['fechaEmision']=$SCTFECEM;
				if(strlen($SCTCHORR)==8){
					$HR=substr($SCTCHORR, 0,2);
					$MN=substr($SCTCHORR, 2,2);
					$SG=substr($SCTCHORR, 4,2);
				}else{
					$HR=substr($SCTCHORR, 0,1);
					$MN=substr($SCTCHORR, 1,2);
					$SG=substr($SCTCHORR, 3,2);	
				}	
				$PYTXTADIPVH='';
				$placavh=$this->facturacionsendws_db2_model->buscar_placavh_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA);		
				while (odbc_fetch_row($placavh)) {						
					$PYTXTADIPVH = trim(odbc_result($placavh, 1));
				}
				$I6CODDAT='';$I6DSCDAT='';
				$rsmailc=$this->facturacionsendws_db2_model->buscar_correo_cliente_db2as400($SCTCCLIE);
				$I6CODDAT = trim(odbc_result($rsmailc, 1));
				$I6DSCDAT = trim(utf8_encode(odbc_result($rsmailc, 2)));
			}
			$stfedataanti='';
			$totalAnticipos=0.00;
			$SCTGTOTAA='';
			$infoAnticipo=array();
			
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
			$data['infoAnticipo']=$infoAnticipo;
			$data['totalAnticipos']=(string)$totalAnticipos;
			$DMAILC=($I6DSCDAT!='')?$I6DSCDAT:'';
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
			$mail=($DMAILC=='')?'':$DMAILC;
			$data['correoElectronicoc']=$mail;
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

		}else{
			$data='';
		}
		return $data;
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

}
?>