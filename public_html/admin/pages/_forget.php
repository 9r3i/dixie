<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Check request reset password  */
if(isset($_POST['username'])){
  $request = password_request();
  if(isset($request['status'])&&$request['status']=='OK'){
    header('location: '.WWW.'admin/login/?request=password-sent');
    exit;
  }else{
    $error = $request['message'];
  }
}

?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Forget Password | Admin &#8213; Dixie</title>
    <meta content="Dixie CMS from Black Apple Inc." name="description" />
    <meta content="Generator, CMS" name="keywords" />
    <meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />
    <meta content="Dixie" name="generator" /><meta content="<?php printf(DIXIE_VERSION); ?>" name="version" />
    <link rel="shortcut icon" href="<?php printf(WWW.PUBDIR); ?>admin/images/dixie.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?php printf(WWW.PUBDIR); ?>admin/css/style.css" type="text/css" />
    <meta content="<?php printf(WWW.PUBDIR); ?>admin/images/dixie.png" property="og:image" />
  </head>
  <body>
    <div class="header">
      Dixie
    </div>
    <div class="body">
      <div class="content" style="width:100%;">
        <form class="login-form" action="" method="post" style="margin:30px auto;padding:15px;background-color:#fff;width:175px;">
          <div class="login-header">Request Password</div>
          <div class="login-input"><span style="color:red;"><?php echo (isset($error))?$error:''; ?></span></div>
          <div class="login-input"><input class="input" name="username" placeholder="Username" type="text" /></div>
          <div class="login-input"><input class="button" value="Send Request" type="submit" /></div>
        </form>
      </div>
    </div>
    <div class="footer">
      <div class="copyright">Dixie &middot; Version <?php echo DIXIE_VERSION; ?> &middot; Powered by <a class="bai" href="http://black-apple.biz/" target="_blank" title="Black Apple Inc.">Black Apple Inc.</a></div>
    </div>
  </body>
</html>

