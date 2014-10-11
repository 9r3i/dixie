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
  echo 'cannot get menu information';
}else{
  if(isset($menus[$_GET['id']])){
    $menu = $menus[$_GET['id']];
/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=edit-menu'); ?>" method="post" class="form-content">
    <div class="config-body">
      <div class="config-form">Menu Type<select class="form-select" name="type">
      <?php
        foreach($menu_types as $type){echo '<option value="'.$type.'"'.(($type==$menu['type'])?' selected="true"':'').'>'.ucwords($type).'</option>';}
      ?>
      </select></div>
    </div>
    <div class="input-parent">Menu Name<input type="text" name="name" class="form-input" placeholder="Menu Name" list="list_title" value="<?php tprint($menu['name']); ?>" /></div>
    <div class="input-parent">Menu Slug<input type="text" name="slug" class="form-input" placeholder="Menu Slug" list="list_slug" value="<?php tprint($menu['slug']); ?>" /></div>
    <div class="input-parent">Menu Order<input type="text" name="order" class="form-input" placeholder="Menu Order" value="<?php tprint($menu['order']); ?>" /></div>
    <input type="hidden" value="<?php tprint($menu['aid']); ?>" name="id" />
    <div><input type="submit" value="Save" class="form-submit" /></div>
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