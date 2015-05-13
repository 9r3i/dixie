<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* --------- CONFIG ACCESS --------- */

/* set access_token */
$access_token = false;

/* config allowed domain and database access */
$_ad = array(
  /* $host=>$database */
);


/* --------- DO NOT EDIT FROM THIS POINT --------- */

/* set empty array */
$result = array();

/* not isset any get request  */
if(!isset($_GET)||empty($_GET)){
  $result['code'] = 700;
  $result['status'] = 'error';
  $result['message'] = 'get request is required';
}
/* no access token */
elseif(!isset($_GET['access_token'])){
  $result['code'] = 701;
  $result['status'] = 'error';
  $result['message'] = 'access token is required';
}
/* not match access_token */
elseif(isset($_GET['access_token'])&&$_GET['access_token']!==$access_token){
  $result['code'] = 702;
  $result['status'] = 'error';
  $result['message'] = 'invalid access token';
}
/* no domain request */
elseif(!isset($_GET['domain'])){
  $result['code'] = 703;
  $result['status'] = 'error';
  $result['message'] = 'domain request is required';
}
/* not allowed domain */
elseif(isset($_GET['domain'])&&!array_key_exists($_GET['domain'],$_ad)){
  $result['code'] = 704;
  $result['status'] = 'error';
  $result['message'] = 'not allowed domain';
}
/* no database selected */
elseif(!isset($_GET['database'])){
  $result['code'] = 705;
  $result['status'] = 'error';
  $result['message'] = 'database request is required';
}
/* no database allowed */
elseif(isset($_GET['database'])&&!in_array($_GET['database'],$_ad)){
  $result['code'] = 706;
  $result['status'] = 'error';
  $result['message'] = 'not allowed database';
}

/* ----- success result ----- */
elseif(isset($_GET['domain'],$_GET['database'])&&array_key_exists($_GET['domain'],$_ad)&&in_array($_GET['database'],$_ad)){
  $ldb = new Ldb($_GET['database']);
  $action = isset($_GET['action'])?$_GET['action']:'';
  /* insert */
  if($action=='insert'&&isset($_GET['table'],$_GET['data'])){
    $data = $ldb->insert($_GET['table'],$_GET['data']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* select */
  elseif($action=='select'&&isset($_GET['table'],$_GET['location'])){
    $data = $ldb->select($_GET['table'],$_GET['location']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* update */
  elseif($action=='update'&&isset($_GET['table'],$_GET['data'],$_GET['location'])){
    $data = $ldb->update($_GET['table'],$_GET['location'],$_GET['data']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* delete */
  elseif($action=='delete'&&isset($_GET['table'],$_GET['location'])){
    $data = $ldb->delete($_GET['table'],$_GET['location']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* search */
  elseif($action=='search'&&isset($_GET['table'],$_GET['key'],$_GET['set'])){
    $data = $ldb->search($_GET['table'],$_GET['key'],$_GET['set']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* create_table */
  elseif($action=='create_table'&&isset($_GET['table'])){
    $data = $ldb->create_table($_GET['table']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* delete_table */
  elseif($action=='delete_table'&&isset($_GET['table'])){
    $data = $ldb->delete_table($_GET['table']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* show_tables */
  elseif($action=='show_tables'){
    $data = $ldb->show_tables();
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* show_database */
  elseif($action=='show_database'){
    $data = $ldb->show_database();
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* valid_password */
  elseif($action=='valid_password'&&isset($_GET['table'],$_GET['location'],$_GET['password'])){
    $data = $ldb->valid_password($_GET['table'],$_GET['location'],$_GET['password']);
    $result['code'] = 200;
    $result['status'] = 'OK';
    $result['message'] = 'success';
    $result['data'] = $data;
  }
  /* unknown action */
  else{
    $result['code'] = 710;
    $result['status'] = 'error';
    $result['message'] = 'unknown action';
  }
  if($ldb->error){
    $result['code'] = 711;
    $result['status'] = 'error';
    $result['message'] = $ldb->error;
    if(isset($result['data'])){unset($result['data']);}
  }
}
/* unknown error */
else{
  $result['code'] = 720;
  $result['status'] = 'error';
  $result['message'] = 'unknown error';
}

/* set header as json */
header('content-type: application/json; charset=utf-8;');

/* print output of result */
print(json_encode($result));
exit;
