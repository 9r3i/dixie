<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Scan files directory */
$dirname = (isset($_GET['dir']))?(($_GET['dir']=='')?'upload/':$_GET['dir']):'upload/';
$files = scandir($dirname);
$dirs = dixie_explore('dir','upload');

/* HTML View */
?>
<div class="all-posts">
  <form action="<?php print(WWW); ?>admin/a?data=bulk-action-file" method="post" style="padding:0px;margin:0px;">
    <div class="config-body">
      <div class="config-form" style="width:100%;padding:0px;margin:0px 5px;">
        <a href="<?php echo WWW; ?>admin/upload?_rdr"><div class="button fs15">Upload</div></a>
        <select style="width:auto" name="dir" class="form-select" onchange="window.location.assign('?dir='+this.value)">
          <option value="upload/">upload/</option>
          <?php foreach($dirs as $dir) echo '<option value="'.$dir.'" '.(($dirname==$dir)?'selected="true"':'').'>'.$dir.'</option>'; ?>
        </select>
        <select style="width:115px;" name="action" class="form-select">
          <option value="">--Bulk Action--</option>
          <option value="delete">Delete</option>
          <option value="delete-all">Delete All</option>
        </select>
        <input type="submit" value="Do Action" class="form-submit fs15" />
        <a href="#" id="check_all" onclick="check_all('input-checkbox')"><div class="button fs15">Check All</div></a>
      </div>
    </div>
  <?php
  foreach($files as $id=>$file){
    if($file!=='.'&&$file!=='..'&&is_file($dirname.$file)){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title"><input type="checkbox" name="check[]" value="'.$dirname.$file.'" class="input-checkbox" id="check_'.$id.'" /><label for="check_'.$id.'">'.$file.'</lable></div>';
      echo '<div class="post-option">
            <a href="'.WWW.$dirname.$file.'" target="_blank" style="color:#37b">View</a>
            <a href="'.WWW.'admin/confirmation?action=rename-file&file='.$dirname.$file.'" style="color:#3b7">Rename</a>
            <a href="'.WWW.'admin/confirmation?action=delete-file&file='.$dirname.$file.'" style="color:#900">Delete</a>
          </div>';
      echo '<div class="post-detail">Size: '.ba_byte($dirname.$file).' | Uploaded Time: '.@date('F, jS Y H:i',@filectime($dirname.$file)).'</div>';
      echo '</div>';
    }
  }
  ?>
  </form>
</div>
<script type="text/javascript">
</script>
