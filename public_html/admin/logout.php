<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Perform logout action */
setcookie('dixie_login_cookie','',time()-60);
setcookie('dixie_privilege_cookie','',time()-60);
unset($_SESSION['dixie_login']);
unset($_SESSION['dixie_privilege']);
header('location: '.WWW.'admin/?status=logout');