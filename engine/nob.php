<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Define the dixie root of site */
define('DROOT',str_replace('\\','/',dirname(__DIR__)));

/* Define document directory of site */
define('DOCUMENT_ROOT',str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']));

/* Define important directory of site */
$dir=str_replace(DOCUMENT_ROOT,'',DROOT);
$dir=substr($dir,0,1)!='/'?'/'.$dir:$dir;
$dir.=substr($dir,-1)!='/'?'/':'';
define('DIR',$dir);

/* prepare scheme and port */
$scheme=isset($_SERVER['HTTPS'])?'https'
  :(isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:'http');
$port=isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:80;
$dport=$port==80?($scheme=='http'?'':":$port")
  :($port==443&&$scheme=='https'?'':":$port");

/* Define address of site */
define('WWW',$scheme.'://'.$_SERVER["SERVER_NAME"].$dport.DIR);

/* Create slug from string */
function create_slug($str){
  return preg_replace_callback('/[^a-z0-9-]+/i',function(){
    return '';
  },str_replace(array(' ','_'),'-',strtolower($str)));
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

/* Function ba_byte for calcualting file size as byte */
function ba_byte($angka=false,$fixed=false,$b=null){
  $angka = (is_file($angka))?filesize($angka):$angka;
  $angka = (is_numeric($angka))?$angka:false;
  $b = (isset($b))?$b:'KB';
  $hasil = $angka/1024;
  $mb = ($b=='KB')?'MB':'GB';
  if($angka<1024&&!isset($b)){
    return number_format($angka,$fixed,'.',',')." B";
  }elseif($hasil>1024){
    return ba_byte($hasil,2,$mb);
  }else{
    return number_format($hasil,$fixed,'.',',')." ".$b;
  }
}


















