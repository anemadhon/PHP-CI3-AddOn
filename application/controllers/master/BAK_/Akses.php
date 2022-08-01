<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Akses extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index(){
        $this->load->view('master/hak_akses/list_view');
    }

    public function add(){
        $this->load->view('master/hak_akses/add_form');
    }
	
	public function edit(){
        $this->load->view('master/hak_akses/edit');
    }
	
	public function check(){
        $this->load->view('master/hak_akses/check');
    }
	
	public function showAllData(){
       $dt= array(
           array(
            "no" => "1",
			"grup_hak_akses"=> "Not Approved",
            "admin_terkait" => "false",
           ),
		   array(
            "no" => "2",
			"grup_hak_akses"=> "Super Admin",
            "admin_terkait" => "true",
           ),
		   array(
            "no" => "3",
			"grup_hak_akses"=> "Area Manager",
            "admin_terkait" => "false",
           ),
		   array(
            "no" => "4",
			"grup_hak_akses"=> "Outlet - Manager",
            "admin_terkait" => "false",
           ),
		   array(
            "no" => "4",
			"grup_hak_akses"=> "HQ - Manager",
            "admin_terkait" => "true",
           ),
		   array(
            "no" => "5",
			"grup_hak_akses"=> "Store",
            "admin_terkait" => "false",
           )
        ); 

        $data = [
            "data"=> $dt
        ];
        
        echo json_encode($data);
    }
}
?>