<section class="container">
  <script language="JavaScript" >
    var dataproc='P';
    $(function () {

       //********BUSCAR CLIENTE********//
       $("#codclie").autocomplete({
        source: function (request, response) {
          $.ajax({
            url: "facturacionsendws_control/buscar_cliente_db2as400",
            dataType: "json",
            data: {codclieas400: request.term},
            cache: false,
            beforeSend: function () {
              $('#loading_spinnerc').show();              
              $('#resulta').html('');
            },
            success: function (data) {
             $('#loading_spinnerc').hide();
             var ex = data.existe;
             switch (ex) {
              case 1:
              response($.map(data.datos, function (item) {
                return {
                  label: item.AKCODCLI+' '+item.NUMIDEN + " " + item.AKRAZSOC,
                  value: item.AKCODCLI,
                  desclie:item.AKCODCLI+' '+item.NUMIDEN + " " + item.AKRAZSOC,                  
                  codclie: item.AKCODCLI,
                }
                ;
              }))
              break;
              case 0:
              response($.map(data, function (item) {
                return {
                  label: data.men,
                }
                ;
              }))
              break;
            }
          }
        });
        },
        delay: 200,
        minLength: 3,
        autoFocus: true,
        select: function (event, ui) {
          $('#codclie').val(ui.item.codclie);
          var datocli=ui.item.desclie;
          var coloralert='';
          coloralert=(datocli==='')?'danger':'success';
          var html='<span class="label label-'+coloralert+'">'+datocli+'.</span>';
          $('#resultac').html(html);

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

       $('#tbdocufe').DataTable();
       $('#loading_baja').hide();

       $("#btnmostrarbajas").click(function () {

        carga_data_jsonwsbaja();

      });

       $("#btnmostraractivos").click(function () {

        carga_data_jsonws();

      });
       $("#btnmostraractivosxcliente").click(function () {

        carga_data_jsonwsclie();

      });
       $("#btnmostrarstatus").click(function () {
       // optener_status_ticket();
       //set_printfedemo();
       optener_status_webservice();

     });

       $("#btnpromanbajdoc").click(function () {

        get_anulacionmanual();

      });

       $('#codclie').on( 'keyup', function (e) {
         if (e.keyCode == 13) {
           carga_data_jsonwsclie();
         }
       } );

     });

//imprimir


function set_sendfe(cia,fecha,tipdoc,nropdc,serie,corr,cont){
var datas='';
  var title='sendfe';
  var fecha = parseInt(fecha, 10);
  var nropdc = parseInt(nropdc, 10);
  var cia= String(cia);
  var serie= String(serie);
  var corr= String(corr);
   var nomtipdoc=String(tipdoc);
   var docu = serie+' '+corr;
 switch(nomtipdoc){
  case '01':
  nomtipdoc='Fatura';
  break;
  case '03':
  nomtipdoc='Boleta';
  break;
  case '07':
  nomtipdoc='Nota Credito';
  break;
  case '08':
  nomtipdoc='Nota Debito';
  break;
};
  $.ajax({
    type:"POST",
    url:"facturacionsendws_control/plantillaJSONFE",
    data:{nropdc:nropdc,
      serie:serie,
      corr:corr,
      fecha:fecha,
      cia:cia,
      titles:title},
      dataType: "JSON",
      beforeSend: function () {
        $('#loading_fe'+cont).show();       
      },
      success:function(msj){
        $('#loading_fe'+cont).hide();
        //console.log(msj);
        if (msj.existe === 1) {
         ms=msj.dato;
        var codigo= ms.docufersl['codigo']
        var info= ms.docufersl['dato']
        //dato=ms.docufersl['dato'];
        //+' '+msg['docufersl']+' '+msg['http_status']+' '+msg['docujson']+' '+msg['http_statuscod']+' '+msg['docufeerror']+' '+msg['prinfe']+' '+msg['errorcomunbd']+' '+msg['d1'];  
        var mensaje=(codigo==="0")?'ACEPTADO':'RECHAZADO';
        var colortag=(codigo==="0")?'success':'danger';        
        datas+='<div class="alert alert-'+colortag+'"><strong> por '+mensaje+'!</strong> '+codigo+' '+info+'.</div>' ;      

      }else{
        datas='<li class="list-group-item">Sin Datos</li>';
      }
      $('#mdalevetosfe .modal-title').text('Respuesta de Envio de documento '+nomtipdoc+' '+nropdc+' '+docu);
      $('#mdalevetosfe .modal-body').html(datas);
    $('#mdalevetosfe .modal-footer .modal-title').text('');
      $('#mdalevetosfe').modal('show');
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
      $('#loading_fe'+cont).hide();
    $('#mdalevetosfe .modal-title').text('Error en envio de documento '+nomtipdoc+' '+nropdc+' '+docu);
     datas='<div class="alert alert-danger"><strong>'+xhr+'!</strong> '+ ajaxOptions+' '+thrownError+'.</div>' ;    

    $('#mdalevetosfe .modal-body').html(datas);
    $('#mdalevetosfe').modal('show');
    } 
  });    

}

function set_sendfebaj(cont,fecha,nropdc,serie,corr,tipdoc,stsdoct,codalm){

  var fecha = String(fecha);
  var nropdc = parseInt(nropdc, 10);
  var serie= String(serie);
  var corr= String(corr);
  var tipdoc= String(tipdoc);
  var stsdoct= String(stsdoct);
  var codalm= String(codalm);

  var url='facturacionsendbajaws_baja_control/get_anulacionmanualindividual';
//facturacionsendbajaws_baja_control/procesarbajafe
//get_anulacion_individual
$.ajax({
  type:"POST",
  url:url,
  data:{nropdc:nropdc,
    serie:serie,
    corr:corr,
    fecha:fecha,
    tipdoc:tipdoc,
    stsdoct:stsdoct,
    codalm:codalm},
    dataType: "JSON",
     beforeSend: function () {
    $('#loading_fe'+cont).show();       
  },
    success:function(data){
          //carga_data_jsonwsbaja();
          console.log(data);
          $('#loading_fe'+cont).hide();
          carga_data_jsonwsbaja();
         /*
         var conta=0;
         var i;
         var datas=''; 
            
         try {

          if (data.proceso === true) {

            $('#datoproc').html(datas);  
          }else{
           datas='<span class="label label-danger">Sin Datos</span>';
           $('#datoproc').html(datas); 
         }

       }  catch (e) {
        alert('Exception while request..'+e);
      }
      */
      
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
      $('#loading_fe'+cont).hide();
      alert(xhr+' '+ ajaxOptions +' '+ thrownError);
    } 
  });    

}
function get_anulacionmanual(){
  var fechabd=$('#fechabd').val();  
  var fechabh=$('#fechabh').val();
  var sts='';
  var url="facturacionsendbajaws_baja_control/get_anulacionmanual";
  $.ajax({
    url: url,
    type: "post",
    data:{fechabd:fechabd,fechabh:fechabh},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_baja').show();
      $("#msg").text(''); 
      $('#btnpromanbajdoc').button('disable');       
    },
    success: function (msj)    
    {
      if (msj.existe === 0) {
        $('#btnpromanbajdoc').button('enable');
        $('#loading_baja').hide(); 
        $("#msg").text(msj['tiempo1']+' '+msj['rptp']);
        carga_data_jsonwsbaja();
      }else{
       $('#btnpromanbajdoc').button('enable');
       $('#loading_baja').hide(); 
       $("#msg").text(msj['tiempo1']+' '+msj['rptp']);
       carga_data_jsonwsbaja();
     }
   },
   error: function (xhr, ajaxOptions, thrownError)
   {
    $('#btnpromanbajdoc').button('enable');
    $("#msg").text(''); 
    $('#loading_baja').hide();
    var erro= xhr+' '+ ajaxOptions +' '+ thrownError;
    $("#msg").text(erro);
    carga_data_jsonwsbaja();
  }
});

}

function get_optener_cdr(nrosere,nrocor,tipdoc,cont){
 //var nropdc = parseInt(nropdc, 10); 
 var nrosere= String(nrosere);
 var nrocor= String(nrocor);
 var tipdoc= String(tipdoc);
 var nomtipdoc=String(tipdoc);
 switch(nomtipdoc){
  case '01':
  nomtipdoc='Fatura';
  break;
  case '03':
  nomtipdoc='Boleta';
  break;
  case '07':
  nomtipdoc='Nota Credito';
  break;
  case '08':
  nomtipdoc='Nota Debito';
  break;
};
var url="facturacionsendws_control/optener_cdr_fe";
$.ajax({
  url: url,
  type: "post",
  data:{nrosere:nrosere,nrocor:nrocor,tipdoc:tipdoc},
  dataType: "JSON",
  beforeSend: function () {
    $('#loading_fe'+cont).show();
    $("#msg").text(''); 
    $('#btnpromanbajdoc').button('disable');       
  },
  success: function (msj)    
  {
    $('#loading_fe'+cont).hide();
    var datas='';
    if (msj.existe === 0) {
      dato=msj.datos;
      var conta=0;
      count=dato.length;
      if(count>0){
        for(i=0;i<count;i++){
          conta=conta+1;
          cad=dato[i];
          var xml=cad['dato2'];
          datas+='<li class="list-group-item">'+conta+' <strong>Codigo</strong> '+cad['dato1']+' <strong> Descripcion </strong>'+xml+'</li>';
        }
      }else{
       datas='<li class="list-group-item">Sin Datos</li>';
     }
   }else{
    datas='<li class="list-group-item">Sin Datos</li>';
  }
  $('#mdalevetosfe .modal-title').text('CDR Confirmacion de Recepcion de '+nomtipdoc+' '+nrosere+' '+nrocor);
  $('#mdalevetosfe .modal-body').html(datas);
  $('#mdalevetosfe').modal('show');
},
error: function (xhr, ajaxOptions, thrownError)
{
  $('#loading_fe'+cont).hide();
  $('#mdalevetosfe .modal-title').text(tipdoc+' '+nrosere+' '+nrocor);
  datas='<li class="list-group-item">'+xhr+' '+ ajaxOptions+' '+thrownError+'</li>';
  $('#mdalevetosfe .modal-body').html(datas);
  $('#mdalevetosfe').modal('show');
}
});

}

function set_printfe(cia,fecha,tipdoc,nropdc,serie,corr,cont){ 
  var fecha = parseInt(fecha, 10);
  var nropdc = parseInt(nropdc, 10);
  var cia= String(cia);
  var tipdoc= String(tipdoc);
  var serie= String(serie);
  var corr= String(corr);
  var title='print';
  var data= cia+'_'+fecha+'_'+tipdoc+'_'+nropdc+'_'+serie+'_'+corr+'_'+title;  
  var url="printfe/"+data;
 window.open(url,"_blank");
  
}
function set_printanufe(cia,fecha,nropdc,serie,corr,cont){ 
  var fecha = parseInt(fecha, 10);
  var nropdc = parseInt(nropdc, 10);
  var cia= String(cia);
  var serie= String(serie);
  var corr= String(corr);
  var title='print';
  var data= cia+'_'+fecha+'_'+nropdc+'_'+serie+'_'+corr+'_'+title;  
  var url="printanufe/"+data;
  window.open(url,"_blank");
}

function set_printfedemo(){ 

  var title='print';
  var data= title;  
  var url="printfedemo/"+data;
  window.open(url,"_blank");
}

function get_vereventosdocumentosfe(nropdc,nrosere,nrocor,tipdoc,cont){
  var nropdc = parseInt(nropdc, 10); 
  var nrosere= String(nrosere);
  var nrocor= String(nrocor);
  var tipdoc= String(tipdoc);
  var nomtipdoc=String(tipdoc);
  switch(nomtipdoc){
    case '01':
    nomtipdoc='Fatura';
    break;
    case '03':
    nomtipdoc='Boleta';
    break;
    case '07':
    nomtipdoc='Nota Credito';
    break;
    case '08':
    nomtipdoc='Nota Debito';
    break;
  };
  var url="facturacionsendws_control/get_vereventosdocumentosfe";
  $.ajax({
    url: url,
    type: "post",
    data:{nropdc:nropdc,nrosere:nrosere,nrocor:nrocor,tipdoc:tipdoc},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_fe'+cont).show();       
    },
    success: function (msj)    
    {
      $('#loading_fe'+cont).hide();
      var datas='';
      var datorf='';
      if (msj.existe === 0) {
        dato=msj.datos;
        var datorf=msj.datoad;
        var conta=0;
        count=dato.length;
        if(count>0){
          for(i=0;i<count;i++){
            conta=conta+1;
            cad=dato[i];
            datas+='<li class="list-group-item">'+conta+' <strong>Codigo</strong> '+cad['SACODRPT']+' <strong> Descripcion</strong> '+cad['SAMSGRPT']+'</li>';
          }
        }else{
         datas='<li class="list-group-item">Sin Datos</li>';
       }
     }else{
      datas='<li class="list-group-item">Sin Datos</li>';
    }
    $('#mdalevetosfe .modal-title').text('Eventos de '+nomtipdoc+' '+nropdc+' '+nrosere+' '+nrocor);
    $('#mdalevetosfe .modal-body').html(datas);
    $('#mdalevetosfe .modal-footer .modal-title').text(datorf);
    $('#mdalevetosfe').modal('show');
  },
  error: function (xhr, ajaxOptions, thrownError)
  {
    $('#loading_fe'+cont).hide();
    $('#mdalevetosfe .modal-title').text(tipdoc+' '+nropdc+' '+nrosere+' '+nrocor);
    datas='<li class="list-group-item">'+xhr+' '+ ajaxOptions+' '+thrownError+'</li>';
    $('#mdalevetosfe .modal-body').html(datas);
    $('#mdalevetosfe').modal('show');
  }
});

}

function get_vereventosbajafe(nropdc,nrosere,nrocor,tipdoc,cont){
  var nropdc = parseInt(nropdc, 10); 
  var nrosere= String(nrosere);
  var nrocor= String(nrocor);
  var tipdoc= String(tipdoc);
  var nomtipdoc=String(tipdoc);
  switch(nomtipdoc){
    case '01':
    nomtipdoc='Fatura';
    break;
    case '03':
    nomtipdoc='Boleta';
    break;
    case '07':
    nomtipdoc='Nota Credito';
    break;
    case '08':
    nomtipdoc='Nota Debito';
    break;
  };
  var url="facturacionsendbajaws_baja_control/get_vereventosbajafe";
  $.ajax({
    url: url,
    type: "post",
    data:{nropdc:nropdc,nrosere:nrosere,nrocor:nrocor,tipdoc:tipdoc},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_fe'+cont).show();       
    },
    success: function (msj)    
    {
      $('#loading_fe'+cont).hide();
      var datas='';
      var datorf='';
      if (msj.existe === 0) {
        dato=msj.datos;
        var datorf=msj.datoad;
        var conta=0;
        count=dato.length;
        if(count>0){
          for(i=0;i<count;i++){
            conta=conta+1;
            cad=dato[i];
            datas+='<li class="list-group-item">'+conta+' <strong>Codigo</strong> '+cad['SACODRPT']+' <strong> Descripcion</strong> '+cad['SAMSGRPT']+'</li>';
          }
        }else{
         datas='<li class="list-group-item">Sin Datos</li>';
       }
     }else{
      datas='<li class="list-group-item">Sin Datos</li>';
    }
    $('#mdalevetosfe .modal-title').text('Eventos de baja de '+nomtipdoc+' '+nropdc+' '+nrosere+' '+nrocor);
    $('#mdalevetosfe .modal-body').html(datas);
    $('#mdalevetosfe .modal-footer .modal-title').text(datorf);
    $('#mdalevetosfe').modal('show');
  },
  error: function (xhr, ajaxOptions, thrownError)
  {
    $('#loading_fe'+cont).hide();
    $('#mdalevetosfe .modal-title').text(tipdoc+' '+nropdc+' '+nrosere+' '+nrocor);
    datas='<li class="list-group-item">'+xhr+' '+ ajaxOptions+' '+thrownError+'</li>';   

    $('#mdalevetosfe .modal-body').html(datas);
    $('#mdalevetosfe').modal('show');
  }
});

}
function set_darbajafeas400(nropdc,nrosere,nrocor,tipdoc,femi,codsuc,cont){
  var nropdc = parseInt(nropdc, 10); 
  var nrosere= String(nrosere);
  var nrocor= String(nrocor);
  var tipdoc= String(tipdoc);
  var nomtipdoc=String(tipdoc);
  var femi= String(femi);
  var codsuc= String(codsuc);

  switch(nomtipdoc){
    case '01':
    nomtipdoc='Fatura';
    break;
    case '03':
    nomtipdoc='Boleta';
    break;
    case '07':
    nomtipdoc='Nota Credito';
    break;
    case '08':
    nomtipdoc='Nota Debito';
    break;
  };
  var url="facturacionsendbajaws_baja_control/set_darbajafeas400";
  $.ajax({
    url: url,
    type: "post",
    data:{nropdc:nropdc,nrosere:nrosere,nrocor:nrocor,tipdoc:tipdoc,femi:femi,codsuc:codsuc},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_fe'+cont).show();       
    },
    success: function (msj)    
    {
      $('#loading_fe'+cont).hide();
      var datas='';

      if (msj.existe === 0) {
        carga_data_jsonwsbaja();
        dato=msj.datos;
        var datorf=msj.datoad;      
        datas+='<li class="list-group-item"> <strong>'+dato+'</strong> </li>';
      }else{
        datas='<li class="list-group-item">Sin Datos</li>';
      }
      $('#mdalevetosfe .modal-title').text('Generacion de baja en as400 de '+nomtipdoc+' '+nropdc+' '+nrosere+' '+nrocor);
      $('#mdalevetosfe .modal-body').html(datas);
      $('#mdalevetosfe').modal('show');    
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
      $('#loading_fe'+cont).hide();
      $('#mdalevetosfe .modal-title').text('Generacion de baja en as400 de '+tipdoc+' '+nropdc+' '+nrosere+' '+nrocor);
      datas='<li class="list-group-item">'+xhr+' '+ ajaxOptions+' '+thrownError+'</li>';
      $('#mdalevetosfe .modal-body').html(datas);
      $('#mdalevetosfe').modal('show');
    }
  });

}

function get_verticketbajafe(nropdc,codalm,tipdoc,docu,cont){
  var nropdc = parseInt(nropdc, 10); 
  var codalm= String(codalm);
  var tipdoc=String(tipdoc);
  var nomtipdoc=String(tipdoc);
  switch(nomtipdoc){
    case '01':
    nomtipdoc='Fatura';
    break;
    case '03':
    nomtipdoc='Boleta';
    break;
    case '07':
    nomtipdoc='Nota Credito';
    break;
    case '08':
    nomtipdoc='Nota Debito';
    break;
  };
  var datas='';
  var datorf='';
  var url="facturacionsendbajaws_baja_control/get_verticketbajafe";
  $.ajax({
    url: url,
    type: "post",
    data:{nropdc:nropdc,codalm:codalm,tipdoc:tipdoc},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_fe'+cont).show();       
    },
    success: function (msj)    
    {
      $('#loading_fe'+cont).hide();      
      if (msj.existe === 1) {
        dato=msj.datos;           
        datas+='<li class="list-group-item"> <strong> '+dato+'  </strong> </li>';         

      }else{
        datas='<li class="list-group-item">Sin Datos</li>';
      }

      $('#mdalevetosfe .modal-title').text('Ver estado de Ticket de '+nomtipdoc+' '+nropdc+' '+docu);
      $('#mdalevetosfe .modal-body').html(datas);
      $('#mdalevetosfe .modal-footer .modal-title').text(datorf);
      $('#mdalevetosfe').modal('show');

    },
    error: function (xhr, ajaxOptions, thrownError)
    {
      $('#loading_fe'+cont).hide();
      $('#mdalevetosfe .modal-title').text('Ver estado de Ticket de '+ nomtipdoc+' '+nropdc+' '+docu);
      datas='<li class="list-group-item">'+xhr+' '+ ajaxOptions+' '+thrownError+'</li>';
      $('#mdalevetosfe .modal-body').html(datas);
      $('#mdalevetosfe').modal('show');
    }
  });

}
function carga_data_jsonws(){
 $('#resulta').html('');
 var codclie=$('#codclie').val();
 var seltd=$('#seltd').val();
 var selseri=$('#selseria').val();
 var fechaad=$('#fechaad').val();
 var fechaah=$('#fechaah').val();
 var selstsd=$('#selstsd').val();
 var url="facturacionsendws_control/get_documentosfe";
 $.ajax({
  url: url,
  type: "post",
  data:{seltd:seltd,selseri:selseri,fechaad:fechaad,fechaah:fechaah,selstsd:selstsd,codclie:codclie},
  dataType: "JSON",
  beforeSend: function () {
    $('#loading_spinner').show();          
  },
  success: function (msj)    
  {
    $('#loading_spinner').hide();
    var dato='';
    var html = '';
    var tbody='';
    var i;
    var conta = 0;
    var count=0;
    var cad='';
    var documen='';
    var estareg
    try {
      if (msj.existe === 0) {
        dato=msj.datos;
        count=dato.length;
        if(count>0){
          for(i=0;i<count;i++){
            conta=conta+1;
            cad=dato[i];
            var estado=cad['SCTCSTST'];
            switch(estado){
              case 'A':
              estado='ACEPTADO';
              break;
              case 'G':
              estado='GENERADO';
              break;
              case 'E':
              estado='ENVIADO';
              break;
              case 'R':
              estado='RECHAZADO';
              break;
              case 'I':
              estado='ANULADO';
              break;
              case 'N':
              estado='POR ANULAR';
              break;
              default:
              estado='NO ESTADO'+estado;
              break;
            };
            estareg=cad['SCTCSTST'];
            switch(estareg){
              case 'A':
              estareg='class="success"';
              break;
              case 'G':
              estareg='class="active"';
              break;
              case 'E':
              estareg='class="info"';
              break;
              case 'R':
              estareg='class="danger"';
              break;
              case 'I':
              estareg='class="warning"';
              break;
              default:
              estareg='class="default"';
              break;
            };
            SCTIPFACS=cad['SCTIPFAC'];
              switch(SCTIPFACS){
                case 'FN':
                SCTIPFACS='success';
                break;
                case 'FG':
                SCTIPFACS='info';
                break;
                case 'FE':
                SCTIPFACS='default';
                break;
                case 'FS':
                SCTIPFACS='danger';
                break;
                case 'FA':
                SCTIPFACS='primary';
                break;
                case 'FF':
                SCTIPFACS='warning';
                break;
                default:
                SCTIPFACS='';
                break;
              };
              SCTIPFAC=cad['SCTIPFAC'];
            documen=cad['SCTETDOC'];
            switch(documen){
              case '01':
             documen='FACTURA <span class="label label-'+SCTIPFACS+'">'+SCTIPFAC+'</span>';
              break;
              case '03':
              documen='BOLETA';
              break;
              case '07':
              documen='NOTA CREDITO';
              break;
              case '08':
              documen='NOTA DEBITO';
              break;
              default:
              documen='NO DEFINIDO';
              break;
            };

            var param="'"+cad['SCTECIAA']+"',"+cad['SCTEFEC']+",'"+cad['SCTETDOC']+"',"+cad['SCTEPDCA']+",'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"',"+conta; 
            var parame="'"+cad['SCTEPDCA']+"','"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"','"+ cad['SCTETDOC']+"',"+conta; 
            var paramcdr="'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"','"+ cad['SCTETDOC']+"',"+conta;
            var acep=cad['SCTCSTST'];
            var nroev=cad['nroevento'];
            var activo='';
            if((acep=='G')||(acep=='R')||(acep=='E')){
              activo='<a href="javascript:void()" title="Enviarfa"  onclick="set_sendfe('+param+')" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-send" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa"  onclick="set_printfe('+param+')" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></a><a href="javascript:void()" title="Eventosfa"  onclick="get_vereventosdocumentosfe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="VerCDR"  onclick="get_optener_cdr('+paramcdr+')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></a>';
            }else if(acep=='N'){
             activo='<a href="javascript:void()" title="Enviarfa" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-send" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa" class="btn btn-info btn-xs disabled"><span class="glyphicon glyphicon-print" aria-hidden="true"></a><a href="javascript:void()" title="Eventosfa"  onclick="get_vereventosdocumentosfe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="VerCDR"  onclick="get_optener_cdr('+paramcdr+')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></a>'; 

           }else{
            activo='<a href="javascript:void()" title="Enviarfa" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-send" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa"  onclick="set_printfe('+param+')" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></a><a href="javascript:void()" title="Eventosfa"  onclick="get_vereventosdocumentosfe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="VerCDR"  onclick="get_optener_cdr('+paramcdr+')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></a>';
          }
          tbody+='<tr '+estareg+'><td>'+conta+'</td><td> <font size="0px">'+cad['SCTFECEM']+'</font></td><td>'+cad['SCTESUCA']+'</td><td>'+cad['SCTCRZSO']+'</td><td> <font size="0px">'+documen+'</font></td><td>'+cad['SCTEPDCA']+'</td><td>'+cad['SCTESERI']+' '+cad['SCTECORR']+'</td><td>'+cad['SCTCTMON']+' <strong>'+cad['SCTGNETO']+'</strong></td><td>'+estado+'</td><td>'+activo+'<img id="loading_fe'+conta+'" src="assest/imagen/loading8.gif" style="display: none;"></td></tr>';         
        }
        $('#tbdocufe tbody').html(tbody);
      }else{
       tbody='<tr class="danger"><td colspan="11" align="center"> Sin datos</td></tr>';    
       $('#tbdocufe tbody').html(tbody);
     }
   }else{
    tbody='<tr class="danger"><td colspan="11" align="center"> Sin datos</td></tr>';    
    $('#tbdocufe tbody').html(tbody);
  }
}  catch (e) {
  $('#loading_spinner').hide();
  tbody='<tr class="danger"><td colspan="11" align="center">'+e+' </td></tr>';    
  $('#tbdocufe tbody').html(tbody);
}
},
error: function (xhr, ajaxOptions, thrownError)
{
  $('#loading_spinner').hide();
  tbody='<tr class="danger"><td colspan="11" align="center">Error: '+xhr+' '+ ajaxOptions +' '+ thrownError+' </td></tr>';    
  $('#tbdocufe tbody').html(tbody);
}
});

}
function carga_data_jsonwsclie(){
  var codclie=$('#codclie').val();
  var seltd=$('#seltdc').val();
  var selseri=$('#selseriac').val();
  var fechadc=$('#fechaadc').val();
  var fechahc=$('#fechaahc').val();
  var url="facturacionsendws_control/get_documentosfe_clie";
  $.ajax({
    url: url,
    type: "post",
    data:{seltd:seltd,selseri:selseri,fechadc:fechadc,fechahc:fechahc,codclie:codclie},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_spinnerc').show();          
    },
    success: function (msj)    
    {
      $('#loading_spinnerc').hide();
      var dato='';
      var html = '';
      var tbody='';
      var i;
      var conta = 0;
      var count=0;
      var cad='';
      var documen='';
      var SCTIPFAC='';
      var SCTIPFACS='';
      var estareg
      try {
        if (msj.existe === 0) {
          dato=msj.datos;
          count=dato.length;
          if(count>0){
            for(i=0;i<count;i++){
              conta=conta+1;
              cad=dato[i];
              var estado=cad['SCTCSTST'];
              switch(estado){
                case 'A':
                estado='ACEPTADO';
                break;
                case 'G':
                estado='GENERADO';
                break;
                case 'E':
                estado='ENVIADO';
                break;
                case 'R':
                estado='RECHAZADO';
                break;
                case 'I':
                estado='ANULADO';
                break;
                case 'N':
                estado='POR ANULAR';
                break;
                default:
                estado='NO ESTADO '+estado;
                break;
              };
              estareg=cad['SCTCSTST'];
              switch(estareg){
                case 'A':
                estareg='class="success"';
                break;
                case 'G':
                estareg='class="active"';
                break;
                case 'E':
                estareg='class="info"';
                break;
                case 'R':
                estareg='class="danger"';
                break;
                case 'I':
                estareg='class="warning"';
                break;
                default:
                estareg='class="default"';
                break;
              };
              
              SCTIPFACS=cad['SCTIPFAC'];
              switch(SCTIPFACS){
                case 'FN':
                SCTIPFACS='success';
                break;
                case 'FG':
                SCTIPFACS='info';
                break;
                case 'FE':
                SCTIPFACS='default';
                break;
                case 'FS':
                SCTIPFACS='danger';
                break;
                case 'FA':
                SCTIPFACS='primary';
                break;
                case 'FF':
                SCTIPFACS='warning';
                break;
                default:
                SCTIPFACS='';
                break;
              };
              SCTIPFAC=cad['SCTIPFAC'];
              documen=cad['SCTETDOC'];
              switch(documen){
                case '01':
                documen='FACTURA <span class="label label-'+SCTIPFACS+'">'+SCTIPFAC+'</span>';
                break;
                case '03':
                documen='BOLETA';
                break;
                case '07':
                documen='NOTA CREDITO';
                break;
                case '08':
                documen='NOTA DEBITO';
                break;
                default:
                documen='NO DEFINIDO';
                break;
              };
              var param="'"+cad['SCTECIAA']+"',"+cad['SCTEFEC']+",'"+cad['SCTETDOC']+"',"+cad['SCTEPDCA']+",'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"',"+conta; 
            
             // var param="'"+cad['SCTECIAA']+"',"+cad['SCTEFEC']+","+cad['SCTEPDCA']+",'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"',"+conta; 
              var parame="'"+cad['SCTEPDCA']+"','"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"','"+ cad['SCTETDOC']+"',"+conta; 
              var paramcdr="'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"','"+ cad['SCTETDOC']+"',"+conta;
              var acep=cad['SCTCSTST'];
              var nroev=cad['nroevento'];
              var activo='';
              if((acep=='G')||(acep=='R')||(acep=='E')){
                activo='<a href="javascript:void()" title="Enviarfa"  onclick="set_sendfe('+param+')" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-send" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa"  onclick="set_printfe('+param+')" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></a><a href="javascript:void()" title="Eventosfa"  onclick="get_vereventosdocumentosfe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="VerCDR"  onclick="get_optener_cdr('+paramcdr+')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></a>';
              }else if(acep=='N'){
               activo='<a href="javascript:void()" title="Enviarfa" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa" class="btn btn-info btn-xs disabled"><span class="glyphicon glyphicon-print" aria-hidden="true"></a><a href="javascript:void()" title="Eventosfa"  onclick="get_vereventosdocumentosfe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="VerCDR"  onclick="get_optener_cdr('+paramcdr+')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></a>'; 
             }else{
              activo='<a href="javascript:void()" title="Enviarfa" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-send" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa"  onclick="set_printfe('+param+')" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></a><a href="javascript:void()" title="Eventosfa"  onclick="get_vereventosdocumentosfe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="VerCDR"  onclick="get_optener_cdr('+paramcdr+')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></a>';
            }
            tbody+='<tr '+estareg+'><td>'+conta+'</td><td> <font size="0px">'+cad['SCTFECEM']+'</font></td><td>'+cad['SCTESUCA']+'</td><td>'+cad['SCTCRZSO']+'</td><td> <font size="0px">'+documen+'</font></td><td>'+cad['SCTEPDCA']+'</td><td>'+cad['SCTESERI']+' '+cad['SCTECORR']+'</td><td>'+cad['SCTCTMON']+' <strong>'+cad['SCTGNETO']+'</strong></td><td>'+estado+'</td><td>'+activo+'<img id="loading_fe'+conta+'" src="assest/imagen/loading8.gif" style="display: none;"></td></tr>';         
          }
          $('#tbdocufec tbody').html(tbody);
        }else{
         tbody='<tr class="danger"><td colspan="11" align="center"> Sin datos</td></tr>';    
         $('#tbdocufec tbody').html(tbody);
       }
     }else{
      tbody='<tr class="danger"><td colspan="11" align="center"> Sin datos</td></tr>';    
      $('#tbdocufec tbody').html(tbody);
    }
  }  catch (e) {
    $('#loading_spinnerc').hide();
    tbody='<tr class="danger"><td colspan="11" align="center">'+e+' </td></tr>';    
    $('#tbdocufec tbody').html(tbody);
  }
},
error: function (xhr, ajaxOptions, thrownError)
{
  $('#loading_spinnerc').hide();
  tbody='<tr class="danger"><td colspan="11" align="center">Error: '+xhr+' '+ ajaxOptions +' '+ thrownError+' </td></tr>';    
  $('#tbdocufec tbody').html(tbody);
}
});

}
function optener_status_ticket(){
  var nrotk=$('#nrotk').val();
  var url="facturacionsendbajaws_baja_control/optener_status_ticket";
   //var url="facturacionsendws_control/cargajson_3scon";
   $.ajax({
    url: url,
    type: "post",
    data:{nrotk:nrotk},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_spinner').show();          
    },
    success: function (msj)    
    {
      $('#loading_spinner').hide();
      console.log(msj);
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
     alert(xhr+' '+ajaxOptions+''+ thrownError); 
   }
 });

 }
 function optener_status_webservice(){
  var nrotk='';
  var url="facturacionsendws_control/optener_status_webservice";
  $.ajax({
    url: url,
    type: "post",
    data:{nrotk:nrotk},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_spinner').show();          
    },
    success: function (msj)    
    {
      $('#loading_spinner').hide();
      console.log(msj);
    },
    error: function (xhr, ajaxOptions, thrownError)
    {
     alert(xhr+' '+ajaxOptions+''+ thrownError); 
   }
 });

}   

function carga_data_jsonwsbaja(){
  var seltd=$('#seltdb').val();
  var selseri=$('#selserib').val();
  var fechabd=$('#fechabd').val();
  var fechabh=$('#fechabh').val();
  var selstsdb=$('#selstsdb').val();  
  var url="facturacionsendbajaws_baja_control/get_detalledocumentobaja";
  $.ajax({
    url: url,
    type: "post",
    data:{seltd:seltd,selseri:selseri,fechabd:fechabd,fechabh:fechabh,selstsdb:selstsdb},
    dataType: "JSON",
    beforeSend: function () {
      $('#loading_spinneranu').show();          
    },
    success: function (msj)    
    {
      $('#loading_spinneranu').hide(); 
      var html='';
      var fecha ='';
      var estadot='';
      var estadoa='';
      var conta=0;
      var estareg='';
      if (msj.existe === 0) {
        dato=msj.datos;
        count=dato.length;
        try {
         for(i=0;i<count;i++){
          conta=conta+1;
          cad=dato[i];          

          estadoyh=cad['YHSTS'];
          switch (estadoyh) {
            case 'A':
            estadoyh='<span class="label label-success">'+estadoyh+'</span>';
            break;
            case 'E':
            estadoyh='<span class="label label-info">'+estadoyh+'</span>';
            break;
            case 'R':
            estadoyh='<span class="label label-warning">'+estadoyh+'</span>';
            break;
            case 'I':
            estadoyh='<span class="label label-danger">'+estadoyh+'</span>';
            break;
            default:
            estadoyh='<span class="label label-primary">'+estadoyh+'</span>';
            break;
          }
          estadoa=cad['ESTADO'];
          switch (estadoa) {
            case 'A':
            estadoa='<span class="label label-success">'+estadoa+'</span>';
            break;
            case 'E':
            estadoa='<span class="label label-info">'+estadoa+'</span>';
            break;
            case 'R':
            estadoa='<span class="label label-warning">'+estadoa+'</span>';
            break;
            case 'I':
            estadoa='<span class="label label-danger">'+estadoa+'</span>';
            break;
            default:
            estadoa='<span class="label label-default">'+estadoa+'</span>';
            break;
          }
          estadot=cad['STATRAM'];
          switch (estadot) {
            case 'A':
            estadot='<span class="label label-success">'+estadot+'</span>';
            break;
            case 'E':
            estadot='<span class="label label-info">'+estadot+'</span>';
            break;
            case 'R':
            estadot='<span class="label label-warning">'+estadot+'</span>';
            break;
            case 'I':
            estadot='<span class="label label-danger">'+estadot+'</span>';
            break;
            default:
            estadot='<span class="label label-default">'+estadot+'</span>';
            break;
          }
          estadows=cad['SCTCSTST'];
          switch (estadows) {
            case 'A':
            estadows='<span class="label label-success" title="Activo">'+estadows+'</span>';
            break;
            case 'E':
            estadows='<span class="label label-info" title="Enviado">'+estadows+'</span>';
            break;
            case 'R':
            estadows='<span class="label label-warning" title="Rechazado">'+estadows+'</span>';
            break;
            case 'N':
            estadows='<span class="label label-primary" title="PorAnulado">'+estadows+'</span>';
            break;
            case 'I':
            estadows='<span class="label label-default" title="Anulado">'+estadows+'</span>';
            break;
            default:
            estadows='<span class="label label-danger" title="No indentificado">'+estadows+'</span>';
            break;
          }
          estareg=cad['STATRAM'];
          switch(estareg){
            case 'A':
            estareg='class="default"';
            break;
            case 'G':
            estareg='class="active"';
            break;
            case 'E':
            estareg='class="info"';
            break;
            case 'R':
            estareg='class="danger"';
            break;
            case 'I':
            estareg='class="warning"';
            break;
            default:
            estareg='class="success"';
            break;
          };
          documen=cad['YHTIPDOC'];
          switch(documen){
            case '01':
            documen='FACTURA';
            break;
            case '03':
            documen='BOLETA';
            break;
            case '07':
            documen='NOTA CREDITO';
            break;
            case '08':
            documen='NOTA DEBITO';
            break;
            default:
            documen='NO DEFINIDO';
            break;
          }; 
          var paramanu="'"+cad['SCTECIAA']+"',"+cad['SCTEFEC']+","+cad['YHNROPDC']+",'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"', "+conta;
          var param=conta+",'"+cad['SCTFECEM']+"',"+cad['YHNROPDC']+",'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"','"+ cad['SCTETDOC']+"','"+ cad['SCTCSTST']+"','"+ cad['SCTEALMA']+"'";
          var parambas=cad['YHNROPDC']+",'"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"','"+ cad['SCTETDOC']+"','"+cad['SCTFECEM']+"','"+ cad['YHCODSUC']+"', "+conta;
          var parame="'"+cad['YHNROPDC']+"','"+cad['SCTESERI']+"','"+ cad['SCTECORR']+"','"+ cad['SCTETDOC']+"',"+conta; 
          var paramtk="'"+cad['YHNROPDC']+"','"+ cad['SCTEALMA']+"','"+ cad['SCTETDOC']+"','"+cad['SCTESERI']+"-"+ cad['SCTECORR']+"', "+conta; 
          var acepw=cad['SCTCSTST'];
          var activo='';
          var anularenas400=(acepw=='E')||(acepw=='R')?'<a href="javascript:void()" title="darBajafaas400"  onclick="set_darbajafeas400('+parambas+')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></a>':'';
          var verticketbaj=((acepw=='N')||(acepw=='I'))?'<a href="javascript:void()" title="VerTicketBajafa"  onclick="get_verticketbajafe('+paramtk+')" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-tag" aria-hidden="true"></a>':'';          
          if((acepw=='A')||(acepw=='E')||(acepw=='N')||(acepw=='R')){
           activo='<a href="javascript:void()" title="Enviarfebaja"  onclick="set_sendfebaj('+param+')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></a><a href="javascript:void()" title="EventosBajafa"  onclick="get_vereventosbajafe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa"  onclick="set_printanufe('+paramanu+')" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></a>'+verticketbaj+anularenas400; 
         }else{
          activo='<a href="javascript:void()" title="Enviarfebaja" class="btn btn-danger btn-xs disabled"><span class="glyphicon glyphicon-trash" aria-hidden="true"></a><a href="javascript:void()" title="EventosBajafa"  onclick="get_vereventosbajafe('+parame+')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></a><a href="javascript:void()" title="Imprimirfa" onclick="set_printanufe('+paramanu+')" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></a>'+verticketbaj+anularenas400;
        }
        html+='<tr '+estareg+'><td>'+conta+'</td><td> <font size="0px">'+cad['SCTFECEM']+'</font></td><td>'+cad['YHCODSUC']+'</td><td>'+cad['DATACLIE']+'</td><td> <font size="0px">'+documen+'</font></td><td>'+cad['YHNROPDC']+'</td><td>'+cad['SCTESERI']+' '+cad['SCTECORR']+'</td><td>'+cad['MONEDA']+' <strong>'+cad['NUMIMPORTE']+'</strong></td><td>'+estadoyh+'</td><td>'+estadot+' '+estadows+'</td><td>'+activo+'<img id="loading_fe'+conta+'" src="assest/imagen/loading8.gif" style="display: none;"></td></tr>';         
      } 
      $('#tbdetadocanu tbody').html(html);
    }  catch (e) {
      html='<tr class="warning"><td colspan="11"> <font size="0px">No hay Datos para Mostrar '+e+'</font> </td>  </tr>';
      $('#tbdetadocanu tbody').html(html);
    }
  }else{
    html='<tr class="warning"><td colspan="11"> <font size="0px">No hay Datos para Mostrar </font> </td>  </tr>';
    $('#tbdetadocanu tbody').html(html); 
  }
},
error: function (xhr, ajaxOptions, thrownError)
{  
  $('#loading_spinneranu').hide();     
  var html='<tr class="warning"><td colspan="11"> <font size="0px">Error: '+xhr+' '+ ajaxOptions +' '+ thrownError+'</font> </td>  </tr>';
  $('#tbdetadocanu tbody').html(html);
}
});

}

$(function () {
  $("#fechaad").datepicker({
    dateFormat: "dd-mm-yy"    
  });
});
$(function () {
  $("#fechaah").datepicker({
    dateFormat: "dd-mm-yy"    
  });
});
$(function () {
  $("#fechabd").datepicker({
    dateFormat: "dd-mm-yy"
  });
});
$(function () {
  $("#fechabh").datepicker({
    dateFormat: "dd-mm-yy"
  });
});
$(function () {
  $("#fechaahc").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "dd-mm-yy"
  });
});
$(function () {
  $("#fechaadc").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "dd-mm-yy"
  });
});
//,
</script>

<div class="row">
 <h2>Facturacion Electronica</h2>

 <ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Activos</a></li>
  <li><a data-toggle="tab" href="#menu1">Anulados</a></li>  
  <li><a data-toggle="tab" href="#menu2">Consultas</a></li>
  <!--<li><a data-toggle="tab" href="#menu3">Resumen</a></li> -->
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
   <div class="panel panel-default">
    <div class="panel-body">          
      <div class="row"> 
       <div class="col-md-12">
        <h3><p>Lista documentos Emitidos</p></h3>
        <form class="form-inline" action="">
         <div class="form-group">
          <label for="selmes">Fecha</label>
          <input type="text" name="fechaad" id="fechaad" class="form-control" value="<?php echo  gmdate("d-m-Y", time() -18000);?>" placeholder="Fecha desde">          
        </div>
        <div class="form-group">
          <label for="selmes">a</label>
          <input type="text" name="fechaah" id="fechaah" class="form-control" value="<?php echo  gmdate("d-m-Y", time() -18000);?>" placeholder="Fecha hasta">          
        </div>
        <div class="form-group">
          <label for="selanioa">Documento</label>
          <select class="form-control" id="seltd" name="seltd">
            <?php 
            if(count($lstatpdc)>0){
              ?>
              <option selected="" value="">--Seleccione--</option>
              <?php
              for ($i=0; $i < count($lstatpdc); $i++) { 
                $cad=$lstatpdc[$i];                  
                $SCTETDOC=$cad['SCTETDOC'];
                $EUDSCCOR=$cad['EUDSCCOR'];
                ?>
                <option value="<?php echo $SCTETDOC ?>"><?php echo $EUDSCCOR;?></option>
                <?php
              }
            }else{?>
              <option selected="" value="">--Seleccione--</option>
              <?php
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="selmesa">Serie</label>
          <select class="form-control" id="selseria" name="selseria">
            <?php               
            if(count($lstaser)>0){
              ?>
              <option selected="" value="">--Seleccione--</option>
              <?php
              for ($i=0; $i < count($lstaser); $i++) {
                $cad=$lstaser[$i];
                $SCTESERI=$cad['SCTESERI'];
                ?>
                <option value="<?php echo $SCTESERI ?>"><?php echo $SCTESERI;?></option>
                <?php
              }
            }else{?>
              <option selected="" value="">--Seleccione--</option>
              <?php
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="selstsd">Estado</label>
          <select class="form-control" id="selstsd" name="selstsd">
            <?php               
            if(count($lissts)>0){
              ?>
              <option selected="" value="">--Seleccione--</option>
              <?php
              for ($i=0; $i < count($lissts); $i++) {
                $cad=$lissts[$i];
                ?>
                <option value="<?php echo $cad['estacod']; ?>"><?php echo $cad['estades'];;?></option>
                <?php
              }
            }else{?>
              <option selected="" value="">--Seleccione--</option>
              <?php
            }
            ?>
          </select>
        </div>

        <button type="button" class="btn btn-info" id="btnmostraractivos"><span class="glyphicon glyphicon-search"></span>&nbsp;</button>
      </form>

    </div>
    <div class="form-group gifCarga"><img id="loading_spinner" src="assest/imagen/loading8.gif" style="display: none;"></div>

  </div>
  <br>
  <div class="col-md-12"> 
   <div id="resulta"></div>
   <div class="taable-responsive">
     <table class="table table-hover table-bordered" id="tbdocufe">
      <thead>
        <tr>
          <th>N°</th>
          <th>EMISION</th>
          <th>SUC.</th>
          <th>CLIENTE</th>
          <th>TIPO</th>
          <th>INTERNO</th>
          <th>DOCUMENTO</th>
          <th>MONTO</th>
          <th>ESTADO</th>
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
<div id="menu1" class="tab-pane fade">   
 <div class="panel panel-default">
  <div class="panel-body">          
    <div class="row"> 
     <div class="col-md-12">
      <h3><p>Lista documentos Anulados</p></h3>
      <form class="form-inline" action="">
       <div class="form-group">
        <label for="selmes">Fecha</label>
        <input type="text" name="fechabd" id="fechabd" class="form-control" value="<?php echo  gmdate("d-m-Y", time() -18000);?>" placeholder="Fecha desde">
      </div>
      <div class="form-group">
        <label for="selmes">a</label>
        <input type="text" name="fechabh" id="fechabh" class="form-control" value="<?php echo  gmdate("d-m-Y", time() -18000);?>" placeholder="Fecha hasta">
      </div>
      <div class="form-group">          
        <label for="selaniob">Documento</label>
        <select class="form-control" id="seltdb" name="seltdb">
          <?php 
          if(count($lstatpdcb)>0){
            ?>
            <option selected="" value="">--Tipo--</option>
            <?php
            for ($i=0; $i < count($lstatpdcb); $i++) { 
              $cad=$lstatpdcb[$i];                  
              $SCTETDOC=$cad['SCTETDOC'];
              $EUDSCCOR=$cad['EUDSCCOR'];
              ?>
              <option value="<?php echo $SCTETDOC ?>"><?php echo $EUDSCCOR;?></option>
              <?php
            }
          }else{?>
            <option selected="" value="">--Tipo--</option>
            <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="selmesb"></label>
        <select class="form-control" id="selserib" name="selserib">
          <?php               
          if(count($lstaserb)>0){
            ?>
            <option selected="" value="">--Serie--</option>
            <?php
            for ($i=0; $i < count($lstaserb); $i++) {
              $cad=$lstaserb[$i];
              $SCTESERI=$cad['SCTESERI'];
              ?>
              <option value="<?php echo $SCTESERI ?>"><?php echo $SCTESERI;?></option>
              <?php
            }
          }else{?>
            <option selected="" value="">--Serie--</option>
            <?php
          }
          ?>
        </select>
      </div>
        <!--
          <div class="form-group">
            <label for="selstsd">Estado</label>
            <select class="form-control" id="selstsdb" name="selstsdb">
              <?php               
              if(count($lisstsb)>0){
                ?>
                <option selected="" value="">--Seleccione--</option>
                <?php
                for ($i=0; $i < count($lisstsb); $i++) {
                  $cad=$lisstsb[$i];
                  ?>
                  <option value="<?php echo $cad['estacod']; ?>"><?php echo $cad['estades'];;?></option>
                  <?php
                }
              }else{?>
                <option selected="" value="">--Seleccione--</option>
                <?php
              }
              ?>
            </select>
          </div>
        -->
        <button type="button" class="btn btn-info" id="btnmostrarbajas"><span class="glyphicon glyphicon-search"></span>&nbsp;</button>
        <button type="button" class="btn btn-danger" id="btnpromanbajdoc"><span class="glyphicon glyphicon-cog"></span>&nbsp;</button><div class="form-group gifCarga"><img id="loading_baja" src="assest/imagen/loading8.gif" style="display: none;"></div><p id="msg"></p>
      </form>
         <!--
      <div class="form-group">
        <label for="selmes">Ticket</label>
        <input type="text" name="nrotk" id="nrotk" class="form-control" value=""> btntestprint
      </div>
   
    <button type="button" class="btn btn-info" id="btnmostrarstatus"><span class="glyphicon glyphicon-search"></span>&nbsp;MOSTRAR</button>
 -->
  </div>
  <div class="form-group gifCarga"><img id="loading_spinneranu" src="assest/imagen/loading8.gif" style="display: none;"></div>
</div>
<br>
<div class="col-md-12"> 
  <div class="taable-responsive">
    <form role="form" class="form-horizontal" name="frmdocanu" id="frmdocanu">
      <div class="modal-body">
       <p class="statusMsg"></p> 
       <input type="hidden" name="fechaas" id="fechaas" value=""></input>
       <table class="table table-bordered" id="tbdetadocanu">
        <thead>             
          <tr >
            <th rowspan="2">N°</th>
            <th rowspan="2">EMISION</th>
            <th rowspan="2">SUC.</th>
            <th rowspan="2">CLIENTE</th>
            <th rowspan="2">TIPO</th>
            <th rowspan="2">INTERNO</th>
            <th rowspan="2">DOCUMENTO</th>
            <th rowspan="2">MONTO</th>
            <th colspan="2" align="center">ESTADO</th>           
            <th rowspan="2">ACCION</th>
          </tr>
          <tr align="center">           
            <th>AS400</th>
            <th>TRAMA</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <!-- Modal Footer -->
    <div class="modal-footer">
        <!--
        <input type="checkbox" name="chksndxml" id="chksndxml" value="1">Solo Generar XML y Enviar
        <button type="button" class="btn btn-danger btnclosemd" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnbajadoc"><span class="glyphicon glyphicon-cloud-upload"></span>Enviar</button>-->
      </div>
    </form>
  </div>
</div>
</div>
</div>
</div>
<div id="menu2" class="tab-pane fade">
 <div class="panel panel-default">
  <div class="panel-body">          
    <div class="row"> 
     <div class="col-md-12">
      <h3><p>Consulta de documentos Emitidos por cliente y documento</p></h3>
      <form class="form-inline" action="">         
       <div class="form-group">
        <label for="fechadc">Fecha</label>
        <input type="text" name="fechaadc" id="fechaadc" class="form-control" value="<?php echo  gmdate("d-m-Y", time() -18000);?>" placeholder="desde">          
      </div>
      <div class="form-group">
        <label for="fechahc">a</label>
        <input type="text" name="fechaahc" id="fechaahc" class="form-control" value="<?php echo  gmdate("d-m-Y", time() -18000);?>" placeholder="Hasta">          
      </div>

      <div class="form-group">
        <label for="selanioa">Documento</label>
        <select class="form-control" id="seltdc" name="seltdc">
          <?php 
          if(count($lstatpdc)>0){
            ?>
            <option selected="" value="">--Tipo--</option>
            <?php
            for ($i=0; $i < count($lstatpdc); $i++) { 
              $cad=$lstatpdc[$i];                  
              $SCTETDOC=$cad['SCTETDOC'];
              $EUDSCCOR=$cad['EUDSCCOR'];
              ?>
              <option value="<?php echo $SCTETDOC ?>"><?php echo $EUDSCCOR;?></option>
              <?php
            }
          }else{?>
            <option selected="" value="">--Tipo--</option>
            <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="selmesa"></label>
        <select class="form-control" id="selseriac" name="selseriac">
          <?php               
          if(count($lstaser)>0){
            ?>
            <option selected="" value="">--Serie--</option>
            <?php
            for ($i=0; $i < count($lstaser); $i++) {
              $cad=$lstaser[$i];
              $SCTESERI=$cad['SCTESERI'];
              ?>
              <option value="<?php echo $SCTESERI ?>"><?php echo $SCTESERI;?></option>
              <?php
            }
          }else{?>
            <option selected="" value="">--Serie--</option>
            <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="selmes">Cliente</label>
        <input type="text" name="codclie" id="codclie" class="form-control" value="" placeholder="Codigo-RUC-Nombre">
      </div>

      <button type="button" class="btn btn-info" id="btnmostraractivosxcliente"><span class="glyphicon glyphicon-search"></span>&nbsp;</button>
    </form>

  </div>
  <div class="form-group gifCarga"><img id="loading_spinnerc" src="assest/imagen/loading8.gif" style="display: none;"></div>

</div>
<br>
<div class="col-md-12"> 
 <div id="resultac"></div>
 <div class="taable-responsive">
   <table class="table table-hover table-bordered" id="tbdocufec">
    <thead>
      <tr>
        <th>N°</th>
        <th>EMISION</th>
        <th>SUC.</th>
        <th>CLIENTE</th>
        <th>TIPO</th>
        <th>INTERNO</th>
        <th>DOCUMENTO</th>
        <th>MONTO</th>
        <th>ESTADO</th>
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
<div id="menu3" class="tab-pane fade">
  <h3>Resumen</h3>

</div>
</div>

<!-- The Modal -->
<div class="modal fade" id="mdalevetosfe">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Modal Heading</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        Modal body..
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- -->



</section>