<?php

class Model_db2 extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

// +---------------------------------------------------------------------------+    
// |Sucursal
// +---------------------------------------------------------------------------+        
    public function get_sucursal($BMCODSUC) {        
        include("application/config/conexdb_db2.php");      
        $sql = "select EUCODELE,EUDSCCOR from LIBPRDDAT.MMEUREL0 WHERE EUCODTBL='24' AND EUCODELE='" . $BMCODSUC . "'";
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
