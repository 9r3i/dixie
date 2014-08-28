<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Theme option
 * Theme name: Dixie
 * CMS: Dixie CMS
 */

if(isset($_POST['header-image'])){
  $set = set_theme_option('Dixie',$_POST);
  header('location: '.WWW.'admin/theme-option?name=Dixie&status='.(($set)?'update':'failed'));
  exit;
}

?>
<style type="text/css">
.option-tags{
  margin:0px;
  padding:10px;
  border:3px dashed #c33;
  font-size:13px;
}
.option-each{margin:0px;padding:10px 0px;border-bottom:1px solid #bbb;}
.option-each-submit{margin:0px;padding:10px 0px 10px 140px;}
.option-label{display:inline-block;width:130px;padding-right:10px;}
.option-data{display:inline-block;width:420px;}
.option-input{
  width:400px;
  border:1px solid #999;
  padding:5px 5px;
  margin:5px 0px;
  font-size:13px;
}
.option-select{
  width:415px;
  border:1px solid #999;
  padding:5px 5px;
  margin:5px 0px;
  font-size:13px;
  margin-left:-5px;
}
.option-submit{
  margin:0px 0px 0px;
  padding:5px 7px;
  background-color:#b22;
  color:#fff;
  font-size:15px;
  border:0px none;
  cursor:pointer;
}
.option-submit:hover{background-color:#900;}
.option-submit:focus{background-color:#700;}
</style>
<div class="option-tags"><form action="" method="post">
  <div class="option-each">
    <div class="option-label">Header Name</div><div class="option-data"><input type="text" name="header-name" class="option-input" value="<?php tprint(get_theme_option('Dixie','header-name')); ?>" placeholder="Empty will set as Site Name" /></div>
  </div>
  <div class="option-each">
    <div class="option-label">Header Image</div>
    <div class="option-data">
      <select name="header-image" class="option-select">
        <option value="">-- Default --</option>
        <?php
        $scan = @scandir('upload/');
        if(is_array($scan)){
          foreach($scan as $file){
            if($file!=='.'&&$file!=='..'){
              $name = 'upload/'.$file;
              echo '<option value="'.$name.'"'.(($name==get_theme_option('Dixie','header-image'))?' selected="true"':'').'>'.$name.'</option>';
            }
          }
        }
        ?>
      </select>
    </div>
  </div>
  <div class="option-each">
    <div class="option-label">Show Sidebar Title</div>
    <div class="option-data">
      <select name="sidebar-title" class="option-select">
        <option value="yes">Yes</option>
        <option value="no"<?php echo (get_theme_option('Dixie','sidebar-title')=='no')?' selected="true"':''; ?>>No</option>
      </select>
    </div>
  </div>
  <div class="option-each-submit">
    <input type="submit" class="option-submit" value="Save" />
  </div>
</form></div>
