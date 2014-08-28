<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Get change log content */
$change_log = @file_get_contents('change_log.txt');;

/* Get all posts */
global $posts;

/* Arrange new posts to $trend */
$trend = array();
foreach($posts as $post){
  if($post['status']=='publish'){
    $trend[$post['type']][$post['aid']] = $post;
  }
}

/* Scan files directory */
$dirname = 'upload/';
$files = scandir($dirname);
$file_count = 0;
foreach($files as $id=>$file){
  if($file!=='.'&&$file!=='..'&&is_file($dirname.$file)){
    $file_count++;
  }
}

/* HTML View */
?>
<div class="home-data">
  <?php
  if(count($trend)>0){
    $dtren = '';
    foreach($trend as $type=>$id){
      $count = count($id);
      $dtren .= '<a href="'.WWW.'admin/posts/?filter-type='.$type.'&ref=home">'.$count.' '.$type.(($count>1)?'s':'').'</a>, ';
    }
    echo 'You\'ve got '.substr($dtren,0,-2).'.';
  }else{
    echo 'You have no post yet.<br /><a href="'.WWW.'admin/new-post/?ref=home"> Create a new post now</a>';
  }
  ?>
</div>

<div class="home-data">
  <?php
  if($file_count>0){echo 'You have <a href="'.WWW.'admin/files/?ref=home">'.$file_count.' file'.(($file_count>1)?'s':'').'</a>.';}
  else{echo 'You have no file yet.<br /><a href="'.WWW.'admin/upload/?ref=home">Upload a file now</a>';}
  ?>
</div>

<div class="home-data">
  Your current theme: <a href="<?php tprint(WWW); ?>admin/theme-option/?name=<?php get_site_info('theme'); ?>&file=index.php&ref=home" title="Option: <?php get_site_info('theme'); ?>"><?php get_site_info('theme'); ?></a>
</div>

<div class="home-data">
  <?php
  $post_editor = get_site_info('post_editor',false);
  if($post_editor=='text'){
    echo 'Post Editor set to <a href="'.WWW.'admin/settings/?ref=home">Text</a>. ';
    echo 'Change to <a href="'.WWW.'admin/settings/?ref=home">HTML</a> to use CK-Editor.<br />';
  }else{
    echo 'Post Editor set to <a href="'.WWW.'admin/settings/?ref=home">HTML</a> (CK-Editor).<br />';
  }
  ?>
  Don't forget to check your <a href="<?php tprint(WWW); ?>admin/settings/?ref=home" title="Edit Settings">Settings</a>.
</div>

<div class="sub-home-content" style="font-size:12px;">
  <div>Change Log<textarea class="form-textarea" style="font-size:12px;"><?php printf($change_log); ?></textarea></div>
</div>

<div class="check-update">
  <a href="<?php print(WWW.'admin/update?check=true&ref=home'); ?>">Check Update Version</a>
</div>