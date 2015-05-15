<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Array of pages */
global $adminReg,$pages,$no_pages,$editor_pages,$priv,$pluger,$icons;
admin_get_data();

//header('content-type: text/plain'); print_r($pluger); exit;

/* Get global $update */
global $update;
if(defined('Q')&&Q=='update'){
  $update = dixie_check_update();
}

/* Set a title */
if(defined('Q')&&array_key_exists(Q,$pages)){
  if(Q=='edit-post'&&isset($_GET['new-post'])){
    $title_page = $pages['new-post'];
  }else{
    $title_page = $pages[Q];
  }
}

/* Create new temporary post */
if(defined('Q')&&Q=='new-post'){
  $temp = get_temporary_post();
  if($temp){
    header('location: '.WWW.'admin/edit-post/?post_id='.$temp['aid'].'&new-post');
    exit;
  }
}

/* HTML view */
?>
<!DOCTYPE html><html lang="en-US"><head>
  <?php admin_get_header(); ?>
  <title><?php echo (isset($title_page))?$title_page.' | ':''; ?>Admin | <?php get_site_info('site_name'); ?> &#8213; Dixie</title>
  <meta content="Dixie - Free and Simple CMS" name="description" />
  <meta content="Generator, CMS" name="keywords" />
  <link rel="shortcut icon" href="<?php print(WWW.PUBDIR); ?>images/dixie-black.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php print(WWW.PUBDIR); ?>admin/css/style-black.css?v=1.1" type="text/css" />
  <link rel="stylesheet" href="<?php print(WWW.PUBDIR); ?>admin/css/font-awesome.min.css?v=1.1" type="text/css" />
  <link rel="stylesheet" href="<?php print(WWW.PUBDIR); ?>admin/css/jquery-ui.css" type="text/css" />

  <meta content="<?php print(WWW.PUBDIR); ?>images/dixie.png" property="og:image" />

  <script type="text/javascript" src="<?php print(WWW.PUBDIR); ?>admin/js/jquery.2.1.3.min.js"></script>
  <script type="text/javascript" src="<?php print(WWW.PUBDIR); ?>admin/js/jquery-ui.min.js"></script>

  <script type="text/javascript" language="javascript">
  var langs = <?php print(json_encode(language_data())); ?>;
  var www = '<?php print(WWW); ?>';
  var pubdir = '<?php print(PUBDIR); ?>';
  var clanguage = '<?php print(lang_get_set()); ?>';
  </script>

  <?php
    if($options['post_editor']=='html'&&defined('Q')&&in_array(Q,$editor_pages)){
      if(is_mobile_browser()){
        echo '<script src="'.WWW.PUBDIR.THIRD_PARTY.'nicedit/nicEdit.js"></script>';
      }else{
        echo '<script src="'.WWW.PUBDIR.THIRD_PARTY.'ckeditor-4.4.7/ckeditor.js"></script>';
      }
    }
  ?>

  <link rel="stylesheet" href="<?php print(WWW.PUBDIR); ?>admin/css/three.css?v=1.1" type="text/css" />
</head><body id="main_body">
  <div class="header">
    <div id="dixie_header">
      <h2>Dixie</h2>
      <div class="fas fa fa-navicon" id="new_aside_button" onclick="aside_menu()" title="Menu"></div>
    </div>
    <a href="<?php print(WWW); ?>?ref=admin_header" target="_blank" title="<?php get_site_info('site_name'); ?>"><div id="site_header"><?php get_site_info('site_name'); ?></div></a>
  </div>
  <div class="clear-both"></div>
  <div class="body">
    <div class="locale"><div id="locale" onclick="change_locale(this,langs,www)"><span title="<?php __locale('Language',true); ?>"><?php echo ucwords(lang_get_set()); ?></span></div></div>

    <!--<div id="aside_button">
      <div class="aside-button-stripo"></div>
      <div class="aside-button-stripo"></div>
      <div class="aside-button-stripo"></div>
    </div>-->

  <table style="width:100%" width="100%" border="0" cellpadding="0" cellspacing="0" class="table-utama"><tbody>
    <tr><td id="tbside">
    <div class="aside" id="aside">
      <a class="aside-link" href="<?php printf(WWW.'admin/?_rdr'); ?>" title="Admin <?php __locale('Home',true); ?>"><div class="aside-each<?php echo ((!defined('Q'))?' aside-active':''); ?>"><div class="fas fa fa-home"></div><?php __locale('Home',true); ?></div></a>
      <?php
      foreach($pages as $slug=>$name){
        $dicon = $icons[$slug]!==''?'fas fa '.$icons[$slug]:'';
        if(!in_array($slug,$no_pages)&&in_array($slug,$priv[$_SESSION['dixie_privilege']])){
          echo '<a class="aside-link" href="'.WWW.'admin/'.$slug.'/?_menu" title="'.$name.'"><div class="aside-each'.((Q==$slug)?' aside-active':((isset($pluger[$slug]))?' aside-pluger':'')).' '.((Q=='edit-post'&&$slug=='new-post'&&isset($_GET['new-post']))?' aside-active':'').'"><div class="'.$dicon.'"></div>'.$name.'</div></a>';
        }
      }
      ?>
      <a class="aside-link" href="<?php printf(WWW.'admin/'); ?>logout?time=<?php printf(time()); ?>" title="<?php __locale('Logout',true); ?>"><div class="aside-each"><div class="fas fa fa-power-off"></div><?php __locale('Logout',true); ?></div></a>
    </div>
    </td><td style="width:100%;">
    <div class="content" id="page_content" onclick="hide_aside()">
      <div class="content-header"><?php echo (isset($title_page))?$title_page:__locale('Home'); ?></div>
      <div class="content-body">
      <?php
        if(defined('Q')&&array_key_exists(Q,$pages)&&file_exists(PUBDIR.'admin/pages/'.Q.'.php')&&in_array(Q,$priv[$_SESSION['dixie_privilege']])){
          @include_once(PUBDIR.'admin/pages/'.Q.'.php');
        }elseif(defined('Q')&&!in_array(Q,$priv[$_SESSION['dixie_privilege']])){
          echo 'you aren\'t authorized to access this page as '.$_SESSION['dixie_privilege'];
        }elseif(!defined('Q')){
          @include_once(PUBDIR.'admin/pages/_home.php');
        }elseif(defined('Q')&&array_key_exists(Q,$pages)&&isset($pluger[Q])&&file_exists('plugins/'.$pluger[Q].'/'.Q.'.php')&&in_array(Q,$priv[$_SESSION['dixie_privilege']])){
          @include_once('plugins/'.$pluger[Q].'/'.Q.'.php');
        }
        if(isset($_GET['icon-list'])){print(fa_list());}
      ?>
      </div>
    </div>
    </td></tr>
  </tbody></table>

  </div>
  <div class="clear-both"></div>
  <div class="footer">
    <?php admin_get_footer(); ?>
    <div class="copyright"><a class="bai" href="http://dixie-cms.herokuapp.com/" target="_blank" title="Dixie - Free and Simple CMS">Dixie</a> <?php echo DIXIE_VERSION; ?> &middot; <?php __locale('Powered by',true); ?> <a class="bai" href="http://luthfie.hol.es/?id=profile" target="_blank" title="Luthfie a.k.a. 9r3i">Luthfie</a> 2014-<?php print(date('Y')); ?></div>
    <style type="text/css">.cke_button__about,#cke_86{display:none;}.fas{margin:0px 5px -3px 0px;padding:0px;width:16px;height:16px;display:inline-block;position:relative;}</style>
    <script type="text/javascript" src="<?php printf(WWW.PUBDIR); ?>admin/js/admin.js?v=2.1"></script>
    <script type="text/javascript" src="<?php printf(WWW.PUBDIR); ?>admin/js/locale.js?v=1.1"></script>
  </div>
</body></html>
<?php
/* function font awesome list */
function fa_list($file=null){
  $file = isset($file)?$file:PUBDIR.'admin/css/font-awesome.min.css';
  $result = array();
  if(is_file($file)){
    $fa = file_get_contents($file);
    preg_match_all('/fa\-[a-z0-9-]+\:/i',$fa,$akur);
    foreach($akur[0] as $aku){
      $ak = substr($aku,0,-1);
      $result[] = '<div class="fa '.$ak.'" style="margin:5px;padding:5px;width:150px;font-size:large;"><span style="margin-left:10px;font-family:Tahoma;font-size:small;">'.$ak.'</span></div>';
    }
    preg_match_all('/fa\-[a-z0-9]+\{/i',$fa,$akur);
    foreach($akur[0] as $aku){
      $ak = substr($aku,0,-1);
      //$result[] = '<div class="fas fa '.$ak.'">'.$ak.'<span>'.$ak.'</span></div>';
    }
  }
  return implode('',$result);
}