<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Get global $update */
global $update;
if($update){
  echo '<div class="update-info">Update version '.$update['update_version'].' is available. <a href="'.WWW.'admin/a?data=update-dixie&update-uri='.urlencode($update['update_uri']).'" title="Update to version '.$update['update_version'].'"><button class="update-button">Update Now</button></a></div>';
}else{
?>
<div class="update-info">
  Dixie is up to date.
</div>
<?php
}
?>
<div class="content-form" style="margin-top:20px;">
  <form action="<?php printf(WWW.'admin/a?data=update-dixie-upload'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div>Update manually? <span style="font-size:13px;color:#379;" title="Requires ZIP file">(.zip)</span></div>
    <div class="input-parent">
      <input type="file" name="file" class="form-input" title="click to choose the file" style="width:70%;" />
      <input type="submit" value="Upload" class="form-submit fs15" />
    </div>
  </form>
</div>