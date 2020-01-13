<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_control extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('login_model', '', TRUE);
    }

    function index($msg = NULL) {   
$datos['msg'] = $msg;
    $datos['titulo'] = 'Comedor';
    $datos['contenido'] = 'general_view';    
    $this->load->view('includes/plantilla', $datos);  
}

    public function process() {        
        $emailusua = $this->security->xss_clean($this->input->post('emailuser'));
        $password = md5($this->security->xss_clean($this->input->post('passuser'))); 
        $result = $this->login_model->validaLoguin($emailusua, $password);
        $valid = $this->session->userdata('validated');
      if ($valid == TRUE) {
        redirect('principal');
        } else {
             $msg = '<font color=red>Usuario y/o Password Incorrectos.</font><br />' . $result;
            $this->index($msg);
        }
        
}


function home_principal() {    
 redirect('principal');
}

public function loginof() {
    $this->session->sess_destroy();
    redirect('principal');
}

public function sin_datos() {
    $username = $this->session->userdata('usuaper');      
    $datos['titulo'] = 'Error';
    $datos['contenido'] = 'error_sin_datos_view';
    $this->load->view('/includes/plantillaerror', $datos);
}
public function usuarios() {
      $valid = $this->session->userdata('validated');
      if ($valid == TRUE) {
        $datos['titulo'] = 'Usuario';
    $datos['contenido'] = 'usuario_view';    
    $this->load->view('includes/plantilla', $datos); 
        } else {
             $msg = '<font color=red>Usuario y/o Password Incorrectos.</font><br />' . $result;
            $this->index($msg);
        }
    
}


    public function ajax_list_user() {
        $list = $this->login_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst(strtolower($person->usuausu));
            $row[] = ucfirst(strtolower($person->emailusu));
            $perfil=$person->prfusu;
            switch ($perfil) {
                case '0':
                    $perfil='Admin_sistema';
                    break;
                 case '1':
                    $perfil='Administrador';
                    break;
                    case '2':
                    $perfil='Chef';
                    break;
                default:
                    $perfil='';
                    break;
            }
            $row[] = ucfirst(strtolower($perfil));
$estado=($person->estrgusu=='A')?'<span class="label label-success">Activo</span>':'<span class="label label-danger">Inactivo</span>';
            $row[] = $estado;
            //add html for action  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_person('."'".$person->coderr."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
            $row[] = '<a href="javascript:void()" title="Editar" onclick="edit_user(' . "'" . $person->codusu . "'" . ')"><span class="label label-info"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
                    <a href="javascript:void()" title="Anular" onclick="delete_user(' . "'" . $person->codusu . "'" . ')"><span class="label label-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->login_model->count_all(),
            "recordsFiltered" => $this->login_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit_user($id) {
        $data = $this->login_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add_user() {
       $nomacc= $this->input->post('nomuser');
        $data = array(
            //nomuser,emailuser,passuser,prfusr
            'emailusu'=>$this->input->post('emailuser'),
            'usuausu'=>$this->input->post('nomuser'),
            'prfusu'=>$this->input->post('prfusr'),
'passusu'=>md5($this->input->post('passuser')),
            'usucrusu' => $this->session->userdata('codiper'),
        );
        $insert = $this->login_model->save($data);
         if ($insert > 0) {
            $dato = 'Correctamente,  Usuario : ' . $nomacc;
        } else {
            $dato = 'Fallo!!!';
        }
        echo json_encode(array("status" => $dato));
    }

    public function ajax_update_user() {
        
       $passexite=(empty($this->input->post('passuser')))?0:1;
       $datmd= $this->input->post('nomsuc');
       if($passexite==1){
        $data = array(
             'emailusu'=>$this->input->post('emailuser'),
            'usuausu'=>$this->input->post('nomuser'),
            'prfusu'=>$this->input->post('prfusr'),
            'passusu'=>md5($this->input->post('passuser')),
            'usumdusu' => $this->session->userdata('codiper'),
            'fmdusu' => gmdate("Y-m-d H:i:s", time() - 18000),
        );
    }else{
       $data = array(
             'emailusu'=>$this->input->post('emailuser'),
            'usuausu'=>$this->input->post('nomuser'),
            'prfusu'=>$this->input->post('prfusr'),
            'usumdusu' => $this->session->userdata('codiper'),
            'fmdusu' => gmdate("Y-m-d H:i:s", time() - 18000),
        );  
    }
        $result = $this->login_model->update(array('codusu' => $this->input->post('coduser')), $data);
        if ($result >0) {
            $dato = 'Correctamente '.$datmd.' '.$result.' Fila (s) Actualizado (s)';
        } else {
            $dato = 'Fallo!!! '.$result;
        }
        echo json_encode(array("status" => $dato));
    }

    public function ajax_delete_user($id) {
      $result=  $this->login_model->delete_by_id($id);
          if ($result>0) {
            $dato = 'Correctamente '.$result.' Fila (s) Anulado (s)';
        } else {
            $dato = 'Fallo!!!';
        }
        echo json_encode(array("status" => $dato));
    }


}

?>