<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

function __locale($string=null,$print=false){
  global $__locale;
  $string = isset($string)?$string:'';
  if(isset($__locale[$string])&&$__locale[$string]!==''){
    $result = $__locale[$string];
  }else{
    $result = $string;
  }
  if($print){
    print($result);
  }else{
    return $result;
  }
}

function load_locale_package(){
  global $__locale;
  $dirname = 'language';
  $name = lang_get_set();
  $names = language_data($dirname);
  $__locale = array();
  if(is_array($names)&&in_array($name,$names)){
    $filename = PUBDIR.THIRD_PARTY.'/'.$dirname.'/'.$name.'.txt';
    $data = @explode("\n",@file_get_contents($filename));
    if(is_array($data)){foreach($data as $dat){
      if(preg_match('/===/i',$dat)){
        $exp = explode('===',$dat);
        if(isset($exp[1],$exp[0])){
          $__locale[trim($exp[0])] = trim($exp[1]);
        }
      }
    }}
  }
  return $__locale;
}

function language_data($lang_dir=null){
  if(defined('PUBDIR')&&defined('THIRD_PARTY')){
    $lang_dir = isset($lang_dir)?$lang_dir:'language';
    $dir = PUBDIR.THIRD_PARTY.$lang_dir;
    if(is_dir($dir)){
      $scan = @scandir($dir);
      $result = array();
      foreach($scan as $file){
        if(is_file($dir.'/'.$file)&&preg_match('/^[a-z0-9-]+\.txt$/',$file,$akur)){
          $name = substr($akur[0],0,-4);
          $result[] = $name;
        }
      }
      return $result;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

function lang_get_set(){
  global $ldb;
  $default = 'english';
  if(ldb()&&isset($_SESSION['dixie_login'])&&lang_test_db()){
    $exp = explode('_',base64_decode($_SESSION['dixie_login']));
    $select = $ldb->select('users','username='.$exp[1]);
    if(isset($select[0])&&$exp[1]==$select[0]['username']){
      $get = $ldb->select('language_option','username='.$select[0]['username']);
      if(isset($get[0])){
        return $get[0]['lang'];
      }else{
        $data = array(
          'username'=>$select[0]['username'],
          'lang'=>'english',
        );
        $ldb->insert('language_option',$data);
        return $default;
      }
    }else{
      if(isset($_SESSION['dixie_locale'])){
        return $_SESSION['dixie_locale'];
      }else{
        $_SESSION['dixie_locale'] = $default;
        return $default;
      }
    }
  }else{
    if(isset($_SESSION['dixie_locale'])){
      return $_SESSION['dixie_locale'];
    }else{
      $_SESSION['dixie_locale'] = $default;
      return $default;
    }
  }
}

function lang_test_db(){
  global $ldb;
  $table_name = 'language_option';
  if(ldb()){
    $tables = $ldb->show_tables();
    if(!in_array($table_name,$tables)){
      $ldb->create_table($table_name);
    }
    return true;
  }else{
    return false;
  }
}
