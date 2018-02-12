<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* load ldb and nob file */
if(!defined('DROOT')){
  require_once('Ldb2x.php');
  require_once('nob.php');
}

/* Load required files */
require_once('define.php');
require_once('functions.php');
require_once('class.php');
require_once('language.php');
require_once('gets.php');

/* Error display */
ini_set('display_errors',true);

/* Checking for an error reporting */
if(error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE)){
  
}

/* Start Dixie */
dixie_start();