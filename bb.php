<?php

require_once 'functions.php';
setupEncodeing();
setDB();
getAccessToken();

if(isset($_GET['pre'])) echo "<pre>";
$id_lecture = $_GET['id'];
//echo "[$id_lecture]";
if (!isset($id_lecture))
    echo "non set id";

$token = setToken();

$lecture = new Lecture($id_lecture);

if ($lecture->getName() == null) jump('', array('err' => 'nn'));

if(isset($_GET['pre']))
    print_r($lecture);

if(isset($_GET['pre'])) echo "</pre></pre></pre></pre>";
?>





<?php htmlHeader($lecture->getName());?>
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
          <!--
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="course">学科<span class="caret"></span>
          </a>
            <ul class="dropdown-menu" aria-labelledby="cource">
              <li><a tabindex="-1" href="./">FI</a></li>
              <li class="divider"></li>
              <li><a tabindex="-1" href="./">FR</a></li>
              <li><a tabindex="-1" href="./">FA</a></li>
            </ul>
          </li> -->
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="other">その他<span class="caret"></span>
          </a>
            <ul class="dropdown-menu" aria-labelledby="cource">
              <li><a tabindex="-1" href="./bb?id=10000">報告・提案</a></li>
              <li><a tabindex="-1" href="./bb?id=10001">更新ログ</a></li>
              <li><a tabindex="-1" href="www.twitter.com/arzzup" target="_blank">作者Twitter</a></li>
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
          <h1>
            <?=$lecture->getName();?>
          </h1>
        </div>
      </div>

      <div class="progress progress-striped" style="margin-bottom: 9px;">
        <div class="progress-bar progress-bar-success" style="width: <?=$lecture->getStateTimePro()?>%"></div>
      </div>

      <div class="row">
        <div class="col-lg-4">
          <div id="word-box-div" class="table-responsive">
            <table class="table table-striped-word table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>word</th>
                  <th>point</th>
                  <th>★</th>
                </tr>
              </thead>
              <tbody>
                <?php $lecture->printListWordDiv();?>
              </tbody>
            </table>
          </div>

          <div class="well">
            <form class="bs-example form-horizontal" action="post.php" method="POST">
              <fieldset>
                <div class="form-group">
                  <label class="col-lg-12 control-label">単語・用語</label>
                  <div class="col-lg-12">
                    <input type="text" name="word" class="form-control" id="inputWord" placeholder="Word">
                  </div>
                </div>
                <input type="hidden" name="mode" value="w" />
                <input type="hidden" name="id_user" value="<?=$access_token['screen_name']?>" />
                <input type="hidden" name="id_lecture" value="<?=$lecture->getId_lecture()?>" />
                <input type="hidden" name="token" value="<?=$token?>" />
                <div class="form-group">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-block btn-primary">送信</button>
                  </div>
                </div>
                <div class="checkbox" style="float: right;">
                  <label> <input type="checkbox" name="tweet" <?=(isset($_COOKIE['with_tweet']) ? "checked" :"")?>> 同時にツイートする
                  </label>
                </div>

              </fieldset>
            </form>
          </div>

        </div>

        <div class="col-lg-8">
          <div id="comment-box-div" class="bs-example table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>type</th>
                  <th>user-id</th>
                  <th>time</th>
                  <th>point</th>
                  <th>bu</th>
                </tr>
              </thead>
              <tbody>
                <?php $lecture->printListCommnetDiv();?>
              </tbody>
            </table>
          </div>

          <!--
          <div class="col-lg-1">
            <div class="bs-example">
              <div class="btn-group-vertical">
                <button type="button" id="sw-show-rate-all" class="btn btn-default">全件表示</button>
                <button type="button" id="sw-show-rate-20" class="btn btn-default">最新10件</button>
                <button type="button" id="sw-show-rate-10" class="btn btn-default">最新20件</button>
              </div>
            </div>
          </div>
           -->

          <div class="well">
            <form class="bs-example form-horizontal" action="post.php" method="POST">
              <fieldset>
                <div class="form-group">
                  <label for="textArea" class="col-lg-12 control-label">Textarea</label>
                  <div class="col-lg-12">
                    <textarea class="form-control" rows="3" name="text" id="textArea"></textarea>
                    <span class="help-block"></span>
                  </div>
                </div>
                <input type="hidden" name="mode" value="c" />
                <input type="hidden" id="c-type" name="type" value="n" />
                <input type="hidden" name="id_user" value="<?=$access_token['screen_name']?>" />
                <input type="hidden" name="id_lecture" value="<?=$lecture->getId_lecture()?>" />
                <input type="hidden" name="token" value="<?=$token?>" />
                <div class="form-group">
                  <div class="col-lg-6">
                    <div class="btn-group btn-group-fit" data-toggle="buttons-radio">
                      <button type="button" id="c-button-n" class="btn btn-default">ノーマル</button>
                      <button type="button" id="c-button-q" class="btn btn-default">質問</button>
                      <button type="button" id="c-button-r" class="btn btn-default">返答</button>
                      <button type="button" id="c-button-u" class="btn btn-default">URL</button>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <button type="submit" class="btn btn-block btn-primary">送信</button>
                  </div>
                </div>
                <div class="checkbox" style="float: right;">
                  <label> <input type="checkbox" name="tweet" <?=(isset($_COOKIE['with_tweet']) ? "checked" :"")?>> 同時にツイートする
                  </label>
                </div>

              </fieldset>
            </form>
          </div>

          <!--
          <div class="btn-group btn-group-fit" data-toggle="buttons-checkbox">
            <button type="button" id="nav-comment-" class="btn btn-default"></button>
            <button type="button" id="nav-comment-" class="btn btn-default">質問</button>
            <button type="button" id="nav-comment-" class="btn btn-default">返答</button>
            <button type="button" id="nav-comment-" class="btn btn-default">URL</button>
          </div>
 -->
          <div class="panel panel-default">
            <div class="panel-body">

              <div class="btn-group btn-gourp-fit" data-toggle="buttons-radio">
                <button type="button" id="sw-show-rate-all" class="btn btn-default">全件表示</button>
                <button type="button" id="sw-show-rate-20" class="btn btn-default">最新20件</button>
                <button type="button" id="sw-show-rate-10" class="btn btn-default">最新10件</button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
  <div id="hidden-div">
    <form id="hidden-post-form" target="script" action="" method="post">
      <input id="hidden-post-input" type="hidden" />
      <input type="hidden" name="id_user" value="<?=$access_token['screen_name']?>" />
      <input type="hidden" name="id_lecture" value="<?=$lecture->getId_lecture()?>" />
      <input type="hidden" name="token" value="<?=$token?>" />
    </form>
    <iframe name="script"></iframe>
  </div>

  <?php



  $jtext= "";
  $show = $_COOKIE['show'];
  if (isset($show) && $show =='all' || $show == '20' || $show == '10') {
$jtext .=<<<EOF
    $('#sw-show-rate-{$show}').click();
EOF;
}

if (!$login) {
$jtext .=<<<EOF
    $('.well').html('No Login');
EOF;
}

if (($id_lecture == '9999' || $id_lecture == '10001') && strtolower($access_token['screen_name']) != 'arzzup') {
$jtext .=<<<EOF
    $('.well').html('Not Master');
EOF;
}



echo $e =<<<EOF
<script type="text/javascript">
$(function() {
{$jtext}
});
</script>
EOF;



?>
</body>
</html>
