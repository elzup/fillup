<?php

$filename_input = 'data_fi.txt';
$filename_output = 'data_lecture_fi.json';

require_once 'functions.php';
setupEncodeing();

echo "<pre>";
if (isset($_GET['c'])) {
    $filename = 'data_lecture_fi.json';
    $json = file_get_contents($filename);
    $data = json_decode($json);
    print_r($data);
    exit;
}






$input = file_get_contents($filename_input);
$input = convertEOL($input);
$lines = preg_split('/\n/u', $input);
array_pop($lines);
//print_r($lines);

$data = array();
$i = 0;

while ($i < count($lines)) {
    $term_code = $lines[$i++];
    $title = $lines[$i++];
    $teacher_room = $lines[$i++];

    $data[] = new L($term_code, $title, $teacher_room);
}

print_r($data);
$json = json_encode($data);
print_r($json);

file_put_contents($filename_output, $json);

class L {
    public $name, $tearcher, $room, $term, $code;
    public function __construct($term_code, $title, $teacher_room) {
        $this->name = $title;

        $tms = preg_split('/\|/u', $teacher_room);
        $this->tearcher = $tms[0]." ".$tms[1];
        $this->room = $tms[2];

        $tms = preg_split('/\|/u', $term_code);
        $this->term = strtocodeDay($tms[0]).$tms[1];
        $this->code = "$tms[2]";
        print_r($this);
    }
}
function strtocodeDay($str) {
    $lib = array_flip(explode(' ', '日 月 火 水 木 金 土'));
    return $lib[$str];
}

function convertEOL($string, $to = "\n")
{
    return preg_replace("/\n\r|\r\n|\r|\n/", $to, $string);
}

