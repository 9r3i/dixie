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
  'meta'=>'Meta',
  'category'=>'Category',
  'search'=>'Search',
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
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:100px"><?php __locale('Type',true); ?></td><td><select class="form-select" name="type" id="sidebar_type" onchange="content_sidebar('sidebar_type','sidebar_content')" style="width:auto;">
      <?php
        foreach($bars as $type=>$tname){echo '<option value="'.$type.'"'.(($type==$side['type'])?' selected="true"':'').'>'.ucwords($tname).'</option>';}
      ?>
      </select></td></tr>
    <tr><td><?php __locale('Title',true); ?></td><td><div class="input-parent"><input type="text" name="title" class="form-input" placeholder="Title" value="<?php tprint($side['title']); ?>" /></div></td></tr>
    <input type="hidden" name="order" class="form-input" placeholder="Order" value="<?php tprint($side['order']); ?>" />
    <tr><td><div id="sidebar_hc"><?php echo $side['type']=='text'?__locale('Content'):($side['type']=='recent'?__locale('Options'):''); ?></div></td><td><div id="sidebar_content">
       <?php
       if($side['type']=='text'){
         echo '<div class="input-parent"><textarea class="form-textarea" placeholder="'.__locale('Insert sidebar content here').'" name="content">';
         tprint($side['content']);
         echo '</textarea></div>';
       }elseif($side['type']=='recent'){
         $option = json_decode($side['option'],true);
         echo '<div class="config-header"></div><div class="config-body"><div class="config-form">Post Type<select class="form-select" name="option[post_type]" style="width:auto;">';
         foreach($post_type as $tpost){echo '<option value="'.$tpost.'"'.(($tpost==$option['post_type'])?' selected="true"':'').'>'.ucwords($tpost).'</option>';}
         echo '</select></div><div class="config-form">'.__locale('Max. Post').'<input type="text" name="option[post_max]" class="form-input" placeholder="'.__locale('Maximum Post').'" style="width:100px;" value="'.$option['post_max'].'" /></div></div>';
       }
       ?>
    </div></td></tr>
    <input type="hidden" value="<?php tprint($side['aid']); ?>" name="id" />
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Save',true); ?>" class="form-submit" /></div></td></tr>
  </tbody></table>
  </form>
</div>
<script type="text/javascript">
function content_sidebar(id,cid){
  document.getElementById("page_content").style.height="600px";
  var el = document.getElementById(id);
  var cel = document.getElementById(cid);
  var cef = document.getElementById('sidebar_hc');
  var val = el.value;
  if(val=='text'){
    cef.innerHTML = '<?php __locale('Content',true); ?>';
    cel.innerHTML = '<div class="input-parent"><textarea class="form-textarea" placeholder="<?php __locale('Insert sidebar content here',true); ?>" name="content"><?php tprint($side['content']); ?></textarea></div>';
  }else if(val=='recent'){
    cef.innerHTML = '<?php __locale('Options',true); ?>';
    <?php $option = json_decode($side['option'],true); ?>
    cel.innerHTML = '<div class="config-header"></div><div class="config-body"><div class="config-form"><?php __locale('Post Type',true); ?><select class="form-select" name="option[post_type]" style="width:auto;"><?php foreach($post_type as $tpost){echo '<option value="'.$tpost.'"'.(($tpost==$option['post_type'])?' selected="true"':'').'>'.__locale(ucwords($tpost)).'</option>';} ?></select></div><div class="config-form"><?php __locale('Max. Post',true); ?><input type="text" name="option[post_max]" class="form-input" placeholder="<?php __locale('Maximum Post',true); ?>" style="width:100px;" value="<?php tprint($option['post_max']); ?>" /></div></div>';
  }else{
    cef.innerHTML = '';
    cel.innerHTML = '';
  }
}
</script>
<?php
}else{
  echo 'sidebar doesn\'t exist';
}