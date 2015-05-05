<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Get privileges */
global $privileges;

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=new-user'); ?>" method="post" class="form-content">
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:135px"><?php __locale('Privilege',true); ?></td><td>
      <select class="form-select" name="privilege">
      <?php
        foreach($privileges as $privilege){if($privilege!=='master'){echo '<option value="'.$privilege.'" '.($privilege=='member'?'selected="selected"':'').'>'.ucwords($privilege).'</option>';}}
      ?>
      </select>
    </td></tr>
    <tr><td><?php __locale('Username',true); ?> <span style="font-size:12px;color:#955;">(permanent)</span></td><td><div class="input-parent"><input type="text" name="username" class="form-input" placeholder="<?php __locale('Username',true); ?>" /></div></td></tr>
    <tr><td><?php __locale('Email',true); ?></td><td><div class="input-parent"><input type="text" name="email" class="form-input" placeholder="<?php __locale('Email',true); ?>" /></div></td></tr>
    <tr><td><?php __locale('Name',true); ?></td><td><div class="input-parent"><input type="text" name="name" class="form-input" placeholder="<?php __locale('Full Name',true); ?>" /></div></td></tr>
    <tr><td><?php __locale('Password',true); ?></td><td><div class="input-parent"><input type="password" name="password" class="form-input" placeholder="<?php __locale('Password',true); ?>" /></div></td></tr>
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Add User',true); ?>" class="form-submit" /></div></td></tr>
  </tbody></table>
  </form>
</div>