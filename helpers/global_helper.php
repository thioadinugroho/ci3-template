<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 date_default_timezone_set('Asia/Jakarta');
 

/**
 * is chrome
 *
 * @access  public
 * @param string
 * @return organization number
 */
if ( ! function_exists('is_chrome'))
{  
  function is_chrome() {
    return(stristr($_SERVER['HTTP_USER_AGENT'],"chrome"));
  }
}

// ------------------------------------------------------------------------

/**
 * writeLog
 *
 * This will write a log in a file
 *
 * @access  public 
 * @param string
 * @return string
 */

function writeLog($text,$prefix='myapp',$time=''){
  date_default_timezone_set('Asia/Jakarta');
  $fileurl = LOG_PATH.$prefix.'_'.date('Ymd');
 
  $ipv4 = get_client_ip();
  if(file_exists($fileurl)){
    if (!$handle = fopen($fileurl, 'a+')) {
      //echo 'Cannot open file ('.$fileurl.')';
      exit;
    }
  }else{
    if (!$handle = fopen($fileurl, 'w')) {
      echo 'Cannot create file ('.$fileurl.')';
      exit;
    }
    @chmod($fileurl,0755);
  }
  $ref = @$_SERVER['HTTP_REFERER'];
  if (fwrite($handle, date('Y-m-d | H:i:s').' | '.$ipv4.' | '.$text." | ".@$ref." | ".@$time."\n") === FALSE) {
    echo 'Cannot write to file ('.$fileurl.')';
    exit;
  }
  
  fclose($handle);  
}

// ------------------------------------------------------------------------

/**
 * get_client_ip
 *
 * @access  public
 * @param string
 * @return ip address
 */
if ( ! function_exists('get_client_ip'))
{  
  function get_client_ip($type='') {
    return getenv('HTTP_CLIENT_IP')?:
      getenv('HTTP_X_FORWARDED_FOR')?:
      getenv('HTTP_X_FORWARDED')?:
      getenv('HTTP_FORWARDED_FOR')?:
      getenv('HTTP_FORWARDED')?:
      getenv('REMOTE_ADDR');
  }
}

// ------------------------------------------------------------------------

/**
 * status code
 *
 * @access  public
 * @param string
 * @return ip address
 */
if ( ! function_exists('get_status_code'))
{  
  function get_status_code($code='') {
    $status = array(
      'c000' => 'Success',
      'c001' => 'Invalid Data',
      'c002' => 'Username / password does not match',
      'c003' => 'Token invalid, not found, or expired',
      'c004' => 'Email sent',
      'c005' => 'Password does not match',
      'c006' => 'Update password failed',
      'c007' => 'OTP expired',
      'c008' => 'Submit failed',
      'c009' => 'Update failed'
    );

    return ($code == -1)?count($status):$status['c'.$code];
  }
}

// ------------------------------------------------------------------------

/**
 * status code
 *
 * @access  public
 * @param string
 * @return ip address
 */
if ( ! function_exists('generate_session'))
{  
  function generate_session($n=6) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
  } 
}

// ------------------------------------------------------------------------

/**
 * convert_date
 *
 * to convert a date format automatically from human readable to system /
 * sql readable
 *
 * @access  public
 * @param string
 * @return string
 */
if ( ! function_exists('convert_date'))
{
  function convert_date($date = '',$year = 2000)
  {
    $prefixyear = substr($year,0,2);
    if (strpos($date, '-') === false) {
      $datearr = explode('/',$date);
      if($date != '') $newdate = ((strlen(@$datearr[2]) == '2')?$prefixyear.@$datearr[2]:@$datearr[2]).'-'.@$datearr[1].'-'.$datearr[0];
      else $newdate = '0000-00-00';
    } else {
      $datearr = explode('-',$date);
      if($date != '') $newdate = @$datearr[2].'/'.@$datearr[1].'/'.((strlen($datearr[0]) == '2')?$prefixyear.$datearr[0]:$datearr[0]);
      else $newdate = '00/00/0000';
    }
    return $newdate;
  }
}

// ------------------------------------------------------------------------

/**
 * terbilang
 *
 * Retrieve all currencies with default currency is IDR 
 *
 * @access  public
 * @param string
 * @return array
 */
if ( ! function_exists('terbilang'))
{

  function terbilang($angkaku) {
    $angkaku = trim($angkaku);
    if($angkaku != 0) {
      $angka = Array();
      $angka[0]="";
      $angka[1]="satu";
      $angka[2]="dua";
      $angka[3]="tiga";
      $angka[4]="empat";
      $angka[5]="lima";
      $angka[6]="enam";
      $angka[7]="tujuh";
      $angka[8]="delapan";
      $angka[9]="sembilan";
      
      $digit = Array();
      $digit[0]="";
      $digit[1]="puluh";
      $digit[2]="ratus";
      $digit[3]="ribu";
      $digit[4]="puluh";
      $digit[5]="ratus";
      $digit[6]="juta";
      $digit[7]="puluh";
      $digit[8]="ratus";
      $digit[9]="milyar";
      $digit[10]="puluh";
      $digit[11]="ratus";
      $digit[12]="triliyun";
      $digit[13]="puluh";
      $digit[14]="ratus";
      
      $panjang = strlen($angkaku);
      $balik = false;
      $terbilang ="";
      $ribuan = Array(9,6,3);
      for($i=1;$i<=$panjang;$i++) {
      
           $angka_jumlah = substr($angkaku,$i-1,1);
           $angka_jumlah_1 = substr($angkaku,$i,1);
           $angka_digit = $panjang - $i;
           $se = in_array($digit[$angka_digit],Array("puluh","ratus","ribu"));
           $tt = (($angka_digit % 3) == 0)?true:false;
           $angka[1]= ($se)?"se":"satu ";
           if ($digit[$angka_digit] =="puluh") {
             $digit[$angka_digit]= ($angka_jumlah == 1)?"belas":"puluh";
             $digit[$angka_digit]= ($angka_jumlah_1 == 0)?"puluh":$digit[$angka_digit];
             if ($digit[$angka_digit]=="belas")
                 $angka_jumlah = $angka_jumlah_1;
           }
          // echo $angkaku;
           $pemisah = ($terbilang =="")? "":" ";
           $separator = (($angka_jumlah >1) || !$se )?" ":"";
           if (!$balik)
                $terbilang .= $pemisah . $angka[$angka_jumlah];
           if ($digit[$angka_digit]=="belas")
               $balik = true;
            else
               $balik =false;
      
           if (($angka_jumlah >0)){
               $terbilang .= $separator . $digit[$angka_digit];
               $xx = $angka_digit;
           } else{
             if ($tt){
                  $rr = (($xx % 3) == 0)?true:false;
                 if (!$rr)
                      $terbilang .= $separator . $digit[$angka_digit];
                $xx = $angka_digit;
             }
      
           }
      
      }
    }
    else $terbilang = 'nol';
    return terbilang_neat($terbilang); 
    //return strlen($terbilang);
  }
  
}

// ------------------------------------------------------------------------

/**
 * terbilang_neat
 *
 * Retrieve all currencies with default currency is IDR 
 *
 * @access  public
 * @param string
 * @return array
 */
if ( ! function_exists('terbilang_neat'))
{

  function terbilang_neat($terbilang) {
    $arrterb = explode(" ",$terbilang);
    for($i=0;$i<count($arrterb);$i++) {
      if(strlen($arrterb[$i]) != "0") $newterb[] = $arrterb[$i];
    }
    $assemble = implode(" ",$newterb);
    $a = explode('seribu',$assemble);
    
    if ($a[0] != '' && count($a) == 2) {
      $assemble = trim($a[0]).' satu ribu '.trim($a[1]);
    }
    return $assemble;
  }

}

// ------------------------------------------------------------------------

/**
 * add zero
 *
 * adding leading zeros
 *
 * @access	public
 * @param	string
 * @return string (json)
 */
if ( ! function_exists('add_zero'))
{
	function add_zero($int,$n)
	{
    if(strlen($int) < $n) { 
      while(strlen($int) != $n) {
        $int = '0'.$int;
      }
    }
    return $int;
	}
}

// ------------------------------------------------------------------------


/**
 * Convert month
 *
 *
 * @access	public
 * @param	string
 * @return array
 */
if ( ! function_exists('convert_month'))
{
  function convert_month($m='') {
    $arrmonth = Array(
      '00' => '',
      '01' => 'Januari',
      '02' => 'Pebruari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'Nopember',
      '12' => 'Desember'
    );
    if(strlen($m) <= 2) {
      return $arrmonth[add_zero($m,2)]; 
    } else {
      $k = array_keys($arrmonth);
      return add_zero($k[$m],2); 
    }
  }

}

// ------------------------------------------------------------------------

/**
 * Convert month in roman
 *
 *
 * @access	public
 * @param	string
 * @return array
 */
if ( ! function_exists('convert_month_in_roman'))
{
  function convert_month_in_roman($m='') {
    $arrmonth = Array(
      '00' => '',
      '01' => 'I',
      '02' => 'II',
      '03' => 'III',
      '04' => 'IV',
      '05' => 'V',
      '06' => 'VI',
      '07' => 'VII',
      '08' => 'VIII',
      '09' => 'IX',
      '10' => 'X',
      '11' => 'XI',
      '12' => 'XII'
    );
    if(strlen($m) <= 2) {
      return $arrmonth[add_zero($m,2)]; 
    } else {
      $k = array_keys($arrmonth);
      return add_zero($k[$m],2); 
    }
  }

}

// ------------------------------------------------------------------------

/**
 * nominal
 *
 * convert into the right nominal format
 *
 * @access	public
 * @param	string
 * @return string
 */
if ( ! function_exists('nominal'))
{
	function nominal($bill = 0,$usebracket=false)
	{
    if(!$usebracket)
      return number_format(trim((int)$bill),0,',','.');
    else {
      if ($bill < 0) {
        $amt = $bill*-1;
        return '('.number_format(trim($amt),0,',','.').')';
      } else {
        $amt = $bill;
        return number_format(trim((int)$bill),0,',','.');
      }
    }
	}
}

// ------------------------------------------------------------------------

/**
 * create_images_from_string
 *
 * @access  public
 * @param string
 * @return string (json)
 */
if ( ! function_exists('create_images_from_string'))
{
  function create_images_from_string($image,$file)
  {
    $CI =& get_instance();
    $CI->config->load('config');
    $server = UPLOAD_PATH;
    $path = $file;
    // $path = 'attachment/'.$file;
    $crt = file_put_contents($server.$path,base64_decode($image));
    return $path;
  }
}

// ------------------------------------------------------------------------