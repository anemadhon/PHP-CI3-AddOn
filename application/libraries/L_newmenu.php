<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class L_newmenu {
    protected $CI;

	public function __construct(){
		$this->CI =& get_instance();
	}
    
    function getMenu(){
        $menu = array(
            101 =>	array
                    (
                        'text'		=> 	'Purchasing',
                        'mnclass'	=> 	'mn_saptr',                        
					    'class'		=> 	'nav-link',
                        'show'		=>	TRUE,
                        'parent'	=>	0,
                        'perms'		=>	array
                            (
                                'purch',
                            ),
                    ),
                    
                    10101 =>	array
                        (
                            'text'		=> 	'Purchase Request(PR) ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/purchase_request'),
                            'show'		=>	TRUE,
                            'parent'	=>	101,
                            'perms'		=>	array
                                (
                                    'purch_pr',
                                ),
                        ),

                    10102 =>	array
                        (
                            'text'		=> 	'In PO from Vendor',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/pofromvendor'),
                            'show'		=>	TRUE,
                            'parent'	=>	101,
                            'perms'		=>	array
                                (
                                    'purch_grpo',
                                ),
                        ),

                    10103 =>	array
                        (
                            'text'		=> 	'Goods Receipt Non PO  ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/grnopo'),
                            'show'		=>	TRUE,
                            'parent'	=>	101,
                            'perms'		=>	array
                                (
                                    'purch_grnonpo',
                                ),
                        ),
                        
            102 =>	array
                    (
                        'text'		=> 	'Inventory',
                        'mnclass'	=> 	'mn_saptr',                        
                        'class'		=> 	'nav-link',
                        'show'		=>	TRUE,
                        'parent'	=>	0,
                        'perms'		=>	array
                            (
                                'inv',
                            ),
                    ),

                    10201 =>	array
                        (
                            'text'		=> 	'Store Room Request (SR)',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi2/sr'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_stdstock',
                                ),
                        ),
                    
                    10202 =>	array
                        (   
                            'text'		=> 	'Good Receipt From Central Kitchen ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/grfromkitchensentul'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_grpodlv',
                                ),
                        ),
                        
                    10203 =>	array
                        (
                            'text'		=> 	'Transfer Out Inter Outlet',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/transferoutinteroutlet'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_gistonew',
                                ),
                        ),
                        
                    10204 =>	array
                        (
                            'text'		=> 	'Transfer In Inter Outlet',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/transferininteroutlet'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_grsto',
                                ),
                        ),
                        
                    10205 =>	array
                        (
                            'text'		=> 	'Retur Out',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/returnout'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_gisto',
                                ),
                        ),
                        
                    10206 =>	array
                        (
                            'text'		=> 	'Retur In',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/returnin'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_retin',
                                ),
                        ),
                    
                    10207 =>	array
                        (
                            'text'		=> 	'Good Issue', 
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/goodissue'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_issue',
                                ),
                        ),
                    
                    10208 =>	array
                        (
                            'text'		=> 	'Spoiled / Breakage / Lost ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/spoiled'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_waste',
                                ),
                        ),

                    10209 =>	array
                        (
                            'text'		=> 	'Stock Opname ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/stock'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_stockopname',
                                ),
                        ),

                    10210 =>	array
                        (
                            'text'		=> 	'Sentul Entry In ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/sentulentry_in'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_sentulin',
                                ),
                        ),

                    10211 =>	array
                        (
                            'text'		=> 	'Sentul Entry Out ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/sentulentry_out'),
                            'show'		=>	TRUE,
                            'parent'	=>	102,
                            'perms'		=>	array
                                (
                                    'inv_sentulout',
                                ),
                        ),
                    10212 =>    array
                        (
                            'text'      =>  'Product Costing',
                            'mnclass'   =>  'mn_trans',
                            'link'      =>  site_url('transaksi1/productcosting'),
                            'show'      =>  TRUE,
                            'parent'    =>  102,
                            'perms'     =>  array
                                (
                                    'inv_productcosting',
                                ),
                        ),
                    10213 =>    array
                        (
                            'text'      =>  'BOM Subtitution & Alternative',
                            'mnclass'   =>  'mn_trans',
                            'link'      =>  site_url('transaksi1/bomsubalt'),
                            'show'      =>  TRUE,
                            'parent'    =>  102,
                            'perms'     =>  array
                                (
                                    'inv_bomsubalt',
                                ),
                        ),

            103 =>	array
                    (
                        'text'		=> 	'Production',
                        'mnclass'	=> 	'mn_saptr',                        
                        'class'		=> 	'nav-link',
                        'show'		=>	TRUE,
                        'parent'	=>	0,
                        'perms'		=>	array
                            (
                                'prod',
                            ),
                    ),

                    10301 =>	array
                        (
                            'text'		=> 	'Cake Cutting',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi2/whole'),
                            'show'		=>	TRUE,
                            'parent'	=>	103,
                            'perms'		=>	array
                                (
                                    'prod_twts',
                                ),
                        ),

                    10302 =>	array
                        (
                            'text'		=> 	'Work Order ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/wo'),
                            'show'		=>	TRUE,
                            'parent'	=>	103,
                            'perms'		=>	array
                                (
                                    'prod_tpaket',
                                ),
                        ),

                    10303 =>	array
                        (
                            'text'		=> 	'Disassembly ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/disassembly'),
                            'show'		=>	TRUE,
                            'parent'	=>	103,
                            'perms'		=>	array
                                (
                                    'prod_tdisassembly',
                                ),
                        ),

                    10304 =>	array
                        (
                            'text'		=> 	'Work Order - POS ',
                            'mnclass'	=> 	'mn_trans',
                            'link'		=> 	site_url('transaksi1/wopos'),
                            'show'		=>	TRUE,
                            'parent'	=>	103,
                            'perms'		=>	array
                                (
                                    'prod_twopos',
                                ),
                        ),
                    

            104 =>	array
                    (
                        'text'		=> 	'Master Data',
                        'mnclass'	=> 	'mn_saptr',                        
                        'class'		=> 	'nav-link',
                        'show'		=>	TRUE,
                        'parent'	=>	0,
                        'perms'		=>	array
                            (
                                'masterdata',
                            ),
                    ),

                    10401 =>	array
                        (
                            'text'		=> 	'User Management ',  
                            'mnclass'	=> 	'mn_master',
                            'link'		=> 	site_url('master/manajemen'),
                            'show'		=>	TRUE,
                            'parent'	=>	104,
                            'perms'		=>	array
                                (
                                    'masterdata_admin',
                                ),
                        ),

                    10402 =>	array
                        (
                            'text'		=> 	'User Authorization',  
                            'mnclass'	=> 	'mn_master',
                            'link'		=> 	site_url('master/akses'),
                            'show'		=>	TRUE,
                            'parent'	=>	104,
                            'perms'		=>	array
                                (
                                    'masterdata_perm_group',
                                ),
                        ),
                    
                    10405 =>	array
                        (
                            'text'		=> 	'Set Up Head Department',  
                            'mnclass'	=> 	'mn_master',
                            'link'		=> 	site_url('master/hdsetup'),
                            'show'		=>	TRUE,
                            'parent'	=>	104,
                            'perms'		=>	array
                                (
                                    'masterdata_head_department',
                                ),
                        ),

                    10403 =>	array
                        (
                            'text'		=> 	'Integration Log',  
                            'mnclass'	=> 	'mn_master ',
                            'link'		=> 	site_url('master/integration'),
                            'show'		=>	TRUE,
                            'parent'	=>	104,
                            'perms'		=>	array
                                (
                                    'masterdata_posisi',
                                ),
                        ),

                    10404 =>	array
                        (
                            'text'		=> 	'Master BOM',  
                            'mnclass'	=> 	'mn_master ',
                            'link'		=> 	site_url('master/bom'),
                            'show'		=>	TRUE,
                            'parent'	=>	104,
                            'perms'		=>	array
                                (
                                    'masterdata_bom',
                                ),
                        ),

            105 =>	array
                    (
                        'text'		=> 	'Report Summary',
                        'mnclass'	=> 	'mn_saptr',                        
                        'class'		=> 	'nav-link',
                        'show'		=>	TRUE,
                        'parent'	=>	0,
                        'perms'		=>	array
                            (
                                'report',
                            ),
                    ),

                    10501 =>	array
                        (
                            'text'		=> 	'On Hand Report',  
                            'mnclass'	=> 	'mn_report',
                            'link'		=> 	site_url('report/onhand'),
                            'show'		=>	TRUE,
                            'parent'	=>	105,
                            'perms'		=>	array
                                (
                                    'report_pastry',
                                ),
                        ),

                    10502 =>	array
                        (
                            'text'		=> 	'Inventory Audit Report',
                            'mnclass'	=> 	'mn_report',
                            'link'		=> 	site_url('report/inventory'),
                            'show'		=>	TRUE,
                            'parent'	=>	105,
                            'perms'		=>	array
                                (
                                    'report_onhand',
                                ),
                        ),

                    10503 =>	array
                        (
                            'text'		=> 	'Good Issue Report',
                            'mnclass'	=> 	'mn_report',
                            'link'		=> 	site_url('report/goodissue'),
                            'show'		=>	TRUE,
                            'parent'	=>	105,
                            'perms'		=>	array
                                (
                                    'report_gi',
                                ),
                        ),
                    
                    10504 =>	array
                        (
                            'text'		=> 	'Work Order Report',
                            'mnclass'	=> 	'mn_report',
                            'link'		=> 	site_url('report/wo'),
                            'show'		=>	TRUE,
                            'parent'	=>	105,
                            'perms'		=>	array
                                (
                                    'report_wo',
                                ),
                        ),
                        

            
        );
        return $menu;
    }

    function create_menu($menu, $childs=1){
        $out = '';
		foreach ($menu as $key => $value) 
		{
			if (is_array($menu[$key])) { //must be by construction but let's keep the errors home
				if ($menu[$key]['parent'] == 0) {
					if ( empty($menu[$key]['perms']) || $this->CI->auth->is_have_perm_category($menu[$key]['perms'])) {
						if (!empty($menu[$key]['mnclass'])) {
							if ($menu[$key]['mnclass']=="mn_home")
								$out .= '<li id="'.$menu[$key]['mnclass'].'">';
							elseif ($menu[$key]['mnclass']=="mn_myjag")
								$out .= '<li id="'.$menu[$key]['mnclass'].'">';
							else
								$out .= '<li class="nav-item nav-item-submenu">';
						}
						else
							$out .= '<li>';
							
						$dirclass="";
						if (!empty($menu[$key]['class']))
							$dirclass = ' class="'.$menu[$key]['class'].'" ';

						if (!empty($menu[$key]['link']))
							$out .= '<a href="'.$menu[$key]['link'].'" '.$dirclass.'>';
						else
							$out .= '<a href="#" '.$dirclass.'>';

						$out .= '<span>'.$menu[$key]['text'].'</span>';
                        $out .= '</a>';
                        $out .= "\n\t\t"."\n\t\t".'<ul class="nav nav-group-sub" data-submenu-title='.$menu[$key]['text'].'>'."\n";

						if($childs)
							$out .= $this->get_childs($menu, $key);
						$out .= '</li>'."\r\n";
					}
				}
			} else {
				die (sprintf('menu number %s must be an array', $key));
			}
		}

		return $out;
    }

    function get_childs($menu, $el_id) {

		$has_subcats = FALSE;
		$out = '';

        
		foreach ($menu as $key => $value) {
			if ( $menu[$key]['parent'] == $el_id ) {
				if (empty($menu[$key]['perms']) || $this->CI->auth->is_have_perm($menu[$key]['perms']) || $this->CI->auth->is_have_perm_category($menu[$key]['perms'])) {
					$has_subcats = TRUE;
					$add_class = ''; 
					$mn_subtitle=' class="nav-item"';
					if (@$menu[$key]['class']=="mn_subtitle") $mn_subtitle= ' class="mn_subtitle" ';
					
					if (!empty($menu[$key]['mnclass'])){
						if ($menu[$key]['mnclass']=="mn_home")
							$out .= '<li id="'.$menu[$key]['mnclass'].'"'.$mn_subtitle.'>';
						elseif ($menu[$key]['mnclass']=="mn_myjag")
							$out .= '<li id="'.$menu[$key]['mnclass'].'"'.$mn_subtitle.'>';
						else
							$out .= '<li'.$mn_subtitle.'>';
					}
					else
						$out .= '<li'.$mn_subtitle.'>';
					if (@$menu[$key]['class']=="mn_subtitle") $out .='<span>';
						
					$dirclass=' class="nav-link"';
					if (!empty($menu[$key]['class']))
						$dirclass = ' class="'.$menu[$key]['class'].'" ';

					if (!empty($menu[$key]['link']))
						$out .= '<a href="'.$menu[$key]['link'].'" '.$dirclass.'>';
					else {
						if (@$menu[$key]['class']!="mn_subtitle")
							$out .= '<a href="./" '.$dirclass.'>';
					}

					$out .= $menu[$key]['text'];
					if (@$menu[$key]['class']!="mn_subtitle")
						$out .= '</a>';

					if (@$menu[$key]['class']=="mn_subtitle") $out .='</span>';


					$out .= $this->get_childs($menu, $key);
					$out .= '</li>'."\r\n";
				}
			}
		}
		$out .= '</ul>'."\n\t\t"."\n";
		return ( $has_subcats ) ? $out : FALSE;
	}
}
?>