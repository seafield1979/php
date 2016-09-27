<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける

    使用例:    
        php create_htmls.php <マークダウンhtmlファイル名>
                            <テンプレートhtmlファイル名>
                            <html出力フォルダパス>
                            <リンク先のhtmlファイル名(の先頭部分)>
                            <top htmlファイル名>
                            <タイトルに表示するテキスト>

        php create_htmls.php swift_memo.html
                            _template.html
                            ./iOS_swift/
                            iOS_swift_
                            _iOS_swift_top.html
                            "Swift プログラミング"
    出力
        swift_<h1の名前>.html  (たくさん)
        _top_swift.html (1つ)
 */

require_once("markdown_tool.php");

 $options = getopt("a",
                     array("mdHtml:", "template:", "topTemplate:", "outputDir:", "htmlName:", "topHtmlName:", "titleText:"));
if (count($options) < 7) {
    exit("not enought parameters\n");
}

$markdownHtml = $options["mdHtml"];
$templateHtml = $options["template"];
$templateTop = $options["topTemplate"];
$outputDir = $options["outputDir"];
$htmlName = $options["htmlName"];
$topHtmlName = $options["topHtmlName"];
$titleText = $options["titleText"];

// 分割htmlファイル出力
$template = getTemplate($templateHtml, $topHtmlName, $titleText);
$block_list = makeH1BlockList($markdownHtml);
createHtmls($outputDir, $htmlName, $block_list, $template);


// top htmlファイル出力
$sidebarLinks = makeSidebarLinks($htmlName, $block_list);
createTopHtml($markdownHtml, $htmlName, $outputDir . $topHtmlName, $template, $sidebarLinks);

?>