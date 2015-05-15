<?php
/* Dixie - Free and Simple CMS;
 *
 * Scripting Language    : PHP and Javascript;
 * PHP-Framework         : None or Bazzmu (optional);
 * Javascript-Framework  : jQuery v2.1.3 and jQuery UI v1.11.4;
 * Database              : Ldb Database v2.5 - Custom Portable Database;
 * Dixie-URI             : http://dixie-cms.herokuapp.com/;
 * Dixie-Author          : Luthfie a.k.a. 9r3i;
 * Author-Email          : Luthfie@y7mail.com;
 * Author-URI            : http://luthfie.hol.es/;
 * Third-party           : + CKEditor as content-editor,
 *                         + nicEdit as content-editor for mobile,
 *                         + Locale package as custom-local-language;
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

/* setup for Ldb2 cross domain */
define('LCD',false);
define('LCD_TOKEN',false);

/* Start the engine files */
require_once('engine/start.php');
