<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
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

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=new-sidebar'); ?>" method="post" class="form-content">
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:100px"><?php __locale('Type',true); ?></td><td><select class="form-select" name="type" id="sidebar_type" onchange="content_sidebar('sidebar_type','sidebar_content')" style="width:auto;">
      <?php
        foreach($bars as $type=>$tname){echo '<option value="'.$type.'"'.($type=='search'?' selected="selected"':'').'>'.ucwords($tname).'</option>';}
      ?>
      </select></div>
    </div></td></tr>
    <tr><td><?php __locale('Title',true); ?></td><td><div class="input-parent"><input type="text" name="title" class="form-input" placeholder="<?php __locale('Title',true); ?>" /></div></td></tr>
    <input type="hidden" name="order" class="form-input" placeholder="<?php __locale('Order',true); ?>" value="9" />
    <tr><td><div id="sidebar_hc"></div></td><td><div id="sidebar_content"></div></td></tr>
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Add Sidebar',true); ?>" class="form-submit" /></div></td></tr>
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
    cel.innerHTML = '<div class="input-parent"><textarea class="form-textarea" placeholder="<?php __locale('Insert sidebar content here',true); ?>" name="content"></textarea></div>';
  }else if(val=='recent'){
    cef.innerHTML = '<?php __locale('Options',true); ?>';
    cel.innerHTML = '<div class="config-header"></div><div class="config-body"><div class="config-form"><?php __locale('Post Type',true); ?><select class="form-select" name="option[post_type]" style="width:auto;"><?php foreach($post_type as $tpost){echo '<option value="'.$tpost.'">'.__locale(ucwords($tpost)).'</option>';} ?></select></div><div class="config-form"><?php __locale('Max. Post',true); ?><input type="text" name="option[post_max]" class="form-input" placeholder="<?php __locale('Maximum Post',true); ?>" style="width:100px;" /></div></div>';
  }else{
    cef.innerHTML = '';
    cel.innerHTML = '';
  }
}
</script>