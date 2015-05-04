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
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:135px"><?php __locale('Privilege',true); ?></td><td><select class="form-select" name="privilege">
      <?php
        foreach($privileges as $privilege){if($privilege!=='master'){echo '<option value="'.$privilege.'"'.(($privilege==$user['privilege'])?' selected="true"':'').'>'.ucwords($privilege).'</option>';}}
      ?>
      </select></td></tr>
    <tr><td><?php __locale('Username',true); ?> <span style="font-size:12px;color:#955;">(permanent)</span></td><td><div class="input-parent"><input type="text" name="username" class="form-input" placeholder="<?php __locale('Username',true); ?>" value="<?php tprint($user['username']); ?>" /></div></td></tr>
    <tr><td><?php __locale('Email',true); ?></td><td><div class="input-parent"><input type="text" name="email" class="form-input" placeholder="<?php __locale('Email',true); ?>" value="<?php tprint($user['email']); ?>" /></div></td></tr>
    <tr><td><?php __locale('Name',true); ?></td><td><div class="input-parent"><input type="text" name="name" class="form-input" placeholder="<?php __locale('Full Name',true); ?>" value="<?php tprint($user['name']); ?>" /></div></td></tr>
    <tr><td><?php __locale('Password',true); ?></td><td><div class="input-parent"><input type="password" name="password" class="form-input" placeholder="<?php __locale('Password',true); ?>" /></div></td></tr>
    <input type="hidden" value="<?php tprint($user['aid']); ?>" name="id" />
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Edit User',true); ?>" class="form-submit" /></div></td></tr>
  </tbody></table>
  </form>
</div>
<?php
}else{
  echo __locale('cannot find the user');
}