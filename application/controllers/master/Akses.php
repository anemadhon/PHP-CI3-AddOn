<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Akses extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('auth');
        //load model
        if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        if(!$this->auth->is_have_perm('masterdata_posisi'))
        redirect('');

        $this->load->model('master/permission_model', 'm_perm');
    }

    public function index(){

        $perm_codes = array (
            'perm_group_browse_name_all',
            'perm_group_browse_detail_all',
        );
        
        $object['perm_codes']['own'] = array (
			'perm_group_view_name_own',
		);

		$object['perm_codes']['view'] = array (
			'perm_group_view_detail_own',
			'perm_group_view_name_all',
			'perm_group_view_detail_all',
		);

		$object['perm_codes']['edit'] = array (
			'perm_group_edit_name_all',
			'perm_group_edit_detail_all',
		);

		$object['perm_codes']['delete'] = array (
			'perm_group_delete_all',
		);

		$object['perm_codes']['add'] = array (
			'perm_group_add_all',
		);

        $object['perm_codes2'] = $this->auth->perm_code_merge($object['perm_codes']);
        
		// end of content permissions

        $object['data']['perm_groups'] = $this->m_perm->perm_groups_select_all();

        $object['perms'] = $this->m_perm->perms_select_all();

        $this->load->view('master/hak_akses/list_view', $object);
    }

    public function add(){
        
        $object = array();

        $object['perms'] = $this->m_perm->perms_select_all1();
        $object['perms2'] = $this->m_perm->perms_select_all2();

        $this->load->view('master/hak_akses/add_form', $object);
    }
    
    public function save(){
        
        $group = $_POST;
        $group['group_perms']=null;
        if( $group['group_name'] !== "" && count($group['perm']) > 0 && count($group['perm2']) > 0 ){
            $group['group_perms'] = '';
            
            foreach($group['perm'] as $perm) {
                if(!empty($perm))
                    $group['group_perms'] .= $perm;
            }
            
            foreach($group['perm2'] as $perm) {
                if(!empty($perm))
                    $group['group_perms'] .= $perm;
            }

            unset($group['perm']);
            unset($group['perm2']);

            $group['group_order'] = $this->m_perm->perm_group_order_max_select() + 1;

            if($this->m_perm->perm_group_add($group)) {
    
                $this->session->set_flashdata('success', "Berhasil nembahkan grup hak akses");	
            } else {
                $this->session->set_flashdata('failed', "Gagal menambahkan grup hak akses");
            }

        }

        redirect('master/akses');
    }
    
	public function edit($ID=NULL){
        $object = array();
        $data = $this->m_perm->perm_group_select($ID);
        $object['data'] = $data;
        $object['page_title'] = $this->lang->line('perm_group_perm_edit');
        $object['perms'] = $this->m_perm->perms_select_all1();
        $object['perms2'] = $this->m_perm->perms_select_all2();
        
        $this->load->view('master/hak_akses/edit_form', $object);
    }

    public function update(){
        $group = $_POST;
        $group['group_perms']=null;
        
        if( $group['group_name'] !== "" && (count($group['perm']) > 0 || count($group['perm2']) > 0)){
            
            $group['group_perms'] = '';
            
            foreach($group['perm'] as $perm) {
                if(!empty($perm))
                    $group['group_perms'] .= $perm;
            }

            foreach($group['perm2'] as $perm) {
                if(!empty($perm))
                    $group['group_perms'] .= $perm;
            }

            unset($group['perm']);
            unset($group['perm2']);

            if($this->m_perm->perm_group_update($group)) {
    
                $this->session->set_flashdata('success', "Berhasil mengubah grup hak akses");	
            } else {
                $this->session->set_flashdata('failed', "Gagal mengubah grup hak akses");
            }
        }

        redirect('master/akses');
    }

    public function delete($group_id){

        $group_id = (int) $group_id;

		// start of check page permission
		$perm_codes = array (
			'perm_group_delete_all',
		);

		if(!$this->auth->is_have_perm($perm_codes))
            redirect(base_url());

        if($this->m_perm->perm_group_delete($group_id)){
            $this->session->set_flashdata('success', "Data grup hak akses berhasil di hapus");	
        } else {
            $this->session->set_flashdata('failed', "Data grup hak akses gagal di hapus");
        }

        redirect('master/akses');
    }
}
?>