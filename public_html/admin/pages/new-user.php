<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Get privileges */
global $privileges;

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=new-user'); ?>" method="post" class="form-content">
    <div class="config-body">
      <div class="config-form">Privilege<select class="form-select" name="privilege">
      <?php
        foreach($privileges as $privilege){if($privilege!=='master'){echo '<option value="'.$privilege.'">'.ucwords($privilege).'</option>';}}
      ?>
      </select></div>
    </div>
    <div>Username <span style="font-size:12px;color:#955;">(permanent)</span><input type="text" name="username" class="form-input" placeholder="Username" /></div>
    <div>Email<input type="text" name="email" class="form-input" placeholder="Email" /></div>
    <div>Name<input type="text" name="name" class="form-input" placeholder="Full Name" /></div>
    <div>Password<input type="password" name="password" class="form-input" placeholder="Password" /></div>
    <div><input type="submit" value="Add User" class="form-submit" /></div>
  </form>
</div>