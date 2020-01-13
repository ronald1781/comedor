<section class="container">
	
	<ol class="breadcrumb">
		<li><a href="#">Cocina</a></li>
		<!--<li><a href="#">Library</a></li> fdsd,fhst,ffnpd -->
		<li class="active">Menus</li>
	</ol>
	<?php
  date_default_timezone_set("America/Lima");
  ?>
  <div class="row">
    <div class="row" id="crearhmenu">
     <div class="col-md-12">
      <form class="form-inline">
        <div class="form-group">
          <label for="txtnomm">menu</label>
          <input type="text" class="form-control" id="txtnomm" name="txtnomm" placeholder="Descripcion" value="" required="" disabled="">
        </div> 
        <div class="form-group">
          <label for="fdsd">desde</label>
          <input type="date" class="form-control" id="fdsd" name="fdsd" placeholder="Menu desde" required="">
        </div>
        <div class="form-group">
          <label for="fhst">hasta</label>
          <input type="date" class="form-control" id="fhst" name="fhst" placeholder="Fecha Fin pedido" required="">
        </div>
        <div class="form-group">
          <label for="ffnpd">Fin Pedido</label>
          <input type="date" class="form-control" id="ffnpd" name="ffnpd" placeholder="Fecha Fin pedido" required="">
        </div>
        <div class="form-group">
          <label for="ncantop">opciones</label>
          <input type="number" class="form-control" id="ncantop" name="ncantop" placeholder="Cantidad de opciones" min="1" max="5" maxlength="1" minlength="1" required="" value="">
        </div>
        <button type="button" class="btn btn-primary"  onclick="add_mhnu()">Crear</button>
      </form>
    </div>
    <hr>
    <div class="col-md-12">
      <div class="panel panel-default">

        <div class="panel-body">
          <table class="table table-bordered" cellspacing="0" width="100%" id="tablecm"> 
            <thead><tr><th>#</th><th>Menu</th><th>Fin Pedido</th><th>Nro Opciones</th><th>estado</th><th>Accion</th></tr></thead>
            <tbody>

            </tbody>
          </table>

        </div>
      </div>      
    </div>
  </div>
  <hr>
  <div class="col-md-12" id="menuopver">     
   <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <div class="btn-group pull-left" id="msg">

      </div>
      <h4 class="panel-title pull-right" style="padding-top: 7.5px;">   
        <button type="button" class="btn btn-info btn-xs" id="btnatras"><span class="glyphicon glyphicon-arrow-left"></span><strong>&nbsp;Atras</button> <button type="button" class="btn btn-primary btn-xs" onclick="add_pltos_mnu()">
          Agregar&nbsp;<span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
        </button>
      </h4>      
    </div>
    <div class="panel-body">
      <table class="table table-bordered" cellspacing="0" width="100%" id="table"> 
        <thead></thead>
        <tbody>

        </tbody>
      </table>

    </div>
  </div>      
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
   <form role="form" name="personal" method="POST" action="#" id="form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar platos al menu</h4>
        <input type="hidden" name="codhmnus" id="codhmnus">
        <input type="hidden" name="codptom" id="codptom">
      </div>
      <div class="modal-body">
       <div class="form-group">
        <label for="fpltoxpr" >Fecha</label>
        <input type="date" class="form-control" id="fpltoxpr" name="fpltoxpr" placeholder="Fecha de preparacion de menu" required="">
      </div>
      <div class="form-group">
        <label for="tippto">Tipo</label>          
        <select class="form-control" name="tippto" id="tippto" required="" onchange="get_platos_menu(this.value)">
        </select>
      </div>
      <div class="form-group">
        <label for="txtpto">Plato</label>
        <P id="opcaeligir"></P>
        <select class="form-control" name="selecpltos[][codplto]" id="selecpltos" placeholder="Platos" required="" multiple="multiple"> 
        </select>      
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" onclick="save_pltos_mnu()">Save</button>
    </div>  
  </form>
</div>
</div>
<script type="text/javascript">

    var save_method; //for save method string
    var table;
    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
    var valoropt=0;
    $(document).ready(function () {
     $('#verimag').hide();
     $('#crearhmenu').show();
     $('#menuopver').hide();
//document.write(diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear());
var f=new Date();
var textodia='Menu '+ f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
$('[name="txtnomm"]').val(textodia);

get_tipoalimentos();
table = $('#tablecm').DataTable({
  "processing": true, 
  "serverSide": true,
  "bFilter": false,             
  "ajax": {
    "url": "comedor_control/ajax_list_pto_mnuh",
    "type": "POST"
  },           
  "columnDefs": [
  {
    "targets": [-1], 
    "orderable": false, 
  },
  ],
});

$("#btnatras").click(function () {
  $('#crearhmenu').show();
  $('#menuopver').hide();
  reload_table();
});

});

    function multisel(){
      $('#selecpltos').multiselect({
        nonSelectedText:'Select selecpltos',
        columns: 1,
        maxHeight : 400,
        buttonWidth : '100%',
        deselectAll: false
      });
    }
    function add_pltos_mnu()
    {
      $('#selecpltos').val([]);
      save_method = 'add';
      $('#form')[0].reset(); 
      $('#modal_form').modal('show'); 
      $('.modal-title').text('Agregar opciones de Platos al Menu'); 
    }
 //codpto,tippto,txtpto,txtdscpto,imagpto
//codplto,tipopto,nomplto,imgplto,descplto,usucrplto,fcrplto,usumdplto,fmdplto,fdelplto,estrgplto


function reload_table()
{
        table.ajax.reload(null, false); //reload datatable ajax
      }
      function add_mhnu(){
//txtnomm,ncantop,fdsd,fhst,ffnpd

var fdsd=$('#fdsd').val();
var fhst=$('#fhst').val();
var txtnomm='Menu desde '+fdsd+' hasta '+fhst;
var ffnpd=$('#ffnpd').val();
var ncantop=(($('#ncantop').val()=='')||($('#ncantop').val().length>1))?'1':$('#ncantop').val();
var table='';
var url = "comedor_control/ajax_add_mhnu";
$.ajax({
  url: url,
  type: "POST",
  data: {
    txtnomm:txtnomm,
    fdsd:fdsd,
    fhst:fhst,
    ffnpd:ffnpd,
    ncantop:ncantop
  },
  dataType: "JSON",
  success: function (data)
  {              
    var ex = data.existe;
    switch (ex) {
      case 1:     
      $('#crearhmenu').hide();         
      $('#menuopver').show();
      var datos=data.dato;
      var nroop=datos.cntpltmnu;
      valoropt=nroop;
      var html='';
      $('[name="codhmnus"]').val(datos.codmnus);
      $('#opcaeligir').text('Debe Seleccion '+nroop+' Platos por cada fecha');
      var msg='<strong>'+datos.codmnus+' '+datos.nommnus+' Fin de pedio: '+datos.ffnpdmnu+' Hay: '+datos.cntpltmnu+' Opciones, Proceda a agregar los platos!!!</strong>';
      $('#msg').html(msg);
      var th='';      
      for(var i=0; i<nroop;i++){
        var cnt=i+1;
        th+='<th>Opcion'+cnt+'</th>';
      }
      var thr='<tr><th>Fechas</th>'+th+'<th>Acciones</th></tr>';

      $('#table thead').html(thr);
      break;
      case 0:
      alert('Datos Registrados '+data.existe+' '+data.dato);
      break;
    }

  },
  error: function (jqXHR, textStatus, errorThrown)
  {
    alert('Error con los datos Ingresados ... :'+textStatus);
  }
});            

}
function edit_mnuh(id)
{
  save_method = 'update';
  $('#form')[0].reset();         
  $.ajax({
    url: "comedor_control/ajax_edit_mnuh/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data)
    {
      var ex = data.existe;
      switch (ex) {
        case 1:     
        $('#crearhmenu').hide();         
        $('#menuopver').show();
        var dat=data.dato1;

        var nroop=dat.cntpltmnu;

        valoropt=nroop;
        $('[name="codhmnus"]').val(dat.codmnus);
        $('#opcaeligir').text('Debe Seleccion '+nroop+' Platos por cada fecha');
        var msg='<strong>'+dat.codmnus+' '+dat.nommnus+' Fin de pedio: '+dat.ffnpdmnu+' Hay: '+dat.cntpltmnu+' Opciones, Proceda a agregar los platos!!!</strong>';
        $('#msg').html(msg);
        var th='';      
        for(var i=0; i<nroop;i++){
          var cnt=i+1;
          th+='<th>Opcion'+cnt+'</th>';
        }
        var thr='<tr><th>Fechas</th>'+th+'<th>Acciones</th></tr>';
        $('#table thead').html(thr); 
        $('#table tbody').html(data.dato2);
        break;
        case 0:
        alert('Datos Registrados '+data.existe+' '+data.dato);
        break;
      }
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Error get data from ajax');
    }
  });
}
function delete_mnuh(id) 
{
  if (confirm('Esta Seguro de Eliminar Esta Persona?'))
  {
    $.ajax({
      url: "comedor_control/ajax_delete_mnuh/" + id,
      type: "POST",

      dataType: "JSON",
      success: function (data)            
      {  
                    //if success reload ajax table
                    alert('Personal Eliminado :'+data.status);
                    reload_table();
                  },
                  error: function (jqXHR, textStatus, errorThrown)
                  {
                    alert('Error en la eliminacion :'+textStatus);
                  }
                });
  }
}
function save_pltos_mnu()
{
  var url;
  const data = new FormData($('#form')[0]);
        //$('#form').serialize()
        if (save_method == 'add')
        {
          url = "comedor_control/ajax_add_pltos_mnu";
        }
        else
        {
          url = "comedor_control/ajax_update_pltos_mnu";
        }
        $.ajax({
          url: url,
          type: "POST",
          data:$('#form').serialize(),
          dataType: "JSON",
          success: function (data)
          {              
            $('#modal_form').modal('hide');
            var ex = data.existe;            
            switch (ex) {
              case 1:              
              var html=data.dato;                            
              $('#table tbody').html(html);
              break;
              case 0:
              alert('Datos Registrados '+data.existe+' '+data.dato);
              break;
            }
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error con los datos Ingresados ... :'+textStatus);
          }
        });
      }

      function delete_pltos_mnu(id,fecha)
      {
        if (confirm('Esta Seguro de Eliminar las opciones de menus?'))
        {
          var id = parseInt(id, 10);
          var fecha= String(fecha);
          $.ajax({
            url: "comedor_control/delete_pltos_mnu", 
            type: "POST",
            data: {
              id:id,
              fecha:fecha,
            },
            dataType: "JSON",
            success: function (data)            
            {  
              var ex = data.existe;            
              switch (ex) {
                case 1:  
                $('#table tbody').html('');            
                var html=data.dato;                            
                $('#table tbody').html(html);
                break;
                case 0:
                alert('Datos Registrados '+data.existe+' '+data.dato);
                break;
              }  
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              alert('Error en la eliminacion :'+jqXHR+' '+ textStatus+' '+ errorThrown);
            }
          });
        }
      }
      function get_tipoalimentos() {    
        var  str = '';
        $.ajax({
          type: 'POST',
          url: 'comedor_control/get_tipoalimentos',
          dataType: 'json',
          success: function(json) {
            lista = json.lista           
            if (lista != 0) {
              cad = lista.split("&&&");
              num = cad.length;
              str += '<option value="">--Seleccione--</option>';
              for (e = 0; e < num; e++) {
                dat = cad[e].split("#$#");
                codtali = dat[0];
                nomali = dat[1];
                str += '<option value="' + codtali + '">' + nomali + '</option>';
              }
            } else {
              str += '<option value="">No hay resultados</option>';
            }
            $('#tippto').html(str);
          }
        });
      }

      function get_platos_menu(idtpto) { 

        var codhmnus=$('#codhmnus').val();

        var  str = '';
        $.ajax({
          type: 'POST',
          url: 'comedor_control/get_platos_menu',
          data: {
            tipopto:idtpto,
            codmenu:codhmnus,
          },
          dataType: 'json',
          success: function(json) {
            lista = json.lista           
            if (lista != 0) {

              cad = lista.split("&&&");
              num = cad.length;             
              for (e = 0; e < num; e++) {
                dat = cad[e].split("#$#");
                codplto = dat[0];
                nomplto = dat[1];
                str += '<option value="' + codplto + '">' + nomplto + '</option>';
              }
            } else {
              str += '<option value="">No hay resultados</option>';
            } 
            $("#selecpltos").html(str);
            $('#selecpltos').multiselect();

            //multisel();          
            //$('#selecpltos').triggetmultiselect('refresh');
          }
        });
      }
    </script>
  </section>