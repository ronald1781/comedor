<section class="container">
  <ol class="breadcrumb">
    <li><a href="#">Administracion</a></li>
    <!--<li><a href="#">Library</a></li>-->
    <li class="active">Usuario</li>
  </ol>
  <div class="row">
    <div class="col-md-12">
      <?PHP 
//echo md5('abc1234');
      ?>
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <div class="btn-group pull-left">
            Usuarios  
</div>
<h4 class="panel-title pull-right" style="padding-top: 7.5px;"> <button type="button" class="btn btn-primary btn-xs" onclick="add_user()">
              Agregar&nbsp;<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            </button>
          </h4>      
        </div>
        <div class="panel-body">
<table class="table table-bordered" cellspacing="0" width="100%" id="table"> 
  <thead><tr><th>#</th><th>Usuario</th><th>Correo</th><th>Perfil</th><th>estado</th><th>Accion</th></tr></thead>
  <tbody>
    <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</tbody>
</table>

        </div>
      </div>      
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
     <form role="form" name="login" method="POST" action="#" id="form">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Crear Usuario</h4>
          <input type="hidden" name="coduser" id="coduser">
        </div>
        <div class="modal-body">      
         <div class="form-group">
          <label for="nomuser">Nombre</label>
          <input type="text" class="form-control" id="nomuser" name="nomuser" placeholder="Jose Perez" required="">
        </div>
        <div class="form-group">
          <label for="emailuser">Email</label>
          <input type="email" class="form-control" id="emailuser" name="emailuser" placeholder="jose.perez@mym.com.pe" required="">
        </div>
        <div class="form-group">
          <label for="passuser">Password</label>
          <input type="password" class="form-control" id="passuser" name="passuser" placeholder="Password">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Perfil</label>          
          <select class="form-control" name="prfusr" id="prfusr">
  <option value="1">Administrador</option>
  <option value="2">Chef</option>
</select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="save()">Save</button>
      </div>
    </div>
  </form>
</div>
</div>
<script type="text/javascript">
  //coduser,nomuser,emailuser,passuser,prfusr
    var save_method; //for save method string
    var table;
    $(document).ready(function () {
        table = $('#table').DataTable({
            "processing": true, 
            "serverSide": true,             
            "ajax": {
                "url": "login_control/ajax_list_user",
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

    function add_user()
    {
        save_method = 'add';
        $('#form')[0].reset(); 
        $('#modal_form').modal('show'); 
        $('.modal-title').text('Agregar Usuario'); 
    }

    function edit_user(id)
    {
        save_method = 'update';
        $('#form')[0].reset();         
        $.ajax({
            url: "login_control/ajax_edit_user/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
              if(data==null){
                alert('Error, Al admin del sistema no puede editar  '+data);
              }else{
                //coduser,nomuser,emailuser,passuser,prfusr
                //'codusu','emailusu','usuausu','prfusu','estrgusu'
                $('[name="coduser"]').val(data.codusu);
                $('[name="nomuser"]').val(data.usuausu);
                 $('[name="emailuser"]').val(data.emailusu);
                  $('[name="prfusr"]').val(data.prfusu);               
                $('#modal_form').modal('show'); 
                $('.modal-title').text('Editar Usuario'); 
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
            url = "login_control/ajax_add_user";
        }
        else
        {
            url = "login_control/ajax_update_user";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                //if success close modal and reload ajax table
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

    function delete_user(id)
    {
        if (confirm('Esta Seguro de Eliminar Este Usuario?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "login_control/ajax_delete_user/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)            
                {  
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    alert('Usuario Eliminado :'+data.status);
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error en la eliminacion :'+textStatus);
                }
            });

        }
    }

</script>
</section>