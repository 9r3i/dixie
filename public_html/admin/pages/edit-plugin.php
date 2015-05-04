<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Call a class: Plugins */
$plugs = new Plugins();
$plugins = $plugs->plugins;
$types = $plugs->types;

/* get plugin name */
if(isset($_GET['name'],$plugins[$_GET['name']])&&is_dir($plugs->dir.'/'.$plugins[$_GET['name']]['dir'])){
  $plug = $plugins[$_GET['name']];
  $plugdir = $plugs->dir.'/'.$plug['dir'].'/';
  $files = dixie_explore('file',$plugdir);
  $name = $plug['about']['Plugin Name'];
  $slug = $_GET['name'];
  $filename = isset($_GET['file'])?$_GET['file']:'';
?>
<div class="content-form">
  <div><?php echo __locale('Name').': '.$name; ?></div>
  <form action="<?php printf(WWW.'admin/a?data=edit-plugin'); ?>" method="post" class="form-content">
    <div><?php __locale('File',true); ?>: <select onchange="open_plugin_file('<?php echo $slug; ?>',this.value)" class="form-input" name="file" style="width:50%;">
      <?php
        echo !isset($_GET['file'])?'<option value="" selected="true">-- Select a file --</option>':'';
        foreach($files as $file){
          $file = str_replace($plugdir,'',$file);
          echo '<option value="'.$file.'"'.(($filename==$file)?' selected="true"':'').'>'.$file.'</option>';
        }
      ?>
    </select>
<?php if(isset($_GET['file'])&&is_file($plugdir.$_GET['file'])){ ?>
    <input type="submit" value="<?php __locale('Update File',true); ?>" class="form-submit" />
<?php } ?>
    </div>
<?php
  if(isset($_GET['file'])&&is_file($plugdir.$_GET['file'])){
    $content = @file_get_contents($plugdir.$_GET['file']);
?>
    <div class="input-parent"><?php __locale('Content file',true); ?>:<textarea name="content" placeholder="Insert the content file here" class="form-textarea" style="height:300px;font-family:monospace;color:#eee;background-color:#333;"><?php tprint($content); ?></textarea></div>
<?php
  }else{
    echo isset($_GET['file'])?'File doesn\'t exist.':'Please select a file correctly.';
  }
?>
    <input type="hidden" name="slug" value="<?php printf($slug); ?>" />
  </form>
</div>
<?php
}else{
  echo 'Plugin doesn\'t exist';
}


