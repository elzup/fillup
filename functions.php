<?php

// ----------------- require files ----------------- //
require_once 'config.php';
require_once 'c_DB.php';        //privaate in git
require_once 'Lecture.php';
require_once 'LectureList.php';
require_once 'config.php';
require_once 'lib/twitteroauth.php';


// ----------------- defines ----------------- //
define('fo_date', 'Y-m-d H:i:s');

define('re_uri', '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u');
define('re_title', '<title>(.*?)<\/title>');

define('st_deli', '__::__::');


/* --------------------------------------------------------- *
 *     initialization functions
* --------------------------------------------------------- */

function setDB(){

    mb_regex_encoding('UTF-8');

    mb_language("uni");
    mb_internal_encoding("utf-8"); //内部文字コードを変更
    mb_http_input("auto");
    mb_http_output("utf-8");

    $link = mysqli_connect(db_url, db_user, db_pass) or die("connectError:".mysqli_error($link));
    mysqli_set_charset($link, "utf8")or die("ERROR charset");
    mysqli_select_db($link, db_name)or die('selectError:'.mysql_error());

    DB::$link = $link;
}

function setupEncodeing(){
    $charset="utf8";
    header('Content-type:text/html; charset=utf-8');
    mb_regex_encoding('UTF-8');
    if(isset($_GET['pre'])) echo "<pre>";
    session_start();
}

function setToken(){
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
    return $token;
}

function checkToken() {
    $token = $_POST['token'];
    if (empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token']))
        jump('', array('err' => 'te'));
    return $token;
}
function getAccessToken() {
    $login = false;
    global $login, $access_token, $connection;
    if(isset($_SESSION['access_token'])) {
        $access_token = $_SESSION['access_token'];
        $login = true;
        $connection = new TwitterOAuth(tw_consumer_key, tw_consumer_select,
                $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $login = true;
    }
}


function loadJson($filename) {
    $json = file_get_contents($filename);
    return json_decode($json);
}

/* --------------------------------------------------------- *
 *     make html functions
* --------------------------------------------------------- */

function htmlHeader($title){
    $title .= " - " . site_name;
    echo $head =<<<head
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>$title - </title>

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.0.0/build/cssreset/reset-min.css" />
<link rel="stylesheet" charset="UTF-8" href="./lib/bootstrap/css/bootstrap.min.css" media="screen" />
<link rel="stylesheet/less" type="text/css" charset="UTF-8" href="style.less" media="screen" />
<script src="lib/less-1.3.3.min.js" type="text/javascript"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="./lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<script src="./script.js" charset="utf-8"></script>

</head>

head;
}


function htmlDate($term) {
    return $html = date("m")."月".date("d")."日".convertDayToStr(date("w"))."曜".$term."限";
}

function toStrDay($n) {
    if ($n > 6) return "NN";
    $lib = explode(" ", "日 月 火 水 木 金 土");
    return $lib[$n]."曜";
}

function toStrTime($n) {
    if ($n > 7) return "NN";
    $lib = explode(" ", "０ １ ２ ３ ４ ５ ６ ７");
    //    $lib = explode(" ", "零 一 ニ 三 四 五 六 七");
    return $lib[$n]."限";
}

function htmlDateJumpForm($y, $m, $d) {
	$ymd = $y.$m.$d;
	$ymd_p = date('Ymd', strtotime('+1day', $ymd));
	$ymd_n = date('Ymd', strtotime('-1day', $ymd));

	echo $e =<<<EOF
          <form action="" class="form-horizontal" method="GET">
            <fieldset>
              <div class="form-group">
				<button id="btn-move-p" class="btn col-lg-1" ><a class="a-block" href="./prev?ymd={$ymd_p}">Prev</a></button>
	            <label for="select" class="col-lg-2 control-label">日付け</label>
                <div class="col-lg-2">
                  <select name="" id="date-y" class="form-control">
EOF;
	for ($i = 2013; $i <= 2014; $i++)
		echo '<option value="'.$i.'"'.(($i == $y) ? " selected" : "").'>'.$i.'</option>';
	echo $e =<<<EOF
                  </select>
				</div>
	            <label for="select" class="col-lg-1 control-label">年</label>
                <div class="col-lg-1">
                  <select name="" id="date-m" class="form-control">
EOF;
	for ($i = 1; $i <= 12; $i++)
		echo '<option value="'.$i.'"'.(($i == $m) ? " selected" : "").'>'.$i.'</option>';
	echo $e =<<<EOF
                  </select>
                </div>
	            <label for="select" class="col-lg-1 control-label">月</label>
                <div class="col-lg-1">
                  <select name="d" id="date-m" class="form-control">
EOF;
	for ($i = 1; $i <= 31; $i++)
		echo '<option value="'.$i.'"'.(($i == $d) ? " selected" : "").'>'.$i.'</option>';
	echo $e =<<<EOF
                  </select>
                </div>
	            <label for="select" class="col-lg-1 control-label">日</label>
				<button id="btn-move" class="btn btn-primary col-lg-1" type="submit">移動</button>
				<button id="btn-move-p" class="btn col-lg-1" ><a class="a-block" href="./prev?ymd={$ymd_p}">Next</a></button>
              </div>
            </fieldset>
          </form>


EOF;
}

/* --------------------------------------------------------- *
 *     sub functions
* --------------------------------------------------------- */
function jump($url, $parameter = array()){
    $get_text = "";
    if(!empty($parameter)){
        $get_text = "?";
        foreach($parameter as $key => $value) {
            if (is_numeric($key))
                $get_text .= $value;
            else
                $get_text .= $key . "=" . $value;
            $get_text .= "&";
        }
        $get_text = substr($get_text, 0, -1);
    }
    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
        $url = site_url.$url;
    }
    header('Location: '.$url.$get_text);
    exit;
}


function get_rest($value_name, $rest) {
    return isset($_{strtoupper($rest)}[$value_name]) ? $_{strtoupper($rest)}[$value_name] : false;
}

function is_sql($input) {
    $search=array("\\","\0","\n","\r","\x1a","'",'"');
    $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
    return str_replace($search,$replace,$input);
}

function super_die($contents) {
    $num = 1;
    echo "<pre>";
    foreach($contents as $key => $value) {
        echo $num." :"."[$key]".PHP_EOL;
        if($value === true) echo "true";
        elseif($value === false) echo "false";
        elseif(empty($value)) echo "empty";
        else {
            print_r($value);
        }
        echo PHP_EOL;
        echo PHP_EOL;
    }
    exit;
}

function h($str) {
    return nl2br(htmlspecialchars($str));
}


define('time_before', 2);
define('time_going', 1);
define('time_after', 3);
function getStateTimeLecture($term_t) {
    $time_table = array(
            "",
            '09:00',
            '10:40',
            '13:10',
            '14:50',
            '16:30',
            '18:10',
            '20:50',
    );
    $time_start = strtotime($time_table[$term_t]);
    $time_end = $time_start + 60 * 90;

    if (isset($_GET['deb'])) {
        echo date('H:i:s', $time_start).PHP_EOL;
        echo date('H:i:s', $time_end).PHP_EOL;
        echo date('H:i:s', time()).PHP_EOL;
        echo $ppt = $pt * 100 / ($time_start - $time_end).PHP_EOL;

        exit;
    }

    if (time() < $time_start) return time_before;
    if (time() < $time_end) {
        $pt = $time_start - time();
        return $ppt = $pt * 100 / ($time_start - $time_end);
    }
    return time_after;
}

function getStateTimeLectureProgress($term_t) {

}


function getTitleFromUrl($url) {
    $f = file_get_contents($url);
    $html = mb_convert_encoding($html, mb_internal_encoding(), "auto" );
    if ( preg_match( "/".re_title.'/iu', $html, $matchese) ) { //(3)
        return $matchese[1];
    } else {
        return false;
    }
}

function urlChangeLink($text){
    $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
    $replacement = '<a href="\1">\1</a>';
    $text= preg_replace($pattern,$replacement,$text);
    return $text;
    //    return preg_replace("/(https?:\/\/[a-zA-Z0-9\.\/:%,!#~*@&_-]+)/" , "\\1" , $text);
}


function keywordChangeBold($text) {
    $pattern = '/\[(.+)\]/u';
    $replacement = '<span title="\1" class="word-key">\1</span>';
    $text= preg_replace($pattern,$replacement,$text);
    return $text;
}

function tripText($text) {
    $pattern = '/{([^}]*)}/u';
    $replacement = '<span title="\1" class="word-trip">\1</span>';
    $text= preg_replace($pattern,$replacement,$text);
    return $text;
}

?>

