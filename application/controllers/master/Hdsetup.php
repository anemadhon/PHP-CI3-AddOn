<?php 

class Hdsetup extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');
        //load model
        if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        if(!$this->auth->is_have_perm('masterdata_posisi'))
        redirect('');

        $this->load->model('master/department_model', 'divisi');
    }

    public function index(){
        $data['dept'] = $this->divisi->getAllDataDivisi();
    
        $this->load->view('master/hdsetup/list_view', $data);
    }

    function add(){
        $data['divisi'] = $this->divisi->getAllDataDivisiSAP();
        $data['users'] = $this->divisi->getUserForHeadDept();
        $this->load->view('master/hdsetup/add_view', $data);
    }

    public function store(){
        $department['dept'] = $this->input->post('dept');
        $department['dept_code'] = $this->input->post('accDeptCode');
        $department['dept_name'] = $this->input->post('accDeptName');
        $department['dept_head_id'] = $this->input->post('deptHead');
        $department['id_user_input'] = $this->session->userdata['ADMIN']['admin_id'];
        $department['created_date'] = date('Y-m-d H:i:s');
        $department['lastmodified'] = date('Y-m-d H:i:s');
        
        if($this->divisi->save($department)){
            return $this->session->set_flashdata('success', "Data Departemen Berhasil ditambahkan");
        }else{
            return $this->session->set_flashdata('failed', "Data Departemen Gagal ditambahkan");
        }
    }

    public function edit($id){
        $data['divisi_sap'] = $this->divisi->getAllDataDivisiSAP();
        $data['divisi'] = $this->divisi->getDivisibyId($id);
        $data['users'] = $this->divisi->getUserForHeadDept();
        
        $this->load->view('master/hdsetup/edit_view', $data);
    }
    
    public function update(){
        
        $department['id_dept'] = $this->input->post('deptId');
        $department['dept'] = $this->input->post('dept');
        $department['dept_code'] = $this->input->post('accDeptCode');
        $department['dept_name'] = $this->input->post('accDeptName');
        $department['dept_head_id'] = $this->input->post('deptHead');
        $department['id_user_edit'] = $this->session->userdata['ADMIN']['admin_id'];
        $department['lastmodified'] = date('Y-m-d H:i:s');

        $this->divisi->update($department);

        return $this->session->set_flashdata('success', "Data Departemen Berhasil diubah");
    }

    public function delete($id = null){
        if(!isset($id)) show_404();
        
        if($this->divisi->hapus($id)){
            redirect('master/hdsetup');
        }
    }

}
?>