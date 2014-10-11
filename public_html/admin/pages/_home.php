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

/* Set posts as privilege */
set_posts_privilege();

/* Arrange new posts to $trend */
$trend = array();
foreach($posts as $post){
  if($post['status']=='publish'){
    $trend[$post['type']][$post['aid']] = $post;
  }
}

/* Count uploaded files */
$dirname = 'upload';
$files = dixie_explore('file',$dirname);
$file_count = count($files);

/* Get current theme name */
$current_theme = get_site_info('theme',false);
$theme_about = get_theme_about($current_theme);
$theme_name = isset($theme_about['Theme Name'])?$theme_about['Theme Name']:$current_theme;

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
    echo 'You have '.substr($dtren,0,-2).'.';
  }else{
    echo 'You have no post yet.<br /><a href="'.WWW.'admin/new-post/?ref=home"> Create a new post now</a>';
  }
  ?>
</div>

<div class="home-data">
  <?php
  if($file_count>0){echo 'You have <a href="'.WWW.'admin/files/?ref=home">'.$file_count.' file'.(($file_count>1)?'s':'').'</a> uploaded.';}
  else{echo 'You have no file yet.<br /><a href="'.WWW.'admin/upload/?ref=home">Upload a file now</a>';}
  ?>
</div>

<div class="home-data">
  Your current theme: <a href="<?php tprint(WWW); ?>admin/theme-option/?name=<?php get_site_info('theme'); ?>&file=index.php&ref=home" title="Option: <?php echo $theme_name; ?>"><?php echo $theme_name; ?></a>
</div>

<div class="home-data">
  <?php
  $plug = new plugins();
  $plug_count = count($plug->plugins);
  if($plug_count>0){echo 'You have '.$plug_count.' installed <a href="'.WWW.'admin/plugins/?ref=home">plugin'.(($plug_count>1)?'s':'').'</a>.';}
  else{echo 'You have no installed-plugin yet.<br /><a href="'.WWW.'admin/new-plugin/?ref=home">Install a plugin now</a>';}
  ?>
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
  <div class="input-parent">Change Log<textarea class="form-textarea" style="font-size:12px;"><?php tprint($change_log); ?></textarea></div>
</div>

<div class="check-update" id="check_update">
  <a href="<?php print(WWW.'admin/update?check=true&ref=home'); ?>"><div class="check-button">Check Update Version</div></a>
</div>

<script type="text/javascript">
$.get('<?php print(WWW); ?>admin/a?data=check-dixie-update',function(hasil){
  $('#check_update').html(hasil.html);
});
</script>
