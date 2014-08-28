<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Configure type, status, access and template */
$type = array('post','page','article','training','schedule','product','event');
$status = array('publish','draft','trash');
$access = array('public','private');
$template = array('standard');

/* Get post id and content */
$post_id = (isset($_GET['post_id']))?$_GET['post_id']:0;
global $ldb;
if(!ldb()){
  exit('cannot connect into database');
}
$select = $ldb->select('posts','aid='.$post_id);
if(isset($select[0])){
  $post = $select[0];
/* HTML View */
?>

<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=edit-post'); ?>" method="post" class="form-content">
    <div>Title<input type="text" name="title" class="form-input" placeholder="Insert a title here" value="<?php tprint($post['title']); ?>" /></div>
    <div>Content<textarea id="content" name="content" placeholder="Insert the content here" class="form-textarea"><?php tprint($post['content']); ?></textarea></div>
    <div class="config-header">Configurations</div>
    <div class="config-body">
      <div class="config-form">Status<select class="form-select" name="status">
      <?php
        foreach($status as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['status'])?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form">Type<select class="form-select" name="type" id="select_type">
      <?php
        foreach($type as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['type'])?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form">Access<select class="form-select" name="access">
      <?php
        foreach($access as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['access'])?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form">Template<select class="form-select" name="template">
      <?php
        foreach($template as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['template'])?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
    </div>
    <div class="post-extension" id="picture_url" style="display:block;">Picture URL<input type="text" name="picture" class="form-input" value="<?php tprint($post['picture']); ?>" list="uploaded_files" /></div>

    <div class="post-extension" id="post_description"<?php printf(($post['type']=='page'||$post['type']=='article')?' style="display:block;"':''); ?>>Description<input type="text" name="description" class="form-input" value="<?php tprint($post['description']); ?>" /></div>
    <div class="post-extension" id="post_keywords"<?php printf(($post['type']=='page'||$post['type']=='article')?' style="display:block;"':''); ?>>Keywords<input type="text" name="keywords" class="form-input" value="<?php tprint($post['keywords']); ?>" /></div>

    <div class="post-extension" id="type_trainer"<?php printf(($post['type']=='training')?' style="display:block;"':''); ?>>Trainer<input type="text" name="trainer" class="form-input" value="<?php tprint($post['trainer']); ?>" /></div>
    <div class="post-extension" id="type_training"<?php printf(($post['type']=='training')?' style="display:block;"':''); ?>>Training Date<input type="text" name="training_date" class="form-input" value="<?php tprint($post['training_date']); ?>" /></div>

    <div class="post-extension" id="schedule_note"<?php printf(($post['type']=='schedule')?' style="display:block;"':''); ?>>Note<input type="text" name="note" class="form-input" value="<?php tprint($post['note']); ?>" /></div>
    <div class="post-extension" id="schedule_expires"<?php printf(($post['type']=='schedule')?' style="display:block;"':''); ?>>Expires<input type="text" name="expires" class="form-input" value="<?php tprint($post['expires']); ?>" /></div>

    <div class="post-extension" id="product_price"<?php printf(($post['type']=='product')?' style="display:block;"':''); ?>>Price<input type="text" name="price" class="form-input" value="<?php tprint($post['price']); ?>" /></div>
    <div class="post-extension" id="product_barcode"<?php printf(($post['type']=='product')?' style="display:block;"':''); ?>>Barcode<input type="text" name="barcode" class="form-input" value="<?php tprint($post['barcode']); ?>" /></div>
    <div class="post-extension" id="product_stock"<?php printf(($post['type']=='product')?' style="display:block;"':''); ?>>Stock<input type="text" name="stock" class="form-input" value="<?php tprint($post['stock']); ?>" /></div>
    <div class="post-extension" id="product_account"<?php printf(($post['type']=='product')?' style="display:block;"':''); ?>>Account<input type="text" name="account" class="form-input" value="<?php tprint($post['account']); ?>" /></div>

    <div class="post-extension" id="event_place"<?php printf(($post['type']=='event')?' style="display:block;"':''); ?>>Place<input type="text" name="place" class="form-input" value="<?php tprint($post['place']); ?>" /></div>
    <div class="post-extension" id="event_host"<?php printf(($post['type']=='event')?' style="display:block;"':''); ?>>Host<input type="text" name="host" class="form-input" value="<?php tprint($post['host']); ?>" /></div>
    <div class="post-extension" id="event_start"<?php printf(($post['type']=='event')?' style="display:block;"':''); ?>>Start<input type="text" name="start" class="form-input" value="<?php tprint($post['start']); ?>" /></div>
    <div class="post-extension" id="event_end"<?php printf(($post['type']=='event')?' style="display:block;"':''); ?>>End<input type="text" name="end" class="form-input" value="<?php tprint($post['end']); ?>" /></div>
    <input type="hidden" name="author" value="<?php printf(get_active_user()); ?>" />
    <input type="hidden" name="post_id" value="<?php printf($post['aid']); ?>" />
    <div><input type="submit" value="Update" class="form-submit" /></div>
    <datalist id="uploaded_files">
      <?php
      $folder_files = 'upload/';
      $scan = @scandir($folder_files);
      if(is_array($scan)){
        foreach($scan as $each){
          if($each!=='.'&&$each!=='..'){echo '<option value="'.$folder_files.$each.'" />';}
        }
      }
      ?>
    </datalist>
  </form>
</div>
<script type="text/javascript">
document.getElementById("select_type").onchange=function(){
  if(this.value=="training"){
    document.getElementById("type_training").style.display="block";
    document.getElementById("type_trainer").style.display="block";
  }else{
    document.getElementById("type_training").style.display="none";
    document.getElementById("type_trainer").style.display="none";
  }
  if(this.value=='page'||this.value=='article'){
    document.getElementById("post_description").style.display="block";
    document.getElementById("post_keywords").style.display="block";
  }else{
    document.getElementById("post_description").style.display="none";
    document.getElementById("post_keywords").style.display="none";
  }
  if(this.value=='schedule'){
    document.getElementById("schedule_note").style.display="block";
    document.getElementById("schedule_expires").style.display="block";
  }else{
    document.getElementById("schedule_note").style.display="none";
    document.getElementById("schedule_expires").style.display="none";
  }
  if(this.value=='product'){
    document.getElementById("product_price").style.display="block";
    document.getElementById("product_barcode").style.display="block";
    document.getElementById("product_stock").style.display="block";
    document.getElementById("product_account").style.display="block";
  }else{
    document.getElementById("product_price").style.display="none";
    document.getElementById("product_barcode").style.display="none";
    document.getElementById("product_stock").style.display="none";
    document.getElementById("product_account").style.display="none";
  }
  if(this.value=='event'){
    document.getElementById("event_place").style.display="block";
    document.getElementById("event_host").style.display="block";
    document.getElementById("event_start").style.display="block";
    document.getElementById("event_end").style.display="block";
  }else{
    document.getElementById("event_place").style.display="none";
    document.getElementById("event_host").style.display="none";
    document.getElementById("event_start").style.display="none";
    document.getElementById("event_end").style.display="none";
  }
};
CKEDITOR.replace('content');
</script>
<?php
}else{
  echo 'The post doesn\'t exist';
}
