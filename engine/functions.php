<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Checking Dixie user login */
function is_login($redirect=null){
  global $ldb;
  if(!isset($_SESSION['dixie_login'])){
    if(isset($redirect)){
	  header('location: '.WWW.$redirect);
	  exit;
	}else{
	  return false;
	}
  }elseif(ldb()){
    $decode = base64_decode($_SESSION['dixie_login']);
    $exp = explode('_',$decode);
    $select = $ldb->select('users','username='.$exp[1]);
    if(is_array($select)&&isset($select[0])&&$exp[1]==$select[0]['username']){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Tell Dixie to start */
function dixie_start(){
  if(defined('DIXIE')&&DIXIE===true){
    session_start();
	refix_uri();
    /* Load public_html */
    load_public_html();
  }else{
    exit;
  }
}

/* Load public html */
function load_public_html(){
  set_time_limit(0);
  if(defined('PUBDIR')&&defined('P')&&file_exists(PUBDIR.P.'.php')){
    @include_once(PUBDIR.P.'.php');
	exit;
  }elseif(defined('PUBDIR')&&file_exists(PUBDIR.'_home.php')){
	include_once(PUBDIR.'_home.php');
    exit;
  }else{
    header('content-type: text/plain');
    die('file is not found!');
  }
}

/* Refix URI inside p= if the request */
function refix_uri(){
  if(defined('P')&&preg_match('/p=/i',$_SERVER['REQUEST_URI'])){
    header('location: '.WWW.str_replace(DIR,'',$_SERVER['REDIRECT_URL']));
	exit;
  }
}

/* Call Ldb class then globalize to $ldb */
function ldb(){
  global $ldb;
  if(is_object($ldb)){
    return true;
  }else{
    $ldb = new Ldb('dixie');
    if(is_object($ldb)){
      return true;
    }else{
      return false;
    }
  }
}

/* Set login request */
function login_request(){
  global $ldb;
  if(isset($_POST['username'])&&isset($_POST['password'])&&ldb()){ 
    if($ldb->valid_password('users','username='.$_POST['username'],$_POST['password'])){
      $_SESSION['dixie_login'] = base64_encode('dixie_'.$_POST['username'].'_'.dechex(time()));
      $user = $ldb->select('users','username='.$_POST['username']);
      if(isset($user[0])){
        $_SESSION['dixie_privilege'] = $user[0]['privilege']; // master/admin/editor/author/member
      }
      header('location: '.WWW.'admin/?status=login');
      exit;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Set password request */
function password_request(){
  global $ldb;
  if(isset($_POST['username'])&&ldb()){
    $select = $ldb->select('users','username='.$_POST['username']);
    if(isset($select[0])){
      $new_password = substr(base64_encode(md5(time())),0,9);
      $update = $ldb->update('users','aid='.$select[0]['aid'],array('password'=>$new_password));
      if($update){
        $from = 'dixie@black-apple.biz';
        $headers = 'From: '.$from.''."\r\n";
        $headers .= 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
        $to = $select[0]['email'];
        $message = '<!DOCTYPE html><html lang="en-US"><head><meta content="text/html; charset=utf-8" http-equiv="content-type" /><meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" /><meta content="width=device-width, initial-scale=1" name="viewport" /><title>Request Password &#8213; Dixie</title><meta content="Dixie CMS from Black Apple Inc." name="description" /><meta content="Generator, CMS" name="keywords" /><meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" /><meta content="Dixie" name="generator" /><meta content="'.DIXIE_VERSION.'" name="version" /><link rel="shortcut icon" href="'.WWW.PUBDIR.'admin/images/dixie.ico" type="image/x-icon" /><meta content="'.WWW.PUBDIR.'admin/images/dixie.png" property="og:image" /><style type="text/css">body{color:#333;font-family:Tahoma,Segoe UI,Arial;}</style></head><body><div>Dear '.$select[0]['name'].' ('.$select[0]['username'].'),<br /><br /></div><div>A few minute ago, somebody has requested a new password by using your username: <strong>'.$select[0]['username'].'</strong>.</div><div>If this is the real of you, here is your new password: <strong>'.$new_password.'</strong></div><div><br /><br />Admin - Dixie</div></body></html>';
        $subject = 'Request Password - Dixie';
        $mail = @mail($to,$subject,$message,$headers);
        if($mail){
          $error = array('status'=>'OK','message'=>'Message has been sent');
          return $error;
        }else{
          $error = array('status'=>'error','message'=>'cannot cannot send the email');
          return $error;
        }
      }else{
        $error = array('status'=>'error','message'=>'cannot update database');
        return $error;
      }
    }else{
      $error = array('status'=>'error','message'=>'username doesn\'t exist');
      return $error;
    }
  }else{
    return false;
  }
}

/* Create slug from string */
function create_slug($str){
  return preg_replace_callback('/[^a-z0-9-]+/i',function(){
    return '';
  },str_replace(array(' ','_'),'-',strtolower($str)));
}

/* Create datetime from current time */
function create_datetime($time=null){
  if(isset($time)){
    return date('y-m-d H:i:s',$time);
  }else{
    return date('y-m-d H:i:s');
  }
}

/* Create print input */
function tprint($str=false){
  return print(htmlspecialchars($str));
}

/* Privilege master */
function master_privilege($username=null){
  $user = (isset($username))?$username:'';
  if($_SESSION['dixie_privilege']=='master'){
    return true;
  }elseif($_SESSION['dixie_privilege']=='admin'&&$user=='master'){
    return false;
  }elseif($_SESSION['dixie_privilege']=='admin'&&$user!=='master'){
    return true;
  }else{
    return false;
  }
}

/* Dixie check update */
function dixie_check_update(){
  $url = 'http://dixie.black-apple.biz/update.php';
  $data = array(
    'dixie_client'=>'free_3c45d9df52f76f69d1130e12db10fe59',
    'dixie_version'=>DIXIE_VERSION,
  );
  $get_content = form_post($url,$data);
  $update = json_decode($get_content,true);
  if(isset($update['update_needed'])){
    return $update;
  }else{
    return false;
  }
}

/* SESSION Dixie privilege -> return integer */
function sdp(){
  $sdp = array(
    'master'=>32,
    'admin'=>16,
    'editor'=>8,
    'author'=>4,
    'member'=>2,
  );
  if(isset($_SESSION['dixie_privilege'])){
    return $sdp[$_SESSION['dixie_privilege']];
  }else{
    return false;
  }
}

/* Re-arrage uploaded files */
function rearrange_files($files=null){
  if(isset($files)&&is_array($files)){
    $hasil = array();
    foreach($files as $key=>$value){
      foreach($value as $val=>$yu){
        $hasil[$val][$key] = $yu;
      }
    }
    return $hasil;
  }else{
    return false;
  }
}

/* Remove directory and its content 
 * @parameter: $dirname = Require base directory line to find is_dir
 */
function remove_dir($dirname=null,$depth=true){
  if(isset($dirname)){
    $dir = (substr($dirname,-1,strlen($dirname))=='/')?$dirname:$dirname.'/';
    $scan = scandir($dir);
    if(is_array($scan)){
      foreach($scan as $file){
        if($file!=='.'&&$file!=='..'){
          if(is_dir($dir.$file)&&$depth===true){
            @remove_dir($dir.$file,$depth);
          }else{
            @unlink($dir.$file);
          }
        }
      }
      @rmdir($dir);
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Function file write */
function file_write($filename,$content='',$type='w+'){
  $fp = fopen($filename,$type);
  $write = fwrite($fp,strip_magic($content));
  fclose($fp);
  if($write){
    return true;
  }
  else{
    return false;
  }
}
/* Function strip quotes */
function strip_magic($str){
  if(is_array($str)){
    $hasil = array();
    foreach($str as $k=>$v){
      $hasil[$k] = (get_magic_quotes_gpc())?stripslashes($v):$v;
    }
    return $hasil;
  }
  else{
    $hasil = (get_magic_quotes_gpc())?stripslashes($str):$str;
    return $hasil;
  }
}

/* Detect mobile browser */
function is_mobile_browser(){
  $useragent=(isset($_SERVER['HTTP_USER_AGENT']))?$_SERVER['HTTP_USER_AGENT']:'';
  if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){return true;}else{return false;}
}

/* Detect MSIE browser */
function is_msie(){
  if(isset($_SERVER['HTTP_USER_AGENT'])&&preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])){return true;}else{return false;}
}

/* Find MSIE version */
function msie_version(){
  if(isset($_SERVER['HTTP_USER_AGENT'])&&preg_match('/MSIE\s\d\.\d/i',$_SERVER['HTTP_USER_AGENT'],$akur)){
    $version = str_replace('MSIE ','',$akur[0]);
    return $version;
  }else{
    return false;
  }
}

/* Get http response status*/
function dixie_response_status($res=null,$print=true){
  $http_status_codes = array(100 => "Continue", 101 => "Switching Protocols", 102 => "Processing", 200 => "OK", 201 => "Created", 202 => "Accepted", 203 => "Non-Authoritative Information", 204 => "No Content", 205 => "Reset Content", 206 => "Partial Content", 207 => "Multi-Status", 300 => "Multiple Choices", 301 => "Moved Permanently", 302 => "Found", 303 => "See Other", 304 => "Not Modified", 305 => "Use Proxy", 306 => "(Unused)", 307 => "Temporary Redirect", 308 => "Permanent Redirect", 400 => "Bad Request", 401 => "Unauthorized", 402 => "Payment Required", 403 => "Forbidden", 404 => "Not Found", 405 => "Method Not Allowed", 406 => "Not Acceptable", 407 => "Proxy Authentication Required", 408 => "Request Timeout", 409 => "Conflict", 410 => "Gone", 411 => "Length Required", 412 => "Precondition Failed", 413 => "Request Entity Too Large", 414 => "Request-URI Too Long", 415 => "Unsupported Media Type", 416 => "Requested Range Not Satisfiable", 417 => "Expectation Failed", 418 => "I'm a teapot", 419 => "Authentication Timeout", 420 => "Enhance Your Calm", 422 => "Unprocessable Entity", 423 => "Locked", 424 => "Failed Dependency", 424 => "Method Failure", 425 => "Unordered Collection", 426 => "Upgrade Required", 428 => "Precondition Required", 429 => "Too Many Requests", 431 => "Request Header Fields Too Large", 444 => "No Response", 449 => "Retry With", 450 => "Blocked by Windows Parental Controls", 451 => "Unavailable For Legal Reasons", 494 => "Request Header Too Large", 495 => "Cert Error", 496 => "No Cert", 497 => "HTTP to HTTPS", 499 => "Client Closed Request", 500 => "Internal Server Error", 501 => "Not Implemented", 502 => "Bad Gateway", 503 => "Service Unavailable", 504 => "Gateway Timeout", 505 => "HTTP Version Not Supported", 506 => "Variant Also Negotiates", 507 => "Insufficient Storage", 508 => "Loop Detected", 509 => "Bandwidth Limit Exceeded", 510 => "Not Extended", 511 => "Network Authentication Required", 598 => "Network read timeout error", 599 => "Network connect timeout error");
  if(isset($res)&&isset($http_status_codes[$res])){
    $status = $res.' '.$http_status_codes[$res];
  }else{
    $status = http_response_code().' '.$http_status_codes[http_response_code()];
  }
  if($print){
    return printf($status);
  }else{
    return $status;
  }
}

/* Dixie session check .htaccess files at each directory */
function dixie_session_check_htaccess(){
  $dirs = array('upload','themes','public_html');
  if(!isset($_SESSION['dixie_check_htaccess'])){
    $explore = dixie_explore('dir');
    if(is_array($explore)){
      foreach($explore as $dir){
        if(!file_exists($dir.'.htaccess')){
          file_write($dir.'.htaccess','Options -Indexes');
        }
      }
      $_SESSION['dixie_check_htaccess'] = true;
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Dixie Explorer */
function dixie_explore($type='all',$dir=null){
  $dir = (isset($dir)&&is_dir($dir))?$dir:substr(ROOT,0,-1);
  $types = array('file','dir','all');
  $scan = scandir($dir);
  if(is_array($scan)){
    $hasil = array();
    foreach($scan as $file){
      if($file!=='.'&&$file!=='..'){
        if($type=='file'){
          $hasil[] = (is_dir($dir.'/'.$file))?dixie_explore($type,$dir.'/'.$file):$dir.'/'.$file;
        }elseif($type=='dir'){
          if(is_dir($dir.'/'.$file)){
            $hasil[] = $dir.'/'.$file.'/';
            $hasil[] = dixie_explore($type,$dir.'/'.$file);
          }
        }else{
          if(is_dir($dir.'/'.$file)){
            $hasil[] = $dir.'/'.$file.'/';
            $hasil[] = dixie_explore($type,$dir.'/'.$file);
          }else{
            $hasil[] = $dir.'/'.$file;
          }
        }
      }
    }
    return wrap_array($hasil);
  }else{
    return false;
  }
}

/* Array wrapper */
function wrap_array($array=array()){
  $hasil = array();
  if(is_array($array)){
    foreach($array as $val){
      if(is_array($val)){
        foreach($val as $vul){
          if(is_array($vul)){
            $hasil[] = wrap_array($vul);
          }else{
            $hasil[] = $vul;
          }
        }
      }else{
        $hasil[] = $val;
      }
    }
    return $hasil;
  }else{
    return false;
  }
}

// *** Form post *** //
function form_post($url,$data=array(),$cookie=''){
  $content = @http_build_query($data,'','&'); // ,PHP_QUERY_RFC1738
  $option = array('http'=>array(
    'method'=>'POST',
    'header'=>"content-type: application/x-www-form-urlencoded;charset=utf-8;\r\ncookie:".$cookie,
    'content'=>$content
  ));
  $context = @stream_context_create($option);
  $file = @file_get_contents($url,false,$context);
  return $file;
}


    if (!function_exists('http_response_code')) {
        function http_response_code($code = NULL) {
            if ($code !== NULL) {
                switch ($code) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
                }
                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
                header($protocol . ' ' . $code . ' ' . $text);
                $GLOBALS['http_response_code'] = $code;
            } else {
                $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
            }
            return $code;
        }
    }