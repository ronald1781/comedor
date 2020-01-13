
<!DOCTYPE html  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8">
  <base href="<?php echo base_url(); ?>"></base>
  <title><?php echo $titulo ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="assest/imagen/fe.ico" />        
  <link href="assest/css/login.css" rel="stylesheet" type="text/css"></link>           
  <link href="assest/css/rrgstilos.css" rel="stylesheet" type="text/css"></link>           
  <link href="assest/css/bootstrap.css" rel="stylesheet" type="text/css"></link>  
  <script src="assest/js/bootstrap.js"></script> 
  <script src="assest/js/jquery.min.js"></script>

  <script type="text/javascript">
    var valor=0;
    function carga_data(){
      check_datafe();
      sendfe_paraprocesar_baja();
      $('#datoproc').html('');
    }
    var myVar;
    var proceso=false;
    var hora='23:56';
    $(document).ready(function() {
     var miliseconds =9000;  
     myVar = setInterval(carga_data, miliseconds);
   });

    function check_datafe(){
      $.ajax({
        type:"POST",
        url:"procesar_facturas_control/get_verificar_existe_fact",
        data:"&hora"+hora,
        dataType: "JSON",
        beforeSend: function () {
          $('#loading_spinner').show();          
        },
        success:function(data){
         $('#loading_spinner').hide();
         var conta=0;
         var i;
         var datas=''; 
         var cia='';
         var fecha='';
         var nropdc='';
         var serie='';
         var corr='';  
         var aler='';  
         var valor=0; 

         try {
          if (data.proceso === true) {
            dato=data.datos;
            count=dato.length;           
            for(i=0;i<count;i++){
              conta=conta+1;
              cad=dato[i];         
              datas+='<li class="list-group-item"><strong>'+conta+'</strong> '+cad['SCTECIAA']+' '+cad['SCTEFEC']+' '+cad['SCTEPDCA']+' '+cad['SCTESERI']+' '+cad['SCTECORR']+'</li>'; 
              cia=cad['SCTECIAA'];
              fecha=cad['SCTEFEC'];
              nropdc=cad['SCTEPDCA'];
              serie=cad['SCTESERI'];
              corr=cad['SCTECORR'];
              sendfe_paraprocesar("'"+cia+"'",fecha,nropdc,"'"+serie+"'","'"+corr+"'");                  
            }          

            var info =   '<div class="alert alert-success"><ul class="list-group">'+datas+'</ul></div>';
            $('#datoproc').html(info);  
          }else{
           datas='Sin Datos';
           $('#datoproc').html(datas); 
         }

       }  catch (e) {
        alert('Exception while request..'+e);
      }

    },
    error: function (xhr, ajaxOptions, thrownError)
    {
      alert(xhr+' '+ ajaxOptions +' '+ thrownError);
    } 
  });  
    }

    function sendfe_paraprocesar(cia,fecha,nropdc,serie,corr){
     $.ajax({
      type:"POST",
      url:"facturacionsendws_control/plantillaJSONFE",
      data:{nropdc:nropdc,
        serie:serie,
        corr:corr,
        fecha:fecha,
        cia:cia},
        dataType: "JSON",
        success:function(data){         
         var conta=0;
         var i;
         var datas='';            
         try {
          if (data.proceso === true) {
            $('#datoproc').html(datas);  
          }else{
           datas='<span class="label label-danger">Sin Datos</span>';
           $('#datoproc').html(datas); 
         }
       }  catch (e) {
         $('#datoproc').html(e); 
       }
     },
     error: function (xhr, ajaxOptions, thrownError)
     {
      var dataerr=xhr+' '+ ajaxOptions +' '+ thrownError;
      $('#datoproc').html(dataerr); 
    } 
  });    

   }

   function sendfe_paraprocesar_baja(){

    $.ajax({
      type:"POST",
      url:"facturacionsendbajaws_baja_control/get_anulacionmanual",
      data:{},
      dataType: "JSON",
      success:function(data){         
       var conta=0;
       var i;
       var datas='';             
       try {
        if (data.existe === 1) {
          var dato=data.datos;  
          var rpta='Mensaje:'+data.msg+' Tiempo:'+dato.tiempo1+' Respuesta:'+dato.rptp+' Cantidad documento:'+dato.numbaja;

          datas='<span class="label label-success"> Baja de documentos rpta: '+rpta+'</span>';
          $('#datoprocbaj').html(datas);  
        }else{
          var dato=data.datos;  
          var rpta='Mensaje:'+data.msg+' Tiempo:'+dato.tiempo1+' Respuesta:'+dato.rptp+' Cantidad documento:'+dato.numbaja;
          datas='<span class="label label-danger"> Baja de documentos rpta:'+ rpta+'</span>';
          $('#datoprocbaj').html(datas); 
        }
      }  catch (e) {
        datas='<span class="label label-danger">Exception while request..'+e+'</span>';
        $('#datoprocbaj').html(datas);
      }
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
     
      datas='<span class="label label-danger">Error :'+xhr+' '+ ajaxOptions +' '+ thrownError+'</span>';
      $('#datoprocbaj').html(datas);
    } 
  });     
    
  }
  /*
  function optener_status_webservice(cia){
   $.ajax({
      type:"POST",
      url:"procesar_facturas_control/optener_status_webservice",
       data:{
        cia:cia},
        dataType: "JSON",
        success:function(data){         
         var conta=0;
         var i;
         var datas='';             
         try {
          if (data.proceso === true) {
            $('#datoproc').html(datas);  
          }else{
           datas='<span class="label label-danger">Sin Datos</span>';
           $('#datoproc').html(datas); 
         }
       }  catch (e) {
         $('#datoproc').html(e); 
      }
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
      var dataerr=xhr+' '+ ajaxOptions +' '+ thrownError;
      $('#datoproc').html(dataerr); 
    } 
  });    
    
  }
  */

</script>
</head>
<body>
  <section class="container">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">MYM</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Procesoar</a></li>
        </ul>
      </div>
    </nav>
    <div class="jumbotron">
      <h1>PROCESAR FACTURA ELECTRONICA</h1>      
      <p>Se procesa las Facturas, Boleta, Nota de credito, Nota de debito.</p>
    </div>
    <div>
     <div class="form-group gifCarga"><img id="loading_spinner" src="assest/imagen/loading8.gif" style="display: none;"></div>
     <p id="datoproc"></p>  
     <p id="datoprocbaj"></p>  
   </div>     
 </div>
</section>
</body>
</html>