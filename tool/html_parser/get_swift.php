<?php
// $argv[1]  解析したいhtmlファイル名
// 使用方法:
//   php get_swift.php [解析したいhtmlファイル名] > 出力ファイル名


require_once 'simple_html_dom.php';


function main($htmlFile) 
{
    // HTMLファイルから読み込む
    // 改行が消える
    //$html = file_get_html( $argv[1] );
    // 改行がそのまま
    $html = file_get_html( $htmlFile, false, null, -1, -1, true, true, DEFAULT_TARGET_CHARSET, false);

    $tags = $html->find("h1 #");
    foreach($tags as $tag) {
        echo $tag . "\n";
    }
}


if($argc < 2) {
    exit("no html file");
}
main($argv[1]);

?>