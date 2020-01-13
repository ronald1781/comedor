<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'general_control';  // general_control
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;;

/* Login */
$route['login'] = "login_control";
$route['valida/login'] = 'login_control/process';
$route['loginin'] = "login_control/do_logout";
$route['inicio'] = 'login_control/home_principal';
$route['principal_control'] = 'login_control/home_principal';

//
$route['principal'] = 'general_control';

//Pedirmenu
$route['pedir'] = 'comedor_control/pedirmenu';

//Consumirmenu
$route['comsumir'] = 'comedor_control/consumirmenu';

//Personas
$route['persona'] = 'personal_control/personasmenu';
$route['grabarpersona'] = 'personal_control/personasave';
//Personas
$route['personal'] = 'personal_control/personal';
//Menu
$route['crear/menu'] = 'comedor_control/crear_menus';

$route['febaja'] = 'facturacionbaja_control/vista_bajas';
$route['fesendbaja/(.*)'] = 'facturacionbaja_control/enviobaja_documentos/$1';
$route['feprocesa'] = 'facturacionsendws_control/vista_fesendws';
$route['feprocesarfe'] = 'Procesar_facturas_control/procesar_facturas_nuevas';
$route['testprint/(.*)'] = 'Imprimir_factura_control/print_automaticofe/$1';
$route['printfe/(.*)'] = 'facturacionsendws_control/mostrar_pdf/$1';
$route['printanufe/(.*)'] = 'facturacionsendbajaws_baja_control/mostraranulado_pdf/$1';
$route['printfedemo/(.*)'] = 'Imprimir_factura_control/print_automaticofe';

