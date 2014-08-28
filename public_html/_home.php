<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global options and posts */
global $options,$posts;

/* Get site options */
if(!get_options()){
  header('location: '.WWW.'install/?ref=first');
  exit;
}

/* Set default timezone to Asia/Jakarta */
if(get_site_info('timezone',false)){
  date_default_timezone_set(get_site_info('timezone',false));
}else{
  global $ldb;
  ldb();
  $ldb->insert('options',array('key'=>'timezone','value'=>'Asia/Jakarta'));
  date_default_timezone_set('Asia/Jakarta');
}

/* Get all posts */
$where = (!is_login())?'status=publish&access=public':'';
get_posts('url',$where);

/* Call XML RSS Feed if requested */
if(defined('P')&&P=='feed.xml'){
  include_once('feed.php');
  exit;
}

/* Call Themes class */
$theme = new Themes();

/* Set theme index */
$theme_index = 'theme';
if(is_mobile_browser()){
  $theme_index = 'mobile_theme';
}elseif(is_msie()&&msie_version()<10){
  $theme_index = 'msie_theme';
}else{
  $theme_index = 'theme';
}

/* Set theme template according to setting options */
$template = get_index();

/* Site header content type */
header('content-type: text/html; charset=utf-8');

/* Set http response code to 404 Not Found */
if($template=='404'){
  header("HTTP/1.1 404 Not Found");
}

/* Load theme template */
$theme->load($options[$theme_index],$template);