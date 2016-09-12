<?php
require_once "./mylog.php";

$array1 = array(1,2,3,4,5);

$hoge = print_r($array1, true);

mylog("hogehoge", null, true);
mylog("hogehoge2", "./log1.txt", true);

?>