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

/* Get post id and content */
$post_id = (isset($_GET['post_id']))?$_GET['post_id']:0;
global $ldb;
if(!ldb()){
  exit('cannot connect into database');
}

/* select by username as author */
if(sdp()>=8){
  $select = $ldb->select('posts','aid='.$post_id);
}else{
  $select = $ldb->select('posts','aid='.$post_id.'&author='.get_active_user());
}

/* get templates of current theme */
$ctheme = get_site_info('theme',false);
$themes = new Themes();
$template = array_merge((array)'standard',array_keys($themes->themes[$ctheme]['template']));

/* Get categories */
$category = get_category();

/* if data post exist */
if(isset($select[0])){
  $post = $select[0];
  //$post_content = is_mobile_browser()?strip_tags($post['content']):$post['content'];
  $post_content = $post['content'];
/* HTML View */
?>

<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=edit-post'); ?>" method="post" class="form-content">

    <div id="post_main">
    <div class="input-parent"><?php __locale('Title',true); ?><input type="text" name="title" class="form-input input-title" placeholder="<?php __locale('Insert a title here',true); ?>" value="<?php tprint($post['title']); ?>" /></div>
    <div><?php __locale('Content',true); ?>
	    <input type="submit" name="submit" value="<?php __locale('Change to',true); ?> <?php echo (get_site_info('post_editor',false)=='text')?'HTML':'Text'; ?>" id="change_editor" />
	    <input type="submit" name="submit" value="<?php __locale('View',true); ?>" id="view_post" />
    </div>
    <div <?php echo (get_site_info('post_editor',false)=='text')?'class="input-parent"':''; ?>><textarea id="content" name="content" placeholder="Insert the content here" class="form-textarea"><?php tprint($post_content); ?></textarea></div>

    <div style="margin-top:20px;"></div>

  <table style="width:100%" width="100%" border="0" class="table-extension"><tbody>

    <tr class="post-extension" id="post_description"<?php printf(($post['type']=='page'||$post['type']=='article')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Description',true); ?></td>
      <td class="extension-detail"><input type="text" name="description" class="form-input" value="<?php tprint($post['description']); ?>" /></td>
    </tr>
  
    <tr class="post-extension" id="post_keywords"<?php printf(($post['type']=='page'||$post['type']=='article')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Keywords',true); ?></td>
      <td class="extension-detail"><input type="text" name="keywords" class="form-input" value="<?php tprint($post['keywords']); ?>" /></td>
    </tr>

    <tr class="post-extension" id="type_trainer"<?php printf(($post['type']=='training')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Trainer',true); ?></td>
      <td class="extension-detail"><input type="text" name="trainer" class="form-input" value="<?php tprint($post['trainer']); ?>" /></td>
    </tr>
    <tr class="post-extension" id="type_training"<?php printf(($post['type']=='training')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Training Date',true); ?></td>
      <td class="extension-detail"><input style="width:45%;" type="text" name="training_date" class="form-input" value="<?php tprint(date('m/d/Y',generate_training_date($post['training_date']))); ?>" id="training_date" autocomplete="off" />
      <input style="width:45%;" type="text" name="training_date_end" class="form-input" value="<?php tprint(date('m/d/Y',generate_training_date($post['training_date'],true))); ?>" id="training_date_end" autocomplete="off" /></td>
    </tr>

    <tr class="post-extension" id="schedule_note"<?php printf(($post['type']=='schedule')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Note',true); ?></td>
      <td class="extension-detail"><input type="text" name="note" class="form-input" value="<?php tprint($post['note']); ?>" /></td>
    </tr>
    <tr class="post-extension" id="schedule_expires"<?php printf(($post['type']=='schedule')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Expires',true); ?></td>
      <td class="extension-detail"><input type="text" name="expires" class="form-input" value="<?php tprint($post['expires']); ?>" id="expires" autocomplete="off" /></td>
    </tr>

    <tr class="post-extension" id="product_price"<?php printf(($post['type']=='product'||$post['type']=='training')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Price',true); ?></td>
      <td class="extension-detail"><input type="text" name="price" class="form-input" value="<?php tprint($post['price']); ?>" /></td>
    </tr>
    <tr class="post-extension" id="product_barcode"<?php printf(($post['type']=='product')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Barcode',true); ?></td>
      <td class="extension-detail"><input type="text" name="barcode" class="form-input" value="<?php tprint($post['barcode']); ?>" /></td>
    </tr>
    <tr class="post-extension" id="product_stock"<?php printf(($post['type']=='product')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Stock',true); ?></td>
      <td class="extension-detail"><input type="text" name="stock" class="form-input" value="<?php tprint($post['stock']); ?>" /></td>
    </tr>
    <tr class="post-extension" id="product_account"<?php printf(($post['type']=='product')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Account',true); ?></td>
      <td class="extension-detail"><input type="text" name="account" class="form-input" value="<?php tprint($post['account']); ?>" /></td>
    </tr>

    <tr class="post-extension" id="event_place"<?php printf(($post['type']=='event'||$post['type']=='training')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Place',true); ?></td>
      <td class="extension-detail"><input type="text" name="place" class="form-input" value="<?php tprint($post['place']); ?>" /></td>
    </tr>
    <tr class="post-extension" id="event_host"<?php printf(($post['type']=='event')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Host',true); ?></td>
      <td class="extension-detail"><input type="text" name="host" class="form-input" value="<?php tprint($post['host']); ?>" /></td>
    </tr>
    <tr class="post-extension" id="event_start"<?php printf(($post['type']=='event')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('Start',true); ?></td>
      <td class="extension-detail"><input type="text" name="start" class="form-input" value="<?php tprint($post['start']); ?>" id="start" autocomplete="off" /></td>
    </tr>
    <tr class="post-extension" id="event_end"<?php printf(($post['type']=='event')?' style="display:block;"':''); ?>>
      <td class="extension-label"><?php __locale('End',true); ?></td>
      <td class="extension-detail"><input type="text" name="end" class="form-input" value="<?php tprint($post['end']); ?>" id="end" autocomplete="off" /></td>
    </tr>
  </tbody></table>
    </div>

    <div id="post_configuration">

    <div class="config-header"><?php __locale('Action',true); ?></div>
    <div class="config-body">
      <div class="config-form"><input name="submit" type="submit" value="<?php echo (isset($_GET['new-post']))?__locale('Publish'):__locale('Update'); ?>" class="form-submit" /></div>
      <div class="config-form">
        <a href="<?php print(WWW); ?>admin/confirmation?action=trash&post_id=<?php print($post['aid']); ?>"><div class="button fs17"><div class="fas fa fa-trash-o"></div><?php __locale('Delete',true); ?></div></a>
      </div>
    </div>

    <div class="config-header"><?php __locale('Configurations',true); ?></div>
    <div class="config-body">
      <div class="config-form"><?php __locale('Status',true); ?><select class="form-select" name="status">
      <?php
        foreach($status as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['status'])?' selected="true"':'').'>'.ucwords(__locale($stat)).'</option>';}
      ?>
      </select></div>
      <div class="config-form"><?php __locale('Type',true); ?><select class="form-select" name="type" id="select_type">
      <?php
        foreach($type as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['type'])?' selected="true"':'').'>'.__locale(ucwords($stat)).'</option>';}
      ?>
      </select></div>
      <div class="config-form"><?php __locale('Access',true); ?><select class="form-select" name="access">
      <?php
        foreach($access as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['access'])?' selected="true"':'').'>'.__locale(ucwords($stat)).'</option>';}
      ?>
      </select></div>
      <div class="config-form"><?php __locale('Template',true); ?><select class="form-select" name="template">
      <?php
        foreach($template as $stat){echo '<option value="'.$stat.'"'.(($stat==$post['template'])?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form"><?php __locale('Category',true); ?><select class="form-select" name="category" id="select_category" style="width:120px;">
      <?php
        if(!array_key_exists('uncategorized',$category)){
          echo '<option value="Uncategorized">Uncategorized</option>';
        }
        foreach($category as $cat){echo '<option value="'.$cat['name'].'"'.((in_array($post['aid'],$cat['post_id']))?' selected="true"':'').'>'.$cat['name'].'</option>';}
      ?>
        <option value="create-new-category">--<?php __locale('Create New',true); ?>--</option>
      </select></div>
      <div class="post-extension" style="display:inline-block;padding:0px 20px 10px 5px;" id="new_cat"></div>
    </div>

    <div class="post-extension" id="picture_url" style="display:block;">
      <?php 
        $image = (file_exists($post['picture']))?WWW.$post['picture']:WWW.PUBDIR.'images/unknown.png';
      ?>
	  <input type="submit" name="submit" value="<?php __locale('Change Picture',true); ?>" id="change_picture" style="vertical-align:top;" />
      <input type="hidden" name="picture" class="form-input" value="<?php tprint($post['picture']); ?>" />
      <img style="width:100%;max-width:237px;" class="form-input" src="<?php print($image); ?>" />
    </div>
      <div class="clear-both"></div>
    </div><!-- end of #post_configuration -->

    <input type="hidden" name="author" value="<?php printf($post['author']); ?>" />
    <input type="hidden" name="post_id" value="<?php printf($post['aid']); ?>" />
  </form>
</div>

<script type="text/javascript">
$(function(){
  $("#training_date").datepicker();
  $("#training_date_end").datepicker();
  $("#expires").datepicker();
  $("#start").datepicker();
  $("#end").datepicker();
});

document.getElementById("select_category").onchange=function(){
  if(this.value=='create-new-category'){
    var cat_input = '<input type="text" name="new-category" class="form-input" placeholder="New Category" style="width:200px;" />';
    document.getElementById("new_cat").innerHTML=cat_input;
  }else{
    document.getElementById("new_cat").innerHTML='';
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
  if(this.value=='product'||this.value=='training'){
    document.getElementById("product_price").style.display="block";
  }else{
    document.getElementById("product_price").style.display="none";
  }
  if(this.value=='product'){
    document.getElementById("product_barcode").style.display="block";
    document.getElementById("product_stock").style.display="block";
    document.getElementById("product_account").style.display="block";
  }else{
    document.getElementById("product_barcode").style.display="none";
    document.getElementById("product_stock").style.display="none";
    document.getElementById("product_account").style.display="none";
  }
  if(this.value=='event'||this.value=='training'){
    document.getElementById("event_place").style.display="block";
  }else{
    document.getElementById("event_place").style.display="none";
  }
  if(this.value=='event'){
    document.getElementById("event_host").style.display="block";
    document.getElementById("event_start").style.display="block";
    document.getElementById("event_end").style.display="block";
  }else{
    document.getElementById("event_host").style.display="none";
    document.getElementById("event_start").style.display="none";
    document.getElementById("event_end").style.display="none";
  }
};

/* CKEditor */
var ckedit = <?php echo $options['post_editor']=='html'&&defined('Q')&&in_array(Q,$editor_pages)&&!is_mobile_browser()?'true':'false'; ?>;
if(ckedit){
  CKEDITOR.replace('content');
}else{
  new nicEditor({fullPanel:true}).panelInstance('content');
}

setTimeout(function(){
  $('#cke_1_contents').attr({"style":"height:400px;"});
},1000);
</script>
<?php
}else{
  echo __locale('The post doesn\'t exist');
}
