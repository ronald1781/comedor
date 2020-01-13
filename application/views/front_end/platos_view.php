<section class="container">
	
	<ol class="breadcrumb">
		<li><a href="#">Cocina</a></li>
		<!--<li><a href="#">Library</a></li>-->
		<li class="active">Platos</li>
	</ol>
	
  <div class="row">
    <div class="col-md-12">
      <?PHP 
//echo md5('abc1234');
      ?>
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <div class="btn-group pull-left">
           Platos  
         </div>
         <h4 class="panel-title pull-right" style="padding-top: 7.5px;"> <button type="button" class="btn btn-primary btn-xs" onclick="add_pto()">
          Agregar&nbsp;<span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
        </button>
      </h4>      
    </div>
    <div class="panel-body">
      <table class="table table-bordered" cellspacing="0" width="100%" id="table"> 
        <thead><tr><th>#</th><th>Tipo</th><th>Plato</th><th>Descripcion</th><th>Imagen</th><th>estado</th><th>Accion</th></tr></thead>
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
        <h4 class="modal-title" id="myModalLabel">Crear Platos</h4>
        <input type="hidden" name="codpto" id="codpto">
      </div>
      <div class="modal-body">
         <div class="form-group">
          <label for="tippto">Tipo</label>          
          <select class="form-control" name="tippto" id="tippto" required="">
          </select>
        </div>
        <div class="form-group">
          <label for="txtpto">Plato</label>
          <input type="text" class="form-control" id="txtpto" name="txtpto" placeholder="ceviche" required="" maxlength="180">        
        </div>
        <div class="form-group">
          <label for="txtdscpto" >Descripcion</label>
          <textarea class="form-control" rows="2" id="txtdscpto" name="txtdscpto" placeholder="ceviche, sebiche o seviche es un plato consistente en carne marina"></textarea>
        </div>
     
        <div class="form-group">
          <label for="imagpto">Imagen</label>
          <div class="thumbnail" id="verimag">
      
      
    </div>
          <input type="file" class="form-control" id="imagpto" name="imagpto" placeholder="ceviche.jpg">
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
     $('#verimag').hide();

      get_tipoalimentos();
      table = $('#table').DataTable({
        "processing": true, 
        "serverSide": true,             
        "ajax": {
          "url": "comedor_control/ajax_list_pto",
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

    function add_pto()
    {
      $('#verimag').hide();
      save_method = 'add';
      $('#form')[0].reset(); 
      $('#modal_form').modal('show'); 
      $('.modal-title').text('Agregar Plato'); 
    }
 //codpto,tippto,txtpto,txtdscpto,imagpto
//codplto,tipopto,nomplto,imgplto,descplto,usucrplto,fcrplto,usumdplto,fmdplto,fdelplto,estrgplto
function edit_pto(id)
{
  save_method = 'update';
  $('#form')[0].reset();         
  $.ajax({
    url: "comedor_control/ajax_edit_pto/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data)
    {
      if(data==null){
        alert('Error, Verificar  '+data);
      }else{       
        $('[name="codpto"]').val(data.codplto);
        $('[name="tippto"]').val(data.tipopto);
        $('[name="txtpto"]').val(data.nomplto);
        $('[name="txtdscpto"]').val(data.descplto);
        $('#verimag').show();
        var img='<img src="assest/imagenplatos/'+data.imgplto+'" alt="Sin Imagen de Plato" class="img-responsive img-thumbnail" id="rutaimag" width="120" height="30">';
        $('#verimag').html(img);            
        $('#modal_form').modal('show'); 
        $('.modal-title').text('Editar Plato'); 
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
        const data = new FormData($('#form')[0]);
        //$('#form').serialize()
        if (save_method == 'add')
        {
          url = "comedor_control/ajax_add_pto";
        }
        else
        {
          url = "comedor_control/ajax_update_pto";
        }
        $.ajax({
          url: url,
          type: "POST",
          data: data,
          dataType: "JSON",
           cache: false,
        contentType: false,
        processData: false,
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

      function delete_pto(id)
      {
        if (confirm('Esta Seguro de Eliminar Este Plato?'))
        {
            $.ajax({
              url: "comedor_control/ajax_delete_pto/" + id,
              type: "POST",
              dataType: "JSON",
              success: function (data)            
              {  
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    alert('Plato Eliminado :'+data.status);
                    reload_table();
                  },
                  error: function (jqXHR, textStatus, errorThrown)
                  {
                    alert('Error en la eliminacion :'+textStatus);
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

      </script>
    </section>