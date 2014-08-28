/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

function content_sidebar(id,cid){
  var el = document.getElementById(id);
  var cel = document.getElementById(cid);
  var val = el.value;
  if(val=='text'){
    cel.innerHTML = 'Content<textarea class="form-textarea" placeholder="Insert sidebar content here" name="content"></textarea>';
  }else if(val=='recent'){
    cel.innerHTML = '<div class="config-header">Options</div><div class="config-body"><div class="config-form">Post Type<select class="form-select" name="option[post_type]" style="width:auto;"><?php foreach($post_type as $tpost){echo '<option value="'.$tpost.'">'.ucwords($tpost).'</option>';} ?></select></div><div class="config-form">Max. Post<input type="text" name="option[post_max]" class="form-input" placeholder="Maximum Post" style="width:100px;" /></div></div>';
  }else{
    cel.innerHTML = '';
  }
}