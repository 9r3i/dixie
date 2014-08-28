<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global options and posts */
global $options,$posts;

/* Get site options */
if(!get_options()){
  header('content-type: text/plain;');
  exit('cannot get options information');
}

/* Get all posts */
if(!get_posts('url')){
  header('content-type: text/plain;');
  exit('cannot get posts information');
}

/* Get contents */
$content = '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0">
  <channel>
    <title><![CDATA['.$options['site_name'].' RSS]]></title>
    <link>'.WWW.'feed.xml</link>
    <description><![CDATA['.$options['site_description'].']]></description>
    <language>ID-id</language>
    <pubDate>'.date("r").'</pubDate>';
$next = (isset($_GET['next']))?$_GET['next']:0;
$counter=0; $stop=$next+10;
foreach(array_reverse($posts) as $id=>$post){
  $counter++;
  if($counter>$next){
    $next++;
    $content .= '<item>';
    $content .= '<title><![CDATA['.utf8_decode($post['title']).']]></title>';
    $content .= '<link>'.WWW.$post['url'].'.html</link>';
    $content .= '<description><![CDATA['.utf8_decode(substr($post['content'],0,200))
      .((strlen($post['content'])>200)?'...':'')
	  .((strlen($post['content'])>200)?' <a href="'.WWW.$post['url'].'.html" rel="detail" title="Baca selanjutnya...">Baca selanjutnya...</a>':'')
	  .']]></description>';
    $content .= '<pubDate>'.date('r',$post['time']).'</pubDate>';
    $content .= '</item>';
	if($next==$stop){break;}
  }
}
$content .= '
  </channel>
</rss>';

/* Set header content */
header('content-type: application/rss+xml;');
echo $content;