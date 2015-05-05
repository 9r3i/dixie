<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Global options and posts */
global $options,$posts;

/* set bazzmu registry as version 3.x.x */
if(defined('BAZZMU')&&BAZZMU===true){
  admin_registry('bazzmu','bazzmu','Bazzmu',39,16,true,false,'fa-spinner fa-spin');
}

/* Get site options */
if(!get_options()){
  header('location: '.WWW.'install/?ref=first');
  exit;
}

/* Set default timezone */
if(get_site_info('timezone',false)){
  date_default_timezone_set(get_site_info('timezone',false));
}

/* Load locale package as version 2.3.0 */
load_locale_package();

/* Check session login */
if(!is_login()){
  @include_once('admin/login.php');
  exit;
}

/* Refix admin uri */
if(!preg_match('/admin\//i',$_SERVER['REQUEST_URI'])){
  header('location: '.str_replace('admin','admin/',$_SERVER['REQUEST_URI']));
  exit;
}

/* create backup system file [--Developer Only--] */
if(isset($_GET['dixie/backup'])){
  header('content-type: text/plain');
  if(dixie_create_backup_engine()){
    exit('success');
  }else{
    exit('error');
  }
}
/* personal function to create plugin about [--Developer Only--] */
if(isset($_GET['dixie/plugins/about'])){
  header('content-type: text/plain');
  $prow = '---------------';
  $plug = new Plugins();
  $result = array();
  foreach($plug->plugins as $key=>$value){
    $sub = array();
    $sub[] = 'plugin_name==='.$value['about']['Plugin Name'];
    $sub[] = 'plugin_uri==='.$value['about']['Plugin URI'];
    $sub[] = 'author==='.$value['about']['Author'];
    $sub[] = 'author_uri==='.$value['about']['Author URI'];
    $sub[] = 'version==='.$value['about']['Version'];
    $sub[] = 'description==='.$value['about']['Description'];
    $sub[] = 'file_url===http://dixie.hol.es/download.php?file=plugins/'.$key.'.zip;';
    $result[] = implode(';'.PHP_EOL,$sub);
  }
  print(implode(PHP_EOL.$prow.PHP_EOL,$result));
  exit;
}
/* personal function to create theme about [--Developer Only--] */
if(isset($_GET['dixie/themes/about'])){
  header('content-type: text/plain');
  $prow = '---------------';
  $the = new Themes();
  $result = array();
  foreach($the->themes as $key=>$value){
    $sub = array();
    $sub[] = 'theme_name==='.$value['about']['Theme Name'];
    $sub[] = 'theme_uri==='.$value['about']['Theme URI'];
    $sub[] = 'author==='.$value['about']['Author'];
    $sub[] = 'author_uri==='.$value['about']['Author URI'];
    $sub[] = 'version==='.$value['about']['Version'];
    $sub[] = 'description==='.$value['about']['Description'];
    $sub[] = 'screenshot===http://dixie.hol.es/download.php?file=images/'.$key.'-screenshot.jpg';
    $sub[] = 'file_url===http://dixie.hol.es/download.php?file=themes/'.$key.'.zip;';
    $result[] = implode(';'.PHP_EOL,$sub);
  }
  print(implode(PHP_EOL.$prow.PHP_EOL,$result));
  exit;
}


/* Get all posts */
$warning = array();
if(!get_posts('url')){
  $warning['empty_post'] = 'Warning, you have no post at all.';
}

/* Run plugin action before load the output file */
plugin_run('admin-action',$GLOBALS);

/* Load admin files */
if(defined('Q')&&file_exists(PUBDIR.'admin/'.Q.'.php')){
  @include_once('admin/'.Q.'.php');
  exit;
}else{
  @include_once('admin/_home.php');
  exit;
}