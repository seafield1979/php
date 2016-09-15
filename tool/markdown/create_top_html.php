<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける

    使用例:    
        php create_top_html.php <マークダウンhtmlファイル名>
                                 <テンプレートhtmlファイル名>
                                 <リンク先のhtmlファイル名の先頭部分>
                                 <出力先ファイル名>
        php create_top_html.php swift_memo.html _iOS_swift_top.html iOS_swift_　./iOS_swift/iOS_swift_top.html

    入力
        swift_memo.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        swift_template.html  
            テンプレート。このファイルにswift_memo.htmlの各h1ブロックを挿入してhtmlファイルとして出力する。
    出力
        swift_<h1の名前>.html  (たくさん)
 */

require_once("../Library/phpQuery-onefile.php");
require_once("markdown_html_tool.php");

if ($argc < 5) {
    print("not enought input html\nphp insert_h1_link.php <マークダウンhtmlファイル名> <テンプレートのtopページ> <リンク先のhtmlファイル名の先頭部分> <出力先フォルダパス>" );
}

// マークダウンで出力されたhtmlファイルを h1のid名をリンク名としてaタグを作成し、topHtmlファイルに挿入する
// <a href="hoge"></a>
function createTopHtml($markdownFile, $link_html_head, $outputFile, $template, $sidebarLinks) {

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

    // 更新を反映させるために再取得
    $tags = $dom["div.toc > ul > li"];
    
    // 置換チェック用
    //print($tags);

    // ファイル出力
    $fp = fopen($outputFile, "w");
    fputs($fp, $template[0]);
    fputs($fp, $tags);
    fputs($fp, $template[1]);
    fputs($fp, $sidebarLinks);
    fputs($fp, $template[2]);
    fclose($fp);

    print("output ${outputFile} \n");
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

$template = getTemplate($argv[2]);
$block_list = makeH1BlockList($argv[1]);
$sidebarLinks = makeSidebarLinks($argv[3], $block_list);
createTopHtml($argv[1], $argv[3], $argv[4], $template, $sidebarLinks);

?>