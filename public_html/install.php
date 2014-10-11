<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Set global ldb */
global $ldb;
if(!ldb()){
  header('content-type: text/plain');
  exit('cannot connect into database');
}

/* Get site options */
if(get_options()){
  header('location: '.WWW.'?status=installed');
  exit;
}

/* Create the important tables */
$tables = array('menu','sidebar','options','posts','users','request','category');
foreach($tables as $table){
  $ldb->create_table($table);
}

/* Set error variable for installation package */
$error = get_installation_package();
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Dixie Installation</title>
    <meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />
    <meta content="Dixie" name="generator" /><meta content="<?php echo DIXIE_VERSION; ?>" name="version" />
    <style type="text/css">
      body{margin:0px;padding:0px;background-color:#f88;font-family:Tahoma,Segoe UI,Arial;color:#333;}
      input{font-family:Tahoma,Segoe UI,Arial;}
      .tubuh{margin:20px auto;padding:10px;width:400px;background-color:#fff;box-shadow:0 0 15px #fff;}
      .header{margin:0px;padding:0px;text-align:center;}
      .header h1{margin:10px 0px 0px;padding:0px;text-shadow:-3px 5px 1px #f88;}
      .error{margin:10px;padding:0px;color:#d66;}
      .body{margin:10px;padding:0px;font-size:15px;}
      .input{margin:5px 0px 10px;padding:5px;width:360px;border:1px solid #999;}
      .input:focus{border:2px solid #d66;}
      .button{margin:5px 0px 10px;padding:7px 5px;width:370px;border:0px none;background-color:#f88;color:#fff;cursor:pointer;font-size:16px;font-weight:bold;text-shadow:-1px 2px 1px #333;}
      .button:hover{background-color:#d66;}
      .button:focus{background-color:#b44;text-shadow:-1px -2px 1px #333;}
      .footer{text-align:center;padding:0px;margin:30px 10px 10px;font-size:13px;}
      .footer a{text-decoration:none;color:#333;}
    </style>
    <link type="image/x-icon" href="<?php echo WWW; ?>public_html/admin/images/dixie.ico" rel="shortcut icon" />
  </head>
  <body><div class="tubuh">
    <div class="header"><h1>Installation</h1></div>
    <div class="error"><?php echo (isset($error))?$error:''; ?></div>
    <div class="body"><form action="" method="post">
<?php
if(get_installation_code()){
?>
      <div>Site Name<input type="text" class="input" name="site_name" placeholder="Site Name"<?php echo (isset($_POST['site_name']))?' value="'.$_POST['site_name'].'"':'' ?> /></div>
      <div>Username<input type="text" class="input" name="username" placeholder="Username"<?php echo (isset($_POST['username']))?' value="'.$_POST['username'].'"':'' ?> /></div>
      <div>Password<input type="password" class="input" name="password" placeholder="Password" /></div>
      <div>Confirm Password<input type="password" class="input" name="cpassword" placeholder="Confirm Password" /></div>
      <div>Email<input type="email" class="input" name="email" placeholder="Email"<?php echo (isset($_POST['email']))?' value="'.$_POST['email'].'"':'' ?> /></div>
      <div>Full Name<input type="text" class="input" name="name" placeholder="Full Name"<?php echo (isset($_POST['name']))?' value="'.$_POST['name'].'"':'' ?> /></div>
      <div><input type="submit" class="button" value="Install" /></div>
<?php
}else{
?>
      <div>Installation Code<input type="text" class="input" name="installation_code" placeholder="Installation Code" /></div>
      <div><input type="submit" class="button" value="Confirm Code" /></div>
<?php
}
?>
    </form></div>
    <div class="footer">Dixie from <a href="http://black-apple.biz/" title="Black Apple Inc." target="_blank">Black Apple Inc.</a></div>
  </div></body>
</html>