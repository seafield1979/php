<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルから記事部分を抜き出し1つのhtmlファイルを出力する

    使用例:    
        php create_one_page_html.php <マークダウンhtmlファイル名>
                                 <テンプレートhtmlファイル名>
                                 <出力先ファイル名>
        php create_one_page_html.php _markdown.html _markdown_template.html　./markdown/_markdown_top.html

    入力
        _markdown.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        _markdown_template.html  
            テンプレートファイル。このファイルの記事部分にマークダウンのhtmlの記事を挿入する。
    出力
        swift_<h1の名前>.html  (たくさん)
 */

require_once("../Library/phpQuery-onefile.php");
require_once("markdown_html_tool.php");

if ($argc < 4) {
    print("not enought input html\nphp insert_h1_link.php <マークダウンhtmlファイル名> <テンプレートのtopページ> <出力先フォルダパス>" );
}

// マークダウンで出力されたhtmlファイルを h1のid名をリンク名としてaタグを作成し、topHtmlファイルに挿入する
// <a href="hoge"></a>
function createTopHtml($markdownFile, $outputFile, $template) {

    $html = file_get_contents($markdownFile);

    // Get DOM Object
    $dom = phpQuery::newDocument($html);

    $tag_article = $dom["article#content"];
    $tag_toc = $dom["div.toc"];

    // ファイル出力
    $fp = fopen($outputFile, "w");
    fputs($fp, $template[0]);
    fputs($fp, $tag_article);
    fputs($fp, $template[1]);
    fputs($fp, $tag_toc);
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
createTopHtml($argv[1], $argv[3], $template);

?>