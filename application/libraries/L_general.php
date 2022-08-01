<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * General Class, general functions.
 * Used by random several pages
 * @author Wiwid Lukiyanto <wiwid@wiwid.org>
 */
class L_general
{

	public function __construct() {
		$this->obj =& get_instance();
	}

	/**
	 * Return random number
	 * @return int
	 */
	// give random number

   function dateDiff($date1,$date2,$unit='HOUR'){
    $date1=strtotime($date1);
    $date2=strtotime($date2);

    $secs=$date1-$date2;

    switch(strtoupper($unit)){
        case 'WEEK':return $secs/60/60/24/7; break;
        case 'DAY':return $secs/60/60/24; break;
        case 'HOUR':return $secs/60/60; break;
        case 'MINUTE':return $secs/60; break;
        case 'SECOND':return $secs; break;
        default:return $secs/60/60; break;
         }
	}
	
	function str_to_date_clone($strdate) {
		$year = $this->rightstr($strdate, 4);
		$month = substr($strdate, 3, 2);
		$day = $this->leftstr($strdate, 2);
		$strdate = $year."-".$month."-".$day;
		unset($year);
		unset($month);
		unset($day);
		return $strdate;
	  }

	function str_to_date($strdate) {
      $year = $this->rightstr($strdate, 4);
      $month = substr($strdate, 3, 2);
      $day = $this->leftstr($strdate, 2);
      $strdate = $year."-".$month."-".$day.' 00:00:00';
      unset($year);
      unset($month);
      unset($day);
      return $strdate;
	}

	function str_datetime_to_date($strdate) {
      $year = $this->leftstr($strdate, 4);
      $month = substr($strdate, 5, 2);
      $day = substr($strdate, 8, 2);
      $strdate = $year."-".$month."-".$day.' 00:00:00';
      unset($year);
      unset($month);
      unset($day);
      return $strdate;
	}

	function sqldate_to_date($strdate) {
      return date("d-m-Y", strtotime($strdate));
	}

	function date_add_day($date_to_add,$day_add) {
       return date("d-m-Y",strtotime($date_to_add) + ($day_add * (60 * 60 * 24)));
	}

	function date_add_day_ymd($date_to_add,$day_add) {
       return date("Y-m-d",strtotime($date_to_add) + ($day_add * (60 * 60 * 24)));
	}

	function date_add_month($date_to_add,$add) {
       return date("Y-m",strtotime(date("Y-m-d", $date_to_add) . $add." month"));
	}

	function _get_web_trans_id($kd_plant,$trans_date,$id_plant_num,$tipe_trans) {
	    $web_trans_id = $kd_plant.$tipe_trans.date('ymd',strtotime($trans_date)).
        $this->rightstr('000000'.$id_plant_num,6);

        return $web_trans_id;
    }

    function leftstr($str, $length) {
         return substr($str, 0, $length);
    }

    function remove_0_digit_in_item_code($item_code) {
      return str_replace(str_repeat('0',12),'',$item_code);
    }

    function export_to_excel($data,$filename) {
      require_once('export-xls.class.php');
      $fields = $data->field_data();
      foreach ($fields as $field) $headers[] = $field->name;
      $xls = new ExportXLS($filename.'.xls');
      $xls->addHeader($headers);
      $xls->addRow($data->result_array());
      $xls->sendFile();
    }

    function rightstr($str, $length) {
         return substr($str, -$length);
    }

	function random_number() {
		list($usec, $sec) = explode(' ', microtime());
		$random = (float) $sec + ((float) $usec * 100000000);
		mt_srand($random);
		return mt_rand();
	}

	/**
	 * Return random password,
	 * can contains lowercase, uppercase, and number
	 * @
	 * @copyright cwarn23
	 * @copyright http://www.daniweb.com/forums/thread178586.html
	 * @copyright http://www.daniweb.com/forums/member202654.html
	 * @param int $minchars numbers of minimum characters returned
	 * @param int $maxchars numbers of maximum characters returned
	 * @return string
	 */
	function random_password($minchars = 10, $maxchars = 15) {
		//settings
		$chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";

		//rest of script
		$escapecharplus=0;
		$repeat=mt_rand($minchars,$maxchars);
		while ($escapecharplus<$repeat)
				{
				$randomword.=$chars[mt_rand(1, strlen($chars)-1)];
				$escapecharplus+=1;
				}
		//display random word
		return $randomword;
	}

	/**
	 * Return age of the person, based on birthdate
	 * @param string $BirthDate Expected format is YYYY-MM-DD
	 * @return int
	 */
	function age($BirthDate) {
		// Put the year, month and day in separate variables
		list($Year, $Month, $Day) = explode("-", $BirthDate);

		$YearDiff = date("Y") - $Year;

		// If the birthday hasn't arrived yet this year,
		// the person is one year younger
		if(date("m") < $Month || (date("m") == $Month && date("d") < $DayDiff))
			$YearDiff--;

		return $YearDiff;
	}

	/**
	 * Send email to someone
	 * @param string $subject Subject of the email
	 * @param string $message Message body
	 * @param string $from_email From email
	 * @param string $from_name From name
	 * @param string $to_email To email
	 * @param string $to_name To name
	 * @param string $cc_email CC email
	 * @param string $cc_name CC name
	 * @param string $bcc_email BCC email
	 * @param string $bcc_name BCC name
	 * @return boolean
	 */
	function send_mail($subject='', $message='', $from_email='', $from_name='', $to_email='', $to_name='', $cc_email='', $cc_name='', $bcc_email='', $bcc_name='', $priority = '') {

		$this->obj->load->library('email');
		$this->obj->lang->load('email', $this->obj->session->userdata('lang_name'));

		$this->obj->email->clear();

		if(!empty($priority)) {
			$config['priority'] = $priority;
			$this->obj->email->initialize($config);
		}

		if(empty($from_name))
			$from_name = $from_email;

		$this->obj->email->from($from_email, $from_name);

		if(empty($to_name))
			$to_name = $to_email;

		$this->obj->email->to($to_email, $to_name);

		if(!empty($cc_email)) {

			if(empty($cc_name))
				$cc_name = $cc_email;

			$this->obj->email->cc($cc_email, $cc_name);

		}

		if(!empty($bcc_email)) {

			if(empty($bcc_name))
				$bcc_name = $bcc_email;

			$this->obj->email->bcc($bcc_email, $bcc_name);

		}

		$this->obj->email->subject($subject);
		$this->obj->email->message($message);
		
		if($this->obj->email->send())
			return TRUE;
		else
			return FALSE;

	}

	function get_month_name($number) {
		$this->obj->lang->load('calendar', $this->obj->session->userdata('lang_name'));

		$number = (int) $number;

		switch($number) {
			case 1:
				return $this->obj->lang->line('cal_january');
			case 2:
				return $this->obj->lang->line('cal_february');
			case 3:
				return $this->obj->lang->line('cal_march');
			case 4:
				return $this->obj->lang->line('cal_april');
			case 5:
				return $this->obj->lang->line('cal_may');
			case 6:
				return $this->obj->lang->line('cal_june');
			case 7:
				return $this->obj->lang->line('cal_july');
			case 8:
				return $this->obj->lang->line('cal_august');
			case 9:
				return $this->obj->lang->line('cal_september');
			case 10:
				return $this->obj->lang->line('cal_october');
			case 11:
				return $this->obj->lang->line('cal_november');
			case 12:
				return $this->obj->lang->line('cal_december');
			default:
				return FALSE;
		}
	}

	function date_ymd_to_dmy($ymd, $separate = '-') {
		$year  = substr($ymd, 0, 4);
		$month = substr($ymd, 5, 2);
		$day   = substr($ymd, 8, 2);

		return $day.$separate.$month.$separate.$year;
	}

	function date_dmy_to_ymd($dmy, $separate = '-') {
		$day   = substr($dmy, 0, 2);
		$month = substr($dmy, 3, 2);
		$year  = substr($dmy, 6, 4);

		return $year.$separate.$month.$separate.$day;
	}

	function success_page($message, $url) {
      $object['refresh'] = 1;
      $object['refresh_time'] = 5;
      $object['refresh_text'] = $message;
      $object['refresh_url'] = $url;

      $this->obj->template->write_view('content', 'success', $object);
      $this->obj->template->render();
	}

	function error_page($jag_module, $message, $url) {
		$object['refresh'] = 0;
		$object['refresh_text'] = $message;
        $object['refresh_url'] = $url;
		$object['jag_module'] = $jag_module;
		$object['error_code'] = $jag_module['error_code'];
		$object['page_title'] = 'Error '.strtoupper($object['jag_module']['web_module']).': '.$object['jag_module']['web_trans']; 
		$this->obj->template->write_view('content', 'errorweb', $object);
		$this->obj->template->render();


	}


    function get_web_page( $url ) {
        $options = array( 'http' => array(
            'user_agent'    => 'ybc',    // who am i
            'max_redirects' => 10,          // stop after 10 redirects
            'timeout'       => 120,         // timeout on response
        ) );
        $context = stream_context_create( $options );
        $page    = @file_get_contents( $url, false, $context );

        $result  = array( );
        if ( $page != false )
            $result['content'] = $page;
        else if ( !isset( $http_response_header ) )
            return null;    // Bad url, timeout

        // Save the header
        $result['header'] = $http_response_header;

        // Get the *last* HTTP status code
        $nLines = count( $http_response_header );
        for ( $i = $nLines-1; $i >= 0; $i-- )
        {
            $line = $http_response_header[$i];
            if ( strncasecmp( "HTTP", $line, 4 ) == 0 )
            {
                $response = explode( ' ', $line );
                $result['http_code'] = $response[1];
                break;
            }
        }

        return $result;
    }

}

/* End of file L_general.php */
/* Location: ./system/application/libraries/L_general.php */