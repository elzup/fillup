<?php

require_once 'functions.php';
setupEncodeing();
setDB();
getAccessToken();

// echo "<pre>";

// print_r($_POST);
// print_r($_SESSION);
// print_r($access_token);
// exit;




if($_SERVER['REQUEST_METHOD'] != 'POST'){
    jump('', array('err' => 'rp'));
}
else {
    $mode = $_POST['mode'];
    $token = checkToken();

    $par = array();
    $par['id_lecture'] = $_POST['id_lecture'];
    $par['id_user'] = $_POST['id_user'];

    $tweet = isset($_POST['tweet']);
    if ($tweet) {
        setcookie('withtweet', '1');
    } else unset($_COOKIE['withtweet']);

    //check login
    if (empty($par['id_user']))
        jump('', array('err' => 'nt'));
    //check hijack
    if ($par['id_user'] != $access_token['screen_name'])
        jump('', array('err' => 'at'));

    echo $mode;
    switch($mode) {
        case 'w':
            // ----------------- word register ----------------- //
            $par['word'] = $_POST['word'];
            if ($par['word'] == '') $err = 'nw';
            if (isset($err)) jump('bb', array('id' => $par['id_lecture'], 'err' => $err));
            $par['point'] = '0';
            //            $par['timestamp'] = 'NOW()';
            $par['index'] = '0';
            print_r($par);
            $data = DB::getTable(tb_word, array('word'), array( 'id_lecture' => $par['id_lecture']));
            if (!empty($data)) {
                foreach ($data as $r) {
                    if ($r['word'] == $par['word'])
                        jump('bb', array('id' => $par['id_lecture'], 'err' => 'pf'));
                }
                $par['index'] = count($data);
            }
            DB::insert(tb_word, $par);
            DB::inclement(tb_lecture, 'count_word', array('id_lecture' => $par['id_lecture']));

            if ($tweet) {
                $text = "単語の登録: ".$par['word']."#".$par['id_lecture'];
                postTweetJenga($text, $connection);
            }
            break;

        case 'c':
            // ----------------- comment register ----------------- //
            $par['text'] = $_POST['text'];
            $par['type'] = $_POST['type'];
             if ($par['type'] == 'u') {
                 if (preg_match(re_uri, $par['text'], $matche) ){
                     $url = $matche[0];
                     $title = getTitleFromUrl($url);
                     $par['text'] = $title." ".$url;
                 } else {
                     $url = $par['text'];
                     $title = getTitleFromUrl($url);
                     $par['text'] = $title." ".$url;
 //                    $par['text'] = st_deli.$par['text'];
                 }
             }
            if ($par['text'] == '') $err = 'nt';
            if (!in_array($par['type'], explode(' ', 'n u q r'))) $err = 'nt';
            if (isset($err)) jump('bb', array('id' => $par['id_lecture'], 'err' => $err));
            $par['point'] = '0';
            //            $par['timestamp'] = 'NOW()';
            $par['index'] = '0';
            print_r($par);
            $data = DB::getTable(tb_comment, array('text'), array('id_lecture' => $par['id_lecture']));
            if (!empty($data)) {
                $par['index'] = count($data);
            }
            DB::insert(tb_comment, $par);
            DB::inclement(tb_lecture, 'count_comment', array('id_lecture' => $par['id_lecture']));

            if ($tweet) {
                $text = $par['text'];
                postTweetJenga($text, $connection);
            }
            break;
    }

    jump('bb', array('id' => $par['id_lecture'], 'did' => 'p'.$mode));



    /* --------------------------------------------------------- *
     *     demo
    * --------------------------------------------------------- */
    $par['name'] = $_POST['name'];
    $par['term_d'] = $_POST['term_d'];
    $par['term_t'] = $_POST['term_t'];
    $par['title_sub'] = $_POST['title_sub'];
    // ----------------- check ----------------- //

    if (empty($par['name'])) $err['name'] = '入力されていません';
    if ($par['term_d'] < 1 || 6 < $par['term_d']) $err['term_d'] = '不正な入力値です';
    if ($par['term_t'] < 1 || 7 < $par['term_t']) $err['term_t'] = '不正な入力値です';
    if (empty($par['title_sub'])) $err['title_sub'] = '入力されていません';

    if (empty($err)) {
        $par['term'] = $par['term_d'] . $par['term_t'];
        unset($par['term_d']);
        unset($par['term_t']);
        DB::insert(tb_lecture, $par);
        //        $id = DB::lastInsertId();
        $result = DB::getTable(tb_lecture, null, null, 1, 'id_lecture', true);
        $id = $result[0]['id_lecture'];
        jump('bb', array('id' => $id));
    }
}


function postTweetJenga($text, TwitterOAuth $connection) {
    $url = "statuses/update";
    $text = mb_substr($text, 0, 120);
    $text .= ' '.tw_hashtag;
    $parameters = array(
            'status' => $text,
    );
    print_r($parameters);
    //    if(isset($mentionID))$parameters['in_reply_to_status_id'] = $mentionID;
    $data = $connection->post($url, $parameters);	//debugSet
}




?>