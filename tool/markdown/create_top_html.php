<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける

    使用例:    
        php insert_h1_link.php <マークダウンhtmlファイル名>
                                 <テンプレートhtmlファイル名>
                                 <リンク先のhtmlファイル名の先頭部分>
                                 <出力先ファイル名>
        php insert_h1_link.php swift_memo.html _iOS_swift_top.html iOS_swift_　./iOS_swift/iOS_swift_top.html

    入力
        swift_memo.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        swift_template.html  
            テンプレート。このファイルにswift_memo.htmlの各h1ブロックを挿入してhtmlファイルとして出力する。
    出力
        swift_<h1の名前>.html  (たくさん)
 */

require_once("../Library/phpQuery-onefile.php");

if ($argc < 5) {
    exit("not enought input html\nphp insert_h1_link.php <マークダウンhtmlファイル名> <テンプレートのtopページ> <リンク先のhtmlファイル名の先頭部分> <出力先フォルダパス>" );
}

// マークダウンで出力されたhtmlファイルを h1のid名をリンク名としてaタグを作成し、topHtmlファイルに挿入する
// <a href="hoge"></a>
function insertLinks($markdownFile, $link_html_head, $outputFile, $template) {

    $html = file_get_contents($markdownFile);

    // Get DOM Object
    $dom = phpQuery::newDocument($html);

    $tags = $dom["div.toc > ul > li"];

    foreach($tags as $tag) {
        foreach( pq($tag)[">a"] as $element) {
            $topKey = pq($element)->attr("href");
            $topKey = str_replace("#", "", $topKey);
            $fileName = $link_html_head . $topKey . ".html";
            pq($element)->attr("href", $fileName);
        }

        $ul_tag = pq($tag)[">ul"];
        if (count($ul_tag) > 0) {
            tracUlTree($ul_tag, $fileName, 1);
        }
    }

    // 置換チェック用
    $tags = $dom["div.toc > ul > li"];
    print($tags);

    // ファイル出力
    $fp = fopen($outputFile, "w");
    fputs($fp, $template["head"]);
    fputs($fp, $tags);
    fputs($fp, $template["tail"]);
    fclose($fp);
}

function tracUlTree($tag, $fileName, $nest) {
    $li_tags = $tag["> li"];

    foreach($li_tags as $li_tag) {
        foreach( pq($li_tag)[">a"] as $element) {
            pq($element)->attr("href", $fileName . pq($element)->attr("href"));
        }

        $ul_tags = pq($li_tag)[">ul"];
        if (count($ul_tags) > 0) {
            tracUlTree($ul_tags, $fileName, $nest);
        }
    }
}



// swift_template.html ファイルを insert point の行を境にして２つの配列に分ける
function readTemplate($templateFile) {
    if (! ($file = file_get_contents($templateFile))) {
        exit("couldn't open inputfile!");
    }

    $template = array();
    if ($pos = strpos($file, "*** insert point ***")) {
        $template['head'] = substr($file, 0, $pos);
        $template['tail'] = substr($file, $pos + strlen("*** insert point ***"));
    }
    return $template;
}


$template = readTemplate($argv[2]);
insertLinks($argv[1], $argv[3], $argv[4], $template);

?>