<?php

class Login_model_db2 extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

// +---------------------------------------------------------------------------+    
// | Validad acceso al programa del usuario
// +---------------------------------------------------------------------------+        
    public function validapgmusr($usuario,$password,$codusuario) {   
        include("application/config/conexdb2_login.php");
        $pgm=nompgmas400();
                 if ($result == 1) {
            $sql = "select count(b.HBCODPER) as numfil FROM LIBPRDDAT.MMHBREP b where b.HBCODPER='".$codusuario."' AND b.HBCODPRO='".$pgm."' and HBSTS='A'";
                
           $dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());

               $result= odbc_result($dato, 1);
        } else {
            $result =FALSE;
            
        }
        return $result;
    }

// +---------------------------------------------------------------------------+    
// | Validad numero de Cia del usuario
// +---------------------------------------------------------------------------+        
    public function validanroCia($usuario,$password) {   
        include("application/config/conexdb2_login.php");
        if ($result == 1) {
            $sql = "select BMCODCIA, BMCODPER from LIBPRDDAT.MMBMREL0 WHERE BMUSERID='" . mb_strtoupper($usuario) . "' AND BMSTS='A'";       
    $datos = odbc_exec($dbconect, $sql);
           $result= $datos;
        } else {
            $result = '0';
            
        }
        return $result;
cerrar_odbc();
    }

// +---------------------------------------------------------------------------+    
// | Listar Cia
// +---------------------------------------------------------------------------+        
    public function listarCia($cias) {   
        include("application/config/conexdb_db2.php");           
            $sql = "select EUCODELE, EUDSCABR, EUDSCLAR,EUJOB from LIBPRDDAT.MMEUREP WHERE  EUCODTBL='01' AND EUSTS='A' AND EUCODELE ='" . $cias . "'";
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
// | Validad Loguin Acceso
// +---------------------------------------------------------------------------+        
    public function validaLoguin($usuario, $password,$cia) {        
        include("application/config/conexdb2_login.php");
        if ($result == 1) {
            $sql = "select BMCODCIA,BMCODPER,BMCODSUC,BMUSERID,BMPRAPLL,BMPRNOMB,BMTIPPER from LIBPRDDAT.MMBMREL0 WHERE BMUSERID='" . mb_strtoupper($usuario) . "' AND BMCODCIA='".$cia."'";
            
            $datos = odbc_exec($dbconect, $sql);
            return $datos;
        } else {
            $result = '0';
            return $result;
        }
        cerrar_odbc();
    }


// +---------------------------------------------------------------------------+    
// | RUC cliente
// +---------------------------------------------------------------------------+        
    public function get_cliente_ruc($codclieas400) {        
        include("application/config/conexdb_db2.php"); 
        $sql = "select a.AKCODCLI,a.AKRAZSOC,b.IFNVORUC,a.AKTIPIDE,a.AKNROIDE from LIBPRDDAT.MMAKREL0 a left join LIBPRDDAT.MMIFREL0 b ON b.IFCODCLI=a.AKCODCLI WHERE a.AKCODCLI ='" . $codclieas400 . "'";
        $dato = odbc_exec($dbconect, $sql)or die("<p>" . odbc_errormsg());
        if (!$dato) {
            $data = FALSE;
        } else {            
            $data = $dato;  
        }        
        return $data;  
        cerrar_odbc();
    }


}

?>
