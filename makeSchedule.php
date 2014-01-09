<?php

require_once 'functions.php';
setupEncodeing();
setDB();

//DB::checkMode();
echo "<pre>";
$data = loadJson('data_lecture_fi.json');

$timeStamp = time();

echo $timeStamp.PHP_EOL;
echo date('Y-m-d', $timeStamp).PHP_EOL;

$w_date = array();
for ($i = 0; $i < 7; $i++) {
	$ts = strtotime("+{$i}day");
	$date = date('Y-m-d', $ts);
	$w = date('w', $ts);
	$w_date[$w] = $date;
}

print_r($w_date);

foreach ($data as $datum) {
	print_r($datum);
	$w = substr($datum->term, 0, 1);
	$date = $w_date[$w];

	Lecture::insertLecture($datum->name, $datum->term,  $date);
}

?>