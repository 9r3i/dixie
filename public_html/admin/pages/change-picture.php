<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Configure uploaded files and directories */
$files = dixie_explore('file','upload');
$dirs = dixie_explore('dir','upload');

/* Global $ldb */
global $ldb;
ldb();

/* Set post data */
if(isset($_GET['post_id'])){
  $select = $ldb->select('posts','aid='.$_GET['post_id']);
  if(isset($select[0])){
    $post = $select[0];
    $image = (file_exists($post['picture']))?WWW.$post['picture']:WWW.PUBDIR.'images/unknown.png';

/* HTML View */
?>

<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=upload-post-picture'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div class="input-parent">
      <?php __locale('Directory',true); ?>
      <select class="form-select" name="directory" style="margin-left:10px;width:auto;" onchange="if(this.value=='new') document.getElementById('new_dir').style.display='block'; else document.getElementById('new_dir').style.display='none';">
        <option value="">--<?php __locale('Directory',true); ?>--</option>
        <?php foreach($dirs as $dir) echo '<option value="'.$dir.'">'.$dir.'</option>'; ?>
        <option value="new">--<?php __locale('Create New',true); ?>--</option>
      </select>
      <input type="text" name="new-directory" class="form-input" placeholder="<?php __locale('Directory',true); ?>" style="display:none;" id="new_dir" />
    </div>
    <div class="input-parent"> 
      <input style="width:70%;" type="file" name="file[]" class="form-input" title="click to choose the file" />
      <input type="hidden" value="<?php print($_GET['post_id']); ?>" name="post_id" />
      <input type="submit" value="<?php __locale('Upload',true); ?>" class="form-submit" />
    </div>
  </form>
  <form action="<?php printf(WWW.'admin/a?data=change-post-picture'); ?>" method="post" class="form-content">
    <div class="input-parent">
      <select class="form-select" name="file" style="width:70%;" onchange=" document.getElementById('image_post').setAttribute('src',this.value);">
        <?php
        echo '<option '.(!is_file($post['picture'])?' selected="selected"':'').' value="'.WWW.PUBDIR.'images/unknown.png">--'.__locale('Empty').'--</option>';
        foreach($files as $file){
          if(preg_match('/\.(jpg|jpeg|gif|png|bmp)$/i',$file)){
            echo '<option value="'.WWW.$file.'" '.(($post['picture']==$file)?'selected="true"':'').'>'.$file.'</option>';
          }
        }
        ?>
      </select>
      <input type="hidden" value="<?php print($_GET['post_id']); ?>" name="post_id" />
      <input type="submit" value="<?php __locale('Save',true); ?>" class="form-submit" />
    </div>
    <div class="input-parent"><img src="<?php print($image); ?>" class="form-input" width="70%" style="width:70%;max-width:300px;" id="image_post" /></div>
  </form>
</div>

<?php
  }else{
    echo 'Invalid post id';
  }
}else{
  echo 'No post id id found';
}