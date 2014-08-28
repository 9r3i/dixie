<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Call a class: Plugins */
$plug = new Plugins();
$plugins = $plug->plugins;

/* Set get name */
$name = (isset($_GET['name']))?$_GET['name']:'';

if(isset($plugins[$name])){
  if(file_exists($plug->dir.'/'.$name.'/options.php')){
/* HTML View */
?>
<div class="content-form">
<?php @include_once($plug->dir.'/'.$name.'/options.php'); ?>
</div>
<?php
  }else{
    echo 'the plugin doesn\'t have optional file';
  }
}else{
  echo 'plugin doesn\'t exist';
}