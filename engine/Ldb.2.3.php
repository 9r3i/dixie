<?php
/* Ldb Class for Katya CMS (Version < 4.0) and Dixie CMS
 * Ldb stands for Luthfie database :D
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 * May 6th 2014
 * Version 2.3.5
 * Last update to 2.3 September 11th 2014
 */

class Ldb{
  public $version = '2.3.5';
  public $time;
  public $aid = 0;
  protected $database;
  private $tables;
  protected $bid;
  protected $cid;
  protected $dir;
  protected $db_dir;
  protected $dump_dir;
  public $error = false;
  function __construct($database){
    $this->database = $database; // Set database name
	$this->time = time();
	$this->bid = 452373300; // Luthfie's birthdate in time();
	$this->cid = dechex($this->time-$this->bid);
	$this->setting('_database'); // Set database main directory
	$this->show_tables();
  }
  function insert($table,$data=array()){
	$filename = $this->db_dir.$table.'.ldb';
	if(file_exists($filename)){
	  $batas_row = $this->batas('row');
	  $batas_column = $this->batas('column');
	  $batas_equal = $this->batas('equal');
	  $column = array();
	  if($this->connect()&&is_array($data)){
        $this->tdb($table);
        $this->aid++; $this->tdb($table,$this->aid);
        $column[] = 'aid'.$batas_equal.$this->aid;
        foreach($data as $key=>$value){
          if($key=='password'){
            $column[] = $key.$batas_equal.$this->hash($value);
          }elseif(!is_array($value)){
            $column[] = $key.$batas_equal.$value;
          }
        }
        $column[] = 'time'.$batas_equal.$this->time;
        $this->cid = $this->get_new_cid($table);
        $column[] = 'cid'.$batas_equal.$this->cid;
        $content = $this->select($table);
	    $rows = $this->imparse_content($content);
        $rows[] = implode($batas_column,$column);
        if($this->write($filename,implode($batas_row,$rows))){
          return $this->cid;
        }
        else{
          $this->error = 'Cannot insert into database';
          return false;
        }
      }
      else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }
    else{
      $this->error = 'Table named '.$table.' does not exist';
      return false;
    }
  }
  function select($table,$where=false){
    @parse_str($where,$keys);
	$hasil = array();
	$filename = $this->db_dir.$table.'.ldb';
	if(!file_exists($filename)){
      $this->error = 'Table named '.$table.' does not exist';
      return false;
    }
	elseif(count($keys)>0&&$this->connect(FULL_ACCESS)){
	  $store = array();
	  foreach($keys as $key=>$value){
	    if(count($store)>0){
          foreach($store as $data){
            if(isset($data[$key])&&$data[$key]==$value){$hasil[] = $data;}
          }
        }
	    if(isset($this->bank[$table][$key][$value])){
		  foreach($this->bank[$table][$key][$value] as $tab){
		    if($tab[$key]==$value){
		      if(count($keys)>1){$store[] = $tab;}
		      else{$hasil[] = $tab;}
	        }
	      }
	    }
	  }
	  return $hasil;
	}
	elseif($where==false&&$this->connect()){
	  if(isset($this->bank[$table])){
	    $hasil = $this->bank[$table];
	    return $hasil;
	  }
	  else{
	    $this->error = 'Table named '.$table.' does not exist';
	    return false;
	  }
	}
	else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  function update($table,$where=false,$data=array()){
	$filename = $this->db_dir.$table.'.ldb';
	if(!file_exists($filename)){
	  $this->error = 'Table named '.$table.' does not exist';
	  return false;
	}
	elseif($this->connect()&&isset($where)){
	  $index = @explode('=',$where);
	  if(!isset($index[0])||!isset($index[1])){
	    $this->error = 'Cannot find index table';
		return false;
	  }
      else{
	    $update = array();
        foreach($this->bank[$table] as $bank){
	      if(isset($bank[$index[0]])&&$bank[$index[0]]==$index[1]){
		    foreach($bank as $key=>$val){
		      if(isset($data[$key])){
                if($key=='password'){
			      $bank[$key] = $this->hash($data[$key]);
                }elseif(!is_array($data[$key])){
			      $bank[$key] = $data[$key];
                }
			  }
		    }
		  }
		  $update[] = $bank;
	    }
	    $imparse = $this->imparse_content($update);
	    $batas_row = $this->batas('row');
	    if($this->write($filename,@implode($batas_row,$imparse))){
	      return true;
	    }
	    else{
          $this->error = 'Cannot update database';
	      return false;
	    }
      }
	}
	else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  function delete($table,$where=false){
	$filename = $this->db_dir.$table.'.ldb';
	if(!file_exists($filename)){
	  $this->error = 'Table named '.$table.' does not exist';
	  return false;
	}
	elseif($this->connect()&&isset($where)){
	  $index = explode('=',$where);
	  if(!isset($index[0])||!isset($index[1])){
	    $this->error = 'Cannot find index table';
		return false;
	  }
      else{
	    $update = array();
        foreach($this->bank[$table] as $bank){
	      if(isset($bank[$index[0]])&&$bank[$index[0]]==$index[1]){
		  }else{
            $update[] = $bank;
		  }
	    }
	    $imparse = $this->imparse_content($update);
	    $batas_row = $this->batas('row');
	    if($this->write($filename,@implode($batas_row,$imparse))){
	      return true;
	    }
	    else{
          $this->error = 'Cannot delete database';
	      return false;
	    }
      }
	}
	else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  function search($table,$key=false,$set=false){
	$filename = $this->db_dir.$table.'.ldb';
    $index = @explode('=',$key);
	if(!file_exists($filename)){
	  $this->error = 'Table named '.$table.' does not exist';
	  return false;
	}
    elseif(!isset($index[0])||!isset($index[1])){
      $this->error = 'Cannot find index table';
      return false;
	}
	elseif($this->connect()&&isset($key)){
      $index = @explode('=',$key);
	  if(isset($this->bank[$table])&&count($this->bank[$table])>0){
	    $hasil = array();
	    foreach($this->bank[$table] as $bank){
		  if(isset($bank[$index[0]])&&preg_match('/'.$index[1].'/i',$bank[$index[0]],$akur)){
			if($set==true){
		      $bank[$index[0]] = str_replace($akur[0],'<strong>'.$akur[0].'</strong>',$bank[$index[0]]);
			  $hasil[] = $bank;
			}
			else{
		      $hasil[] = $bank;
			}
		  }
		}
		return $hasil;
	  }
	  else{
	    $this->error = 'Table is empty';
	    return false;
	  }
	}
	else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  function connect($access=4){
    $hasil = array();
	if(is_array($this->tables)&&count($this->tables)>0){
	  foreach($this->tables as $tab){
	    $filename = $this->db_dir.$tab.'.ldb';
	    $content = @file_get_contents($filename);
		$parse = $this->parse_content($content,$access);
		$this->bank[$tab] = $parse;
	  }
	  return true;
	}
	else{
	  return false;
	}
  }
  function write($filename,$content='',$type='w+'){
    $fp = fopen($filename,$type);
    $write = fwrite($fp,$this->strip_magic($content));
    fclose($fp);
    if($write){
      return true;
    }
    else{
      return false;
    }
  }
  function strip_magic($str){
    if(is_array($str)){
      $hasil = array();
	  foreach($str as $k=>$v){
        $hasil[$k] = (get_magic_quotes_gpc())?stripslashes($v):$v;
      }
      return $hasil;
	}
	else{
	  return (get_magic_quotes_gpc())?stripslashes($str):$str;
	}
  }
  function imparse_content($table){
	$batas_column = $this->batas('column');
	$batas_equal = $this->batas('equal');
	$hasil = array();
	if(is_array($table)&&count($table)>0){
      foreach($table as $row){
	    if(is_array($row)&&count($row)>0){
		  $column = array();
		  foreach($row as $key=>$value){
		    $column[] = $key.$batas_equal.$value;
		  }
		  $hasil[] = @implode($batas_column,$column);
		}
	  }
	  return $hasil;
	}
	else{
	  return false;
	}
  }
  function parse_content($content,$access=4){
	$batas_row = $this->batas('row');
	$batas_column = $this->batas('column');
	$batas_equal = $this->batas('equal');
    $hasil = array();
	$rows = @explode($batas_row,$content);
	if(is_array($rows)&&count($rows)>0){
	  $r=0;
	  foreach($rows as $row){
	    if(!empty($row)){
		  $columns = @explode($batas_column,$row);
	      if(is_array($columns)&&count($columns)>0){
		    foreach($columns as $column){
			  $equal = @explode($batas_equal,$column);
			  if(isset($equal[0])&&isset($equal[1])){
			    $hasil[$r][$equal[0]] = $equal[1];
			  }
			}
		  }
		  $r++;
		}
	  }
	  if($access==16){
	    $full = array();
	    foreach($hasil as $has){
	      if(is_array($has)&&count($has)>0){
		    foreach($has as $key=>$val){
			  $full[$key][$val][] = $has;
			}
		  }
	    }
		$hasil = $full;
	  }
	}
	return $hasil;
  }
  function get_new_cid($table){
    $table_file = $this->db_dir.$table.'.ldb';
    if(file_exists($table_file)){
      $micro = @number_format((@microtime(true)),9,'.','');
      $exp = explode('.',$micro);
      $ext = (isset($exp[1]))?dechex($exp[1]):10000000;
      $base = dechex(time()-1141963053);
      if(strlen($ext)==8){
        $ext = $ext;
      }elseif(strlen($ext)<8&&strlen($ext)>0){
        $uj = 8-strlen($ext);
        $ext = $ext.substr(dechex(time()),0,$uj);
      }else{
        $ext = dechex(time());
      }
      return $base.$ext;
    }
    else{
      return $this->cid;
    }
  }
  function create_table($name){
    if(preg_match('/[a-zA-Z0-9_]+/i',$name,$akur)){
	  $filename = $this->db_dir.$akur[0].'.ldb';
	  if(!file_exists($filename)){
        if($this->write($filename)){
          $this->error = false;
          $this->show_tables();
	      return true;
        }
	    else{
          $this->error = 'Table '.$akur[0].' was created';
	      return false;
	    }
	  }
	  else{
        $this->error = 'Table '.$akur[0].' was created';
	    return false;
	  }
	}
	else{
      $this->error = 'Character name is not available';
	  return false;
	}
  }
  function delete_table($name){
    $filename = $this->db_dir.$name.'.ldb';
    if(file_exists($filename)){
	  $content = @file_get_contents($filename);
	  $dump = $this->write($this->dump_dir.$name.'_'.$this->cid.'.ldb',$content);
      $this->tdb($name,'0');
      @unlink($filename);
      $this->show_tables();
      return true;
	}
	else{
      $this->error = 'Table named '.$name.' does not exist';
	  return false;
	}
  }
  function show_tables(){
    $sdir = @scandir($this->db_dir);
	$hasil = array();
	foreach($sdir as $sd){
	  if(preg_match('/[a-zA-Z0-9_]+\.ldb/i',$sd)){
	    $hasil[] = str_replace('.ldb','',$sd);
	  }
	}
	return $this->tables = $hasil;
  }
  function show_database(){
    $sdir = scandir($this->dir);
	$hasil = array();
	foreach($sdir as $sd){
	  if(preg_match('/[a-zA-Z0-9_]+/i',$sd)&&$sd!=='.htaccess'&&is_dir($this->dir.$sd)){
	    $hasil[] = $sd;
	  }
	}
	return $hasil;
  }
  function valid_password($table,$where,$password){
    $select = $this->select($table,$where);
    if(isset($select[0]['password'])&&$select[0]['password']==$this->hash($password)){
      return true;
    }
    else{
      return false;
    }
  }
  protected function hash($password,$algo=5){
    $algos = hash_algos();
    $algo = ($algo<count($algos))?$algo:5;
    $hash = hash($algos[$algo],$password,false);
    return $hash;
  }
  protected function db_rules($table,$key=false){
    
  }
  protected function tdb($table,$key=false){
    $tdb = $this->db_dir.'~tdb_'.$table.'.tdb';
    if(!file_exists($tdb)){
      $this->write($tdb,'0');
    }
    if($key){
      $this->write($tdb,$key);
    }
    else{
      $content = @file_get_contents($tdb);
      $this->aid=$content;
    }
  }
  protected function batas($key=''){
    $batas = array(
	  'table'=>'+++++++++'.md5('batas_table').'+++++++++',
	  'row'=>'-----'.md5('batas_row').'-----',
	  'column'=>'||'.md5('batas_column').'||',
	  'equal'=>'==='.md5('batas_equal').'==='
	);
    if(array_key_exists($key,$batas)){
	  return $batas[$key];
	}
	else{
	  return false;
	}
  }
  private function setting($Ldb_dir='_Ldb'){
    $this->dir = $Ldb_dir.'/';
    if(!is_dir($this->dir)){
	  @mkdir($this->dir,0700);
	  @chmod($this->dir,0700);
	}
	if(!file_exists($this->dir.'.htaccess')){
	  $this->write($this->dir.'.htaccess','Options -Indexes'. PHP_EOL .'deny from all');
	}
	$this->db_dir = $this->dir.$this->database.'/';
    if(!is_dir($this->db_dir)){
	  @mkdir($this->db_dir,0700);
	  @chmod($this->db_dir,0700);
	}
	if(!file_exists($this->db_dir.'.htaccess')){
	  $this->write($this->db_dir.'.htaccess','Options -Indexes'. PHP_EOL .'deny from all');
	}
	$this->dump_dir = $this->db_dir.'_dump/';
    if(!is_dir($this->dump_dir)){
	  @mkdir($this->dump_dir,0700);
	  @chmod($this->dump_dir,0700);
	}
	if(!file_exists($this->dump_dir.'.htaccess')){
	  $this->write($this->dump_dir.'.htaccess','Options -Indexes'. PHP_EOL .'deny from all');
	}
    if(!defined('STD_ACCESS')){define('STD_ACCESS',4);}
	if(!defined('FULL_ACCESS')){define('FULL_ACCESS',16);}
	if(!defined('PRIME_ACCESS')){define('PRIME_ACCESS',64);}
	if(!defined('ULTIMATE_ACCESS')){define('ULTIMATE_ACCESS',256);}
  }
}
