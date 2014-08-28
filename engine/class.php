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
    $sdir = @scandir($themes_directory);
	$theme = array();
	foreach($sdir as $sd){
	  if($sd!=='.'&&$sd!=='..'&&!is_file($sd)){
		$theme[$sd]['name'] = $sd;
	    $smdr = @scandir($themes_directory.'/'.$sd.'/');
	    foreach($smdr as $sm){
		  if($sm!=='.'&&$sm!=='..'&&!is_dir($themes_directory.'/'.$sd.'/'.$sm)){
		    $theme[$sd][str_replace('.php','',$sm)] = $sm;
		  }
		}
	  }
	}
	$this->themes = $theme;
	$this->themes_dir = $themes_directory;
  }
  public function load($theme_name,$file){
    if(isset($this->themes[$theme_name][$file])){
	  include_once($this->themes_dir.'/'.$theme_name.'/'.$this->themes[$theme_name][$file]);
	}
  }
}

/* Plugins class */
class Plugins{
  public $dir;
  public $types = array('action','header','menu','title','content','sidebar','footer');
  public $plugins;
  public $active;
  function __construct($directory='plugins'){
    $this->dir = $directory;
    $sdir = @scandir($this->dir);
    $types = $this->types;
	foreach($sdir as $sd){
	  if($sd!=='.'&&$sd!=='..'&&$sd!=='.htaccess'){
        $this->plugins[$sd]['dir'] = $sd;
        $this->plugins[$sd]['name'] = ucwords(preg_replace('/[^a-zA-Z0-9]+/i',' ',$sd));
		$sd_dir = @scandir($this->dir.'/'.$sd);
        $status = @file_get_contents($this->dir.'/'.$sd.'/status.txt');
		foreach($sd_dir as $sdd){
		  if(preg_match('/\.php/i',$sdd)){
		    $type = str_replace('.php','',$sdd);
            if(in_array($type,$types)){
		      $this->plugins[$sd][$type] = $sdd;
            }
            if($status=='active'){
		      $this->active[$type][$sd] = $sdd;
            }
		  }
		}
      }
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