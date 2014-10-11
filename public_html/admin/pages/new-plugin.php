<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* External data plugins */
$data_url = 'http://dixie.black-apple.biz/external_plugins.php';

/* Get plugins class */
$plug = new Plugins();
$plugins = array();
foreach($plug->plugins as $pname=>$plugin){
  if(isset($plugin['about'],$plugin['about']['Plugin Name'])){
    $plugins[$plugin['about']['Plugin Name']] = $plugin['about']['Plugin Name'];
  }
}

/* HTML View */
?>
<div class="content-form">
  <form action="<?php printf(WWW.'admin/a?data=upload-plugin'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div class="input-parent">
      <div>Upload Plugin <span style="font-size:13px;color:#379;" title="Requires ZIP file">(*.zip)</span></div>
      <input type="file" name="file" class="form-input" title="click to choose the file" style="width:70%;" />
      <input type="submit" value="Upload" class="form-submit fs15" />
    </div>
  </form>
  <form action="<?php printf(WWW.'admin/a?data=activation-external-plugin'); ?>" method="post" class="form-content" enctype="multipart/form-data">
    <div style="font-size:13px;">You have an Activation Code?</div>
    <div class="input-parent"><input type="text" name="activation_code" class="form-input" placeholder="Activation Code" style="width:50%;" /><input type="submit" value="Activate" class="form-submit" style="font-size:13px;" /></div>
  </form>
</div>

<div class="content-form">
  <div id="external_plugins">
<?php
if(is_mobile_browser()){
  $file = json_decode(@file_get_contents($data_url),true);
  if(is_array($file)){
    foreach($file['data'] as $id=>$data){
      echo '<div class="external-plugin-each" id="external_plugin_each_'.$id.'">';
      if(isset($data['file_url'])){
        if(in_array($data['plugin_name'],$plugins)){
          echo '<div class="external-float"><div class="button">Installed</div></div>';
        }else{
          echo '<div class="external-float"><a href="'.WWW.'admin/a?data=add-external-plugin&file_url='.$data['file_url'].'"><div class="button">Install</div></a><div class="external-free">Free</div></div>';
        }
      }elseif(isset($data['plugin_code'],$data['kurs'],$data['price'])){
        if(in_array($data['plugin_name'],$plugins)){
          echo '<div class="external-float"><div class="button">Purchased</div></div>';
        }else{
          echo '<div class="external-float"><a href="'.WWW.'admin/a?data=purchase-external-plugin&plugin_code='.$data['plugin_code'].'"><div class="button">Purchase</div></a><div class="external-price">'.$data['kurs'].' '.number_format($data['price'],0,',','.').'</div></div>';
        }
      }else{
        echo '<div class="external-float"><div class="button">Private</div></div>';
      }
      echo '<div><strong><a href="'.$data['plugin_uri'].'" title="'.$data['plugin_name'].'" target="_blank">'.$data['plugin_name'].'</a></strong></div>';
      if(isset($data['screenshot'])){
        echo '<div style="margin:5px 0px;"><img src="'.$data['screenshot'].'" width="170px" /></div>';
      }
      if(isset($data['version'])){
        echo '<div>Version: '.$data['version'].'</div>';
      }
      if(isset($data['author'])){
        echo '<div>Author: <a href="'.$data['author_uri'].'" title="'.$data['author'].'" target="_blank">'.$data['author'].'</a></div>';
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
var plugins = <?php print(json_encode($plugins)); ?>;
  var external_url = '<?php print(WWW.'admin/a?data=get-external-plugin-data&url='.$data_url); ?>';
  $('#external_plugins').html('<div style="text-align:center;"><img src="<?php print(WWW.PUBDIR); ?>admin/images/loader.gif" width="16px" height="11px" /> <span style="margin-left:5px;">Loading...</span></div>');
  $.get(external_url,function(result){
    if(result.status=='error'){
      $('#external_plugins').html(result.message);
    }else if(result.status=='OK'){
      $('#external_plugins').empty();
      var data = result.data;
      for(var key in data){
        var id = 'external_plugin_each_'+key;
        var key_name = data[key].plugin_name;
        $('#external_plugins').append('<div class="external-plugin-each" id="'+id+'"></div>');

        if(data[key].file_url!==undefined){
          if(plugins[key_name]!==undefined){
            $('#'+id).append('<div class="external-float"><div class="button">Installed</div></div>');
          }else{
            $('#'+id).append('<div class="external-float"><a href="<?php print(WWW); ?>admin/a?data=add-external-plugin&file_url='+data[key].file_url+'"><div class="button">Install</div></a><div class="external-free">Free</div></div>');
          }
        }else if(data[key].plugin_code!==undefined&&data[key].price!==undefined&&data[key].kurs!==undefined){
          if(plugins[key_name]!==undefined){
            $('#'+id).append('<div class="external-float"><div class="button">Purchased</div></div>');
          }else{
            $('#'+id).append('<div class="external-float"><a href="<?php print(WWW); ?>admin/a?data=purchase-external-plugin&plugin_code='+data[key].plugin_code+'"><div class="button">Purchase</div></a><div class="external-price">'+data[key].kurs+' '+number_format(data[key].price,0,',','.')+'</div></div>');
          }
        }else{
          $('#'+id).append('<div class="external-float"><div class="button">Private</div></div>');
        }

        $('#'+id).append('<div><strong><a href="'+data[key].plugin_uri+'" title="'+data[key].plugin_name+'" target="_blank">'+data[key].plugin_name+'</a></strong></div>');

        if(data[key].version!==undefined){
          $('#'+id).append('<div>Version: '+data[key].version+'</div>');
        }
        if(data[key].author!==undefined){
          $('#'+id).append('<div>Author: <a href="'+data[key].author_uri+'" title="'+data[key].author+'" target="_blank">'+data[key].author+'</a></div>');
        }
        if(data[key].description!==undefined){
          var des = data[key].description.replace(/\\n/g,'<br />');
          $('#'+id).append('<div style="color:#555;font-size:90%;margin-top:5px;border-top:1px solid #bbb;padding-top:5px;">'+des+'</div>');
        }
      }
    }else{
      $('#external_plugins').html('unknown error');
    }
  });
</script>
<?php
}