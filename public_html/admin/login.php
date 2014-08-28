<?php
/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

/* Check session login */
if(is_login()){
  header('location: '.WWW.'admin/?status=logged_in');
  exit;
}

/* Check if user forget password  */
if(isset($_GET['password'])){
  @include_once('pages/_forget.php');
  exit;
}

/* Check request */
if(isset($_GET['request'])){
  if($_GET['request']=='password-sent'){
    $notif = 'New password request has been sent to your email.';
  }
}

/* Check post login request */
login_request();

?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Login | Admin &#8213; Dixie</title>
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
          <div class="login-header">User Login</div>
          <div class="login-forget"><span style="color:#7b3;"><?php echo (isset($notif))?$notif:''; ?></span></div>
          <div class="login-input"><input class="input" name="username" placeholder="Username" type="text" /></div>
          <div class="login-input"><input class="input" name="password" placeholder="Password" type="password" /></div>
          <div class="login-input"><input class="button" value="Login" type="submit" /></div>
          <div class="login-forget"><a href="?password" title="Request password">Forget your password?</a></div>
        </form>
      </div>
    </div>
    <div class="footer">
      <div class="copyright">Dixie &middot; Version <?php echo DIXIE_VERSION; ?> &middot; Powered by <a class="bai" href="http://black-apple.biz/" target="_blank" title="Black Apple Inc.">Black Apple Inc.</a></div>
    </div>
  </body>
</html>

