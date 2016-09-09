<?php

$str = "<h1 id=\"hoge\">hoge</h1>";

// preg_match("/<h1>(.*)<\/h1>/", $str, $m);
preg_match("/^<h1 id=\"(.*)\">(.*)<\/h1>/", $str, $m);
print_r($m);

?>