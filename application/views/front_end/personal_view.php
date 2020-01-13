<section class="container">
	
	<ol class="breadcrumb">
		<li><a href="#">Administracion</a></li>
		<!--<li><a href="#">Library</a></li>-->
		<li class="active">Registrar Persona</li>
	</ol>
	
  <div class="row">
    <div class="col-md-12">
      <?PHP 
//echo md5('abc1234');
      ?>
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <div class="btn-group pull-left">
           Personal  
         </div>
         <h4 class="panel-title pull-right" style="padding-top: 7.5px;"> <button type="button" class="btn btn-primary btn-xs" onclick="add_per()">
          Agregar&nbsp;<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
        </button>
      </h4>      
    </div>
    <div class="panel-body">
      <table class="table table-bordered" cellspacing="0" width="100%" id="table"> 
        <thead><tr><th>#</th><th>DNI</th><th>Nombres</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Email</th><th>Sucursal</th><th>estado</th><th>Accion</th></tr></thead>
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
        <h4 class="modal-title" id="myModalLabel">Crear Personal</h4>
        <input type="hidden" name="codper" id="codper">
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="dniper">DNI</label>
          <input type="text" class="form-control" id="dniper" name="dniper" placeholder="12345678" required="" maxlength="8" minlength="7">        
        </div>
        <div class="form-group">
          <label for="txtnomper" >Nombres</label>
          <input type="text" class="form-control" id="txtnomper" name="txtnomper" placeholder="Juan carlos" required="">
        </div>
        <div class="form-group">
          <label for="txtapepper">Apellido paterno</label>
          <input type="text" class="form-control" id="txtapepper" name="txtapepper" placeholder="Perez" required="">
        </div>
        <div class="form-group">
          <label for="txtapemper">Apellido materno</label>
          <input type="text" class="form-control" id="txtapemper" name="txtapemper" placeholder="Lopez" required="">  
        </div>
        <div class="form-group">
          <label for="emailper">Email</label>
          <input type="email" class="form-control" id="emailper" name="emailper" placeholder="jperez@dominio.com">
        </div>
        <div class="form-group">
          <label for="sucuper">Sucursal</label>          
          <select class="form-control" name="sucuper" id="sucuper" required="">
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="save()">Save</button>
      </div>  
    </form>
  </div>
</div>
<script type="text/javascript">

    var save_method; //for save method string
    var table;
    $(document).ready(function () {
      get_sucursal();
      table = $('#table').DataTable({
        "processing": true, 
        "serverSide": true,             
        "ajax": {
          "url": "personal_control/ajax_list_per",
          "type": "POST"
        },           
        "columnDefs": [
        {
          "targets": [-1], 
          "orderable": false, 
        },
        ],
      });
    });

    function add_per()
    {
      save_method = 'add';
      $('#form')[0].reset(); 
      $('#modal_form').modal('show'); 
      $('.modal-title').text('Agregar Personal'); 
    }
 //codper,dniper,txtnomper,txtapepper,txtapemper,emailper
//"codper","dniper","nomper","apepper","apmper","usucrper","emailper","fcrper","usumdper","fmdper","estrgper"
function edit_per(id)
{
  save_method = 'update';
  $('#form')[0].reset();         
  $.ajax({
    url: "personal_control/ajax_edit_per/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data)
    {
      if(data==null){
        alert('Error, Verificar  '+data);
      }else{       
        $('[name="codper"]').val(data.codper);
        $('[name="dniper"]').val(data.dniper);
        $('[name="txtnomper"]').val(data.nomper);
        $('[name="txtapepper"]').val(data.apepper);
        $('[name="txtapemper"]').val(data.apmper);
        $('[name="emailper"]').val(data.emailper);   
        $('[name="sucuper"]').val(data.sucper);            
        $('#modal_form').modal('show'); 
        $('.modal-title').text('Editar Personal'); 
      }
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Error get data from ajax');
    }
  });
}

function reload_table()
{
        table.ajax.reload(null, false); //reload datatable ajax
      }

      function save()
      {
        var url;
        if (save_method == 'add')
        {
          url = "personal_control/ajax_add_per";
        }
        else
        {
          url = "personal_control/ajax_update_per";
        }
        $.ajax({
          url: url,
          type: "POST",
          data: $('#form').serialize(),
          dataType: "JSON",
          success: function (data)
          {              
                $('#modal_form').modal('hide');
                alert('Datos Registrados '+data.status);
                reload_table();
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                alert('Error con los datos Ingresados ... :'+textStatus);
              }
            });
      }

      function delete_per(id)
      {
        if (confirm('Esta Seguro de Eliminar Esta Persona?'))
        {
            $.ajax({
              url: "personal_control/ajax_delete_per/" + id,
              type: "POST",
              dataType: "JSON",
              success: function (data)            
              {  
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
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
        function get_sucursal() {    
          var  str = '';
          $.ajax({
            type: 'POST',
            url: 'personal_control/get_sucursal',
            dataType: 'json',
            success: function(json) {
              lista = json.lista           
              if (lista != 0) {
                cad = lista.split("&&&");
                num = cad.length;
                str += '<option value="">--Seleccione--</option>';
                for (e = 0; e < num; e++) {
                  dat = cad[e].split("#$#");
                  codsuc = dat[0];
                  nomsuc = dat[1];
                  str += '<option value="' + codsuc + '">' + nomsuc + '</option>';
                }
              } else {
                str += '<option value="">No hay resultados</option>';
              }
              $('#sucuper').html(str);
            }
          });
        }

      </script>
    </section>