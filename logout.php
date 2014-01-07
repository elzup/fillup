<?php
require_once 'functions.php';
session_start();
$_SESSION = array();
$source = $_SERVER['HTTP_REFERER'];
jump($source);
