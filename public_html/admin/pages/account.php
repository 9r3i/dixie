<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Get user data */
$userdata = get_user_data(get_active_user());

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=account'); ?>" method="post" class="form-content">
    <div class="form-account-info">
      <div class="form-account-info-each">ID: <?php printf($userdata['id']); ?></div>
      <div class="form-account-info-each"><?php __locale('Privilege',true); ?>: <?php printf(ucwords($userdata['privilege'])); ?></div>
      <div class="form-account-info-each"><?php __locale('Joined',true); ?>: <?php printf(date('F, jS Y',$userdata['time'])); ?></div>
    </div>

  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:150px;"><?php __locale('Username',true); ?> <span style="font-size:12px;color:#955;">(permanent)</span></td><td><div class="input-parent"><input type="text" name="username" readonly="true" class="form-input" value="<?php printf($userdata['username']); ?>" /></div></td></tr>
    <tr><td><?php __locale('Email',true); ?></td><td><div class="input-parent"><input type="text" name="email" class="form-input" value="<?php printf($userdata['email']); ?>" /></div></td></tr>
    <tr><td><?php __locale('Name',true); ?></td><td><div class="input-parent"><input type="text" name="name" class="form-input" value="<?php printf($userdata['name']); ?>" /></div></td></tr>
    <tr><td><?php __locale('Password',true); ?> <span style="font-size:12px;color:#955;">(security confirmation)</span></td><td><div class="input-parent"><input type="password" name="password" class="form-input" placeholder="<?php __locale('Password',true); ?>" /></div></td></tr>
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Save',true); ?>" class="form-submit" /></div></td></tr>
  </tbody></table>
  </form>
  <form action="<?php printf(WWW.'admin/a?data=change-password'); ?>" method="post" class="form-content">
    <div class="form-account-info"><div class="form-account-info-each"><?php __locale('Change Password',true); ?></div></div>
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:150px;"><?php __locale('Old Password',true); ?> <span style="font-size:12px;color:#955;">(security confirmation)</span></td><td><div class="input-parent"><input type="password" name="old-password" class="form-input" placeholder="<?php __locale('Old Password',true); ?>" /></div></td></tr>
    <tr><td><?php __locale('New Password',true); ?></td><td><div class="input-parent"><input type="password" name="new-password" class="form-input" placeholder="<?php __locale('New Password',true); ?>" /></div></td></tr>
    <tr><td><?php __locale('Confirm Password',true); ?></td><td><div class="input-parent"><input type="password" name="confirm-password" class="form-input" placeholder="<?php __locale('Confirm Password',true); ?>" /></div></td></tr>
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Change Password',true); ?>" class="form-submit" /></div></td></tr>

  </tbody></table>
  </form>
</div>