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
      <div class="form-account-info-each">Privilege: <?php printf(ucwords($userdata['privilege'])); ?></div>
      <div class="form-account-info-each">Joined: <?php printf(date('F, jS Y',$userdata['time'])); ?></div>
    </div>
    <div>Username <span style="font-size:12px;color:#955;">(permanent)</span><input type="text" name="username" readonly="true" class="form-input" value="<?php printf($userdata['username']); ?>" /></div>
    <div>Email<input type="text" name="email" class="form-input" value="<?php printf($userdata['email']); ?>" /></div>
    <div>Name<input type="text" name="name" class="form-input" value="<?php printf($userdata['name']); ?>" /></div>
    <div>Password <span style="font-size:12px;color:#955;">(security confirmation)</span><input type="password" name="password" class="form-input" placeholder="Password" /></div>
    <div><input type="submit" value="Save" class="form-submit" /></div>
  </form>
  <form action="<?php printf(WWW.'admin/a?data=change-password'); ?>" method="post" class="form-content">
    <div class="form-account-info"><div class="form-account-info-each">Change Password</div></div>
    <div>Old Password <span style="font-size:12px;color:#955;">(security confirmation)</span><input type="password" name="old-password" class="form-input" placeholder="Old Password" /></div>
    <div>New Password<input type="password" name="new-password" class="form-input" placeholder="New Password" /></div>
    <div>Confirm Password<input type="password" name="confirm-password" class="form-input" placeholder="Confirm Password" /></div>
    <div><input type="submit" value="Change Password" class="form-submit" /></div>
  </form>
</div>