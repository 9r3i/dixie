<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Global options and posts */
global $options,$posts;

/* Get data */
$data = array();
foreach($posts as $post){
  if($post['type']!=='page'&&$post['status']=='publish'&&$post['access']=='public'){
    $data[] = WWW.$post['url'].'.html';
  }
}

header('content-type: text/plain');
print(implode(PHP_EOL,$data));