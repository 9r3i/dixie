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

/* Check session login */
if(!is_login()){
  include_once('admin/login.php');
  exit;
}

/* Refix admin uri */
if(!preg_match('/admin\//i',$_SERVER['REQUEST_URI'])){
  header('location: '.str_replace('admin','admin/',$_SERVER['REQUEST_URI']));
  exit;
}

/* Get all posts */
$warning = array();
if(!get_posts('url')){
  $warning['empty_post'] = 'Warning, you have no post at all.';
}

/* Load admin files */
if(defined('Q')&&file_exists(PUBDIR.'admin/'.Q.'.php')){
  @include_once('admin/'.Q.'.php');
  exit;
}else{
  @include_once('admin/_home.php');
  exit;
}