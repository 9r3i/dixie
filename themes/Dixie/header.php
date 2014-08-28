<!DOCTYPE html>
<html lang="en-US">
  <head>
    <?php get_header(); ?>
    <link rel="shortcut icon" href="<?php printf(WWW); ?>themes/Dixie/images/dixie.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?php printf(WWW); ?>themes/Dixie/style.css?v=1.10" type="text/css" />
    <meta content="<?php printf(WWW); ?>themes/Dixie/images/dixie.png" property="og:image" />
    <script src="<?php printf(WWW); ?>themes/Dixie/js/jquery.1.10.2.js" type="text/javascript"></script>
    <style type="text/css">
      .header-logo{
        background-image:url('<?php if(get_theme_option('Dixie','header-image')!==''){echo get_theme_option('Dixie','header-image');}else{echo WWW.'themes/Dixie/images/dixie-logo.png';} ?>');
        background-repeat:no-repeat;
        background-color:transparent;
      }
      .sidebar-text-title,.sidebar-recent-title,.sidebar-menu-title{display:<?php echo (get_theme_option('Dixie','sidebar-title')=='yes')?'block':'none'; ?>;}
    </style>
  </head>
  <body>
    <header><div class="header">
      <div class="header-logo"></div>
      <div class="header-title">
        <h1><?php if(get_theme_option('Dixie','header-name')!==''){echo get_theme_option('Dixie','header-name');}else{get_site_info('site_name');} ?></h1>
      </div>
      <div id="menu_button">
        <div class="menu-button-strip"></div>
        <div class="menu-button-strip"></div>
        <div class="menu-button-strip"></div>
      </div>
    </div></header>
    <div class="clear-both"></div>
    <nav><div id="nav">
      <?php get_menu_print('top'); ?>
    </div></nav>
    <div class="tubuh">
      <div class="body">