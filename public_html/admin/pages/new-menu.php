<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
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
    <div class="config-body">
      <div class="config-form" style="width:100%;padding:0px;margin:0px 5px;">
        <select class="form-select" name="type">
          <?php foreach($menu_types as $type){echo '<option value="'.$type.'">'.ucwords($type).'</option>';} ?>
        </select>
      </div>
    </div>
    <div class="input-parent">Menu Name<input type="text" name="name" class="form-input" placeholder="Menu Name" list="list_title" /></div>
    <div class="input-parent">Menu Slug<input type="text" name="slug" class="form-input" placeholder="Menu Slug" list="list_slug" /></div>
    <div class="input-parent">Menu Order<input type="text" name="order" class="form-input" placeholder="Menu Order" /></div>
    <div><input type="submit" value="Add Menu" class="form-submit" /></div>
    <datalist id="list_slug">
      <?php foreach($posts as $url=>$post){echo '<option value="'.$url.'.html"></option>';} ?>
    </datalist>
    <datalist id="list_title">
      <?php foreach($posts as $url=>$post){echo '<option value="'.$post['title'].'"></option>';} ?>
    </datalist>
  </form>
</div>