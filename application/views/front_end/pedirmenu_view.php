<section class="container">	
	<ol class="breadcrumb">
		<li><a href="#">Comedor</a></li>
		<!--<li><a href="#">Library</a></li>-->
		<li class="active">pedir Menu</li>
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
		<br>
		<p id="perconreg"></p>
	</form>
	<hr>
	<form id="pedirmenu">
		<input type="hidden" name="codper" id="codper">
		<input type="hidden" name="codhmnus" id="codhmnus">
		<div id="muestramenu" class="row" >		

			<div class="col-md-12"><h4 id="datosper"></h4><div class="alert alert-danger" role="alert" id="msgsindato"></div></div>
			<div class="col-md-12 sin datos" >
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
					<div class="panel-footer"><p id="btnsaveped"><button type="button" class="btn btn-success btn-lg btn-block" id="savepedmenu"><span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;Grabar Pedido</button></p><p id="msgvenciofechapedido"></p></div>
				</div>
			</div>
		</div>
	</form>

	<script language="JavaScript" >
		var dataproc='P';
		$(function () {
			$('#savepedmenu').hide();
			$('#muestramenu').hide();
			$('#perconreg').hide(); 

			$('#msgsindato').hide();
			$('#btnsaveped').show();

			$('#msgvenciofechapedido').hide();
       //********BUSCAR CLIENTE********// 
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
					get_menuporpedir(ui.item.codclie);
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
			
			const accesorios = document.querySelectorAll('input[type=radio]:checked'); 
			if(accesorios.length <= 0){
				alert('Debe seleccionar las opciones');
				return;
			}else{
				save_pdo_mnu();
			}				
			
		});     

		$('#txtdni').on( 'keyup', function (e) {
			if (e.keyCode == 13) {
           //carga_data_jsonwsclie();
       }
   } );

	});
 function colortr(dia) 
        {        	
           var trd="success";
           $("#menuxpedir tbody #"+dia).addClass(trd);
        }

		function get_menuporpedir(idper)
		{
			$.ajax({
				url: "comedor_control/ajax_visuali_mnuxp/"+idper,
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
						var suge=data.dato4;
						var codpn=data.dato3;
						var descop=(codpn==0)?'Proceda a elegir uno por dia':'Ya elegio sus platos, estan pintados en verde!';
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
						var d = new Date();
						//var datestring = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);

						f= d.getFullYear()+ "-" +("0"+(d.getMonth()+1)).slice(-2) + "-"+ ("0" +d.getDate()).slice(-2);
						if(codpn==0){

							$('#txtcmtsgr').val(' ');					
							
							if(data.dato5>=f){								
								$('#btnsaveped').show();
								document.getElementById("txtcmtsgr").disabled = false;
							}else{
								var msgffp='<div class="alert alert-danger" role="alert">Esta fuera de fecha para realizar pedidos</div>';
								$('#msgvenciofechapedido').html(msgffp);
								$('#msgvenciofechapedido').show();
								$('#btnsaveped').hide();
								document.getElementById("txtcmtsgr").disabled = true;
							}
							
							$('#msgsindato').hide();


						}else{	
							$('#txtcmtsgr').val('');
							$('#txtcmtsgr').val(suge);					
							document.getElementById("txtcmtsgr").disabled = true;
							$('#btnsaveped').hide();
							$('#msgsindato').hide();
						}
						break;
						case 0:
						var datos=data.dato;
						var data='No hay Menu Registrado '+datos.men;
						$('#msgsindato').show();						
						$('#msgsindato').text(data);         
						$('.datos').hide();
						break;
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});
		}
		function save_pdo_mnu()
		{
			if (confirm('Esta Seguro de su eleccion de los Platos?'))
			{
				$.ajax({
					url: "comedor_control/ajax_add_pedidomnu",
					type: "POST",
					data: $('#pedirmenu').serialize(),
					dataType: "JSON",
					success: function (data)            
					{  
						var ex = data.existe;
						switch (ex) {
							case 1:
							get_menuporpedir(data.dato);
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


	</script>

</section>