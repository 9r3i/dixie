<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
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
    $notif = __locale('New password request has been sent to your email');
  }
}

/* Check post login request */
login_request();

/* change locale */
$locale_data = language_data();
if(isset($_GET['data'],$_GET['locale'])&&$_GET['data']=='change-locale'&&in_array($_GET['locale'],$locale_data)){
  $exp = explode('_',base64_decode($_SESSION['dixie_login']));
  $_SESSION['dixie_locale'] = $_GET['locale'];
  $ref = WWW.'admin?status=locale-update';
  header('location: '.$ref);
  exit;
}

?>
<!DOCTYPE html><html lang="en-US"><head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title><?php __locale('Login',true); ?> | Admin &#8213; Dixie</title>
  <meta content="Dixie - Free and Simple CMS" name="description" />
  <meta content="Generator, CMS" name="keywords" />
  <meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />
  <meta content="Dixie" name="generator" /><meta content="<?php printf(DIXIE_VERSION); ?>" name="version" />
  <link rel="shortcut icon" href="<?php printf(WWW.PUBDIR); ?>admin/images/dixie-black.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php printf(WWW.PUBDIR); ?>admin/css/style-black.css?v=3.0" type="text/css" />
  <link rel="stylesheet" href="<?php printf(WWW.PUBDIR); ?>admin/css/three.css?v=1.1" type="text/css" />
  <meta content="<?php printf(WWW.PUBDIR); ?>admin/images/dixie-black.png" property="og:image" />
  <script type="text/javascript" language="javascript">
  var langs = <?php print(json_encode(language_data())); ?>;
  var www = '<?php print(WWW); ?>';
  </script>
</head><body>
  <div class="header">
    <div id="dixie_header"><h2>Dixie</h2></div>
  </div>
  <div class="body">
    <div class="locale"><div id="locale" onclick="change_locale(this,langs,www)"><span title="<?php __locale('Language',true); ?>"><?php echo ucwords(lang_get_set()); ?></span></div></div>
    <div class="content" style="width:100%;">
      <form class="login-form" action="" method="post" style="">
        <div class="login-header"><?php __locale('User Login',true); ?></div>
        <div class="login-error"><span style="color:#7b3;"><?php echo (isset($notif))?$notif:''; ?></span></div>
        <div class="login-input">
          <div class="login-label"><?php __locale('Username',true); ?></div>
          <div class="login-text"><input class="login-text-input" name="username" placeholder="<?php __locale('Username',true); ?>" type="text" /></div>
        </div>
        <div class="login-input">
          <div class="login-label"><?php __locale('Password',true); ?></div>
          <div class="login-text"><input class="login-text-input" name="password" placeholder="<?php __locale('Password',true); ?>" type="password" /></div>
        </div>
        <div class="login-input">
          <div class="login-label"></div>
          <div class="login-text" style="width:135px;">
            <div class="roundedOne">
              <input type="checkbox" name="remember" value="remember-login" class="input-checkbox" id="remember" />
              <label for="remember"></label>
            </div>
            <div style="margin-left:10px;white-space:nowrap;display:inline-block;">
              <label for="remember"><?php __locale('Remember Me',true); ?></label>
            </div>
          </div>
        </div>
        <div class="login-input"><div class="login-submit"><input class="button" value="<?php __locale('Login',true); ?>" type="submit" /></div></div>
        <div class="login-forget"><a href="?password=request" title="Request password"><?php __locale('Forget your password',true); ?>?</a></div>
      </form>
    </div>
  </div>
  <div class="footer">
    <div class="copyright"><a class="bai" href="http://dixie.hol.es/" target="_blank" title="Dixie - Free and Simple CMS">Dixie</a> <?php echo DIXIE_VERSION; ?> &middot; <?php __locale('Powered by',true); ?> <a class="bai" href="http://n8ro.hol.es/?id=profile" target="_blank" title="Luthfie a.k.a. 9r3i">Luthfie</a> 2014-<?php print(date('Y')); ?></div>
    <script type="text/javascript" src="<?php printf(WWW.PUBDIR); ?>admin/js/locale.js?v=1.1"></script>
  </div>
</body></html>


