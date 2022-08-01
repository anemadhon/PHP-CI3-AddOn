<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Integration_model extends CI_Model{

    function __construct(){
        parent::__construct(); 
	}

    public function showIntegration(){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$sql = "SELECT * FROM error_log WHERE modul NOT LIKE 'Module:%' AND Whs = '".$kd_plant."' ORDER BY time_error DESC";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$result = $query->result();

		if($num > 0){
			return $result;
		}else{
			return FALSE;
		}	
	}
}