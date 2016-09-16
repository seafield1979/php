<?php

function pn($str) {
    print($str . "\n");
}

function test_errorlog() {
    error_log("hogehoge");      // コンソールから実行したら標準出力に表示された
}

           

function test_cast() {
// 型変換
pn(1);
$int1 = 100;
pn(gettype($int1));

// integer -> string
pn(2);
$str1 = (string)$int1;
pn(gettype($str1));

// string -> integer
pn(3);
$int1 = (int)$str1;
$int1 = intval($str1);
pn(gettype($int1));

// float -> integer
pn(4);
$int2 = (int)1.1234;
$int2 = intval(1.2345);
pn(gettype(1.2345));
pn(gettype($int2));

pn(5);
$int1 = (int)1.2345;
$int2 = intval(1.2345);
pn(gettype($int1) . " " . gettype($int2));  // integer integer
}

test_errorlog();

?>
