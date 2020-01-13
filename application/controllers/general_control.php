<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class General_control extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('general_model', '', true);
    $this->load->model('graficofe_model', '', true);
    $this->load->library("nusoap_lib");
  }

 function index($msg = NULL) {   
$datos['msg'] = $msg;
    $datos['titulo'] = 'Comedor';
    $datos['contenido'] = 'general_view';    
    $this->load->view('includes/plantilla', $datos);  
}




}
?>