<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Perform logout action */
unset($_SESSION['dixie_login']);
unset($_SESSION['dixie_privilege']);
header('location: '.WWW.'admin/?status=logout');