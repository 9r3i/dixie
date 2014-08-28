<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Include content header */
include_once('header.php');

/* View content body */
?>

<div class="title">
  <h2><?php get_post_detail('title'); ?></h2>
</div>
  <?php
    $types = array();
    $type = get_post_detail('type',false);
    if($type=='post'||$type=='article'){
      echo '<div class="detail">';
      echo '<div class="detail-time">Published on '.date('F, jS Y',get_post_detail('time',false)).'</div>';
      echo '<div class="detail-time">Written by '.get_post_detail('author',false).'</div>';
      echo '</div>';
    }elseif($type=='training'){
      echo '<div class="detail">';
      echo '<div class="detail-time">Event on '.date('F, jS Y',strtotime(get_post_detail('training_date',false))).'</div>';
      echo '<div class="detail-time">Trainer: '.get_post_detail('trainer',false).'</div>';
      echo '</div>';
    }
    
  ?>
<div class="content">
  <?php get_post_detail('content'); ?>
</div>

<?php
/* Include content footer */
include_once('footer.php');