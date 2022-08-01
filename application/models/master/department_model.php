<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Department_Model extends CI_Model {

    private $_table = 't_department';

    function __construct(){
        parent::__construct(); 
    }

    function getAllDataDivisiSAP(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('PrcCode, PrcName');
        $SAP_MSI->from('oprc');
        $SAP_MSI->where('DimCode',3);

        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function getUserForHeadDept(){
        $this->db->select('admin_id, admin_username, admin_realname');
        $this->db->from('d_admin');
        $this->db->like('admin_username', 'SX_', 'after');

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }
    
    function getAllDataDivisi(){
        $this->db->select('id_dept, dept_code, dept_name, dept_head_id, dept, (SELECT admin_realname FROM d_admin WHERE admin_id = a.dept_head_id) as dept_head_name');
        $this->db->from('t_department a');

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function getDivisibyId($id){
        $this->db->select('id_dept, dept_code, dept_name, dept_head_id, dept');
        $this->db->from('t_department a');
        $this->db->where('id_dept', $id);

        $query = $this->db->get();
        $ret = $query->row_array();
        return $ret;
    }

    function save($data){

        if($this->db->insert($this->_table, $data)) {
            if ($this->update_hod($data['dept_head_id'])) {
                return $this->db->insert_id();
            }
        } else {
            return FALSE;
        }
    }

    function update($data){

        $update = array(
            'dept' => $data['dept'],
            'dept_code' => $data['dept_code'],
            'dept_name' => $data['dept_name'],
            'dept_head_id' => $data['dept_head_id'],
            'id_user_edit' => $data['id_user_edit'],
            'lastmodified' => $data['lastmodified']
        );
        
        $this->db->update($this->_table, $update, array('id_dept' => $data['id_dept']));
    }

    public function hapus($id){
        return $this->db->delete($this->_table, array("id_dept" => $id));
    }

    function getDivisibyHead($id){
        $this->db->select('dept_head_id');
        $this->db->from('t_department');
        $this->db->where('dept_head_id', $id);

        $query = $this->db->get();
        $ret = $query->row_array();

        if(($query)&&($query->num_rows() > 0)){
			return $ret;
		}else{
			return FALSE;
		}
    }

    function getUserFromHeadDept($id){
        $this->db->select('admin_id, dept_manager');
        $this->db->from('d_admin');
        $this->db->where('dept_manager', $id);

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }
    
    function getHeadDeptUsername($id){
        $this->db->select('admin_username');
        $this->db->from('d_admin');
        $this->db->where('admin_id', $id);

        $query = $this->db->get();
        $ret = $query->row_array();
        return $ret;
    }

    function update_hod($id)
    {
        $this->db->set('dept_manager', $id);
        $this->db->where('admin_id', $id);
        if($this->db->update('d_admin'))
			return TRUE;
		else
			return FALSE;
    }
}