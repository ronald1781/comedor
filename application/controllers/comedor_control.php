<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Comedor_control extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('comedor_model', '', true);
  }

  function index() {  

  }
  function pedirmenu() { 
    $datos['titulo'] = 'Pedir Menu';
    $datos['contenido'] = 'pedirmenu_view';    
    $this->load->view('includes/plantilla', $datos);  
  }
  
  function consumirmenu() {  
   $datos['titulo'] = 'Consumir Menu';
   $datos['contenido'] = 'consumirmenu_view';    
   $this->load->view('includes/plantilla', $datos);  
 }

 function platos() { 
  $valid = $this->session->userdata('validated');
  if ($valid == TRUE) {
    $datos['titulo'] = 'Platos';
    $datos['contenido'] = 'platos_view';    
    $this->load->view('includes/plantilla', $datos);
  }else{ 
    redirect('principal');
  }  
}

function get_tipoalimentos(){
  $listatali = $this->comedor_model->get_tipoalimentos();
  $json = array();
  $dato = array();        
  $num = count($listatali);
  if ($num > 0) {            
              foreach ($listatali as $cad) {//,
                $codtali = $cad['codtali'];
                $nomali = $cad['nomali'];
                $dato[] = $codtali . '#$#' . $nomali;
              }
              $json['lista'] = implode("&&&", $dato);
            } else {
              $json['lista'] = 0;
            }
            echo json_encode($json);
          }

//codplto,tipopto,nomplto,imgplto,descplto,usucrplto,fcrplto,usumdplto,fmdplto,fdelplto,estrgplto

          public function ajax_list_pto() {
            $list = $this->comedor_model->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $person) {
              $no++;
              $row = array();
              $row[] = $no;
              $row[] = ucfirst(strtolower($person->nomali));
              $row[] = ucfirst(strtolower($person->nomplto));
              $row[] = ucfirst(strtolower($person->descplto));
              $img=($person->imgplto=='')?'':'<img class="media-object img-thumbnail" src="assest/imagenplatos/'.strtolower($person->imgplto).'" alt="plato1" width="60" height="30">';
              $row[] = $img;
              $estado=($person->estrgplto=='A')?'<span class="label label-success">Activo</span>':'<span class="label label-danger">Inactivo</span>';
              $row[] = $estado;
            //add html for action  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_person('."'".$person->coderr."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
              $row[] = '<a href="javascript:void()" title="Editar" onclick="edit_pto(' . "'" . $person->codplto . "'" . ')"><span class="label label-info"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
              <a href="javascript:void()" title="Anular" onclick="delete_pto(' . "'" . $person->codplto . "'" . ')"><span class="label label-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
              $data[] = $row;
            }
            $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->comedor_model->count_all(),
              "recordsFiltered" => $this->comedor_model->count_filtered(),
              "data" => $data,
            );

            echo json_encode($output);
          }

          public function ajax_edit_pto($id) {
            $data = $this->comedor_model->get_by_id($id);
            echo json_encode($data);
          }

          public function ajax_add_pto() {
    //codpto,tippto,txtpto,txtdscpto,imagpto
//codplto,tipopto,nomplto,imgplto,descplto,usucrplto,fcrplto,usumdplto,fmdplto,fdelplto,estrgplto

            $imgd='';
            $imge='';
            if (!empty($_FILES['imagpto']['name'])){
              $config['upload_path'] = 'assest/imagenplatos';
              $config['allowed_types'] = '*';
              $config["max_size"] = "0";
              $config["max_width"] = "0";
              $config["max_height"] = "0";
              $config["remove_spaces"] = TRUE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if ($this->upload->do_upload('imagpto')){
               $upload_data = $this->upload->data();
               $imgd = $upload_data['file_name'];
             }else{
               $imge= $this->upload->display_errors();
             }
           }else{
            $imgd='';
          }
          $data = array(
            'tipopto'=>$this->input->post('tippto'),
            'nomplto'=>$this->input->post('txtpto'),
            'descplto'=>$this->input->post('txtdscpto'),
            'imgplto'=>$imgd, 
            'usucrplto' => $this->session->userdata('codiper'),
          );

          $insert = $this->comedor_model->save($data);
          if ($insert > 0) {
            $dato = 'Correctamente,  Usuario : ' .$this->input->post('txtpto');
          } else {
            $dato = 'Fallo!!!';
          }
          echo json_encode(array("status" => $dato));
        }

        public function ajax_update_pto() {
         $imgd='';
         $imge='';
         $codpto= $this->input->post('codpto');
         if (!empty($_FILES['imagpto']['name'])){
          $fileold = $this->comedor_model->get_fileold($codpto);
          $source = 'assest/imagenplatos/'.$fileold->imgplto;
          @unlink($source);
          $config['upload_path'] = 'assest/imagenplatos';
          $config['allowed_types'] = '*';
          $config["max_size"] = "0";
          $config["max_width"] = "0";
          $config["max_height"] = "0";
          $config["remove_spaces"] = TRUE;
          $this->load->library('upload', $config);
          $this->upload->initialize($config);
          if ($this->upload->do_upload('imagpto')){
           $upload_data = $this->upload->data();
           $imgd = $upload_data['file_name'];
         }else{
           $imge= $this->upload->display_errors();
         }
       }else{
        $imgd='';
      }
      $data='';
      if($imgd==''){
       $data = array(
         'tipopto'=>$this->input->post('tippto'),
         'nomplto'=>$this->input->post('txtpto'),
         'descplto'=>$this->input->post('txtdscpto'),
         'usumdplto' => $this->session->userdata('codiper'),
         'fmdplto' => gmdate("Y-m-d H:i:s", time() - 18000),
       );
     }else{
       $data = array(
         'tipopto'=>$this->input->post('tippto'),
         'nomplto'=>$this->input->post('txtpto'),
         'descplto'=>$this->input->post('txtdscpto'),
         'imgplto'=>$imgd,
         'usumdplto' => $this->session->userdata('codiper'),
         'fmdplto' => gmdate("Y-m-d H:i:s", time() - 18000),
       );
     }     
     $result = $this->comedor_model->update(array('codplto' =>$codpto), $data);
     if ($result >0) {
      $dato = 'Correctamente '.$this->input->post('txtpto').' '.$result.' Fila (s) Actualizado (s)';
    } else {
      $dato = 'Fallo!!! '.$result;
    }
    echo json_encode(array("status" => $dato));
  }

  public function ajax_delete_pto($id) {   
    $fileold = $this->comedor_model->get_fileold($id);
    $source = 'assest/imagenplatos/'.$fileold->imgplto;
    @unlink($source);
    $result=  $this->comedor_model->delete_by_id($id);
    if ($result>0) {
      $dato = 'Correctamente '.$result.' Fila (s) Anulado (s)';
    } else {
      $dato = 'Fallo!!!';
    }
    echo json_encode(array("status" => $dato));
  }
  function crear_menus() {  

    $datos['titulo'] = 'Crear Menus';
    $datos['contenido'] = 'crearmenus_view';    
    $this->load->view('includes/plantilla', $datos);  
  }
  public function ajax_list_pto_mnuh() {
    $list = $this->comedor_model->get_datatables_hm();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $person) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = ucfirst(strtolower($person->nommnus));
      $row[] = ucfirst(strtolower($person->ffnpdmnu));
      $row[] = ucfirst(strtolower($person->cntpltmnu));
      $estado=($person->estmenu=='G')?'<span class="label label-warning">Generado</span>':'<span class="label label-success">Aprobado</span>';
      $row[] = $estado;
      $row[] = '<a href="javascript:void()" title="Editar" onclick="edit_mnuh(' . "'" . $person->codmnus . "'" . ')"><span class="label label-info"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
      <a href="javascript:void()" title="Anular" onclick="delete_mnuh(' . "'" . $person->codmnus . "'" . ')"><span class="label label-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->comedor_model->count_all_hm(),
      "recordsFiltered" => $this->comedor_model->count_filtered_hm(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  function ajax_add_mhnu(){
    $lispb = array();
    $data = array(
      'nommnus'=>$this->input->post('txtnomm'),
      'cntpltmnu'=>$this->input->post('ncantop'),
      'fdsdmnu'=>$this->input->post('fdsd'),
      'fhstmnu'=>$this->input->post('fhst'),      
      'ffnpdmnu'=>$this->input->post('ffnpd'),
      'usucrmnus' => $this->session->userdata('codiper'),
    );
    $insert = $this->comedor_model->save_hmnu($data);
    if ($insert > 0) {
      $datahmnu = $this->comedor_model->get_by_id_hm($insert);
      $result['dato'] = $datahmnu;
      $result['existe'] = 1;
    }else{
      $result['existe'] = 0;
      $lispb[] = array('men'=>'el dato buscado no esta registrado, haga clic para proceder a registrarse!!!','msg'=>0);
      $result['dato'] =$lispb;
    }
    echo json_encode($result);
  } 

  public function ajax_edit_mnuh($id) {

    $codhmnus = strtoupper($this->security->xss_clean($id));

    $html='';
    if ($codhmnus > 0) {
      $datahmnu = $this->comedor_model->get_by_id_hm($codhmnus);
      $lista_fecha_menu = $this->comedor_model->get_lista_fecha_menu($codhmnus);            
      $listamenu=array();
      $platosm=array();
      if($lista_fecha_menu){
        foreach ($lista_fecha_menu as $fecha_menu) {    
          $fechamenu=$fecha_menu['diasemenu'].' '.$fecha_menu['diamenu'].' de '.$fecha_menu['mesmenu'];
          $html.="<tr><td><strong>".$fechamenu.'</strong></td>';
          $fecha=$fecha_menu['fprepmnu'];
          $codmenu=$fecha_menu['codmenu'];
          $lista_platos_menu = $this->comedor_model->get_lista_platos_menu($codmenu,$fecha);    
          if($lista_platos_menu){
            $conta=0;
            foreach ($lista_platos_menu as $platos_menu) {
              $conta=$conta+1;
              $platosm[]=array('nomplto'=>$platos_menu['nomplto'],'codmndet'=>$platos_menu['codmndet']); 
              $html.='<td>'.$platos_menu['nomplto'].'</td>';        
            }}
            $param=  $codmenu . ", '" . $fecha . "'" ;
            $html.='<td><a href="javascript:void()" title="Anular" onclick="delete_pltos_mnu(' .  "'" . $codmenu . "','" . $fecha . "'"  . ')"><span class="label label-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span></a></td></tr>';
          }     
        }   
        $result['dato1'] =$datahmnu;
        $result['dato2']=$html;
        $result['existe'] = 1;
      }else{
        $result['existe'] = 0;
        $lispb[] = array('men'=>'el dato buscado no esta registrado, haga clic para proceder a registrarse!!!','msg'=>0);
        $result['dato'] =$lispb;
      }
      echo json_encode($result);
    }
    public function ajax_delete_mnuh($id) {   

      $result=  $this->comedor_model->delete_by_id_hm($id);
      if ($result>0) {
        $dato = 'Correctamente '.$result.' Fila (s) Anulado (s)';
      } else {
        $dato = 'Fallo!!!';
      }
      echo json_encode(array("status" => $dato));
    }

    function get_platos_menu(){

      $tipopto=$this->input->post('tipopto');
      $codmenu=$this->input->post('codmenu');
      $listapltos = $this->comedor_model->get_platos_menu($tipopto,$codmenu);
      $json = array();
      $dato = array();        
      $num = count($listapltos);
      if ($num > 0) {            
              foreach ($listapltos as $cad) {//,
                $codplto = $cad['codplto'];
                $nomplto = $cad['nomplto'];
                $dato[] = $codplto . '#$#' . $nomplto;
              }
              $json['lista'] = implode("&&&", $dato);
            } else {
              $json['lista'] = 0;
            }
            echo json_encode($json);
          }
          public function ajax_add_pltos_mnu() {
    //codpto,tippto,txtpto,txtdscpto,imagpto
//codplto,tipopto,nomplto,imgplto,descplto,usucrplto,fcrplto,usumdplto,fmdplto,fdelplto,estrgplto
           $selecpltos=$this->input->post('selecpltos');          
           $canti=count($selecpltos);
           $cantins=0;
           $platos='';
           $insert=0;
           $html='';
           if($canti>0){
            for($i=0;$i<$canti;$i++) { 
              $cad=$selecpltos[$i];
              $platos.=' '.$cad['codplto'];  
              $codplto= $cad['codplto'];  
              $data = array(
                'codmenu'=>$this->input->post('codhmnus'),
                'fprepmnu'=>$this->input->post('fpltoxpr'),
                'codtippto'=>$this->input->post('tippto'),
                'codplto'=>$codplto,
                'impmndet '=>0.00,
                'monemndet'=>0, 
                'usucrmndet' => $this->session->userdata('codiper'),
              );
              $insert = $this->comedor_model->save_bmnu($data);
              $cantins=$cantins+$insert;
            }
          }else{
            $cantins=0;
          }
          if ($insert > 0) {
            $lista_fecha_menu = $this->comedor_model->get_lista_fecha_menu($this->input->post('codhmnus'));            
            $listamenu=array();
            $platosm=array();
            if($lista_fecha_menu){
              foreach ($lista_fecha_menu as $fecha_menu) {    
                $fechamenu=$fecha_menu['diasemenu'].' '.$fecha_menu['diamenu'].' de '.$fecha_menu['mesmenu'];
                $html.="<tr><td><strong>".$fechamenu.'</strong></td>';
                $fecha=$fecha_menu['fprepmnu'];
                $codmenu=$fecha_menu['codmenu'];
                $lista_platos_menu = $this->comedor_model->get_lista_platos_menu($codmenu,$fecha);    
                if($lista_platos_menu){
                  $conta=0;
                  foreach ($lista_platos_menu as $platos_menu) {
                    $conta=$conta+1;
                    $platosm[]=array('nomplto'=>$platos_menu['nomplto'],'codmndet'=>$platos_menu['codmndet']); 
                    $html.='<td>'.$platos_menu['nomplto'].'</td>';        
                  }}
                  $param=  $codmenu . ", '" . $fecha . "'" ;
                  $html.='<td><a href="javascript:void()" title="Anular" onclick="delete_pltos_mnu(' . "'" . $codmenu . "','" . $fecha . "'" . ')"><span class="label label-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span></a></td></tr>';
                }     
              }   
              $result['dato'] = $html;
              $result['existe'] = 1;
            }else{
              $result['existe'] = 0;
              $lispb[] = array('men'=>'el dato buscado no esta registrado, haga clic para proceder a registrarse!!!','msg'=>0);
              $result['dato'] =$lispb;
            }
            echo json_encode($result);
          }

          public function delete_pltos_mnu() {   
           $html='';
           $id = strtoupper($this->security->xss_clean($this->input->post('id')));
           $fecha = strtoupper($this->security->xss_clean($this->input->post('fecha')));
           $resulta=  $this->comedor_model->delete_by_id_bm($id,$fecha);
           if ($resulta>0) {
            $lista_fecha_menu = $this->comedor_model->get_lista_fecha_menu($id);            
            $listamenu=array();
            $platosm=array();
            if($lista_fecha_menu){
              foreach ($lista_fecha_menu as $fecha_menu) {    
                $fechamenu=$fecha_menu['diasemenu'].' '.$fecha_menu['diamenu'].' de '.$fecha_menu['mesmenu'];
                $html.="<tr><td><strong>".$fechamenu.'</strong></td>';
                $fecha=$fecha_menu['fprepmnu'];
                $codmenu=$fecha_menu['codmenu'];
                $lista_platos_menu = $this->comedor_model->get_lista_platos_menu($codmenu,$fecha);    
                if($lista_platos_menu){
                  $conta=0;
                  foreach ($lista_platos_menu as $platos_menu) {
                    $conta=$conta+1;
                    $platosm[]=array('nomplto'=>$platos_menu['nomplto'],'codmndet'=>$platos_menu['codmndet']); 
                    $html.='<td>'.$platos_menu['nomplto'].'</td>';        
                  }}
                  $html.='<td><a href="javascript:void()" title="Anular" onclick="delete_pltos_mnu(' . "'" . $codmenu . "','" . $fecha . "'" . ')"><span class="label label-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></td></tr>';
                }     
              }      
              $result['dato'] = $html;
              $result['existe'] = 1;
            }else{
              $result['existe'] = 0;
              $lispb[] = array('men'=>'el dato buscado no esta registrado, haga clic para proceder a registrarse!!!','msg'=>0);
              $result['dato'] =$lispb;
            }
            echo json_encode($result);
          }

          public function ajax_visuali_mnuxp($idper) {
           $per=$idper; $resultado='';
           $resultado = $this->comedor_model->get_by_id_mxp();            
           if(!$resultado){
            $resmnu = $this->comedor_model->get_by_id_mxpmaximo();
          }else{
            $resmnu=$resultado;
          }

          $html='';
          $radios='';
          $fechaultiped='';
          if ($resmnu) {
           $codhmnus= $resmnu->codmnus;
           $fechaultiped= $resmnu->ffnpdmnu;
           $rslpm=$this->comedor_model->get_by_id_pmhvalid($codhmnus,$per);
           $existeped=($rslpm!=null)?$rslpm->codped:0;
           $existepeddsc=($rslpm!=null)?$rslpm->comen:'';
           $datahmnu = $this->comedor_model->get_by_id_hm($codhmnus);
           $lista_fecha_menu = $this->comedor_model->get_lista_fecha_menu($codhmnus);
           if($lista_fecha_menu){
            $conta=0;
            foreach ($lista_fecha_menu as $fecha_menu) {  
              $conta=$conta+1;  
              $fechamenu=$fecha_menu['diasemenu'].' '.$fecha_menu['diamenu'].' de '.$fecha_menu['mesmenu'];
              $html.="<tr id=".$conta."><td><strong>".$fechamenu.'</strong><input type="hidden" name="idf[]" id="idf" value="'.$conta.'"></td>';
              $fecha=$fecha_menu['fprepmnu'];
              $codmenu=$fecha_menu['codmenu'];
              $lista_platos_menu = $this->comedor_model->get_lista_platos_menu($codmenu,$fecha);

              if($lista_platos_menu){

                foreach ($lista_platos_menu as $platos_menu) {             

                  $imgplto=($platos_menu['imgplto']=='')?'sinplato.jpg':$platos_menu['imgplto'];
                  $clastd='class="success"';
                  $td='';
                  $radio='';
                  $radios='<input type="radio" name="optionsRadios'.$conta.'" id="optionsRadios'.$conta.'" value="'.$platos_menu['codmndet'].'" required="" onclick="colortr('.$conta.')">';
                  $radion='<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>';
                  $radiox='<span class="glyphicon glyphicon-check" aria-hidden="true"></span>';
                  if($rslpm!=null){
                    $rslpmd=$this->comedor_model->get_by_id_pmbvalid($rslpm->codped,$platos_menu['codmndet']);
                    $radio=($rslpmd!=null)?$radiox:$radion;
                    $td=($rslpmd!=null)?$clastd:'';
                  }else{
                    $radio=$radios;
                    $td='';
                  }

                  $plto='<div class="media">
                  <div class="media-left">
                  <a href="#">
                  <img class="media-object img-thumbnail" src="assest/imagenplatos/'.$imgplto.'" alt="plato1" width="60" height="60">
                  </a>
                  </div>
                  <div class="media-body">
                  <h4 class="media-heading">'.$platos_menu['nomplto'].'</h4>
                  <div class="radio">
                  <label>
                  '.$radio.'
                  <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
                  </label>
                  </div>
                  </div>
                  </div>';

                  $html.='<td '.$td.'>'.$plto.'</td>';        
                }}
                $param=  $codmenu . ", '" . $fecha . "'" ;
                $html.='</tr>';
              }     
            }   
            $result['dato1']=$datahmnu;
            $result['dato2']=$html;
            $result['dato3']=$existeped;
            $result['dato4']=$existepeddsc;
            $result['dato5']=$fechaultiped;
            $result['existe'] = 1;
          }else{
            $result['existe'] = 0;
            $lispb[] = array('men'=>'el dato buscado no esta registrado, no hay menu registrado!!!','msg'=>0);
            $result['dato'] =$lispb;
          }
          echo json_encode($result);
        }
        function ajax_add_pedidomnu(){
          $per=$this->input->post('codper');
          $menu=$this->input->post('codhmnus');
          $rslpm=$this->comedor_model->get_by_id_pmhvalid($menu,$per);           
            //$existe=($rslpm!=null)?$rslpm->codped:'';           
          if ($rslpm) { 
           $result['existe'] = 0;      
           $result['dato'] =$per;
         }else {  
          $idf=$this->input->post('idf');
          $datab= array();
          foreach ($idf as $id) {
           $datab[]= array(
            'codmdp'=>$this->input->post('optionsRadios'.$id),
          );

         }
         $datah = array(
          'codper'=>$per,
          'cdmnu'=>$menu,
          'comen'=>$this->input->post('txtcmtsgr'),
          'fechped'=>gmdate("Y-m-d H:i:s", time() - 18000), 
          'usucrped' => 1,
        );
         $insert = $this->comedor_model->save_pedmnuh($datah,$datab);

         $result['dato'] = $per;
         $result['existe'] = 1;  

       }
       echo json_encode($result);
     }

     public function ajax_visuali_mnuxcsmo() {
      $per=$this->input->post('codper');     
      $resmnu='';
      $resultado = $this->comedor_model->get_by_id_mxc();
      if($resultado){
       $resmnu=$resultado;      
     }else{
       $resmnu = $this->comedor_model->get_by_id_mxpmaximo();
     }

     $html='';
     $radios='';
     $consumido='';
     if ($resmnu) {
       $codhmnus= $resmnu->codmnus;
       $rslpm=$this->comedor_model->get_by_id_pmhvalid($codhmnus,$per);

       $rslcm=$this->comedor_model->get_plato_hoy_menu_per($codhmnus,$per);
       if($rslcm!=null){
        $imgpltod=($rslcm->imgplto=='')?'sinplato.jpg':$rslcm->imgplto;
        $hid='<input type="hidden" name="codped" id="codped" value="'.$rslcm->codped.'"><input type="hidden" name="codpedet" id="codpedet" value="'.$rslcm->codpd.'"><input type="hidden" name="codpdtm" id="codpdtm" value="'.$rslcm->codmdp.'"><input type="hidden" name="califplto" id="califplto">';//glyphicon glyphicon-star-empty estdp
        $consumido=($rslcm->estdp!=null)?$rslcm->estdp:''; 
        $estadoconsumo=($rslcm->estdp=='C')?' <span class="label label-success"><span class="glyphicon glyphicon-check" aria-hidden="true"> Ya consuminio</span></span>':'';
        $datmnudia='<p>'.$estadoconsumo.'</p><div class="media-left">'.$hid.'<a href="#"><img class="media-object img-thumbnail" src="assest/imagenplatos/'.$imgpltod.'" alt="plato1" width="180" height="180"></a></div><div class="media-body"><h4 class="media-heading"> Menu Eligido para el dia :'.$rslcm->diasemenu.' '.$rslcm->diamenu.' de '.$rslcm->mesmenu.'</h4><p>'.$rslcm->nomplto.'</p><div id="rateYo"></div></div></div><br>';
        $existeped=($rslpm->codped>0)?$rslpm->codped:0;
        $existepeddsc=($rslpm->comen!=null)?$rslpm->comen:0;
      }else{
        $datmnudia='<div class="media-left"><a href="#"><img class="media-object img-thumbnail" src="assest/imagenplatos/sinplato.jpg" alt="plato1" width="180" height="180"></a></div><div class="media-body"><h4 class="media-heading"> No hay menu para el dia </h4><p></p><div id="rateYo"></div></div></div><br>';
        $consumido='';
        $existeped=0;
        $existepeddsc=0;
      }
      
      $datahmnu = $this->comedor_model->get_by_id_hm($codhmnus);
      $lista_fecha_menu = $this->comedor_model->get_lista_fecha_menu($codhmnus);
      if($lista_fecha_menu){
        $conta=0;
        foreach ($lista_fecha_menu as $fecha_menu) {  
          $conta=$conta+1;  
          $fechamenu=$fecha_menu['diasemenu'].' '.$fecha_menu['diamenu'].' de '.$fecha_menu['mesmenu'];
          $html.="<tr><td><strong>".$fechamenu.'</strong></td>';
          $fecha=$fecha_menu['fprepmnu'];
          $codmenu=$fecha_menu['codmenu'];
          $lista_platos_menu = $this->comedor_model->get_lista_platos_menu($codmenu,$fecha);
          if($lista_platos_menu){
            foreach ($lista_platos_menu as $platos_menu) {
             $imgplto=($platos_menu['imgplto']=='')?'sinplato.jpg':$platos_menu['imgplto'];
             $clastd='class="success"';
             $td='';
             $radio='';
             $radion='<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>';
             $radiox='<span class="glyphicon glyphicon-check" aria-hidden="true"></span>';
             if($rslpm!=null){
              $rslpmd=$this->comedor_model->get_by_id_pmbvalid($rslpm->codped,$platos_menu['codmndet']);
              $radio=($rslpmd!=null)?$radiox:$radion;
              $td=($rslpmd!=null)?$clastd:'';
            }else{
              $radio=$radios;
              $td='';
            }
            $plto='<div class="media">
            <div class="media-left">
            <a href="#">
            <img class="media-object img-thumbnail" src="assest/imagenplatos/'.$imgplto.'" alt="plato1" width="60" height="60">
            </a>
            </div>
            <div class="media-body">
            <h4 class="media-heading">'.$platos_menu['nomplto'].'</h4>
            <div class="radio">
            <label>
            '.$radio.'
            <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
            </label>
            </div>
            </div>
            </div>';
            $html.='<td '.$td.'>'.$plto.'</td>';        
          }}
          $param=  $codmenu . ", '" . $fecha . "'" ;
          $html.='</tr>';
        }     
      }else{

      }   
      $result['dato1']=$datahmnu;
      $result['dato2']=$html;
      $result['dato3']=$existeped;
      $result['dato4']=$existepeddsc;
      $result['dato5']=$datmnudia;
      $result['dato6']=$consumido;
      $result['existe'] = 1;
    }else{
      $result['existe'] = 0;
      $lispb = ['men'=>'el dato buscado no esta registrado no hay Menu Pedido !!!','msg'=>0];
      $result['dato'] =$lispb;
    }
    echo json_encode($result);
  }
  function ajax_add_conmnudia(){
   $data = array(
     'califpltodp'=>$this->input->post('califplto'),
     'fcnspd'=>gmdate("Y-m-d H:i:s", time() - 18000),
     'estdp'=>'C',
     'usumdpd' => $this->input->post('codper'),
     'fmdpd' => gmdate("Y-m-d H:i:s", time() - 18000),
   );
   $where=    array(
    'codpd'=>$this->input->post('codpedet'),
    'codped'=>$this->input->post('codped'),
    'codmdp'=>$this->input->post('codpdtm'),
  );
   $rslupcmd = $this->comedor_model->update_conmnudia($where, $data);
   if ($rslupcmd > 0) {     
    $result['dato'] = $this->input->post('codper');
    $result['existe'] = 1;
  }else{
    $result['existe'] = 0;
    $lispb[] = array('men'=>'el dato buscado no esta registrado, haga clic para proceder a registrarse!!!','msg'=>0);
    $result['dato'] =$lispb;
  }
  echo json_encode($result);
}
function get_lista_menupedidosxper($idper){
  $html='';
  $acori='';
  $codper = $this->security->xss_clean($idper);
  $rslmpp = $this->comedor_model->get_lista_menupedidosxper($codper);
  if($rslmpp){

    $acori='<div class="col-md-12"> <div class="panel-group" id="accordion">';
    $conta=0;
    $thd='';
    $tbd='';
    $table='';
    foreach($rslmpp as $datah){
     $conta=$conta+1;
     $in=($conta==1)?'in':'';
     $th='';
    $tb='';
     $lista_fm = $this->comedor_model->get_lista_fecha_menu($datah['cdmnu']);
        foreach ($lista_fm as $fecha_menu) {
      $fecha=$fecha_menu['fprepmnu'];
      $fechamenu=$fecha_menu['diasemenu'].' '.$fecha_menu['diamenu'].' de '.$fecha_menu['mesmenu'];
      $th.='<td><strong>'.$fechamenu.'</strong></td>';
      $plts_menu = $this->comedor_model->get_plato_menu_pordia($datah['cdmnu'],$fecha);
      $imgplto=($plts_menu->imgplto=='')?'sinplato.jpg':$plts_menu->imgplto;
      $img='<div class="media">
      <div class="media-left">
      <a href="#">
      <img class="media-object img-thumbnail" src="assest/imagenplatos/'.$imgplto.'" alt="plato1" width="60" height="60">
      </a>
      </div>
      <div class="media-body"><h4 class="media-heading">'.$plts_menu->nomplto.'</h4></div>
      </div>';
      $fe= gmdate("Y-m-d", time() - 18000);
      $clastd='class="success"';
      $fech=($fe==$fecha)?$clastd:'';
      $tb.='<td '.$fech.'>'.$img.'</td>';
     
    }
$thd=$th;
    $tbd=$tb;
     $simb='<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
      $table='<div class="panel panel-info">
    <div class="panel-heading">
    <h4 class="panel-title">
    <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$conta.'">'.$simb.' Mi Pedido de '.$datah['nommnus'].' Pedido el dia '.$datah['fechped'].'</a>
    </h4>
    </div>
    <div id="collapse'.$conta.'" class="panel-collapse collapse '.$in.'">
    <div class="panel-body"><table class="table table-bordered"><thead><tr>'.$thd.'</tr></thead><tbody><tr>'.$tbd.'</tr></tbody></table></div></div>';
$html.=$table;
  }
  
$resul=$acori.$html.'</div></div>';
  $result['dato1']=$resul;
  $result['existe'] = 1;
}else{
  $result['existe'] = 0;
  $lispb = ['men'=>'el dato buscado no esta registrado no hay Menu Pedido !!!','msg'=>0];
  $result['dato'] =$lispb;
}
echo json_encode($result);
}


}
?>