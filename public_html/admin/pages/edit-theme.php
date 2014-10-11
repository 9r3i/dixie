<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Call a class: Themes */
$ct = new Themes();
$themes = $ct->themes;

/* Get theme-name */
$name = (isset($_GET['name']))?$_GET['name']:'';
$filename = (isset($_GET['file']))?$_GET['file']:'';

/* HTML View */
if(isset($themes[$name])){
  $theme = $themes[$name];
?>
<div class="content-form">
  <div><?php echo 'Name: '.$name; ?></div>
  <form action="<?php printf(WWW.'admin/edit-theme'); ?>" method="get" class="form-content">
    <input type="hidden" name="name" value="<?php echo $name; ?>" />
    <div>File: <select class="form-input" name="file" style="width:50%;">
      <?php
        unset($theme['name']);
        foreach($theme as $slug=>$file){
          if(preg_match('/(\.css|\.js|\.php)/i',$file)&&$file!=='options.php'){
            echo '<option value="'.$file.'"'.(($filename==$file)?' selected="true"':'').'>'.$file.'</option>';
          }
        }
      ?>
    </select><input type="submit" value="Open File" class="form-submit" style="font-size:14px" /></div>
  </form>
<?php
  if(isset($_GET['file'])&&file_exists('themes/'.$name.'/'.$filename)){
    $content = @file_get_contents('themes/'.$name.'/'.$filename);
?>
  <form action="<?php printf(WWW.'admin/a?data=edit-theme'); ?>" method="post" class="form-content">
    <div class="input-parent">Content file:<textarea name="content" placeholder="Insert the content theme here" class="form-textarea"><?php tprint($content); ?></textarea></div>
    <input type="hidden" name="theme-name" value="<?php printf($name); ?>" />
    <input type="hidden" name="file-name" value="<?php printf($filename); ?>" />
    <div><input type="submit" value="Update File" class="form-submit" /></div>
  </form>
</div>
<?php
  }
}else{
  echo 'theme doesn\'t exist';
}