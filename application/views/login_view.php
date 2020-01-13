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
  <script language="JavaScript" >
    $(document).ready(function () {
     $('#loading_spinner').hide();
     $('#ingresar').hide();
     $("#seleccia").attr('disabled', true);

     $("#password").on('keydown', function(e) {
      if (e.which == 13) {
       $("#validar").trigger('click');
       return false;
     }
   });
     $("#username").on('onkeyup', function(e) {
       e.preventDefault();
       $('#seleccia').get(0).selectedIndex = 1;
       $('#ingresar').hide();
       $('#validar').show();   
       $("#seleccia").attr('disabled', true);
     });

     

     $("#validar").click(function(e){
       e.preventDefault(); 
       var username=$('#username').val();
       var password=$('#password').val();
       var value = $("#selebanco").val();
  var valuefe = $("#dtfecha").val();

       var mensajealert='';
       if(username.trim() == '' ){
        alert('Ingrese un Usuario.');
        $('#username').focus();
        return false;}else if(password.trim() == '' ){
          alert('Ingrese un Password.');
          $('#password').focus();
          return false;
        }else{
           $('.msg').html('');
          $.ajax({
            url: "login_control/get_cia",
            type: "post",
            data:{username:username,password:password},
            dataType: "JSON",
            beforeSend: function () {
              $('#validar').text('Verificando Usuario.......');          
            },
            success: function (json)    
            {
              $('#validar').text('validar');

              try {
               lista = json.lista;
               if (lista != 0) {
                var str='';
                cad = lista.split("&&&");
                var num = cad.length;

                for (e = 0; e < num; e++) {
                  dat = cad[e].split("#$#");
                  codbanco0 = dat[0];
                  nombanco1 = dat[1];
                  nombanco2 = dat[2]; 

                  str += '<option value="' + codbanco0 + '">'+ codbanco0 +' '+ nombanco2 + '</option>';
                }
                str += (num>1)?'<option selected="" value=""> --Seleciona--</option>':'';
                if(num>0){
                  $("#seleccia").attr("disabled", false);
                }else{
                  $("#seleccia").attr("disabled", true);
                };

                $('#ingresar').show();
                $('#validar').hide();
                $('#seleccia').html(str);
                $('.msg').html('');

              } else {

               var alrt = '<div class="alert alert-danger">El usuario <strong>'+username+'</strong> No tienen Acceso al programa, puede solicitar atencion a TI.</div>';
               $('.msg').html(alrt);
 mensajealert='Intento de acceso no tiene acceso al programa';
          alerta_intento_acceso(username,mensajealert);
             }

           }  catch (e) {
            //alert('Exception while request..'+e);
            var alrt = '<div class="alert alert-danger">Error <strong>'+e+'</strong>.</div>';
               $('.msg').html(alrt);

          }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
          $('#validar').text('validar');
          //alert(xhr+' '+ ajaxOptions +' '+ thrownError);
          var alrt = '<div class="alert alert-danger">Para el usuario <strong>'+username+'</strong>se genero Error verifique los datos Ingresados, puede solicitar atencion a TI.  </div>';
          $('.msg').html(alrt);
          mensajealert='Intento de acceso y salio error';
          alerta_intento_acceso(username,mensajealert);
        }
      });

        }
      });

   });

    function alerta_intento_acceso(usuario,mensaje){

  var url="login_control/send_mail_intento_acceso"; 
  $.ajax({
    url: url,
    type: "post",
    data:{usuario:usuario,mensaje:mensaje},
    dataType: "JSON",
       success: function (msj)    
    {
      
      console.log(msj);
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
     var errores=xhr+' '+ajaxOptions+''+ thrownError; 
     console.log(errores);
   }
 });

} 

    function validateForm() {
    var username=$('#username').val();
       var password=$('#password').val();
       var seleccia=$('#seleccia').val();
       

       if(username.trim() == '' ){
        alert('Ingrese un Usuario.');
        $('#username').focus();
        return false;
      }else if(password.trim() == ''){
alert('Ingrese un password.');
        $('#password').focus();
        return false;
        }else if(seleccia.trim() == ''){
alert('No hay empresa.');
        $('#seleccia').focus();
        return false;
        }

}
 </script>          
</head>
<body>
  <section clase="container">   

    <!--login modal-->
    <div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->         
            <h2 class="text-center">Debe identificarse</h2>
          </div>
          <div class="modal-body">        

            <?php echo validation_errors('<p class="error">'); ?> 
            <form class="form col-md-12 center-block" role="form" name="login" action="valida/login" method="POST" id="forlogin" onsubmit="return validateForm()" >
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" class="form-control input-lg" name="username" value="<?php echo set_value('username'); ?>" minlength="4"  maxlength="10" required id="username" placeholder="Usuario" id="username" autofocus >
              </div>            
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" class="form-control input-lg" placeholder="Password" name="password" minlength="6"  maxlength="10" required id="password" value="" >
              </div>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                <select class="form-control input-lg" name="seleccia" id="seleccia">
                  <option >--Empresa--</option>
                </select>
              </div>
              <br>
              <div class="form-group">
                <button class="btn btn-info btn-lg btn-block" id="validar">Validar</button>
                <button class="btn btn-primary btn-lg btn-block" id="ingresar" type="submit" >Ingresar</button>
                <!--<span class="pull-right"><a href="#">Register</a></span><span><a href="#">Need help?</a></span>-->
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div class="col-md-12 msg">
              <!--<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <div class="loader" id="precarga"></div>
              -->
            </div>  
          </div>
        </div>
      </div>
    </div>

  </section>

<style>
  .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('assest/imagen/loading_onepage.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}
</style>
</body>
</html>

