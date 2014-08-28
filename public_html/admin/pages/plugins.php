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
  <div class="config-form"><a href="<?php echo WWW; ?>admin/new-plugin?_rdr" class="form-submit">Add Plugin</a></div>
</div>
<div class="all-posts">
  <?php
  foreach($plugins as $dir=>$value){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.$value['name'].'</div>';
      echo '<div class="post-option">
            <a href="'.WWW.'admin/plugin-option?name='.$value['dir'].'" style="color:#37b" title="Options">Options</a>
            <a href="'.WWW.'admin/confirmation?action='.((plugin_active($dir))?'deactivate':'activate').'-plugin&name='.$value['dir'].'" style="color:#'.((plugin_active($dir))?'b73':'7b3').'" title="'.((plugin_active($dir))?'Deactivate':'Activate').'">'.((plugin_active($dir))?'Deactivate':'Activate').'</a>
            '.((plugin_active($dir))?'':'<a href="'.WWW.'admin/confirmation?action=delete-plugin&name='.$value['dir'].'" style="color:#b73" title="Delete">Delete</a>').'
          </div>';
      $count_file = (count($theme)-1);
      echo '<div class="post-detail">Status: '.((plugin_active($dir))?'Active':'Inactive').'</div>';
      echo '</div>';
    
  }
  ?>
</div>