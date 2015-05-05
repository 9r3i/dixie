<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* External data themes */
$data_url = 'http://dixie.hol.es/external_themes.php';

/* Get themes class */
$ct = new Themes();
$themes = array();
foreach($ct->themes as $tname=>$theme){
  if(isset($theme['about'],$theme['about']['Theme Name'])){
    $themes[$theme['about']['Theme Name']] = $theme['about']['Theme Name'];
  }
}

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=upload-theme'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div class="input-parent">
      <div>Upload Theme <span style="font-size:13px;color:#379;" title="<?php __locale('Requires ZIP file',true); ?>">(*.zip)</span></div>
      <input type="file" name="file" class="form-input" title="<?php __locale('click to choose the file',true); ?>" style="width:70%;" />
      <input type="submit" value="<?php __locale('Upload',true); ?>" class="form-submit fs15" />
    </div>
  </form>
  <form action="<?php printf(WWW.'admin/a?data=activation-external-theme'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div style="font-size:13px;"><?php __locale('You have an Activation Code',true); ?>?</div>
    <div class="input-parent"><input type="text" name="activation_code" class="form-input" placeholder="<?php __locale('Activation Code',true); ?>" style="width:50%;" /><input type="submit" value="<?php __locale('Activate',true); ?>" class="form-submit" style="font-size:13px;" /></div>
  </form>
</div>

<div class="content-form">
  <div id="external_themes">
<?php
if(is_mobile_browser()){
  $file = json_decode(@file_get_contents($data_url),true);
  if(is_array($file)){
    foreach($file['data'] as $id=>$data){
      echo '<div class="external-theme-each" id="external_theme_each_'.$id.'">';

      if(isset($data['file_url'])){
        if(in_array($data['theme_name'],$themes)){
          echo '<div class="external-center"><div class="button">'.__locale('Installed').'</div></div>';
        }else{
          echo '<div class="external-center"><a href="'.WWW.'admin/a?data=add-external-theme&file_url='.$data['file_url'].'"><div class="button">'.__locale('Install').'</div></a><div class="external-free">'.__locale('Free').'</div></div>';
        }
      }elseif(isset($data['theme_code'],$data['kurs'],$data['price'])){
        if(in_array($data['theme_name'],$plugins)){
          echo '<div class="external-center"><div class="button">'.__locale('Purchased').'</div></div>';
        }else{
          echo '<div class="external-center"><a href="'.WWW.'admin/a?data=purchase-external-theme&theme_code='.$data['theme_code'].'"><div class="button">Purchase</div></a><div class="external-price-theme">'.$data['kurs'].' '.number_format($data['price'],0,',','.').'</div></div>';
        }
      }else{
        echo '<div class="external-center"><div class="button">'.__locale('Private').'</div></div>';
      }

      echo '<div><strong><a href="'.$data['theme_uri'].'" title="'.$data['theme_name'].'" target="_blank">'.$data['theme_name'].'</a></strong></div>';
      if(isset($data['screenshot'])){
        echo '<div style="margin:5px 0px;"><img src="'.$data['screenshot'].'" width="170px" /></div>';
      }
      if(isset($data['version'])){
        echo '<div>'.__locale('Version').': '.$data['version'].'</div>';
      }
      if(isset($data['author'])){
        echo '<div>'.__locale('Author').': <a href="'.$data['author_uri'].'" title="'.$data['author'].'" target="_blank">'.$data['author'].'</a></div>';
      }
      if(isset($data['description'])){
        echo '<div style="color:#555;font-size:90%;margin-top:5px;border-top:1px solid #bbb;padding-top:5px;">'.nrtobr($data['description']).'</div>';
      }
      echo '</div>';
    }
  }
}
?>
  </div>
</div>
<?php
if(!is_mobile_browser()){
?>
<script type="text/javascript">
var themes = <?php print(json_encode($themes)); ?>;
  var external_url = '<?php print(WWW.'admin/a?data=get-external-theme-data&url='.$data_url); ?>';
  $('#external_themes').html('<div><img src="<?php print(WWW.PUBDIR); ?>admin/images/loader.gif" width="16px" height="11px" /> <span style="margin-left:5px;"><?php __locale('Loading',true); ?>...</span></div>');
  $.get(external_url,function(result){
    if(result.status=='error'){
      $('#external_themes').html(result.message);
    }else if(result.status=='OK'){
      $('#external_themes').empty();
      var data = result.data;
      for(var key in data){
        var id = 'external_theme_each_'+key;
        var key_name = data[key].theme_name;
        $('#external_themes').append('<div class="external-theme-each" id="'+id+'"></div>');

        if(data[key].file_url!==undefined){
          if(themes[key_name]!==undefined){
            $('#'+id).append('<div class="external-center"><div class="button"><?php __locale('Installed',true); ?></div></div>');
          }else{
            $('#'+id).append('<div class="external-center"><a href="<?php print(WWW); ?>admin/a?data=add-external-theme&file_url='+data[key].file_url+'"><div class="button"><?php __locale('Install',true); ?></div></a><div class="external-free"><?php __locale('Free',true); ?></div></div>');
          }
        }else if(data[key].theme_code!==undefined&&data[key].price!==undefined&&data[key].kurs!==undefined){
          if(themes[key_name]!==undefined){
            $('#'+id).append('<div class="external-center"><div class="button"><?php __locale('Purchased',true); ?></div></div>');
          }else{
            $('#'+id).append('<div class="external-center"><a href="<?php print(WWW); ?>admin/a?data=purchase-external-theme&theme_code='+data[key].theme_code+'"><div class="button"><?php __locale('Purchase',true); ?></div></a><div class="external-price-theme">'+data[key].kurs+' '+number_format(data[key].price,0,',','.')+'</div></div>');
          }
        }else{
          $('#'+id).append('<div class="external-center"><div class="button"><?php __locale('Private',true); ?></div></div>');
        }

        $('#'+id).append('<div><strong><a href="'+data[key].theme_uri+'" title="'+data[key].theme_name+'" target="_blank">'+data[key].theme_name+'</a></strong></div>');
        if(data[key].screenshot!==undefined){
          $('#'+id).append('<div style="margin:5px 0px;"><img src="'+data[key].screenshot+'" width="170px" /></div>');
        }
        if(data[key].version!==undefined){
          $('#'+id).append('<div><?php __locale('Version',true); ?>: '+data[key].version+'</div>');
        }
        if(data[key].author!==undefined){
          $('#'+id).append('<div><?php __locale('Author',true); ?>: <a href="'+data[key].author_uri+'" title="'+data[key].author+'" target="_blank">'+data[key].author+'</a></div>');
        }
        if(data[key].description!==undefined){
          var des = data[key].description.replace(/\\n/g,'<br />');
          $('#'+id).append('<div style="color:#555;font-size:90%;margin-top:5px;border-top:1px solid #bbb;padding-top:5px;">'+des+'</div>');
        }
      }
    }else{
      $('#external_themes').html('<?php __locale('unknown error',true); ?>');
    }
  });
</script>
<?php
}