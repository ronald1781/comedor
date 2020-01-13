<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comedor_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

/*
codplto,tipopto,nomplto,imgplto,descplto,usucrplto,fcrplto,usumdplto,fmdplto,fdelplto,estrgplto
*/
var $table = 'platos';
var $colum = array('codplto','nomali','nomplto','descplto','imgplto','estrgplto');
var $order = array('codplto' => 'asc');

private function _get_datatables_query() {
    $this->db->from($this->table);
    $this->db->join('tipoalimento ', 'tipoalimento.codtali=platos.tipopto','left');
    $this->db->where('estrgplto', 'A');
    $i = 0;
    foreach ($this->colum as $item) {
        if ($_POST['search']['value'])
            ($i === 0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
        $column[$i] = $item;
        $i++;
    }

    if (isset($_POST['order'])) {
        $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
        $order = $this->order;
        $this->db->order_by(key($order), $order[key($order)]);
    }
}

function get_datatables() {
    $this->_get_datatables_query();
    if ($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
}

function count_filtered() {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
}

public function count_all() {
    $this->db->from($this->table);
    $this->db->where('estrgplto', 'A');
    return $this->db->count_all_results();
}

public function get_by_id($id) {
    $this->db->from($this->table);
    $this->db->where('codplto', $id);
    $query = $this->db->get();
    return $query->row();
}

public function save($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
}

public function update($where, $data) {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
}

public function delete_by_id($id) {
    $this->db->set('fdelplto',  gmdate("Y-m-d H:i:s", time() - 18000)); 
    $this->db->set('imgplto', '');
    $this->db->set('estrgplto', 'I');
    $this->db->where('codplto', $id);
    $this->db->update($this->table);
    return $this->db->affected_rows();
}


public function get_tipoalimentos() {
 $this->db->select('codtali,nomali');
 $this->db->from('tipoalimento');
 $this->db->where('estrgali', 'A');
 $query = $this->db->get();
 return $query->result_array();
}
public function get_fileold($id) {
 $this->db->select('imgplto');
 $this->db->from('platos');
 $this->db->where('codplto', $id);
 $this->db->where('estrgplto', 'A');
 $query = $this->db->get();
 return $query->row();
}
    /*
Menus

 SELECT codmnus,nommnus,cntpltmnu,fdsdmnu,fhstmnu,ffnpdmnu,estmenu FROM `menus` where estmenu='G' and estrgmnus
    */
 var $tablehm = 'menus';
 var $columhm = array('codmnus','nommnus','cntpltmnu','ffnpdmnu','estmenu');
 var $orderhm = array('codmnus' => 'asc');

 private function _get_datatables_query_hm() {
    $this->db->from($this->tablehm);
    $this->db->where('estrgmnus', 'A');
    $this->db->order_by('codmnus', 'desc');
    $i = 0;
    foreach ($this->columhm as $item) {
        if ($_POST['search']['value'])
            ($i === 0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
        $columhm[$i] = $item;
        $i++;
    }
    if (isset($_POST['order'])) {
        $this->db->order_by($columhm[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
        $orderhm = $this->order;
        $this->db->order_by(key($orderhm), $orderhm[key($orderhm)]);
    }
}

function get_datatables_hm() {
    $this->_get_datatables_query_hm();
    if ($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
}

function count_filtered_hm() {
    $this->_get_datatables_query_hm();
    $query = $this->db->get();
    return $query->num_rows();
}

public function count_all_hm() {
    $this->db->from($this->tablehm);
    $this->db->where('estrgmnus', 'A');
    return $this->db->count_all_results();
}


public function save_hmnu($data) {
    $this->db->insert($this->tablehm, $data);
    return $this->db->insert_id();
}

public function get_by_id_hm($id) {
    $this->db->from($this->tablehm);
    $this->db->where('codmnus', $id);
    $this->db->where('estrgmnus', 'A');
    $query = $this->db->get();
    return $query->row();
}
public function update_hmnu($where, $data) {
    $this->db->update($this->tablehm, $data, $where);
    return $this->db->affected_rows();
}
var $tablebm = 'menudetalle';
public function delete_by_id_hm($id) {
    $this->db->set('fdlmndet',  gmdate("Y-m-d H:i:s", time() - 18000)); 
    $this->db->set('estrgmndet', 'I');
    $this->db->where('codmenu', $id);
    $this->db->update($this->tablebm);

    $this->db->set('fdlmnu',  gmdate("Y-m-d H:i:s", time() - 18000)); 
    $this->db->set('estrgmnus', 'I');
    $this->db->where('codmnus', $id);
    $this->db->update($this->tablehm);
    return $this->db->affected_rows();
}

function get_platos_menu($tipopto,$codmenu){

    $sql = $this->db->query("select codplto,nomplto FROM platos where tipopto=".$tipopto." and codplto not in(SELECT codplto FROM menudetalle where codmenu=".$codmenu." and estrgmndet='A') and estrgplto='A'")->result_array();
    return $sql;
}


public function save_bmnu($data) {
    $this->db->insert($this->tablebm, $data);
    return $this->db->insert_id();
}

public function get_by_id_bm($id) {
    $this->db->from($this->tablebm);
    $this->db->where('codmnus', $id);
    $this->db->where('estrgmndet', 'A');
    $query = $this->db->get();
    return $query->row();
}
public function update_bmnu($where, $data) {
    $this->db->update($this->tablebm, $data, $where);
    return $this->db->affected_rows();
}

public function delete_by_id_bm($id,$fecha) {
    $this->db->set('fdlmndet',  gmdate("Y-m-d H:i:s", time() - 18000)); 
    $this->db->set('estrgmndet', 'I');
    $this->db->where('codmenu', $id);
    $this->db->where('fprepmnu', $fecha);
    $this->db->update($this->tablebm);
    return $this->db->affected_rows();
}
function get_lista_fecha_menu($codmenu){
    $sql = $this->db->query('select a.codmndet,a.fprepmnu,a.codmenu,a.fprepmnu,(ELT(WEEKDAY(fprepmnu) + 1, "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo")) AS diasemenu,CASE
        WHEN MONTH(fprepmnu)=1 THEN "Ene"
        WHEN MONTH(fprepmnu)=2 THEN "Feb"
        WHEN MONTH(fprepmnu)=3 THEN "Mar"
        WHEN MONTH(fprepmnu)=4 THEN "Abr"
        WHEN MONTH(fprepmnu)=5 THEN "May"
        WHEN MONTH(fprepmnu)=6 THEN "Jun"
        WHEN MONTH(fprepmnu)=7 THEN "Jul"
        WHEN MONTH(fprepmnu)=8 THEN "Ago"
        WHEN MONTH(fprepmnu)=9 THEN "Sep"
        WHEN MONTH(fprepmnu)=10 THEN "Oct"
        WHEN MONTH(fprepmnu)=11 THEN "Nom"
        WHEN MONTH(fprepmnu)=12 THEN "Dic"
        ELSE "No pertenece"
        END AS mesmenu,day(fprepmnu) AS diamenu FROM menudetalle a where a.codmenu='.$codmenu.' and estrgmndet="A" group by fprepmnu')->result_array();
    return $sql;
}

function get_lista_platos_menu($codmenu,$fecha){
    $sql = $this->db->query('select a.codmndet,a.codtippto,a.codmenu,a.fprepmnu,a.codplto,b.nomali,c.nomplto,c.imgplto FROM menudetalle a inner join tipoalimento b on a.codtippto=b.codtali inner join platos c on a.codplto=c.codplto where codmenu='.$codmenu.' and fprepmnu="'.$fecha.'" and estrgmndet="A"')->result_array();
    return $sql;
}
function get_plato_menu_pordia($codmenu,$fecha){
    $sql = $this->db->query('select a.codmndet,a.codtippto,a.codmenu,a.fprepmnu,a.codplto,b.nomali,c.nomplto,c.imgplto FROM menudetalle a inner join tipoalimento b on a.codtippto=b.codtali inner join platos c on a.codplto=c.codplto where codmenu='.$codmenu.' and fprepmnu="'.$fecha.'" and estrgmndet="A"')->row();
    return $sql;
}
public function get_by_id_mxp() {
    $this->db->from($this->tablehm);
    $this->db->where('ffnpdmnu>=', gmdate("Y-m-d", time() - 18000));
    $this->db->where('estrgmnus', 'A');
    $this->db->limit(1);
    $query = $this->db->get();
    return $query->row();
}
public function get_by_id_mxpmaximo() {
    $this->db->from($this->tablehm);
    $this->db->order_by('ffnpdmnu', 'desc');
    $this->db->where('estrgmnus', 'A');
    $this->db->limit(1);
    $query = $this->db->get();
    return $query->row();
}
public function get_by_id_mxc() {
    $this->db->from($this->tablehm);
    $this->db->where('ffnpdmnu<=', gmdate("Y-m-d", time() - 18000));
    $this->db->where('estrgmnus', 'A');
    $this->db->order_by('ffnpdmnu','desc');// order by ffnpdmnu desc
    $this->db->limit(1);
    $query = $this->db->get();
    return $query->row();
}
var $tablepmh = 'pedidos';
var $tablepmb = 'pedidodetalle';
public function get_by_id_pmhvalid($menu,$per) {
    $this->db->from($this->tablepmh);
    $this->db->where('codper', $per);    
    $this->db->where('cdmnu', $menu);
    $this->db->where('estrgped', 'A');
    $query = $this->db->get();
    return $query->row();
}
public function save_pedmnuh($data,$datab) {

    $this->db->insert($this->tablepmh, $data);
    $insert=$this->db->insert_id();
    $datamv = array();
    foreach ($datab as $id) {
       $datab = array(
          'codped'=>$insert,
          'codmdp'=>$id['codmdp'],
          'fpedd'=>gmdate("Y-m-d", time() - 18000),
          'usucrpd' => 1,
      );       
       array_push($datamv, $datab);
   }
   $this->db->insert_batch($this->tablepmb, $datamv);
   $this->db->trans_complete();
   if ($this->db->trans_status() === FALSE) {
    $this->db->trans_rollback();
    return false;
} else {
    $this->db->trans_commit();
    return true;
}
$this->db->trans_off();     
return $insert;
}

public function get_by_id_pmbvalid($ped,$detpm) {
    $this->db->from($this->tablepmb);
    $this->db->where('codped', $ped);    
    $this->db->where('codmdp', $detpm);
    $this->db->where('estrgpd', 'A');
    $query = $this->db->get();
    return $query->row();
}

function get_plato_hoy_menu_per($codmenu,$codper){
$fechaactual=gmdate("Y-m-d", time() - 18000);
    $sql = $this->db->query('select b.codpd,a.codped,a.codper,a.cdmnu,b.estdp,b.codmdp,d.nomplto,d.imgplto,(ELT(WEEKDAY(c.fprepmnu) + 1, "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo")) AS diasemenu,CASE
        WHEN MONTH(c.fprepmnu)=1 THEN "Ene"
        WHEN MONTH(c.fprepmnu)=2 THEN "Feb"
        WHEN MONTH(c.fprepmnu)=3 THEN "Mar"
        WHEN MONTH(c.fprepmnu)=4 THEN "Abr"
        WHEN MONTH(c.fprepmnu)=5 THEN "May"
        WHEN MONTH(c.fprepmnu)=6 THEN "Jun"
        WHEN MONTH(c.fprepmnu)=7 THEN "Jul"
        WHEN MONTH(c.fprepmnu)=8 THEN "Ago"
        WHEN MONTH(c.fprepmnu)=9 THEN "Sep"
        WHEN MONTH(c.fprepmnu)=10 THEN "Oct"
        WHEN MONTH(c.fprepmnu)=11 THEN "Nom"
        WHEN MONTH(c.fprepmnu)=12 THEN "Dic"
        ELSE "No pertenece"
        END AS mesmenu,day(c.fprepmnu) AS diamenu FROM pedidos a inner join pedidodetalle b on a.codped=b.codped inner join menudetalle c on b.codmdp=c.codmndet inner join platos d on c.codplto=d.codplto where a.codper='.$codper.' and a.cdmnu='.$codmenu.' and c.fprepmnu="'.$fechaactual.'"')->row();
    return $sql;
}
public function update_conmnudia($where, $data) {
    $this->db->update($this->tablepmb, $data, $where);
    return $this->db->affected_rows();
}

function get_lista_menupedidosxper($codper){
$sql='select a.codper,a.codped,a.cdmnu,a.comen,a.fechped,b.nommnus,b.fdsdmnu,b.fhstmnu,b.ffnpdmnu,b.estmenu,(select count(b.codmdp) from pedidodetalle b where b.codped=a.codped)as numpltos FROM pedidos a inner join menus b on a.cdmnu=b.codmnus where a.codper='.$codper.' order by a.codped desc limit 15';
$data = $this->db->query($sql)->result_array();
return $data;

}




}
?>