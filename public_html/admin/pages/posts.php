<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global posts */
global $posts;

/* Configure type, status, access and template */
$type = array('post','page','article','training','schedule','product','event');
$status = array('publish','draft','trash');
$access = array('public','private');
$template = array('standard');

/* Configure query filter get */
$query = '';
$query .= (isset($_GET['filter-status'])&&in_array($_GET['filter-status'],$status))?'status='.$_GET['filter-status'].'&':'status=publish&';
$query .= (isset($_GET['filter-type'])&&in_array($_GET['filter-type'],$type))?'type='.$_GET['filter-type'].'&':'';
$query = substr($query,0,-1);

/* Get posts data */
$posts = get_posts('url',$query);

/* Configure get next */
$next = (isset($_GET['next']))?$_GET['next']:0;

/* HTML View */
?>
<div class="config-body"><form action="" method="get">
  <div class="config-form">Status<select name="filter-status" class="form-select">
    <?php foreach($status as $stat){echo '<option value="'.$stat.'"'.((isset($_GET['filter-status'])&&$_GET['filter-status']==$stat)?' selected="true"':'').'>'.ucfirst($stat).'</option>';} ?>
  </select></div>
  <div class="config-form">Type<select name="filter-type" class="form-select">
    <option value="all">-- All --</option>
    <?php foreach($type as $stat){echo '<option value="'.$stat.'"'.((isset($_GET['filter-type'])&&$_GET['filter-type']==$stat)?' selected="true"':'').'>'.ucfirst($stat).'</option>';} ?>
  </select></div>
  <div class="config-form"><input type="submit" value="Filter" class="form-submit" /></div>
</form></div>
<div class="all-posts">
  <?php
  $count=0;
  foreach(array_reverse($posts) as $slug=>$post){
    $count++;
    if($count>$next){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">'.$post['title'].'</div>';
      echo '<div class="post-option">
              <a href="'.WWW.''.$post['url'].'.html" target="_blank" style="color:#37b" title="View the post">'.(($post['status']=='publish')?'View':'Preview').'</a>
              <a href="'.WWW.'admin/edit-post?post_id='.$post['aid'].'" style="color:#3b7" title="Edit the post">Edit</a>
              '.((isset($_GET['filter-status'])&&$_GET['filter-status']=='trash')?'<a href="'.WWW.'admin/confirmation?action=delete-post&post_id='.$post['aid'].'" style="color:#900" title="Delete the post">Delete Permanently</a>':'<a href="'.WWW.'admin/confirmation?action=trash&post_id='.$post['aid'].'" style="color:#900" title="Move the post to trash">Trash</a>').'
          </div>';
      echo '<div class="post-detail">'.ucfirst($post['status']).' '.ucfirst($post['type']).(($post['access']=='private')?' <span style="color:#b73;">(Private)</span>':'').' | Last Updated: '.date('F, jS Y H:i',strtotime($post['datetime'])).' | Created Time: '.date('F, jS Y H:i',$post['time']).'</div>';
      echo '</div>';
      if($count==($next+10)){
        echo '<div class="all-posts-each next-posts"><a href="'.WWW.'admin/posts?next='.($next+10).((isset($_GET['filter-status']))?'&filter-status='.$_GET['filter-status']:'').((isset($_GET['filter-type']))?'&filter-type='.$_GET['filter-type']:'').'" title="Next">Next</a></div>';
        break;
      }
    }
  }
  ?>
</div>
<script type="text/javascript">

</script>