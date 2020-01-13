<!DOCTYPE html  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
    <?php date_default_timezone_set('America/Lima'); ?>
    <base href="<?php echo base_url(); ?>">
    <title><?php echo $titulo ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="assest/imagen/menu.ico" />
    <!--<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"></link>-->

    <link  rel="stylesheet" type="text/css" href="assest/css/bootstrap.css"></link> 
    <link  rel="stylesheet" type="text/css" href="assest/css/jquery-ui.min.css"></link> 
    <link  rel="stylesheet" type="text/css" href="assest/css/chosen.min.css"></link> 
    <link  rel="stylesheet" type="text/css" href="assest/css/bootstrap-multiselect.css"></link>
    <link  rel="stylesheet" type="text/css" href="assest/css/rrgstilos.css"></link>
    <link  rel="stylesheet" type="text/css" href="assest/css/jquery.rateyo.css"></link> 

    <script src="assest/js/bootstrap.js"></script> 
    <script src="assest/js/jquery.min.js"></script> 
    <script src="assest/js/alertify.js"></script>  
    <script src="assest/js/jquery-ui.min.js"></script> 
    <script src="assest/js/bootstrap.min.js"></script> 
    <script src="assest/js/jquery.validate.js"></script> 

    <script src="assest/js/chosen.jquery.min.js"></script>
    <script src="assest/js/chosen.proto.min.js"></script>
    <script src="assest/js/bootstrap-filestyle.min.js"></script>                
    <script src="assest/js/jquery.dataTables.min.js"></script> 
    <script src="assest/js/dataTables.bootstrap.js"></script>
    <script src="assest/js/ajaxfileupload.js"></script>
    <script src="assest/js/jquery.ajax-progress.min.js"></script>
    <script src="assest/js/bootstrap-multiselect.js"></script>
    <script src="assest/js/generales_js.js"></script>     
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="assest/js/push.js"></script> 
    <script src="assest/js/sw.js"></script>
 <script src="assest/js/jquery.rateyo.js"></script>
    <script>
      $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
</script>
<style>
    .dropdown-submenu{position:relative;}
    .dropdown-submenu>.dropdown-menu{top:0;left:100%;margin-top:-6px;margin-left:-1px;-webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;}
    .dropdown-submenu:hover>.dropdown-menu{display:block;}
    .dropdown-submenu>a:after{display:block;content:" ";float:right;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 0 5px 5px;border-left-color:#cccccc;margin-top:5px;margin-right:-10px;}
    .dropdown-submenu:hover>a:after{border-left-color:#ffffff;}
    .dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px;}
    .modal-header,  .close {
        background-color: #1E90FF;
        color:white !important;
        text-align: center;
        font-size: 30px;
    }
    .modal-footer {
        background-color: #f9f9f9;
    }
</style>
</head>
<body> 
    <?PHP 
    $valid = $this->session->userdata('validated');
    if ($valid == true) {
        $codper = $this->session->userdata('codper');
        $usuaper = strtoupper($this->session->userdata('usuaper'));
        $nomuser = strtoupper($this->session->userdata('nomperusr'));
    }else{
        $codper = '';
        $usuaper = '';
        $nomuser = '';
    }    
    ?>
    <nav>
        <div class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
            </div>
            <a class="navbar-brand"><img src="./assest/imagen/10.png" style="height: 30px; margin-top: -5px;" ></img></a>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    <?PHP 
                    $valid = $this->session->userdata('validated');
                     $perfil = $this->session->userdata('perfil');
     if (($valid == true)&&($perfil==0)) { ?>
                        
                        <li><a href="inicio" class="dropdown-toggle" data-toggle="dropdown">Administracion<b class="caret"></b></a>
                            <ul class="dropdown-menu">                        
                                <li><a href="<?php echo base_url('login_control/usuarios') ?>">Usuario</a></li>
                                <li><a href="<?php echo base_url('personal_control/personal') ?>">Personal</a></li>
                                
                            </ul>
                        </li>
    <?PHP
                    }else if(($valid == true)&&($perfil==1)||($perfil==2)){
                        ?>
<li><a href="inicio" class="dropdown-toggle" data-toggle="dropdown">Cocina<b class="caret"></b></a>
                            <ul class="dropdown-menu">                        
                                <li><a href="<?php echo base_url('comedor_control/platos') ?>">Plato</a></li> 
                                <li><a href="<?php echo base_url('crear/menu') ?>">Crear Menus</a></li>
                            </ul>
                        </li>
                        <?PHP
                    }else{
                        ?>
                        <li><a href="inicio" class="dropdown-toggle" data-toggle="dropdown">Comedor<b class="caret"></b></a>
                            <ul class="dropdown-menu">                        
                                <li><a href="<?php echo base_url('pedir') ?>">Pedir</a></li> 
                                <li><a href="<?php echo base_url('comsumir') ?>">Consumir</a></li>
                            </ul>
                        </li>
                        <?PHP
                    }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                 <?PHP 
                 $valid = $this->session->userdata('validated');
                 if ($valid == true) {
                    ?>
                    <li><a title="Usuario Sucursal"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>  <?php echo $usuaper; ?>
                </a>                       
            </li>  
            <li>
            </li>
            <li><a href="<?php echo base_url('login_control/loginof') ?>" title="Salir"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a>
            </li>                 
            <?PHP
        }else{
            ?>
            <li>
            </li> 
            <?php
        }
        ?>
    </ul>
</div>
</div>
</nav>
<section class="container">


