<?php
$htmlParam = array();

$id_bb = $_GET['id'];

$htmlParam['lecture_name'] = "コンパイラ";
?>


<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title><?=$htmlParam['lecture_name']?> - 穴埋めチェック</title>
	<link rel="stylesheet" href="style/style.css" media="all" />
</head>
<body>
<div id="wrapper">
	<div id="header">
	<div id="lecture_name">講義名</div>
	<div id="lecture_details">
	X月Y日Z曜W限 <br>
	サブタイトルサブタイトル
	</div>
	<div id="lecture_datas"></div>
	<ul><li>count01: 1</li>
	<li>count02: 3</li>
	<li>count03: 200</li></ul>
	</div>
	<div id="contents">
	<div id="fillup_words_box">
	FillUpWords
		<div id="words_table"></div>
	</div>
	<div id="comments_box"></div>
	Comments
	<div id="comments_table"></div>
	<div id="forms_div"></div>
	</div>
	</div>
	<div id="footer"></div>
</div>

</body>
</html>