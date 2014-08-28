<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Include content header */
include_once('header.php');

/* View content body */
$gip = generate_index_posts();
if(!$gip){
  echo 'Doesn\'t have a post yet';
}else{
  echo $gip;
}

/* Include content footer */
include_once('footer.php');