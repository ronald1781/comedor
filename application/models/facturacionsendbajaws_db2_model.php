<?php

class Facturacionsendbajaws_db2_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	function index(){

	}

 // +---------------------------------------------------------------------------+    
// |Listar Años de Documentos Anulados
// +---------------------------------------------------------------------------+       
	
	function listaaniobaja(){ 
		include("application/config/conexdb_db2.php");
		//MMYHREL0
		$codcia=$this->session->userdata('codcia');
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 1, 4)) AS anio FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='I' AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by anio asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data; 
		cerrar_odbc(); 
	}
// +---------------------------------------------------------------------------+    
// |Listar Tipo de Documentos Emetido Activos
// +---------------------------------------------------------------------------+        
	
	function listatpodocubaja(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql="select DISTINCT(a.YHTIPDOC) AS TIPODOCU,e.EUDSCCOR FROM LIBPRDDAT.MMYHREL0 a INNER JOIN LIBPRDDAT.MMEUREL0 e ON a.YHTIPDOC=e.EUCODELE WHERE a.YHTIPDOC!='' AND e.EUCODTBL='AG' and a.YHSTS='I' AND a.YHCODCIA='".$codcia."' and a.YHFECDOC>='20170101' order by TIPODOCU asc";		
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
		cerrar_odbc();
	}
// +---------------------------------------------------------------------------+    
// |Listar Serie de Documentos Emetidos Activos
// +---------------------------------------------------------------------------+        
	
	function listaseribaja(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');  
		$sql="select distinct(a.YHNUMSER) as SERIE FROM LIBPRDDAT.MMYHREL0 a WHERE a.YHSTS='I' AND a.YHCODCIA='".$codcia."' and a.YHFECDOC>='20170101' order by a.YHNUMSER asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;
		}        
		return $data; 
		cerrar_odbc();
	}

	// +---------------------------------------------------------------------------+    
// |Datos de Almacen y estado Para Anular Manualmente
// +---------------------------------------------------------------------------+        
	
	function get_almstsdoc($SCTEFEC,$SCTETDOC,$SCTEPDCA,$SCTESERI,$SCTECORR){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');    
		$sql ="select a.SCTEALMA,a.SCTCSTST,a.SCTTDREF FROM LIBPRDDAT.SNT_CTRAM a where a.SCTEFEC='".$SCTEFEC."' AND a.SCTCSTS='I' AND a.SCTCSTST='A' and a.SCTETDOC='".$SCTETDOC."' AND a.SCTEPDCA='".$SCTEPDCA."' and a.SCTESERI='".$SCTESERI."' and a.SCTECORR='".$SCTECORR."'";

		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {  
			$nr = odbc_num_rows($dato);
			$data = ($nr>0)?$dato:false;
		}        
		return $data;
		cerrar_odbc();
	}

	function cuentadocxanular($fecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$usuaper= $this->session->userdata('usuaper');
		$periodo='';
		$fechae='';
		if($fecha==''){
			$da=gmdate("Y", time() -18000);
			$dm=gmdate("m", time() -18000);
			$dd=gmdate("d", time() -18000);
			$fechae=$da.$dm.$dd;
			$periodo=$da.$dm;
		}else{
			$pa=substr($fecha,0,4);
			$pm=substr($fecha,4,2);
			$periodo=$pa.$pm;
			$fechae=$fecha;
		}
		//$fechae=(int)$fechae,AND a.YHCODCIA='10';
		$sql = "select count(b.SCTCFECB) as nroanu FROM LIBPRDDAT.MMYHREL0 a inner join LIBPRDDAT.SNT_CTRAM b on CASE WHEN a.YHTIPDOC='07' THEN (select IANROPDC FROM LIBPRDDAT.MMIAREP WHERE IACODCIA='10' AND IACODSUC=a.YHSUCDOC AND IANROREF=a.YHNROPDC) ELSE a.YHNROPDC END=b.SCTEPDCA and CASE WHEN a.YHTIPDOC='07' THEN 'F'||SUBSTRING(a.YHNUMSER,2) WHEN a.YHTIPDOC='08' THEN 'F'||SUBSTRING(a.YHNUMSER,2) ELSE a.YHNUMSER END=b.SCTESERI and a.YHNUMCOR=b.SCTECORR and a.YHTIPDOC=b.SCTETDOC WHERE a.YHSTS='I' and a.YHFECDOC ".$fechae." and b.SCTCSTS='A'";	
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());

		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}  
		return $data;  
	}

	// +---------------------------------------------------------------------------+    
// |Listar Documentos Para Anular Manualmente
// +---------------------------------------------------------------------------+        
	
	function get_anulacionmanual($limit,$fecha){
		include("application/config/conexdb_db2.php");

		if($limit>0){ 
			$sql ="select b.SCTEFEC,b.SCTECIAA,b.SCTESUCA,b.SCTEPDCA,b.SCTETDOC,b.SCTESERI,b.SCTECORR,b.SCTEALMA,b.SCTCSTST,b.SCTTDREF FROM LIBPRDDAT.MMYHREL0 a inner join LIBPRDDAT.SNT_CTRAM b on CASE WHEN a.YHTIPDOC='07' THEN (select IANROPDC FROM LIBPRDDAT.MMIAREP WHERE IACODCIA='10' AND IACODSUC=a.YHSUCDOC AND IANROREF=a.YHNROPDC) ELSE a.YHNROPDC END=b.SCTEPDCA and CASE WHEN a.YHTIPDOC='07' THEN 'F'||SUBSTRING(a.YHNUMSER,2) WHEN a.YHTIPDOC='08' THEN 'F'||SUBSTRING(a.YHNUMSER,2) ELSE a.YHNUMSER END=b.SCTESERI and a.YHNUMCOR=b.SCTECORR and a.YHTIPDOC=b.SCTETDOC WHERE a.YHSTS='I'  and a.YHFECDOC ".$fecha." and b.SCTCSTS='A' limit ".$limit;		
			$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = false;
			} else {  
				$nr = odbc_num_rows($dato);
				$data = ($nr>0)?$dato:false;
			}
		}else {
			$data = false;
		}       
		return $data;

	}

	// +---------------------------------------------------------------------------+    
// |Listar Documentos Para Anular Manualmente unitario
// +---------------------------------------------------------------------------+        
	
	function get_anulacionmanual_unitario($data){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
//se cambio estado
		$fecha=$data['fecha'];
$query="a.YHFECDOC ".$fecha." and b.SCTEPDCA=".$data['nropdc']." and b.SCTETDOC='".$data['tipdoc']."' and b.SCTESERI='".$data['serie']."' and b.SCTECORR='".$data['correl']."' and b.SCTEALMA='".$data['codalm']."' and b.SCTCSTST='".$data['stsdoct']."'";
		
			$sql ="select b.SCTEFEC,b.SCTESUCA,b.SCTEPDCA,b.SCTETDOC,b.SCTESERI,b.SCTECORR,b.SCTEALMA,b.SCTCSTST,b.SCTTDREF,b.SCTECIAA FROM LIBPRDDAT.MMYHREL0 a inner join LIBPRDDAT.SNT_CTRAM b on CASE WHEN a.YHTIPDOC='07' THEN (select IANROPDC FROM LIBPRDDAT.MMIAREP WHERE IACODCIA='10' AND IACODSUC=a.YHSUCDOC AND IANROREF=a.YHNROPDC) ELSE a.YHNROPDC END=b.SCTEPDCA and CASE WHEN a.YHTIPDOC='07' THEN 'F'||SUBSTRING(a.YHNUMSER,2) WHEN a.YHTIPDOC='08' THEN 'F'||SUBSTRING(a.YHNUMSER,2) ELSE a.YHNUMSER END=b.SCTESERI and a.YHNUMCOR=b.SCTECORR and a.YHTIPDOC=b.SCTETDOC WHERE a.YHSTS='I' AND a.YHCODCIA='".$codcia."' and b.SCTCSTS='A' AND  ".$query;
			$datos = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
			if (!$datos) {
				$dato = false;
			} else {  
				$nr = odbc_num_rows($datos);
				$dato = ($nr>0)?$datos:false;
			}
		       
		return $dato;

	}
	// +---------------------------------------------------------------------------+    
// |Listar detalle de Documentos Anulados
// +---------------------------------------------------------------------------+        
	//select YHSUCDOC,YHTIPDOC,YHNROPDC,YHNUMSER,YHNUMCOR,YHSTS,YHCODSUC FROM LIBPRDDAT.MMYHREL0 a WHERE a.YHSTS='I' AND a.YHCODCIA='10' and a.YHFECDOC  between '20190704' and '20190704'  order by a.YHSUCDOC asc
	function detalledocumentoanulado($datas){
		include("application/config/conexdb_db2.php");
		$query='';

		if(($datas['seltd']=='')&&($datas['selseri']=='')){
			$query="";
		}elseif(($datas['seltd']!='')&&($datas['selseri']=='')){
			$query=" and a.YHTIPDOC='".$datas['seltd']."'";
		}elseif(($datas['seltd']!='')&&($datas['selseri']=='')){
			$query=" and a.YHTIPDOC='".$datas['seltd']."'";
		}elseif(($datas['seltd']!='')&&($datas['selseri']!='')){
			$query=" and a.YHTIPDOC='".$datas['seltd']."' and a.YHNUMSER='".$datas['selseri']."'";
		}else{
			$query=" and a.YHNUMSER='".$datas['selseri']."'";
		}
		$codcia=$this->session->userdata('codcia');    
		$sql ="select YHSUCDOC,YHTIPDOC,YHNROPDC,YHNUMSER,YHNUMCOR,YHSTS,YHCODSUC FROM LIBPRDDAT.MMYHREL0 a WHERE a.YHSTS='I' AND a.YHCODCIA='".$codcia."' and a.YHFECDOC ".$datas['fechaa']." ".$query." order by a.YHSUCDOC asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
//print_r($sql);
		if (!$dato) {
			$data = FALSE;
		} else {  
			$nr = odbc_num_rows($dato);
			$data = ($nr>0)?$dato:false;
		}        
		return $data;
		
	}
	

	// +---------------------------------------------------------------------------+    
// |Listar detalle de Documentos Anulados
// +---------------------------------------------------------------------------+        
	
	function get_documentoanulado($fecha){
		include("application/config/conexdb_db2.php");
		
		$sql ="select YHSUCDOC,YHTIPDOC,YHNROPDC,YHNUMSER,YHNUMCOR,YHSTS,YHCODSUC FROM LIBPRDDAT.MMYHREL0 a WHERE a.YHSTS='I' AND a.YHCODCIA='10' and a.YHFECDOC>='".$fecha."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {  
			$nr = odbc_num_rows($dato);
			$data = ($nr>0)?$dato:false;
		}        
		return $data;
		
	}

// +---------------------------------------------------------------------------+    
// |Validar Documento Anulado en CB Factura y boleta
// +---------------------------------------------------------------------------+        
	function cbdocumentoanulado($fecha,$nropdc,$suc,$serie,$correl){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$correle=substr($correl, 0,1);
		$sini=substr($serie, 0,1);
		$nini=substr($serie, 2,2);
		$serie=$sini.$nini;
		$sql ="select CBSTS,CBSTSPDO,CBCODMON FROM LIBPRDDAT.MMCBREP WHERE CBCODCIA='".$codcia."' AND CBCODSUC='".$suc."' and CBFECDOC ".$fecha." AND CBNROSER='".$serie."' AND CBNROCOR='".$correle."' AND CBNROPDC =".$nropdc;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = false;
		} else {            
			$data = $dato;  
		}        
		return $data; 
		cerrar_odbc();
	}
// +---------------------------------------------------------------------------+    
// |Validar Documento Anulado en IA Nota de credito cabecera
// +---------------------------------------------------------------------------+        
	function iadocumentoanulado($nroref,$suc){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$sql ="select IANROPDC FROM LIBPRDDAT.MMIAREP WHERE IACODCIA='".$codcia."' AND IACODSUC='".$suc."' AND IANROREF =".$nroref;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = false;
		} else {            
			$data = $dato;  
		}        
		return $data; 
		cerrar_odbc(); 
	}
// +---------------------------------------------------------------------------+    
// |Validar Documento Anulado en FX Nota de credito
// +---------------------------------------------------------------------------+       
	function fxdocumentoanulado($fecha,$nropdc,$suc,$serie,$correl,$tipodoc){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$sini=substr($serie, 0,1);
		$nini=substr($serie, 2,2);
		$serie=$sini.$nini;
		$sql ="select FXSTS FROM LIBPRDDAT.MMFXREP WHERE FXCODCIA='".$codcia."' AND FXCODSUC='".$suc."' and FXFECDVL ".$fecha." AND FXNROPDC =".$nropdc." AND FXNROSER='".$serie."' AND FXNROCOR='".$correl."' AND FXTIPDOC='".$tipodoc."'"; 
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
		cerrar_odbc();  
	}
// +---------------------------------------------------------------------------+    
// |Validar Documento Anulado en JR Nota de debito
// +---------------------------------------------------------------------------+        
	function jrdocumentoanulado($fecha,$nropdc,$suc,$tipdoc,$serie,$correl){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$sini=substr($serie, 0,1);
		$nini=substr($serie, 2,2);
		$serie=$sini.$nini;
		$Cini=substr($correl, 0,1);
		$Cfin=substr($correl, 1,7);
		$correl=$Cfin;
		$sql ="select JRSTS FROM LIBPRDDAT.MMJRREP WHERE JRCODCIA='".$codcia."' AND JRCODSUC='".$suc."' and JRFECEMI ".$fecha." AND JRNRODOC =".$nropdc." AND JRNROSER='".$serie."' AND JRNROCOR='".$correl."' AND JRTIPDOC='".$tipdoc."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
		cerrar_odbc();  
	}
// +---------------------------------------------------------------------------+    
// |Documentos Anulados en Trama 
// +---------------------------------------------------------------------------+        
	function stdocumentoanulado($fecha,$nropdc,$suc,$tipdoc,$serie,$correl){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$sini=substr($serie, 0,1);
		$nini=substr($serie, 2,2);
		$serie=$sini.$nini;
		$Cini=substr($correl, 0,1);
		$Cfin=substr($correl, 1,7);
		$correl=$Cfin;
		$sql ="select SCTESERI, SCTECORR, SCTCCLIE, SCTCRZSO, SCTCTMON, SCTGTOTA, SCTCSTS,SCTFECEM,SCTETDOC,SCTCSTST,SCTEALMA,SCTECIAA,SCTEFEC FROM LIBPRDDAT.SNT_CTRAM WHERE SCTECIAA='".$codcia."' AND SCTESUCA='".$suc."' and SCTEFEC ".$fecha." AND SCTEPDCA =".$nropdc." AND SCTETDOC='".$tipdoc."' AND SCTESERA='".$serie."' AND SCTECORA='".$correl."'" ;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		//print_r($sql);
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data; 
		cerrar_odbc(); 
	}

// +---------------------------------------------------------------------------+    
// |Actualizacion de estado Documentos Anulados en Trama a enviado E
// +---------------------------------------------------------------------------+        
	function upstdocumentoanulado($fecha,$nropdc,$suc,$tipdoc,$serie,$correl){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$usuaper= $this->session->userdata('usuaper'); 
		$pgm='BAJADOCWEB';
		$sini=substr($serie, 0,1);
		$nini=substr($serie, 2,2);
		$serie=$sini.$nini;
		$Cini=substr($correl, 0,1);
		$Cfin=substr($correl, 1,7);
		$correl=$Cfin;	
		$da=gmdate("Y", time() -18000);
		$dm=gmdate("m", time() -18000);
		$dd=gmdate("d", time() -18000);
		$fechae=$da.$dm.$dd;
		$hh=gmdate("H", time() -18000);
		$hm=gmdate("i", time() -18000);
		$hs=gmdate("s", time() -18000);
		$horae=$hh.$hm.$hs;
		$sql ="update LIBPRDDAT.SNT_CTRAM SET SCTCSTS='E', SCTCUSUE='".$usuaper."', SCTCFECE='".$fechae."',SCTCHORE='".$horae."',SCTCPGME='".$pgm."' WHERE SCTCSTS='A' AND SCTECIAA='".$codcia."' AND SCTESUCA='".$suc."' and SCTEFEC=".$fecha." AND SCTEPDCA =".$nropdc." AND SCTETDOC='".$tipdoc."' AND SCTESERA='".$serie."' AND SCTECORA='".$correl."'" ;
		$resl=odbc_exec($dbconect,$sql);
		if(!$resl){
			$result=odbc_error($dbconect).":".odbc_errormsg($dbconect)."\n";
		}else{
			$result=odbc_num_rows($resl);
		}
		
		return $result;
		odbc_close($dbconect);
	}

	// +---------------------------------------------------------------------------+    
// |Actualizacion de estado Documentos Anulados en Trama a anulado I
// +---------------------------------------------------------------------------+        
	function upstdocumentoanuladoconfirma($fecha,$nropdc,$suc,$tipdoc,$serie,$correl){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$usuaper= $this->session->userdata('usuaper'); 
		$pgm='BAJADOCWEB';
		$sini=substr($serie, 0,1);
		$nini=substr($serie, 2,2);
		$serie=$sini.$nini;
		$Cini=substr($correl, 0,1);
		$Cfin=substr($correl, 1,7);
		$correl=$Cfin;		
		$da=gmdate("Y", time() -18000);
		$dm=gmdate("m", time() -18000);
		$dd=gmdate("d", time() -18000);
		$fechae=$da.$dm.$dd;
		$hh=gmdate("H", time() -18000);
		$hm=gmdate("i", time() -18000);
		$hs=gmdate("s", time() -18000);
		$horae=$hh.$hm.$hs;
		$sql ="update LIBPRDDAT.SNT_CTRAM SET SCTCSTS='I', SCTCUSUE='".$usuaper."', SCTCFECE='".$fechae."',SCTCHORE='".$horae."',SCTCPGME='".$pgm."' WHERE SCTCSTS='E' AND SCTECIAA='".$codcia."' AND SCTESUCA='".$suc."' and SCTEFEC=".$fecha." AND SCTEPDCA =".$nropdc." AND SCTETDOC='".$tipdoc."' AND SCTESERA='".$serie."' AND SCTECORA='".$correl."'" ;
		$resl=odbc_exec($dbconect,$sql);
		if(!$resl){
			$result=odbc_error($dbconect).":".odbc_errormsg($dbconect)."\n";
		}else{
			$result=odbc_num_rows($resl);
		}
		odbc_close($dbconect);
		return $result;

	}

	// +---------------------------------------------------------------------------+    
// |Documentos en Trama Para Generar jsonpara ws
// +---------------------------------------------------------------------------+     
	function documentoxgenerarjson($datas){

		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$usuaper= $this->session->userdata('usuaper');
		$codcia=$datap['codcia'];
		$fecha=$datas['fecha'];
		$serie=$datas['serie'];
		$correl=$datas['correl'];
		$nropdc=$datas['nropdc'];		
		$tipodoc=$datas['tipdoc'];
		$stsdoct=$datas['stsdoct'];
		$docref=$datas['docref'];
		$data='';
		$sql='';
		if($fecha!=''){	
			if(($serie!='')||($correl!='')||($nropdc!='')||($tipodoc=='01')){
				$query=" and a.SCTESERI='".$serie."' and a.SCTECORR='".$correl."' and a.SCTEPDCA='".$nropdc."' and a.SCTETDOC='".$tipodoc."' and a.SCTCSTST='".$stsdoct."'";	 
			}else if(($tipodoc=='03')&&($docref=='0')){
				$query=" and a.SCTETDOC='".$tipodoc."' and a.SCTCSTST='".$stsdoct."'";	 
			}else if((($tipodoc=='07')&&($docref=='3'))||(($tipodoc=='08')&&($docref=='3'))){
				$query=" and a.SCTETDOC='".$tipodoc."' and a.SCTCSTST='".$stsdoct."'";	 
			}
			else{
				$query="";
			}	
			$sql ="select a.SCTEFEC,a.SCTCTMON,a.SCTESERI,a.SCTECORR,a.SCTETDOC,a.SCTETPDO,a.SCTCNRUC,a.SCTGNETO,a.SCTCCIMP,a.SCTMTIMP,a.SCTGTOTA,a.SCTTDREF,a.SCTCSTST,a.SCTEALMA,a.SCTEPDCA,a.SCTFECEM FROM LIBPRDDAT.SNT_CTRAM a where a.SCTECIAA='".$codcia."' and a.SCTCSTS IN ('I','A') and a.SCTFECEM='".$fecha."' ".$query;

			$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = false;
			} else {            
				$data = $dato; 
			}   
		}else{
			$data=false;	
		} 

		return $data; 

	}
	function datos_emisor($codcia,$fecha){

		include("application/config/conexdb_db2.php");

		if($fecha!=''){				
			$sql ="select distinct(c.SCTERZSO) as SCTERZSO,c.SCTECRUC,c.SCTEUBIG,c.SCTEDIRE,c.SCTENCOM,c.SCTFECEM from LIBPRDDAT.SNT_CTRAM c where c.SCTFECEM='".$fecha."' and c.SCTECIAA='".$codcia."'";
			$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = FALSE;
			} else {            
				$data = $dato; 
			}   
		}else{
			$data=FALSE;	
		} 

		return $data;
	}
	function cuentadocurc($codcia,$fecha){
		include("application/config/conexdb_db2.php");
		$usuaper= $this->session->userdata('usuaper');
		$da=gmdate("Y", time() -18000);
		$dm=gmdate("m", time() -18000);
		$dd=gmdate("d", time() -18000);
		$fechae=$da.$dm.$dd;

		if($fecha!=''){	
		//$sql ="select count(SCTCSTS) as nrobolanu FROM LIBPRDDAT.SNT_CTRAM a where a.SCTFECEM='".$fecha."' and a.SCTECIAA='10' and a.SCTETDOC='03' and a.SCTCSTS='I' ";			
			$sql ="select count(SCTCSTS) as nrobolanu FROM LIBPRDDAT.SNT_CTRAM a where a.SCTECIAA='".$codcia."' and a.SCTETDOC in ('03','07','08') and SCTTDREF in('0','3') and a.SCTCSTST IN ('I','N') and SCTCFECB=$fechae";
			$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = FALSE;
			} else {            
				$data = $dato; 
			}   
		}else{
			$data=FALSE;	
		} 

		return $data;  
	}
	function cuentadocura($codcia,$fecha){
		include("application/config/conexdb_db2.php");
		$usuaper= $this->session->userdata('usuaper');
		$da=gmdate("Y", time() -18000);
		$dm=gmdate("m", time() -18000);
		$dd=gmdate("d", time() -18000);
		$fechae=$da.$dm.$dd;
		if($fecha!=''){	
		//$sql ="select count(SCTCSTS) as nroanu FROM LIBPRDDAT.SNT_CTRAM a where a.SCTFECEM='".$fecha."' and a.SCTECIAA='10' and a.SCTETDOC<>'03' and a.SCTCSTS='I'";			
			$sql ="select count(SCTCSTS) as nroanu FROM LIBPRDDAT.SNT_CTRAM a where a.SCTECIAA='".$codcia."' and a.SCTETDOC<>'03' and a.SCTCSTST IN ('I','N') and SCTTDREF in('0','1') and SCTCFECB=$fechae";
			$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = FALSE;
			} else {            
				$data = $dato; 
			}   
		}else{
			$data=FALSE;	
		} 

		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |Actualizacion estado de TRAMA DE DOCUMENTO
// +---------------------------------------------------------------------------+       
	function update_fe_traman($SCTECIAA,$SCTEPDC,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB){

		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$pgm='BAJADOCWEB';
		$f= gmdate("d-m-Y", time() - 18000);
		$dd=substr($f, 0,2);
		$dm=substr($f, 3,2);
		$da=substr($f, 6,4);
		$fecha=$da.$dm.$dd;
		$hh= gmdate("H-i-s", time() - 18000);
		$h=substr($hh, 0,2);
		$m=substr($hh, 3,2);
		$s=substr($hh, 6,2);
		$hora=$h.$m.$s;
		$sql = "update LIBPRDDAT.SNT_CTRAM  SET SCTCSTST='".$ESTADO."',SCTCUSUA='MMUSRSCD', SCTCFECA=".$fecha.", SCTCHORA=".$hora.", SCTCPGMA='PGMWEBFE', SCTCSTSB='".$SCTCSTSB."', SCTCUSUB='MMUSRSCD', SCTCFECB=$fecha, SCTCHORB=$hora, SCTCPGMB='".$pgm."' WHERE SCTECIAA='".$SCTECIAA."' and SCTEPDCA='".$SCTEPDC."' and SCTESERI='".$SCTESERI."' and SCTECORR='".$SCTECORR."' and SCTFECEM='".$SCTFECEM."' and SCTCSTS ='I' and SCTCSTST ='N'";

		$res =odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());				
		if (!$res):$res = 0;
			else: $res = 1;
			endif;
			return $res;
			
		}
		function update_fe_tramae($SCTECIAA,$SCTEPDC,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB){
			include("application/config/conexdb_db2.php");
			$codcia=$this->session->userdata('codcia');
			$pgm='BAJADOCWEB'; 
			$f= gmdate("d-m-Y", time() - 18000);
			$dd=substr($f, 0,2);
			$dm=substr($f, 3,2);
			$da=substr($f, 6,4);
			$fecha=$da.$dm.$dd;
			$hh= gmdate("H-i-s", time() - 18000);
			$h=substr($hh, 0,2);
			$m=substr($hh, 3,2);
			$s=substr($hh, 6,2);
			$hora=$h.$m.$s;

			$SCTCSTS=($ESTADO=='I')?'I':'I';
			$updba='';
			if($SCTCSTSB==''){	
				$updba='';
			}else{
				$updba="SCTCFECB=$fecha, SCTCHORB=$hora";
			}
			$sql = "update LIBPRDDAT.SNT_CTRAM  SET SCTCSTST='".$ESTADO."',SCTCSTS='".$SCTCSTS."',SCTCUSUA='MMUSRSCD', SCTCFECA=$fecha, SCTCHORA=$hora, SCTCPGMA='PGMWEBFE',  SCTCSTSB='".$SCTCSTSB."' ,SCTCUSUB='MMUSRSCD' , ".$updba.", SCTCPGMB='".$pgm."' WHERE SCTECIAA='".$SCTECIAA."' and SCTEPDCA='".$SCTEPDC."' and SCTESERI='".$SCTESERI."' and SCTECORR='".$SCTECORR."' and SCTFECEM='".$SCTFECEM."' and SCTCSTS ='A' and SCTCSTST IN ('E','R')";
			
			$res = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
			if (!$res):$res = 0;
				else: $res = 1;
				endif;
				return $res;	
				
			}
		function update_fe_tramaa($SCTECIAA,$SCTEPDC,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO,$SCTCSTSB){
			include("application/config/conexdb_db2.php");
			$codcia=$this->session->userdata('codcia');
			$pgm='BAJADOCWEB'; 
			$f= gmdate("d-m-Y", time() - 18000);
			$dd=substr($f, 0,2);
			$dm=substr($f, 3,2);
			$da=substr($f, 6,4);
			$fecha=$da.$dm.$dd;
			$hh= gmdate("H-i-s", time() - 18000);
			$h=substr($hh, 0,2);
			$m=substr($hh, 3,2);
			$s=substr($hh, 6,2);
			$hora=$h.$m.$s;

			$SCTCSTS=($ESTADO=='I')?'I':'I';
			$updba='';
			if($SCTCSTSB==''){	
				$updba='';
			}else{
				$updba="SCTCFECB=$fecha, SCTCHORB=$hora";
			}
			$sql = "update LIBPRDDAT.SNT_CTRAM  SET SCTCSTST='".$ESTADO."',SCTCSTS='".$SCTCSTS."',SCTCUSUA='MMUSRSCD', SCTCFECA=$fecha, SCTCHORA=$hora, SCTCPGMA='PGMWEBFE',  SCTCSTSB='".$SCTCSTSB."' ,SCTCUSUB='MMUSRSCD' , ".$updba.", SCTCPGMB='".$pgm."' WHERE SCTECIAA='".$SCTECIAA."' and SCTEPDCA='".$SCTEPDC."' and SCTESERI='".$SCTESERI."' and SCTECORR='".$SCTECORR."' and SCTFECEM='".$SCTFECEM."' and SCTCSTS ='A' and SCTCSTST='A'";
			$res = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());				
			if (!$res):$res = 0;
				else: $res = 1;
				endif;
				return $res;	
				
			}
// +---------------------------------------------------------------------------+    
// |Registrar TRAMA de Envio a WS en Anexos  $docufersl->responseCode,$docufersl->responseContent
// +---------------------------------------------------------------------------+       
			function set_eventos_fe($SCTECIAA,$SCTEPDC,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCode,$responseContent,$SCTESUCA){
				include("application/config/conexdb_db2.php");
				$codcia=$this->session->userdata('codcia'); 
				$f= gmdate("d-m-Y", time() - 18000);
				$dd=substr($f, 0,2);
				$dm=substr($f, 3,2);
				$da=substr($f, 6,4);
				$fecha=$da.$dm.$dd;
				$hh= gmdate("H-i-s", time() - 18000);
				$h=substr($hh, 0,2);
				$m=substr($hh, 3,2);
				$s=substr($hh, 6,2);
				$hora=$h.$m.$s;
				$sql = "insert into LIBPRDDAT.SNT_ANEXO (SACODCIA,SACODSUC,SANROINT,SANROSER,SANROCOR,SATIPDOC,SACODRPT,SAMSGRPT,SASTS,SAUSR,SAFECREG,SAHORREG)values('".$SCTECIAA."','".$SCTESUCA."','".$SCTEPDC."','".$SCTESERI."','".$SCTECORR."','".$SCTETDOC."','".$responseCode."','".$responseContent."','A','MMUSRSCD','".$fecha."','".$hora."')";	
				$res = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
				if (!$res):$res = 0;
					else: $res = 1;
					endif;				
					return $res;			
				}

		// +---------------------------------------------------------------------------+    
// | Consulta TRAMA de Envio a WS en Anexos 
// +---------------------------------------------------------------------------+       
				function get_eventos_fe($SCTECIAA,$SCTEPDCA,$responseCode){
					include("application/config/conexdb_db2.php");
					$data='';
					$sql = "select COUNT(b.SACODRPT) as SACODRPT FROM LIBPRDDAT.SNT_ANEXO b where b.SACODCIA='".$SCTECIAA."' and b.SANROINT=".$SCTEPDCA." and b.SACODRPT='".$responseCode."'";
					$dato = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
					if (!$dato) {
						$data = FALSE;
					} else {            
						$data = $dato; 
					}
					return $data;
					
				}

			// +---------------------------------------------------------------------------+    
// | Consulta en Evento Ticket Baja en WS desde el Anexos 
// +---------------------------------------------------------------------------+       
				function get_ticket_eventos_fe($SCTECIAA,$SCTEPDCA,$responseCode){
					include("application/config/conexdb_db2.php");
					$data='';
					$sql = "select b.SACODRPT,b.SAMSGRPT FROM LIBPRDDAT.SNT_ANEXO b where b.SACODCIA='".$SCTECIAA."' and b.SANROINT=".$SCTEPDCA." and b.SACODRPT='".$responseCode."'";
					$dato = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
					if (!$dato) {
						$data = FALSE;
					} else {            
						$data = $dato; 
					}
					return $data;
				}

// +---------------------------------------------------------------------------+    
// |Listar Eventos de Documentos Baja
// +---------------------------------------------------------------------------+        
	
	function get_vereventosbajafe($codcia,$nropdc,$nrosere,$nrocor,$tipdoc){
		include("application/config/conexdb_db2.php");                                                       
		$sql ="select c.SACODRPT,c.SAMSGRPT FROM LIBPRDDAT.SNT_ANEXO c WHERE c.SACODCIA='".$codcia."' and c.SANROINT='".$nropdc."' and c.SANROSER='".$nrosere."' and c.SANROCOR='".$nrocor."' and c.SATIPDOC='".$tipdoc."' ";		
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  

		}        
		return $data;  
	}

		// +---------------------------------------------------------------------------+    
// |Mostrar Documentos Referencia si es NC o CD
// +---------------------------------------------------------------------------+        
	
	function get_verdocumentosrefncnd($codcia,$nropdc,$nrosere,$nrocor,$tipdoc){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$sql ="select a.SCTTDREF,a.SCTSERRF,a.SCTCORRF,a.SCTFECRF FROM LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='".$codcia."' and  a.SCTEPDCA=".$nropdc." AND a.SCTESERI='".$nrosere."' and a.SCTECORR='".$nrocor."' and a.SCTETDOC='".$tipdoc."'";		
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  

		}        
		return $data;  
	}

	// +---------------------------------------------------------------------------+    
// |Documentos en Trama Para Generar pdf anulado
// +---------------------------------------------------------------------------+     

	function generadataanuladofe($datas){

		include("application/config/conexdb_db2.php");
		$fecha=$datas['fecha'];
		$serie=$datas['serie'];
		$correl=$datas['correl'];
		$nropdc=$datas['nropdc'];		
		$codcia=$datas['cia']; 
		if($fecha!=''||$serie!=''||$correl!=''){
			if($codcia=="'10'"){
				$wr=" and a.SCTEFEC=".$fecha." and a.SCTECIAA=".$codcia." and a.SCTESERI=".$serie." and a.SCTECORR=".$correl." and a.SCTEPDCA=".$nropdc
				;
			}else{
				$wr=" and a.SCTEFEC=".$fecha." and a.SCTECIAA='".$codcia."' and a.SCTESERI='".$serie."' and a.SCTECORR='".$correl."' and a.SCTEPDCA=".$nropdc
				;	
			}		
			$sql ="select a.SCTEPER,a.SCTEFEC,a.SCTECIAA,a.SCTESUCA,a.SCTEALMA,a.SCTEPDCA,a.SCTESERA,a.SCTECORA,a.SCTESERI,a.SCTECORR,a.SCTERZSO,a.SCTECODE,a.SCTECRUC,a.SCTEUBIG,a.SCTEDIRE,a.SCTENCOM,a.SCTETDOC,a.SCTETPDO,a.SCTVENDE,a.SCTCCLIE,a.SCTCNRUC,a.SCTCRZSO,a.SCTCDIRE,a.SCTCTMON,a.SCTCODAU,a.SCTCSUST,a.SCTCTPNO,a.SCTGNETO,a.SCTGGEXE,a.SCTGNEXO,a.SCTGIGV,a.SCTGTOTA,a.SCTFECEM,a.SCTCCIMP,a.SCTMTIMP,a.SCTTASAI,a.SCTCSTS,a.SCTCUSUR,a.SCTCFECR,a.SCTCHORR,a.SCTIPFAC FROM LIBPRDDAT.SNT_CTRAM a where a.SCTCSTS='I'".$wr;
//print_r($sql);
			$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = FALSE;
			} else {            
				$data = $dato; 
			}   
		}else{
			$data=FALSE;	
		} 

		return $data;  
	}


			}
			?>