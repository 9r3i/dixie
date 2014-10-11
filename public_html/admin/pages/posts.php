<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Global posts */
global $posts;

/* Configure type, status, access, template and bulk_actions */
$type = array('post','page','article','training','schedule','product','event');
$status = array('publish','draft','trash');
$access = array('public','private');
$template = array('standard');
$bulk_actions = array('trash','delete','publish','draft');


/* Configure query filter get */
$query = '';
$query .= (isset($_GET['filter-status'])&&in_array($_GET['filter-status'],$status))?'status='.$_GET['filter-status'].'&':'status=publish&';
$query .= (isset($_GET['filter-type'])&&in_array($_GET['filter-type'],$type))?'type='.$_GET['filter-type'].'&':'';
$query = substr($query,0,-1);

/* Get posts data */
$posts = get_posts('url',$query);

/* Set posts as privilege */
set_posts_privilege();

/* Configure get next */
$next = (isset($_GET['next']))?$_GET['next']:0;

/* HTML View */
?>
<div class="all-posts">
  <form action="<?php print(WWW); ?>admin/a?data=bulk-action" method="post" style="padding:0px;margin:0px;">
    <div class="config-body">
      <div class="config-form" style="width:100%;padding:0px;margin:0px 5px;">
        <select style="width:120px;" id="filter_status" name="filter-status" class="form-select" onchange="filter_assign()">
          <option value="">--Status Filter--</option>
          <?php foreach($status as $stat){echo '<option value="'.$stat.'"'.((isset($_GET['filter-status'])&&$_GET['filter-status']==$stat)?' selected="true"':'').'>'.ucfirst($stat).'</option>';} ?>
        </select>
        <select onchange="filter_assign()" style="width:113px;" id="filter_type" name="filter-type" class="form-select">
          <option value="all">--Type Filter--</option>
          <?php foreach($type as $stat){echo '<option value="'.$stat.'"'.((isset($_GET['filter-type'])&&$_GET['filter-type']==$stat)?' selected="true"':'').'>'.ucfirst($stat).'</option>';} ?>
        </select>
        <input type="hidden" name="query" value="?filter-status=<?php print($_GET['filter-status']); ?>&filter-type=<?php print($_GET['filter-type']); ?>" />
        <select style="width:115px;" name="action" class="form-select">
          <option value="">--Bulk Action--</option>
          <?php foreach($bulk_actions as $action){echo '<option value="'.$action.'">'.ucwords($action).'</option>';} ?>
        </select>
        <input type="submit" value="Do Action" class="form-submit fs15" />
        <a href="#" id="check_all" onclick="check_all('input-checkbox')"><div class="button fs15">Check All</div></a>
      </div>
    </div>
  <?php
  $count=0;
  foreach(array_reverse($posts) as $slug=>$post){
    $count++;
    if($count>$next){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title"><input type="checkbox" name="check[]" value="'.$post['aid'].'" class="input-checkbox" id="check_'.$post['aid'].'" /><label for="check_'.$post['aid'].'">'.$post['title'].'</span></div>';
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
  </form>
</div>
<script type="text/javascript">
function filter_assign(){
  var status = document.getElementById('filter_status').value;
  var type = document.getElementById('filter_type').value;
  status = (status=='')?'publish':status;
  window.location.assign('?filter-status='+status+'&filter-type='+type);
}
</script>