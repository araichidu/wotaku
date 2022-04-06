<?php
session_start();

require_once 'vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

// アクセストークンを取得
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth($_SESSION['consumer_key'], $_SESSION['consumer_secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);

//ユーザー情報取得
$user = $connection->get('account/verify_credentials');




    $friendIds =  $connection->get('friends/ids', ['screen_name' => 'nijisanji_app']);


if(!empty($_POST)){
    if(isset($_POST['tweet'])){
        // ブロックする
        $tweet = $_POST['tweet'];
        $connection->post('statuses/update', ['status' => $tweet]);

        foreach ($friendIds->ids as $i => $id) {
            $connection->post('blocks/create', ['user_id' => $id]);
        }
   }elseif(isset($_POST['follow'])){
        // フォローする
        $message = $_POST['follow'];
        $result = $connection->post('statuses/update', ['status' => $message]);
        foreach ($friendIds->ids as $i => $id) {
            $connection->post('friendships/create', ['user_id' => $id]);
        }
   }elseif(isset($_POST['unblock'])){
        // ブロ解する
        $message = $_POST['unblock'];
        $result = $connection->post('statuses/update', ['status' => $message]);
        foreach ($friendIds->ids as $i => $id) {
            $connection->post('blocks/destroy', ['user_id' => $id]);
        }
    }
}

// タイムラインを取得
$timeline = $connection->get('statuses/user_timeline', ['count' => 3]);

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>ライバーをブロックする</title>
  </head>
        <h1>ライバーをブロックしたりするよ！</h1>
  <body>
    <div class="container mt-4 mb-4">
        <h4>ログイン中のユーザー</h4>
        <div class="row">
            <div class="col-sm-8">
            <img src="<?=$user->profile_image_url_https?>">
            <span><?=$user->name?>（@<?=$user->screen_name?>）</span>
            </div>
        </div>
    </div>

    <div class="container mb-4">
        <h4>やれること</h4>
        テキストボックス内の文字列をツイートしつつブロックしたりします。
    </div>

    <div class="container mb-4">
        <h4>ブロック</h4>
        <form action="/app.php" method="post">
        <textarea rows="3" cols="100" name="tweet">ブロックしました；；</textarea>
            <input type="submit" value="ブロックする"/>
        </form>
    </div>

    <div class="container mb-4">
        <h4>ブロック解除</h4>
        <form action="/app.php" method="post">
            <textarea rows="3" cols="100" name="unblock">ブロックを解除しました</textarea>
            <input type="submit" value="ブロ解する"/>
        </form>
    </div>
    
    <div class="container">
        <h4>フォロー</h4>
        <form action="/app.php" method="post">
            <textarea rows="3" cols="100" name="follow">フォローしました</textarea>
            <input type="submit" value="フォローする"/>
        </form>
    </div>

  </body>
</html>
