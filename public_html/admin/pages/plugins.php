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

/* HTML View */
?>
<div class="config-body">
  <a href="<?php echo WWW; ?>admin/new-plugin?_rdr"><div class="button fs15">Add Plugin</div></a>
</div>
<div class="all-posts">
  <?php
  foreach($plugins as $dir=>$value){
      $about = $value['about'];
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.((isset($about['Plugin Name']))?$about['Plugin Name']:$value['name']).'</div>';
      echo '<div class="post-option">
            <a href="'.WWW.'admin/plugin-option?name='.$value['dir'].'" style="color:#37b" title="Options">Options</a>
            <a href="'.WWW.'admin/confirmation?action='.((plugin_active($dir))?'deactivate':'activate').'-plugin&name='.$value['dir'].'" style="color:#'.((plugin_active($dir))?'b73':'7b3').'" title="'.((plugin_active($dir))?'Deactivate':'Activate').'">'.((plugin_active($dir))?'Deactivate':'Activate').'</a>
            '.((plugin_active($dir))?'':'<a href="'.WWW.'admin/confirmation?action=delete-plugin&name='.$value['dir'].'" style="color:#b73" title="Delete">Uninstall</a>').'
          </div>';
      $count_file = (count($theme)-1);
      echo '<div class="post-detail">Status: '.((plugin_active($dir))?'Active':'Inactive');
      if(isset($about['Version'])){
        echo ' | Version '.$about['Version'];
      }
      if(isset($about['Author'])){
        if(isset($about['Author URI'])){
          echo ' | Author: <a href="'.$about['Author URI'].'" title="Author" target="_blank">'.$about['Author'].'</a>';
        }else{
          echo ' | Author: '.$about['Author'];
        }
      }
      if(isset($about['Plugin URI'])){
        echo ' | <a href="'.$about['Plugin URI'].'" title="Visit Plugin Site" target="_blank">Visit Plugin Site</a>';
      }
      if(isset($about['Description'])){
        echo ' | <span class="dedes" onclick="open_plugin_description(\'plug_description_'.$value['dir'].'\')" title="Open Plugin Description">Description</span>';
      }
      echo '</div>'; // end of div post-detail
      if(isset($about['Description'])){
        echo '<div class="post-description" id="plug_description_'.$value['dir'].'">'.nrtobr(htmlspecialchars($about['Description'])).'</div>';
      }
      echo '</div>';
  }
  ?>
</div>
<script type="text/javascript">
function open_plugin_description(id){
  var el = document.getElementById(id);
  var display = el.style.display;
  if(display=='block'){
    el.style.display="none";
  }else{
    el.style.display="block";
  }
}
</script>