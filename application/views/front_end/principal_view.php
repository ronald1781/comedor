<section class="container">
  <script type="text/javascript">

    var txt_mes={"01":"Ene","02":"Feb","03":"Mar","04":"Abr","05":"May","06":"Jun","07":"Jul","08":"Ago","09":"Sep","10":"Oct","11":"Nov","12":"Dic"};
    google.charts.load('current', {'packages':['sankey','corechart', 'bar', 'calendar','treemap','gauge','map']});
    google.charts.setOnLoadCallback(drawCharts);

    function errorHandler(errorMessage) {

      console.log(errorMessage);

      google.visualization.errors.removeError(errorMessage.id);
    }

    function drawCharts(){
     var anio=$("#selaniog").val();
     var mes=$("#selmesg").val();
     var dia='';
     if(($("#fechag").val()>31)||($("#fechag").val()<1)){
      dia;
      $("#fechag").val('');
    }else{
      dia=$("#fechag").val();
    };

    //drawVisualization(anio,mes,dia);
    PieChartimp(anio,mes,dia);
    PieChartcant(anio,mes,dia);
    drawChartP00(anio,mes,dia);
    drawChartP01(anio,mes,dia);   
    drawChartP02(anio,mes,dia);
    drawChartP03(anio,mes,dia);
    drawChartP05(anio,mes,dia);
    drawChartP06(anio,mes,dia);
    drawChartP09(anio,mes,dia);

  }
/*
  function drawVisualization(anio,mes,dia) {

    var variable='';
    var nommes=txt_mes[mes]
         variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
    var jsonData= $.ajax({
      url: 'graficofe_control/grafico_ventaxtipodocu',
      data: {'anio':anio,'action':'ajax','mes':mes,'dia':dia},
      dataType: 'json',
      async: false,
      beforeSend: function () {
        $('#loading_graf').show();
        $("#msg").text(''); 
        $('#btnmostrargrafico').button('disable');       
      },
    }).responseText;
    $('#loading_graf').hide();
    $('#btnmostrargrafico').button('enable'); 
    var obj = jQuery.parseJSON(jsonData);

    var datos = google.visualization.arrayToDataTable(obj);   
    var options = {
      title : 'Cantidad generado y enviados por documento Anulados '+variable,
      chart: {
        title: 'Comunicación',
        annotations: {
          alwaysOutside: true
        },
        width: 500,
        height: 600,
        legend: 'bottom',
        isStacked: 'percent'
      },

      vAxis: {title: 'Cantidad',
      minValue: 0,
      maxValue: 10,
      direction: 1,
    },
    hAxis: {title: 'Documento',
    maxTextLines: 10,
    textStyle: {
      fontSize: 10,
    }

  },
  seriesType: 'bars',
  series: {5: {type: 'line'}},
  colors: ['#F9674A','#DB6D57'],
      is3D:true
};

var chart = new google.visualization.ComboChart(document.getElementById('p1Chart'));
google.visualization.events.addListener(chart, 'error', errorHandler);
chart.draw(datos, options);
}
*/
function PieChartimp(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_cantidad_anulados_x_sucursal',
    data: {'anio':anio,'action':'ajax','mes':mes,'dia':dia},
    dataType: 'json',
    async: false
  }).responseText;  
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;

  var options = {
    legend: 'none',
    title: 'Cantidad Anulados por Sucursal '+variable, 
    hAxis: {
      title: 'Sucursal',
      minValue:0,
      slantedTextAngle: 70,
      textStyle: {
        fontSize: 8,
      }
    },
    vAxis: {
      title: 'Cantidad', 
      gridlines:{count: 7} 
    },
    colors: ['#F60D24']
  };
  var obj = jQuery.parseJSON(jsonData);
  var data = new google.visualization.arrayToDataTable(obj); 
  var chart = new google.visualization.ColumnChart(document.getElementById('p2Chart'));
  chart.draw(data, options);
}
function PieChartcant(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_documento_cantidad',
    data: {'anio':anio,'action':'ajax','mes':mes,'dia':dia},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
  var options = {
    legend: 'none',
    title: 'Cantidad Anulados por Documento '+variable, 
    hAxis: {
      title: 'Cantidad',
      minValue:0,
      slantedTextAngle: 70,
      textStyle: {
        fontSize: 8,
      }
    },
    vAxis: {
      title: 'Documento', 
      gridlines:{count: 7} 
    },

    colors: ['#F60D24']
  };
  var obj = jQuery.parseJSON(jsonData);
  var data = new google.visualization.arrayToDataTable(obj);
  var chart = new google.visualization.BarChart(document.getElementById('p3Chart')); //ColumnChart,BarChart,BarChart
  chart.draw(data, options);

}

function drawChartP06(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_cantidad_activo_x_tipodocu',
    data: {'anio':anio,'action':'ajax','mes':mes,'dia':dia},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
  var options = {
    legend: 'none',
    title: 'Cantidad Documentos Activos por tipo Documento '+variable, 
    hAxis: {
      title: 'Documento',
      minValue:0,
      slantedTextAngle: 70,
      textStyle: {
        fontSize: 8,
      }
    },
    vAxis: {
      title: 'Cantidad', 
      gridlines:{count: 7} 
    },

    colors: ['#06DB7D']
  };
  var obj = jQuery.parseJSON(jsonData);
  var data = new google.visualization.arrayToDataTable(obj);
  var chart = new google.visualization.ColumnChart(document.getElementById('p03Chart')); //ColumnChart,BarChart
  chart.draw(data, options);

}
function drawChartP00(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_total_documentos',
    data: {'anio':anio,'mes':mes,'dia':dia,'action':'ajax'},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
  var options = {
    legend: 'none',
    pieHole: 0.7,
    pieSliceText: 'label',
    title: 'Total Documentos Emitidos '+variable,
    pieStartAngle: 100,
    pieSliceTextStyle: {
      //color: 'black', 
      //fontSize: 18,
      color: "#000",
      fontName: "Arial",
      fontSize: 18,
      bold: true,
      italic: false
    },
    slices: {
      0: { color: '#0D98F6' },
      1: { color: 'transparent' }
    }            
  };
   //var obj = jQuery.parseJSON(jsonData);
    //data.addColumn('string', 'Indicador');
  //data.addColumn('number', 'Cantidad');
  var data = new google.visualization.DataTable(jsonData); 
  var chart = new google.visualization.PieChart(document.getElementById('p00Chart'));
  chart.draw(data, options);
}
function drawChartP01(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_total_documentos_activos',
    data: {'anio':anio,'mes':mes,'dia':dia,'action':'ajax'},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
  var options = {
    legend: 'none',
    pieHole: 0.7,
    pieSliceText: 'label',
    title: 'Total Documentos Activos '+variable,
    pieStartAngle: 100,
    pieSliceTextStyle: {
     color: "#000",
       //color: '#01579b',
       fontName: "Arial",
       fontSize: 18,
       bold: true,
       italic: false
     },
     slices: {
      0: { color: '#06DB7D' },
      1: { color: 'transparent' }
    }            
  };
  var data = new google.visualization.DataTable(jsonData); 
  var chart = new google.visualization.PieChart(document.getElementById('p01Chart'));
  chart.draw(data, options);
}
function drawChartP02(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_total_documentos_inactivos',
    data: {'anio':anio,'mes':mes,'dia':dia,'action':'ajax'},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
  var options = {
    legend: 'none',
    pieHole: 0.7,
    pieSliceText: 'label',
    title: 'Total Documentos Anulados '+variable,
    pieStartAngle: 100,
    pieSliceTextStyle: {
     color: "#000",
     fontName: "Arial",
     fontSize: 18,
     bold: true,
     italic: false
   },
   slices: {
    0: { color: '#F60D24' },
    1: { color: 'transparent' }
  }  

};
var data = new google.visualization.DataTable(jsonData); 
var chart = new google.visualization.PieChart(document.getElementById('p02Chart'));
chart.draw(data, options);
}

function drawChartP03(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_donus_documentos',
    data: {'anio':anio,'mes':mes,'dia':dia,'action':'ajax'},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;

  var options = {
    pieHole: 0.4,
    // legend: 'bottom',
     // legend: { position: 'top', alignment: 'start' },
     title: 'Total Documentos '+variable,     
     colors: ['#0D98F6','#06DB7D','#F60D24']
   };

  //var obj = jQuery.parseJSON(jsonData);
  var data = new google.visualization.DataTable(jsonData); 
  var chart = new google.visualization.PieChart(document.getElementById('p4Chart'));
  chart.draw(data, options);
}
function drawChartP05(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_total_documentos_activos_linea',
    data: {'anio':anio,'mes':mes,'dia':dia,'action':'ajax'},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
  var nombre='';
  if((mes=='')&&(dia=='')){
   nombre='Meses';
 }else if((mes!='')&&(dia=='')){
   nombre='dias';
 }else if((mes=='')&&(dia!='')){
   nombre='dias';
 }else{
  nombre='horas';
}

var data = new google.visualization.DataTable(jsonData); 

var options = {
 hAxis: {
  title: nombre
},
vAxis: {
  title: 'Cantidad'
},
title: 'Histograma de documentos activos y anulados '+variable,
colors: ['#F60D24','#06DB7D']
};

var chart = new google.visualization.LineChart(document.getElementById('p0hart'));
chart.draw(data, options);
}

function drawChartP09(anio,mes,dia) {
  var jsonData = $.ajax({
    url: 'graficofe_control/grafico_total_documentos_inactivos_vendedor',
    data: {'anio':anio,'mes':mes,'dia':dia,'action':'ajax'},
    dataType: "json",
    async: false
  }).responseText;
  var nommes=txt_mes[mes];
  variable=((mes=='')||(mes==null))?anio:dia+' '+nommes+' '+anio;
  var nombre='';
  if((mes=='')&&(dia=='')){
   nombre='Meses';
 }else if((mes!='')&&(dia=='')){
   nombre='dias';
 }else if((mes=='')&&(dia!='')){
   nombre='dias';
 }else{
  nombre='horas';
}

var data = new google.visualization.DataTable(jsonData); 
var options = {
  title : 'Documentos Anulados por Vendedor '+variable,
  vAxis: {title: 'Vendedores',
  minValue:0,
  slantedTextAngle: 20,
  textStyle: {
    fontSize: 8,
  }
},
hAxis: {title: 'Cantidad Anulado'},
seriesType: 'bars',
height: 520,
orientation: 'vertical',
 colors: ['#C0392B', '#E74C3C', '#D35400','#E67E22']
};

var chart = new google.visualization.ComboChart(document.getElementById('p5Chart'));
chart.draw(data, options);
}

$(function(){
  $('#loading_graf').hide();
  $("#btnmostrargrafico").click(function () {
    drawCharts();

  });
});

$(function () {
  $("#fechag").datepicker({
    dateFormat: "dd"     
  });
});
</script>

<script language="JavaScript" >


</script>

<div class="row">
  <div class="jumbotron">
    <div class='row'> 
      <div class='col-md-12'>
       <form class="form-inline" action="">
        <div class="form-group">
          <label for="selanio">AÑO</label>
          <select class="form-control" id="selaniog">
            <?php 
            $anio=date("Y");
            if(count($lstanio)>0){
              for ($i=0; $i < count($lstanio); $i++) { 
                $cad=$lstanio[$i];
                $anio=$cad['anio'];
                $selected=($anio==$anio)?'selected=""':'';
                ?>
                <option <?php echo $selected; ?> value="<?php echo $anio ?>"><?php echo $anio;?></option>
                <?php
              }
            }else{?>
              <option selected="" value="<?php echo date("Y");?>"><?php echo date("Y");?></option>
              <?php
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="selmes">MES</label>
          <select class="form-control" id="selmesg">
            <?php 
            $m=date("m");
            if(count($lstmes)>0){
              echo '<option value="">--Todo--</option>';
              for ($i=0; $i < count($lstmes); $i++) {
                $cad=$lstmes[$i];
                $mes=$cad['mes'];
                $dscmes=$cad['dscmes'];
                $selected=($m==$mes)?'selected=""':'';
                ?>
                <option <?php echo $selected; ?> value="<?php echo $mes ?>"><?php echo $dscmes;?></option>
                <?php
              }                
            }else{?>
              <option selected="" value="<?php echo date("m");?>"><?php echo mes_letra(date("m"));?></option>
              <?php
              echo '<option value="">--Ninguno--</option>';
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="selmes">Dia</label>
          <input type="text" name="fechag" id="fechag" class="form-control" value="" placeholder="dia">
        </div>
        <button type="button" class="btn btn-info" id="btnmostrargrafico" ><span class="glyphicon glyphicon-stats"></span>&nbsp;Mostrar</button>
        <div class="form-group gifCarga"><img id="loading_graf" src="assest/imagen/loading8.gif" style="display: none;"></div>
        <!--<button type="button" class="btn btn-primary" id="btnsendbajas" onclick="drawVisualization()"><span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;Enviar</button>-->
      </form>
    </div>  
  </div>
  <hr>
  <div class="dashboard-wrapper">
   <div id='pp0' class='panel'><h2>Indicador Documentos F.E.</h2> <div id='pp0Chart'></div> </div>
   <div id='p00' class='panel'><div id='p00Chart'></div> </div>
   <div id='p01' class='panel'><div id='p01Chart'></div> </div>
   <div id='p02' class='panel'><div id='p4Chart'></div> </div> 
   <div id='p03' class='panel'><div id='p03Chart'></div> </div>
   <div id='p1' class='panel'><div id='p0hart'></div> </div>
   <div id='p4' class='panel'><div id='p02Chart'></div> </div>
   <div id='p3' class='panel'><div id='p3Chart'></div> </div>
   <div id='p2' class='panel'><div id='p2Chart'></div> </div> 
   <div id='p5' class='panel'><div id='p5Chart'></div> </div>
 </div>
</div>
<div class="panel panel-default">
  <div class="panel-body">           

    <div class="row">
      <div class="col-md-12"> 

      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
       <p></p>
     </div>
   </div>

 </div>
</div>  
</div>
<style type="text/css">
  h2 {
    margin: 0px;
  }
  .dashboard-wrapper {
    margin: auto;
    max-width: 920px;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-column-gap: 15px;
    grid-row-gap: 15px;
  }
  .dashboard-wrapper .panel {
    text-align: left;
    background-color: #fff;
    border: 1px solid #d3d3d3;
    box-shadow: 0 0 4px 1px rgba(143,143,143,.2);
    padding: 15px;
  }
  .dashboard-wrapper #p0.panel,#pp0.panel,#pp1.panel, #p1.panel,#p5.panel, #p03.panel,.dashboard-wrapper #p8.panel {
    grid-column: 1 / 4;
  }
  .bottom {
    font-size: 10px;
    margin-top: 15px;
  }
  .bottom a {
    color: #333;
    text-decoration: none;
  }
  @media screen and (max-width: 1024px) {
    .dashboard-wrapper .panel {
      grid-column: 1 / 4;
    } 
  }
</style>
</section>