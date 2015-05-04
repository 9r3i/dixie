<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Define Dixie to be true */
define('DIXIE',true);

/* change working directory */
if(defined('BAZZMU')&&BAZZMU===true){
  $droot = str_replace(ROOT,'',str_replace('\\','/',__DIR__));
  chdir($droot);
  /* define dixie root */
  define('DROOT',ROOT.$droot.'/');
}

/* Start the engine files */
require_once('engine/start.php');