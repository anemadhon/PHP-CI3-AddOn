<?php

class Auth {

	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('master/permission_model', 'm_perm');
		$this->CI->load->model('transaksi1/stock_model', 'st_model');
		$this->CI->load->model('master/department_model', 'divisi');
		$this->CI->load->model('transaksi1/productcosting_model', 'pc');	
		$this->CI->load->model('transaksi1/bomsubalt_model', 'bomsa');
	}
	
	function is_logged_in() {
		
		if ($this->CI->session) {

			if ($this->CI->session->userdata('logged_in')){
				return TRUE;
			}else{
				return FALSE;
			}

		} else {
			return FALSE;
		}
		
	}

	function is_have_perm($perm_name, $admin_id = 0) {
		$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
		if($admin_id === 0)
			$admin_id = $this->CI->session->userdata['ADMIN']['admin_id'];

			

		if(is_array($perm_name)) {

			foreach($perm_name as $perm_value) {

				if(empty($perm_value))
					return TRUE;


				$perm_group_ids = $this->CI->m_perm->admin_perm_group_ids_select($admin_id);
				
				foreach($perm_group_ids as $perm_group_id) {
					$admin_perm = $this->CI->m_perm->admin_perm_select($perm_group_id);

					$perm_code = $this->CI->m_perm->perm_code_select($perm_value);
	
					@$perm_have = substr_count($admin_perm, $perm_code);
	

					if($perm_have || ($admin_perm == "*"))
						return TRUE;
				}


			}
			return FALSE;

		} else {

			$perm_group_ids = $this->CI->m_perm->admin_perm_group_ids_select($admin_id);

			
				
			
			foreach($perm_group_ids as $perm_group_id) {
				
				$admin_perm = $this->CI->m_perm->admin_perm_select($perm_group_id);
				
				$perm_code = $this->CI->m_perm->perm_code_select($perm_name);
				
				@$perm_have = substr_count($admin_perm, $perm_code);


				if($perm_have || ($admin_perm == "*"))
					return TRUE;

			}

			return FALSE;

		}

	}

	function perm_code_merge($perm_code) {
		$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
		$temp = array_keys($perm_code);

		foreach($temp as $key1 => $value1) {
			foreach($perm_code[$value1] as $key2 => $value2) {
				if(!is_array($value2))
					$return[] = $value2;
			}
		}
		return $return;
	}
	
	function perm_group_detail($group_id) {
		$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
		if(!$perm_group = $this->CI->m_perm->perm_group_select($group_id))
    	return FALSE;

		if(!$perms = $this->CI->m_perm->perms_select_all())
			return FALSE;

		$detail = array();
		// will be $detail[$i][$j]
		// $i and $j declared below, based on content of $perms

		foreach ($perms->result_array() as $perm) {

			
			$i = $perm['cat_id']; // array dimension 1
			$j = $perm['perm_order']; // array dimension 2

			// define GROUP permission
			if(!isset($detail[$i][0])) {
				$detail[$i][0] = $perm;
				$detail[$i][0]['category_name'] = $perm['category_name'];
				$detail[$i][0]['category_descr'] = $this->CI->lang->line('perm_opt_category_'.$perm['category_name']);
				$detail[$i][0]['category_code'] = $perm['category_code'];
			}

			// define permission
			if(substr_count($perm_group['group_perms'], $perm['category_code'].sprintf("%02s", $perm['perm_code']))) {
				$detail[$i][$j] = $perm;
				$detail[$i][$j]['perm_descr'] = $this->CI->lang->line('perm_opt_'.$perm['perm_name']);
			}

		}

		return $detail;

	}

	function perm_group_detail_show($group_id) {
		$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
		$return = '';

		$detail = $this->perm_group_detail($group_id);

		foreach ($detail as $key1 => $detail1) {

    	$count = count($detail1);

			if($count > 1) {
				$j = 1; // counter to match with last of $detail1
				foreach ($detail1 as $key2 => $detail2) {

					if($key2 == 0) {
						$return .= "<strong>".$detail2['category_descr']."</strong>\n";
						$return .= "<ul>\n";
					} else {
						$return .= "<li>".$detail2['perm_descr']."</li>\n";
					}

					if($j == ($count)) {
						$return .= "</ul>\n";
					}

					$j++;
				}
			}

		}

		return $return;

	}

	function startup() {
		$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
		$startup = $this->CI->session->userdata('startup');

		if(!empty($startup)) {

			$this->CI->session->unset_userdata('startup');
			$this->CI->session->unset_userdata('password');

		}
	}

	function is_have_perm_category($perm_name, $admin_id = 0) {
		if($admin_id === 0)
			$admin_id = $this->CI->session->userdata['ADMIN']['admin_id'];
	
		if(is_array($perm_name)) {
			foreach($perm_name as $perm_value) {
				if(empty($perm_value))
					return TRUE;
	
					$perm_groups_id = $this->CI->m_perm->admin_perm_group_ids_select($admin_id);

					foreach($perm_groups_id as $perm_group_id) {
						$admin_perm = $this->CI->m_perm->admin_perm_select($perm_group_id);
						$perm_code = $this->CI->m_perm->perm_category_code_select($perm_value);
						@$perm_have = substr_count($admin_perm, $perm_code);

						if($perm_have || ($admin_perm == "*"))
							return TRUE;
					}
	
				}
	
				return FALSE;
	
			} else {
	
				$perm_groups_id = $this->CI->m_perm->admin_perm_group_ids_select($admin_id);
	
				foreach($perm_groups_id as $perm_group_id) {
					$admin_perm = $this->CI->m_perm->admin_perm_select($perm_group_id);
					$perm_code = $this->CI->m_perm->perm_category_code_select($perm_name);
					@$perm_have = substr_count($admin_perm, $perm_code);
	
					if($perm_have || ($admin_perm == "*"))
						return TRUE;
				}
	
				return FALSE;
	
			}
	
		}

		function is_freeze() {
			$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
	
			$object['opname_header']['freeze'] = $this->CI->st_model->freeze();
			$arr_ids = explode(", ",$this->CI->session->userdata['ADMIN']['admin_perm_grup_ids']);
			
			$ids = 0;
			$freeze = [];
			$mgrState = [];
			
			foreach($arr_ids as $val){
				if($val == 14){
					$ids = $val;
				}elseif($val == 10064){
					$ids = $val;
				}
			}
	
			if ($object['opname_header']['freeze']){
				foreach ($object['opname_header']['freeze'] as $is_freeze) {
					$freeze[] = $is_freeze['freeze'];
					$mgrState[] = $is_freeze['am_approved'].$is_freeze['rm_approved'];
				}
			} 
			
			$isFreeze['stock_opname']['is_freeze'] = count($freeze) > 0 && in_array('Y',$freeze) ? 1 : 0;
			$isFreeze['stock_opname']['is_reject'] = count($mgrState) > 0 && (in_array('01',$mgrState) || in_array('11',$mgrState) || in_array('21',$mgrState) || in_array('10',$mgrState) || in_array('12',$mgrState)) ? 1 : 0;
			$isFreeze['stock_opname']['is_mgr'] = $ids;
	
			return $isFreeze['stock_opname'];
			
		}

		function is_head_dept() {
			$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
			$user_id = $this->CI->session->userdata['ADMIN']['admin_id'];

			$object['head'] = $this->CI->divisi->getDivisibyHead($user_id);	
			if ($object['head']) {
				$object['user'] = $this->CI->divisi->getUserFromHeadDept($object['head']['dept_head_id']);	
				$object['username'] = $this->CI->divisi->getHeadDeptUsername($object['head']['dept_head_id']);	
				if ($object['user']) {
					$object['costing']['head_dept'] = $object['user'][0]['dept_manager'];
					$object['costing']['head_dept_username'] = $object['username']['admin_username'];
					$object['costing']['users'] = $object['user'];
					
					return $object['costing'];
				}
			}
		}

		function who_is_login(){
			$this->CI->lang->load('g_perm', $this->CI->session->userdata('lang_name'));
			$dept = $this->CI->pc->getDeptUserLogin($this->CI->session->userdata['ADMIN']['admin_id']);
			$catAppr = $this->CI->pc->getCatApprover($this->CI->session->userdata['ADMIN']['admin_username']);
			$catApprBOMSA = $this->CI->bomsa->getCatApproverName('', $this->CI->session->userdata['ADMIN']['admin_username']);

			$isUser = array();
			$userLogin['flag'] = '01';
			$userLogin['username'] = '';
			$userLogin['users_dept'] = '';
	
			if ($this->is_head_dept()['head_dept'] == $this->CI->session->userdata['ADMIN']['admin_id'] && strtolower($dept['dept']) == 'cost control') {
				$userLogin['flag'] = '0204';
				foreach ($this->is_head_dept()['users'] as $user) {
					$isUser[] = $user['admin_id'];
				}
				$userLogin['users_dept'] = $isUser;
			} elseif ($this->is_head_dept()['head_dept'] == $this->CI->session->userdata['ADMIN']['admin_id'] && $catAppr['approver']) { //checking HOD & Cat Approver Costing
				$userLogin['flag'] = '0203';
				$userLogin['username'] = $catAppr['approver'];
				foreach ($this->is_head_dept()['users'] as $user) {
					$isUser[] = $user['admin_id'];
				}
				$userLogin['users_dept'] = $isUser;
			} 
			elseif ($this->is_head_dept()['head_dept'] == $this->CI->session->userdata['ADMIN']['admin_id'] && $catApprBOMSA['approver']) { //checking HOD & Cat Approver BOM
				$userLogin['flag'] = '0203';
				$userLogin['username'] = $catApprBOMSA['approver'];
				foreach ($this->is_head_dept()['users'] as $user) {
					$isUser[] = $user['admin_id'];
				}
				$userLogin['users_dept'] = $isUser;
			}
			elseif ($this->is_head_dept()['head_dept'] == $this->CI->session->userdata['ADMIN']['admin_id']) { //checking HOD
				$userLogin['flag'] = '02';
				foreach ($this->is_head_dept()['users'] as $user) {
					$isUser[] = $user['admin_id'];
				}
				$userLogin['users_dept'] = $isUser;
			} elseif ($catAppr['approver'] && strtolower($dept['dept']) == 'cost control') { //checking Cat Approver Costing && Cost Control
				$userLogin['flag'] = '0304';
				$userLogin['username'] = $catAppr['approver'];
			} 
			elseif ($catApprBOMSA['approver'] && strtolower($dept['dept']) == 'cost control') { //checking Cat Approver BOMSA && Cost Control
				$userLogin['flag'] = '0304';
				$userLogin['username'] = $catApprBOMSA['approver'];
			} 
			elseif ($catAppr['approver']) { //checking Cat Approver Costing
				$userLogin['flag'] = '03';
				$userLogin['username'] = $catAppr['approver'];
			} 
			elseif ($catApprBOMSA['approver']) { //checking Cat Approver BOM
				$userLogin['flag'] = '03';
				$userLogin['username'] = $catApprBOMSA['approver'];
			}
			elseif (strtolower($dept['dept']) == 'cost control') { //checking Cost Control
				$userLogin['flag'] = '04';
			}
	
			$userLogin['id'] = $this->CI->session->userdata['ADMIN']['admin_id'];
	
			return $userLogin;
		}

}