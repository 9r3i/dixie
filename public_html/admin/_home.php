<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Array of pages */
$pages = array(
  'new-post'=>'New Post', // e & a
  'posts'=>'All Posts',   // e & a
  'files'=>'Files',       // e
  'menu'=>'Menu',         // e
  'sidebar'=>'Sidebar',   // e
  'themes'=>'Themes',     // 
  'plugins'=>'Plugins',   // 
  'users'=>'Users',       // 
  'account'=>'Account',   // ALL
  'settings'=>'Settings', // 

  'edit-post'=>'Edit Post',         // e & a
  'edit-theme'=>'Edit Theme',       //
  'new-user'=>'Add New User',       // 
  'edit-user'=>'Edit User',         //
  'new-menu'=>'New Menu',           // e
  'edit-menu'=>'Edit Menu',         // e
  'new-sidebar'=>'New Sidebar',     // e
  'edit-sidebar'=>'Edit Sidebar',   // e
  'plugin-option'=>'Plugin Option', // 
  'theme-option'=>'Theme Option',   // 
  'new-plugin'=>'Add New Plugin',   // 
  'new-theme'=>'Add New Theme',   // 
  'confirmation'=>'Confirmation',   // e & a
  'upload'=>'Upload New File',      // e
  'update'=>'Update',               //
);
$no_pages = array('edit-post','confirmation','upload','edit-theme','new-user','edit-user','new-menu','edit-menu','new-sidebar','edit-sidebar','plugin-option','update','theme-option','new-plugin','new-theme');
$editor_pages = array('edit-post','new-post');

/* Set privileges */
$priv = array(
  'master'=>array('new-post','posts','files','themes','users','account','settings','edit-post','confirmation','upload','edit-theme','new-user','edit-user','menu','new-menu','edit-menu','sidebar','new-sidebar','edit-sidebar','plugins','plugin-option','update','theme-option','new-plugin','new-theme'),
  'admin'=>array('new-post','posts','files','themes','users','account','settings','edit-post','confirmation','upload','edit-theme','new-user','edit-user','menu','new-menu','edit-menu','sidebar','new-sidebar','edit-sidebar','plugins','plugin-option','update','theme-option','new-plugin','new-theme'),
  'editor'=>array('new-post','posts','files','account','edit-post','confirmation','upload','menu','new-menu','edit-menu','sidebar','new-sidebar','edit-sidebar'),
  'author'=>array('new-post','posts','account','edit-post','confirmation'),
  'member'=>array('account'),
);

/* Get global $update */
global $update;
if(defined('Q')&&Q=='update'){
  $update = dixie_check_update();
}

/* Set a title */
if(defined('Q')&&array_key_exists(Q,$pages)){
  $title_page = $pages[Q];
}

/* HTML view */
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title><?php echo (isset($title_page))?$title_page.' | ':''; ?>Admin &#8213; Dixie</title>
    <meta content="Dixie CMS from Black Apple Inc." name="description" />
    <meta content="Generator, CMS" name="keywords" />
    <meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />
    <meta content="Dixie" name="generator" /><meta content="<?php printf(DIXIE_VERSION); ?>" name="version" />
    <link rel="shortcut icon" href="<?php printf(WWW.PUBDIR); ?>admin/images/dixie.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?php printf(WWW.PUBDIR); ?>admin/css/style.css" type="text/css" />
    <meta content="<?php printf(WWW.PUBDIR); ?>admin/images/dixie.png" property="og:image" />
    <script type="text/javascript" src="<?php printf(WWW.PUBDIR); ?>admin/js/jquery.1.10.2.js"></script>
    <?php echo ($options['post_editor']=='html'&&defined('Q')&&in_array(Q,$editor_pages))?'<script src="'.WWW.'public_html/admin/editor/ckeditor.js"></script>':''; ?>
  </head>
  <body>
    <div class="header">
      Dixie
    </div>
    <div class="clear-both"></div>
    <div class="body">
      <div class="aside">
        <a class="aside-link" href="<?php printf(WWW.'admin/?_rdr'); ?>" title="Admin Home"><div class="aside-each">Home</div></a>
        <?php
        foreach($pages as $slug=>$name){
          if(!in_array($slug,$no_pages)&&in_array($slug,$priv[$_SESSION['dixie_privilege']])){
            echo '<a class="aside-link" href="'.WWW.'admin/'.$slug.'/?_menu" title="'.$name.'"><div class="aside-each">'.$name.'</div></a>';
          }
        }
        ?>
        <a class="aside-link" href="<?php printf(WWW.'admin/'); ?>logout?time=<?php printf(time()); ?>" title="Logout"><div class="aside-each">Logout</div></a>
      </div>
      <div class="content">
        <div class="content-header"><?php echo (isset($title_page))?$title_page:'Home'; ?></div>
        <div class="content-body">
        <?php
          if(defined('Q')&&array_key_exists(Q,$pages)&&file_exists(PUBDIR.'admin/pages/'.Q.'.php')&&in_array(Q,$priv[$_SESSION['dixie_privilege']])){
            @include_once(PUBDIR.'admin/pages/'.Q.'.php');
          }elseif(defined('Q')&&!in_array(Q,$priv[$_SESSION['dixie_privilege']])){
            echo 'you aren\'t authorized to access this page';
          }elseif(!defined('Q')){
            @include_once(PUBDIR.'admin/pages/_home.php');
          }
        ?>
        </div>
      </div>
    </div>
    <div class="clear-both"></div>
    <div class="footer">
      <div class="copyright">Dixie &middot; Version <?php echo DIXIE_VERSION; ?> &middot; Powered by <a class="bai" href="http://black-apple.biz/" target="_blank" title="Black Apple Inc.">Black Apple Inc.</a></div>
      <script type="text/javascript" src="<?php printf(WWW.PUBDIR); ?>admin/js/admin.js"></script>
    </div>
  </body>
</html>