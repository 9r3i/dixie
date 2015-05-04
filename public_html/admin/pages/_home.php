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

/* get userdata information */
$userdata = get_user_data(get_active_user());
$user_priv = $userdata['privilege'];

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
    echo __locale('You have').' '.substr($dtren,0,-2).'.';
  }else{
    echo __locale('You have no post yet').'.<br /><a href="'.WWW.'admin/new-post/?ref=home">'.__locale('Create a new post now').'</a>';
  }
  ?>
</div>

<?php if(sdp()>=8){ ?>
<div class="home-data">
  <?php
  if($file_count>0){echo __locale('You have').' <a href="'.WWW.'admin/files/?ref=home">'.$file_count.' '.__locale('file').(($file_count>1)?'s':'').'</a> '.__locale('uploaded').'.';}
  else{echo __locale('You have no file yet').'.<br /><a href="'.WWW.'admin/upload/?ref=home">'.__locale('Upload a file now').'</a>';}
  ?>
</div>

<div class="home-data">
  <?php __locale('Your current theme',true); ?>: <a href="<?php tprint(WWW); ?>admin/theme-option/?name=<?php get_site_info('theme'); ?>&file=index.php&ref=home" title="Option: <?php echo $theme_name; ?>"><?php echo $theme_name; ?></a>
</div>

<div class="home-data">
  <?php
  $plug = new plugins();
  $plug_count = count($plug->plugins);
  if($plug_count>0){echo __locale('You have').' '.$plug_count.' '.__locale('installed').' <a href="'.WWW.'admin/plugins/?ref=home">plugin'.(($plug_count>1)?'s':'').'</a>.';}
  else{echo __locale('You have no installed-plugin yet').'.<br /><a href="'.WWW.'admin/new-plugin/?ref=home">'.__locale('Install a plugin now').'</a>';}
  ?>
</div>
<?php } ?>

<?php if(sdp()>=4){ ?>
<div class="home-data">
  <?php
  $post_editor = get_site_info('post_editor',false);
  if($post_editor=='text'){
    echo __locale('Post Editor set to').' <a href="'.WWW.'admin/settings/?ref=home">Text</a>. ';
    echo __locale('Change to').' <a href="'.WWW.'admin/settings/?ref=home">HTML</a> '.__locale('to use').' CK-Editor.<br />';
  }else{
    echo __locale('Post Editor set to').' <a href="'.WWW.'admin/settings/?ref=home">HTML</a> (CK-Editor).<br />';
  }
  ?>
  <?php if($user_priv=='admin'){ __locale('Don\'t forget to check your',true); ?> <a href="<?php tprint(WWW); ?>admin/settings/?ref=home" title="<?php __locale('Settings',true); ?>"><?php __locale('Settings',true); ?></a>.<?php } ?>
</div>
<?php } ?>

<div class="sub-home-content" style="font-size:12px;">
  <div class="input-parent"><?php __locale('Change Log',true); ?><textarea readonly="true" class="form-textarea" style="font-size:12px;"><?php tprint($change_log); ?></textarea></div>
</div>

<?php if(sdp()>=16){ ?>
<div class="check-update" id="check_update">
  <a href="<?php print(WWW.'admin/update?check=true&ref=home'); ?>"><div class="check-button"><?php __locale('Check Update Version',true); ?></div></a>
</div>
<?php } ?>

<?php
if(sdp()>=16&&!isset($_COOKIE['dixie_thanks'])){
  $thank_you = 'Thank you for choosing Dixie as your CMS.<br />I hope you enjoy it. :)';
  echo '<div id="dialog" title="'.__locale('Thank You').'">';
  __locale($thank_you,true);
  echo '</div>';
}
?>
<script type="text/javascript">
$.get('<?php print(WWW); ?>admin/a?data=check-dixie-update',function(hasil){
  $('#check_update').html(hasil.html);
});
<?php if(sdp()>=16&&!isset($_COOKIE['dixie_thanks'])){ ?>
$(function(){
  $("#dialog").dialog({
    autoOpen:false,
    show:{
      effect:"blind",
      duration:5
    },
    hide:{
      effect:"explode",
      duration:500
    }
  });
  $("#dialog").dialog("open");
  setTimeout(function(){
    $("#dialog").dialog("close");
  },7000);
});
setCookie('dixie_thanks',true,7);
function setCookie(cname,cvalue,exdays){
  var d = new Date();
  d.setTime(d.getTime()+(exdays*24*60*60*1000));
  var expires = "expires="+d.toGMTString();
  /* BlackBerry browser cannot suport document.cookie */
  document.cookie = cname+"="+cvalue+"; "+expires;
}
<?php } ?>
</script>


