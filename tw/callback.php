<?php
session_start();

require_once 'vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

// Twitterから返されたOAuthトークンの検証
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    die('OAuth token invalid');
}

// OAuthトークンも用いてTwitterOAuthをインスタンス化
$connection = new TwitterOAuth($_SESSION['consumer_key'], $_SESSION['consumer_secret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

// アクセストークンの取得
$_SESSION['access_token'] = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);

session_regenerate_id();

header('location: /app.php');
