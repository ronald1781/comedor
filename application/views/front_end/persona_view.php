<section class="container">	
	<ol class="breadcrumb">
		<li><a href="#">Comedor</a></li>
		<!--<li><a href="#">Library</a></li>
dniper,nomper,txtapepper,txtapemper,emailper
-->
<li class="active">Registrar Persona</li>
</ol>
<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-body">
        <form class="form-horizontal" action="grabarpersona" method="post" >
          <?php    
          echo '<div>' . $this->session->flashdata("mensajeper") . '</div>';          
          ?>
          <div class="form-group">
            <label for="dniper" class="col-sm-2 control-label">DNI</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="dniper" name="dniper" placeholder="12345678" maxlength="8" minlength="7" required="" autofocus="">
            </div>
          </div>
          <div class="form-group">
            <label for="txtnomper" class="col-sm-2 control-label">Nombres</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="txtnomper" name="txtnomper" placeholder="Juan carlos" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="txtapepper" class="col-sm-2 control-label">Apellido paterno</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="txtapepper" name="txtapepper" placeholder="Perez" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="txtapemper" class="col-sm-2 control-label">Apellido materno</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="txtapemper" name="txtapemper" placeholder="Lopez" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="emailper" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="emailper" name="emailper" placeholder="jperez@dominio.com">
            </div>
          </div>
          <div class="form-group">
            <label for="emailper" class="col-sm-2 control-label">Sucursal</label>
            <div class="col-sm-10">
              <select class="form-control" name="sucuper" id="sucuper" required="">
                <option value="">--Seleccion--</option>
                <?php foreach($sucursal as $sucu){?>
                  <option value="<?php echo $sucu->codsuc?>"><?php echo $sucu->nomsuc?></option>
                <?php }?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;Guardar</button>
              <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Reset</button>
              <a href="principal" class="btn btn-info" role="button"><span class="glyphicon glyphicon-arrow-left"></span><strong>&nbsp;Atras</strong></a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-4">
  </div>
</div>
</section>