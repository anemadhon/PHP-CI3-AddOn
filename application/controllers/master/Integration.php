<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Integration extends CI_Controller {
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

        $this->load->model('master/integration_model');
    }

    public function index(){
		$this->load->view('master/integration_log/list_view');
    }

    public function add(){
        $this->load->view('master/integration_log/add_form');
    }
	
	public function edit(){
        $this->load->view('master/integration_log/edit_form');
    }
	
	public function showAllData(){
        $data['data'] = $this->integration_model->showIntegration();
		
		echo json_encode($data);
    }
	
	public function goodIssueData(){
        $dt= array(
           array(
            "no" => "1",
            "issue_no" => "TH-FGB003",
            "material"=>"Tuna Fish Bun ",
            "whs_qty"=> "12.00",
            "qty"=> "3.00",
            "uom"=> "pcs",
            "reason"=> "Outlet Sales ",
            "other_reason"=> ""
           ),
		   array(
            "no" => "2",
            "issue_no" => "TH-FGB018",
            "material"=>"Cheese Croissant  ",
            "whs_qty"=> "9.00",
            "qty"=> "3.00",
            "uom"=> "pcs",
            "reason"=> "Outlet Sales ",
            "other_reason"=> ""
           ),
		   array(
            "no" => "3",
            "issue_no" => "TH-FGB014",
            "material"=>"Chocolate Cheese-TH ",
            "whs_qty"=> "7.00",
            "qty"=> "3.00",
            "uom"=> "pcs",
            "reason"=> "Outlet Sales ",
            "other_reason"=> ""
           ),
		   array(
            "no" => "4",
            "issue_no" => "TH-FGB022",
            "material"=>"Peach Danish ",
            "whs_qty"=> "10.00",
            "qty"=> "3.00",
            "uom"=> "pcs",
            "reason"=> "Outlet Sales ",
            "other_reason"=> ""
           ),
		   array(
            "no" => "5",
            "issue_no" => "TH-FGB020",
            "material"=>"Strawberry Danish ",
            "whs_qty"=> "8.00",
            "qty"=> "3.00",
            "uom"=> "pcs",
            "reason"=> "Outlet Sales ",
            "other_reason"=> ""
           ),
		   
        ); 

        $data = [
            "data"=> $dt
        ];
        
        echo json_encode($data);
    }
}
?>