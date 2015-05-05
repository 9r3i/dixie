<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Get global $update */
global $update;
if($update){
  echo '<div class="update-info">Update version '.$update['update_version'].' is available. <a href="'.WWW.'admin/a?data=update-dixie&update-uri='.urlencode($update['update_uri']).'" title="Update to version '.$update['update_version'].'"><button class="update-button">Update Now</button></a></div>';
}else{
?>
<div class="update-info">
  <?php __locale('Dixie is up to date',true); ?>.
</div>
<?php
}
?>
<div class="content-form" style="margin-top:20px;">
  <form action="<?php printf(WWW.'admin/a?data=update-dixie-upload'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div><?php __locale('Update manually',true); ?>? <span style="font-size:13px;color:#379;" title="<?php __locale('Requires ZIP file',true); ?>">(*.zip)</span></div>
    <div class="input-parent">
      <input type="file" name="file" class="form-input" title="<?php __locale('click to choose the file',true); ?>" style="width:70%;" />
      <input type="submit" value="<?php __locale('Upload',true); ?>" class="form-submit fs15" />
    </div>
  </form>
</div>