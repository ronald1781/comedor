<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Personal_control extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('personal_model', '', true);
    $this->load->library("nusoap_lib");
  }

  function index($msg = NULL) { 
  $valid = $this->session->userdata('validated');
      if ($valid == TRUE) {  
    $datos['msg'] = $msg;
    $datos['titulo'] = 'Comedor';
    $datos['contenido'] = 'general_view';    
    $this->load->view('includes/plantilla', $datos);
    }else{ 
redirect('principal');
}
  }

  function personasmenu() {   
   
$listasucu = $this->personal_model->get_sucursal();
$datos['sucursal'] = $listasucu;
    $datos['titulo'] = 'Persona Menu';
    $datos['contenido'] = 'persona_view';    
    $this->load->view('includes/plantilla', $datos); 
  
  }

  function personal(){
    $valid = $this->session->userdata('validated');
      if ($valid == TRUE) {
    $datos['titulo'] = 'Personal Menu';
    $datos['contenido'] = 'personal_view';    
    $this->load->view('includes/plantilla', $datos);
}else{ 
redirect('principal');
}
  }

//codper,dniper,txtnomper,txtapepper,txtapemper,emailper
//"codper","dniper","nomper","apepper","apmper","usucrper","emailper","fcrper","usumdper","fmdper","estrgper"
  public function ajax_list_per() {
    $list = $this->personal_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $person) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $person->dniper;
      $row[] = ucfirst(strtolower($person->nomper));
      $row[] = ucfirst(strtolower($person->apepper));
      $row[] = ucfirst(strtolower($person->apmper));
      $row[] = strtolower($person->emailper);
      $row[] = strtolower($person->nomsuc);
      $estado=($person->estrgper=='A')?'<span class="label label-success">Activo</span>':'<span class="label label-danger">Inactivo</span>';
      $row[] = $estado;
            //add html for action  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_person('."'".$person->coderr."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
      $row[] = '<a href="javascript:void()" title="Editar" onclick="edit_per(' . "'" . $person->codper . "'" . ')"><span class="label label-info"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
      <a href="javascript:void()" title="Anular" onclick="delete_per(' . "'" . $person->codper . "'" . ')"><span class="label label-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->personal_model->count_all(),
      "recordsFiltered" => $this->personal_model->count_filtered(),
      "data" => $data,
    );
        //output to json format
    echo json_encode($output);
  }

  public function ajax_edit_per($id) {
    $data = $this->personal_model->get_by_id($id);
    echo json_encode($data);
  }

  public function ajax_add_per() {
   $nomacc= $this->input->post('txtnomper');
   $data = array(
    'dniper'=>$this->input->post('dniper'),
    'nomper'=>$this->input->post('txtnomper'),
    'apepper'=>$this->input->post('txtapepper'),
    'apmper'=>$this->input->post('txtapemper'),            
    'emailper'=>$this->input->post('emailper'),
    'sucper'=>$this->input->post('sucuper'),
    'usucrper' => $this->session->userdata('codiper'),
  );
   $insert = $this->personal_model->save($data);
   if ($insert > 0) {
    $dato = 'Correctamente,  Usuario : ' . $nomacc;
  } else {
    $dato = 'Fallo!!!';
  }
  echo json_encode(array("status" => $dato));
}

public function ajax_update_per() {
 $datmd= $this->input->post('nomsuc');
 $data = array(
   'dniper'=>$this->input->post('dniper'),
   'nomper'=>$this->input->post('txtnomper'),
   'apepper'=>$this->input->post('txtapepper'),
   'apmper'=>$this->input->post('txtapemper'),
   'emailper'=>$this->input->post('emailper'),
   'sucper'=>$this->input->post('sucuper'),
   'usumdper' => $this->session->userdata('codiper'),
   'fmdper' => gmdate("Y-m-d H:i:s", time() - 18000),
 );
 $result = $this->personal_model->update(array('codper' => $this->input->post('codper')), $data);
 if ($result >0) {
  $dato = 'Correctamente '.$datmd.' '.$result.' Fila (s) Actualizado (s)';
} else {
  $dato = 'Fallo!!! '.$result;
}
echo json_encode(array("status" => $dato));
}

public function ajax_delete_per($id) {
  $result=  $this->personal_model->delete_by_id($id);
  if ($result>0) {
    $dato = 'Correctamente '.$result.' Fila (s) Anulado (s)';
  } else {
    $dato = 'Fallo!!!';
  }
  echo json_encode(array("status" => $dato));
}

function buscardniper(){
  $datoper = $this->input->get('datoper');
  $datoper = strtoupper($this->security->xss_clean($datoper));
  $rslper=$this->personal_model->get_by_dni($datoper);
  $lispb = array();
  if($rslper!=null){
    foreach ($rslper as $value) {
      $lispb[] = array('codper'=>$value->codper,'dniper'=>$value->dniper,'nomper'=>strtoupper($value->nomper),'apepper'=>strtoupper($value->apepper),'apmper'=>strtoupper($value->apmper),'msg'=>1,'men'=>'');
    }
    $result['datos'] = $lispb;
    $result['existe'] = 1;
  }else{
    $result['existe'] = 0;
    $lispb[] = array('men'=>'el dato buscado no esta registrado, haga clic para proceder a registrarse!!!','msg'=>0);
    $result['datos'] = $lispb;
  }
  echo json_encode($result);
}
function personasave(){
  $data = array(
    'dniper'=>$this->input->post('dniper'),
    'nomper'=>$this->input->post('txtnomper'),
    'apepper'=>$this->input->post('txtapepper'),
    'apmper'=>$this->input->post('txtapemper'),            
    'emailper'=>$this->input->post('emailper'),
    'sucper'=>$this->input->post('sucuper'),
    'usucrper' => 1,
  );
   $insert = $this->personal_model->save($data);
   if ($insert > 0) {   
    $msg='<span class="label label-success">Se registro correctamente '.$this->input->post('dniper').'</span>';
   $this->session->set_flashdata("mensajeper", $msg);   
    redirect('pedir');
  } else {
    $msg='<span class="label label-danger">Se presento error</span>';
   $this->session->set_flashdata("mensajeper", $msg);   
    redirect('persona');
  }
}

function get_sucursal(){
  $listasucu = $this->personal_model->get_sucursales();
$json = array();
        $dato = array();        
        $num = count($listasucu);
        if ($num > 0) {            
              foreach ($listasucu as $cad) {
                $codsuc = $cad['codsuc'];
                $nomsuc = $cad['nomsuc'];
                $dato[] = $codsuc . '#$#' . $nomsuc;
            }
            $json['lista'] = implode("&&&", $dato);
        } else {
            $json['lista'] = 0;
        }

        echo json_encode($json);
}



}
?>