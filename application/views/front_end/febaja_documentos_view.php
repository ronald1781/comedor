<section class="container">
  <script language="JavaScript" >
    $(document).ready(function () {

     $('#loading_spinner').hide();
     $('#loading_spinnerm').hide();
     $("#btnmostrarbajas").attr('disabled', true);
     $("#tbdocanu tr").removeClass("success"); 

     listadocumentobaja();

     $("#btnmostrarbajas").click(function () {
       listadocumentobaja();

     });



     $(".btnclosemd").click(function(){
      $("#tbdocanu tr").removeClass("success"); 
    });
     $("#btnbajadoc").click(function(e){
       e.preventDefault(); 
       if (confirm('Esta seguro de enviar la baja de documentos!'))
       {  
         set_senddocbaja();
       }
       return false;
     });
   });
    function listadocumentobaja(){
      var selmes=$('#selmes').val();
      var selanio=$('#selanio').val();
      setTimeout(function(){
        $.ajax({
          type: 'POST',
      //dataType: "json",
      data: {selanio: selanio,
        selmes:selmes},
        url: 'facturacionbaja_control/listar_bajas',
        beforeSend: function () {
          $('#loading_spinner').show();
          $("#btnmostrarbajas").attr('disabled', true);
        },
        success: function (msj) {
          $('#loading_spinner').hide();
          $("#btnmostrarbajas").attr('disabled', false);
          try {

            if (msj.existe === 1) {
              var html=msj.tbody;
              $('#tbdocanu tbody').html(html);
            }
          }  catch (e) {
            alert('Exception while request..'+e);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr+' '+ ajaxOptions +' '+ thrownError);
        }
      });
      }, 1000);

    }

    function get_DetalleAnulacion(fecha,id)
    {
      $("#tbdocanu tr").removeClass("success"); 
      $("#tbdocanu #"+id).addClass("success");
      var fecha=fecha;      
      $.ajax({
        url: "facturacionbaja_control/get_detalledocumentobaja",
        type: "post",
        data:{fecha:fecha},
        dataType: "JSON",
        beforeSend: function () {
          $('#loading_spinner').show();          
        },
        success: function (msj)    
        {
          $('#loading_spinner').hide();
          try {
            if (msj.existe === 1) {
              var html=msj.tbody;
              var nommes = msj.nommes;
              var fecha =msj.fechaas;

              $('#mdldetabajadocumento').modal('show'); 
              $('.modal-title').text('Detalle de documentos anulados de '+nommes);             
              
              $('#fechaas').val(fecha);
              $('#tbdetadocanu tbody').html(html);
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
    function set_sendAnulacion(dataform)
    {
      var bajadoc=dataform;      
      $.ajax({
        url: "facturacionbaja_control/set_senddocbaja",
        type: "post",
        data:{bajadoc:bajadoc},
        dataType: "JSON",
        beforeSend: function () {
          $('#loading_spinnerm').show();          
        },
        success: function (msj)    
        {
          console.log(msj);
          /*
          $('#loading_spinnerm').hide();
          try {
            if (msj.existe === 1) {
              var rptxml=msj.tbody;
             alert('Respuesta: '+rptxml);
            }
          }  catch (e) {
            alert('Exception while request..'+e);
          }
          */
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
          alert(xhr+' '+ ajaxOptions +' '+ thrownError);
        }
      });
    }
function set_senddocbaja1(formId){

}
    function set_senddocbaja(){
     
  var bajadoc = $("#frmdocanu").serialize(); 

 
     $.ajax({
      url: "facturacionbaja_control/set_senddocbaja",
      type: "post",
      data:bajadoc,
      beforeSend: function () {
        $('#loading_spinnerm').show();  
        $("#btnbajadoc").attr('disabled', true); 
        $("#btnbajadoc").text('Procesando .......');       
      },        
      success: function (msj,textStatus, jqXHR)    
      {
        $('#loading_spinnerm').hide();
        $("#btnbajadoc").attr('disabled', false);
        $("#btnbajadoc").html('<span class="glyphicon glyphicon-cloud-upload"></span>Enviar');
          //console.log(msj);
          try {
            if (msj.existe === 1) {
              var rptxml=msj.sendxml; 
              var rptws=msj.msgxml;
              var i,conta=0;
              var html='';

for(i=0;i<rptws.length;i++){
  var cad=rptws[i];
html+='<li>'+cad['rslbd2']+' '+cad['datadocu']+' '+cad['rpta']+'</li>';

}
              $("#mdldetabajadocumento").modal('hide');          
              $('#mdldetabajadocumentoxml .modal-body').html(rptxml+'<br> '+html);
              $('#mdldetabajadocumentoxml').modal('show');

            }else{
              var rptxml=msj.sendxml; 
              var rptws=msj.msgxml;              
              
              alert('Error!!:'+rptxml+' '+rptws['msjxml']);
            }
          }  catch (e) {
            alert('Exception while request..'+e);
          }          
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
          alert(xhr+' '+ ajaxOptions +' '+ thrownError);
          $('#loading_spinnerm').hide();
          $("#btnbajadoc").attr('disabled', false);
        }
      });
     
     
   }
 </script>
 <div class="row">
  <div class="panel panel-default">
    <div class="panel-body">          
      <div class="row"> 
       <div class="col-md-12">
        <h3><p>Lista de cantidad de documentos anulados por dia segun mes</p></h3>
        <form class="form-inline" action="">
          <div class="form-group">
            <label for="selanio">AÑO</label>
            <select class="form-control" id="selanio">
              <?php 
              $anio=date("Y");
              if(count($lstanio)>0){
                for ($i=0; $i < count($lstanio); $i++) { 
                  $cad=$lstanio[$i];
                  $anio=$cad['anio'];
                  $selected=($anio==$anio)?'selected=""':'';
                  ?>
                  <option <?php echo $selected; ?> value="<?php echo $anio ?>"><?php echo $anio;?></option>
                  <?php
                }
              }else{?>
                <option selected="" value="<?php echo date("Y");?>"><?php echo date("Y");?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="selmes">MES</label>
            <select class="form-control" id="selmes">
              <?php 
              $m=date("m");
              if(count($lstmes)>0){
                for ($i=0; $i < count($lstmes); $i++) {
                  $cad=$lstmes[$i];
                  $mes=$cad['mes'];
                  $dscmes=$cad['dscmes'];
                  $selected=($m==$mes)?'selected=""':'';
                  ?>
                  <option <?php echo $selected; ?> value="<?php echo $mes ?>"><?php echo $dscmes;?></option>
                  <?php
                }
              }else{?>
                <option selected="" value="<?php echo date("m");?>"><?php echo mes_letra(date("m"));?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <button type="button" class="btn btn-info" id="btnmostrarbajas"><span class="glyphicon glyphicon-list"></span>&nbsp;Mostrar</button>

          <!--<button type="button" class="btn btn-primary" id="btnsendbajas"><span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;Enviar</button>-->
        
        

</form>
      </div>

      <div class="form-group gifCarga"><img id="loading_spinner" src="assest/imagen/loading8.gif" style="display: none;"></div>

    </div>
    <br>
    <div class="col-md-12"> 

      <table class="table table-bordered" id="tbdocanu">
        <thead>
          <tr>

            <th>N°</th>
            <th>FECHA</th>
            <th>FACTURAS</th>
            <th>BOLETAS</th>
            <th>NOTA CREDITO</th>
            <th>NOTA DEBITO</th>
            <th>TOTAL</th>
            <th>ACCION</th>
          </tr>
        </thead>
        <tbody>


        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</div>  
</div>
<div class="modalload"><!-- Place at bottom of page --></div>
<!-- Modal -->
<div class="modal fade" id="mdldetabajadocumento" role="dialog" > 
 <div class="modal-dialog modal-lg">
  <div class="modal-content">
    <!-- Modal Header -->
    <div class="modal-header">
      <button type="button" class="close btnclosemd" data-dismiss="modal">
        <span aria-hidden="true">×</span>
        <span class="sr-only">Close</span>
      </button>
      <h4 class="modal-title" id="myModalLabel">Detalle de documentos del  </h4>
    </div>
    <!-- Modal Body  action="<php echo base_url(); ?>facturacionbaja_control/set_senddocbaja"-->
      <form role="form" class="form-horizontal" name="frmdocanu" id="frmdocanu">
        <div class="modal-body">
          <div class="form-group gifCargam"><img id="loading_spinnerm" src="assest/imagen/loading8.gif" style="display: none;"></div>
          <p class="statusMsg"></p>  

          <input type="hidden" name="fechaas" id="fechaas" value=""></input>
          <table class="table table-bordered" id="tbdetadocanu">
            <thead>
              <tr>
                <th>N°</th>
                <th>SUC.</th>
                <th>T.D.</th>
                <th>SUNAT</th>
                <th>MONEDA</th>
                <th>INTERNO</th>
                <th>CLIENTE</th>
                <th>TOTAL</th>
                <th>ESTADO</th>
                <th>ACCION</th>
                 <th></th>
              </tr>
            </thead>
            <tbody>


            </tbody>
          </table>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <input type="checkbox" name="chksndxml" id="chksndxml" value="1">Solo Generar XML y Enviar
          <button type="button" class="btn btn-danger btnclosemd" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnbajadoc"><span class="glyphicon glyphicon-cloud-upload"></span>Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="mdldetabajadocumentoxml" role="dialog" > 
 <div class="modal-dialog modal-lg">
  <div class="modal-content">
    <!-- Modal Header -->
    <div class="modal-header">
      <button type="button" class="close btnclosemd" data-dismiss="modal">
        <span aria-hidden="true">×</span>
        <span class="sr-only">Close</span>
      </button>
      <h4 class="modal-title" id="myModalLabel">Detalle de documentos anulados por enviar en xml </h4>
    </div>
    <!-- Modal Body  action="<php echo base_url(); ?>facturacionbaja_control/set_senddocbaja"-->
      <form role="form" class="form-horizontal" name="frmdocanu" id="frmdocanu" enctype='application/json' method="POST" >
        <div class="modal-body">


        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btnclosemd" id="cancel-btn" data-dismiss="modal">Cerrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<style type="text/css">

.selected {
  background: red;
}
.modal .fade .in{}

</style>
</section>