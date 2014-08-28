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
  <div class="config-form"><a href="<?php echo WWW; ?>admin/new-theme?_rdr" class="form-submit">Add Theme</a></div>
</div>
<div class="all-posts">
  <?php
  foreach($themes as $dir=>$theme){
    echo '<div class="all-posts-each">';
    echo '<div class="post-title">'.$theme['name'].'</div>';
    echo '<div class="post-option">
            <a href="'.WWW.'admin/theme-option?name='.$theme['name'].'" style="color:#37b" title="Options">Options</a>
            <a href="'.WWW.'admin/edit-theme?name='.$theme['name'].'&file=index.php" style="color:#3b7" title="Edit the theme">Edit</a>
            '.((get_site_info('theme',false)==$dir)?'':'<a href="'.WWW.'admin/confirmation?action=activate-theme&name='.$dir.'" style="color:#7b3" title="Activate">Activate</a><a href="'.WWW.'admin/confirmation?action=delete-theme&name='.$dir.'" style="color:#b73" title="Delete">Delete</a>').'
          </div>';
    $count_file = (count($theme)-1);
    echo '<div class="post-detail">'.$count_file.' file'.(($count_file>1)?'s':'').'</div>';
    echo '</div>';
  }
  ?>
</div>