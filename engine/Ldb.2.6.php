<?php
/* Ldb Class for Katya CMS (Version < 4.0) and Dixie CMS
 * Ldb stands for Luthfie database :D
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 * http://luthfie.hol.es/
 * Started on May 6th 2014 version 2.0
 * Updated to 2.4 at October 2nd 2014
 * Updated to 2.5 at March 16th 2015
 * Last updated to 2.6 at May 13th 2015
 * Current Version 2.6.0
 */

class Ldb{
  public $version = '2.6.0';
  public $time;
  public $microtime;
  public $aid = 0;
  protected $database;
  private $tables;
  private $bid;
  private $cid;
  private $dir;
  private $db_dir;
  private $dump_dir;
  private $temp_dir;
  private $crossDomain = false;
  private $host = false;
  private $www = false;
  private $access_token = false;
  public $error = false;
  function __construct($database=null,$host=null,$access_token=''){
    if(isset($database)){
      $this->database = $database; // Set database name
	  $this->time = time();
	  $this->microtime = microtime(true);
	  $this->bid = 452373300; // Luthfie's birthdate in time();
	  $this->cid = dechex($this->time-$this->bid);
	  $this->setting('_database'); // Set database main directory
	  $this->show_tables();
      $pattern = '/^http:\/\/[a-z0-9]{1}[a-z0-9\.-]+[a-z0-9]{2,4}\/([\w-]+\/)?$/';
      if(isset($host)&&preg_match($pattern,$host)&&defined('WWW')&&$host!==WWW){
        $this->crossDomain = true;
        $this->host = $host.'Ldb2.connect';
        $this->www = WWW;
        $this->access_token = $access_token;
      }
    }else{
      return false;
    }
  }
  public function insert($table,$data=array()){
	$filename = $this->db_dir.$table.'.ldb';
    if($this->crossDomain){
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'insert',
        'table'=>$table,
        'data'=>$data,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }elseif(is_file($filename)){
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
        if($this->swrite($filename,implode($batas_row,$rows))){
          return $this->cid;
        }else{
          $this->error = 'Cannot insert into database';
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }else{
      $this->error = 'Table named '.$table.' does not exist';
      return false;
    }
  }
  public function select($table,$where=false){
    @parse_str($where,$keys);
	$hasil = array();
	$filename = $this->db_dir.$table.'.ldb';
	if($this->crossDomain){
      $where = $where?$where:'';
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'select',
        'table'=>$table,
        'location'=>$where,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }elseif(!is_file($filename)){
      $this->error = 'Table named '.$table.' does not exist';
      return false;
    }elseif(count($keys)>0&&$this->connect(FULL_ACCESS)){
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
	}elseif($where==false&&$this->connect()){
	  if(isset($this->bank[$table])){
	    $hasil = $this->bank[$table];
	    return $hasil;
	  }else{
	    $this->error = 'Table named '.$table.' does not exist';
	    return false;
	  }
	}else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  public function update($table,$where=false,$data=array()){
	$filename = $this->db_dir.$table.'.ldb';
	if($this->crossDomain){
      $where = $where?$where:'';
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'update',
        'table'=>$table,
        'location'=>$where,
        'data'=>$data,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }elseif(!is_file($filename)){
	  $this->error = 'Table named '.$table.' does not exist';
	  return false;
	}elseif($this->connect()&&isset($where)){
	  $index = @explode('=',$where);
	  if(!isset($index[0])||!isset($index[1])){
	    $this->error = 'Cannot find index table';
		return false;
	  }else{
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
	    if($this->swrite($filename,@implode($batas_row,$imparse))){
	      return true;
	    }else{
          $this->error = 'Cannot update database';
	      return false;
	    }
      }
	}else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  public function delete($table,$where=false){
	$filename = $this->db_dir.$table.'.ldb';
	if($this->crossDomain){
      $where = $where?$where:'';
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'delete',
        'table'=>$table,
        'location'=>$where,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }elseif(!is_file($filename)){
	  $this->error = 'Table named '.$table.' does not exist';
	  return false;
	}elseif($this->connect()&&isset($where)){
	  $index = explode('=',$where);
	  if(!isset($index[0])||!isset($index[1])){
	    $this->error = 'Cannot find index table';
		return false;
	  }else{
	    $update = array();
        foreach($this->bank[$table] as $bank){
	      if(isset($bank[$index[0]])&&$bank[$index[0]]==$index[1]){
		  }else{
            $update[] = $bank;
		  }
	    }
	    $imparse = $this->imparse_content($update);
	    $batas_row = $this->batas('row');
	    if($this->swrite($filename,@implode($batas_row,$imparse))){
	      return true;
	    }else{
          $this->error = 'Cannot delete database';
	      return false;
	    }
      }
	}else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  public function search($table,$key=false,$set=false){
	$filename = $this->db_dir.$table.'.ldb';
    $index = @explode('=',$key);
	if($this->crossDomain){
      $key = $key?$key:'';
      $set = $set?$set:'';
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'search',
        'table'=>$table,
        'key'=>$key,
        'set'=>$set,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }elseif(!is_file($filename)){
	  $this->error = 'Table named '.$table.' does not exist';
	  return false;
	}elseif(!isset($index[0])||!isset($index[1])){
      $this->error = 'Cannot find index table';
      return false;
	}elseif($this->connect()&&isset($key)){
      $index = @explode('=',$key);
	  if(isset($this->bank[$table])&&count($this->bank[$table])>0){
	    $hasil = array();
	    foreach($this->bank[$table] as $bank){
          if(isset($bank[$index[0]],$index[1])&&!empty($index[1])&&preg_match('/'.$index[1].'/i',$bank[$index[0]],$akur)){
			if($set==true){
		      $bank[$index[0]] = str_replace($akur[0],'<b>'.$akur[0].'</b>',$bank[$index[0]]);
			  $hasil[] = $bank;
			}else{
		      $hasil[] = $bank;
			}
		  }
		}
		return $hasil;
	  }else{
	    $this->error = 'Table is empty';
	    return false;
	  }
	}else{
	  $this->error = 'Cannot connect to database';
	  return false;
	}
  }
  public function create_table($name=null){
    $name = isset($name)?$name:'+';
    if($this->crossDomain){
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'create_table',
        'table'=>$name,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }elseif(preg_match('/^[a-zA-Z0-9_]+$/i',$name,$akur)){
      $filename = $this->db_dir.$akur[0].'.ldb';
      if(!is_file($filename)){
        if($this->write($filename)){
          $this->error = false;
          $this->show_tables();
          return true;
        }else{
          $this->error = 'Table '.$akur[0].' was created';
          return false;
        }
      }else{
        $this->error = 'Table '.$akur[0].' was created';
        return false;
      }
    }else{
      $this->error = 'Character name is not available';
      return false;
    }
  }
  public function delete_table($name=null){
    $name = isset($name)?$name:'+';
    $filename = $this->db_dir.$name.'.ldb';
    if($this->crossDomain){
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'delete_table',
        'table'=>$name,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }elseif(is_file($filename)){
      $content = @file_get_contents($filename);
      $dump = $this->write($this->dump_dir.$name.'_'.$this->cid.'.ldb',$content);
      $this->tdb($name,'0');
      @unlink($filename);
      $this->show_tables();
      return true;
    }else{
      $this->error = 'Table named '.$name.' does not exist';
      return false;
    }
  }
  public function show_tables(){
    if($this->crossDomain){
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'show_tables',
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }else{
      $sdir = @scandir($this->db_dir);
	  $hasil = array();
	  foreach($sdir as $sd){
	    if(preg_match('/[a-zA-Z0-9_]+\.ldb/i',$sd)){
	      $hasil[] = str_replace('.ldb','',$sd);
	    }
	  }
	  return $this->tables = $hasil;
    }
  }
  public function show_database(){
    if($this->crossDomain){
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'show_database',
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }else{
      $sdir = scandir($this->dir);
	  $hasil = array();
	  foreach($sdir as $sd){
	    if(preg_match('/[a-zA-Z0-9_]+/i',$sd)&&$sd!=='.htaccess'&&is_dir($this->dir.$sd)){
	      $hasil[] = $sd;
	    }
	  }
	  return $hasil;
    }
  }
  public function valid_password($table,$where,$password){
    if($this->crossDomain){
      $cross = $this->cross($this->host,array(
        'domain'=>$this->www,
        'database'=>$this->database,
        'action'=>'valid_password',
        'table'=>$table,
        'location'=>$where,
        'password'=>$password,
      ));
      if($cross){
        if(isset($cross['status'])&&$cross['status']=='OK'){
          return $cross['data'];
        }else{
          $this->error = $cross['code'].ucfirst($cross['message']);
          return false;
        }
      }else{
        $this->error = 'Cannot connect to database';
        return false;
      }
    }else{
      $select = $this->select($table,$where);
      if(isset($select[0]['password'])&&$select[0]['password']==$this->hash($password)){
        return true;
      }else{
        return false;
      }
    }
  }
  public function hash($password,$algo=5){
    $algos = hash_algos();
    $algo = ($algo<count($algos))?$algo:5;
    $hash = hash($algos[$algo],$password,false);
    return $hash;
  }
  public function strip_magic($str){
    if(is_array($str)){
      $hasil = array();
	  foreach($str as $k=>$v){
        $hasil[$k] = (get_magic_quotes_gpc())?stripslashes($v):$v;
      }
      return $hasil;
	}else{
	  return (get_magic_quotes_gpc())?stripslashes($str):$str;
	}
  }
  public function spend_time(){
    return @number_format(@microtime(true)-$this->microtime,4,'.','');
  }
  protected function connect($access=4){
    $hasil = array();
	if(is_array($this->tables)&&count($this->tables)>0){
	  foreach($this->tables as $tab){
	    $filename = $this->db_dir.$tab.'.ldb';
	    $content = @file_get_contents($filename);
		$parse = $this->parse_content($content,$access);
		$this->bank[$tab] = $parse;
	  }
	  return true;
	}else{
	  return false;
	}
  }
  private function cross($url,$data=array(),$cookie=''){
    $data['access_token'] = $this->access_token;
    $content = @http_build_query($data,'','&'); // ,PHP_QUERY_RFC1738
    $file = @file_get_contents($url.'?'.$content);
    return json_decode($file,true);
  }
  private function write($filename=null,$content='',$type='wb'){
    $filename = isset($filename)?$filename:'error-'.time().'.txt';
    $fp = fopen($filename,$type);
    if($fp&&flock($fp,LOCK_EX)){
      $write = fwrite($fp,(get_magic_quotes_gpc()?stripslashes($content):$content));
      flock($fp,LOCK_UN);
      if($write){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
    fclose($fp);
  }
  private function swrite($filename=null,$content='',$type='wb',$attemp=0){
    $filename = isset($filename)?$filename:'error-'.time().'.txt';
    $temp = $this->temp_dir.date('ymdHis').'_'.@number_format((@microtime(true)),9,'_','').'.ldb';
    $write = $this->write($temp,$content,$type);
    if($write){
      @copy($temp,$filename);
      @unlink($temp);
      return true;
    }else{
      @unlink($temp);
      if($attemp<10){
        $attemp++;
        return $this->swrite($filename,$content,$type,$attemp);
      }else{
        return false;
      }
    }
  }
  private function imparse_content($table){
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
	}else{
	  return false;
	}
  }
  private function parse_content($content,$access=4){
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
  private function get_new_cid($table){
    $table_file = $this->db_dir.$table.'.ldb';
    if(is_file($table_file)){
      $micro = @number_format((@microtime(true)),9,'.','');
      $exp = explode('.',$micro);
      $ext = (isset($exp[1]))?dechex($exp[1]):dechex(time());
      $base = dechex(time()-$this->bid);
      if(strlen($ext)==8){
        $ext = $ext;
      }elseif(strlen($ext)<8&&strlen($ext)>0){
        $uj = 8-strlen($ext);
        $ext = $ext.substr(dechex(time()),0,$uj);
      }else{
        $ext = dechex(time());
      }
      return $base.$ext;
    }else{
      return $this->cid;
    }
  }
  private function tdb($table,$key=false){
    $tdb = $this->db_dir.'~tdb_'.$table.'.tdb';
    if(!is_file($tdb)){
      $this->write($tdb,'0');
    }
    if($key){
      $this->write($tdb,$key);
    }else{
      $content = @file_get_contents($tdb);
      $this->aid=$content;
    }
  }
  private function batas($key=''){
    $batas = array(
	  'table'=>'+++++++++'.md5('batas_table').'+++++++++',
	  'row'=>'-----'.md5('batas_row').'-----',
	  'column'=>'||'.md5('batas_column').'||',
	  'equal'=>'==='.md5('batas_equal').'==='
	);
    if(array_key_exists($key,$batas)){
	  return $batas[$key];
	}else{
	  return false;
	}
  }
  private function setting($Ldb_dir='_Ldb'){
    /* Ldb directory */
    $this->dir = $Ldb_dir.'/';
    if(!is_dir($this->dir)){
	  @mkdir($this->dir,0700);
	  @chmod($this->dir,0700);
	}
	if(!is_file($this->dir.'.htaccess')){
	  $this->write($this->dir.'.htaccess','Options -Indexes'. PHP_EOL .'deny from all');
	}
    /* database directory */
	$this->db_dir = $this->dir.$this->database.'/';
    if(!is_dir($this->db_dir)){
	  @mkdir($this->db_dir,0700);
	  @chmod($this->db_dir,0700);
	}
	if(!is_file($this->db_dir.'.htaccess')){
	  $this->write($this->db_dir.'.htaccess','Options -Indexes'. PHP_EOL .'deny from all');
	}
    /* dump directory */
	$this->dump_dir = $this->db_dir.'_dump/';
    if(!is_dir($this->dump_dir)){
	  @mkdir($this->dump_dir,0700);
	  @chmod($this->dump_dir,0700);
	}
	if(!is_file($this->dump_dir.'.htaccess')){
	  $this->write($this->dump_dir.'.htaccess','Options -Indexes'. PHP_EOL .'deny from all');
	}
    /* temp directory */
	$this->temp_dir = $this->db_dir.'_temp/';
    if(!is_dir($this->temp_dir)){
	  @mkdir($this->temp_dir,0700);
	  @chmod($this->temp_dir,0700);
	}
	if(!is_file($this->temp_dir.'.htaccess')){
	  $this->write($this->temp_dir.'.htaccess','Options -Indexes'. PHP_EOL .'deny from all');
	}
    /* define access code */
    if(!defined('STD_ACCESS')){define('STD_ACCESS',4);}
	if(!defined('FULL_ACCESS')){define('FULL_ACCESS',16);}
  }
}

