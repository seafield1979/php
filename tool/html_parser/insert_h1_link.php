<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける

    使用例:    
        php insert_h1_link.php <マークダウンhtmlファイル名>
                                 <テンプレートhtmlファイル名>
                                 <リンク先のhtmlファイル名の先頭部分>
                                 <出力先フォルダ名>
        php insert_h1_link.php swift_memo.html _iOS_swift_top.html iOS_swift_　./iOS_swift/

    入力
        swift_memo.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        swift_template.html  
            テンプレート。このファイルにswift_memo.htmlの各h1ブロックを挿入してhtmlファイルとして出力する。
    出力
        swift_<h1の名前>.html  (たくさん)

 */

if ($argc < 5) {
    exit("not enought input html\nphp insert_h1_link.php <マークダウンhtmlファイル名> <テンプレートのtopページ> <リンク先のhtmlファイル名の先頭部分> <出力先フォルダパス>" );
}

// マークダウンで出力されたhtmlファイルを h1のid名をリンク名としてaタグを作成し、topHtmlファイルに挿入する
// <a href="hoge"></a>
function insertLinks($markdownFile, $top_file_name, $link_html_head, $outputDir, $topHtml) {
    $file = file($markdownFile);

    $fp = fopen($outputDir . $top_file_name, "w");
    fputs($fp, $topHtml["head"]);

    foreach($file as $line) {
        // <h1 id="hoge"></h1> の行を境界にファイルを作成する
        preg_match("/<h1 id=\"(.*)\">(.*)<\/h1>/", $line, $m);

        if (count($m) >= 3) {
            $link = "./" . $link_html_head . $m[1] . ".html";
            $atag = "<a href=\"$link\">$m[2]</a><br>\n";
            fputs($fp, $atag);
            print($m[1] . " " . $m[2] ."\n");
        }
    }

    fputs($fp, $topHtml["tail"]);
    fclose($fp);
}


// swift_template.html ファイルを insert point の行を境にして２つの配列に分ける
function readTopHtml($templateFile) {
    if (! ($file = file($templateFile))) {
        exit("couldn't open inputfile!");
    }

    $getFlag = false;
    $headPart = "";
    $tailPart = "";

    // テキストを insert point の行を境に分割する
    foreach ($file as $line) {
        // テキストの挿入ポイントを探す
        if ($getFlag == false) {
            if (strpos($line, "*** insert point ***") !== false){
                $getFlag = true;
                continue;
            }
            $headPart .= $line;
            print("+++" . $line);
        }
        else {
            $tailPart .= $line;
            print("---" . $line);
        }
    }
    return array("head"=>$headPart, "tail"=>$tailPart);
}


$topHtml = readTopHtml($argv[2]);
insertLinks($argv[1], $argv[2], $argv[3], $argv[4], $topHtml);

?>