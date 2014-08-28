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
  <div class="config-form"><a href="<?php echo WWW; ?>admin/new-user?_rdr" class="form-submit">Add User</a></div>
</div>
<div class="all-posts">
  <?php
  foreach($users as $id=>$user){
    if(master_privilege($user['privilege'])){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.$user['name'].' ('.$user['privilege'].')</div>';
      echo '<div class="post-option">
            <a href="'.WWW.'admin/edit-user?id='.$user['aid'].'" style="color:#3b7">Edit</a>
            <a href="'.WWW.'admin/confirmation?action=delete-user&id='.$user['aid'].'" style="color:#900">Delete</a>
          </div>';
      echo '<div class="post-detail">Username: '.$user['username'].' | Email: '.$user['email'].' | Joined Time: '.date('F, jS Y H:i',$user['time']).'</div>';
      echo '</div>';
    }
  }
  ?>
</div>