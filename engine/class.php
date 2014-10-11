<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Themes Class */
class Themes{
  public $themes;
  public $themes_dir;
  function __construct($themes_directory='themes'){
	$this->themes_dir = $themes_directory;
    $sdir = @scandir($themes_directory);
	$theme = array();
	foreach($sdir as $sd){
	  if($sd!=='.'&&$sd!=='..'&&!is_file($sd)&&is_dir($this->themes_dir.'/'.$sd)){
		$theme[$sd]['name'] = $sd;
		$theme[$sd]['about'] = $this->theme_about($sd);
	    $smdr = @scandir($themes_directory.'/'.$sd.'/');
	    foreach($smdr as $sm){
		  if($sm!=='.'&&$sm!=='..'&&!is_dir($themes_directory.'/'.$sd.'/'.$sm)){
		    $theme[$sd][str_replace('.php','',$sm)] = $sm;
		  }
		}
	  }
	}
	$this->themes = $theme;
  }
  public function load($theme_name,$file){
    if(isset($this->themes[$theme_name][$file])){
	  include_once($this->themes_dir.'/'.$theme_name.'/'.$this->themes[$theme_name][$file]);
	}
  }
  function theme_about($name=null){
    if(isset($name)&&file_exists($this->themes_dir.'/'.$name.'/about.txt')){
      $keys = array('Theme Name','Theme URI','Author','Author URI','Version','Description','ScreenShot');
      $file = @file($this->themes_dir.'/'.$name.'/about.txt');
      $content = array();
      foreach($file as $fi){
        if(preg_match('/===/i',$fi)){
          $exp = explode('===',$fi);
          if(in_array(trim($exp[0]),$keys)&&isset($exp[1])){
            $content[trim($exp[0])] = trim($exp[1]);
          }
        }
      }
      return $content;
    }else{
      return false;
    }
  }
};

/* Plugins class */
class Plugins{
  public $dir;
  public $types = array(
      'action','header','nav','title','content','menu','sidebar','footer',
      'admin-action','admin-header','admin-content','admin-footer'
    );
  public $plugins;
  public $active;
  function __construct($directory='plugins'){
    $this->dir = $directory;
    $sdir = @scandir($this->dir);
    $types = $this->types;
	foreach($sdir as $sd){
	  if($sd!=='.'&&$sd!=='..'&&$sd!=='.htaccess'&&is_dir($this->dir.'/'.$sd)){
        $this->plugins[$sd]['dir'] = $sd;
        $this->plugins[$sd]['about'] = $this->plugin_about($sd);
        $this->plugins[$sd]['name'] = ucwords(preg_replace('/[^a-zA-Z0-9]+/i',' ',$sd));
		$sd_dir = @scandir($this->dir.'/'.$sd);
        $status = @file_get_contents($this->dir.'/'.$sd.'/status.txt');
		foreach($sd_dir as $sdd){
		  if(preg_match('/\.php/i',$sdd)){
		    $type = str_replace('.php','',$sdd);
            if(in_array($type,$types)){
		      $this->plugins[$sd][$type] = $sdd;
              if($status=='active'){
		        $this->active[$type][$sd] = $sdd;
              }
            }
		  }
		}
      }
    }
  }
  function plugin_about($name=null){
    if(isset($name)&&file_exists($this->dir.'/'.$name.'/about.txt')){
      $keys = array('Plugin Name','Plugin URI','Author','Author URI','Version','Description');
      $file = @file($this->dir.'/'.$name.'/about.txt');
      $content = array();
      foreach($file as $fi){
        if(preg_match('/===/i',$fi)){
          $exp = explode('===',$fi);
          if(in_array(trim($exp[0]),$keys)&&isset($exp[1])){
            $content[trim($exp[0])] = trim($exp[1]);
          }
        }
      }
      return $content;
    }else{
      return false;
    }
  }
  function load($type){
    $types = $this->types;
    if(in_array($type,$types)&&isset($this->active[$type])){
      foreach($this->active[$type] as $plug_dir=>$plug){
        if(file_exists($this->dir.'/'.$plug_dir.'/'.$plug)){
          @include_once($this->dir.'/'.$plug_dir.'/'.$plug);
        }
      }
    }
  }
}