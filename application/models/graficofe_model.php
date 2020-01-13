
<?php

class graficofe_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function index(){


	}
// +---------------------------------------------------------------------------+    
// |Listar Años de Documentos Anulados
// +---------------------------------------------------------------------------+        
	
	function listaanio(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 1, 4)) AS anio FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by anio asc";
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
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 5, 2)) AS mes FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by mes asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;  
	}
	function ventaxdocumento($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		//$sql="select a.SCTFECEM,a.SCTETDOC,count(a.SCTETDOC) as CANT, sum(a.SCTGTOTA) as TOTAL from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='10' AND  a.SCTFECEM ".$paramfecha." GROUP BY a.SCTFECEM, a.SCTETDOC ORDER BY a.SCTFECEM";
		//$sql="select CASE WHEN a.SCTETDOC='01' THEN 'Factura' WHEN a.SCTETDOC='03' THEN 'Boleta' WHEN a.SCTETDOC='07' THEN 'NotaCredito' WHEN a.SCTETDOC='08' THEN 'NotaDebito' ELSE 'NoExiste' END as Documento,count(a.SCTETDOC) as Cantidad, sum(a.SCTGTOTA) as TotalImporte from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='".$codcia."' AND  a.SCTFECEM ".$paramfecha." AND SCTCSTS='A' GROUP BY a.SCTETDOC ORDER BY a.SCTETDOC";
		//$sql="select CASE WHEN a.SCTETDOC='01' THEN 'Factura' WHEN a.SCTETDOC='03' THEN 'Boleta' WHEN a.SCTETDOC='07' THEN 'NotaCredito' WHEN a.SCTETDOC='08' THEN 'NotaDebito' ELSE 'NoExiste' END as Documento,count(a.SCTCSTS) as Generados,count(a.SCTCSTST) as Aceptados from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='".$codcia."'  AND  a.SCTFECEM ".$paramfecha." AND SCTCSTS='I' GROUP BY a.SCTETDOC ORDER BY a.SCTETDOC";

		$sql="select CASE WHEN a.SCTETDOC='01' THEN 'Factura' WHEN a.SCTETDOC='03' THEN 'Boleta' WHEN a.SCTETDOC='07' THEN 'NotaCredito' WHEN a.SCTETDOC='08' THEN 'NotaDebito' ELSE 'NoExiste' END as Documento,count(x.YHSTS) as Generados,count(a.SCTCSTS) as Aceptados from LIBPRDDAT.MMYHREP x inner join LIBPRDDAT.SNT_CTRAM a on x.YHNROPDC=a.SCTEPDCA and x.YHTIPDOC=a.SCTETDOC and x.YHSUCDOC=a.SCTESUCA where x.YHSTS='I' AND  a.SCTFECEM>='2019-06-01' AND  a.SCTECIAA='".$codcia."' AND  a.SCTFECEM ".$paramfecha."   GROUP BY a.SCTETDOC ORDER BY a.SCTETDOC";

		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}

	function grafico_documento_importe($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		//$sql="select a.SCTFECEM,a.SCTETDOC,count(a.SCTETDOC) as CANT, sum(a.SCTGTOTA) as TOTAL from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='10' AND  a.SCTFECEM ".$paramfecha." GROUP BY a.SCTFECEM, a.SCTETDOC ORDER BY a.SCTFECEM";
		$sql="select CASE WHEN a.SCTETDOC='01' THEN 'Factura' WHEN a.SCTETDOC='03' THEN 'Boleta' WHEN a.SCTETDOC='07' THEN 'NotaCredito' WHEN a.SCTETDOC='08' THEN 'NotaDebito' ELSE 'NoExiste' END as Documento, sum(a.SCTGTOTA) as TotalImporte from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='".$codcia."' AND a.SCTFECEM>='2019-06-01'  a.SCTFECEM ".$paramfecha." AND SCTCSTS='A' GROUP BY a.SCTETDOC ORDER BY a.SCTETDOC";

		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}
	function grafico_cantidad_anulados_x_tipodocu($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		//$sql="select a.SCTFECEM,a.SCTETDOC,count(a.SCTETDOC) as CANT, sum(a.SCTGTOTA) as TOTAL from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='10' AND  a.SCTFECEM ".$paramfecha." GROUP BY a.SCTFECEM, a.SCTETDOC ORDER BY a.SCTFECEM";
		//$sql="select CASE WHEN a.SCTETDOC='01' THEN 'Factura' WHEN a.SCTETDOC='03' THEN 'Boleta' WHEN a.SCTETDOC='07' THEN 'NotaCredito' WHEN a.SCTETDOC='08' THEN 'NotaDebito' ELSE 'NoExiste' END as Documento,count(a.SCTETDOC) as Cantidad from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTECIAA='".$codcia."' AND  a.SCTFECEM ".$paramfecha." AND SCTCSTS='A' GROUP BY a.SCTETDOC ORDER BY a.SCTETDOC";

		$sql="select CASE WHEN x.YHTIPDOC='01' THEN 'Factura' WHEN x.YHTIPDOC='03' THEN 'Boleta' WHEN x.YHTIPDOC='07' THEN 'NotaCredito' WHEN x.YHTIPDOC='08' THEN 'NotaDebito' ELSE 'NoExiste' END as Documento,count(x.YHTIPDOC) as Cantidad FROM LIBPRDDAT.MMYHREP x where x.YHCODCIA='".$codcia."' and  x.YHSTS='I'  AND x.YHFECDOC>='20190601'  AND x.YHFECDOC ".$paramfecha." GROUP BY x.YHTIPDOC ORDER BY x.YHTIPDOC";

		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());

		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}
function grafico_cantidad_activo_x_tipodocu($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		
		$sql="select CASE WHEN x.YHTIPDOC='01' THEN 'Factura' WHEN x.YHTIPDOC='03' THEN 'Boleta' WHEN x.YHTIPDOC='07' THEN 'NotaCredito' WHEN x.YHTIPDOC='08' THEN 'NotaDebito' ELSE 'NoExiste' END as Documento,count(x.YHTIPDOC) as Cantidad FROM LIBPRDDAT.MMYHREP x where x.YHCODCIA='".$codcia."' and  x.YHSTS='A'  AND x.YHFECDOC>='20190601'  AND x.YHFECDOC ".$paramfecha." GROUP BY x.YHTIPDOC ORDER BY x.YHTIPDOC";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}
	function grafico_total_documentos($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql="select count(x.YHNROPDC) as totaldocu FROM LIBPRDDAT.MMYHREP x where x.YHFECDOC>='20190601' AND x.YHFECDOC ".$paramfecha;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}
	function grafico_total_documentos_activos($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql="select count(x.YHNROPDC) as totaldocu FROM LIBPRDDAT.MMYHREP x where x.YHSTS='A' AND x.YHFECDOC>='20190601' and x.YHFECDOC ".$paramfecha;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}
	function grafico_total_documentos_inactivos($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql="select count(x.YHNROPDC) as totaldocu FROM LIBPRDDAT.MMYHREP x where x.YHSTS='I' AND x.YHFECDOC>='20190601' and x.YHFECDOC ".$paramfecha;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}

	function grafico_cantidad_anulados_x_sucursal($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql="select a.EUCODELE ||' '|| a.EUDSCLAR as sucursal,count(x.YHCODSUC) as Cantidad from LIBPRDDAT.MMEUREL0 a inner join LIBPRDDAT.MMYHREP x on a.EUCODELE=x.YHCODSUC WHERE a.EUCODTBL='02' and a.EUCODELE not in('98','97','96','99','94','80') and a.EUSTS='A' and x.YHCODCIA='".$codcia."'  AND x.YHFECDOC>='20190601' and  x.YHSTS='I' AND x.YHFECDOC ".$paramfecha." group by a.EUCODELE,a.EUDSCLAR order by a.EUCODELE asc ";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}

// +---------------------------------------------------------------------------+    
// |Grafico de Documentos activos
// +---------------------------------------------------------------------------+        

function grafico_total_documentos_activos_linea($paramfecha,$flg){
	include("application/config/conexdb_db2.php");
	$codcia=$this->session->userdata('codcia');
	if($flg==0){
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 5, 2)) AS mes FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by mes asc";
	}else if($flg==1){
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 7, 2)) AS dia FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by dia asc
		";
	}else{
		$sql ="select DISTINCT( CASE WHEN CHAR_LENGTH (YHJTM) >5 THEN SUBSTRING(YHJTM, 1, 2)ELSE '0'||SUBSTRING(YHJTM, 1, 1) END) AS hora FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by hora asc";
	}

	$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
	if (!$dato) {
		$data = FALSE;
	} else {            
		$data = $dato; 
	}        
	return $data;  
}
// +---------------------------------------------------------------------------+    
// |Grafico de Documentos activos cantidad
// +---------------------------------------------------------------------------+        

function grafico_total_documentos_activos_linea_detag($paramfecha,$flg){
	include("application/config/conexdb_db2.php");
	$codcia=$this->session->userdata('codcia');                                                       
	if($flg==0){
		$sql ="select count(YHNROPDC) AS CANTIDAD FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='I' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0";
	}else if($flg==1){
		$sql ="select count(YHNROPDC) AS CANTIDAD FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='I' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0";
	}else{
		$sql ="select count(YHNROPDC) AS CANTIDAD FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='I' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0";
	}
	$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
	if (!$dato) {
		$data = FALSE;
	} else {            
		$data = $dato; 
	}        
	return $data;  
}
// +---------------------------------------------------------------------------+    
// |Grafico de Documentos activos cantidad
// +---------------------------------------------------------------------------+        

function grafico_total_documentos_activos_linea_detaa($paramfecha,$flg){
	include("application/config/conexdb_db2.php");
	$codcia=$this->session->userdata('codcia');                                                       
	if($flg==0){
		$sql ="select count(YHNROPDC) AS CANTIDAD FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0";
	}else if($flg==1){
		$sql ="select count(YHNROPDC) AS CANTIDAD FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0";
	}else{
		$sql ="select count(YHNROPDC) AS CANTIDAD FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' and YHFECDOC>=20190601 and YHFECDOC ".$paramfecha." AND YHCODCIA='".$codcia."' and YHFECDOC<>0";
	}
	$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
	if (!$dato) {
		$data = FALSE;
	} else {            
		$data = $dato; 
	}        
	return $data;  
}
function grafico_lista_vendedor($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql="select a.SCTVENDE,b.BMPRNOMB,b.BMPRAPLL,b.BMUSERID,count(a.SCTETDOC) as Cantidad from LIBPRDDAT.SNT_CTRAM a left join LIBPRDDAT.MMBMREL0 b on a.SCTVENDE=b.BMCODPER WHERE a.SCTEFEC ".$paramfecha." AND a.SCTEFEC>='20190601' and a.SCTCSTS='I' group by a.SCTVENDE, b.BMPRNOMB,b.BMPRAPLL,b.BMUSERID order by Cantidad desc FETCH FIRST 10 ROWS ONLY";
	$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());

		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
}
function grafico_lista_documen($paramfecha){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql="select distInct(c.SCTETDOC) as DOCUMENTO from libprddat.SNT_CTRAM c where c.SCTEFEC ".$paramfecha." and c.SCTCSTS='I' AND c.SCTEFEC>='20190601'";
	$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());

		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
}

function grafico_cantidad_anulados_x_vendedor($paramfecha,$codtdoc,$codven){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
	$sql="select count(c.SCTETDOC) as Cantidad from libprddat.SNT_CTRAM c where c.SCTECIAA='".$codcia."' and c.SCTEFEC ".$paramfecha." and c.SCTCSTS='I' and c.SCTVENDE='".$codven."' and c.SCTETDOC='".$codtdoc."' AND c.SCTEFEC>='20190601'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());

		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;
	}


}
?>