<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global $sidebar */
global $sidebar;
get_sidebar('order');

/* HTML View */
?>
<div class="config-body">
  <div class="config-form" style="width:auto;"><a href="<?php echo WWW; ?>admin/new-sidebar?_rdr" class="form-submit">New Sidebar</a></div>
</div>
<div class="all-posts">
  <?php
  foreach($sidebar as $id=>$bar){
    echo '<div class="all-posts-each">';
    echo '<div class="post-title">'.ucwords($bar['type']).': '.$bar['title'].'</div>';
    echo '<div class="post-option">
            <a href="'.WWW.'admin/edit-sidebar?id='.$bar['aid'].'" style="color:#3b7">Edit</a>
            <a href="'.WWW.'admin/confirmation?action=delete-sidebar&id='.$bar['aid'].'" style="color:#900">Delete</a>
          </div>';
    echo '<div class="post-detail">Type: '.$bar['type'].' | Order: '.$bar['order'].' | Option: '.$bar['option'].'</div>';
    echo '</div>';
  }
  ?>
</div>
