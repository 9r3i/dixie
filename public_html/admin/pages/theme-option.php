<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Call a class: Themes */
$ct = new Themes();
$themes = $ct->themes;

/* Set get name */
$name = (isset($_GET['name']))?$_GET['name']:'';

if(isset($themes[$name])){
  if(file_exists($ct->themes_dir.'/'.$name.'/options.php')){
/* HTML View */
?>
<div class="content-form">
<?php @include_once($ct->themes_dir.'/'.$name.'/options.php'); ?>
</div>
<?php
  }else{
    echo __locale('the theme doesn\'t have optional file');
  }
}else{
  echo __locale('theme doesn\'t exist');
}