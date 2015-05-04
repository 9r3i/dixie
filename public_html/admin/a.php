<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

if(get_active_user()=='demo'){
  header('content-type: text/plain;');
  exit('demo version cannot do this action');
}

/* Developer tester scripts by Luthfie */
//header('content-type: text/plain;'); print_r($_POST); exit;

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
  if(isset($_POST['post_editor'])){
    setcookie('dixie_post_editor',$_POST['post_editor'],time()+(24*3600*30));
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
    $slug = ($_POST['title']!=='')?create_slug($_POST['title']):create_slug('temp-'.microtime(true));
    if(preg_match('/(temp\-[\d]{10,14})/i',$select[0]['url'])&&$slug!==''){
    }
    $check = $ldb->select('posts','url='.$slug);
    if(isset($check[0])&&$check[0]['aid']!==$_POST['post_id']){
      $slug .= '-'.$_POST['post_id'];
    }
    $data['url'] = $slug;
    $data['training_date'] = $data['training_date'].'-'.$data['training_date_end'];
    unset($data['training_date_end']);
    if(isset($_POST['category'])){
      if(isset($_POST['new-category'])){
        $cat_slug = create_slug($_POST['new-category']);
        if(!empty($cat_slug)){
          $category = $_POST['new-category'];
        }else{
          $category = '';
        }
      }else{
        $category = $_POST['category'];
      }
      $cat = set_category($_POST['post_id'],$category);
    }
    $data['content'] = is_mobile_browser()&&$options['post_editor']=='text'?dixie_mobile_content_retags($data['content']):$data['content'];
    $update = $ldb->update('posts','aid='.$_POST['post_id'],$data);
    if($update){
      if(isset($_POST['submit'])&&$_POST['submit']==__locale('View')){
        header('location: '.WWW.$slug.'.html?_preview');
      }elseif(isset($_POST['submit'])&&preg_match('/'.__locale('change to').'/i',strtolower($_POST['submit']))){
        $redir = WWW.'admin/edit-post?post_id='.$_POST['post_id'];
        header('location: '.WWW.'admin/a?data=change-editor&to='.substr(strtolower($_POST['submit']),-4).'&re='.$redir);
      }elseif(isset($_POST['submit'])&&preg_match('/^'.__locale('change picture').'$/i',strtolower(trim($_POST['submit'])))){
        header('location: '.WWW.'admin/change-picture/?post_id='.$_POST['post_id']);
      }else{
        header('location: '.WWW.'admin/edit-post?post_id='.$_POST['post_id'].'&status=success-update');
      }
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
    if(isset($_POST['directory'],$_POST['new-directory'])){
      if($_POST['directory']=='new'&&$_POST['new-directory']!==''){
        $dir = 'upload/'.create_slug($_POST['new-directory']).'/';
        @mkdir($dir);
      }else{
        $dir = $_POST['directory'];
      }
    }
    $dir = isset($dir)&&is_dir($dir)?$dir:'upload/';
    $files = rearrange_files($_FILES['file']);
    $r=0;
    if(isset($dir)&&is_dir($dir)){
      foreach($files as $file){
        $r++;
        if($file['error']==0){
          $fn = create_filename($file['name']);
          @move_uploaded_file($file['tmp_name'],$dir.$fn);
        }
      }
    }
    if($r==count($files)){
      if(isset($_POST['type'])&&$_POST['type']=='ajax'){
        header('content-type: text/plain;');
        print($dir);
      }else{
        header('location: '.WWW.'admin/files?dir='.$dir.'&status=success-upload');
      }
      exit;
    }else{
      header('content-type: text/plain;');
      if(isset($_POST['type'])&&$_POST['type']=='ajax'){
        print($dir);
      }else{
        exit('cannot upload the file');
      }
    }
  }else{
    header('content-type: text/plain;');
    if(isset($_POST['type'])&&$_POST['type']=='ajax'){
      print($dir);
    }else{
      exit('cannot upload the file');
    }
  }
}
/* Edit theme */
elseif($data=='edit-theme'){
  if(isset($_POST['file-name'])&&isset($_POST['theme-name'])&&isset($_POST['content'])&&file_exists('themes/'.$_POST['theme-name'].'/'.$_POST['file-name'])){
    $location = 'themes/'.$_POST['theme-name'].'/'.$_POST['file-name'];
    $write = file_write($location,$_POST['content']);
    if($write){
      header('location: '.WWW.'admin/edit-theme?name='.$_POST['theme-name'].'&file='.$_POST['file-name'].'&status=success-update');
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
/* Edit plugin */
elseif($data=='edit-plugin'){
  if(isset($_POST['file'],$_POST['slug'],$_POST['content'])&&is_file('plugins/'.$_POST['slug'].'/'.$_POST['file'])){
    $location = 'plugins/'.$_POST['slug'].'/'.$_POST['file'];
    $write = file_write($location,$_POST['content']);
    if($write){
      header('location: '.WWW.'admin/edit-plugin?name='.$_POST['slug'].'&file='.$_POST['file'].'&status=success-update');
      exit;
    }else{
      header('content-type: text/plain;');
      exit('cannot write on the file');
    }
  }else{
    header('content-type: text/plain;');
    exit('plugin or file doesn\'t exist');
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
              header('location: '.WWW.'admin/users?id='.$_POST['id'].'&status=success-edit-user');
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
        header('location: '.WWW.'admin/menu?type='.$_POST['type'].'&status=success-add-menu');
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
          header('location: '.WWW.'admin/menu?type='.$_POST['type'].'&status=success-edit-menu');
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
        header('location: '.WWW.'admin/menu?type='.$select[0]['type'].'&status=success-delete-menu');
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
  $bar_types = array('text','recent','menu','tags','meta','profile','search','category');
  if(sdp()>7){
    if(isset($_POST['type'],$_POST['title'],$_POST['order'])){
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
          header('location: '.WWW.'admin/sidebar?id='.$_POST['id'].'&status=success-edit-sidebar');
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
          if($zip->extractTo(DROOT)){
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
          if($zip->extractTo(DROOT)){
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
          if($zip->extractTo(DROOT.'plugins/'.$name)){
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
          if($zip->extractTo(DROOT.'themes/'.$name)){
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
/* Get external theme data */
elseif($data=='get-external-theme-data'){
  if(isset($_GET['url'])){
    $file = @file_get_contents($_GET['url']);
    header('content-type: application/json');
    print($file);
    exit;
  }else{
    header('content-type: application/json');
    print(json_encode(array('status'=>'error','message'=>'cannot connect into dixie\'s database themes')));
  }
}
/* Add external theme */
elseif($data=='add-external-theme'){
  if(isset($_GET['file_url'])){
    $exp = @explode('/',$_GET['file_url']);
    $filename = array_pop($exp);
    $target = PUBDIR.'/temp/'.$filename;
    $copy = @copy($_GET['file_url'],$target);
    if($copy){
      $name = (isset($_GET['theme_name']))?$_GET['theme_name']:str_replace('.zip','',$filename);
      if(file_exists($target)){
        $zip = new ZipArchive;
        if($zip->open($target)===true){
          if($zip->extractTo(DROOT.'themes/'.$name)){
            $zip->close();
            @unlink($target);
            header('location: '.WWW.'admin/themes/?status=success-add-external-theme');
            exit;
          }else{
            header('content-type: text/plain;');
            exit('error: the zip file cannot be extracted');
          }
        }else{
          header('content-type: text/plain;');
          exit('error: the zip file cannot be opened');
        }
      }else{
        header('content-type: text/plain;');
        exit('the file isn\'t in temp directory');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot get theme\'s copy file');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot find file url');
  }
}
/* Get external plugin data */
elseif($data=='get-external-plugin-data'){
  if(isset($_GET['url'])){
    $file = @file_get_contents($_GET['url']);
    header('content-type: application/json');
    print($file);
    exit;
  }else{
    header('content-type: application/json');
    print(json_encode(array('status'=>'error','message'=>'cannot connect into dixie\'s database plugins')));
  }
}
/* Add external plugin */
elseif($data=='add-external-plugin'){
  if(isset($_GET['file_url'])){
    $exp = @explode('/',$_GET['file_url']);
    $filename = array_pop($exp);
    $target = PUBDIR.'/temp/'.$filename;
    $copy = @copy($_GET['file_url'],$target);
    if($copy){
      $name = (isset($_GET['plugin_name']))?$_GET['plugin_name']:str_replace('.zip','',$filename);
      if(file_exists($target)){
        $zip = new ZipArchive;
        if($zip->open($target)===true){
          if($zip->extractTo(DROOT.'plugins/'.$name)){
            $zip->close();
            @unlink($target);
            header('location: '.WWW.'admin/plugins/?status=success-add-external-plugin');
            exit;
          }else{
            header('content-type: text/plain;');
            exit('error: the zip file cannot be extracted');
          }
        }else{
          header('content-type: text/plain;');
          exit('error: the zip file cannot be opened');
        }
      }else{
        header('content-type: text/plain;');
        exit('the file isn\'t in temp directory');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot get plugin\'s copy file');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot find file url');
  }
}
/* Purchase external plugin */
elseif($data=='purchase-external-plugin'){
  if(isset($_GET['plugin_code'])){
    $url = 'http://dixie.hol.es/external_purchase.php?product_code='.$_GET['plugin_code'];
    $file = @file_get_contents($url);
    $data = @json_decode($file,true);
    if(isset($data['validation'],$data['data_information'])){
      $info = $data['data_information'];
      if(isset($info['method'],$info['message'],$info['phone'])&&$info['method']=='call'){
        header('content-type: text/plain;');
        echo 'Purchase method: '.$info['method'].PHP_EOL.$info['message'].PHP_EOL.'Here is the phone number: '.$info['phone'];
      }else{
        header('content-type: text/plain;');
        print_r($data);
      }
    }else{
      header('content-type: text/plain;');
      exit('plugin code is not valid');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot find plugin code');
  }
}
/* Activation external plugin */
elseif($data=='activation-external-plugin'){
  if(isset($_POST['activation_code'])){
    $url = 'http://dixie.hol.es/external_activation.php?activation_code='.$_POST['activation_code'];
    $file = @file_get_contents($url);
    $data = @json_decode($file,true);
    if(isset($data['status'],$data['message'],$data['type'])&&$data['type']=='plugin'){
      if($data['status']=='OK'&&isset($data['file_url'])){
        $name = str_replace('.zip','',$data['filename']);
        $target = PUBDIR.'temp/'.$data['filename'];
        @copy($data['file_url'],$target);
        if(is_dir(DROOT.'plugins/'.$name)){
          header('content-type: text/plain;');
          exit('the plugin has been installed');
        }elseif(file_exists($target)){
          $zip = new ZipArchive;
          if($zip->open($target)===true){
            if($zip->extractTo(DROOT.'plugins/'.$name)){
              $zip->close();
              @unlink($target);
              header('location: '.WWW.'admin/plugins/?status=success-activation-external-plugin');
              exit;
            }else{
              header('content-type: text/plain;');
              exit('error: the zip file cannot be extracted');
            }
          }else{
            header('content-type: text/plain;');
            exit('error: the zip file cannot be opened');
          }
        }else{
          header('content-type: text/plain;');
          exit('the file isn\'t in temp directory');
        }
      }else{
        header('content-type: text/plain;');
        exit($data['message']);
      }
    }else{
      header('content-type: text/plain;');
      if(isset($data['message'])){
        exit($data['message']);
      }else{
        exit('unknown error');
      }
    }
  }else{
    header('content-type: text/plain;');
    exit('invalid activation code');
  }
}
/* Purchase external theme */
elseif($data=='purchase-external-theme'){
  if(isset($_GET['theme_code'])){
    $url = 'http://dixie.hol.es/external_purchase.php?product_code='.$_GET['theme_code'];
    $file = @file_get_contents($url);
    $data = @json_decode($file,true);
    if(isset($data['validation'],$data['data_information'])){
      $info = $data['data_information'];
      if(isset($info['method'],$info['message'],$info['phone'])&&$info['method']=='call'){
        header('content-type: text/plain;');
        echo 'Purchase method: '.$info['method'].PHP_EOL.$info['message'].PHP_EOL.'Here is the phone number: '.$info['phone'];
      }else{
        header('content-type: text/plain;');
        print_r($data);
      }
    }else{
      header('content-type: text/plain;');
      exit('theme code is not valid');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot find theme code');
  }
}
/* Activation external theme */
elseif($data=='activation-external-theme'){
  if(isset($_POST['activation_code'])){
    $url = 'http://dixie.hol.es/external_activation.php?activation_code='.$_POST['activation_code'];
    $file = @file_get_contents($url);
    $data = @json_decode($file,true);
    if(isset($data['status'],$data['message'],$data['type'])&&$data['type']=='theme'){
      if($data['status']=='OK'&&isset($data['file_url'])){
        $name = str_replace('.zip','',$data['filename']);
        $target = PUBDIR.'temp/'.$data['filename'];
        @copy($data['file_url'],$target);
        if(is_dir(DROOT.'themes/'.$name)){
          header('content-type: text/plain;');
          exit('the theme has been installed');
        }elseif(file_exists($target)){
          $zip = new ZipArchive;
          if($zip->open($target)===true){
            if($zip->extractTo(DROOT.'themes/'.$name)){
              $zip->close();
              @unlink($target);
              header('location: '.WWW.'admin/themes/?status=success-activation-external-theme');
              exit;
            }else{
              header('content-type: text/plain;');
              exit('error: the zip file cannot be extracted');
            }
          }else{
            header('content-type: text/plain;');
            exit('error: the zip file cannot be opened');
          }
        }else{
          header('content-type: text/plain;');
          exit('the file isn\'t in temp directory');
        }
      }else{
        header('content-type: text/plain;');
        exit($data['message']);
      }
    }else{
      header('content-type: text/plain;');
      if(isset($data['message'])){
        exit($data['message']);
      }else{
        exit('unknown error');
      }
    }
  }else{
    header('content-type: text/plain;');
    exit('invalid activation code');
  }
}
/* Check dixie update */
elseif($data=='check-dixie-update'){
  $update = dixie_check_update();
  header('content-type: application/json;');
  $data = array();
  if($update){
    $data['status'] = 'OK';
    $data['html'] = '<div class="update-info">'.__locale('Update version').' '.$update['update_version'].' '.__locale('is available').'. <a href="'.WWW.'admin/a?data=update-dixie&update-uri='.urlencode($update['update_uri']).'" title="Update to version '.$update['update_version'].'"><button class="update-button">Update Now</button></a></div>';
  }else{
    $data['status'] = 'up-to-date';
    $data['html'] = '<div class="update-info">'.__locale('Dixie is up to date').'</div>';
  }
  print(json_encode($data));
  exit;
}
/* Activate theme */
elseif($data=='activate-theme'){
  $the = new Themes();
  $themes = $the->themes;
  $name = (isset($_POST['name']))?$_POST['name']:'';
  if(isset($themes[$name])){
    $update = $ldb->update('options','key=theme',array('value'=>$_POST['name']));
    $update = $ldb->update('options','key=msie_theme',array('value'=>$_POST['name']));
    $update = $ldb->update('options','key=mobile_theme',array('value'=>$_POST['name']));
    header('location: '.WWW.'admin/themes/?status=success-activate-theme');
    exit;
  }else{
    header('content-type: text/plain;');
    exit('cannot find the theme');
  }
}
/* Change editor */
elseif($data=='change-editor'){
  if(isset($_GET['re'])&&isset($_GET['to'])){
    if($_GET['to']=='html'||$_GET['to']=='text'){
      setcookie('dixie_post_editor',$_GET['to'],time()+(24*3600*30));
      $update = $ldb->update('options','key=post_editor',array('value'=>$_GET['to']));
      if($update){
        header('location: '.$_GET['re']);
      }else{
        header('content-type: text/plain;');
        exit('cannot update database');
      }
    }else{
      header('content-type: text/plain;');
      exit('invalid request to');
    }
  }else{
    header('content-type: text/plain;');
    exit('invalid action request');
  }
}
/* Bulk action */
elseif($data=='bulk-action'){
  if(isset($_POST['action'])){
    $bulk_actions = array('trash','delete','publish','draft');
    header('content-type: text/plain;');
    if(is_array($_POST['check'])&&in_array($_POST['action'],$bulk_actions)){
      foreach($_POST['check'] as $id){
        if($_POST['action']=='delete'){
          $ldb->delete('posts','aid='.$id);
        }else{
          $ldb->update('posts','aid='.$id,array('status'=>$_POST['action']));
        }
      }
      header('location: '.WWW.'admin/posts/'.$_POST['query']);
      exit;
    }else{
      exit('invalid action request');
    }
  }else{
    header('content-type: text/plain;');
    exit('invalid action request');
  }
}
/* Bulk action file */
elseif($data=='bulk-action-file'){
  if(isset($_POST['action'])){
    $bulk_actions = array('delete','delete-all');
    header('content-type: text/plain;');
    if(in_array($_POST['action'],$bulk_actions)){
      if($_POST['action']=='delete'&&is_array($_POST['check'])){
        foreach($_POST['check'] as $id){
          if($_POST['action']=='delete'){
            @unlink($id);
          }
        }
      }elseif($_POST['action']=='delete-all'&&isset($_POST['dir'])){
        $files = dixie_explore('file',$_POST['dir']);
        if(is_array($files)){
          foreach($files as $file){
            @unlink($file);
          }
        }
        @rmdir($_POST['dir']);
      }
      header('location: '.WWW.'admin/files/?status=success-bulk-'.$_POST['action']);
      exit;
    }else{
      exit('invalid action request');
    }
  }else{
    header('content-type: text/plain;');
    exit('no action request');
  }
}
/* Upload post picture */
elseif($data=='upload-post-picture'){
  if(isset($_FILES['file'])){
    if(isset($_POST['directory'],$_POST['new-directory'])){
      if($_POST['directory']=='new'&&!empty($_POST['new-directory'])){
        @mkdir('upload/'.$_POST['new-directory']);
        $dir = 'upload/'.$_POST['new-directory'].'/';
      }elseif(!empty($_POST['directory'])&&is_dir($_POST['directory'])){
        $dir = $_POST['directory'];
      }else{
        $dir = 'upload/';
      }
    }
    $files = rearrange_files($_FILES['file']);
    $r=0;
    if(isset($dir)&&is_dir($dir)){
      foreach($files as $file){
        $r++;
        if($file['error']==0){
          $fn = create_filename($file['name']);
          @move_uploaded_file($file['tmp_name'],$dir.$fn);
          if(file_exists($dir.$fn)){
            $filename = $dir.$fn;
          }
        }
      }
    }
    if(isset($filename)){
      $update = $ldb->update('posts','aid='.$_POST['post_id'],array('picture'=>$filename));
    }
    if($r==count($files)){
      header('location: '.WWW.'admin/change-picture/?post_id='.$_POST['post_id'].'&status=success-upload');
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
/* Change post picture */
elseif($data=='change-post-picture'){
  if(isset($_POST['file'],$_POST['post_id'])){
    $file = (preg_match('/images\/unknown\.png/i',$_POST['file']))?'':str_replace(WWW,'',$_POST['file']);
    $select = $ldb->select('posts','aid='.$_POST['post_id']);
    if(isset($select[0])){
      $update = $ldb->update('posts','aid='.$_POST['post_id'],array('picture'=>$file));
      if($update){
        header('location: '.WWW.'admin/edit-post/?post_id='.$_POST['post_id'].'&status=success-change-picture');
        exit;
      }else{
        header('content-type: text/plain;');
        exit('cannot update database');
      }
    }else{
      header('content-type: text/plain;');
      exit('cannot find the post');
    }
  }else{
    header('content-type: text/plain;');
    exit('cannot save the picture');
  }
}
/* Change locale */
elseif($data=='change-locale'){
  $data = language_data();
  $exp = explode('_',base64_decode($_SESSION['dixie_login']));
  if(isset($_GET['locale'],$exp[1])&&in_array($_GET['locale'],$data)){
    $ldb->update('language_option','username='.$exp[1],array('lang'=>$_GET['locale']));
    $_SESSION['dixie_locale'] = $_GET['locale'];
  }else{
    $_SESSION['dixie_locale'] = $_GET['locale'];
  }
  $ref = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:WWW.'admin?status=locale-update';
  header('location: '.$ref);
  exit;
}
/* re-order menu */
elseif($data=='reorder-menu'){
  if(isset($_POST)){
    foreach($_POST as $key=>$value){
      $select = $ldb->select('menu','aid='.$key);
      if(isset($select[0])){
        $ldb->update('menu','aid='.$key,array('order'=>$value));
      }
    }
    header('content-type: application/json');
    print(json_encode(array(
      'code'=>200,
      'status'=>'OK',
      'message'=>'success',
      'type'=>'re-order menu',
    )));
    exit;
  }else{
    header('content-type: text/plain;');
    exit('invalid request');
  }
}
/* re-order sidebar */
elseif($data=='reorder-sidebar'){
  if(isset($_POST)){
    foreach($_POST as $key=>$value){
      $select = $ldb->select('sidebar','aid='.$key);
      if(isset($select[0])){
        $ldb->update('sidebar','aid='.$key,array('order'=>$value));
      }
    }
    header('content-type: application/json');
    print(json_encode(array(
      'code'=>200,
      'status'=>'OK',
      'message'=>'success',
      'type'=>'re-order sidebar',
    )));
    exit;
  }else{
    header('content-type: text/plain;');
    exit('invalid request');
  }
}
/* Else action */
else{
  header('content-type: text/plain;');
  exit('invalid request');
}












