<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
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
    /* create new optional point */
    if(isset($options['post_editor'])){
      $options['post_editor'] = isset($_COOKIE['dixie_post_editor'])?$_COOKIE['dixie_post_editor']:$options['post_editor'];
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

/* Set posts privilege */
function set_posts_privilege(){
  global $posts;
  $hasil = array();
  //$userdata = get_user_data(get_active_user());
  $priv = $_SESSION['dixie_privilege'];
  if($priv=='admin'||$priv=='master'||$priv=='editor'){
    $hasil = $posts;
  }elseif(is_array($posts)){
    foreach($posts as $id=>$post){
      if(get_active_user()==$post['author']&&$priv=='author'){
        $hasil[$id] = $post;
      }
    }
  }
  $posts = $hasil;
  return true;
}

/* Get site info */
function get_site_info($index=null,$print=true){
  global $options;
  if(is_array($options)&&isset($options[$index])){
    if($print){
      return print($options[$index]);
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
function get_menu_print($type='top',$print=true){
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
    if($type=='top'){
      $content = plugin_run('nav',$content);
    }else{
      $content = plugin_run('menu',$content);
    }
    if($print){
      print($content);
    }else{
      return $content;
    }
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
function get_sidebar_print($print=true){
  /* Global $sidebar */
  global $sidebar;
  if(get_sidebar('order')){
    $content = '';
    foreach($sidebar as $bar){
      if($bar['type']=='menu'){
        $content .= '<div class="sidebar-menu">';
        $content .= '<div class="sidebar-menu-title">'.((!empty($bar['title']))?$bar['title']:'Menu').'</div>';
        $content .= '<div class="sidebar-menu-content">';
        $content .= get_menu_print('sidebar',false);
        $content .= '</div></div>';
      }elseif($bar['type']=='recent'){
        $option = json_decode($bar['option'],true);
        $posts = get_posts('aid','type='.$option['post_type'].'&status=publish');
        $content .= '<div class="sidebar-recent">';
        $content .= '<div class="sidebar-recent-title">'.((!empty($bar['title']))?$bar['title']:'Recent '.ucwords($option['post_type'])).'</div>';
        $content .= '<div class="sidebar-recent-content">';
        $ro=0;
        foreach(array_reverse($posts) as $post){
          if($post['access']=='public'){
            $ro++;
            $content .= '<a href="'.WWW.$post['url'].'.html" title="'.htmlspecialchars($post['title']).'"><div class="sidebar-recent-list">'.$post['title'].'</div></a>';
            if($ro==$option['post_max']){break;}
          }
        }
        $content .= '</div></div>';
      }elseif($bar['type']=='text'){
        $content .= '<div class="sidebar-text">';
        $content .= '<div class="sidebar-text-title">'.((!empty($bar['title']))?$bar['title']:'').'</div>';
        $content .= '<div class="sidebar-text-content">';
        $content .= $bar['content'];
        $content .= '</div></div>';
      }elseif($bar['type']=='meta'){
        $content .= '<div class="sidebar-meta">';
        $content .= '<div class="sidebar-meta-title">'.((!empty($bar['title']))?$bar['title']:'Meta').'</div>';
        $content .= '<div class="sidebar-meta-content">';
        $content .= '<a href="'.WWW.'admin/?ref=meta" title="'.((is_login())?'Admin Page':'Login to Admin').'"><div class="sidebar-meta-list">'.((is_login())?'Admin':'Login').'</div></a>';
        $content .= '<a href="'.WWW.'feed.xml" title="Really Simple Syndicate"><div class="sidebar-meta-list">RSS</div></a>';
        $content .= '<a href="http://dixie.hol.es/?ref='.urlencode(WWW).'" title="Dixie" target="_blank" rel="follow"><div class="sidebar-meta-list">Dixie</div></a>';
        $content .= '<a href="http://n8ro.hol.es/?ref='.urlencode(WWW).'" title="Luthfie a.k.a. 9r3i" target="_blank" rel="follow"><div class="sidebar-meta-list">Luthfie</div></a>';
        $content .= '</div></div>';
      }elseif($bar['type']=='category'){
        $content .= '<div class="sidebar-category">';
        $content .= '<div class="sidebar-category-title">'.((!empty($bar['title']))?$bar['title']:'Category').'</div>';
        $content .= '<div class="sidebar-category-content">';
        $cats = get_category();
        foreach($cats as $cat){
          $content .= '<a href="'.WWW.$cat['slug'].'" title="'.$cat['name'].'"><div class="sidebar-category-list">'.$cat['name'].'</div></a>';
        }
        $content .= '</div></div>';
      }elseif($bar['type']=='search'){
        $content .= '<div class="sidebar-search">';
        $content .= '<div class="sidebar-search-title">'.((!empty($bar['title']))?$bar['title']:'Search').'</div>';
        $content .= '<div class="sidebar-search-content"><form action="'.WWW.'search" method="get">';
        $content .= '<div class="sidebar-search-left"><input class="sidebar-search-input" name="keywords" type="text" id="sidear_search" placeholder="Search..." /></div>';
        $content .= '<div class="sidebar-search-right"><input class="sidebar-search-submit" value="Search" type="submit" id="sidear_search_submit" /></div>';
        $content .= '</form></div></div>';
      }
    }
    $content = plugin_run('sidebar',$content);
    if($print){
      print($content);
    }else{
      return $content;
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
  $content .= '<link href="'.WWW.((isset($post['url']))?$post['url'].'.html':'').'" type="text/html" rel="canonical" />';
  $content .= '<link href="'.WWW.((isset($post['url']))?$post['url'].'.html':'').'" type="text/html" rel="pingback" />';
  $content .= '<link href="'.WWW.'feed.xml" title="RSS Feed" type="application/rss+xml" rel="alternate" />';
  $content .= '';
  $content = plugin_run('header',$content);
  if($print){
    return print($content);
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
  }elseif($template=='search'){
    $title = 'Search &#8213; '.$options['site_name'];
  }else{
    $title = '404 Not Found &#8213; '.$options['site_name'];
  }
  $title = plugin_run('title',$title);
  if($print){
    return print($title);
  }else{
    return $title;
  }
}

/* Get footer body-tag */
function get_footer($print=true){
  $content = '<div id="copyright">Copyright &copy; '.date('Y').' &middot; <a href="'.WWW.'" title="'.get_site_info('site_name',false).'" target="_blank">'.get_site_info('site_name',false).'</a> &middot; All Right Reserved</div>';
  $content = plugin_run('footer',$content);
  if($print){
    return print($content);
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
  $category = get_category();
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
  }elseif(defined('P')&&isset($posts[str_replace('.html','',P)])){
    $post = $posts[str_replace('.html','',P)];
    if(is_login()){
      $template = 'post';
    }elseif($post['access']=='public'&&$post['status']=='publish'){
      $template = 'post';
    }else{
      $template = '404';
    }
  }elseif(defined('P')&&array_key_exists(P,$category)){
    $template = 'type';
  }elseif(defined('P')&&P=='search'){
    $template = 'search';
  }elseif(defined('P')&&P=='admin'&&is_login()){
    $template = 'admin';
  }else{
    $template = '404';
  }
  return $template;
}

/* Get category */
function get_category($post_id=null,$safer=false){
  global $ldb;
  ldb();
  $cat_table = 'category';
  $category = $ldb->select($cat_table);
  $hasil = array();
  if(is_array($category)){
    foreach($category as $cat){
      if(isset($post_id)&&$post_id==$cat['post_id']){
        $hasil['name'] = $cat['name'];
        $hasil['slug'] = $cat['slug'];
      }elseif($safer){
        $hasil[$cat['post_id']] = $cat;
      }elseif($cat['slug']!=='uncategorized'){
        $hasil[$cat['slug']]['name'] = $cat['name'];
        $hasil[$cat['slug']]['slug'] = $cat['slug'];
        $hasil[$cat['slug']]['post_id'][] = $cat['post_id'];
      }
    }
  }
  return $hasil;
}
/* Set category */
function set_category($post_id=null,$cat_name=null){
  if(isset($post_id,$cat_name)){
    global $ldb;
    ldb();
    $cat_table = 'category';
    $delete = $ldb->delete($cat_table,'post_id='.$post_id);
    $slug = create_slug($cat_name);
    $data = array(
      'name'=>$cat_name,
      'slug'=>$slug,
      'post_id'=>$post_id
    );
    return $ldb->insert($cat_table,$data);
  }else{
    return false;
  }
}

/* Get search result */
function get_search_result(){
  $hasil = array();
  if(isset($_GET['keywords'])){
    global $ldb;
    ldb();
    $keywords = @explode(',',$_GET['keywords']);
    foreach($keywords as $keyword){
      $content = $ldb->search('posts','content='.$keyword,true);
      if(is_array($content)){foreach($content as $con){
        if($con['status']=='publish'){
          $hasil[$con['aid']]['url'] = WWW.$con['url'].'.html';
          $hasil[$con['aid']]['title'] = $con['title'];
          $hasil[$con['aid']]['content'] = $con['content'];
        }
      }}
      $title = $ldb->search('posts','title='.$keyword,true);
      if(is_array($title)){foreach($title as $tit){
        if($tit['status']=='publish'){
          $hasil[$tit['aid']]['url'] = WWW.$tit['url'].'.html';
          $hasil[$tit['aid']]['title'] = $tit['title'];
          $hasil[$tit['aid']]['content'] = $tit['title'];
        }
      }}
    }
    krsort($hasil);
  }
  return $hasil;
}

/* Generate posts information for index template */
function generate_index_posts($per=false,$next=0,$ppp=10){
  global $posts,$options;
  $post_types = array('post','page','article','training','schedule','product','event');
  $categories = get_category();
  $type = (get_index()=='type')?P:'post';
  if(defined('P')&&P=='index.php'){
    if(in_array($options['main_page'],$post_types)){
      $type = $options['main_page'];
    }elseif(in_array($options['main_page'],$categories)){
      $type = $options['main_page'];
    }
  }
  $category = (array_key_exists($type,$categories))?$categories[$type]['post_id']:array();
  if(is_array($posts)&&count($posts)>0){
    $hasil = '<div class="dixie-posts">';
    $s_hasil = '';
    $tposts = ($type=='training')?generate_training_post($posts):array_reverse($posts);
    $rp = 0;
    foreach($tposts as $post){
      $generate = generate_index_single_post($post,$type,39,$category);
      if($per&&$generate!==''){
        $rp++;
        if($rp>$next){$s_hasil .= $generate;}
        if($rp==($ppp+$next)){break;}
      }else{$s_hasil .= $generate;}
    }
    if($s_hasil!==''){
      $hasil .= $s_hasil;
      if($per&&$rp==($ppp+$next)){
        $hasil .= '<div class="post-navigator"><a href="'.WWW.((get_index()=='index')?'':$type).'?next='.($next+$ppp).'"><div class="post-navigator-next">Next</div></a></div>';
      }
    }else{
      $an = array('article','event');
      $pre = (in_array($type,$an))?'an':'a';
      if(array_key_exists($type,$categories)){
        $hasil .= '<h2>Doesn\'t have a post yet in category <a href="'.WWW.$type.'">'.ucwords($type).'</a></h2>';
      }elseif($per&&$next>0){
        $hasil .= '<h2>No more <a href="'.WWW.$type.'">'.ucwords($type).'</a></h2>';
      }else{
        $hasil .= '<h2>Doesn\'t have '.$pre.' <a href="'.WWW.$type.'">'.ucwords($type).'</a> yet</h2>';
      }
    }
    $hasil .= '</div>';
    return $hasil;
  }else{
    return false;
  }
}
/* Generate training post only */
function generate_training_post($data=array()){
  $hasil = array();
  if(is_array($data)){
    $times = array();
    $newd = array();
    foreach($data as $id=>$post){
      $tdate = generate_training_date($post['training_date']);
      if($tdate>time()){
        $times[$id] = $tdate;
      }
    }
    asort($times);
    foreach($times as $id=>$time){
      $hasil[$id] = $data[$id];
    }
  }
  return $hasil;
}
/* Generate per single post */
function generate_index_single_post($post,$type='post',$word=39,$category){
  $types = array('post','page','article','training','schedule','product','event');
  $split = explode(' ',strip_tags($post['content'],'<p><br>'),($word+1));
  $split_pop = (count($split)>=$word)?array_pop($split):'';
  $con = implode(' ',$split);
  $dots = (count($split)>=$word)?'...':'';
  $rows = explode(PHP_EOL,$con,6);
  $rows_pop = (count($rows)>=5)?array_pop($rows):'';
  $dots = (count($rows)>=5)?'...':$dots;
  $recon = implode(PHP_EOL,$rows);
  $content = $recon.'<span></span>'.$dots;
  $time = generate_training_date($post['training_date']);
  $hasil ='';
    $hasil .= '<div class="each-post data-'.$type.'" data-type="'.$type.'">';
    $hasil .= '<div class="each-post-title"><a href="'.WWW.$post['url'].'.html"><h3>'.$post['title'].'</h3></a></div>';
    if($post['type']=='training'){
      $hasil .= '<div class="each-post-detail"><div class="each-post-detail-training-date">'.date('F jS, Y',$time).'</div><div class="each-post-detail-trainer">'.$post['trainer'].'</div></div>';
    }elseif($post['type']=='post'||$post['type']=='article'){
      $author = get_user_data($post['author']);
      $hasil .= '<div class="each-post-detail"><div class="each-post-detail-time">'.date('F jS, Y',$post['time']).'</div>&#8213;<div class="each-post-detail-author">'.$author['name'].'</div></div>';
    }elseif($post['type']=='event'){
      $hasil .= '<div class="each-post-detail">
        <div class="each-post-detail-push">
          <div class="each-post-detail-start">'.date('F jS, Y',strtotime($post['start'])).'</div>&#8213;<div class="each-post-detail-end">'.date('F jS, Y',strtotime($post['end'])).'</div>
        </div>
        <div class="each-post-detail-push">
          <div class="each-post-detail-host">'.$post['host'].'</div>&#8213;<div class="each-post-detail-place">'.$post['place'].'</div>
        </div>
      </div>';
    }elseif($post['type']=='product'){
      $hasil .= '<div class="each-post-detail">
        <div class="each-post-detail-push">
          <div class="each-post-detail-price">'.$post['price'].'</div>&#8213;<div class="each-post-detail-stock">Stock: '.$post['stock'].'</div>
        </div>
      </div>';
    }elseif($post['type']=='schedule'){
      $hasil .= '<div class="each-post-detail">
        <div class="each-post-detail-push">
          <div class="each-post-detail-note">'.$post['note'].'</div>&#8213;<div class="each-post-detail-expires">Expires: '.date('F jS, Y',strtotime($post['expires'])).'</div>
        </div>
      </div>';
    }
    $hasil .= '<div class="each-post-content">'.$content.(($dots=='...')?' <span></span><a style="white-space:pre;" href="'.WWW.$post['url'].'.html">[Read More...]</a>':'').'</p></div>';
    $hasil .= '</div>';
  if($post['type']==$type&&$post['access']=='public'&&$post['status']=='publish'){
    $hasil = $hasil;
  }elseif($type=='training'&&$time<time()){
    $hasil= '';
  }elseif(in_array($post['aid'],$category)&&$post['access']=='public'&&$post['status']=='publish'){
    $hasil = $hasil;
  }else{
    $hasil = '';
  }
  //$hasil = plugin_run('content',$hasil);
  return $hasil;
}

function generate_training_date($date,$until=false){
  $split = explode('-',$date);
  if(is_array($split)){
    if($until&&isset($split[1])){
      $result = @strtotime($split[1]);
      return $result?$result:time();
    }else{
      $result = @strtotime($split[0]);
      return $result?$result:time();
    }
  }else{
    return time();
  }
}

/* Admin get header */
function admin_get_header($print=true){
  $default = '<meta content="text/html; charset=utf-8" http-equiv="content-type" /><meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" /><meta content="width=device-width, initial-scale=1" name="viewport" /><meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" /><meta content="Dixie" name="generator" /><meta content="'.DIXIE_VERSION.'" name="version" />';
  $content = plugin_run('admin-header','');
  $hasil = $default.$content;
  if($print){
    print($hasil);
  }else{
    return $hasil;
  }
}
/* Admin get footer */
function admin_get_footer($print=true){
  $content = '';
  $hasil = plugin_run('admin-footer',$content);
  if($print){
    print($hasil);
  }else{
    return $hasil;
  }
}

/* Admin get registered data */
function admin_get_data(){
  global $adminReg,$pages,$no_pages,$editor_pages,$priv,$pluger,$icons;
  admin_default_registry();
  ksort($adminReg);
  $sdp = array(32=>'master',16=>'admin',8=>'editor',4=>'author',2=>'member');
  foreach($adminReg as $pre=>$reg){
    $pages[$reg['slug']] = $reg['title'];
    $icons[$reg['slug']] = $reg['icon'];
    if($reg['regside']===false){
      $no_pages[] = $reg['slug'];
    }
    if($reg['editor']===true){
      $editor_pages[] = $reg['slug'];
    }
    if(isset($sdp[$reg['privilege']])){
      foreach($sdp as $id=>$val){
        if($reg['privilege']<=$id){
          $priv[$val][] = $reg['slug'];
        }
      }
    }
    if($reg['type']=='plugin'){
      $pluger[$reg['slug']] = $reg['name'];
    }
  }
  return true;
}



/* Admin registry function
 * To register a plugin menu and page into admin page (backend)
 * @name: string -> Plugin name or actually the slug uri of the plugin directory
 * @slug: string -> The file slug to the content file
 * @title: string -> The title
 * @priority: integer -> default 10
 * @privilege: integer -> according to sdp -> default 16 as admin
 * @regside: bool -> register to sidebar -> default true
 * @editor: bool -> register to editor pages -> default false
 * 
 */
function admin_registry($name,$slug,$title,$priority=10,$privilege=16,$regside=true,$editor=false,$icon=''){
  global $adminReg;
  $sdp = array(32=>'master',16=>'admin',8=>'editor',4=>'author',2=>'member');
  if(isset($name,$slug,$title,$sdp[$privilege])){
    $data = array();
    $data['name'] = $name;
    $data['slug'] = $slug;
    $data['title'] = $title;
    $data['priority'] = $priority;
    $data['privilege'] = $privilege;
    $data['regside'] = $regside;
    $data['editor'] = $editor;
    $data['type'] = 'plugin';
    $data['icon'] = $icon;
    $adminReg[$priority.'_'.$name] = $data;
    return true;
  }else{
    return false;
  }
}

/* Admin default registry */
function admin_default_registry(){
  global $adminReg;
  $default = array(
    '12_admin'=>array(
      'name'=>'admin',
      'slug'=>'new-post',
      'title'=>__locale('New Post'),
      'priority'=>'12',
      'privilege'=>'4',
      'regside'=>true,
      'editor'=>true,
      'type'=>'default',
      'icon'=>'fa-edit',
    ),
    '13_admin'=>array(
      'name'=>'admin',
      'slug'=>'posts',
      'title'=>__locale('All Posts'),
      'priority'=>'13',
      'privilege'=>'4',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-file-o',
    ),
    '16_admin'=>array(
      'name'=>'admin',
      'slug'=>'files',
      'title'=>__locale('Files'),
      'priority'=>'16',
      'privilege'=>'8',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-folder-o',
    ),
    '19_admin'=>array(
      'name'=>'admin',
      'slug'=>'menu',
      'title'=>__locale('Menu'),
      'priority'=>'19',
      'privilege'=>'8',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-list-alt',
    ),
    '22_admin'=>array(
      'name'=>'admin',
      'slug'=>'sidebar',
      'title'=>__locale('Sidebar'),
      'priority'=>'22',
      'privilege'=>'8',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-columns',
    ),
    '25_admin'=>array(
      'name'=>'admin',
      'slug'=>'themes',
      'title'=>__locale('Themes'),
      'priority'=>'25',
      'privilege'=>'16',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-shield',
    ),
    '28_admin'=>array(
      'name'=>'admin',
      'slug'=>'plugins',
      'title'=>__locale('Plugins'),
      'priority'=>'28',
      'privilege'=>'16',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-thumb-tack',
    ),
    '31_admin'=>array(
      'name'=>'admin',
      'slug'=>'users',
      'title'=>__locale('Users'),
      'priority'=>'31',
      'privilege'=>'16',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-user',
    ),
    '34_admin'=>array(
      'name'=>'admin',
      'slug'=>'account',
      'title'=>__locale('Account'),
      'priority'=>'34',
      'privilege'=>'2',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-lock',
    ),
    '37_admin'=>array(
      'name'=>'admin',
      'slug'=>'settings',
      'title'=>__locale('Settings'),
      'priority'=>'37',
      'privilege'=>'16',
      'regside'=>true,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'fa-gear',
    ),
    '40_admin'=>array(
      'name'=>'admin',
      'slug'=>'edit-post',
      'title'=>__locale('Edit Post'),
      'priority'=>'40',
      'privilege'=>'4',
      'regside'=>false,
      'editor'=>true,
      'type'=>'default',
      'icon'=>'',
    ),
    '43_admin'=>array(
      'name'=>'admin',
      'slug'=>'edit-theme',
      'title'=>__locale('Edit Theme'),
      'priority'=>'43',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '44_admin'=>array(
      'name'=>'admin',
      'slug'=>'edit-plugin',
      'title'=>__locale('Edit Plugin'),
      'priority'=>'44',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '46_admin'=>array(
      'name'=>'admin',
      'slug'=>'new-user',
      'title'=>__locale('Add New User'),
      'priority'=>'46',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '49_admin'=>array(
      'name'=>'admin',
      'slug'=>'edit-user',
      'title'=>__locale('Edit User'),
      'priority'=>'49',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '52_admin'=>array(
      'name'=>'admin',
      'slug'=>'new-menu',
      'title'=>__locale('New Menu'),
      'priority'=>'52',
      'privilege'=>'8',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '55_admin'=>array(
      'name'=>'admin',
      'slug'=>'edit-menu',
      'title'=>__locale('Edit Menu'),
      'priority'=>'55',
      'privilege'=>'8',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '58_admin'=>array(
      'name'=>'admin',
      'slug'=>'new-sidebar',
      'title'=>__locale('New Sidebar'),
      'priority'=>'58',
      'privilege'=>'8',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '61_admin'=>array(
      'name'=>'admin',
      'slug'=>'edit-sidebar',
      'title'=>__locale('Edit Sidebar'),
      'priority'=>'61',
      'privilege'=>'8',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '64_admin'=>array(
      'name'=>'admin',
      'slug'=>'plugin-option',
      'title'=>__locale('Plugin Option'),
      'priority'=>'64',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '67_admin'=>array(
      'name'=>'admin',
      'slug'=>'theme-option',
      'title'=>__locale('Theme Option'),
      'priority'=>'67',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '70_admin'=>array(
      'name'=>'admin',
      'slug'=>'new-plugin',
      'title'=>__locale('Add New Plugin'),
      'priority'=>'70',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '73_admin'=>array(
      'name'=>'admin',
      'slug'=>'new-theme',
      'title'=>__locale('Add New Theme'),
      'priority'=>'73',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '76_admin'=>array(
      'name'=>'admin',
      'slug'=>'confirmation',
      'title'=>__locale('Confirmation'),
      'priority'=>'76',
      'privilege'=>'4',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '79_admin'=>array(
      'name'=>'admin',
      'slug'=>'upload',
      'title'=>__locale('Upload New File'),
      'priority'=>'79',
      'privilege'=>'8',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '82_admin'=>array(
      'name'=>'admin',
      'slug'=>'update',
      'title'=>__locale('Update'),
      'priority'=>'82',
      'privilege'=>'16',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    ),
    '85_admin'=>array(
      'name'=>'admin',
      'slug'=>'change-picture',
      'title'=>__locale('Change Picture'),
      'priority'=>'85',
      'privilege'=>'4',
      'regside'=>false,
      'editor'=>false,
      'type'=>'default',
      'icon'=>'',
    )
  );
  $adminReg = array_merge((array)$adminReg,(array)$default);
  return true;
}


/* Plugins registry store at global $plugReg */
function plugin_registry($p_name,$p_index=array(),$priority=10,$type='content',$arg=array()){
  global $plugReg;
  if(isset($p_name)&&is_array($p_index)){
    $plugReg[$priority.'_'.$p_name]['name'] = $p_name;
    $plugReg[$priority.'_'.$p_name]['index'] = $p_index;
    $plugReg[$priority.'_'.$p_name]['priority'] = $priority;
    $plugReg[$priority.'_'.$p_name]['type'] = $type;
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
  /* few contents are dismissed, i'm afraid if this will be bad.
   * but i still merge the additional argument below
   */
  if(is_array($plugReg)){
    ksort($plugReg);
    foreach($plugReg as $func){
      $first_arg = array($hasil);
      $arg = array_merge((array)$first_arg,(array)$func['arg']);
      if(in_array(get_index(),$func['index'])&&$type==$func['type']){
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
  $plug = new Plugins();
  $plugins = $plug->plugins;
  if(isset($name,$plugins[$name],$plugins[$name]['about'])){
    return $plugins[$name]['about'];
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
/* Get theme about */
function get_theme_about($name=null){
  $theme = new Themes();
  $themes = $theme->themes;
  if(isset($name,$themes[$name],$themes[$name]['about'])){
    return $themes[$name]['about'];
  }else{
    return false;
  }
}

/* Get installation package */
function get_temporary_post(){
  global $ldb;
  ldb();
  $select = $ldb->select('posts','status=temp');
  if(is_array($select)){
    foreach($select as $sel){
      if($sel['author']==get_active_user()){
        $ldb->delete('posts','aid='.$sel['aid']);
      }
    }
  }
  $slug = create_slug('temp-'.microtime(true));
      $post = array(
        'url'=>$slug,
        'title'=>'',
        'content'=>'',
        'datetime'=>date('y-m-d H:i:s'),
        'author'=>get_active_user(),
        'type'=>'post',
        'status'=>'temp',
        'access'=>'public',
        'template'=>'standard',
        'picture'=>'',
        'description'=>'',
        'keywords'=>'',
        'trainer'=>'',
        'training_date'=>'',
        'expires'=>'',
        'note'=>'',
        'price'=>'',
        'stock'=>'',
        'barcode'=>'',
        'account'=>'',
        'place'=>'',
        'host'=>'',
        'start'=>'',
        'end'=>'',
      );
  $ldb->insert('posts',$post);
  $select = $ldb->select('posts','url='.$slug);
  if(isset($select[0])){
    return $select[0];
  }else{
    return false;
  }
}


/* Get installation package */
function get_installation_code(){
  $code = 'LAY-ON-DIXIE';
  if(isset($_SESSION['dixie_installation'])&&$_SESSION['dixie_installation']==$code){
    return true;
  }elseif(isset($_POST['installation_code'])&&$_POST['installation_code']==$code){
    $_SESSION['dixie_installation'] = $_POST['installation_code'];
    return true;
  }else{
    return false;
  }
}

/* Get installation package */
function get_installation_package(){
  global $ldb;
  if(isset($_POST['site_name'])&&isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['cpassword'])&&isset($_POST['email'])&&isset($_POST['name'])&&!empty($_POST['password'])&&!empty($_POST['cpassword'])&&!empty($_POST['site_name'])&&!empty($_POST['username'])&&!empty($_POST['email'])&&!empty($_POST['name'])){
    if($_POST['password']==$_POST['cpassword']){
      $title = 'Welcome to Dixie CMS';
      $slug = create_slug($title);
      $options = array(
        array('key'=>'site_name','value'=>$_POST['site_name']),
        array('key'=>'site_description','value'=>'Site is generated by Dixie CMS'),
        array('key'=>'site_keywords','value'=>'Free, Simple, CMS'),
        array('key'=>'robots','value'=>'index, follow'),
        array('key'=>'timezone','value'=>'Asia/Jakarta'),
        array('key'=>'theme','value'=>'Dixie3'),
        array('key'=>'mobile_theme','value'=>'Dixie3'),
        array('key'=>'msie_theme','value'=>'Dixie3'),
        array('key'=>'main_page','value'=>$slug),
        array('key'=>'post_editor','value'=>'html'),
      );
      $post = array(
        'url'=>$slug,
        'title'=>$title,
        'content'=>'<p>Hi '.$_POST['name'].',</p>
<p>Dixie v'.DIXIE_VERSION.' is finally installed.<br />Thank you for using Dixie as your CMS.</p>
<p>This Content Management System (CMS) is the free one and created by <a href="http://n8ro.hol.es/?id=profile" title="Luthfie a.k.a. 9r3i" target="_blank">Luthfie</a>. You can try Dixie by downloading then install her into your own blog or website.</p>
<p>Dixie is portable CMS, you can move these whole files inside the directory if Dixie to another directory or folder, without committing any setup.</p>
<p>For more info about what features are added to, please read the change log inside the backend (the admin page).</p>
<p>Please <a href="'.WWW.'admin/login/">Login</a> once Dixie was installed.<br />I hope you enjoy this CMS. :)</p>
<p><br />--<a href="http://n8ro.hol.es/?id=profile">Luthfie</a> (Developer)</p>',
        'datetime'=>date('y-m-d H:i:s'),
        'author'=>$_POST['username'], 
        'type'=>'page',
        'status'=>'publish',
        'access'=>'public',
        'template'=>'standard',
        'picture'=>'',
        /* article & page */
        'description'=>'Site is generated by Dixie - Free and Simple CMS',
        'keywords'=>'Free, Simple, CMS',
        /* training */
        'trainer'=>'',
        'training_date'=>'',
        /* schedule */
        'expires'=>'',
        'note'=>'',
        /* product + training + event */
        'price'=>'',
        /* training + event */
        'place'=>'',
        /* product */
        'stock'=>'',
        'barcode'=>'',
        'account'=>'',
        /* event */
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
      $sidebar = array(
        'type'=>'text',
        'title'=>'Dixie',
        'order'=>'22',
        'content'=>'<img src="'.PUBDIR.'images/dixie-black.png" width="100%" style="width:100%;border:none;" title="Dixie CMS for '.$_POST['site_name'].'"  />',
        'option'=>'', // json
      );
      $sidebar2 = array(
        'type'=>'search',
        'title'=>'',
        'order'=>'21',
        'content'=>'',
        'option'=>'', // json
      );
      $sidebar3 = array(
        'type'=>'meta',
        'title'=>'',
        'order'=>'23',
        'content'=>'',
        'option'=>'', // json
      );
      if(ldb()){
        foreach($options as $option){
          $ldb->insert('options',$option);
        }
        $ldb->insert('posts',$post);
        $ldb->insert('users',$user);
        $ldb->insert('menu',$menu);
        $ldb->insert('sidebar',$sidebar);
        $ldb->insert('sidebar',$sidebar2);
        $ldb->insert('sidebar',$sidebar3);
        header('location: '.WWW.$slug.'.html?_success=installation');
        exit;
      }else{
        $error = 'Error: Cannot connect into database';
        return $error;
      }
    }else{
      $error = 'Confirm password doesn\'t match';
      return $error;
    }
  }
}
