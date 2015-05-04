<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
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
<!DOCTYPE html><html lang="en-US"><head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Dixie Installation</title>
  <meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />
  <meta content="Dixie" name="generator" /><meta content="<?php echo DIXIE_VERSION; ?>" name="version" />
  <style type="text/css">
  body{margin:0px;padding:0px;background-color:#9bf;font-family:Tahoma,Segoe UI,Arial;color:#333;}
  input{font-family:Tahoma,Segoe UI,Arial;}
  .tubuh{margin:20px auto;padding:10px;width:400px;background-color:#fff;box-shadow:0 0 15px #fff;}
  .header{margin:0px;padding:0px;text-align:center;}
  .header h1{margin:10px 0px 0px;padding:0px;text-shadow:-3px 5px 1px #999;}
  .error{margin:10px;padding:0px;color:#d66;}
  .body{margin:10px;padding:0px;font-size:small;}
  .input{margin:5px 0px 10px;padding:5px;width:100%;border:1px solid #999;}
  .input:focus{border:2px solid #d66;}
  .button{margin:5px 0px 10px;padding:7px 5px;width:100%;border:0px none;background-color:#999;color:#fff;cursor:pointer;font-size:16px;font-weight:bold;text-shadow:-1px 2px 1px #333;}
  .button:hover{background-color:#666;}
  .button:focus{background-color:#444;text-shadow:-1px -2px 1px #333;}
  .footer{text-align:center;padding:0px;margin:30px 10px 10px;font-size:13px;}
  .footer a{text-decoration:none;color:#37b;}
  </style>
  <link type="image/x-icon" href="<?php echo WWW; ?>public_html/admin/images/dixie-black.ico" rel="shortcut icon" />
</head><body><div class="tubuh">
  <div class="header"><h1>Installation</h1></div>
  <div class="error"><?php echo (isset($error))?$error:''; ?></div>
  <div class="body"><form action="" method="post">
<?php
if(get_installation_code()){
?>
<table border="0" width="100%" style="width:100%;padding-right:20px;"><tbody>
  <tr>
    <td style="width:100px;">Site Name</td>
    <td style="width:160px;"><input type="text" class="input" name="site_name" placeholder="Site Name"<?php echo (isset($_POST['site_name']))?' value="'.$_POST['site_name'].'"':'' ?> /></td>
  </tr>
  <tr>
    <td>Username</td>
    <td><input type="text" class="input" name="username" placeholder="Username"<?php echo (isset($_POST['username']))?' value="'.$_POST['username'].'"':'' ?> /></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" class="input" name="password" placeholder="Password" /></td>
  </tr>
  <tr>
    <td>Confirm Password</td>
    <td><input type="password" class="input" name="cpassword" placeholder="Confirm Password" /></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><input type="email" class="input" name="email" placeholder="Email"<?php echo (isset($_POST['email']))?' value="'.$_POST['email'].'"':'' ?> /></td>
  </tr>
  <tr>
    <td>Full Name</td>
    <td><input type="text" class="input" name="name" placeholder="Full Name"<?php echo (isset($_POST['name']))?' value="'.$_POST['name'].'"':'' ?> /></td>
  </tr>
  <tr><td></td><td><input type="submit" class="button" value="Install" /></td></tr>
</tbody></table>
<?php
}else{
?>
  <div>Installation Code<input type="text" class="input" name="installation_code" placeholder="Installation Code" /></div>
  <div><input type="submit" class="button" value="Confirm Code" /></div>
<?php
}
?>
  </form></div>
  <div class="footer"><a href="http://dixie.hol.es/" title="Dixie" target="_blank">Dixie</a> v<?php print(DIXIE_VERSION); ?> &middot; Powered by <a href="http://n8ro.hol.es/?id=profile" target="_blank" title="Luthfie a.k.a. 9r3i">Luthfie</a> 2014-<?php print(date('Y')); ?></div>
</div></body></html>


