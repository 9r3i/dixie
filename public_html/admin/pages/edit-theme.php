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
  /* rewrite variable $theme as version 3.0 using dixie_explore function */
  $tdir = 'themes/'.$name.'/';
  $theme = dixie_explore('file',$tdir);
  //echo '<pre>'.print_r($themes,true).'</pre>';
?>
<div class="content-form">
  <div><?php echo __locale('Name').': '.$themes[$name]['about']['Theme Name']; ?></div>
  <!--<form action="<?php printf(WWW.'admin/edit-theme'); ?>" method="get" class="form-content">
    <input type="hidden" name="name" value="<?php echo $name; ?>" />
    <div><?php __locale('File',true); ?>: <select onchange="open_theme_file('<?php echo $name; ?>',this.value)" class="form-input" name="file" style="width:50%;">
      <?php
        unset($theme['name']);
        foreach($theme as $slug=>$file){
          $file = str_replace($tdir,'',$file);
          echo '<option value="'.$file.'"'.(($filename==$file)?' selected="true"':'').'>'.$file.'</option>';
        }
      ?>
    </select>
    <input type="submit" value="<?php __locale('Open File',true); ?>" class="form-submit" style="font-size:14px" />
    </div>
  </form>-->
<?php
  if(isset($_GET['file'])&&file_exists('themes/'.$name.'/'.$filename)){
    $content = @file_get_contents('themes/'.$name.'/'.$filename);
?>
  <form action="<?php printf(WWW.'admin/a?data=edit-theme'); ?>" method="post" class="form-content">
    <div><?php __locale('File',true); ?>: <select onchange="open_theme_file('<?php echo $name; ?>',this.value)" class="form-input" name="file" style="width:50%;">
      <?php
        unset($theme['name']);
        foreach($theme as $slug=>$file){
          $file = str_replace($tdir,'',$file);
          echo '<option value="'.$file.'"'.(($filename==$file)?' selected="true"':'').'>'.$file.'</option>';
        }
      ?>
    </select>
    <input type="submit" value="<?php __locale('Update File',true); ?>" class="form-submit" />
    </div>
    <div class="input-parent"><?php __locale('Content file',true); ?>:<textarea name="content" placeholder="Insert the content theme here" class="form-textarea" style="height:300px;font-family:monospace;color:#eee;background-color:#333;"><?php tprint($content); ?></textarea></div>
    <input type="hidden" name="theme-name" value="<?php printf($name); ?>" />
    <input type="hidden" name="file-name" value="<?php printf($filename); ?>" />
  </form>
</div>
<script type="text/javascript">
</script>
<?php
  }
}else{
  echo __locale('theme doesn\'t exist');
}