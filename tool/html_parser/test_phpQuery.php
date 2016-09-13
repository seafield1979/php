<?php

require_once("../Library/phpQuery-onefile.php");

// Get Data Source
$html = file_get_contents("./_swift_memo.html");

// Get DOM Object
$dom = phpQuery::newDocument($html);

$top_li = $dom['div.toc > ul > li'];
$top_a = $top_li['> a'];

foreach( $top_a as $a) {
    print(pq($a)->attr("href", "hoge") . "\n");
}

foreach( $top_li as $li) {
    $a2 = pq($li)[">a"];
}



?>