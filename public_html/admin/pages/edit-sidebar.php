<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

// *** Allowed sidebar *** //
$bars = array(
  'text'=>'Text',
  'recent'=>'Recent Post',
  'menu'=>'Menu (Aside)',
);

/* Get $posts */
global $posts;

/* Set post types */
$post_type = array('post','page','article','training','schedule','product','event');

/* Get sidebar */
global $sidebar;
get_sidebar('aid');

if(isset($_GET['id'])&&isset($sidebar[$_GET['id']])){
  $side = $sidebar[$_GET['id']];
/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=edit-sidebar'); ?>" method="post" class="form-content">
    <div class="config-body">
      <div class="config-form">Type<select class="form-select" name="type" id="sidebar_type" onchange="content_sidebar('sidebar_type','sidebar_content')" style="width:auto;">
        <option value="">-- Type --</option>
      <?php
        foreach($bars as $type=>$tname){echo '<option value="'.$type.'"'.(($type==$side['type'])?' selected="true"':'').'>'.ucwords($tname).'</option>';}
      ?>
      </select></div>
    </div>
    <div>Title<input type="text" name="title" class="form-input" placeholder="Title" value="<?php tprint($side['title']); ?>" /></div>
    <div>Order<input type="text" name="order" class="form-input" placeholder="Order" value="<?php tprint($side['order']); ?>" /></div>
    <div id="sidebar_content">
       <?php
       if($side['type']=='text'){
         echo 'Content<textarea class="form-textarea" placeholder="Insert sidebar content here" name="content">';
         tprint($side['content']);
         echo '</textarea>';
       }elseif($side['type']=='recent'){
         $option = json_decode($side['option'],true);
         echo '<div class="config-header">Options</div><div class="config-body"><div class="config-form">Post Type<select class="form-select" name="option[post_type]" style="width:auto;">';
         foreach($post_type as $tpost){echo '<option value="'.$tpost.'"'.(($tpost==$option['post_type'])?' selected="true"':'').'>'.ucwords($tpost).'</option>';}
         echo '</select></div><div class="config-form">Max. Post<input type="text" name="option[post_max]" class="form-input" placeholder="Maximum Post" style="width:100px;" value="'.$option['post_max'].'" /></div></div>';
       }
       ?>
    </div>
    <input type="hidden" value="<?php tprint($side['aid']); ?>" name="id" />
    <div><input type="submit" value="Save" class="form-submit" /></div>
  </form>
</div>
<script type="text/javascript">
function content_sidebar(id,cid){
  var el = document.getElementById(id);
  var cel = document.getElementById(cid);
  var val = el.value;
  if(val=='text'){
    cel.innerHTML = 'Content<textarea class="form-textarea" placeholder="Insert sidebar content here" name="content"><?php tprint($side['content']); ?></textarea>';
  }else if(val=='recent'){
    <?php $option = json_decode($side['option'],true); ?>
    cel.innerHTML = '<div class="config-header">Options</div><div class="config-body"><div class="config-form">Post Type<select class="form-select" name="option[post_type]" style="width:auto;"><?php foreach($post_type as $tpost){echo '<option value="'.$tpost.'"'.(($tpost==$option['post_type'])?' selected="true"':'').'>'.ucwords($tpost).'</option>';} ?></select></div><div class="config-form">Max. Post<input type="text" name="option[post_max]" class="form-input" placeholder="Maximum Post" style="width:100px;" value="<?php tprint($option['post_max']); ?>" /></div></div>';
  }else{
    cel.innerHTML = '';
  }
}
</script>
<?php
}else{
  echo 'sidebar doesn\'t exist';
}