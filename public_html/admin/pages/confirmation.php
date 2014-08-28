<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global posts */
global $posts;

/* Re-arrange post_id */
$post_id = array();
foreach($posts as $post){
  $post_id[] = $post['aid'];
}

/* Configure actions */
$action = (isset($_GET['action']))?$_GET['action']:'';

/* Prevent if no action chosen */
if(!isset($_GET['action'])||empty($_GET['action'])){
  echo 'no action chosen';
}

/* Move to Trash */
if($action=='trash'){
  if(isset($_GET['post_id'])&&in_array($_GET['post_id'],$post_id)){
    echo '<div>Are you sure want to move this post to Trash?</div>';
    echo '<form action="'.WWW.'admin/a?data=trash" method="post">
      <input type="hidden" value="'.$_GET['post_id'].'" name="post_id" />
      <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
      <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
    </form>';
  }else{
    echo 'cannot find the post';
  }
}
/* Delete permanently */
elseif($action=='delete-post'){
  if(isset($_GET['post_id'])&&in_array($_GET['post_id'],$post_id)){
    echo '<div>Are you sure want to delete this post permanently?</div>';
    echo '<form action="'.WWW.'admin/a?data=delete-post" method="post">
      <input type="hidden" value="'.$_GET['post_id'].'" name="post_id" />
      <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
      <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
    </form>';
  }else{
    echo 'cannot find the post';
  }
}
/* Delete file */
elseif($action=='delete-file'){
  if(isset($_GET['file'])&&file_exists($_GET['file'])){
    echo '<div>Filename: '.$_GET['file'].'</div>';
    echo '<div>Are you sure want to delete this file permanently?</div>';
    echo '<form action="'.WWW.'admin/a?data=delete-file" method="post">
      <input type="hidden" value="'.$_GET['file'].'" name="file" />
      <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
      <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
    </form>';
  }else{
    echo 'cannot find the file';
  }
}
/* Rename file */
elseif($action=='rename-file'){
  if(isset($_GET['file'])&&file_exists($_GET['file'])){
    $explode = explode('/',$_GET['file']);
    $file = $explode[1];
    $dirname = $explode[0];
    echo 'Rename file: '. $file;
    echo '<form action="'.WWW.'admin/a?data=rename-file" method="post">
      <div><input type="text" value="'.$file.'" name="file" class="form-input" /></div>
      <input type="hidden" value="'.$dirname.'" name="dirname" />
      <input type="hidden" value="'.$file.'" name="oldname" />
      <div class="confirm-trash"><input type="submit" value="Confirm" class="form-submit" /></div>
      <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
    </form>';
  }else{
    echo 'cannot find the file';
  }
}
/* Delete user */
elseif($action=='delete-user'){
  global $ldb;
  $select = $ldb->select('users','aid='.$_GET['id']);
  if(isset($_GET['id'])&&isset($select[0])){
    echo '<div>Username: '.$select[0]['username'].' ('.$select[0]['privilege'].')</div>';
    echo '<div>Are you sure want to delete this user permanently?</div>';
    echo '<form action="'.WWW.'admin/a?data=delete-user" method="post">
      <input type="hidden" value="'.$_GET['id'].'" name="id" />
      <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
      <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
    </form>';
  }else{
    echo 'cannot find the user';
  }
}
/* Delete menu */
elseif($action=='delete-menu'){
  global $ldb;
  $select = $ldb->select('menu','aid='.$_GET['id']);
  if(isset($_GET['id'])&&isset($select[0])){
    echo '<div>Menu Name: '.$select[0]['name'].' ('.$select[0]['slug'].')</div>';
    echo '<div>Are you sure want to delete this menu permanently?</div>';
    echo '<form action="'.WWW.'admin/a?data=delete-menu" method="post">
      <input type="hidden" value="'.$_GET['id'].'" name="id" />
      <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
      <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
    </form>';
  }else{
    echo 'cannot find content menu';
  }
}
/* Delete sidebar */
elseif($action=='delete-sidebar'){
  global $ldb;
  $select = $ldb->select('sidebar','aid='.$_GET['id']);
  if(isset($_GET['id'])&&isset($select[0])){
    echo '<div>Sidebar Type: '.$select[0]['type'].((!empty($select[0]['title']))?'('.$select[0]['title'].')':'').'</div>';
    echo '<div>Are you sure want to delete this sidebar permanently?</div>';
    echo '<form action="'.WWW.'admin/a?data=delete-sidebar" method="post">
      <input type="hidden" value="'.$_GET['id'].'" name="id" />
      <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
      <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
    </form>';
  }else{
    echo 'cannot find content sidebar';
  }
}
/* Activate plugin */
elseif($action=='activate-plugin'){
  $activate = set_plugin_status($_GET['name'],true);
  if($activate){
    echo 'plugin has been activated.<br />';
    echo '<a href="'.WWW.'admin/plugins?_ref=confirm">Back to Plugins</a>';
  }else{
    echo 'cannot be activated.';
  }
}
/* Deactivate plugin */
elseif($action=='deactivate-plugin'){
  $activate = set_plugin_status($_GET['name'],false);
  if($activate){
    echo 'plugin has been deactivated.<br />';
    echo '<a href="'.WWW.'admin/plugins?_ref=confirm">Back to Plugins</a>';
  }else{
    echo 'cannot be deactivated.';
  }
}
/* Delete plugin */
elseif($action=='delete-plugin'){
  $plug = new Plugins();
  $plugins = $plug->plugins;
  $active = $plug->active;
  $name = (isset($_GET['name']))?$_GET['name']:'';
  if(isset($plugins[$name])){
    if(plugin_active($name)){
      echo 'the plugin cannot be deleted while it\'s active';
    }else{
      echo '<div>Plugin Name: '.$plugins[$name]['name'].'</div>';
      echo '<div>Are you sure want to delete this plugin permanently?</div>';
      echo '<form action="'.WWW.'admin/a?data=delete-plugin" method="post">
        <input type="hidden" value="'.$plugins[$name]['dir'].'" name="name" />
        <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
        <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
      </form>';
    }
  }else{
    echo 'cannot find content plugin';
  }
}
/* Delete theme */
elseif($action=='delete-theme'){
  $the = new Themes();
  $themes = $the->themes;
  $name = (isset($_GET['name']))?$_GET['name']:'';
  if(isset($themes[$name])){
    if(get_site_info('theme',false)==$name){
      echo 'the theme cannot be deleted while it\'s active';
    }else{
      echo '<div>Theme Name: '.$themes[$name]['name'].'</div>';
      echo '<div>Are you sure want to delete this theme permanently?</div>';
      echo '<form action="'.WWW.'admin/a?data=delete-theme" method="post">
        <input type="hidden" value="'.$themes[$name]['name'].'" name="name" />
        <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
        <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
      </form>';
    }
  }else{
    echo 'cannot find content theme';
  }
}
/* Activate theme */
elseif($action=='activate-theme'){
  $the = new Themes();
  $themes = $the->themes;
  $name = (isset($_GET['name']))?$_GET['name']:'';
  if(isset($themes[$name])){
    if(get_site_info('theme',false)==$name){
      echo 'the theme has been actived';
    }else{
      echo '<div>Theme Name: '.$themes[$name]['name'].'</div>';
      echo '<div>Are you sure want to change to this theme as your theme?</div>';
      echo '<form action="'.WWW.'admin/a?data=activate-theme" method="post">
        <input type="hidden" value="'.$themes[$name]['name'].'" name="name" />
        <div class="confirm-trash"><input type="submit" value="Yes" class="form-submit" /></div>
        <div class="confirm-cancel"><a href="#" onclick="history.go(-1)" class="cancel">Cancel</a></div>
      </form>';
    }
  }else{
    echo 'cannot find content theme';
  }
}
/* Else action */
else{
  echo 'Unrecognized action';
}











