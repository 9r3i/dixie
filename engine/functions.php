<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Checking Dixie user login */
function is_login($redirect=null){
  global $ldb;
  if(!isset($_SESSION['dixie_login'])){
    if(isset($_COOKIE['dixie_login_cookie'],$_COOKIE['dixie_privilege_cookie'])){
      $_SESSION['dixie_login'] = $_COOKIE['dixie_login_cookie'];
      $_SESSION['dixie_privilege'] = $_COOKIE['dixie_privilege_cookie'];
      return isset($redirect)?is_login($redirect):is_login();
    }elseif(isset($redirect)){
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
    check_data_files();
	refix_uri();
    @set_time_limit(0);
    /* add connection cross domain */
    if(defined('P')&&P=='Ldb2.connect'){
      @require_once('Ldb2.connect.php');
    }
    /* Load public_html */
    load_public_html();
  }else{
    exit;
  }
}

/* Load public html */
function load_public_html(){
  if(defined('PUBDIR')&&defined('P')&&is_file(PUBDIR.P.'.php')){
    @include_once(PUBDIR.P.'.php');
	exit;
  }elseif(defined('PUBDIR')&&file_exists(PUBDIR.'_home.php')){
	@include_once(PUBDIR.'_home.php');
    exit;
  }else{
    header('content-type: text/plain');
    die('file is not found!');
  }
}

/* Refix URI inside p= if the request */
function refix_uri(){
  if(defined('P')&&preg_match('/(p=[a-z0-9-]+)/i',$_SERVER['REQUEST_URI'])){
    header('location: '.WWW.str_replace(DIR,'',P));
	exit;
  }
}


/* Checking folders/directories and install dixie theme if doesn't exist */
function check_data_files(){
  $folders = array('plugins','themes','upload');
  foreach($folders as $folder){
    if(!is_dir($folder)){
      @mkdir($folder);
    }
  }
  $target = 'public_html/temp/DixieX.zip';
  if(file_exists($target)&&count(dixie_explore('dir','themes/'))==0){
    $zip = new ZipArchive;
    if($zip->open($target)===true){
      if($zip->extractTo('themes/Dixie3')){
        $zip->close();
      }
    }
  }
  global $ldb;
  ldb();
  /* Create the important tables */
  $tables = array('menu','sidebar','options','posts','users','request','category');
  $current_tables = $ldb->show_tables();
  foreach($tables as $table){
    if(is_array($current_tables)&&!in_array($table,$current_tables)){
      $ldb->create_table($table);
    }
  }
  return true;
}

/* Call Ldb class then globalize to $ldb */
function ldb(){
  global $ldb;
  if(is_object($ldb)){
    return true;
  }else{
    if(defined('LCD')&&LCD!==false&&defined('LCD_TOKEN')&&LCD_TOKEN!==false){
      $ldb = new Ldb('dixie',LCD,LCD_TOKEN);
    }else{
      $ldb = new Ldb('dixie');
    }
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
    if($ldb->valid_password('users','username='.$_POST['username'],$_POST['password'])||$ldb->valid_password('users','email='.$_POST['username'],$_POST['password'])){
      $user = $ldb->select('users','username='.$_POST['username']);
      $user = (isset($user[0]))?$user:$ldb->select('users','email='.$_POST['username']);
      if(isset($user[0])){
        $login_code = base64_encode('dixie_'.$user[0]['username'].'_'.dechex(time()));
        $_SESSION['dixie_login'] = $login_code;
        $_SESSION['dixie_privilege'] = $user[0]['privilege']; // master/admin/editor/author/member
        if(isset($_POST['remember'])){
          setcookie('dixie_login_cookie',$login_code,time()+(3600*24*30));
          setcookie('dixie_privilege_cookie',$user[0]['privilege'],time()+(3600*24*30));
        }
        header('location: ?status=login');
        exit;
      }else{
        return false;
      }
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
  $ldb->create_table('request');
  if(isset($_POST['username'])&&ldb()){
    $select = $ldb->select('users','username='.$_POST['username']);
    if(isset($select[0])){
      $code = base64_encode(md5(time()));
      $insert = $ldb->insert('request',array('code'=>$code,'username'=>$select[0]['username']));
      if($insert){
        $from = 'dixie@'.$_SERVER["SERVER_NAME"];
        $headers = 'From: '.$from.''."\r\n";
        $headers .= 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
        $to = $select[0]['email'];
        $link = WWW.'reset-password?username='.$select[0]['username'].'&code='.$code;
        $message = '<!DOCTYPE html><html lang="en-US"><head><meta content="text/html; charset=utf-8" http-equiv="content-type" /><meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" /><meta content="width=device-width, initial-scale=1" name="viewport" /><title>Request Password &#8213; Dixie</title><meta content="Dixie - Free and Simple CMS" name="description" /><meta content="Generator, CMS" name="keywords" /><meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" /><meta content="Dixie" name="generator" /><meta content="'.DIXIE_VERSION.'" name="version" /><link rel="shortcut icon" href="'.WWW.PUBDIR.'admin/images/dixie.ico" type="image/x-icon" /><meta content="'.WWW.PUBDIR.'admin/images/dixie.png" property="og:image" /><style type="text/css">body{color:#333;font-family:Tahoma,Segoe UI,Arial;}</style></head><body><div>Dear '.$select[0]['name'].' ('.$select[0]['username'].'),<br /><br /></div><div>A few minute ago, somebody has requested a new password by using your username: <strong>'.$select[0]['username'].'</strong>.</div><div>If this is the real of you, please click this link: <a href="'.$link.'">'.$link.'</a></div><div>But if it\'s not your request, please ignore this message.</div><div><br /><br />Admin - Dixie</div></body></html>';
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

/* Check request code */
function check_request_code($username=null,$code=null){
  global $ldb;
  if(isset($username)&&isset($code)&&ldb()){
    $select = $ldb->select('request','username='.$username.'&code='.$code);
    if(isset($select[0])){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Password reset */
function password_reset(){
  global $ldb;
  if(isset($_POST['username'])&&isset($_POST['code'])&&isset($_POST['new-password'])&&isset($_POST['confirm-password'])&&$_POST['new-password']==$_POST['confirm-password']&&ldb()){
    $select = $ldb->select('request','username='.$_POST['username'].'&code='.$_POST['code']);
    if(isset($select[0])){
      $delete = $ldb->update('request','aid='.$select[0]['aid'],array('username'=>'_'.$select[0]['username']));
      $update = $ldb->update('users','username='.$select[0]['username'],array('password'=>$_POST['new-password']));
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Create slug filename from string */
function create_filename($str){
  return preg_replace_callback('/[^A-Za-z0-9\.-]+/i',function(){
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

function nrtobr($str){
  return str_replace(array('\n\r','\r\n','\r','\n'),'<br />',$str);
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
  $url = 'http://dixie-cms.herokuapp.com/update.php';
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

/* Function retags for mobile only */
function dixie_mobile_content_retags($str){
  $new_str = explode(PHP_EOL.PHP_EOL,strip_tags($str));
  $result = array();
  if(is_array($new_str)){
    foreach($new_str as $new){
      $result[] = '<p>'.nl2br($new).'</p>';
    }
  }
  return implode(PHP_EOL.PHP_EOL,$result);
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
  $dir = (isset($dir)&&is_dir($dir))?$dir:substr(DROOT,0,-1);
  $dir = (substr($dir,-1,strlen($dir))=='/')?substr($dir,0,-1):$dir;
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

/* create backup engine [--Developer Only--] */
function dixie_create_backup_engine(){
  $files = dixie_explore('file','engine');
  $files = array_merge($files,dixie_explore('file','public_html'));
  $files = array_merge($files,array('.htaccess','index.php','change_log.txt','readme.md','license.txt'));
  $zip = new ZipArchive;
  $zipfile = 'dixie_'.DIXIE_VERSION.'_'.DIXIE_REVISION.'.zip';
  file_write($zipfile,'');
  if($zip->open($zipfile)===true){
    foreach($files as $file){
      $zip->addFile($file);
    }
    $zip->close();
    return true;
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


    if(!function_exists('http_response_code')) {
        function http_response_code($code=NULL) {
            if($code!==NULL) {
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
            }else{
                $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
            }
            return $code;
        }
    }