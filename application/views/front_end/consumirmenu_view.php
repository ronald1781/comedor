<section class="container">	
	<ol class="breadcrumb">
		<li><a href="#">Comedor</a></li>		
		<li class="active">Consumir Menu</li>
	</ol>
	
	<form class="form-inline">
		<?php
		echo '<div >' . $this->session->flashdata("mensajeper") . '</div>';        
		?>		
		<div class="form-group">
			<label for="txtdni">DNI</label>
			<input type="text" class="form-control" id="txtdni" name="txtdni" placeholder="52312456" maxlength="8" minlength="7" autofocus="">
		</div>
		<a href="principal" class="btn btn-info" role="button"><span class="glyphicon glyphicon-arrow-left"></span><strong>&nbsp;Atras</strong></a>
		<div class="form-group gifCarga"><img id="loading_spinnerc" src="assest/imagen/loading8.gif" style="display: none;"></div>
		<div class="form-group">
			<h4 id="datosper"></h4>
			
		</div>
		<br>
		<p id="perconreg"></p>
	</form>
	<div id="muestramenu" class="row" >
		<div class="alert alert-danger" role="alert" id="msgsindato"></div>
		<div class="col-md-12">
			<form id="confirmarconsumenu">				
				<input type="hidden" name="codhmnus" id="codhmnus">
				<input type="hidden" name="codper" id="codper">
				<div class="jumbotron">
					<div class="media" id="jumbo">
					</div>
					<p><button type="button" class="btn btn-success btn-lg" id="confirmarcsm">Confirmar Consumo</button><button type="button" class="btn btn-primary btn-lg" id="verpedosxper">Ver mis pedidos</button></p>
				</div>
			</form>
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" id="msg"></div>
					<div class="panel-body">
						<table class="table table-bordered" id="menuxpedir">
							<thead>
							</thead>
							<tbody>
							</tbody>
						</table>
						<div class="form-group">
							<label for="txtdscpto" >Comentario o Sugerencia</label>
							<input type="text" class="form-control"  id="txtcmtsgr" name="txtcmtsgr" placeholder="Servir con gorra y guante">
						</div>					
					</div>
					<div class="panel-footer"></div>
				</div>
			</div>
		</div>
	</div>
	<div id="muestramenupedidoxper" class="row" >
		
		
		
	</div>
	<script language="JavaScript" >
		var dataproc='P';
		$(function () {
			$('#savepedmenu').hide();
				$('#muestramenu').hide();//muestramenu
				$('#perconreg').hide();	
				$('#msgsindato').hide();				
				$('#muestramenupedidoxper').hide();
				$('#jumbo').hide();
				$('#confirmarcsm').hide();
       //********BUSCAR Personal********// 
		//buscarper,txtdni,datosper,codper
		//'codper','dniper','nomper','apepper','apmper','emailper','estrgper'
		$("#txtdni").autocomplete({
			source: function (request, response) {
				$.ajax({
					url: "personal_control/buscardniper",
					dataType: "json",
					data: {datoper: request.term},
					cache: false,
					beforeSend: function () {
						$('#loading_spinnerc').show();              
						$('#resulta').html('');
					},
					success: function (data) {
						$('#codper').val('');
						$('#datosper').val('');
						$('#loading_spinnerc').hide();
						var ex = data.existe;
						switch (ex) {
							case 1:
							response($.map(data.datos, function (item) {
								return {
									label: item.dniper+' '+item.nomper + " " + item.apepper+ " " + item.apmper,
									value: item.dniper,
									desclie:item.dniper+' '+item.nomper + " " + item.apepper+ " " + item.apmper,                  
									codclie: item.codper,
									exist: item.msg,
								};
							}))
							break;
							case 0:
							response($.map(data.datos, function (item) {
								return {
									label: item.men,
									exist: item.msg,
								}
								;
							}))
							break;
						}
					}
				});
			},
			delay: 200,
			minLength: 5,
			autoFocus: true,
			select: function (event, ui) {
				var exist=ui.item.exist;
				if(exist==0){
					var valdni=$('#txtdni').val();
					var html='<span class="label label-warning"><a href="persona" class="btn btn-info btn-xs" role="button"><strong>Registrarse Aqui</strong></a> '+valdni+' :Personal con el dato no esta Registrado </span>';
					$('#perconreg').html(html);		
					$('#perconreg').show();
					$('#txtdni').val('');
					$('#muestramenu').hide();
					document.getElementById("txtdni").disabled = true;
					
				}else{
					$('#txtdni').val('');
					$('#codper').val(ui.item.codclie);
					$('#datosper').html('<div class="alert alert-default" role="alert">Datos:  <strong>'+ui.item.desclie+'</strong></div>');
					$('#muestramenu').show();
					$('#savepedmenu').show();
					document.getElementById("txtdni").disabled = true;
					get_menuporconsumir(ui.item.codclie);
				}                    
				return false;
			},
			open: function () {

				$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status+' '+ thrownError);
			},
			close: function () {
				$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
			}
		});
		$("#savepedmenu").click(function () {
			save_pdo_mnu();
		});  
		$("#confirmarcsm").click(function () {
			save_cmo_mnu();
		});
		$("#verpedosxper").click(function () {
			var codper=$('#codper').val();
			get_menupedidos(codper);
		});

		$('#txtdni').on( 'keyup', function (e) {
			if (e.keyCode == 13) {
           //carga_data_jsonwsclie();
       }
   } );
		$("#rateYo").rateYo({
			rating: 3.6,
			spacing   : "5px",
			multiColor: {
      "startColor": "#FF0000", //RED
      "endColor"  : "#00FF00"  //GREEN
  }
});

	});
		function get_menuporconsumir(idper)
		{
			var codper=idper;
			$.ajax({
				url: "comedor_control/ajax_visuali_mnuxcsmo",				
				type: "POST",
				data:{codper:codper},
				dataType: "JSON",
				success: function (data)
				{
					var ex = data.existe;
					switch (ex) {
						case 1:  
						
						$('#menuopver').show();
						$('#muestramenu').show();
						$('.datos').show();						
						var dat=data.dato1;
						var nroop=dat.cntpltmnu;							
						var codpn=data.dato3;
						var suge=data.dato4;
						var consum=data.dato6;
						var descop=(codpn==0)?'No tiene Menu Elegido':'Sus platos, estan pintados en verde!';
						valoropt=nroop;
						$('[name="codhmnus"]').val(dat.codmnus);
						var msg='<strong>'+dat.codmnus+' '+dat.nommnus+' Fin de pedio: '+dat.ffnpdmnu+' Hay: '+dat.cntpltmnu+' Opciones, '+descop+' !!!</strong>';
						$('#msg').html(msg);
						var th='';      
						for(var i=0; i<nroop;i++){
							var cnt=i+1;
							th+='<th>Opcion'+cnt+'</th>';
						}
						var thr='<tr><th>Fechas</th>'+th+'</tr>';
						$('#menuxpedir thead').html(thr); 
						$('#menuxpedir tbody').html(data.dato2);
						
						if(codpn==0){
							$('#txtcmtsgr').val('');					
							document.getElementById("txtcmtsgr").disabled = true;
							$('#btnsaveped').show();
							$('#msgsindato').text(data);
							$('#jumbo').show();
							$('#jumbo').html(data.dato5);
							if((consum=='C')||(consum==0)){
								$('#confirmarcsm').hide();
							}else{
								$('#confirmarcsm').show();
							}
						}else{	
							$('#txtcmtsgr').val('');
							$('#txtcmtsgr').val(suge);					
							document.getElementById("txtcmtsgr").disabled = true;
							$('#btnsaveped').hide();
							$('#jumbo').show();
							$('#jumbo').html(data.dato5);
							if((consum=='C')||(consum==0)){
								$('#confirmarcsm').hide();
							}else{
								$('#confirmarcsm').show();
							}
						}
						break;
						case 0:
											
						$('#muestramenu').show();
						var datos=data.dato;
						var data='No hay Menu Pedido '+datos.men;
						$('#msgsindato').show();						
						$('#msgsindato').text(data); 
						break;
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});
		}
		function save_cmo_mnu()
		{
			if (confirm('Esta confirmando su consumo?'))
			{
				$.ajax({
					url: "comedor_control/ajax_add_conmnudia",
					type: "POST",
					data: $('#confirmarconsumenu').serialize(),
					dataType: "JSON",
					success: function (data)            
					{  
						var ex = data.existe;
						switch (ex) {
							case 1:
							get_menuporconsumir(data.dato);
							break;
							case 0:
							alert('Datos Registrados '+data.existe+' '+data.dato);
							break;
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error en la eliminacion :'+textStatus);
					}
				});
			}
		}
		function get_menupedidos(idper)//get_menupedidos
		{
			$.ajax({
				url: "comedor_control/get_lista_menupedidosxper/"+idper,
				type: "GET",
				dataType: "JSON",
				success: function (data)
				{
					var ex = data.existe;
					switch (ex) {
						case 1:  
						$('#muestramenu').hide();
						$('#muestramenupedidoxper').show();
						$('#muestramenupedidoxper').html(data.dato1); 
						break;
						case 0:
						alert('Datos Registrados '+data.existe+' '+data.dato);
						break;
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error en la eliminacion :'+textStatus);
				}
			});
		}

	</script>
</section>