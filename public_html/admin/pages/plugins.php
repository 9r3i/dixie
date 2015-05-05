<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Call a class: Plugins */
$plug = new Plugins();
$plugins = $plug->plugins;

/* HTML View */
?>
<div class="config-body">
  <a href="<?php echo WWW; ?>admin/new-plugin?_rdr"><div class="button fs15"><div class="fas fa fa-plus"></div><?php __locale('Add Plugin',true); ?></div></a>
</div>
<div class="all-posts">
  <?php
  foreach($plugins as $dir=>$value){
      $about = $value['about'];
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.((isset($about['Plugin Name']))?$about['Plugin Name']:$value['name']).'</div>';
      echo '<div class="post-option">
            <a href="'.WWW.'admin/plugin-option?name='.$value['dir'].'" style="color:#37b" title="'.__locale('Options').'"><div class="fas fa fa-wrench"></div>'.__locale('Options').'</a>
            <a href="'.WWW.'admin/edit-plugin?name='.$value['dir'].'" style="color:#3b7" title="'.__locale('Edit the plugin').'"><div class="fas fa fa-edit"></div>'.__locale('Edit').'</a>
            <a href="'.WWW.'admin/confirmation?action='.((plugin_active($dir))?'deactivate':'activate').'-plugin&name='.$value['dir'].'" style="color:#'.((plugin_active($dir))?'b73':'7b3').'" title="'.((plugin_active($dir))?'Deactivate':'Activate').'">'.((plugin_active($dir))?'<div class="fas fa fa-power-off"></div>'.__locale('Deactivate'):'<div class="fas fa fa-send"></div>'.__locale('Activate')).'</a>
            '.((plugin_active($dir))?'':'<a href="'.WWW.'admin/confirmation?action=delete-plugin&name='.$value['dir'].'" style="color:#b73" title="'.__locale('Delete').'"><div class="fas fa fa-trash-o"></div>'.__locale('Uninstall').'</a>').'
          </div>';
      $count_file = (count($theme)-1);
      echo '<div class="post-detail">'.__locale('Status').': '.__locale((plugin_active($dir))?'Active':'Inactive');
      if(isset($about['Version'])){
        echo ' | '.__locale('Version').' '.$about['Version'];
      }
      if(isset($about['Dixie Compare Version'])){ // DCV = Dixie Compare Version
        echo ' | DCV >= '.$about['Dixie Compare Version'];
      }
      if(isset($about['Author'])){
        if(isset($about['Author URI'])){
          echo ' | '.__locale('Author').': <a href="'.$about['Author URI'].'" title="'.__locale('Author').'" target="_blank">'.$about['Author'].'</a>';
        }else{
          echo ' | '.__locale('Author').': '.$about['Author'];
        }
      }
      if(isset($about['Plugin URI'])){
        echo ' | <a href="'.$about['Plugin URI'].'" title="'.__locale('Visit Plugin Site').'" target="_blank">'.__locale('Visit Plugin Site').'</a>';
      }
      if(isset($about['Description'])){
        echo ' | <span class="dedes" onclick="open_plugin_description(\'plug_description_'.$value['dir'].'\')" title="'.__locale('Open Plugin Description').'">'.__locale('Description').'</span>';
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