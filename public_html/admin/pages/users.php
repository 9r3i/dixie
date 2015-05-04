<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* HTML View */
$users = get_user_data(true);

/* HTML View */
?>
<div class="config-body">
  <a href="<?php echo WWW; ?>admin/new-user?_rdr"><div class="button fs15"><div class="fas fa fa-plus"></div><?php __locale('Add User',true); ?></div></a>
</div>
<div class="all-posts">
  <?php
  foreach($users as $id=>$user){
    if(master_privilege($user['privilege'])){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.$user['name'].' ('.$user['privilege'].')</div>';
      echo '<div class="post-option">
            <a href="'.WWW.'admin/edit-user?id='.$user['aid'].'" style="color:#3b7"><div class="fas fa fa-edit"></div>'.__locale('Edit').'</a>
            <a href="'.WWW.'admin/confirmation?action=delete-user&id='.$user['aid'].'" style="color:#900"><div class="fas fa fa-trash-o"></div>'.__locale('Delete').'</a>
          </div>';
      echo '<div class="post-detail">'.__locale('Username').': '.$user['username'].' | '.__locale('Email').': '.$user['email'].' | '.__locale('Joined Time').': '.date('F, jS Y H:i',$user['time']).'</div>';
      echo '</div>';
    }
  }
  ?>
</div>