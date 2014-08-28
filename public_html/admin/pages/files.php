<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Scan files directory */
$dirname = 'upload/';
$files = scandir($dirname);

/* HTML View */
?>
<div class="config-body">
  <div class="config-form"><a href="<?php echo WWW; ?>admin/upload?_rdr" class="form-submit">Upload</a></div>
</div>
<div class="all-posts">
  <?php
  foreach($files as $id=>$file){
    if($file!=='.'&&$file!=='..'&&is_file($dirname.$file)){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.$file.'</div>';
      echo '<div class="post-option">
            <a href="'.WWW.$dirname.$file.'" target="_blank" style="color:#37b">View</a>
            <a href="'.WWW.'admin/confirmation?action=rename-file&file='.$dirname.$file.'" style="color:#3b7">Rename</a>
            <a href="'.WWW.'admin/confirmation?action=delete-file&file='.$dirname.$file.'" style="color:#900">Delete</a>
          </div>';
      echo '<div class="post-detail">Uploaded Time: '.date('F, jS Y H:i',filectime($dirname.$file)).' | Created Time: '.date('F, jS Y H:i',filemtime($dirname.$file)).'</div>';
      echo '</div>';
    }
  }
  ?>
</div>
