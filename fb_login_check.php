<?php
if (!session_id()) {
    session_start();
}
require_once __DIR__ . '/Facebook_sdk/autoload.php'; 
$fb = new Facebook\Facebook([
  'app_id' => 'XXXXXXXXX',
  'app_secret' => 'XXXXXXXXX',
  'default_graph_version' => 'v3.1',
  ]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId('275822089728118'); 
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    exit;
  }
}
$accesstokenstring = (string) $accessToken;

try {
  $response = $fb->get('/me?fields=id,name,email,picture.width(1000)', $accesstokenstring);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
$user = $response->getGraphUser();
$UserName = '';
$UserProfile = '';
$Status = 'Not Logged in through Facebook';
if($user['id']){
	$pic = $user['picture'];
	$b64image = base64_encode(file_get_contents((string) $pic['url']));
	$UserName = 'Welcome ' . $user['name'] . '<br>';
	$UserProfile = '<img class="myimage" src="data:image/png;base64,'.$b64image.'" alt="Fb Profile" />';
	$Status = 'Logged in through Facebook';
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Logged in page</title>
<style>
.myimage {height: 200px;
    border-radius: 100px;}
#contents {text-align: center;}
</style>
</head>
<body>
<div id="contents">
<h1><?php echo $UserName; ?></h1>
<p><?php echo $UserProfile; ?></p>
<h2><?php echo $Status; ?></h2>
</div>
</body>
</html>











