<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

   
public function validaLoguin($emailusua, $password) {
$rpt='';
$data ='';    
       //codusu,emailusu,usuausu,passusu,perfilusu,estrgusu
        $this->db->select('codusu,emailusu,usuausu,passusu,prfusu,estrgusu');
        $this->db->from('usuario');
        $this->db->where('emailusu', trim($emailusua));
        $this->db->where('passusu', trim($password));
        $this->db->where('estrgusu', 'A');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($query->num_rows==1) {
            $row = $query->row();
            $data = array(
                'codiper' => $row->codusu,
                'usuaper' => $row->usuausu,
                'mailuser' => $row->emailusu,
                'perfil' => $row->prfusu,
                'validated' => TRUE,
            );
            $this->session->set_userdata($data);
            $rpt=TRUE;
        }else{
      $rpt=FALSE;        
    }
   return $rpt;
}

var $table = 'usuario';
    var $colum = array('codusu','emailusu','usuausu','prfusu','estrgusu');
    var $order = array('codusu' => 'desc');

    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->where('estrgusu', 'A');
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
         $this->db->where('estrgusu', 'A');
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('codusu', $id);
        $this->db->where('prfusu<>', 0);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        $this->db->where('prfusu<>', 0);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
        $this->db->set('fdelusu',  gmdate("Y-m-d H:i:s", time() - 18000)); 
        $this->db->set('estrgusu', 'I');
        $this->db->where('codusu', $id);
        $this->db->where('prfusu<>', 0);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }


}
?>