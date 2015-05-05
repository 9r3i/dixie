<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Global $sidebar */
global $sidebar;
get_sidebar('order');

/* HTML View */
?>
<div class="config-body">
  <a href="<?php echo WWW; ?>admin/new-sidebar?_rdr"><div class="button fs15"><div class="fas fa fa-plus"></div><?php __locale('New Sidebar',true); ?></div></a>
  <a href="javascript:save_sidebar()" title="<?php __locale('Save',true); ?>"><div id="save-change" class="button fs15"><div class="fas fa fa-save"></div><?php __locale('Save',true); ?></div></a>
</div>
<div class="all-posts" id="sidebars">
  <?php
  foreach($sidebar as $id=>$bar){
    echo '<div class="all-posts-each" barid="'.$bar['aid'].'">';
    echo '<div class="post-title">'.ucwords($bar['type']).': '.$bar['title'].'</div>';
    echo '<div class="post-option">
            <a href="'.WWW.'admin/edit-sidebar?id='.$bar['aid'].'" style="color:#3b7"><div class="fas fa fa-edit"></div>'.__locale('Edit').'</a>
            <a href="'.WWW.'admin/confirmation?action=delete-sidebar&id='.$bar['aid'].'" style="color:#900"><div class="fas fa fa-trash-o"></div>'.__locale('Delete').'</a>
            <a href="javascript:void(0)" style="color:#993">'.__locale('Type').': '.$bar['type'].'</a>
          </div>';
    //echo '<div class="post-detail">'.__locale('Type').': '.$bar['type'].' | '.__locale('Order').': '.$bar['order'].' | '.__locale('Option').': '.$bar['option'].'</div>';
    echo '</div>';
  }
  ?>
</div>
<script type="text/javascript">
function save_sidebar(){
  var cbar = document.getElementById('sidebars').children;
  var result = {},r = 10;
  for(i=0;i<cbar.length;i++){
    r++;
    var mid = cbar[i].getAttribute('barid');
    result[mid] = r;
  }
  $.post(www+'admin/a?data=reorder-sidebar',result,function(res){
    alert('Saved');
  });
}

$(function(){
  $("#sidebars").sortable();
  $("#sidebars").disableSelection();
});
</script>
