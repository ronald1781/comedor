<section class="container">
  <ol class="breadcrumb">
    <li><a href="#">Home</a></li>
    <!--<li><a href="#">Library</a></li>-->
    <li class="active">Principal</li>
  </ol>
  <div class="row">
    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-body">
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
              <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
              <li data-target="#carousel-example-generic" data-slide-to="1"></li>
              <li data-target="#carousel-example-generic" data-slide-to="2"></li>
              <li data-target="#carousel-example-generic" data-slide-to="3"></li>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
              <div class="item active">
                <img src="assest\imagenplatos\alimentos-menos.jpg" alt="plato1" class="img-thumbnail" width="800" height="450" >
                <div class="carousel-caption">

                </div>
              </div>
              <div class="item">
                <img src="assest\imagenplatos\fmi_portada.jpg" alt="plato1" class="img-thumbnail" width="800" height="450">
                <div class="carousel-caption">

                </div>
              </div>
              <div class="item">
                <img src="assest\imagenplatos\alimentos_ricos.jpg" alt="plato1" class="img-thumbnail" width="800" height="450">
                <div class="carousel-caption">
                </div>
              </div>
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
      </div>      
    </div>
    <div class="col-md-4">
     <?PHP 
//echo md5('abc1234');
     $valid = $this->session->userdata('validated');
     $perfil = $this->session->userdata('perfil');
     if (($valid == true)&&($perfil==0)) { ?>
      <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;Crear Usuario</div>
        <div class="panel-body">
          <form role="form" name="login" action="valida/login" method="POST" id="forlogin">
           <div class="form-group">
            <label for="exampleInputPassword1">Nombre</label>
            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Jose Perez">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="jose.perez@mym.com.pe">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-success btn-lg btn-block">Grabar</button>
          <br>          
        </form>
      </div>
    </div>
    <?PHP 
  }elseif(($valid == true)&&(($perfil==1)||($perfil==2))) { ?>
      <h2>Bienveidos</h2>

    <?PHP }else{
      ?>
      <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;Ingresar</div>
        <div class="panel-body">

          <form role="form" name="login" action="<?php echo base_url('login_control/process')?>" method="POST" id="forlogin">
            <div class="form-group">
              <label for="emailuser">Email</label>
              <input type="email" class="form-control" id="emailuser" placeholder="jose.perez@mym.com.pe" name="emailuser">
            </div>
            <div class="form-group">
              <label for="passuser">Password</label>
              <input type="password" class="form-control" id="passuser" placeholder="Password" name="passuser">
            </div>
            <button type="submit" class="btn btn-success btn-lg btn-block">Ingresar</button>
            <br>
           
           <?PHP echo $msg; print_r($perfil);?>
          </form>
        </div>
      </div>
      <br>
      <div class="panel panel-default">
        <div class="panel-body">
           <div class="alert alert-info" role="alert">El <strong>login</strong> es solo para Chef y administrador, el usuario puede acceder con el boton de pedir y consumir menu!!.</div>
         <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('pedir') ?>" role="button">Pedir Menu</a>
         <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('comsumir') ?>" role="button">Consumir Menu</a>
       </div>
     </div>     
     <?PHP 
   }
   ?>
 </div>
</div>
</section>