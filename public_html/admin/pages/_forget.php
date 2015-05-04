<?php
/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
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
<!DOCTYPE html><html lang="en-US"><head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title><?php __locale('Forget Password',true); ?> | Admin &#8213; Dixie</title>
  <meta content="Dixie - Free and Simple CMS" name="description" />
  <meta content="Generator, CMS" name="keywords" />
  <meta content="Luthfie" name="developer" /><meta content="luthfie@y7mail.com" name="developer-email" />
  <meta content="Dixie" name="generator" /><meta content="<?php printf(DIXIE_VERSION); ?>" name="version" />
  <link rel="shortcut icon" href="<?php printf(WWW.PUBDIR); ?>admin/images/dixie-black.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php printf(WWW.PUBDIR); ?>admin/css/style-black.css" type="text/css" />
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
      <form class="login-form" action="" method="post" style="margin:30px auto;padding:15px;background-color:#fff;width:175px;">
        <div class="login-header"><?php __locale('Request Password',true); ?></div>
        <div class="login-input"><span style="color:red;"><?php echo (isset($error))?$error:''; ?></span></div>
        <div class="login-input"><input class="login-text-input" name="username" placeholder="<?php __locale('Username',true); ?>" type="text" /></div>
        <div class="login-input"><input class="button" value="<?php __locale('Send Request',true); ?>" type="submit" /></div>
      </form>
    </div>
  </div>
  <div class="footer">
    <div class="copyright"><a class="bai" href="http://dixie.hol.es/" target="_blank" title="Dixie - Free and Simple CMS">Dixie</a> <?php echo DIXIE_VERSION; ?> &middot; <?php __locale('Powered by',true); ?> <a class="bai" href="http://n8ro.hol.es/?id=profile" target="_blank" title="Luthfie a.k.a. 9r3i">Luthfie</a> 2014-<?php print(date('Y')); ?></div>
    <script type="text/javascript" src="<?php printf(WWW.PUBDIR); ?>admin/js/locale.js?v=1.1"></script>
  </div>
</body></html>


