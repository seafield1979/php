<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける
    使用例:    
        php create_page_html.php <マークダウンhtmlファイル名>
                             <テンプレートhtmlファイル名>
                             <html出力フォルダパス>
                             <ファイル名>
        php create_page_html.php swift_memo.html
                              _swift_template.html
                              ./swift_html/
                              swift_
    入力
        _swift_memo.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        _swift_template.html  
            テンプレート。このファイルにswift_memo.htmlの各h1ブロックを挿入してhtmlファイルとして出力する。
    出力
        swift_<h1の名前>.html  (たくさん)

 */
require_once("markdown_html_tool.php");

if ($argc < 5) {
    exit("not enought parameters!\n\n");
}

$template = getTemplate($argv[2]);
$block_list = makeH1BlockList($argv[1]);
createHtmls($argv[3], $argv[4], $block_list, $template);


?>