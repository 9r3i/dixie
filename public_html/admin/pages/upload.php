<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Configure directories */
$dirs = dixie_explore('dir','upload');

/* HTML View */
?>

<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=upload-file'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div class="input-parent">
      <?php __locale('Directory',true); ?>
      <select class="form-select" name="directory" style="margin-left:10px;width:auto;" onchange="if(this.value=='new') document.getElementById('new_dir').style.display='block'; else document.getElementById('new_dir').style.display='none';">
        <option value="">--<?php __locale('Directory',true); ?>--</option>
        <?php foreach($dirs as $dir) echo '<option value="'.$dir.'">'.$dir.'</option>'; ?>
        <option value="new">--<?php __locale('Create New',true); ?>--</option>
      </select>
      <input type="text" name="new-directory" class="form-input" placeholder="<?php __locale('Directory',true); ?>" style="display:none;" id="new_dir" />
    </div>
    <div class="input-parent"><?php __locale('Upload',true); ?><input type="file" name="file[]" class="form-input" title="<?php __locale('click to choose the file',true); ?>" /></div>
    <div id="new_file"></div>
    <div>
      <div href="#" id="add_file" onclick="add_file('new_file')"><?php __locale('Add File',true); ?></div>
      <input type="submit" value="<?php __locale('Upload',true); ?>" class="form-submit" />
    </div>
  </form>
  <input type="hidden" id="file_count" value="1" />
</div>
<script type="text/javascript">
function add_file(id){
  var nf = document.getElementById(id);
  var fc = document.getElementById('file_count');
  var count = fc.value;
  var pc = document.getElementById("page_content");
  var pcp = (count*40)+400;
  pc.style.height=pcp+"px";
  var newdiv = document.createElement('div');
  newdiv.setAttribute('class','input-parent');
  newdiv.innerHTML = '<input type="file" name="file[]" class="form-input" title="<?php __locale('click to choose the file',true); ?>" />';
  if(count<20){
    count++;
    nf.appendChild(newdiv);
    fc.value=count;
  }else{
    document.getElementById('add_file').style.display="none";
  }
}
</script>