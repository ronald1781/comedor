<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

/*
"codper","dniper","nomper","apepper","apmper","usucrper","emailper","fcrper","usumdper","fmdper","estrgper"
*/
var $table = 'personas';
    var $colum = array('codper','dniper','nomper','apepper','apmper','emailper','nomsuc','estrgper');
    var $order = array('codper' => 'desc');

    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->join('sucursal ', 'sucursal.codsuc=personas.sucper','left');
        $this->db->where('estrgper', 'A');
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
         $this->db->where('estrgper', 'A');
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('codper', $id);
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
        $this->db->set('fdelper',  gmdate("Y-m-d H:i:s", time() - 18000)); 
        $this->db->set('estrgper', 'I');
        $this->db->where('codper', $id);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
    public function get_by_dni($id) {
        $this->db->from($this->table);
        $this->db->where('dniper', $id);/*
        $this->db->or_where('nomper', $id);
        $this->db->or_where('apepper', $id);        
        $this->db->or_where('apmper', $id);*/
        $this->db->where('estrgper', 'A');
        $query = $this->db->get();
        return $query->result();
    }
public function get_sucursal() {
     $this->db->select('codsuc,nomsuc,estrgsuc');
        $this->db->from('sucursal');
        $this->db->where('estrgsuc', 'A');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_sucursales() {
     $this->db->select('codsuc,nomsuc');
        $this->db->from('sucursal');
        $this->db->where('estrgsuc', 'A');
        $query = $this->db->get();
        return $query->result_array();
    }

}
?>