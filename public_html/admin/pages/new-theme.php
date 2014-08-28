<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=upload-theme'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div>Upload Theme <span style="font-size:13px;color:#379;" title="Requires ZIP file">(*.zip)</span><input type="file" name="file" class="form-input" title="click to choose the file" /></div>
    <div><input type="submit" value="Upload" class="form-submit" /></div>
  </form>
</div>