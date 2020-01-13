<?php

class Facturacionbaja_db2_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

// +---------------------------------------------------------------------------+    
// |Listar Documentos Anulados
// +---------------------------------------------------------------------------+        
	
	function listadocumento($fecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql ="select DISTINCT(YHFECDOC)AS FECHA, (select COUNT(YHTIPDOC) FROM LIBPRDDAT.MMYHREL0 WHERE YHFECDOC=".$fecha." AND YHSTS='I' AND YHCODCIA='".$codcia."' and YHTIPDOC='01') AS CANFAC, (select COUNT(YHTIPDOC) FROM LIBPRDDAT.MMYHREL0 WHERE YHFECDOC=".$fecha." AND YHSTS='I' AND YHCODCIA='".$codcia."' and YHTIPDOC='03') AS CANBOL,(select COUNT(YHTIPDOC) FROM LIBPRDDAT.MMYHREL0 WHERE YHFECDOC=".$fecha." AND YHSTS='I' AND YHCODCIA='".$codcia."' and YHTIPDOC='08') as CANND,(select COUNT(YHTIPDOC) FROM LIBPRDDAT.MMYHREL0 WHERE YHFECDOC=".$fecha." AND YHSTS='I' AND YHCODCIA='".$codcia."' and YHTIPDOC='07') AS CANNC FROM LIBPRDDAT.MMYHREL0 WHERE YHFECDOC=".$fecha." AND YHSTS='I' AND YHCODCIA='".$codcia."'";
		$listadocumentos=array();
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}

// +---------------------------------------------------------------------------+    
// |Estado Documentos EN TRAMA 
// +---------------------------------------------------------------------------+        
	
	function stsdocumentotrama($fecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql ="select DISTINCT (SCTCSTS) as SCTCSTS FROM LIBPRDDAT.SNT_CTRAM WHERE SCTEFEC=".$fecha." and SCTECIAA='".$codcia."' ";	
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  

		}        
		return $data;  
	}

// +---------------------------------------------------------------------------+    
// |Cantidad de documentos por estado 
// +---------------------------------------------------------------------------+        
	
	function cantstsdocumentotrama($fecha,$estado){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql ="select count(SCTCSTS) as SCTCSTS FROM LIBPRDDAT.SNT_CTRAM WHERE SCTCSTS='".$estado."' AND SCTEFEC=".$fecha." and SCTECIAA='".$codcia."' ";
	
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  

		}        
		return $data;  
	}

// +---------------------------------------------------------------------------+    
// |Listar Años de Documentos Anulados
// +---------------------------------------------------------------------------+        
	
	function listaanio(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 1, 4)) AS anio FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='I' AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by anio asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |Listar Años de Documentos Anulados
// +---------------------------------------------------------------------------+        
	
	function listames(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');                                                       
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 5, 2)) AS mes FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='I' AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by mes asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;  
	}

// +---------------------------------------------------------------------------+    
// |Listar detalle de Documentos Anulados
// +---------------------------------------------------------------------------+        
	
	function detalledocumentoanulado($fecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');    
		$sql ="select YHSUCDOC,YHTIPDOC, YHNROPDC, YHNUMSER, YHNUMCOR,YHSTS,YHCODSUC FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='I' AND YHCODCIA='".$codcia."' and YHFECDOC=".$fecha." order by YHSUCDOC asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}

// +---------------------------------------------------------------------------+    
// |Validar Documento Anulado en CB Factura y boleta
// +---------------------------------------------------------------------------+        
	function cbdocumentoanulado($fecha,$nropdc,$suc,$serie,$correl){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$sini=substr($serie, 0,1);
		$nini=substr($serie, 2,2);
		$serie=$sini.$nini;
		$sql ="select CBSTS FROM LIBPRDDAT.MMCBREP WHERE CBCODCIA='".$codcia."' AND CBCODSUC='".$suc."' and CBFECDOC=".$fecha." AND CBNROSER='".$serie."' AND CBNROCOR='".$correl."' AND CBNROPDC =".$nropdc;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
	return $data; 
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
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
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
		$sql ="select FXSTS FROM LIBPRDDAT.MMFXREP WHERE FXCODCIA='".$codcia."' AND FXCODSUC='".$suc."' and FXFECDVL=".$fecha." AND FXNROPDC =".$nropdc." AND FXNROSER='".$serie."' AND FXNROCOR='".$correl."' AND FXTIPDOC='".$tipodoc."'"; 
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
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
		$sql ="select JRSTS FROM LIBPRDDAT.MMJRREP WHERE JRCODCIA='".$codcia."' AND JRCODSUC='".$suc."' and JRFECEMI=".$fecha." AND JRNRODOC =".$nropdc." AND JRNROSER='".$serie."' AND JRNROCOR='".$correl."' AND JRTIPDOC='".$tipdoc."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
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
		$sql ="select SCTESERI, SCTECORR, SCTCCLIE, SCTCRZSO, SCTCTMON, SCTGTOTA, SCTCSTS FROM LIBPRDDAT.SNT_CTRAM WHERE SCTECIAA='".$codcia."' AND SCTESUCA='".$suc."' and SCTEFEC=".$fecha." AND SCTEPDCA =".$nropdc." AND SCTETDOC='".$tipdoc."' AND SCTESERA='".$serie."' AND SCTECORA='".$correl."' " ;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;  
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
		odbc_close($dbconect);
		return $result;
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

}   

?>