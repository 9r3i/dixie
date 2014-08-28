<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global $ldb & $privileges */
global $ldb,$privileges;

/* Get user data */
$id = (isset($_GET['id']))?$_GET['id']:'';
$select = $ldb->select('users','aid='.$id);

if(isset($select[0])&&$select[0]['privilege']!=='master'){
  $user = $select[0];
/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=edit-user'); ?>" method="post" class="form-content">
    <div class="config-body">
      <div class="config-form">Privilege<select class="form-select" name="privilege">
      <?php
        foreach($privileges as $privilege){if($privilege!=='master'){echo '<option value="'.$privilege.'"'.(($privilege==$user['privilege'])?' selected="true"':'').'>'.ucwords($privilege).'</option>';}}
      ?>
      </select></div>
    </div>
    <div>Username <span style="font-size:12px;color:#955;">(permanent)</span><input type="text" name="username" class="form-input" placeholder="Username" value="<?php tprint($user['username']); ?>" /></div>
    <div>Email<input type="text" name="email" class="form-input" placeholder="Email" value="<?php tprint($user['email']); ?>" /></div>
    <div>Name<input type="text" name="name" class="form-input" placeholder="Full Name" value="<?php tprint($user['name']); ?>" /></div>
    <div>Password<input type="password" name="password" class="form-input" placeholder="Password" /></div>
    <input type="hidden" value="<?php tprint($user['aid']); ?>" name="id" />
    <div><input type="submit" value="Edit User" class="form-submit" /></div>
  </form>
</div>
<?php
}else{
  echo 'cannot find the user';
}