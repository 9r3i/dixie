<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

if(get_active_user()=='demo'){
  header('content-type: text/plain;');
  exit('demo version cannot do this action');
}

/* Configuration global and Ldb */
global $ldb,$posts,$options;
if(!ldb()){
  header('content-type: text/plain;');
  exit('cannot connect into database');
}

/* Configuration variable data */
$data = (isset($_GET['data']))?$_GET['data']:'';

/* Settings */
if($data=='settings'){
  $select = $ldb->select('options');
  foreach($select as $sel){
    if(isset($_POST[$sel['key']])){
      $update = $ldb->update('options','key='.$sel['key'],array('value'=>$_POST[$sel['key']]));
      if(!$update){
        header('content-type: text/plain;');
        exit('cannot update database');
      }
    }
  }
  header('location: '.WWW.'admin/settings?status=update-settings');
  exit;
}
/* Account */
elseif($data=='account'){
  if($ldb->valid_password('users','username='.get_active_user(),$_POST['password'])){
    unset($_POST['password']);
    unset($_POST['username']);
    $update = $ldb->update('users','username='.get_active_user(),$_POST);
    if($update){
      header('location: '.WWW.'admin/account?status=update-account');
      exit;
    }else{
      header('content-type: text/plain;');
      exit('cannot update database');
    }
  }else{
    header('content-type: text/plain;');
    exit('invalid password');
  }
}
/* Change passwrod */
elseif($data=='change-password'){
  if($ldb->valid_password('users','username='.get_active_user(),$_POST['old-password'])&&!empty($_POST['new-password'])){
    if($_POST['new-password']===$_POST['confirm-password']){
      $update = $ldb->update('users','username='.get_active_user(),array('password'=>$_POST['new-password']));
      if($update){
        header('location: '.WWW.'admin/account?status=update-password');
        exit;
      }else{
        header('content-type: text/plain;');
        exit('cannot update database');
      }
    }else{
      header('content-type: text/plain;');
      exit('new password doesn\'t match');
    }
  }else{
    header('content-type: text/plain;');
    exit('invalid password');
  }
}
/* New post */
elseif($data=='new-post'){
  $slug = create_slug($_POST['title']);
  $datetime = create_datetime();
  $select = $ldb->select('posts','url='.$slug);
  if(!empty($_POST['title'])&&!empty($_POST['content'])&&!isset($select[0])){
    $data = $_POST;
    $data['url'] = $slug;
    $data['datetime'] = $datetime;
    $insert = $ldb->insert('posts',$data);
    $select = $ldb->select('posts','url='.$slug);
    if($insert&&isset($select[0]['aid'])){
      header('location: '.WWW.'admin/edit-post?post_id='.$select[0]['aid'].'&status=success-publish');
      exit;
    }else{
      header('content-type: text/plain;');
      exit('cannot write into database');
    }
  }else{
    header('content-type: text/plain;');
    exit('title or content might be empty, please re-check the form, '.PHP_EOL.'and makes sure if the title is not duplicated to create the slug');
  }
}
/* Edit post */
elseif($data=='edit-post'){
  $datetime = create_datetime();
  $select = $ldb->select('posts','aid='.$_POST['post_id']);
  if(isset($select[0])){
    $data = $_POST;
    $data['datetime'] = $datetime;
    $update = $ldb->update('posts','aid='.$_POST['post_id'],$data);
    if($update){
      header('location: '.WWW.'admin/edit-post?post_id='.$_POST['post_id'].'&status=success-update');
      exit;
    }else{
      header('content-type: text/plain;');
      exit('cannot update database');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot find the post');
  }
}
/* Delete post */
elseif($data=='delete-post'){
  $select = $ldb->select('posts','aid='.$_POST['post_id']);
  if(isset($select[0])){
    $delete = $ldb->delete('posts','aid='.$_POST['post_id']);
    header('location: '.WWW.'admin/posts?filter-status=trash&status=success-delete');
    exit;
  }else{
    header('content-type: text/plain;');
    exit('cannot find the post');
  }
}
/* Trash */
elseif($data=='trash'){
  $datetime = create_datetime();
  $select = $ldb->select('posts','aid='.$_POST['post_id']);
  if(isset($select[0])){
    $data = array('status'=>'trash');
    $data['datetime'] = $datetime;
    $update = $ldb->update('posts','aid='.$_POST['post_id'],$data);
    if($update){
      header('location: '.WWW.'admin/posts?status=success-update');
      exit;
    }else{
      header('content-type: text/plain;');
      exit('cannot update database');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot find the post');
  }
}
/* Delete file */
elseif($data=='delete-file'){
  if(isset($_POST['file'])&&file_exists($_POST['file'])){
    @unlink($_POST['file']);
    header('location: '.WWW.'admin/files?status=success-delete');
    exit;
  }else{
    header('content-type: text/plain;');
    exit('cannot find the file');
  }
}
/* Rename file */
elseif($data=='rename-file'){
  if(isset($_POST['oldname'])&&isset($_POST['file'])&&isset($_POST['dirname'])&&file_exists($_POST['dirname'].'/'.$_POST['oldname'])){
    @rename($_POST['dirname'].'/'.$_POST['oldname'],$_POST['dirname'].'/'.$_POST['file']);
    header('location: '.WWW.'admin/files?status=success-rename');
    exit;
  }else{
    header('content-type: text/plain;');
    exit('cannot find the file');
  }
}
/* Upload file */
elseif($data=='upload-file'){
  if(isset($_FILES['file'])){
    $files = rearrange_files($_FILES['file']);
    $r=0;
    foreach($files as $file){
      $r++;
      if($file['error']==0){
        @move_uploaded_file($file['tmp_name'],'upload/'.$file['name']);
      }
    }
    if($r==count($files)){
      header('location: '.WWW.'admin/files?status=success-upload');
      exit;
    }else{
      header('content-type: text/plain;');
      exit('cannot upload the file');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot upload the file');
  }
}
/* Edit theme */
elseif($data=='edit-theme'){
  if(isset($_POST['file-name'])&&isset($_POST['theme-name'])&&isset($_POST['content'])&&file_exists('themes/'.$_POST['theme-name'].'/'.$_POST['file-name'])){
    $location = 'themes/'.$_POST['theme-name'].'/'.$_POST['file-name'];
    $write = file_write($location,$_POST['content']);
    if($write){
      header('location: '.WWW.'admin/edit-theme?name='.$_POST['theme-name'].'&file='.$_POST['file-name'].'&status=success-upload');
      exit;
    }else{
      header('content-type: text/plain;');
      exit('cannot write on the file');
    }
  }else{
    header('content-type: text/plain;');
    exit('theme doesn\'t exist');
  }
}
/* New user */
elseif($data=='new-user'){
  if($_SESSION['dixie_privilege']=='master'||$_SESSION['dixie_privilege']=='admin'){
    if(isset($_POST['username'])&&isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['password'])&&isset($_POST['privilege'])){
      $select = $ldb->select('users','username='.$_POST['username']);
      if(!isset($select[0])){
        if(!empty($_POST['username'])&&!empty($_POST['name'])&&!empty($_POST['email'])&&!empty($_POST['password'])&&!empty($_POST['privilege'])){
          $data = $_POST;
          $insert = $ldb->insert('users',$data);
          if($insert){
            header('location: '.WWW.'admin/users?status=success-add-user');
            exit;
          }else{
            header('content-type: text/plain;');
            exit('cannot write into database');
          }
        }else{
          header('content-type: text/plain;');
          exit('some input might be empty');
        }
      }else{
        header('content-type: text/plain;');
        exit('username has been taken');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Edit user */
elseif($data=='edit-user'){
  if($_SESSION['dixie_privilege']=='master'||$_SESSION['dixie_privilege']=='admin'){
    if(isset($_POST['username'])&&isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['password'])&&isset($_POST['privilege'])){
      $select = $ldb->select('users','aid='.$_POST['id']);
      if(isset($select[0])){
        if(!empty($_POST['username'])&&!empty($_POST['name'])&&!empty($_POST['email'])&&!empty($_POST['password'])&&!empty($_POST['privilege'])){
          if($_POST['username']!==$select[0]['username']){
            $selectu = $ldb->select('users','username='.$_POST['username']);
            if(isset($selectu[0])){
              $allow = false;
            }else{
              $allow = true;
            }
          }else{
            $allow = true;
          }
          if(isset($allow)&&$allow==true){
            $data = $_POST;
            unset($data['id']);
            $update = $ldb->update('users','aid='.$_POST['id'],$data);
            if($update){
              header('location: '.WWW.'admin/edit-user?id='.$_POST['id'].'&status=success-edit-user');
              exit;
            }else{
              header('content-type: text/plain;');
              exit('cannot update the user');
            }
          }else{
            header('content-type: text/plain;');
            exit('username has been taken');
          }
        }else{
          header('content-type: text/plain;');
          exit('some input might be empty');
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot find the user');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Delete user */
elseif($data=='delete-user'){
  if($_SESSION['dixie_privilege']=='master'||$_SESSION['dixie_privilege']=='admin'){
    if(isset($_POST['id'])){
      $select = $ldb->select('users','aid='.$_POST['id']);
      if(isset($select[0])){
        if($select[0]['privilege']!=='master'){
          $delete = $ldb->delete('users','aid='.$_POST['id']);
          header('location: '.WWW.'admin/users?status=success-delete-user');
          exit;
        }else{
          header('content-type: text/plain;');
          exit('cannot delete the user');
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot find the user');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* New menu */
elseif($data=='new-menu'){
  if(sdp()>7){
    if(isset($_POST['name'])&&isset($_POST['slug'])&&isset($_POST['type'])&&isset($_POST['order'])){
      $insert = $ldb->insert('menu',$_POST);
      if($insert){
        header('location: '.WWW.'admin/menu?status=success-add-menu');
        exit;
      }else{
        header('content-type: text/plain;');
        exit('cannot write into database');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Edit menu */
elseif($data=='edit-menu'){
  if(sdp()>7){
    if(isset($_POST['name'])&&isset($_POST['slug'])&&isset($_POST['type'])&&isset($_POST['order'])&&isset($_POST['id'])){
      $select = $ldb->select('menu','aid='.$_POST['id']);
      if(isset($select[0])){
        $data = $_POST;
        unset($data['id']);
        $update = $ldb->update('menu','aid='.$_POST['id'],$data);
        if($update){
          header('location: '.WWW.'admin/edit-menu?id='.$_POST['id'].'&status=success-edit-menu');
          exit;
        }else{
          header('content-type: text/plain;');
          exit('cannot update database');
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot find content menu');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Delete menu */
elseif($data=='delete-menu'){
  if(sdp()>7){
    if(isset($_POST['id'])){
      $select = $ldb->select('menu','aid='.$_POST['id']);
      if(isset($select[0])){
        $delete = $ldb->delete('menu','aid='.$_POST['id']);
        header('location: '.WWW.'admin/menu?status=success-delete-menu');
        exit;
      }else{
        header('content-type: text/plain;');
        exit('cannot find content menu');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* New sidebar */
elseif($data=='new-sidebar'){
  $bar_types = array('text','recent','menu','tags','meta','profile','search');
  if(sdp()>7){
    if(isset($_POST['type'])&&isset($_POST['title'])&&isset($_POST['order'])){
      $data = array(
        'type'=>$_POST['type'],
        'title'=>$_POST['title'],
        'order'=>$_POST['order'],
        'content'=>((isset($_POST['content']))?$_POST['content']:''),
        'option'=>((isset($_POST['option']))?json_encode($_POST['option']):''), // json
      );
      $insert = $ldb->insert('sidebar',$data);
      if($insert){
        header('location: '.WWW.'admin/sidebar?status=success-add-sidebar');
        exit;
      }else{
        header('content-type: text/plain;');
        exit('cannot write into database');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Edit sidebar */
elseif($data=='edit-sidebar'){
  if(sdp()>7){
    if(isset($_POST['title'])&&isset($_POST['type'])&&isset($_POST['order'])&&isset($_POST['id'])){
      $select = $ldb->select('sidebar','aid='.$_POST['id']);
      if(isset($select[0])){
        $data = array(
          'type'=>$_POST['type'],
          'title'=>$_POST['title'],
          'order'=>$_POST['order'],
          'content'=>((isset($_POST['content']))?$_POST['content']:''),
          'option'=>((isset($_POST['option']))?json_encode($_POST['option']):''), // json
        );
        $update = $ldb->update('sidebar','aid='.$_POST['id'],$data);
        if($update){
          header('location: '.WWW.'admin/edit-sidebar?id='.$_POST['id'].'&status=success-edit-sidebar');
          exit;
        }else{
          header('content-type: text/plain;');
          exit('cannot update database');
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot find content sidebar');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Delete sidebar */
elseif($data=='delete-sidebar'){
  if(sdp()>7){
    if(isset($_POST['id'])){
      $select = $ldb->select('sidebar','aid='.$_POST['id']);
      if(isset($select[0])){
        $delete = $ldb->delete('sidebar','aid='.$_POST['id']);
        header('location: '.WWW.'admin/sidebar?status=success-delete-menu');
        exit;
      }else{
        header('content-type: text/plain;');
        exit('cannot find content sidebar');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid form');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Delete plugin */
elseif($data=='delete-plugin'){
  if(sdp()>9){
    $plug = new Plugins();
    $plugins = $plug->plugins;
    $active = $plug->active;
    $name = (isset($_POST['name']))?$_POST['name']:'';
    if(isset($plugins[$name])){
      if(!plugin_active($name)){
        $remove = @remove_dir($plug->dir.'/'.$name);
        if($remove){
          header('location: '.WWW.'admin/plugins?status=success-delete-plugin');
          exit;
        }else{
          header('content-type: text/plain;');
          exit('the plugin cannot be deleted');
        }
      }else{
        header('content-type: text/plain;');
        exit('the plugin cannot be deleted while it\'s active');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot find the plugin');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Delete theme */
elseif($data=='delete-theme'){
  if(sdp()>9){
    $the = new Themes();
    $themes = $the->themes;
    $name = (isset($_POST['name']))?$_POST['name']:'';
    if(isset($themes[$name])){
      if(get_site_info('theme',false)!==$name){
        $remove = @remove_dir($the->themes_dir.'/'.$name);
        if($remove){
          header('location: '.WWW.'admin/themes?status=success-delete-theme');
          exit;
        }else{
          header('content-type: text/plain;');
          exit('the theme cannot be deleted');
        }
      }else{
        header('content-type: text/plain;');
        exit('the theme cannot be deleted while it\'s active');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot find the theme');
    }
  }else{
    header('content-type: text/plain;');
    exit('you don\'t have authorization to use this action');
  }
}
/* Update Dixie */
elseif($data=='update-dixie'){
  if(isset($_GET['update-uri'])){
    $file = json_decode(@file_get_contents($_GET['update-uri']),true);
    if(isset($file['filename'])&&isset($file['uri'])){
      $target = PUBDIR.'/temp/'.$file['filename'];
      $copy = @copy($file['uri'],$target);
      if($copy){
        $zip = new ZipArchive;
        if($zip->open($target)===true){
          if($zip->extractTo(ROOT)){
            $zip->close();
            @unlink($target);
            header('location: '.WWW.'admin/update?status=success-update-dixie');
            exit;
          }
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot get the update data');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot proceed the update, error data');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot proceed the update, update-uri is required');
  }
}
/* Update Dixie upload-way */
elseif($data=='update-dixie-upload'){
  if(isset($_FILES['file'])&&$_FILES['file']['error']==0){
    $file = $_FILES['file'];
    if(substr($file['name'],-4,strlen($file['name']))=='.zip'){
      $target = PUBDIR.'/temp/'.$file['name'];
      $move = @move_uploaded_file($file['tmp_name'],$target);
      if($move&&file_exists($target)){
        $zip = new ZipArchive;
        if($zip->open($target)===true){
          if($zip->extractTo(ROOT)){
            $zip->close();
            @unlink($target);
            header('location: '.WWW.'admin/update?status=success-update-dixie');
            exit;
          }else{
            header('content-type: text/plain;');
            exit('error: the zip file cannot be extracted');
          }
        }else{
          header('content-type: text/plain;');
          exit('error: the zip file has an unknown error');
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot move the uploaded file');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot proceed the update, the file is not zip');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot proceed the update, error file');
  }
}
/* Upload plugin */
elseif($data=='upload-plugin'){
  if(isset($_FILES['file'])&&$_FILES['file']['error']==0){
    $file = $_FILES['file'];
    if(substr($file['name'],-4,strlen($file['name']))=='.zip'){
      $name = str_replace('.zip','',$file['name']);
      $target = PUBDIR.'/temp/'.$file['name'];
      $move = @move_uploaded_file($file['tmp_name'],$target);
      if($move&&file_exists($target)){
        $zip = new ZipArchive;
        if($zip->open($target)===true){
          if($zip->extractTo(ROOT.'plugins/'.$name)){
            $zip->close();
            @unlink($target);
            header('location: '.WWW.'admin/plugins/?status=success-upload-plugin');
            exit;
          }else{
            header('content-type: text/plain;');
            exit('error: the zip file cannot be extracted');
          }
        }else{
          header('content-type: text/plain;');
          exit('error: the zip file has an unknown error');
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot move the uploaded file');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot proceed the update, the file is not zip');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot proceed the update, error file');
  }
}
/* Upload theme */
elseif($data=='upload-theme'){
  if(isset($_FILES['file'])&&$_FILES['file']['error']==0){
    $file = $_FILES['file'];
    if(substr($file['name'],-4,strlen($file['name']))=='.zip'){
      $name = str_replace('.zip','',$file['name']);
      $target = PUBDIR.'/temp/'.$file['name'];
      $move = @move_uploaded_file($file['tmp_name'],$target);
      if($move&&file_exists($target)){
        $zip = new ZipArchive;
        if($zip->open($target)===true){
          if($zip->extractTo(ROOT.'themes/'.$name)){
            $zip->close();
            @unlink($target);
            header('location: '.WWW.'admin/themes/?status=success-upload-theme');
            exit;
          }else{
            header('content-type: text/plain;');
            exit('error: the zip file cannot be extracted');
          }
        }else{
          header('content-type: text/plain;');
          exit('error: the zip file has an unknown error');
        }
      }else{
        header('content-type: text/plain;');
        exit('cannot move the uploaded file');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot proceed the update, the file is not zip');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot proceed the update, error file');
  }
}
/* Settings */
if($data=='activate-theme'){
  $the = new Themes();
  $themes = $the->themes;
  $name = (isset($_POST['name']))?$_POST['name']:'';
  if(isset($themes[$name])){
    $update = $ldb->update('options','key=theme',array('value'=>$_POST['name']));
    header('location: '.WWW.'admin/themes/?status=success-activate-theme');
    exit;
  }else{
    header('content-type: text/plain;');
    exit('cannot find the theme');
  }
}
/* Else action */
else{
  header('content-type: text/plain;');
  exit('invalid request');
}












