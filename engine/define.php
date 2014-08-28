<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Define the root of site */
define('ROOT',strtr(dirname(__FILE__),array('\\'=>'/','engine'=>'')));

/* Define important directory of site */
define('DIR',str_replace($_SERVER['DOCUMENT_ROOT'],'',ROOT));

/* Define address of site */
define('WWW','http://'.$_SERVER["SERVER_NAME"].DIR);

// *** Define _get p and q *** //
if(isset($_GET['p'])){
  define('P',$_GET['p']);
}
if(isset($_GET['q'])){
  define('Q',$_GET['q']);
}

/* Define public_html directory */
define('PUBDIR','public_html/');

/* Define Dixie Version */
define('DIXIE_VERSION','1.3');
define('DIXIE_REVISION','e81c');

/* Privileges global */
global $privileges;
$privileges = array(
  'master',
  'admin',
  'editor',
  'author',
  'member',
);

// *** Plugins $variable for plugins registry *** //
global $plugReg;