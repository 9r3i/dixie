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

/* Get categories */
$category = get_category();

if(isset($select[0])){
  $post = $select[0];
/* HTML View */
?>

<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=edit-post'); ?>" method="post" class="form-content">
    <div class="input-parent">Title<input type="text" name="title" class="form-input input-title" placeholder="Insert a title here" value="<?php tprint($post['title']); ?>" /></div>
    <div>Content
      <a href="<?php print(WWW); ?>admin/a?data=change-editor&to=<?php echo (get_site_info('post_editor',false)=='text')?'html':'text'; ?>&re=<?php print(urlencode(WWW.'admin/edit-post/?post_id='.$post_id)); ?>"><div id="change_editor">Change to <?php echo (get_site_info('post_editor',false)=='text')?'HTML':'Text'; ?></div></a>
      <a href="<?php print(WWW.$post['url']); ?>.html" title="View Post" target="_blank"><div id="view_post">View</div></a>
    </div>
    <div <?php echo (get_site_info('post_editor',false)=='text'||is_mobile_browser())?'class="input-parent"':''; ?>><textarea id="content" name="content" placeholder="Insert the content here" class="form-textarea"><?php tprint($post['content']); ?></textarea></div>

    <div id="post_configuration">
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
      <div class="config-form">Category<select class="form-select" name="category" id="select_category" style="width:120px;">
      <?php
        if(!array_key_exists('uncategorized',$category)){
          echo '<option value="Uncategorized">Uncategorized</option>';
        }
        foreach($category as $cat){echo '<option value="'.$cat['name'].'"'.((in_array($post['aid'],$cat['post_id']))?' selected="true"':'').'>'.$cat['name'].'</option>';}
      ?>
        <option value="create-new-category">--Create New--</option>
      </select></div>
      <div class="post-extension" style="display:block;padding:0px 20px 10px 5px;" id="new_cat"></div>
    </div>

    <div class="post-extension" id="picture_url" style="display:block;">
      <?php 
        $image = (file_exists($post['picture']))?WWW.$post['picture']:WWW.PUBDIR.'images/unknown.png';
      ?>
      <input type="hidden" name="picture" class="form-input" value="<?php tprint($post['picture']); ?>" />
      <img style="width:50%;" class="form-input" src="<?php print($image); ?>" />
      <a href="<?php print(WWW); ?>admin/change-picture/?post_id=<?php print($post['aid']); ?>"><div class="button" style="vertical-align:top;">Change Picture</div></a>
    </div>

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

      <div class="clear-both"></div>
    </div><!-- end of #post_configuration -->

    <input type="hidden" name="author" value="<?php printf($post['author']); ?>" />
    <input type="hidden" name="post_id" value="<?php printf($post['aid']); ?>" />
    <div><input type="submit" value="<?php echo (isset($_GET['new-post']))?'Publish':'Update'; ?>" class="form-submit" /></div>
  </form>
</div>
<script type="text/javascript">
document.getElementById("select_category").onchange=function(){
  if(this.value=='create-new-category'){
    var cat_input = '<input type="text" name="new-category" class="form-input" placeholder="New Category" />';
    document.getElementById("new_cat").innerHTML=cat_input;
  }
};

document.getElementById("select_type").onchange=function(){
  var hape = <?php echo (is_mobile_browser())?'true':'false'; ?>;
  if(hape){
    document.getElementById("page_content").style.height="800px";
  }

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
