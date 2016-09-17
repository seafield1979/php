<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルから記事部分を抜き出し1つのhtmlファイルを出力する

    使用例:    
        php create_one_page_html.php <マークダウンhtmlファイル名>
                                 <テンプレートhtmlファイル名>
                                 <出力先ファイル名>
                                 <タイトルに表示するテキスト>
        php create_one_page_html.php _markdown.html
                            _markdown_template.html
                            ./markdown/_markdown_top.html
                            "マークダウン"

    入力
        _markdown.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        _markdown_template.html  
            テンプレートファイル。このファイルの記事部分にマークダウンのhtmlの記事を挿入する。
    出力
        swift_<h1の名前>.html  (たくさん)
 */

require_once("../Library/phpQuery-onefile.php");
require_once("markdown_tool.php");

if ($argc < 4) {
    print("not enought input html\nphp insert_h1_link.php <マークダウンhtmlファイル名> <テンプレートのtopページ> <出力先フォルダパス>" );
}

// マークダウンで出力されたhtmlファイルを h1のid名をリンク名としてaタグを作成し、topHtmlファイルに挿入する
// <a href="hoge"></a>
function createOneTopHtml($markdownFile, $outputFile, $template) {

    $html = file_get_contents($markdownFile);

    // Get DOM Object
    $dom = phpQuery::newDocument($html);

    // h1ブロックの不要な文字を削除する処理
    $tags = $dom["div.toc > ul > li > a"];
    foreach($tags as $element) {
        // h1ブロックのテキスト部分をスペースで分割して左側だけ残す
        $titles = explode(" ", pq($element)->text());
        pq($element)->text($titles[0]);
    }

    $tag_article = $dom["article#content"];
    $tag_toc = $dom["div.toc"];

    // ファイル出力
    $fp = fopen($outputFile, "w");
    fputs($fp, $template[0]);
    fputs($fp, $tag_article->html());
    fputs($fp, $template[1]);
    fputs($fp, $tag_toc);
    fputs($fp, $template[2]);
    fclose($fp);

    print("output ${outputFile} \n");
}

$markdownHtml = $argv[1];
$templateHtml = $argv[2];
$topHtmlName = $argv[3];
$titleText = $argv[4];

$template = getTemplate($templateHtml, $topHtmlName, $titleText);
createOneTopHtml($markdownHtml, $topHtmlName, $template);

?>