<?php
// $argv[1]  解析したいhtmlファイル名
// 使用方法:
//   php get_swift.php [解析したいhtmlファイル名] > 出力ファイル名


require_once '../Library/simple_html_dom.php';


function main($htmlFile) 
{
    // HTMLファイルから読み込む
    // 改行が消える
    //$html = file_get_html( $argv[1] );
    // 改行がそのまま
    $html = file_get_html( $htmlFile, false, null, -1, -1, true, true, DEFAULT_TARGET_CHARSET, false);

    $tags = $html->find("div.toc ul li");
    foreach($tags as $tag) {
        $tags2 = $tag->find("ul li");
        if (count($tags2) == 0) {
            // echo $tag . "no ul!\n";
            foreach( $tag->find("a") as $element) {
                //$element->src = str_replace($element->href, "hoge", $element->src);
                $element->href = $element->href;
                // echo $element . "\n";
            }
            //echo $tag;
        }
        else {
            foreach($tags2 as $tag2) {
                foreach($tag2->find("a") as $element) {
                    // echo $element->href . "\n";
                }
            }
        }
        // echo $tag . "\n"; 
    }

    $tags = $html->find("div.toc ul li");
    foreach($tags as $tag) {
        $tags2 = $tag->find("ul li");
        if (count($tags2) == 0) {
            echo $tag . "no ul!\n";
            foreach( $tag->find("a") as $element) {
                echo $element . "\n";
            }
        }
    }
}


if($argc < 2) {
    exit("no html file");
}
main($argv[1]);

?>