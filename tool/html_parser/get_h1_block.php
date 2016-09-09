<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける

    入力
        swift_memo.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        swift_template.html  
            テンプレート。このファイルにswift_memo.htmlの各h1ブロックを挿入してhtmlファイルとして出力する。
    出力
        swift_<h1の名前>.html  (たくさん)

    使用例:    
        php get_h1_block.php <マークダウンhtmlファイル名> <テンプレートhtmlファイル名>
        php get_h1_block.php swift_memo.html _swift_template.html
 */

if ($argc < 2) {
    exit("no input html");
}

// マークダウンで出力されたhtmlファイルを h1 タグ毎に分割し、テンプレートファイルに挿入してファイルに出力する
function createHtmls($infile, $template) {
    $file = file($infile);

    $getFlag = false;
    $fp = null;

    foreach($file as $line) {
        // <h1 id="hoge"></h1> の行を境界にファイルを作成する
        preg_match('/^<h1 id="(.*)"/', $line, $m);

        if (count($m) == 2) {
            if ($fp) {
                fputs($fp, $template["tail"]);
                fclose($fp);
            }
            if (!($fp = fopen("./swift_html/swift_" . $m[1] . ".html", "w"))) {
                return;
            }
            print("./swift_html/swift_" . $m[1] . ".html\n");
            fputs($fp, $template["head"]);

            $getFlag = true;
        }
        if ($getFlag) {
            fputs($fp, $line);
        }
    }
    fclose($fp);
}


// swift_template.html ファイルを insert point の行を境にして２つの配列に分ける
function readTemplate($templateFile) {
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
            // print("+++" . $line);
        }
        else {
            $tailPart .= $line;
            // print("---" . $line);
        }
    }
    return array("head"=>$headPart, "tail"=>$tailPart);
}

$template = readTemplate($argv[2]);
createHtmls($argv[1], $template);

?>