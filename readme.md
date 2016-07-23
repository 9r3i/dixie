Dixie
=====
Free and Simple CMS;

[![Author](https://img.shields.io/badge/author-9r3i-lightgrey.svg)](https://github.com/9r3i)
[![License](https://img.shields.io/github/license/9r3i/dixie.svg)](https://github.com/9r3i/dixie/blob/master/license.txt)
[![Forks](https://img.shields.io/github/forks/9r3i/dixie.svg)](https://github.com/9r3i/dixie/network)
[![Stars](https://img.shields.io/github/stars/9r3i/dixie.svg)](https://github.com/9r3i/dixie/stargazers)
[![Issues](https://img.shields.io/github/issues/9r3i/dixie.svg)](https://github.com/9r3i/dixie/issues)
[![Release](https://img.shields.io/github/release/9r3i/dixie.svg)](https://github.com/9r3i/dixie/releases)
[![Donate](https://camo.githubusercontent.com/11b2f47d7b4af17ef3a803f57c37de3ac82ac039/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f70617970616c2d646f6e6174652d79656c6c6f772e737667)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=5VLYA8SDV3CTG&lc=ID&item_name=Software%20Developer&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted "Donate")


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
