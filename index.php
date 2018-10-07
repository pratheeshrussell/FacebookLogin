

<!DOCTYPE html>
<html>
<head>
<title>Facebook Login Example</title>
<meta charset="UTF-8">
<style>

#contents {text-align: center;margin-top:100px;font-size:50px}
</style>
</head>
<body>
<?php
if(!session_id()) {
    session_start();
}
require_once __DIR__ . '/Facebook_sdk/autoload.php'; 
$fb = new Facebook\Facebook([
  'app_id' => 'XXXXXXXXX', // Replace {app-id} with your app id
  'app_secret' => 'XXXXXXXXX',
  'default_graph_version' => 'v3.1',
  ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://localhost:100/FbLogInTest/fb_login_check.php', $permissions);

?>
<div id="contents">
<a  href="<?php echo htmlspecialchars($loginUrl) ?>">Log in with Facebook!</a>
</div>
</body>
</html>