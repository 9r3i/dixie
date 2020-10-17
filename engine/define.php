<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Define _get p and q */
if(defined('R1')&&R1!==''){
  define('P',R1);
}elseif(isset($_GET['p'])){
  define('P',$_GET['p']);
}
if(defined('R2')&&R2!==''){
  define('Q',R2);
}elseif(isset($_GET['q'])){
  define('Q',$_GET['q']);
}

/* Define public_html directory */
define('PUBDIR','public_html/');

/* Define third_party directory */
define('THIRD_PARTY','third_party/');

/* Define Dixie Version */
define('DIXIE_VERSION','3.3.0');
define('DIXIE_REVISION','30300');

/* Privileges global */
global $privileges;
$privileges = array(
  'master',
  'admin',
  'editor',
  'author',
  'member',
);

/* Plugins $variable for plugins registry */
global $plugReg;

/* Globalize variable admin registry */
global $adminReg;
