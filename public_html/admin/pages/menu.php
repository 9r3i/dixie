<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global $menus */
global $menus;
get_menus('order');

/* Configure get type */
$type = (isset($_GET['type']))?$_GET['type']:'top';
$menu_types = array('top','sidebar','bottom');

/* HTML View */
?>
<div class="config-body">
  <div class="config-form"><a href="<?php echo WWW; ?>admin/new-menu?_rdr" class="form-submit">New Menu</a></div>
</div>

<div class="config-body"><form action="" method="get">
  <div class="config-form">Menu Type<select name="type" class="form-select">
    <?php foreach($menu_types as $stat){echo '<option value="'.$stat.'"'.(($type==$stat)?' selected="true"':'').'>'.ucfirst($stat).'</option>';} ?>
  </select></div>
  <div class="config-form"><input type="submit" value="Filter" class="form-submit" /></div>
</form></div>
<div class="all-posts">
  <?php
  foreach($menus as $id=>$menu){
    if($menu['type']==$type){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.$menu['name'].'</div>';
      echo '<div class="post-option">
            <a href="'.WWW.$menu['slug'].'" target="_blank" style="color:#37b">View</a>
            <a href="'.WWW.'admin/edit-menu?id='.$menu['aid'].'" style="color:#3b7">Edit</a>
            <a href="'.WWW.'admin/confirmation?action=delete-menu&id='.$menu['aid'].'" style="color:#900">Delete</a>
          </div>';
      echo '<div class="post-detail">Type: '.$menu['type'].' | Order: '.$menu['order'].'</div>';
      echo '</div>';
    }
  }
  ?>
</div>