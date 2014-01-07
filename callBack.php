<?php
require_once 'functions.php';
if (isset($_GET['oauth_verifier'])) {
    session_start();
    $connection = new TwitterOAuth(tw_consumer_key, tw_consumer_select,
            $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $_SESSION['access_token'] = $access_token = $connection->getAccessToken($_GET['oauth_verifier']);
    unset($_SESSION['oauth_token']);
    unset($_SESSION['oauth_token_secret']);
    $connection = new TwitterOAuth(tw_consumer_key, tw_consumer_select,
            $access_token['oauth_token'], $access_token['oauth_token_secret']);
}
else {
    jump('./', array('err'));
}
jump('./');
?>
