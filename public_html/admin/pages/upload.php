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
  <form action="<?php printf(WWW.'admin/a?data=upload-file'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div>Upload<input type="file" name="file[]" class="form-input" title="click to choose the file" /></div>
    <div id="new_file"></div>
    <div><input type="submit" value="Upload" class="form-submit" /><a href="#" id="add_file" onclick="add_file('new_file')">Add File</a></div>
  </form>
</div>
<script type="text/javascript">
function add_file(id){
  var nf = document.getElementById(id);
  var newdiv = document.createElement('div');
  newdiv.innerHTML = '<input type="file" name="file[]" class="form-input" title="click to choose the file" />';
  nf.appendChild(newdiv);
}
</script>