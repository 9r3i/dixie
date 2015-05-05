<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Call a class: Themes */
$ct = new Themes();
$themes = $ct->themes;

/* HTML View */
?>
<div class="config-body">
  <a href="<?php echo WWW; ?>admin/new-theme?_rdr"><div class="button fs15"><div class="fas fa fa-plus"></div><?php __locale('Add Theme',true); ?></div></a>
</div>
<div class="all-posts">
  <?php
  foreach($themes as $dir=>$theme){
    $about = $theme['about'];
    echo '<div class="all-posts-each">';
      if(isset($about['ScreenShot'])){
        $screenshot_url = WWW.$ct->themes_dir.'/'.$dir.'/'.$about['ScreenShot'];
        if(file_exists($ct->themes_dir.'/'.$dir.'/'.$about['ScreenShot'])){
          echo '<div class="theme-screenshot"><a href="'.$screenshot_url.'" target="_blank" title="ScreenShot"><img id="screenshot_'.$dir.'" src="'.$screenshot_url.'" height="50px" /></a></div>';
        }
      }
    echo '<div class="post-title">'.((isset($about['Theme Name']))?$about['Theme Name']:$theme['name']).'</div>';
    echo '<div class="post-option">
            <a href="'.WWW.'admin/theme-option?name='.$theme['name'].'" style="color:#37b" title="'.__locale('Options').'"><div class="fas fa fa-wrench"></div>'.__locale('Options').'</a>
            <a href="'.WWW.'admin/edit-theme?name='.$theme['name'].'&file=index.php" style="color:#3b7" title="'.__locale('Edit the theme').'"><div class="fas fa fa-edit"></div>'.__locale('Edit').'</a>
            '.((get_site_info('theme',false)==$dir)?'':'<a href="'.WWW.'admin/confirmation?action=activate-theme&name='.$dir.'" style="color:#7b3" title="'.__locale('Activate').'"><div class="fas fa fa-send-o"></div>'.__locale('Activate').'</a><a href="'.WWW.'admin/confirmation?action=delete-theme&name='.$dir.'" style="color:#b73" title="'.__locale('Delete').'"><div class="fas fa fa-trash-o"></div>'.__locale('Uninstall').'</a>').'
          </div>';
    echo '<div class="post-detail">';
      if(isset($about['Version'])){
        echo __locale('Version').' '.$about['Version'];
      }
      if(isset($about['Author'])){
        if(isset($about['Author URI'])){
          echo ' | '.__locale('Author').': <a href="'.$about['Author URI'].'" title="'.__locale('Author').'" target="_blank">'.$about['Author'].'</a>';
        }else{
          echo ' | '.__locale('Author').': '.$about['Author'];
        }
      }
      if(isset($about['Theme URI'])){
        echo ' | <a href="'.$about['Theme URI'].'" title="'.__locale('Visit Theme Site').'" target="_blank">'.__locale('Visit Theme Site').'</a>';
      }
      if(isset($about['Description'])){
        echo ' | <span class="dedes" onclick="open_theme_description(\'theme_description_'.$dir.'\',\'screenshot_'.$dir.'\')" title="'.__locale('Open Theme Description').'">'.__locale('Description').'</span>';
      }
    echo '</div>'; // end of post-detail
      if(isset($about['Description'])){
        echo '<div class="post-description" id="theme_description_'.$dir.'">'.nrtobr(htmlspecialchars($about['Description'])).'</div>';
      }
    echo '<div class="clear-both"></div></div>';
  }
  ?>
</div>
<script type="text/javascript">
function open_theme_description(id,img){
  var el = document.getElementById(id);
  var elm = document.getElementById(img);
  var display = el.style.display;
  if(display=='block'){
    el.style.display="none";
    elm.setAttribute('height','50px');
  }else{
    el.style.display="block";
    elm.setAttribute('height','110px');
  }
}
</script>