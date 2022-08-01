<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bomsubalt_model extends CI_Model {

	function getBomSubAltData($fromDate='', $toDate='', $status='', $whoIsLogin){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$this->db->distinct();
		$this->db->select("a.*,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_user_input) as created_by,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_user_approved) as approved_by,
		(SELECT dept FROM t_department WHERE dept_head_id = a.id_head_dept) as dept,
		(SELECT admin_realname FROM d_admin WHERE admin_username = a.category_approver) as category_approver,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_cost_control) as cost_control,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_head_dept) as head_dept");
		$this->db->from('t_bom_subalt_header a');
		$this->db->join('t_bom_subalt_detail b', 'a.id_bom_subalt_header = b.id_bom_subalt_header');
        
        if ($whoIsLogin['flag'] == '01') {
			$this->db->where('a.id_user_input', $whoIsLogin['id']);
		} elseif ($whoIsLogin['flag'] == '02') {
			$this->db->where_in('a.id_user_input', $whoIsLogin['users_dept']);
		} elseif ($whoIsLogin['flag'] == '0203') {
			$this->db->where_in('a.id_user_input', $whoIsLogin['users_dept']);
			$this->db->or_where('a.category_approver', $whoIsLogin['username']);
		} elseif ($whoIsLogin['flag'] == '03') {
			$this->db->where('a.category_approver', $whoIsLogin['username']);
		} elseif ($whoIsLogin['flag'] == '0304' || $whoIsLogin['flag'] == '04' || $whoIsLogin['flag'] == '0204') {
			//get all record
		} 
		
		if((!empty($fromDate)) || (!empty($toDate))){
			if( (!empty($fromDate)) || (!empty($toDate)) ) {
				$this->db->where("created_date BETWEEN '$fromDate' AND '$toDate'");
			} else if( (!empty($fromDate))) {
				$this->db->where("created_date >= '$fromDate'");
			} else if( (!empty($toDate))) {
				$this->db->where("created_date <= '$toDate'");
			}
		}

		$this->db->order_by('a.id_bom_subalt_header','desc');

		if((!empty($status))){
			$this->db->where('status', $status);
		}

		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getCategory(){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select('Code, Name');
		$SAP_MSI->from('@YBC_SUBALT_CAT');
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
			return FALSE;
		}
	}

	function getCatApproverName($code = '', $username = ''){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select('U_CatApprover as approver');
		$SAP_MSI->from('@YBC_SUBALT_CAT');
		if ($username != '') {
			$SAP_MSI->where('U_CatApprover', $username);
		} else {
			$SAP_MSI->where('Code', $code);
		}
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->row_array();
		}else{
			return FALSE;
		}
	}

	function showMatrialGroup(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->distinct();
        $SAP_MSI->select('ItmsGrpNam');
        $SAP_MSI->from('OITB t0');
		$SAP_MSI->join('OITM t1 with (NOLOCK)','t0.ItmsGrpCod = t1.ItmsGrpCod','inner');
        $SAP_MSI->where('t1.validFor', 'Y');

        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
	}

	function getAllDataItems($itmGrp = ''){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR, t0.ItemName as MAKTX, t0.ItmsGrpCod as DISPO, t0.InvntryUom as UNIT, t1.ItmsGrpNam as DSNAM, t1.U_PlusTaxCost as TAX');
        $SAP_MSI->from('OITM t0 with (NOLOCK)');
        $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
        $SAP_MSI->where('validFor', 'Y');
        $SAP_MSI->where('t0.InvntItem', 'Y');
        
		if($itmGrp != '' && $itmGrp != 'all'){
			$SAP_MSI->where('t1.ItmsGrpNam', $itmGrp);
		}
		
		$query = $SAP_MSI->get();
        
        if(($query)&&($query->num_rows() > 0)) {
            return $query->result_array();
        }else {
            return FALSE;
        }
	}

	function getDataItemSelected($itemSelect='', $category='') {
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);

		$SQL = "SELECT C.ItmsGrpNam as DSNAM, A.ChildNum as SAPLINE, A.Father as MATNR, B.ItemName as MAKTX, 
		(SELECT AA.Quantity FROM ITT1 AA WHERE AA.Code = A.Code AND AA.Father = A.Father and AA.ChildNum = A.ChildNum) as QTY, 
		(SELECT BB.InvntryUom FROM OITM BB with (NOLOCK) WHERE BB.ItemCode = A.Code) as UNIT, dbo.f_bomrollup(A.Father) as LastPrice
		FROM ITT1 A 
		INNER JOIN OITM B with (NOLOCK) ON A.Father = B.ItemCode
		INNER JOIN OITB C ON C.itmsgrpcod = B.ItmsGrpCod
		INNER JOIN OITM D with (NOLOCK) ON A.Code = D.ItemCode
		where A.code = '$itemSelect'
		and C.U_Category = '$category'";

		$query = $SAP_MSI->query($SQL);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
    }
    
    function getNewUOMLastPriceItemsDetail($itemSelect=''){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('InvntryUom as UNIT, LstEvlPric as LastPrice'); 
        $SAP_MSI->from('OITM A with (NOLOCK)');
        $SAP_MSI->join('OITB B', 'B.itmsgrpcod = A.ItmsGrpCod', 'inner');
        $SAP_MSI->where('A.ItemCode',$itemSelect);

        $last = $SAP_MSI->get();
        
        if ($last->num_rows() > 0) {
            return $last->row_array();
        } 
	}
	
	function getUnitPrice($itemRM='', $father='', $line='')
	{
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);

		$SQL = "SELECT SUM(A.Quantity * C.LstEvlPric) as UnitPrice
		FROM ITT1 A 
		INNER JOIN OITM B with (NOLOCK) ON A.father = B.ItemCode
		INNER JOIN OITM C with (NOLOCK) ON A.Code = C.ItemCode
		WHERE A.Code = '$itemRM' AND A.father = '$father' AND A.ChildNum = '$line'";

		$query = $SAP_MSI->query($SQL);
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
	}

	function getDifferentImpact($arrayItemsCode)
	{
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);

		$array = implode(',', $arrayItemsCode);

		$SQL = "WITH [BOM_Recursive] (FGItmCode,FgItmName,ChildNum,RmItemCode,RmItemName,Quantity,[CurrentTotalCost], [RecursionLevel]) 
		-- CTE name and columns
		AS (

			select father as FGItmCode, b.ItemName as FgItmName, a.ChildNum, a.Code RmItemCode, D.ItemName RmItemName,
			Quantity,
			dbo.f_bomrollup(a.Father)
			[CurrentTotalCost], 0 [RecursionLevel]
			from ITT1 A 
			INNER JOIN OITM B ON A.Father = B.ItemCode
			INNER JOIN OITM d ON A.Code = d.ItemCode
			where a.code in ($array)

				UNION ALL

				SELECT c.ItemCode, c.ItemName, b.ChildNum, cte.RmItemCode RmItemCode, cte.RmItemName RmItemName,
				b.Quantity Quantity,
				dbo.f_bomrollup(b.Father) [CurrentTotalCost],
				[RecursionLevel] + 1 
				-- Join recursive member to anchor
				FROM [BOM_Recursive] cte
					INNER JOIN itt1 b 
					inner join oitm c on b.[Father]=c.itemcode 
					INNER JOIN OITM e ON b.Code = e.ItemCode
					on b.Code = cte.FGItmCode
				)

		SELECT d.ItmsGrpNam, FGItmCode as FGCodeImpact, FgItmName as FGNameImpact, ChildNum as line, RmItemCode as FGCode,
		RmItemName as FGName, Quantity as oldQty, C.InvntryUom as oldUOM, [CurrentTotalCost] as oldPrice, RecursionLevel
		FROM [BOM_Recursive] a
		inner join oitm c on a.FGItmCode = c.ItemCode
		inner join oitb d on d.ItmsGrpCod = c.ItmsGrpCod
		order by 4,7";

		$query = $SAP_MSI->query($SQL);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	function selectIDPlant($id_outlet, $created_date="") {

		$this->db->select_max('id_bom_subalt_plant');
		$this->db->from('t_bom_subalt_header');
		$this->db->where('plant', $id_outlet);
		$this->db->where('created_date', $created_date);

		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)) {
			$bom_subalt = $query->row_array();
			$id_bom_subalt_outlet = $bom_subalt['id_bom_subalt_plant'] + 1;
		} else {
			$id_bom_subalt_outlet = 1;
		}

		return $id_bom_subalt_outlet;
	}

	function insertHeaderBomSubAlt($data) {
		if($this->db->insert('t_bom_subalt_header', $data)) {
			return $this->db->insert_id();
		}else{
			return FALSE;
		}
	}

	function insertDetailBomSubAlt($data) {
        if ($this->db->insert('t_bom_subalt_detail', $data)) {
			return $this->db->insert_id();
        } else {
            return FALSE;
        }
	}

	function deleteBomSubAltHeader($id){
		$data = $this->selectBomSubAltHeader($id);
		$status = $data['status'];
		if ($status!=2) {
			if($this->selectBomSubAltDetailForDelete($id)){
				$this->db->where('id_bom_subalt_header', $id);
				if($this->db->delete('t_bom_subalt_header'))
					return TRUE;
				else
					return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	function selectBomSubAltHeader($id){
		$this->db->from('t_bom_subalt_header');
		$this->db->where('id_bom_subalt_header', $id);
		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->row_array();
		}else{
			return FALSE;
		}
	}

	function selectBomSubAltDetailForDelete($id) {
		$this->db->where('id_bom_subalt_header', $id);
		if($this->db->delete('t_bom_subalt_detail'))
			return TRUE;
		else
			return FALSE;
	}

	function selectBomSubAltDetail($id){
		$this->db->from('t_bom_subalt_detail');
		$this->db->where('id_bom_subalt_header', $id);
		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
			return FALSE;
		}
	}

	function updateDataBomSubAltHeader($bom_subalt_header){
		$update = array(
			'bom_type' => $bom_subalt_header['bom_type'],
			'item_group_header_old' => $bom_subalt_header['item_group_header_old'],
			'raw_mat_code_old' => $bom_subalt_header['raw_mat_code_old'],
			'raw_mat_name_old' => $bom_subalt_header['raw_mat_name_old'],
			'item_group_header_new' => $bom_subalt_header['item_group_header_new'],
			'raw_mat_code_new' => $bom_subalt_header['raw_mat_code_new'],
			'raw_mat_name_new' => $bom_subalt_header['raw_mat_name_new'],
			'category_code' => $bom_subalt_header['category_code'],
			'category_name' => $bom_subalt_header['category_name'],
			'category_approver' => $bom_subalt_header['category_approver'],
			'lastmodified' => $bom_subalt_header['lastmodified']
		);
		if ($bom_subalt_header['flag'] == 2) {
			$update['status'] = $bom_subalt_header['status'];
			$update['approved_user_date'] = $bom_subalt_header['approved_user_date'];
			$update['id_user_approved'] = $bom_subalt_header['id_user_approved'];
			$update['status_head'] = $bom_subalt_header['status_head'];
			$update['status_cat_approver'] = $bom_subalt_header['status_cat_approver'];
			$update['status_cost_control'] = $bom_subalt_header['status_cost_control'];
			if ($bom_subalt_header['status_head'] == 2) {
                $update['approved_head_dept_date'] = $bom_subalt_header['approved_head_dept_date'];
                $update['id_head_dept'] = $bom_subalt_header['id_head_dept'];
			}
		} elseif ($bom_subalt_header['flag'] == 3) {
			$update['status'] = $bom_subalt_header['status'];
			$update['status_head'] = $bom_subalt_header['status_head'];
			$update['approved_head_dept_date'] = $bom_subalt_header['approved_head_dept_date'];
			$update['id_head_dept'] = $bom_subalt_header['id_head_dept'];
			if (isset($bom_subalt_header['head_dept_username'])) {
				$update['status_cat_approver'] = $bom_subalt_header['status_cat_approver'];
				$update['approved_cat_approver_date'] = $bom_subalt_header['approved_cat_approver_date'];
			}
		} elseif ($bom_subalt_header['flag'] == 4) {
			$update['status_cat_approver'] = $bom_subalt_header['status_cat_approver'];
			$update['approved_cat_approver_date'] = $bom_subalt_header['approved_cat_approver_date'];
		} elseif ($bom_subalt_header['flag'] == 5) {
			$update['status_cost_control'] = $bom_subalt_header['status_cost_control'];
			$update['approved_cost_control_date'] = $bom_subalt_header['approved_cost_control_date'];
			$update['id_cost_control'] = $bom_subalt_header['id_cost_control'];
		}
		
		$this->db->where('id_bom_subalt_header', $bom_subalt_header['id_bom_subalt_header']);
        if($this->db->update('t_bom_subalt_header', $update))
			return TRUE;
		else
			return FALSE;
	}
	
	function reject($reject){
		if ($reject['whosRejectFlag'] == 1) {
			$update = array(
				'status' => 1,
				'status_head' => $reject['status_head'],
				'reject_reason' => $reject['reject_reason'],
				'id_user_approved' => $reject['id_user_approved'],
				'rejected_head_dept_date' => $reject['rejected_head_dept_date']
			);
		} elseif ($reject['whosRejectFlag'] == 2) {
			$update = array(
				'status' => 1,
				'status_head' => 1,
				'status_cat_approver' => $reject['status_cat_approver'],
				'reject_reason' => $reject['reject_reason'],
				'rejected_cat_approver_date' => $reject['rejected_cat_approver_date']
			);
		} elseif ($reject['whosRejectFlag'] == 3) {
			$update = array(
				'status' => 1,
				'status_head' => 1,
				'status_cat_approver' => 1,
				'status_cost_control' => $reject['status_cost_control'],
				'reject_reason' => $reject['reject_reason'],
				'id_cost_control' => $reject['id_cost_control'],
				'rejected_cost_control_date' => $reject['rejected_cost_control_date']
			);
		}
		$this->db->where('id_bom_subalt_header', $reject['id_bom_subalt_header']);
        if($this->db->update('t_bom_subalt_header', $update))
			return TRUE;
		else
			return FALSE;
	}

	function updateNoBomSubAlt($no, $id){
		$update = array(
			'bom_subalt_no' => $no
		);
		$this->db->where('id_bom_subalt_header', $id);
        if($this->db->update('t_bom_subalt_header', $update))
			return TRUE;
		else
			return FALSE;
	}

	function getDeptUserLogin($user_id){
		$this->db->select('dept');
		$this->db->from('t_department a');
		$this->db->join('d_admin b', 'a.dept_head_id = b.dept_manager');
		$this->db->where('b.admin_id', $user_id);

		$query = $this->db->get();
		return $query->row_array();
	}

    //for dashboard
	function getAllBOMSubAltData($whoIsLogin, $flag, $status = ''){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$this->db->distinct();
		$this->db->select('a.id_bom_subalt_header');
		$this->db->from('t_bom_subalt_header a');
		$this->db->join('t_bom_subalt_detail b', 'a.id_bom_subalt_header = b.id_bom_subalt_header');
		if ($flag == '01' && $status == 1) {
			$this->db->where('a.id_user_input', $whoIsLogin['id']);
			$this->db->where('a.status', 1);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '01' && $status == 2) {
			$this->db->where('a.id_user_input', $whoIsLogin['id']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		}
		elseif ($flag == '01' && $status == 3) {
			$this->db->where('a.id_user_input', $whoIsLogin['id']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		}
		elseif ($flag == '01' && $status == 4) {
			$this->db->where('a.id_user_input', $whoIsLogin['id']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 2);
			$this->db->where('a.status_cost_control', 1);
		}
		elseif ($flag == '02' && $status == 1) {
			$this->db->where_in('a.id_user_input', $whoIsLogin['users_dept']);
			$this->db->where('a.status', 1);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '02' && $status == 2) {
			$this->db->where_in('a.id_user_input', $whoIsLogin['users_dept']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '02' && $status == 3) {
			$this->db->where_in('a.id_user_input', $whoIsLogin['users_dept']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '02' && $status == 4) {
			$this->db->where_in('a.id_user_input', $whoIsLogin['users_dept']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 2);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '03' && $status == 1) {
			$this->db->where('a.category_approver', $whoIsLogin['username']);
			$this->db->where('a.status', 1);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '03' && $status == 2) {
			$this->db->where('a.category_approver', $whoIsLogin['username']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '03' && $status == 3) {
			$this->db->where('a.category_approver', $whoIsLogin['username']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '03' && $status == 4) {
			$this->db->where('a.category_approver', $whoIsLogin['username']);
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 2);
			$this->db->where('a.status_cost_control', 1);
		}
		elseif ($flag == '04' && $status == 1) {
			$this->db->where('a.id_user_input', $whoIsLogin['id']);
			$this->db->where('a.status', 1);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '04' && $status == 2) {
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 1);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '04' && $status == 3) {
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 1);
			$this->db->where('a.status_cost_control', 1);
		} 
		elseif ($flag == '04' && $status == 4) {
			$this->db->where('a.status', 2);
			$this->db->where('a.status_head', 2);
			$this->db->where('a.status_cat_approver', 2);
			$this->db->where('a.status_cost_control', 1);
		} 

		$query = $this->db->get();
		return $query->num_rows();
	}
	//for dashboard
}