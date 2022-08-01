<?php defined('BASEPATH') OR exit('No direct script access allowed');

class General_Model extends CI_Model {

    function __construct(){
        parent::__construct(); 
    }

    function sap_plant_select_by_id($plant) {
		if (Empty($plant)) $plant="";
		$plant = trim($plant);
		if ($plant!=""){
			$plants = $this->sap_plants_select_all("","","",$plant);
			
			
			if ($plants) {
			
				return $plants[1];
			} else {
				return FALSE;
			}
		} else return FALSE;
    }
    
    function sap_plants_select_all($plant_type_id="",$is_ck_outlet="",$is_non_ck_outlet="",$plant_id="") {
        $plants = $this->sap_plants_select_by_plant_type($plant_type_id,$is_ck_outlet,$is_non_ck_outlet,$plant_id);
        return $plants;
    }

    function sap_plants_select_by_plant_type($plant_type_id,$is_ck_outlet,$is_non_ck_outlet,$plant_id="") {

        $this->db->from('m_outlet');
        if (!empty($plant_type_id)) {
            $this->db->where('COMP_CODE', $plant_type_id);
        }
        if ($is_ck_outlet=="X") {
            $this->db->where('OUTLET LIKE "C%"');
        }
        if ($is_non_ck_outlet=="X") {
            $this->db->where('OUTLET NOT LIKE "C%"');
        }
        if (!empty($plant_id)) {
            $this->db->where('OUTLET',$plant_id);
        }

        $query = $this->db->get();

        if(($query)&&($query->num_rows() > 0)) {
            $plants = $query->result_array();
            $count = count($plants);
            $k = 1;
            for ($i=0;$i<=$count-1;$i++) {
                $plant[$k] = $plants[$i];
                $plant[$k]['plant'] = $plant[$k]['OUTLET'];
                $plant[$k]['plant_name'] = $plant[$k]['OUTLET_NAME1'];
                $plant[$k]['plant_name2'] = $plant[$k]['OUTLET_NAME2'];
                $plant[$k]['plant_type_id'] = $plant[$k]['COMP_CODE'];
                $plant[$k]['WERKS'] = $plant[$k]['plant'];
                $plant[$k]['NAME1'] = $plant[$k]['plant_name'];
                $plant[$k]['HR_CODE'] = $plant[$k]['HR_CODE'];
                $k++;
            }
            return $plant;
        } else {
            return FALSE;
       }
    }

    function sap_jenisoutlet_select($id_jenisoutlet) {
		$this->db->from('t_jenisoutlet');
		$this->db->where('id_jenisoutlet', $id_jenisoutlet);

		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0))
			return $query->row_array();
		else
			return FALSE;
	}
    
    function sap_plant_select_by_id_summary($plant) {
		if (Empty($plant)) $plant="";
		$plant = trim($plant);

		if ($plant!=""){

			$this->db->select('a.OUTLET,a.OUTLET_NAME1,a.OUTLET_NAME2,a.COMP_CODE,b.jenisoutlet');
			$this->db->from('m_outlet a, t_jenisoutlet b');
			if (!empty($plant)) {
			  $this->db->where('OUTLET',$plant);
			}
			$query = $this->db->get();
			$plant_result=array();

			if(($query)&&($query->num_rows() > 0)) {
				$plants = $query->result_array();
				return $plants[0];
			 } else {
				return FALSE;
			 }

			
		} else return FALSE;
    }
	
	function sap_get_plant_type_id($plant) {
		$this->db->select('comp_code');
		$this->db->from('m_outlet');
		$this->db->where('outlet', $plant);

		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			$hasil =  $query->row_array();
			return $hasil['comp_code'];
		} else {
			return FALSE;
		}
	}

	function sap_get_user_plants($admin_id) {
		$this->db->select('plants');
		$this->db->from('d_admin');
		$this->db->where('admin_id', $admin_id);

		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			$hasil =  $query->row_array();
			return $hasil['plants'];
		} else {
			return FALSE;
		}
	}

    function office_plants() {
		$plant[] = 'AB01';
		$plant[] = 'AB02';

		return $plant;
	}
	
	function kurs() {
		$matauang = 'IDR';
		$matauang = 'USD';
		return $matauang;
	}
}   