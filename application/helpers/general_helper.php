<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('mes_letra'))
{
  function mes_letra($mes) {
    SWITCH($mes){
      CASE "01":
      $mes='Enero';
      break;
      CASE "02":
      $mes='Febrero';
      break;
      CASE "03":
      $mes='Marzo';
      break; 
      CASE "04":
      $mes='Abril';
      break;
      CASE "05":
      $mes='Mayo';
      break;
      CASE "06":
      $mes='Junio';
      break;
      CASE "07":
      $mes='Julio';
      break; 
      CASE "08":
      $mes='Agosto';
      break;
      CASE "09":
      $mes='Setiembre';
      break;
      CASE "10":
      $mes='Octubre';
      break;
      CASE "11":
      $mes='Noviembre';
      break; 
      CASE "12":
      $mes='Diciembre';
      break;
      default: 
      $mes='No existe';
      break;

    }
    return $mes;
  }
}

class EnLetras
{
  var $Void = "";
  var $SP = " ";
  var $Dot = ".";
  var $Zero = "0";
  var $Neg = "Menos";
  
  function ValorEnLetras($x, $Moneda ) 
  {
    $s="";
    $Ent="";
    $Frc="";
    $Signo="";

    if(floatVal($x) < 0)
     $Signo = $this->Neg . " ";
   else
     $Signo = "";

    if(intval(number_format($x,2,'.','') )!=$x) //<- averiguar si tiene decimales
    $s = number_format($x,2,'.','');
    else
      $s = number_format($x,0,'.','');

    $Pto = strpos($s, $this->Dot);

    if ($Pto === false)
    {
      $Ent = $s;
      $Frc = $this->Void;
    }
    else
    {
      $Ent = substr($s, 0, $Pto );
      $Frc =  substr($s, $Pto+1);
    }

    if($Ent == $this->Zero || $Ent == $this->Void)
     $s = "Cero ";
   elseif( strlen($Ent) > 7)
   {
     $s = $this->SubValLetra(intval( substr($Ent, 0,  strlen($Ent) - 6))) . 
     "Millones " . $this->SubValLetra(intval(substr($Ent,-6, 6)));
   }
   else
   {
    $s = $this->SubValLetra(intval($Ent));
  }

  if (substr($s,-9, 9) == "Millones " || substr($s,-7, 7) == "Millón ")
   $s = $s . "de ";

    //$s = $s . $Moneda;
 $s = $s;    

 if($Frc != $this->Void)
 {
       //$s = $s . " con " . $this->SubValLetra(intval($Frc)) . "Centavos";
   $s = $s . " con " . $Frc . "/100";
 }
    //return ($Signo . $s . " M.N.");
 return ($Signo . $s . '  '.$Moneda);    

}


function SubValLetra($numero) 
{
  $Ptr="";
  $n=0;
  $i=0;
  $x ="";
  $Rtn ="";
  $Tem ="";

  $x = trim("$numero");
  $n = strlen($x);

  $Tem = $this->Void;
  $i = $n;

  while( $i > 0)
  {
   $Tem = $this->Parte(intval(substr($x, $n - $i, 1). 
     str_repeat($this->Zero, $i - 1 )));
   If( $Tem != "Cero" )
   $Rtn .= $Tem . $this->SP;
   $i = $i - 1;
 }


    //--------------------- GoSub FiltroMil ------------------------------
 $Rtn=str_replace(" Mil Mil", " Un Mil", $Rtn );
 while(1)
 {
   $Ptr = strpos($Rtn, "Mil ");       
   If(!($Ptr===false))
   {
    If(! (strpos($Rtn, "Mil ",$Ptr + 1) === false ))
    $this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
    Else
    break;
  }
  else break;
}

    //--------------------- GoSub FiltroCiento ------------------------------
$Ptr = -1;
do{
 $Ptr = strpos($Rtn, "Cien ", $Ptr+1);
 if(!($Ptr===false))
 {
  $Tem = substr($Rtn, $Ptr + 5 ,1);
  if( $Tem == "M" || $Tem == $this->Void)
   ;
 else          
   $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
}
}while(!($Ptr === false));

    //--------------------- FiltroEspeciales ------------------------------
$Rtn=str_replace("Diez Un", "Once", $Rtn );
$Rtn=str_replace("Diez Dos", "Doce", $Rtn );
$Rtn=str_replace("Diez Tres", "Trece", $Rtn );
$Rtn=str_replace("Diez Cuatro", "Catorce", $Rtn );
$Rtn=str_replace("Diez Cinco", "Quince", $Rtn );
$Rtn=str_replace("Diez Seis", "Dieciseis", $Rtn );
$Rtn=str_replace("Diez Siete", "Diecisiete", $Rtn );
$Rtn=str_replace("Diez Ocho", "Dieciocho", $Rtn );
$Rtn=str_replace("Diez Nueve", "Diecinueve", $Rtn );
$Rtn=str_replace("Veinte Un", "Veintiun", $Rtn );
$Rtn=str_replace("Veinte Dos", "Veintidos", $Rtn );
$Rtn=str_replace("Veinte Tres", "Veintitres", $Rtn );
$Rtn=str_replace("Veinte Cuatro", "Veinticuatro", $Rtn );
$Rtn=str_replace("Veinte Cinco", "Veinticinco", $Rtn );
$Rtn=str_replace("Veinte Seis", "Veintiseís", $Rtn );
$Rtn=str_replace("Veinte Siete", "Veintisiete", $Rtn );
$Rtn=str_replace("Veinte Ocho", "Veintiocho", $Rtn );
$Rtn=str_replace("Veinte Nueve", "Veintinueve", $Rtn );

    //--------------------- FiltroUn ------------------------------
If(substr($Rtn,0,1) == "M") $Rtn = "Un " . $Rtn;
    //--------------------- Adicionar Y ------------------------------
for($i=65; $i<=88; $i++)
{
  If($i != 77)
  $Rtn=str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
}
$Rtn=str_replace("*", "a" , $Rtn);
return($Rtn);
}


function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr)
{
  $x = substr($x, 0, $Ptr)  . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
}


function Parte($x)
{
  $Rtn='';
  $t='';
  $i='';
  Do
  {
    switch($x)
    {
     Case 0:  $t = "Cero";break;
     Case 1:  $t = "Un";break;
     Case 2:  $t = "Dos";break;
     Case 3:  $t = "Tres";break;
     Case 4:  $t = "Cuatro";break;
     Case 5:  $t = "Cinco";break;
     Case 6:  $t = "Seis";break;
     Case 7:  $t = "Siete";break;
     Case 8:  $t = "Ocho";break;
     Case 9:  $t = "Nueve";break;
     Case 10: $t = "Diez";break;
     Case 20: $t = "Veinte";break;
     Case 30: $t = "Treinta";break;
     Case 40: $t = "Cuarenta";break;
     Case 50: $t = "Cincuenta";break;
     Case 60: $t = "Sesenta";break;
     Case 70: $t = "Setenta";break;
     Case 80: $t = "Ochenta";break;
     Case 90: $t = "Noventa";break;
     Case 100: $t = "Cien";break;
     Case 200: $t = "Doscientos";break;
     Case 300: $t = "Trescientos";break;
     Case 400: $t = "Cuatrocientos";break;
     Case 500: $t = "Quinientos";break;
     Case 600: $t = "Seiscientos";break;
     Case 700: $t = "Setecientos";break;
     Case 800: $t = "Ochocientos";break;
     Case 900: $t = "Novecientos";break;
     Case 1000: $t = "Mil";break;
     Case 1000000: $t = "Millón";break;
   }

   If($t == $this->Void)
   {
    $i = $i + 1;
    $x = $x / 1000;
    If($x== 0) $i = 0;
  }
  else
   break;

}while($i != 0);

$Rtn = $t;
Switch($i)
{
 Case 0: $t = $this->Void;break;
 Case 1: $t = " Mil";break;
 Case 2: $t = " Millones";break;
 Case 3: $t = " Billones";break;
}
return($Rtn . $t);
}

}


function num_to_letras($numero, $moneda, $subfijo = 'M.N.')
{

	$subfijo=($moneda=='USD')?'DOLARES AMERICANOS':'SOLES';
  $xarray = array(
    0 => 'Cero'
    , 1 => 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
    , 'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'
    , 'VEINTI', 30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA'
    , 60 => 'SESENTA', 70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA'
    , 100 => 'CIENTO', 200 => 'DOSCIENTOS', 300 => 'TRESCIENTOS', 400 => 'CUATROCIENTOS', 500 => 'QUINIENTOS'
    , 600 => 'SEISCIENTOS', 700 => 'SETECIENTOS', 800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS'
  );

  $numero = trim($numero);
  $xpos_punto = strpos($numero, '.');
  $xaux_int = $numero;
  $xdecimales = '00';
  if (!($xpos_punto === false)) {
    if ($xpos_punto == 0) {
      $numero = '0' . $numero;
      $xpos_punto = strpos($numero, '.');
    }
        $xaux_int = substr($numero, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($numero . '00', $xpos_punto + 1, 2); // obtengo los valores decimales
      }

    $XAUX = str_pad($xaux_int, 18, ' ', STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = '';
    for ($xz = 0; $xz < 3; $xz++) {
      $xaux = substr($XAUX, $xz * 6, 6);
      $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
              }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
              switch ($xy) {
                    case 1: // checa las centenas
                    $key = (int) substr($xaux, 0, 3);
                        if (100 > $key) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                          /* do nothing */
                        } else {
                            if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                              $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (100 == $key) {
                                  $xcadena = ' ' . $xcadena . ' CIEN ' . $xsub;
                                } else {
                                  $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                                }
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                              $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = ' ' . $xcadena . ' ' . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                    $key = (int) substr($xaux, 1, 2);
                    if (10 > $key) {
                      /* do nothing */
                    } else {
                      if (TRUE === array_key_exists($key, $xarray)) {
                        $xseek = $xarray[$key];
                        $xsub = subfijo($xaux);
                        if (20 == $key) {
                          $xcadena = ' ' . $xcadena . ' VEINTE ' . $xsub;
                        } else {
                          $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                        }
                        $xy = 3;
                      } else {
                        $key = (int) substr($xaux, 1, 1) * 10;
                        $xseek = $xarray[$key];
                        if (20 == $key)
                          $xcadena = ' ' . $xcadena . ' ' . $xseek;
                        else
                          $xcadena = ' ' . $xcadena . ' ' . $xseek . ' Y ';
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                    $key = (int) substr($xaux, 2, 1);
                        if (1 > $key) { // si la unidad es cero, ya no hace nada
                          /* do nothing */
                        } else {
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO
        # si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
        if ('ILLON' == substr(trim($xcadena), -5, 5)) {
          $xcadena.= ' DE';
        }

        # si la cadena obtenida en MILLONES o BILLONES, entonces le agrega al final la conjuncion DE
        if ('ILLONES' == substr(trim($xcadena), -7, 7)) {
          $xcadena.= ' DE';
        }

        # depurar leyendas finales
        if ('' != trim($xaux)) {
          switch ($xz) {
            case 0:
            if ('1' == trim(substr($XAUX, $xz * 6, 6))) {
              $xcadena.= 'UN BILLON ';
            } else {
              $xcadena.= ' BILLONES ';
            }
            break;
            case 1:
            if ('1' == trim(substr($XAUX, $xz * 6, 6))) {
              $xcadena.= 'UN MILLON ';
            } else {
              $xcadena.= ' MILLONES ';
            }
            break;
            case 2:
            if (1 > $numero) {
              $xcadena = "CERO {$xdecimales}/100 {$subfijo}";
            }
            if ($numero >= 1 && $numero < 2) {
              $xcadena = "UN {$xdecimales}/100 {$subfijo}";
            }
            if ($numero >= 2) {
                        $xcadena.= " Y {$xdecimales}/100 {$subfijo}"; //
                      }
                      break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")

        $xcadena = str_replace('VEINTI ', 'VEINTI', $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace('  ', ' ', $xcadena); // quito espacios dobles
        $xcadena = str_replace('UN UN', 'UN', $xcadena); // quito la duplicidad
        $xcadena = str_replace('  ', ' ', $xcadena); // quito espacios dobles
        $xcadena = str_replace('BILLON DE MILLONES', 'BILLON DE', $xcadena); // corrigo la leyenda
        $xcadena = str_replace('BILLONES DE MILLONES', 'BILLONES DE', $xcadena); // corrigo la leyenda
        $xcadena = str_replace('DE UN', 'UN', $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
  }

/**
 * Esta función regresa un subfijo para la cifra
 * 
 * @author Ultiminio Ramos Galán <contacto@ultiminioramos.com>
 * @param string $cifras La cifra a medir su longitud
 */
function subfijo($cifras)
{
  $cifras = trim($cifras);
  $strlen = strlen($cifras);
  $_sub = '';
  if (4 <= $strlen && 6 >= $strlen) {
    $_sub = 'MIL';
  }

  return $_sub;
}
if ( ! function_exists('datos_webservice'))
{
  function datos_webserviceAcceso($ruc) {
    $datos='';
    SWITCH($ruc){
      CASE "20101759688":
      $usuario="20101759688mymrep02"; 
      $clave="Mymrep2019*";
      $datos=["usuario"=>$usuario,"clave"=>$clave];
      break;
      CASE "20603215576":
      $usuario="20603215576mymrep04";
      $clave="Mymrep2019*"; 
     $datos=["usuario"=>$usuario,"clave"=>$clave];
      break;
      CASE "20603215649":
      $usuario="20603215649mymrep06";
      $clave="Mymrep2019*"; 
      $datos=["usuario"=>$usuario,"clave"=>$clave];
      break;
    }
    return $datos;
  }
}

if ( ! function_exists('datos_webservice'))
{
  function datos_webservice($ipas) {
    $datos='';
    SWITCH($ipas){
      CASE "192.168.1.110":
      $url="https://escondatagate.net/wsParser_2_1/rest/parserWS"; 
      $urlref="https://escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
      CASE "192.168.1.105":
      $url="https://calidad.escondatagate.net/wsParser_2_1/rest/parserWS";
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
      CASE "192.168.1.92":
      $url="https://calidad.escondatagate.net/wsParser_2_1/rest/parserWS";
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
    }
    return $datos;
  }
}
if ( ! function_exists('datos_webserviceBaja'))
{
  function datos_webserviceBaja($ipas) {
    $datos='';
    SWITCH($ipas){
      CASE "192.168.1.110":      
      $url="https://escondatagate.net/wsParser/rest/parserWS"; 
      $urlref="https://escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
      CASE "192.168.1.105":       
      $url="https://calidad.escondatagate.net/wsParser/rest/parserWS";
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
       CASE "192.168.1.92":      
      $url="https://calidad.escondatagate.net/wsParser/rest/parserWS";
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
    }
    return $datos;
  }
}


if ( ! function_exists('datos_webserviceTicket'))
{
  function datos_webserviceTicket($ipas) {
    $datos='';    
    SWITCH($ipas){
      CASE "192.168.1.110":     
      $url="https://escondatagate.net/wsBackend/clients/getStatus"; 
      $urlref="https://escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
      CASE "192.168.1.105": 
      $url="https://calidad.escondatagate.net/wsBackend/clients/getStatus";
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
       break;
      CASE "192.168.1.92":
      $url="https://calidad.escondatagate.net/wsBackend/clients/getStatus";
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
    }
    return $datos;
  }
}
if ( ! function_exists('datos_webserviceCdr'))
{
  function datos_webserviceCdr($ipas) {
    $datos='';    
    SWITCH($ipas){
      CASE "192.168.1.110":
      $url="https://escondatagate.net/wsBackend/clients/getDocumentCDR"; 
      $urlref="https://escondatagate.net";

      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
      CASE "192.168.1.105": 
      $url="https://calidad.escondatagate.net/wsBackend/clients/getDocumentCDR"; 
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
      CASE "192.168.1.92": 
      $url="https://calidad.escondatagate.net/wsBackend/clients/getDocumentCDR"; 
      $urlref="https://calidad.escondatagate.net";
      $datos=["url"=>$url,"urlref"=>$urlref];
      break;
    }

    return $datos;
  }
}
if ( ! function_exists('dato_hostas'))
{
  function dato_hostas() {
    $fp = fopen("./server.txt", "r");
    $server = fgets($fp);
    fclose($fp);
    $ipas='';
    $nomhost='';
    $sts='';
    $datos=array();
    if($server=='192.168.1.110'){
      $ipas="192.168.1.110"; 
      $nomhost='PRODUCCION';
      $sts='A';
    }elseif($server=='192.168.1.92'){
      $ipas=$server;
      $nomhost='CALIDAD';
      $sts='A';
    }elseif($server=='192.168.1.105'){
      $ipas=$server;
      $nomhost='DESARROLLO';
      $sts='A';
    }else{
      $ipas=$server;
      $nomhost='NO EXISTE';
      $sts='A';
    }   

    $datos=['ipas'=>$ipas,'nomhost'=>$nomhost,'sts'=>$sts];
    return $datos;
  }
}


if ( ! function_exists('cerrar_odbc'))
{
  function cerrar_odbc() {
    $cerrar='';
    //$cerrar=close_odbc();
    return $cerrar;
  }
}

if ( ! function_exists('nompgmas400'))
{
  function nompgmas400() {
    $pgm='';
    $pgm="MMFEWEBSVR";
    return $pgm;
  }
}


?>