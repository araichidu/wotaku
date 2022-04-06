<?php
session_start();

require_once 'vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
const CONSUMER_KEY = '';  // API key
const CONSUMER_SECRET = '';  // API key secret
const OAUTH_CALLBACK = '';

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

$request_token = $connection->oauth('oauth/request_token', ['oauth_callback' => OAUTH_CALLBACK]);

// callback.php で利用する
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
$_SESSION['consumer_key'] = CONSUMER_KEY;
$_SESSION['consumer_secret'] = CONSUMER_SECRET;

// twitter.com上の認証画面のURLを取得してリダイレクト
$url = $connection->url('oauth/authenticate', ['oauth_token' => $request_token['oauth_token']]);
header('location: '. $url);
