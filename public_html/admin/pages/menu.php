<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global $menus */
global $menus;
get_menus('order');

/* Configure get type */
$type = (isset($_GET['type']))?$_GET['type']:'top';
$menu_types = array('top','sidebar','bottom');

/* HTML View */
?>
<div class="config-body">
  <form action="" method="get" style="padding:0px;margin:0px;">
    <div class="config-form" style="width:100%;padding:0px;margin:0px 5px;">
      <a href="<?php echo WWW; ?>admin/new-menu?_rdr"><div class="button fs15"><div class="fas fa fa-plus"></div><?php __locale('New Menu',true); ?></div></a>
      <select name="type" class="form-select">
        <?php foreach($menu_types as $stat){echo '<option value="'.$stat.'"'.(($type==$stat)?' selected="true"':'').'>'.__locale(ucfirst($stat)).'</option>';} ?>
      </select>
      <input type="submit" value="<?php __locale('Filter',true); ?>" class="form-submit fs15" />
      <a href="javascript:save_menu()" title="<?php __locale('Save',true); ?>"><div id="save-change" class="button fs15"><div class="fas fa fa-save"></div><?php __locale('Save',true); ?></div></a>
    </div>
  </form>
</div>
<div class="all-posts" id="menus">
  <?php
  foreach($menus as $id=>$menu){
    if($menu['type']==$type){
      echo '<div class="all-posts-each" menuid="'.$menu['aid'].'">';
      echo '<div class="post-title">'.$menu['name'].'</div>';
      echo '<div class="post-option">
            <a href="'.WWW.$menu['slug'].'" target="_blank" style="color:#37b"><div class="fas fa fa-search"></div>'.__locale('View').'</a>
            <a href="'.WWW.'admin/edit-menu?id='.$menu['aid'].'" style="color:#3b7"><div class="fas fa fa-edit"></div>'.__locale('Edit').'</a>
            <a href="'.WWW.'admin/confirmation?action=delete-menu&id='.$menu['aid'].'" style="color:#900"><div class="fas fa fa-trash-o"></div>'.__locale('Delete').'</a>
          </div>';
      //echo '<div class="post-detail">'.__locale('Type').': '.$menu['type'].' | '.__locale('Order').': '.$menu['order'].'</div>';
      echo '</div>';
    }
  }
  ?>
</div>
<script type="text/javascript">
function save_menu(){
  var cmenu = document.getElementById('menus').children;
  var result = {},r = 10;
  for(i=0;i<cmenu.length;i++){
    r++;
    var mid = cmenu[i].getAttribute('menuid');
    result[mid] = r;
  }
  $.post(www+'admin/a?data=reorder-menu',result,function(res){
    alert('Saved');
  });
}

$(function(){
  $("#menus").sortable();
  $("#menus").disableSelection();
});
</script>