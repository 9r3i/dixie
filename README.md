Dixie
=====
Free and Simple CMS;

```php
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

/* Start the engine files */
require_once('engine/start.php');

```


The first time I did commit directly to version 1.3, please check the change log to see the update.

See [Dixie CMS](http://dixie-cms.herokuapp.com/ "Dixie CMS") page.

-----
## How to Install

+ Simply download the newest version of Dixie as the ZIP file in [here](http://dixie-cms.herokuapp.com/blog/ "Dixie CMS").
+ Extract the file into directory you want to be intalled. Such as, /home/user4096/public_html/dixie/
+ Run Dixie. Such as, http://www.yourdomain.com/dixie/
+ You will see the installation page, and what you have to do is, fill the form correctly as it is.
+ You'll be asked an installation code (as version 2.0.0), input these words: LAY-ON-DIXIE
+ Then enjoy. :)

-----

[![Dixie - Free and Simple CMS](http://dixie-cms.herokuapp.com/blog/public_html/images/dixie-black.png)](http://dixie-cms.herokuapp.com/ "Dixie CMS")
