<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_model extends CI_Model {

    function __construct(){
        parent::__construct(); 
		
		$this->load->model('general_model', 'm_general');
    }

    function perm_group_add($data) {
		$this->db->insert('d_perm_group', $data);
		return $this->db->insert_id();
    }
    
    function perm_group_update($data) {
		$update = array(
			'group_name' => $data['group_name'],
			'group_perms' => $data['group_perms']
		);
		$this->db->where('group_id', $data['group_id']);
		if($this->db->update('d_perm_group', $update))
			return TRUE;
		else
			return FALSE;
    }
    
    function perm_group_admins_select($group_id) {
		$this->db->from('d_admin');
		$this->db->LIKE('admin_perm_grup_ids', ' '.$group_id.',');
		$this->db->order_by('admin_username');

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query;
		else
			return FALSE;
	}

	/**
	 * Check is the permission group already defined to admin?
	 * @param integer $group_id
	 * @return bool
	 */

	function check_group_id_exist($group_id) {
		$this->db->from('d_admin');
		$this->db->LIKE('admin_perm_grup_ids', ' '.$group_id.',');

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Delete group permission
	 * @param integer $group_id
	 * @return bool
	 */

	function perm_group_delete($group_id) {
		if($this->check_group_id_exist($group_id) || $group_id == 1 || $group_id == 2) {
			return FALSE;
		} else {
			$this->db->where('group_id', $group_id);

			if($this->db->delete('d_perm_group'))
				return TRUE;
			else
				return FALSE;
		}
	}

	/**
	 * Get query of all group permissions
	 * @return resource Query of all group permission.
	 */

	function perm_groups_select_all() {
		$this->db->from('d_perm_group');
		$this->db->order_by('group_order');

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query;
		else
			return FALSE;
	}

	/**
	 * Get one row/record (array) of group permission
	 * @param integer $group_id
	 * @return array row/record of group_id
	 */

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

	/**
	 * Get groups id (array, multiple) of an admin
	 * @param integer $admin_id
	 * @return array multiple group_id
	 */

	function admin_perm_group_ids_select($admin_id = 0) {
		if($admin_id == 1){
			$perm_group_ids[0] = 0;
			return $perm_group_ids;
		
		} else {

			if($admin_id === 0)
				$admin_id = $this->session->userdata['ADMIN']['admin_id'];

			$this->db->select('admin_perm_grup_ids');
			$this->db->from('d_admin');
			$this->db->where('admin_id', $admin_id);

			$query = $this->db->get();

			if (($query)&&($query->num_rows() > 0)) {

				$admin = $query->row_array();

				$temp = substr($admin['admin_perm_grup_ids'], 1); // delete the first space
				$perm_group_ids = explode(",", $temp); // get permissions array
				unset($temp);

				// check the last array
				// if empty, unset/delete it
				$digit = count($perm_group_ids) - 1;
				if(empty($perm_group_ids[$digit]))
					unset($perm_group_ids[$digit]);
				
				return $perm_group_ids;

			} else {

				return FALSE;

			}
		}
	}

	function admin_perm_group_names_select($admin_id) {

		if($admin_id === 0)
			$admin_id = $this->session->userdata['ADMIN']['admin_id'];

		$this->db->select('admin_perm_grup_ids');
		$this->db->from('d_admin');
		$this->db->where('admin_id', $admin_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$admin = $query->row_array();

			$temp = substr($admin['admin_perm_grup_ids'], 1); // delete the first space
			$perm_group_ids = explode(", ", $temp); // get permissions array
			unset($temp);

			// check the last array
			// if empty, unset/delete it
			$digit = count($perm_group_ids) - 1;
			if(empty($perm_group_ids[$digit]))
				unset($perm_group_ids[$digit]);

			$group_name = '';
			foreach ($perm_group_ids as $perm_group_id) {
				$perm_group = $this->perm_group_select($perm_group_id);
				$group_name .= $perm_group['group_name']."<br />\n";
			}
			return $group_name;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get group permission array (2 dimensions) of an admin
	 * @param integer $admin_id
	 * @return array multiple group_id with properties (id, name, etc)
	 */

	function admin_perm_groups_select($admin_id) {

		if($admin_id === 0)
			$admin_id = $this->session->userdata['ADMIN']['admin_id'];

		$this->db->select('admin_perm_grup_ids');
		$this->db->from('d_admin');
		$this->db->where('admin_id', $admin_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$admin = $query->row_array();

			$temp = substr($admin['admin_perm_grup_ids'], 1); // delete the first space
			$perm_group_ids = explode(", ", $temp); // get permissions array
			unset($temp);

			// check the last array
			// if empty, unset/delete it
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

	/**
	 * Get plant array (2 dimensions) of an admin
	 * @param integer $admin_id
	 * @return array multiple group_id with properties (id, name, etc)
	 */

	function admin_plants_select($admin_id) {

		if($admin_id === 0)
			$admin_id = $this->session->userdata['ADMIN']['admin_id'];

		$this->db->select('plants');
		$this->db->from('d_admin');
		$this->db->where('admin_id', $admin_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$admin = $query->row_array();

			$temp = substr($admin['plants'], 1); // delete the first space
			$plants = explode(", ", $temp); // get permissions array
			unset($temp);
			sort($plants);

			// check the last array
			// if empty, unset/delete it
			$digit = count($plants) - 1;
			if(empty($plants[$digit]))
				unset($plants[$digit]);

			foreach ($plants as $plant) {
				$outlet = $this->m_general->sap_plant_select_by_id($plant);
				if ($outlet!=FALSE) $return[] = $outlet;
			}

			return $return;

		} else {
			return FALSE;
		}
	}
	
	function admin_plants_select_all($admin_id) {

		if($admin_id === 0)
			$admin_id = $this->session->userdata['ADMIN']['admin_id'];

		$this->db->select('plants, admin_selectall');
		$this->db->from('d_admin');
		$this->db->where('admin_id', $admin_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$admin = $query->row_array();
			$office_array = $this->m_general->office_plants();
			$temp = substr($admin['plants'], 1); // delete the first space
			$plants = explode(",", $admin['plants']); // get permissions array

			unset($temp);
			sort($plants);

			// check the last array
			// if empty, unset/delete it
			$digit = count($plants) - 1;
			if(empty($plants[$digit]))
				unset($plants[$digit]);

			unset($plant_permitted);
			$plant_permitted = Array();
			
			foreach ($plants as $plant) {
				$plant_permitted[trim($plant)] = trim($plant);
			}
			
			if (trim($admin['admin_selectall'])!='') {
				$comp_codes = explode(",", $admin['admin_selectall']);
				foreach ($comp_codes as $plant_type_id) {
					$plant_type_id = trim($plant_type_id);
					if ($plant_type_id=='') continue;
						$this->db->select('OUTLET');
					if ($plant_type_id=='OFFICE') {
						$this->db->from('m_outlet');
						$this->db->where_in('OUTLET', $office_array);
						$this->db->order_by("OUTLET", "asc");
					} else {
						$this->db->from('m_outlet');
						$this->db->where('COMP_CODE', $plant_type_id);
						$this->db->where_not_in('OUTLET', $office_array);
						$this->db->order_by("OUTLET", "asc");
					}
					
					$query = $this->db->get();

					if ($query->num_rows() > 0) {
						$admin_comps = $query->result_array();
						
						foreach ($admin_comps as $admin_comp=>$value){
							$plant_permitted[$admin_comps[$admin_comp]['OUTLET']]=$admin_comps[$admin_comp]['OUTLET'];
						}
					}
				}
			
			}
			
			foreach ($plant_permitted as $plant) {
				$outlet = $this->m_general->sap_plant_select_by_id_summary($plant);
				if ($outlet!=FALSE) {
					if (in_array($outlet['OUTLET'],$office_array)){
						$outlet['COMP_CODE']='1OFFICE';
						$outlet['jenisoutlet']='YBC Offices';
						$outlet['codeid']='1'.str_replace(' ','',$outlet['jenisoutlet']);
					} else {
						$outlet['codeid']=str_replace(' ','',$outlet['jenisoutlet']);
					}
					$return[$outlet['codeid'].'_'.$plant] = $outlet;
				}
			}
			ksort($return);
			return $return;

		} else {
			return FALSE;
		}
	}	

	/**
	 * Get group permission from group_id
	 * @param integer $group_id
	 * @return string group permission. Ex: aa20aa21aa22
	 */

	function admin_perm_select($group_id) {

		$this->db->select('group_perms');
		$this->db->from('d_perm_group');
		$this->db->where('group_id', $group_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			return $data['group_perms'];
		} else {
			return FALSE;
		}

	}

	/**
	 * Get permission code, which have patern: aa99.
	 * Where aa = alphabet, from aa to zz; 99 = number, from 01 to 99.
	 * Example: aa01, ab28, bd11.
	 * @param integer $group_id
	 * @return string permission code
	 */

	function perm_code_select($perm_name) {

		$this->db->select('category_code, perm_code');
		$this->db->from('r_perm');
		$this->db->join('r_perm_category', 'cat_id = category_id');
		$this->db->where('perm_name', $perm_name);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			return $data['category_code'].sprintf("%02s", $data['perm_code']);
		} else {
			return FALSE;
		}

	}

	/**
	 * Get permission category code, which have patern: aa.
	 * Where aa = alphabet, from aa to zz.
	 * Example: aa, ab, bd.
	 * @param integer $group_id
	 * @return string permission code
	 */

	function perm_category_code_select($perm_name) {

		$this->db->select('category_code');
		$this->db->from('r_perm_category');
		$this->db->where('category_name', $perm_name);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			return $data['category_code'];
		} else {
			return FALSE;
		}

	}

	/**
	 * Get the max value of group order
	 * @return integer
	 */

	function perm_group_order_max_select() {
		$this->db->select_max('group_order', 'max');
		$this->db->from('d_perm_group');

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			return $data['max'];
		} else {
			return FALSE;
		}
	}

	/**
	 * Get query of all permissions
	 * @return resource Query of all permissions.
	 */

	function perms_select_all() {
		$names = array(2,3,4,13,14,15,10014,10015,10016,10017,10021,10022,10023,10024,10025);
		$this->db->from('r_perm');
		$this->db->join('r_perm_category', 'cat_id = category_id');
		$this->db->where_in('cat_id',$names);
		$this->db->order_by('cat_id, perm_order');

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query;
		else
			return FALSE;
	}

	function perms_select_all1() {
		$names = array(2,3,4,13,14,15);
		$this->db->from('r_perm');
		$this->db->join('r_perm_category', 'cat_id = category_id');
		$this->db->where_in('cat_id',$names);
		$this->db->order_by('cat_id, perm_order');

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query;
		else
			return FALSE;
	}


	function perms_select_all2() {
		$names = array(10014,10015,10016,10017,10021,10022,10023,10024,10025);
		$this->db->from('r_perm');
		$this->db->join('r_perm_category', 'cat_id = category_id');
		$this->db->where_in('cat_id',$names);
		$this->db->order_by('cat_id, perm_order');

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query;
		else
			return FALSE;
	}

	/**
	 * Get query of one permisssion category
	 * @return resource Query object.
	 */
	
	function perms_select_by_category_id($cat_id) {
		$this->db->from('r_perm');
		$this->db->join('r_perm_category', 'cat_id = category_id');
		$this->db->where('cat_id', $cat_id);
		$this->db->order_by('cat_id, perm_order');

		$query = $this->db->get();
		echo $this->db->last_query();

		if($query->num_rows() > 0)
			return $query;
		else
			return FALSE;
	}

}