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

/* HTML View */
?>
<div class="config-body">
  <a href="<?php echo WWW; ?>admin/new-theme?_rdr"><div class="button fs15">Add Theme</div></a>
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
            <a href="'.WWW.'admin/theme-option?name='.$theme['name'].'" style="color:#37b" title="Options">Options</a>
            <a href="'.WWW.'admin/edit-theme?name='.$theme['name'].'&file=index.php" style="color:#3b7" title="Edit the theme">Edit</a>
            '.((get_site_info('theme',false)==$dir)?'':'<a href="'.WWW.'admin/confirmation?action=activate-theme&name='.$dir.'" style="color:#7b3" title="Activate">Activate</a><a href="'.WWW.'admin/confirmation?action=delete-theme&name='.$dir.'" style="color:#b73" title="Delete">Uninstall</a>').'
          </div>';
    echo '<div class="post-detail">';
      if(isset($about['Version'])){
        echo 'Version '.$about['Version'];
      }
      if(isset($about['Author'])){
        if(isset($about['Author URI'])){
          echo ' | Author: <a href="'.$about['Author URI'].'" title="Author" target="_blank">'.$about['Author'].'</a>';
        }else{
          echo ' | Author: '.$about['Author'];
        }
      }
      if(isset($about['Theme URI'])){
        echo ' | <a href="'.$about['Theme URI'].'" title="Visit Theme Site" target="_blank">Visit Theme Site</a>';
      }
      if(isset($about['Description'])){
        echo ' | <span class="dedes" onclick="open_theme_description(\'theme_description_'.$dir.'\',\'screenshot_'.$dir.'\')" title="Open Theme Description">Description</span>';
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