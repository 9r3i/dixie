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
  <table style="width:100%" width="100%" border="0" class="table-settings"><tbody>
    <?php
    foreach($options as $key=>$value){
      $label_key = __locale(ucwords(str_replace('_',' ',$key)));
      if(in_array($key,$input_text)){
        echo '<tr><td>'.$label_key.'</td><td><div class="input-parent"><input type="text" name="'.$key.'" class="form-input" value="'.$value.'" /></div></td></tr>';
      }
    }
    ?>
    <tr><td style="width:110px;"><?php __locale('Timezone',true); ?></td><td><div><select class="form-input" name="timezone" style="width:100%;">
      <?php
        $times = mu_get_timezones();
        foreach($times as $time=>$zone){
          echo '<option value="'.$time.'"'.(($options['timezone']==$time)?' selected="true"':'').'>'.$zone.'</option>';
        }
      ?>
    </select></div></td></tr>
    <tr><td><?php __locale('Themes',true); ?></td><td>
    <div class="config-body">
      <div class="config-form-theme"><?php __locale('Main Theme',true); ?><select class="form-select-theme" name="theme">
      <?php
        foreach($themes as $stat=>$detail){echo '<option value="'.$stat.'"'.(($options['theme']==$stat)?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form-theme"><?php __locale('Mobile Theme',true); ?><select class="form-select-theme" name="mobile_theme">
      <?php
        foreach($themes as $stat=>$detail){echo '<option value="'.$stat.'"'.(($options['mobile_theme']==$stat)?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
      <div class="config-form-theme"><?php __locale('MSIE Theme',true); ?><select class="form-select-theme" name="msie_theme">
      <?php
        foreach($themes as $stat=>$detail){echo '<option value="'.$stat.'"'.(($options['msie_theme']==$stat)?' selected="true"':'').'>'.ucwords($stat).'</option>';}
      ?>
      </select></div>
    </div></td></tr>
    <tr><td><?php __locale('Main Page',true); ?></td><td><div><select class="form-input" name="main_page" style="width:100%;">
      <option value="">None (<?php __locale('Recent Posts',true); ?>)</option>
      <?php
        $post_types = array('post','page','article','training','schedule','product','event');
        foreach($post_types as $post_type){
          echo '<option value="'.$post_type.'"'.(($options['main_page']==$post_type)?' selected="true"':'').'>'.__locale('Recent Posts').' ('.__locale(ucwords($post_type)).')</option>';
        }
        foreach($posts as $stat=>$detail){
          if($detail['status']=='publish'&&$detail['type']=='page'&&$detail['access']=='public'){
            echo '<option value="'.$stat.'"'.(($options['main_page']==$stat)?' selected="true"':'').'>'.$detail['title'].' ('.$stat.')</option>';
          }
        }
      ?>
    </select></div></td></tr>
    <tr><td><?php __locale('Post Editor',true); ?></td><td><div><select class="form-input" name="post_editor" style="width:100%;">
      <option value="text">Text (text/plain)</option>
      <option value="html"<?php echo ($options['post_editor']=='html')?' selected="true"':''; ?>>HTML (text/html)</option>
    </select></div></td></tr>
    <tr><td></td><td><div><input type="submit" value="<?php __locale('Save Settings',true); ?>" class="form-submit" /></div></td></tr>
  </tbody></table>
  </form>
</div>