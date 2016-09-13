<?php


$array = array();
$array[0] = '<li><a href="#property">プロパティ (Property)</a><ul>';
$array[1] = '<li><a href="#stored-property">保持型プロパティ (Stored Property)</a></li>';
$array[2] = '<li><a href="#computed-property">計算型プロパティ (Computed Property)</a></li>';
$array[3] = '<li><a href="#gettersetter">ゲッターセッター  getter/setter</a></li>';
$array[4] = '<li><a href="#dictionary">辞書型配列 dictionary</a></li>';

foreach ($array as $value) {
    preg_match("/<li><a href=\"#(.+)\">(.+)<\/a>/", $value, $m);

    if (count($m) >= 2) {
        print($m[1] . "\n");
    }
}

?>