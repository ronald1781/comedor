<?php

class Facturacionsendws_db2_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function get_verificar_existe_fact(){
		
		include("application/config/conexdb_db2.php");
		
		$f= gmdate("d-m-Y", time() - 18000);
		$dd=substr($f, 0,2);
		$dm=substr($f, 3,2);
		$da=substr($f, 6,4);
		$fecha=$da.$dm.$dd;
		$codcia=$this->session->userdata('codcia');
		$sql ="select a.SCTEFEC,a.SCTECIAA,a.SCTEPDCA,a.SCTESERI,a.SCTECORR from LIBPRDDAT.SNT_CTRAM a where a.SCTCSTST='G' or a.SCTCSTST='E' and a.SCTFECEM>='2019-06-01'";				
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;
	}
	// +---------------------------------------------------------------------------+    
// | Listar Cia
// +---------------------------------------------------------------------------+        
	public function listarCia($cias) {   
		include("application/config/conexdb_db2.php");           
		$sql = "select EUCODELE, EUDSCABR, EUDSCLAR,EUJOB from LIBPRDDAT.MMEUREP WHERE  EUCODTBL='01' AND EUSTS='A' AND EUCODELE ='" . $cias ."'";

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
// |Listar Años de Documentos Anulados
// +---------------------------------------------------------------------------+       
	function listaanio(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 1, 4)) AS anio FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by anio asc";
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
		$sql ="select DISTINCT(SUBSTRING(YHFECDOC, 5, 2)) AS mes FROM LIBPRDDAT.MMYHREL0 WHERE YHSTS='A' AND YHCODCIA='".$codcia."' and YHFECDOC<>0 order by mes asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;  
	}
	// +---------------------------------------------------------------------------+    
// |Listar estados Documentos 
// +---------------------------------------------------------------------------+        
	
	function listaestado(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');                                                       
		$sql ="select DISTINCT(a.SCTCSTST) AS ESTADO from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTEFEC>=20190601 and a.SCTCSTST<>'I'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;  
	}
	// +---------------------------------------------------------------------------+    
// |Listar estados Documentos Baja
// +---------------------------------------------------------------------------+        
	
	function listaestadobaja(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');                                                       
		$sql ="select DISTINCT(a.SCTCSTST) AS ESTADO from LIBPRDDAT.SNT_CTRAM a WHERE a.SCTEFEC>=20190601 and a.SCTCSTS<>'I'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato; 
		}        
		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |Listar Tipo de Documentos Emetido Activos
// +---------------------------------------------------------------------------+        
	
	function listatpodocu(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');
		$sql ="select DISTINCT(a.SCTETDOC) AS TIPODOCU,e.EUDSCCOR FROM LIBPRDDAT.SNT_CTRAM a INNER JOIN LIBPRDDAT.MMEUREL0 e ON a.SCTETDOC=e.EUCODELE WHERE a.SCTETDOC!='' AND e.EUCODTBL='AG' order by TIPODOCU asc";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |Listar Serie de Documentos Emetidos Activos
// +---------------------------------------------------------------------------+        
	
	function listaseri(){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');                                                       
		$sql ="select DISTINCT(SCTESERI) AS SERIE FROM LIBPRDDAT.SNT_CTRAM WHERE SCTESERI!='' ORDER BY SERIE ASC";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  

		}        
		return $data;  
	}

// +---------------------------------------------------------------------------+    
// |Listar Evventos de Documentos Emetidos
// +---------------------------------------------------------------------------+        
	
	function get_vereventosdocumentosfe($nropdc,$nrosere,$nrocor,$tipdoc){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');                                                       
		$sql ="select c.SACODRPT,c.SAMSGRPT FROM LIBPRDDAT.SNT_ANEXO c WHERE c.SANROINT='".$nropdc."' and c.SANROSER='".$nrosere."' and c.SANROCOR='".$nrocor."' and c.SATIPDOC='".$tipdoc."' ";		
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
	
	function get_verdocumentosrefncnd($nropdc,$nrosere,$nrocor,$tipdoc){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$sql ="select a.SCTTDREF,a.SCTSERRF,a.SCTCORRF,a.SCTFECRF FROM LIBPRDDAT.SNT_CTRAM a WHERE a.SCTEPDCA=".$nropdc." AND a.SCTESERI='".$nrosere."' and a.SCTECORR='".$nrocor."' and a.SCTETDOC='".$tipdoc."'";		
		
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  

		}        
		return $data;  
	}
	// +---------------------------------------------------------------------------+    
// | Datos cliente para la busqueda y enviar correo
// +---------------------------------------------------------------------------+        
	
	function buscar_cliente_db2as400($codclieas400){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia');  

		$sql ="select DISTINCT(a.SCTCCLIE) AS SCTCCLIE,a.SCTCNRUC,a.SCTCRZSO FROM LIBPRDDAT.SNT_CTRAM a WHERE a.SCTFECEM>='2019-06-01' and a.SCTCCLIE like '%".$codclieas400."%' or a.SCTCNRUC like '%".$codclieas400."%' or a.SCTCRZSO like '%".$codclieas400."%'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  

		}        
		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |Documentos en Trama Para Mostrar en visual
// +---------------------------------------------------------------------------+     

	function get_documentosfe($fecha,$seltd,$selseri,$selstsd){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		$parama='';
		$qry='';
		if($fecha!=''){
			$fecha=$fecha;
		}else{
			$f= gmdate("d-m-Y", time() - 18000);
			$dd=substr($f, 0,2);
			$dm=substr($f, 3,2);
			$da=substr($f, 6,4);
			$fecha=" between '".$da."-".$dm."-".$dd."' and '".$da."-".$dm."-".$dd."'";
		}
		if(($fecha!='')&&($selstsd!='')&&($selseri=='')&&($seltd=='')){	
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTCSTST='".$selstsd."'";
		}elseif(($fecha!='')&&($selstsd=='')&&($selseri=='')&&($seltd=='')){
			$qry =" and a.SCTFECEM ".$fecha;
		}elseif(($fecha!='')&&($seltd!='')&&($selseri!='')&&($selstsd=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTETDOC='".$seltd."' and a.SCTESERI='".$selseri."'";
		}elseif(($fecha!='')&&($seltd!='')&&($selseri=='')&&($selstsd=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTETDOC='".$seltd."'";
		}elseif(($fecha!='')&&($selseri!='')&&($seltd=='')&&($selstsd=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTESERI='".$selseri."'";
		}elseif(($fecha!='')&&($selseri!='')&&($selstsd!='')&&($seltd=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTCSTST='".$selstsd."' and a.SCTESERI='".$selseri."'";	
		}elseif(($fecha!='')&&($selseri=='')&&($selstsd!='')&&($seltd!='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTCSTST='".$selstsd."' and a.SCTETDOC='".$seltd."'";	
		}else{
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTCSTST='".$selstsd."' and a.SCTETDOC='".$seltd."' and a.SCTESERI='".$selseri."'";
		}
		$sql="select a.SCTFECEM,a.SCTESUCA,a.SCTETDOC,a.SCTEPDCA,a.SCTCRZSO,a.SCTESERI,a.SCTECORR,a.SCTCTMON,a.SCTGNETO,a.SCTCSTST,a.SCTECIAA,a.SCTEFEC,a.SCTGTOTA,(select COUNT(c.SACODRPT) AS CANT FROM LIBPRDDAT.SNT_ANEXO c WHERE c.SANROINT=a.SCTEPDCA and c.SANROSER=a.SCTESERI and c.SANROCOR=a.SCTECORR and c.SATIPDOC=a.SCTETDOC) as nroevento,a.SCTIPFAC FROM LIBPRDDAT.SNT_CTRAM a where a.SCTFECEM>='2019-06-01' and a.SCTCSTS='A' and a.SCTECIAA='".$codcia."' ".$qry." order by a.SCTEPDCA desc";
		//print_r($sql);
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
// |Documentos en Trama por cliente Para Mostrar en visual
// +---------------------------------------------------------------------------+     

	function get_documentosfe_clie($fecha,$seltd,$selseri,$codclie){
		include("application/config/conexdb_db2.php");

		$codcia=$this->session->userdata('codcia'); 
		$parama='';
		$fechaa='';
		

		if(($fecha!='')&&($codclie!='')&&($selseri=='')&&($seltd=='')){	
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTCCLIE='".$codclie."'";
		}elseif(($fecha!='')&&($codclie=='')&&($selseri=='')&&($seltd=='')){
			$qry =" and a.SCTFECEM ".$fecha."";
		}elseif(($fecha!='')&&($seltd!='')&&($selseri!='')&&($codclie=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTETDOC='".$seltd."' and a.SCTESERI='".$selseri."'";
		}elseif(($fecha!='')&&($seltd!='')&&($selseri=='')&&($codclie=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTETDOC='".$seltd."'";
		}elseif(($fecha!='')&&($selseri!='')&&($seltd=='')&&($codclie=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTESERI='".$selseri."'";
		}elseif(($fecha!='')&&($selseri=='')&&($codclie!='')&&($seltd=='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTCCLIE='".$codclie."'";	
		}elseif(($fecha!='')&&($selseri=='')&&($codclie!='')&&($seltd!='')){
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTETDOC='".$seltd."' and a.SCTCCLIE='".$codclie."'";	
		}else{
			$qry =" and a.SCTFECEM ".$fecha." and a.SCTETDOC='".$seltd."' and a.SCTESERI='".$selseri."' and a.SCTCCLIE='".$codclie."'";
		}

		$sql="select a.SCTFECEM,a.SCTESUCA,a.SCTETDOC,a.SCTEPDCA,a.SCTCRZSO,a.SCTESERI,a.SCTECORR,a.SCTCTMON,a.SCTGNETO,a.SCTCSTST,a.SCTECIAA,a.SCTEFEC,a.SCTGTOTA, (select COUNT(c.SACODRPT) AS CANT FROM LIBPRDDAT.SNT_ANEXO c WHERE c.SANROINT=a.SCTEPDCA and c.SANROSER=a.SCTESERI and c.SANROCOR=a.SCTECORR and c.SATIPDOC=a.SCTETDOC)  as nroevento,a.SCTIPFAC FROM LIBPRDDAT.SNT_CTRAM a where a.SCTFECEM>='2019-06-01' and a.SCTCSTS='A' and a.SCTECIAA='".$codcia."' ".$qry." order by a.SCTEPDCA desc";		
		//print_r($sql);
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
// |Documentos en Trama Para Generar jsonpara ws
// +---------------------------------------------------------------------------+     

	function documentoxgenerarjson($datas){

		include("application/config/conexdb_db2.php");
		$fecha=$datas['fecha'];
		$serie=$datas['serie'];
		$correl=$datas['correl'];
		$nropdc=$datas['nropdc'];		
		$codcia=$datas['codcia']; 
		if($fecha!=''||$serie!=''||$correl!=''){
			if($codcia=="'10'"){
				$wr=" and a.SCTEFEC=".$fecha." and a.SCTECIAA=".$codcia." and a.SCTESERI=".$serie." and a.SCTECORR=".$correl." and a.SCTEPDCA=".$nropdc
				;
			}else{
				$wr=" and a.SCTEFEC=".$fecha." and a.SCTECIAA='".$codcia."' and a.SCTESERI='".$serie."' and a.SCTECORR='".$correl."' and a.SCTEPDCA=".$nropdc
				;	
			}		
			$sql ="select a.SCTEPER,a.SCTEFEC,a.SCTECIAA,a.SCTESUCA,a.SCTEALMA,a.SCTEPDCA,a.SCTESERA,a.SCTECORA,a.SCTESERI,a.SCTECORR,a.SCTERZSO,a.SCTECODE,a.SCTECRUC,a.SCTEUBIG,a.SCTEDIRE,a.SCTENCOM,a.SCTETDOC,a.SCTETPDO,a.SCTVENDE,a.SCTCCLIE,a.SCTCNRUC,a.SCTCRZSO,a.SCTCDIRE,a.SCTCTMON,a.SCTCODAU,a.SCTCSUST,a.SCTCTPNO,a.SCTGNETO,a.SCTGGEXE,a.SCTGNEXO,a.SCTGIGV,a.SCTGTOTA,a.SCTFECEM,a.SCTCCIMP,a.SCTMTIMP,a.SCTTASAI,a.SCTCSTS,a.SCTCUSUR,a.SCTCFECR,a.SCTCHORR,a.SCTIPFAC FROM LIBPRDDAT.SNT_CTRAM a where a.SCTCSTS='A' and a.SCTFECEM>='2019-06-01' ".$wr;	
								
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
	function ocdocxgenerarjson($SCTECIAA,$SCTESUCA,$SCTEPDCA){
		include("application/config/conexdb_db2.php");
		$codcia=$this->session->userdata('codcia'); 
		if($SCTECIAA!=''||$SCTESUCA!=''||$SCTEPDCA!=''){		
			$sql ="select i.IWODCPRV FROM LIBPRDDAT.MMIWREL0 i where i.IWCODCIA='".$SCTECIAA."' and i.IWCODSUC='".$SCTESUCA."' AND i.IWNROPDC=".$SCTEPDCA." AND i.IWSTS='A'";
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
// |Tipo de cambio
// +---------------------------------------------------------------------------+        
	public function buscar_tcmb_cliente_db2as400($fecha,$moneda) {        
		include("application/config/conexdb_db2.php");
		$mda=($moneda=='USD')?'02':'01';
		$sql = "select d.DKIMPVTA from LIBPRDDAT.mmdkrel0 d WHERE d.DKCODMON='".$mda."' AND d.DKCLSTCM='03' AND d.DKFECTCM<=".$fecha." AND d.DKSTS='A' ORDER BY d.DKFECTCM DESC LIMIT 1";		
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
		
	}
	// +---------------------------------------------------------------------------+    
// | Buscar Placa de Vehiculo
// +---------------------------------------------------------------------------+        
	public function buscar_placavh_cliente_db2as400($PYCODCIA,$PYCODSUC,$PYNROPDC) {        
		include("application/config/conexdb_db2.php");
		$sql = "select p.PYTXTADI from LIBPRDDAT.mmpyrep p where p.PYCODCIA='".$PYCODCIA."' and p.PYCODSUC='".$PYCODSUC."' and p.PYNROPDC=".$PYNROPDC;		
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
		
	}
// +---------------------------------------------------------------------------+    
// | Buscar Correo Cliente
// +---------------------------------------------------------------------------+        
	public function buscar_correo_cliente_db2as400($SCTCCLIE) {        
		include("application/config/conexdb_db2.php");
		$sql = "select i.I6CODDAT,i.I6DSCDAT,i.I6FLAG from LIBPRDDAT.MMI6REP i WHERE i.I6CODCLI='".$SCTCCLIE."' AND  i.I6TIPDAT='003' AND i.I6STS='A' order by i.I6FLAG asc FETCH FIRST 3 ROWS ONLY";	
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
		
	}
// +---------------------------------------------------------------------------+    
// |buscar Cliente Unitario
// +---------------------------------------------------------------------------+        
	public function buscar_cliente_uni_db2as400($codclieas400) {        
		include("application/config/conexdb_db2.php");
		$sql = "select a.AKCODCLI,a.AKRAZSOC,b.IFNVORUC,a.AKTIPIDE,a.AKNROIDE,a.AKIMPLMT from LIBPRDDAT.MMAKREL0 a left join LIBPRDDAT.MMIFREL0 b ON b.IFCODCLI=a.AKCODCLI WHERE a.AKCODCLI ='" . $codclieas400 . "' and IFSTS='A'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |Direccion Cliente mym
// +---------------------------------------------------------------------------+        
	public function buscar_direccion_cliente_mym_db2as400($codclieas400) {        
		include("application/config/conexdb_db2.php");
		$sql ="select ALVIADIR, ALDSCDIR, ALNRODIR,ALDEPART, ALPROVIN, ALDISTRI FROM LIBPRDDAT.MMALREP WHERE ALCODCLI='" . $codclieas400 . "' AND  
		ALTIPDIR='01' and ALITEM01=1" ;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}
	// +---------------------------------------------------------------------------+    
// |Direccion Cliente
// +---------------------------------------------------------------------------+        
	public function buscar_direccion_cliente_db2as400($codclieas400) {        
		include("application/config/conexdb_db2.php");
		$sql ="select ALVIADIR, ALDSCDIR, ALNRODIR, ALDEPART, ALPROVIN, ALDISTRI FROM LIBPRDDAT.MMALREP WHERE ALCODCLI='" . $codclieas400 . "' AND  
		ALTIPDIR='01'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |TipoVia Cliente
// +---------------------------------------------------------------------------+        
	public function buscar_tipovia_cliente_db2as400($ALVIADIR) {        
		include("application/config/conexdb_db2.php");
		$sql ="select EUDSCABR, EUDSCCOR FROM LIBPRDDAT.MMEUREL0 WHERE EUCODTBL='17' and
		EUCODELE='".$ALVIADIR."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}

	// +---------------------------------------------------------------------------+    
// |Departamento Cliente
// +---------------------------------------------------------------------------+        
	public function buscar_departamento_cliente_db2as400($ALDEPART) {        
		include("application/config/conexdb_db2.php");
		$sql ="select EUDSCCOR,EUDSCABR FROM LIBPRDDAT.MMEUREL0 WHERE EUCODTBL='19' and
		EUCODELE='".$ALDEPART."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}

	// +---------------------------------------------------------------------------+    
// |Provincia Cliente
// +---------------------------------------------------------------------------+        
	public function buscar_provincia_cliente_db2as400($ALDEPART,$ALPROVIN) {        
		include("application/config/conexdb_db2.php");
		$sql ="select BIDSCCOR,BIPROVIN FROM LIBPRDDAT.MMBIREL0 WHERE BIDEPART='".$ALDEPART."' and 
		BIPROVIN='".$ALPROVIN."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}

	
// +---------------------------------------------------------------------------+    
// |Distrito Cliente
// +---------------------------------------------------------------------------+        
	public function buscar_distrito_cliente_db2as400($ALDEPART,$ALPROVIN,$ALDISTRI) {        
		include("application/config/conexdb_db2.php");
		$sql ="select BJDSCCOR,BJDISTRI FROM LIBPRDDAT.MMBJREL0 WHERE BJDEPART='".$ALDEPART."' AND 
		BJPROVIN='".$ALPROVIN."' AND BJDISTRI='".$ALDISTRI."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}


		// +---------------------------------------------------------------------------+    
// |Datos de forma pago. mym
// +---------------------------------------------------------------------------+        
	public function buscar_formapgo_db2as400($SCTECIAA,$FECHA,$SCTEPDCA) {        
		include("application/config/conexdb_db2.php");
		$f= gmdate("d-m-Y", time() - 18000);
		$dd=substr($f, 0,2);
		$dm=substr($f, 3,2);
		$da=substr($f, 6,4);
		$fecha=$da.$dm.$dd;
		$sql ="select c.CBFRMPAG from LIBPRDDAT.MMCBREP c where cbcodcia='" . $SCTECIAA . "' and c.CBNROPDC='".$SCTEPDCA."' and c.CBFECDOC='".$FECHA."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}
	// +---------------------------------------------------------------------------+    
// |Datos de documentos de refe. mym
// +---------------------------------------------------------------------------+        
	public function buscar_grpdc_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA) {        
		include("application/config/conexdb_db2.php");
		$sql ="select JQNROSER,JQNROCOR FROM LIBPRDDAT.MMJQREL0 WHERE JQCODCIA='" . $SCTECIAA . "' AND  
		JQCODSUC='".$SCTESUCA."' and JQNROPDC=".$SCTEPDCA ;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}
	// +---------------------------------------------------------------------------+    
// |Datos de documentos Vencimiento. mym
// +---------------------------------------------------------------------------+        
	public function buscar_fvpdc_cliente_db2as400($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTCCLIE,$SCTETDOC) {        
		include("application/config/conexdb_db2.php");
		$sql ="select i.EIFECVCT from LIBPRDDAT.MMEIREL0 i WHERE i.EICODCIA='" . $SCTECIAA . "' and i.EICODSUC='".$SCTESUCA."' and i.EICODCLI='".$SCTCCLIE."' and i.EITIPDOC='".$SCTETDOC."' and i.EINRODOC=".$SCTEPDCA ;
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = FALSE;
		} else {            
			$data = $dato;  
		}        
		return $data;  
	}
// +---------------------------------------------------------------------------+    
// |Datos de documentos de detalle. mym
// +---------------------------------------------------------------------------+        
	public function buscar_detallepdc_cliente_db2as400($SCTECIAA,$SCTEPDCA,$SCTESERI,$SCTECORR) {        
		include("application/config/conexdb_db2.php");
		//$numchar=strlen($SCTECORR);
		//$SCTECORR=($numchar==8)?substr($SCTECORR, 1):$SCTECORR;
		
		//SP_DTRAMWB
		$sql ="select b.SDTNITEM,b.SDTCDART,b.SDTDSCAR,b.SDTCANTI,b.SDTUNIME,b.SDTPUSIG,b.SDTPUCIG,b.SDTCTIGV,b.SDTPIIGV,b.SDTCAFEC,b.SDTPTIGV,b.SDTPSITE from LIBPRDDAT.SNT_DTRAM b where  b.SDTECIA='".$SCTECIAA."' and b.SDTESERI='".$SCTESERI."' and b.SDTEPDC=".$SCTEPDCA." and b.SDTECORR='".$SCTECORR."'";
		$dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
		if (!$dato) {
			$data = false;
		} else {            
			$data = $dato;  
		}        
		return $data;  

		/*
		$rslt='';
		$param=array($SCTECIAA,$SCTEPDCA,$SCTESERI,$SCTECORR);
$sp='call LIBPRDOBJ.SP_DTRAMAWB(?,?,?,?)';
$declaracion=odbc_prepare($odbconect,$sp);
if($declaracion){
$result=odbc_execute($declaracion,$param);
if($result){
	$rslt=$result
}else{
	$rslt= false;
	//odbc_errormsg();
}
}else{
	//odbc_errormsg();
$rslt= false;	
}
return $result;
		*/
}
// +---------------------------------------------------------------------------+    
// |Datos de documentos de detalle. mym
// +---------------------------------------------------------------------------+       

function buscar_drf_cliente_db2as400($SCTECIAA,$SCTEPDC,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR){
	include("application/config/conexdb_db2.php");
	$codcia=$this->session->userdata('codcia'); 
	if($SCTECIAA!=''||$SCTETDOC!=''||$SCTEPDC!=''){
		$sql ="select b.SDTSERRF,b.SDTCORRF,b.SDTTDREF from LIBPRDDAT.SNT_DTRAM b where b.SDTEFEC=".$SCTFECEM." and b.SDTECIA='".$SCTECIAA."' and b.SDTEPDC=".$SCTEPDC." and b.SDTETDOC='". $SCTETDOC."' and b.SDTESERI='".$SCTESERI."' AND b.SDTECORR='".$SCTECORR."'";
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
function update_fe_trama($SCTECIAA,$SCTEPDC,$SCTFECEM,$SCTESERI,$SCTECORR,$ESTADO){
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
	$sql = "update LIBPRDDAT.SNT_CTRAM  SET SCTCSTST='".$ESTADO."',SCTCUSUA='MMUSRSCD', SCTCFECA=".$fecha.", SCTCHORA=".$hora.", SCTCPGMA='PGMWEBFE' WHERE SCTECIAA='".$SCTECIAA."' and SCTEPDCA=".$SCTEPDC." and SCTESERI='".$SCTESERI."' and SCTECORR='".$SCTECORR."' and SCTEFEC='".$SCTFECEM."' and SCTCSTST<>'A' and SCTCSTS='A'";			
	$res = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());				
	if (!$res):$res = 0;
		else: $res = 1;
		endif;
		return $res;
	}
// +---------------------------------------------------------------------------+    
// |Registrar TRAMA de Envio a WS en Anexos  $docufersl->responseCode,$docufersl->responseContent
// +---------------------------------------------------------------------------+       
	function set_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDC,$SCTETDOC,$SCTFECEM,$SCTESERI,$SCTECORR,$responseCode,$responseContent){
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
		function get_eventos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$responseCode){
			include("application/config/conexdb_db2.php");
			$data='';
			$sql = "select b.SACODRPT FROM LIBPRDDAT.SNT_ANEXO b where b.SACODCIA='".$SCTECIAA."' and b.SACODSUC='".$SCTESUCA."' and b.SANROINT=".$SCTEPDCA." and b.SACODRPT='".$responseCode."'";
			$dato = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = FALSE;
			} else {            
				$data = $dato; 
			}
			return $data;
		}
// +---------------------------------------------------------------------------+    
// | Consulta TRAMA si ya se Envio a WS 
// +---------------------------------------------------------------------------+       
		function get_estadosend_fe($SCTECIAA,$SCTEPDCA,$SCTESERI,$SCTECORR){
			include("application/config/conexdb_db2.php");
			$data='';
			$sql = "select b.SCTCSTST FROM LIBPRDDAT.SNT_CTRAM b where b.SCTEPDCA='".$SCTEPDCA."' and b.SCTESERI='".$SCTESERI."' and b.SCTECORR=".$SCTECORR." and b.SCTECIAA='".$SCTECIAA."' and b.SCTCSTST='A'";
			$dato = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = FALSE;
			} else {            
				$data = $dato; 
			}
			return $data;
		}

// +---------------------------------------------------------------------------+    
// | Consulta si tiene impresors asignado 
// +---------------------------------------------------------------------------+       
		function get_impresora_fe($usuaper){
			include("application/config/conexdb_db2.php");
			  //$usuaper = strtoupper($this->session->userdata('usuaper'));
			$data='';
			$sql = "select e.EUDSCLAR FROM LIBPRDDAT.MMEUREP e where e.EUCODTBL='FE' and e.EUDSCCOR='".$usuaper."' and e.EUSTS='A'";
			$dato = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = FALSE;
			} else {            
				$data = $dato; 
			}
			return $data;
		}


// +---------------------------------------------------------------------------+    
// | Consulta si tiene hay anticipo relacionado
// +---------------------------------------------------------------------------+       
		function get_anticiporelacionado_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC){
			include("application/config/conexdb_db2.php");
			$data='';
			$sql = "select count(c.F5NROPDC) as nroant FROM LIBPRDDAT.MMF5REP c WHERE c.F5CODCIA='".$SCTECIAA."' and c.F5CODSUC='".$SCTESUCA."' and c.F5TIPDOC='".$SCTETDOC."' and c.F5NROPDC='".$SCTEPDCA."'";	
				
			$dato = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
			if (!$dato) {
				$data = 0;
			} else {            
				$data = odbc_result($dato,1); 
			}
			return $data;
		}
// +---------------------------------------------------------------------------+    
// | Consulta Data de anticipos asociados para una final
// +---------------------------------------------------------------------------+       
		function get_anticipos_fe($SCTECIAA,$SCTESUCA,$SCTEPDCA,$SCTETDOC){
			include("application/config/conexdb_db2.php");
			$sql = "select a.SCTESERI,a.SCTECORR,a.SCTETDOC,a.SCTGTOTA,a.SCTCTMON,a.SCTFECEM,a.SCTECRUC,a.SCTETPDO FROM LIBPRDDAT.MMF5REP c inner join LIBPRDDAT.SNT_CTRAM a on c.F5SUCFAC=a.SCTESUCA AND CASE WHEN CHARACTER_LENGTH(TRIM(c.F5NROSER))=3 THEN SUBSTRING(TRIM(c.F5NROSER),1,2)||'0'||SUBSTRING(TRIM(c.F5NROSER),3,1) ELSE c.F5NROSER END=a.SCTESERI and c.F5NROCOR=a.SCTECORR WHERE c.F5CODCIA='".$SCTECIAA."' and c.F5CODSUC='".$SCTESUCA."' and c.F5TIPDOC='".$SCTETDOC."' and c.F5NROPDC=".$SCTEPDCA." and a.SCTIPFAC='FA'";
			$datos = odbc_exec($dbconect, $sql) or die("<p>" . odbc_errormsg());
			if (!$datos) {
				$data = false;
			} else {            
				$data = $datos; 
			}
			return $data;
		}


	}
	?>