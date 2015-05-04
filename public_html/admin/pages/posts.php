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

/* count the post rows */
$rows = count($posts);
$locale_rows = __locale('rows');

/* Set posts as privilege */
set_posts_privilege();

/* Configure get next and limit */
$next = (isset($_GET['next']))?$_GET['next']:0;
$limit = isset($_GET['limit'])?$_GET['limit']:10;

/* HTML View */
?>
<div class="all-posts">
  <form action="<?php print(WWW); ?>admin/a?data=bulk-action" method="post" style="padding:0px;margin:0px;">
    <div class="config-body">
      <div class="config-form" style="width:100%;padding:0px;margin:0px 5px;">
        <select id="filter_status" name="filter-status" class="form-select" onchange="filter_assign()">
          <option value="">--<?php __locale('Status Filter',true); ?>--</option>
          <?php foreach($status as $stat){echo '<option value="'.$stat.'"'.((isset($_GET['filter-status'])&&$_GET['filter-status']==$stat)?' selected="true"':'').'>'.ucfirst(__locale($stat)).'</option>';} ?>
        </select>
        <select onchange="filter_assign()" id="filter_type" name="filter-type" class="form-select">
          <option value="all">--<?php __locale('Type Filter',true); ?>--</option>
          <?php foreach($type as $stat){echo '<option value="'.$stat.'"'.((isset($_GET['filter-type'])&&$_GET['filter-type']==$stat)?' selected="true"':'').'>'.__locale(ucfirst($stat)).'</option>';} ?>
        </select>
        <select onchange="filter_assign()" id="limit" name="action" class="form-select" style="width:60px;">
          <?php foreach(array(10,20,50,100,200,500,1000) as $lim){echo '<option value="'.$lim.'" '.($limit==$lim?' selected="selected"':'').'>'.$lim.'</option>';} ?>
        </select>
        <input type="hidden" name="query" value="?filter-status=<?php print($_GET['filter-status']); ?>&filter-type=<?php print($_GET['filter-type']); ?>" />
        <select name="action" class="form-select">
          <option value="">--<?php __locale('Bulk Action',true); ?>--</option>
          <?php foreach($bulk_actions as $action){echo '<option value="'.$action.'">'.__locale(ucwords($action)).'</option>';} ?>
        </select>
        <input type="submit" value="<?php __locale('Do Action',true); ?>" class="form-submit fs15" />
        <a href="#" id="check_all" onclick="check_all('input-checkbox')"><div class="button fs15"><div class="fas fa fa-check-square-o"></div><?php __locale('Check All',true); ?></div></a>
      </div>
    </div>
  <?php
  $count=0;
  foreach(array_reverse($posts) as $slug=>$post){
    $count++;
    if($count>$next){
      echo '<div class="all-posts-each">';
      echo '<div class="post-title">
        <div class="roundedOne">
          <input type="checkbox" name="check[]" value="'.$post['aid'].'" class="input-checkbox" id="check_'.$post['aid'].'" /><label for="check_'.$post['aid'].'"></label>
        </div>
        <label for="check_'.$post['aid'].'">'.$post['title'].'</label></div>';
      echo '<div class="post-option">
              <a href="'.WWW.''.$post['url'].'.html" target="_blank" style="color:#37b" title="View the post"><div class="fas fa fa-search"></div>'.(($post['status']=='publish')?__locale('View'):__locale('Preview')).'</a>
              <a href="'.WWW.'admin/edit-post?post_id='.$post['aid'].'" style="color:#3b7" title="Edit the post"><div class="fas fa fa-edit"></div>'.__locale('Edit').'</a>
              '.((isset($_GET['filter-status'])&&$_GET['filter-status']=='trash')?'<a href="'.WWW.'admin/confirmation?action=delete-post&post_id='.$post['aid'].'" style="color:#900" title="'.__locale('Delete the post').'"><div class="fas fa fa-trash"></div>'.__locale('Delete Permanently').'</a>':'<a href="'.WWW.'admin/confirmation?action=trash&post_id='.$post['aid'].'" style="color:#900" title="'.__locale('Move the post to trash').'"><div class="fas fa fa-trash-o"></div>'.__locale('Trash').'</a>').'
          </div>';
      echo '<div class="post-detail">'.ucfirst(__locale($post['status'])).' '.__locale(ucfirst($post['type'])).(($post['access']=='private')?' <span style="color:#b73;">(Private)</span>':'').' | '.__locale('Updated').': '.date('Y-m-d H:i',strtotime($post['datetime'])).' | '.__locale('Created').': '.date('Y-m-d H:i',$post['time']).'</div>';
      echo '</div>';
      if($count==($next+$limit)){
        echo '<a href="'.WWW.'admin/posts?next='.($next+$limit).'&limit='.$limit.((isset($_GET['filter-status']))?'&filter-status='.$_GET['filter-status']:'').((isset($_GET['filter-type']))?'&filter-type='.$_GET['filter-type']:'').'" title="'.__locale('Next').'"><div class="all-posts-each next-posts">'.__locale('Next').'</div></a>';
        break;
      }
    }
  }
  ?>
  </form>
</div>
<script type="text/javascript">
var next = <?php print($next); ?>;
var rows = <?php print($rows); ?>;
var locale_rows = "<?php print($locale_rows); ?>";
var cheader = document.getElementsByClassName('content-header');
var cht = cheader[0].innerHTML;
cheader[0].innerHTML = cht+' ('+rows+' '+locale_rows+')';

function filter_assign(){
  var status = document.getElementById('filter_status').value;
  var type = document.getElementById('filter_type').value;
  var limit = document.getElementById('limit').value;
  status = (status=='')?'publish':status;
  limit = limit==''?10:limit;
  window.location.assign('?next='+next+'&filter-status='+status+'&filter-type='+type+'&limit='+limit);
}
</script>