<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Set menu types */
$menu_types = array('top','sidebar','bottom');

/* Get $posts and $menus */
global $posts,$menus;

/* Handler if return false */
if(!get_menus('aid')){
  echo __locale('cannot get menu information');
}else{
  if(isset($menus[$_GET['id']])){
    $menu = $menus[$_GET['id']];
/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=edit-menu'); ?>" method="post" class="form-content">
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <tr><td style="width:100px"><?php __locale('Menu Type',true); ?></td><td><select class="form-select" name="type">
      <?php
        foreach($menu_types as $type){echo '<option value="'.$type.'"'.(($type==$menu['type'])?' selected="true"':'').'>'.__locale(ucwords($type)).'</option>';}
      ?>
      </select></td></tr>
    <tr><td><?php __locale('Menu Name',true); ?></td><td><div class="input-parent"><input type="text" name="name" class="form-input" placeholder="<?php __locale('Menu Name',true); ?>" list="list_title" value="<?php tprint($menu['name']); ?>" /></div></td></tr>
    <tr><td><?php __locale('Menu Slug',true); ?></td><td><div class="input-parent"><input type="text" name="slug" class="form-input" placeholder="<?php __locale('Menu Slug',true); ?>" list="list_slug" value="<?php tprint($menu['slug']); ?>" /></div></td></tr>
    <input type="hidden" name="order" class="form-input" placeholder="<?php __locale('Menu Order',true); ?>" value="<?php tprint($menu['order']); ?>" />
    <input type="hidden" value="<?php tprint($menu['aid']); ?>" name="id" />
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Save',true); ?>" class="form-submit" /></div></td></tr>
  </tbody></table>
    <datalist id="list_slug">
      <?php foreach($posts as $url=>$post){echo '<option value="'.$url.'.html"></option>';} ?>
    </datalist>
    <datalist id="list_title">
      <?php foreach($posts as $url=>$post){echo '<option value="'.$post['title'].'"></option>';} ?>
    </datalist>
  </form>
</div>
<?php
 }else{
  echo 'menu doesn\'t exist';
 }
}