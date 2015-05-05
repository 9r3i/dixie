<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Set menu types */
$menu_types = array('top','sidebar','bottom');

/* Get $posts */
global $posts;

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=new-menu'); ?>" method="post" class="form-content">
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:100px"><?php __locale('Menu Type',true); ?></td><td>
        <select class="form-select" name="type">
          <?php foreach($menu_types as $type){echo '<option value="'.$type.'">'.__locale(ucwords($type)).'</option>';} ?>
        </select></td></tr>
    <tr><td><?php __locale('Menu Name',true); ?></td><td><div class="input-parent"><input type="text" name="name" class="form-input" placeholder="<?php __locale('Menu Name',true); ?>" list="list_title" /></div></td></tr>
    <tr><td><?php __locale('Menu Slug',true); ?></td><td><div class="input-parent"><input type="text" name="slug" class="form-input" placeholder="<?php __locale('Menu Slug',true); ?>" list="list_slug" /></div></td></tr>
    <input type="hidden" name="order" class="form-input" placeholder="<?php __locale('Menu Order',true); ?>" value="9" />
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Add Menu',true); ?>" class="form-submit" /></div></td></tr>
  </tbody></table>
    <datalist id="list_slug">
      <?php foreach($posts as $url=>$post){echo '<option value="'.$url.'.html"></option>';} ?>
    </datalist>
    <datalist id="list_title">
      <?php foreach($posts as $url=>$post){echo '<option value="'.$post['title'].'"></option>';} ?>
    </datalist>
  </form>
</div>