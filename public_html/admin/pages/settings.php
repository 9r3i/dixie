<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Array of options */
$input_text = array('site_name','site_description','site_keywords','robots');
$select_theme = array('theme','mobile_theme','msie_theme');

/* Call the theme class */
$the = new Themes();
$themes = $the->themes;

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=settings'); ?>" method="post" class="form-content">
    <?php
    foreach($options as $key=>$value){
      $label_key = ucwords(str_replace('_',' ',$key));
      if(in_array($key,$input_text)){
        echo '<div class="input-parent">'.$label_key.'<input type="text" name="'.$key.'" class="form-input" value="'.$value.'" /></div>';
      }
    }
    ?>
    <div>Timezone<select class="form-input" name="timezone" style="width:100%;">
      <?php
        $times = mu_get_timezones();
        foreach($times as $time=>$zone){
          echo '<option value="'.$time.'"'.(($options['timezone']==$time)?' selected="true"':'').'>'.$zone.'</option>';
        }
      ?>
    </select></div>
    <div class="config-header">Themes</div>
    <div class="config-body">
      <div class="config-form-theme">Main Theme<select class="form-select-theme" name="theme">
      <?php
        foreach($themes as $stat=>$detail){echo '<option value="'.$stat.'"'.(($options['theme']==$stat)?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form-theme">Mobile Theme<select class="form-select-theme" name="mobile_theme">
      <?php
        foreach($themes as $stat=>$detail){echo '<option value="'.$stat.'"'.(($options['mobile_theme']==$stat)?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form-theme">MSIE Theme<select class="form-select-theme" name="msie_theme">
      <?php
        foreach($themes as $stat=>$detail){echo '<option value="'.$stat.'"'.(($options['msie_theme']==$stat)?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
    </div>
    <div>Main Page<select class="form-input" name="main_page" style="width:100%;">
      <option value="">None (Recent Posts)</option>
      <?php
        $post_types = array('post','page','article','training','schedule','product','event');
        foreach($post_types as $post_type){
          echo '<option value="'.$post_type.'"'.(($options['main_page']==$post_type)?' selected="true"':'').'>Recent Posts ('.$post_type.')</option>';
        }
        foreach($posts as $stat=>$detail){
          if($detail['status']=='publish'&&$detail['type']=='page'&&$detail['access']=='public'){
            echo '<option value="'.$stat.'"'.(($options['main_page']==$stat)?' selected="true"':'').'>'.$detail['title'].' ('.$stat.')</option>';
          }
        }
      ?>
    </select></div>
    <div>Post Editor<select class="form-input" name="post_editor" style="width:100%;">
      <option value="text">Text (text/plain)</option>
      <option value="html"<?php echo ($options['post_editor']=='html')?' selected="true"':''; ?>>HTML (text/html)</option>
    </select></div>
    <div><input type="submit" value="Save Settings" class="form-submit" /></div>
  </form>
</div>