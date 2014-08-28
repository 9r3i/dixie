<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Import mueeza timezone */
require_once('mueeza_timezone.php');

/* Get site options */
function get_options(){
  global $ldb,$options;
  if(ldb()){
    $set = $ldb->select('options');
    $options = array();
    if(isset($set[0])){
      foreach($set as $each){
        $options[$each['key']] = $each['value'];
      }
    }
    return $options;
  }else{
    return false;
  }
}

/* Get posts contents */
function get_posts($index=null,$where=null){
  global $ldb,$posts;
  if(ldb()){
    $select = $ldb->select('posts',((isset($where))?$where:false));
    $posts = array();
    if(isset($index)){
      if(isset($select[0][$index])){
        foreach($select as $post){
          $posts[$post[$index]] = $post;
        }
        return $posts;
      }else{
        $posts = $select;
        return $posts;
      }
    }else{
      $posts = $select;
      return $posts;
    }
  }else{
    return false;
  }
}

/* Get site info */
function get_site_info($index=null,$print=true){
  global $options;
  if(is_array($options)&&isset($options[$index])){
    if($print){
      return printf($options[$index]);
    }else{
      return $options[$index];
    }
  }else{
    return false;
  }
}

/* Get menu */
function get_menus($index=null,$where=null){
  global $ldb,$menus;
  if(ldb()){
    $select = $ldb->select('menu',((isset($where))?$where:false));
    $menus = array();
    if(isset($index)){
      if(isset($select[0][$index])){
        foreach($select as $menu){
          if($index=='order'){
            $menus[$menu[$index].'_'.$menu['cid']] = $menu;
          }else{
            $menus[$menu[$index]] = $menu;
          }
        }
        ksort($menus);
        return $menus;
      }else{
        $menus = $select;
        return $menus;
      }
    }else{
      $menus = $select;
      return $menus;
    }
  }else{
    return false;
  }
}
/* Get menu print mode with tags */
function get_menu_print($type='top'){
  /* Global $menus */
  global $menus;
  if(get_menus('order')){
    $content = '';
    foreach($menus as $menu){
      if($menu['type']==$type){
        $url = (preg_match('/http/i',$menu['slug']))?$menu['slug']:WWW.$menu['slug'];
        $target = (preg_match('/http/i',$menu['slug']))?' target="_blank" ':'';
        $content .= '<a href="'.$url.'"'.$target.' title="'.$menu['name'].'"><div class="menu-list menu-list-'.$type.'" id="menu_list_'.$menu['aid'].'">'.$menu['name'].'</div></a>';
      }
    }
    echo $content;
  }else{
    return false;
  }
}

/* Get sidebar */
function get_sidebar($index=null,$where=null){
  global $ldb,$sidebar;
  if(ldb()){
    $select = $ldb->select('sidebar',((isset($where))?$where:false));
    $sidebar = array();
    if(isset($index)){
      if(isset($select[0][$index])){
        foreach($select as $side){
          if($index=='order'){
            $sidebar[$side[$index].'_'.$side['cid']] = $side;
          }else{
            $sidebar[$side[$index]] = $side;
          }
        }
        ksort($sidebar);
        return $sidebar;
      }else{
        $sidebar = $select;
        return $sidebar;
      }
    }else{
      $sidebar = $select;
      return $sidebar;
    }
  }else{
    return false;
  }
}
/* Get sidebar with print mode */
function get_sidebar_print(){
  /* Global $sidebar */
  global $sidebar;
  if(get_sidebar('order')){
    $content = '';
    foreach($sidebar as $bar){
      if($bar['type']=='menu'){
        echo '<div class="sidebar-menu">';
        echo '<div class="sidebar-menu-title">'.((!empty($bar['title']))?$bar['title']:'Menu').'</div>';
        echo '<div class="sidebar-menu-content">';
        get_menu_print('sidebar');
        echo '</div></div>';
      }elseif($bar['type']=='recent'){
        $option = json_decode($bar['option'],true);
        $posts = get_posts('aid','type='.$option['post_type'].'&status=publish');
        echo '<div class="sidebar-recent">';
        echo '<div class="sidebar-recent-title">'.((!empty($bar['title']))?$bar['title']:'Recent '.ucwords($option['post_type'])).'</div>';
        echo '<div class="sidebar-recent-content">';
        $ro=0;
        foreach(array_reverse($posts) as $post){
          if($post['access']=='public'){
            $ro++;
            echo '<a href="'.WWW.$post['url'].'.html" title="'.htmlspecialchars($post['title']).'"><div class="sidebar-recent-list">'.$post['title'].'</div></a>';
            if($ro==$option['post_max']){break;}
          }
        }
        echo '</div></div>';
      }elseif($bar['type']=='text'){
        echo '<div class="sidebar-text">';
        echo '<div class="sidebar-text-title">'.((!empty($bar['title']))?$bar['title']:'').'</div>';
        echo '<div class="sidebar-text-content">';
        echo $bar['content'];
        echo '</div></div>';
      }
    }
  }else{
    return false;
  }
}

/* Get post info */
function get_post_detail($index=null,$print=true,$author_name=true){
  global $post;
  if(is_array($post)&&isset($post[$index])){
    if($index=='content'){
      $run = plugin_run('content',$post[$index]);
    }elseif($index=='author'){
      $author = get_user_data($post[$index]);
      $run = ($author_name)?$author['name']:$post[$index];
    }else{
      $run = $post[$index];
    }
    if($print){
      return print($run);
    }else{
      return $run;
    }
  }else{
    return false;
  }
}

/* Get html header meta according to global post and options */
function get_header($print=true){
  global $post,$posts,$options;
  $content = '<meta content="text/html; charset=utf-8" http-equiv="content-type" />';
  $content .= '<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />';
  $content .= '<meta content="width=device-width, initial-scale=1" name="viewport" />';
  $content .= '<title>'.get_title(false).'</title>';
  $content .= '<meta content="'.((isset($post['description']))?$post['description']:$options['site_description']).'" name="description" />';
  $content .= '<meta content="'.((isset($post['keywords']))?$post['keywords']:$options['site_keywords']).'" name="keywords" />';
  $content .= '<meta content="'.$options['robots'].'" name="robots" />';
  $content .= '<meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />';
  $content .= '<meta content="Dixie" name="generator" /><meta content="'.DIXIE_VERSION.'" name="version" />';
  $content .= '<link href="'.WWW.((isset($post['url']))?$post['url'].'/':'').'" type="text/html" rel="canonical" />';
  $content .= '<link href="'.WWW.((isset($post['url']))?$post['url'].'.html':'').'" type="text/html" rel="pingback" />';
  $content .= '<link href="'.WWW.'feed.xml" title="RSS Feed" type="application/rss+xml" rel="alternate" />';
  $content .= '';
  if($print){
    return printf($content);
  }else{
    return $content;
  }
}

/* Get title */
function get_title($print=true){
  global $post,$options,$template;
  $types = array('post','page','article','training','schedule','product','event');
  $title = $options['site_name'];
  if(isset($post['title'])){
    $title = $post['title'].(($post['title']!==$options['site_name'])?' &#8213; '.$options['site_name']:'');
  }elseif($template=='index'){
    $title = $options['site_name'];
  }elseif($template=='type'&&defined('P')){
    $title = ucfirst(P).' &#8213; '.$options['site_name'];
  }else{
    $title = '404 Not Found &#8213; '.$options['site_name'];
  }
  if($print){
    return printf($title);
  }else{
    return $title;
  }
}

/* Get footer body-tag */
function get_footer($print=true){
  $content = '<div id="copyright">Copyright &copy; '.date('Y').' &middot; <a href="'.WWW.'" title="'.get_site_info('site_name',false).'" target="_blank">'.get_site_info('site_name',false).'</a> &middot; All Right Reserved</div>';
  if($print){
    return printf($content);
  }else{
    return $content;
  }
}

/* Get current active username */
function get_active_user(){
  if(isset($_SESSION['dixie_login'])){
    $decode = base64_decode($_SESSION['dixie_login']);
    $exp = explode('_',$decode);
    if(isset($exp[1])){
      return $exp[1];
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Get current user data */
function get_user_data($username=null){
  global $ldb;
  if(ldb()){
    $user = (isset($username))?$username:'';
    $select = ($username===true)?$ldb->select('users'):$ldb->select('users','username='.$user);
    if($username===true&&$_SESSION['dixie_privilege']=='master'){
      return $select;
    }elseif($username===true&&$_SESSION['dixie_privilege']=='admin'){
      return $select;
    }elseif(isset($select[0])){
      $select[0]['id'] = $select[0]['aid'];
      unset($select[0]['password']);
      unset($select[0]['aid']);
      unset($select[0]['cid']);
      return $select[0];
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Get index page template */
function get_index(){
  global $template,$post,$posts,$options;
  $types = array('post','page','article','training','schedule','product','event');
  $template = 'index';
  get_posts('url');
  get_options();
  if(!defined('P')){
    if(array_key_exists($options['main_page'],$posts)){
      $template = 'post';
      $post = $posts[$options['main_page']];
    }else{
      $template = 'index';
    }
  }elseif(defined('P')&&P=='index.php'){
    if(array_key_exists($options['main_page'],$posts)){
      $template = 'post';
      $post = $posts[$options['main_page']];
    }else{
      $template = 'index';
    }
  }elseif(defined('P')&&in_array(P,$types)){
    $template = 'type';
  }elseif(defined('P')&&array_key_exists(P,$posts)){
    $template = 'post';
    $post = $posts[P];
  }elseif(defined('P')&&isset($posts[str_replace('.html','',P)])){
    $template = 'post';
    $post = $posts[str_replace('.html','',P)];
  }else{
    $template = '404';
  }
  return $template;
}

/* Generate posts information for index template */
function generate_index_posts($type='post'){
  global $posts;
  $type = (get_index()=='type')?P:$type;
  if(is_array($posts)&&count($posts)>0){
    $hasil = '<div class="dixie-posts">';
    $s_hasil = '';
    $tposts = ($type=='training')?$posts:array_reverse($posts);
    foreach($tposts as $post){
      $s_hasil .= generate_index_single_post($post,$type);
    }
    if($s_hasil!==''){
      $hasil .= $s_hasil;
    }else{
      $an = array('article','event');
      $pre = (in_array($type,$an))?'an':'a';
      $hasil .= '<h2>Doesn\'t have '.$pre.' <a href="'.WWW.$type.'/">'.ucwords($type).'</a> yet</h2>';
    }
    $hasil .= '</div>';
    return $hasil;
  }else{
    return false;
  }
}
/* Generate per single post */
function generate_index_single_post($post,$type='post',$word=39){
  $types = array('post','page','article','training','schedule','product','event');
  $split = explode(' ',strip_tags($post['content'],'<p>'));
  $len = (count($split)>=$word)?$word:count($split);
  $imp = array();
  for($r=0;$r<$len;$r++){
    $imp[] = $split[$r];
  }
  $con = implode(' ',$imp);
  $dots = (count($split)>$word)?'...':'';
  $split_baris = @explode(PHP_EOL,$con);
  $new_baris = array();
  if(is_array($split_baris)&&count($split_baris)>5){ // filter by 5 rows
    for($r=0;$r<5;$r++){$new_baris[] = $split_baris[$r];}
    $content = @implode(PHP_EOL,$new_baris).'...';
  }else{
    $content = $con.$dots;
  }
  $time = strtotime($post['training_date']);
  $hasil ='';
  if($post['type']==$type&&$post['access']=='public'&&$post['status']=='publish'){
    $hasil .= '<div class="each-post data-'.$type.'" data-type="'.$type.'">';
    $hasil .= '<div class="each-post-title"><a href="'.WWW.$post['url'].'.html"><h3>'.$post['title'].'</h3></a></div>';
    if($type=='training'){
      $hasil .= '<div class="each-post-detail"><div class="each-post-detail-training-date">'.date('F, jS Y',$time).'</div><div class="each-post-detail-trainer">'.$post['trainer'].'</div></div>';
    }elseif($type=='post'||$type=='article'){
      $author = get_user_data($post['author']);
      $hasil .= '<div class="each-post-detail"><div class="each-post-detail-time">'.date('F, jS Y',$post['time']).'</div>&#8213;<div class="each-post-detail-author">'.$author['name'].'</div></div>';
    }
    $hasil .= '<div class="each-post-content">'.$content.(($dots=='...')?' <a style="white-space:pre;" href="'.WWW.$post['url'].'.html">[Read More...]</a>':'').'</div>';
    $hasil .= '</div>';
  }
  if($type=='training'&&$time<time()){$hasil= '';}
  return $hasil;
}

/* Plugins registry store at global $plugReg */
function plugin_registry($p_name,$p_index=array(),$priority=10,$arg=array()){
  global $plugReg;
  if(isset($p_name)&&is_array($p_index)){
    $plugReg[$priority.'_'.$p_name]['name'] = $p_name;
    $plugReg[$priority.'_'.$p_name]['index'] = $p_index;
    $plugReg[$priority.'_'.$p_name]['priority'] = $priority;
    $plugReg[$priority.'_'.$p_name]['arg'] = $arg;
    return true;
  }
}

/* Request plugin content */
function plugin_run($type,$content=false){
  global $plugReg,$plugin;
  if(!is_object($plugin)){
    $plugin = new Plugins('plugins');
  }
  $plugin->load($type);
  $hasil = $content;
  if(is_array($plugReg)){
    ksort($plugReg);
    foreach($plugReg as $func){
      $arg = array();
      $arg[]=$hasil;
      if(in_array(get_index(),$func['index'])){
        $hasil = call_user_func_array($func['name'],$arg);
      }
    }
  }
  return $hasil;
}

/* Check plugin status */
function plugin_active($name=null){
  if(isset($name)&&file_exists('plugins/'.$name.'/status.txt')){
    $content = @file_get_contents('plugins/'.$name.'/status.txt');
    if($content=='active'){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Set plugin status */
function set_plugin_status($name=null,$status=true){
  if(isset($name)&&is_dir('plugins/'.$name.'/')){
    $filename = 'plugins/'.$name.'/status.txt';
    $content = ($status)?'active':'';
    $write = file_write($filename,$content);
    return true;
  }else{
    return false;
  }
}

/* Get plugin option according to $key */
function get_plugin_option($plugin_name,$key=null){
  $option_file = 'plugins/'.$plugin_name.'/options.txt';
  $file = @file($option_file);
  $hasil = array();
  foreach($file as $fi){
    $exp = explode('===',trim($fi));
    if(isset($exp[1])){
      $hasil[$exp[0]] = $exp[1];
    }
  }
  if(isset($key)&&isset($hasil[$key])){
    return $hasil[$key];
  }else{
    return false;
  }
}

/* Set plugin option then write to the option file in array parameter */
function set_plugin_option($plugin_name,$post_content=array()){
  $option_file = 'plugins/'.$plugin_name.'/options.txt';
  $content = array();
  foreach($post_content as $key=>$value){
    $content[] = $key.'==='.$value;
  }
  $content_write = @implode(PHP_EOL,$content);
  $write = file_write($option_file,$content_write,'w+');
  if($write){
    return true;
  }else{
    return false;
  }
}

/* Get plugin about */
function get_plugin_about($name=null){
  if(isset($name)&&file_exists('plugins/'.$name.'/about.txt')){
    $content = @file_get_contents('plugins/'.$name.'/about.txt');
    return $content;
  }else{
    return false;
  }
}

/* Get theme option */
function get_theme_option($name=null,$key=null){
  $hasil = array();
  if(isset($name)){
    $option_file = 'themes/'.$name.'/options.txt';
    if(file_exists($option_file)){
      $file = @file($option_file);
      if(is_array($file)){
        foreach($file as $fi){
          $exp = explode('===',trim($fi));
          if(isset($exp[1])){
            $hasil[$exp[0]] = $exp[1];
          }
        }
      }
    }
  }
  if(isset($hasil[$key])&&$key!==false){
    return $hasil[$key];
  }else{
    return false;
  }
}
/* Set theme option */
function set_theme_option($name=null,$post_content=array()){
  if(isset($name)&&is_array($post_content)){
    $option_file = 'themes/'.$name.'/options.txt';
    $content = array();
    foreach($post_content as $key=>$value){
      $content[] = $key.'==='.$value;
    }
    $content_write = @implode(PHP_EOL,$content);
    $write = @file_write($option_file,$content_write);
    if($write){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

/* Get installation package */
function get_installation_package(){
  global $ldb;
  if(isset($_POST['site_name'])&&isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['cpassword'])&&isset($_POST['email'])&&isset($_POST['name'])&&!empty($_POST['password'])&&!empty($_POST['cpassword'])&&!empty($_POST['site_name'])&&!empty($_POST['username'])&&!empty($_POST['email'])&&!empty($_POST['name'])){
    if($_POST['password']==$_POST['cpassword']){
      $options = array(
        array('key'=>'site_name','value'=>$_POST['site_name']),
        array('key'=>'site_description','value'=>'Free and Simple CMS'),
        array('key'=>'site_keywords','value'=>'Free, Simple, CMS'),
        array('key'=>'robots','value'=>'indexes, followes'),
        array('key'=>'timezone','value'=>'Asia/Jakarta'),
        array('key'=>'theme','value'=>'Dixie'),
        array('key'=>'mobile_theme','value'=>'Dixie'),
        array('key'=>'msie_theme','value'=>'Dixie'),
        array('key'=>'main_page','value'=>'dixie'),
        array('key'=>'post_editor','value'=>'html'),
      );
      $post = array(
        'url'=>'dixie',
        'title'=>'Welcome to Dixie CMS',
        'content'=>'<p>Hi '.$_POST['name'].', Dixie is finally installed.<br />Thank you for using Dixie as your CMS.</p>
<p>This CMS is powered by <a href="http://black-apple.biz/" target="_blank">Black Apple Inc.</a> and the free one. You can try Dixie by downloading and installing into your own blog or website. But typically Dixie is for website.</p>
<p>Dixie is portable as it is, you can move this (the whole files inside the directory) to another directory or folder, without committing any setup.</p>
<p>Dixie also is using CK-Editor as post-content editor, but of cause you can remove this option, as well.<br />Always check update version of Dixie in admin page.</p>
<p>Please <a href="'.WWW.'admin/login/">Login</a> once Dixie was installed.<br />I hope you enjoy this CMS. :)</p>
<p><br />--<a href="http://n8ro.hol.es/">Luthfie</a></p>',
        'datetime'=>date('y-m-d H:i:s'),
        'author'=>$_POST['username'], 
        'type'=>'page',
        'status'=>'publish',
        'access'=>'public',
        'template'=>'standard',
        'picture'=>'',
        /* article & page */
        'description'=>'Free and Simple CMS',
        'keywords'=>'Free, Simple, CMS',
        /* training */
        'trainer'=>'',
        'training_date'=>'',
        /* schedule */
        'expires'=>'',
        'note'=>'',
        /* product */
        'price'=>'',
        'stock'=>'',
        'barcode'=>'',
        'account'=>'',
        /* event */
        'place'=>'',
        'host'=>'',
        'start'=>'',
        'end'=>'',
      );
      $user = array(
        'username'=>$_POST['username'],
        'password'=>$_POST['password'],
        'email'=>$_POST['email'],
        'name'=>$_POST['name'],
        'privilege'=>'admin',
      );
      $menu = array(
        'name'=>'Home',
        'slug'=>'./',
        'order'=>'1',
        'type'=>'top',
      );
      if(ldb()){
        foreach($options as $option){
          $ldb->insert('options',$option);
        }
        $ldb->insert('posts',$post);
        $ldb->insert('users',$user);
        $ldb->insert('menu',$menu);
        header('location: '.WWW.'?_success=installation');
        exit;
      }else{
        $error = 'Our mistake: Cannot connect into database';
        return $error;
      }
    }else{
      $error = 'Confirm password is not match';
      return $error;
    }
  }
}
