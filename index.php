<?php

require_once 'functions.php';
setupEncodeing();
setDB();
getAccessToken();

if(isset($_GET['pre'])) echo "<pre>";



$err = array();
$par = array();

$lecture_list = new LectureList();

//print_r($lecture_list);


if(isset($_GET['d'])) {
    $lecture_list->addDebugPage();
}
// $lecture_list->addTempPage();


if(isset($_GET['pre'])) echo "</pre></pre></pre></pre>";

?>


<?php htmlHeader("top");?>
<body>
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a href="./" class="navbar-brand">JNote-TDU</a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
          <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse" id="navbar-main">
        <ul class="nav navbar-nav">
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="other">その他<span class="caret"></span>
          </a>
            <ul class="dropdown-menu" aria-labelledby="cource">
              <li><a tabindex="-1" href="./bb?id=10000">報告・提案</a></li>
              <li><a tabindex="-1" href="./bb?id=10001">更新ログ</a></li>
              <li><a tabindex="-1" href="http://twitter.com/arzzup" target="_blank">作者Twitter</a></li>
            </ul>
          </li>
          <li><a href="./prev">過去のノート</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <?php
          if ($login) {
    echo $e =<<<EOF
                <li><a href="https://twitter.com/{$access_token['screen_name']}" target="_blank">{$access_token['screen_name']}</a></li>
                <li><a href="./logout.php">ログアウト</a></li>
EOF;
} else
    echo $e = '<li><a href="oauth/oauth_start">Twitterログイン</a></li>';
?>
        </ul>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="bs-docs-section">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-header">
            <h1 id="type">JNote -ジェンガノート-</h1>
          </div>
        </div>
      </div>
      <!-- TitleHead -->
      <div class="row">
        <div class="col-lg-8">
          <h2 class="type-blockquotes">ジェンガノートとは</h2>
          <blockquote>
            <p>
              同じ講義を受けている学生同士で情報共有が出来るオンライン掲示板です<br /> キーワード・単語や授業のポイントの相互分析・整理が出来ます<br /> 掲示板を使用により、講義に対する疑問点の解決や共有をすることが出来ます<br /> 補足知識をしあう、補足資料(WebページURL)の共有することも可能
            </p>
            <small><cite title="Source Title"></cite> </small>
          </blockquote>
        </div>
        <div class="col-lg-4">
          <h2 class="type-blockquotes">使い方</h2>
          <blockquote>
            <p>
              Twitter連携によってアカウント管理をしているため<br /> 個人のアカウントでのログインが必要となります<br /> (閲覧のみであればログイン不必要)<br />
            </p>
            <small><cite title="Source Title"></cite> </small>
          </blockquote>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div id="sw-time-table">
          <div class="btn-toolbar" id="select-term">
            <div class="btn-group">
              <button class="btn btn-default" term="1" type="button">1限</button>
              <button class="btn btn-default" term="2" type="button">2限</button>
              <button class="btn btn-default" term="3" type="button">3限</button>
              <button class="btn btn-default" term="4" type="button">4限</button>
              <button class="btn btn-default" term="5" type="button">5限</button>
            </div>
            <div class="btn-group">
              <button class="btn btn-default" term="6" type="button">6限</button>
              <button class="btn btn-default" term="7" type="button">7限</button>
            </div>
            <div class="btn-group">
              <button class="btn btn-primary" term="day" type="button">ALL</button>
            </div>
          </div>
        </div>

        <?php $lecture_list->printListDivs();?>

      </div>
    </div>
  </div>

</body>
</html>
