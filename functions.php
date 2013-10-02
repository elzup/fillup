<?php

require_once 'config.php';
require_once 'c_Db.php';

function connectDb(){
    mb_language("uni");
    mb_internal_encoding("UTF-8");
    mb_http_input("auto");
    mb_http_output("utf-8");

    DB::$link = mysqli_connect(DSN, DB_USER, DB_PASS);
    mysqli_set_charset(DB::$link, "utf8")or die("ERROR charset");

    //     try {
    //         $dbh = new PDO(DB_DSN, DB_USER, DB_PASS);
    //     } catch (PDOException $e) {
    //         print('Error:'.$e->getMessage());
    //         die();
    //     }

    mysqli_select_db(DB::$link, DB_NAME)or die('selectError:'.mysql_error());
}

