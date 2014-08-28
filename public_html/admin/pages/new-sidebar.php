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
  'menu'=>'Menu (Sidebar)',
);

/* Get $posts */
global $posts;

/* Set post types */
$post_type = array('post','page','article','training','schedule','product','event');

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=new-sidebar'); ?>" method="post" class="form-content">
    <div class="config-body">
      <div class="config-form">Type<select class="form-select" name="type" id="sidebar_type" onchange="content_sidebar('sidebar_type','sidebar_content')" style="width:auto;">
        <option value="">-- Type --</option>
      <?php
        foreach($bars as $type=>$tname){echo '<option value="'.$type.'">'.ucwords($tname).'</option>';}
      ?>
      </select></div>
    </div>
    <div>Title<input type="text" name="title" class="form-input" placeholder="Title" /></div>
    <div>Order<input type="text" name="order" class="form-input" placeholder="Order" /></div>
    <div id="sidebar_content"></div>
    <div><input type="submit" value="Add Sidebar" class="form-submit" /></div>
  </form>
</div>
<script type="text/javascript">
function content_sidebar(id,cid){
  var el = document.getElementById(id);
  var cel = document.getElementById(cid);
  var val = el.value;
  if(val=='text'){
    cel.innerHTML = 'Content<textarea class="form-textarea" placeholder="Insert sidebar content here" name="content"></textarea>';
  }else if(val=='recent'){
    cel.innerHTML = '<div class="config-header">Options</div><div class="config-body"><div class="config-form">Post Type<select class="form-select" name="option[post_type]" style="width:auto;"><?php foreach($post_type as $tpost){echo '<option value="'.$tpost.'">'.ucwords($tpost).'</option>';} ?></select></div><div class="config-form">Max. Post<input type="text" name="option[post_max]" class="form-input" placeholder="Maximum Post" style="width:100px;" /></div></div>';
  }else{
    cel.innerHTML = '';
  }
}
</script>