<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Load required files */
require_once('define.php');
require_once('functions.php');
require_once('class.php');
require_once('Ldb.2.4.php');
require_once('gets.php');

/* Error display */
ini_set('display_errors',true);

/* Checking for an error reporting */
if(error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE)){
  
}

/* Start Dixie */
dixie_start();