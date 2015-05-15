<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

/* Load locale package as version 2.3.0 */
load_locale_package();

/* Check username and code  */
if(isset($_POST['new-password'])&&isset($_POST['confirm-password'])){
  if($_POST['new-password']==$_POST['confirm-password']){
    if(password_reset()){
      header('location: '.WWW.'admin/?status=new-password');
      exit;
    }else{
      $error = __locale('Your request can\'t be confirmed');
    }
  }else{
    $error = __locale('Your confirm password dosn\'t match');
  }
}

/* Check username and code  */
if(isset($_GET['username'])&&isset($_GET['code'])){
  if(check_request_code($_GET['username'],$_GET['code'])){
?>
<!DOCTYPE html><html lang="en-US"><head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title><?php __locale('Reset Password',true); ?> | Admin &#8213; Dixie</title>
  <meta content="Dixie - Free and Simple CMS" name="description" />
  <meta content="Generator, CMS" name="keywords" />
  <meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />
  <meta content="Dixie" name="generator" /><meta content="<?php printf(DIXIE_VERSION); ?>" name="version" />
  <link rel="shortcut icon" href="<?php printf(WWW.PUBDIR); ?>admin/images/dixie-black.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php printf(WWW.PUBDIR); ?>admin/css/style-black.css" type="text/css" />
  <meta content="<?php printf(WWW.PUBDIR); ?>admin/images/dixie-black.png" property="og:image" />
</head><body>
  <div class="header">
    <div id="dixie_header"><h2>Dixie</h2></div>
  </div>
  <div class="body">
    <div class="content" style="width:100%;">
      <form class="login-form" action="" method="post" style="margin:30px auto;padding:15px;background-color:#fff;width:175px;">
        <div class="login-header"><?php __locale('New Password',true); ?></div>
        <div class="login-input"><span style="color:red;"><?php echo (isset($error))?$error:''; ?></span></div>
        <div class="login-input"><input class="login-text-input" name="new-password" placeholder="<?php __locale('New Password',true); ?>" type="password" /></div>
        <div class="login-input"><input class="login-text-input" name="confirm-password" placeholder="<?php __locale('New Password',true); ?>" type="password" /></div>
        <input name="username" value="<?php print($_GET['username']); ?>" type="hidden" />
        <input name="code" value="<?php print($_GET['code']); ?>" type="hidden" />
        <div class="login-input"><input class="button" value="<?php __locale('Proceed',true); ?>" type="submit" /></div>
      </form>
    </div>
  </div>
  <div class="footer">
    <div class="copyright"><a class="bai" href="http://dixie-cms.herokuapp.com/" target="_blank" title="Dixie - Free and Simple CMS">Dixie</a> <?php echo DIXIE_VERSION; ?> &middot; <?php __locale('Powered by',true); ?> <a class="bai" href="http://luthfie.hol.es/?id=profile" target="_blank" title="Luthfie a.k.a. 9r3i">Luthfie</a> 2014-<?php print(date('Y')); ?></div>
  </div>
</body></html>
<?php
  }else{
    header('content-type: text/plain');
    exit('invalid request');
  }
}else{
  header('content-type: text/plain');
  exit('invalid request');
}
