<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Scan files directory */
$dirname = (isset($_GET['dir']))?(($_GET['dir']=='')?'upload/':$_GET['dir']):'upload/';
$files = scandir($dirname);
$dirs = dixie_explore('dir','upload');

/* HTML View */
?>
<script type="text/javascript" src="<?php print(WWW.PUBDIR); ?>admin/js/mozilla-upload.js"></script>
<div class="all-posts">
  <form action="<?php print(WWW); ?>admin/a?data=bulk-action-file" method="post" style="padding:0px;margin:0px;">
    <div class="config-body">
      <div class="config-form" style="width:100%;padding:0px;margin:0px 5px;">
        <a href="javascript:void(0)" id="upload"><div class="button fs15"><div class="fas fa fa-upload"></div><?php __locale('Upload',true); ?></div></a>
        <select style="width:auto" name="dir" class="form-select" onchange="window.location.assign('?dir='+this.value)">
          <option value="upload/">upload/</option>
          <?php foreach($dirs as $dir) echo '<option value="'.$dir.'" '.(($dirname==$dir)?'selected="true"':'').'>'.$dir.'</option>'; ?>
        </select>
        <select style="width:115px;" name="action" class="form-select">
          <option value="">--<?php __locale('Bulk Action',true); ?>--</option>
          <option value="delete"><?php __locale('Delete',true); ?></option>
          <option value="delete-all"><?php __locale('Delete All',true); ?></option>
        </select>
        <input type="submit" value="<?php __locale('Do Action',true); ?>" class="form-submit fs15" />
        <a href="#" id="check_all" onclick="check_all('input-checkbox')"><div class="button fs15"><div class="fas fa fa-check-square-o"></div><?php __locale('Check All',true); ?></div></a>
      </div>
    </div>
  <?php
  foreach($files as $id=>$file){
    if($file!=='.'&&$file!=='..'&&is_file($dirname.$file)){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">
        <div class="roundedOne">
          <input type="checkbox" name="check[]" value="'.$dirname.$file.'" class="input-checkbox" id="check_'.$id.'" />
          <label for="check_'.$id.'"></lable>
        </div>
        <label for="check_'.$id.'">'.$file.'</lable></div>';
      echo '<div class="post-option">
            <a href="'.WWW.$dirname.$file.'" target="_blank" style="color:#37b"><div class="fas fa fa-search"></div>'.__locale('View').'</a>
            <a href="'.WWW.'admin/confirmation?action=rename-file&file='.$dirname.$file.'" style="color:#3b7"><div class="fas fa fa-edit"></div>'.__locale('Rename').'</a>
            <a href="'.WWW.'admin/confirmation?action=delete-file&file='.$dirname.$file.'" style="color:#900"><div class="fas fa fa-trash-o"></div>'.__locale('Delete').'</a>
            <a href="javascript:alert(\''.__locale('Uploaded').': '.@date('Y-m-d H:i',@filectime($dirname.$file)).'\');" style="color:#555;white-space:nowrap;" title="File detail">'.ba_byte($dirname.$file).'</a>
          </div>';
      //echo '<div class="post-detail">'.__locale('Size').': '.ba_byte($dirname.$file).' | '.__locale('Uploaded').': '.@date('Y-m-d H:i',@filectime($dirname.$file)).'</div>';
      echo '</div>';
    }
  }
  ?>
  </form>
</div>
<div id="dialog" title="<?php __locale('Upload',true); ?>">
</div>


<script type="text/javascript">
var adialog = '<form action="<?php print(WWW); ?>admin/a?data=upload-file" method="post" enctype="multipart/form-data" onsubmit="AJAXSubmit(this); return false;" style="height:150px;">';
adialog += '<select style="width:100%" name="directory" class="form-select" onchange="if(this.value==\'new\') document.getElementById(\'new_dir\').style.display=\'block\'; else document.getElementById(\'new_dir\').style.display=\'none\';">';
adialog += '<option value="upload/">upload/</option>';
adialog += '<?php foreach($dirs as $dir) echo '<option value="'.$dir.'" '.(($dirname==$dir)?'selected="true"':'').'>'.$dir.'</option>'; ?>';
adialog += '<option value="new">--<?php __locale('Create New',true); ?>--</option>';
adialog += '</select>';
adialog += '<input type="text" name="new-directory" class="form-input" placeholder="<?php __locale('Directory',true); ?>" style="display:none;width:95%;" id="new_dir" />';
adialog += '<input type="file" id="file_upload" name="file[]" multiple="multiple" class="input-file" />';
adialog += '<input type="submit" value="<?php __locale('Upload',true); ?>" class="button fs15" onclick="loader2(this.id)" id="submit_upload" />';
adialog += '<input type="hidden" name="type" value="ajax" />';
adialog += '</form>';



$(function(){
  $("#dialog").dialog({
    autoOpen:false,
    show:{
      effect:"blind",
      duration:5
    },
    hide:{
      effect:"explode",
      duration:500
    }
  });
  $("#upload").click(function(){
    $("#dialog").html(adialog);
    $("#dialog").dialog("open");
  });
});

function loader2(id){
  var el = document.getElementById(id);
  var di = document.createElement('div');
  di.setAttribute('class','button fs15');
  di.innerHTML = '<img src="'+www+pubdir+'admin/images/loader.gif" /> Uploading...';
  setTimeout(function(){
    el.parentElement.appendChild(di);
    el.parentElement.removeChild(el);
  },100);
}
</script>
