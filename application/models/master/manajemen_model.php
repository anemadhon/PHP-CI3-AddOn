<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manajemen_model extends CI_Model{

    private $_table = 'd_admin';
    public $admin_emp_id;
    public $admin_perm_grup_ids;
    public $admin_username;
    public $admin_realname;
    public $admin_password;
    public $plant_select_type;
    public $admin_selectall;
    public $plants;
    public $plant_type_id;
    public $plant;
    public $admin_add_id;
    public $add_time;
    public $edit_time;
    public $admin_email;

    function __construct(){
        parent::__construct(); 
    }

    public function rules(){
        return [
            ['field' => 'admin_username',
            'label' => 'Username',
            'rules' => 'required'],

            ['field' => 'admin_realname',
            'label' => 'Nama Lengkap',
            'rules' => 'required'],
            
            ['field' => 'admin_password',
            'label' => 'Password',
            'rules' => 'required'],

            ['field' => 'admin_password_confirm',
            'label' => 'Konfirmasi Password',
            'rules' => 'matches[admin_password]'],

            ['field' => 'dept_manager',
            'label' => 'Departemen',
            'rules' => 'required'],
        ];
    }

    function getAdmin(){
        $this->db->select('admin_id, admin_username, admin_realname, admin_perm_grup_ids');
        $this->db->from('d_admin');

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function getAdminbyId($admin_id){
        return $this->db->get_where($this->_table, ['admin_id' => $admin_id])->row();
    }

    function save(){
        $post = $this->input->post();
        $this->admin_password = md5($post['admin_password']);
        $this->admin_username = $post['admin_username'];
        $this->admin_realname = $post['admin_realname'];
        $this->admin_email = $post['admin_email'];
        $this->dept_manager = $post['dept_manager'];
        $this->add_time = date("Y-m-d H:i:s");
        $this->edit_time = date("Y-m-d H:i:s");
        $data_nik = $post['data_nik'];
        $perm_group_id = $post['perm_group_id'];
        $plant_list = $post['plants'];

        if($data_nik != 0){
            $this->admin_emp_id = $this->select_employee_id($data_nik);
        }else{
            $this->admin_emp_id = 0;
        }

        foreach ($plant_list as  $value) {
            $this->plants .= trim($value).", ";
            if(empty($this->plant)){
                $this->plant = $value;
            }
        }

        if($this->plant != 0){
            $this->plant_type_id = $this->select_company_code($this->plant);
        }

        foreach ($perm_group_id as $value) {
            $this->admin_perm_grup_ids .= " ".$value.", ";
        }

        $this->db->insert($this->_table, $this);
    }

    function update(){
        $post = $this->input->post();

        $update = array(
            'plants' => implode(",",$post['plants']),
            'admin_perm_grup_ids' => " ".implode(", ",$post['perm_group_id']).",",
            'edit_time' => date("Y-m-d H:i:s"),
            'dept_manager' => $post['dept_manager']
        );
        

        $this->db->update($this->_table, $update, array('admin_id' => $post['admin_id']));
    }

    function perm_group_select($group_id) {

		$this->db->from('d_perm_group');
		$this->db->where('group_id', $group_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return FALSE;
		}
    }

    function selectPermGroup($group_id){
        $this->db->from('d_perm_group');
		$this->db->where('group_id', $group_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return FALSE;
		}
    }

    function selectedPermGroups($permGroupId = Null){
        $temp = str_replace(' ', '', $permGroupId);
        $arrPermGroup = explode(',',$temp);
        unset($temp);
        sort($arrPermGroup);

        foreach ($arrPermGroup as $key => $val) {
            $group[] = $this->selectPermGroup($val);
        }
        return $group;
    }
    
    function selectedPlants($plantsSelected = NULL){
        $temp = str_replace(' ', '', $plantsSelected);
        $arrPlants = explode(',',$temp);
        unset($temp);
        sort($arrPlants);

        foreach ($arrPlants as $key => $value) {
            $plant[] = $this->selectOutlet($value);
        }
        return $plant;
        
    }

    function selectOutlet($group_outlet){
        $this->db->from('m_outlet');
		$this->db->where('OUTLET', $group_outlet);

        $query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return FALSE;
        }
    }

    function select_company_code($plant) {
		$this->db->select('COMP_CODE');
		$this->db->from('m_outlet');
		$this->db->where('OUTLET', $plant);
		
		$query = $this->db->get();
  	    if($query->num_rows() > 0) {
  	        $comp_code = $query->row_array();
			return $comp_code['COMP_CODE'];
		} else {
			return '';
		}
    }
    
    function select_employee_id($nik) {
		$this->db->select('employee_id');
		$this->db->from('m_employee');
		$this->db->where('nik', $nik);
		
		$query = $this->db->get();
  	    if($query->num_rows() > 0) {
  	        $emp_id = $query->row_array();
			return intval($emp_id['employee_id']);
		} else {
			return 0;
		}
    }
    
    function showOutlet(){
        $this->db->from('m_outlet');
        $this->db->order_by("OUTLET", "asc");
        $this->db->not_like('OUTLET', 'T.','after');

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function perm_group() {

        $this->db->from('d_perm_group');
        
		$query = $this->db->get();

		$ret = $query->result_array();
        return $ret;
	}

    function showPermGroup($admin_id){
        $this->db->select('admin_perm_grup_ids');
		$this->db->from('d_admin');
        $this->db->where('admin_id', $admin_id);
        
        $query = $this->db->get();

        if ($query->num_rows() > 0){
            $admin = $query->row_array();

            $temp = str_replace(' ', '', $admin['admin_perm_grup_ids']);
            $perm_group_ids = explode(",", $temp); 
            unset($temp);
            
            $digit = count($perm_group_ids) - 1;
            if(empty($perm_group_ids[$digit]))
                unset($perm_group_ids[$digit]);
                foreach ($perm_group_ids as $perm_group_id) {
                    $perm_groups[] = $this->perm_group_select($perm_group_id);
                }
    
                return $perm_groups;
            } else {
                return FALSE;
        }
    }

    public function hapus($admin_id){
        return $this->db->delete($this->_table, array("admin_id" => $admin_id));
    }

    function getAllDataDivisi(){
        $this->db->select('id_dept, dept, dept_head_id, (SELECT admin_realname FROM d_admin WHERE admin_id = a.dept_head_id) as dept_head_name');
        $this->db->from('t_department a');
        $this->db->where("a.dept <> ''",null,false);

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }
}