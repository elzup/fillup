<?php
require_once 'functions.php';
setupEncodeing();
setDB();
getAccessToken();


$token = checkToken();

// print_r($_POST);
// print_r($_SESSION);
// print_r($token);

$id_lecture = $_POST['id_lecture'];
$id_user = $_POST['id_user'];
$data = $_POST['data'];

$type = substr($data, 0, 1);
$index = substr($data, 1);

if ($id_user != $access_token['screen_name'] ||
        in_array(null, array($id_lecture, $id_user, $type, $index)))
    exit;

$condition = array (
        'id_lecture' => $id_lecture,
        'index'      => $index
);

switch ($type) {
    case 'w':
        DB::inclement(tb_word, 'point', $condition);
        break;
    case 'c':
        DB::inclement(tb_comment, 'point', $condition);
        break;
}


?>