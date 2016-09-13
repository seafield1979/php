<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける
    使用例:    
        php get_h1_block.php <マークダウンhtmlファイル名>
                             <テンプレートhtmlファイル名>
                             <フォルダパス&ファイル名>
        php get_h1_block.php swift_memo.html _swift_template.html ./swift_html/swift_

    入力
        swift_memo.html
            マークダウンから出力したhtmlファイル。このファイルのh1タグ毎にファイルを出力する
        swift_template.html  
            テンプレート。このファイルにswift_memo.htmlの各h1ブロックを挿入してhtmlファイルとして出力する。
    出力
        swift_<h1の名前>.html  (たくさん)

 */

if ($argc < 4) {
    exit("not enought parameters!\nphp get_h1_block.php <markdown html file> <template html file> <directory&filename>");
}

// マークダウンで出力されたhtmlファイルを h1 タグ毎に分割し、テンプレートファイルに挿入してファイルに出力する
// $infile:  マークダウンのhtmlファイル
// $template:   テンプレートhtmlを分割した配列("head":前半部分, "tail":後半部分)
// $outputfile:   出力ファイル名(フォルダ＆ファイル名先頭)
function createHtmls($infile, $template, $outputfile) 
{
    $file = file($infile);
    $getFlag = false;
    $block_cnt = 0;
    $h1_block_list[$block_cnt] = array();
    $key = null;
    $h1_block_body = "";

    foreach($file as $line) {
        // <h1 id="hoge"></h1> の行を境界にファイルを作成する
        preg_match('/^<h1 id="(.*)"/', $line, $m);

        if (count($m) == 2) {
            // １つ前のループのデータを$h1_block_listに追加
            if ($key) {
                $h1_block_list[$block_cnt] = array("key"=>$key, "body" => $h1_block_body);
                $h1_block_body = "";
                $block_cnt++;
            }

            $key = $m[1];

            // print($outputfile . $m[1] . ".html\n");
            
            $getFlag = true;
        }
        if ($getFlag) {
            $h1_block_body .= $line;
        }
    }
    $h1_block_list[$block_cnt] = array("key"=>$key, "body" => $h1_block_body);


    // リンク部分を取得
    $mode = 0;
    $block_cnt = 0;
    $link_html = "";
    // ブロック検索文字列
    // <li><a href="#uiwindow">UIWindow</a><ul> のような行を見つける
    $searchStr = "<li><a href=\"#" . $h1_block_list[$block_cnt]["key"] . "\">";
                
    foreach($file as $line) {
        if ($mode == 0) {
            // リンクの
            if (strpos($line, '<div class="toc">') !== false) {
                $mode = 1;
            }
        }
        elseif ($mode > 0) {
            // ここでリンクのリストを取得する
            if (strpos($line, '</div>') !== false) {
                // ファイルが開けなかったので終了
                break;
            }
            if ($mode == 2 && $line == "</ul>\n") {
                // 保存
                $link_html .= $line;
                $h1_block_list[$block_cnt]["link"] = $link_html;

                // 次のブロック検索文字列
                $block_cnt++;
                if ($block_cnt >= count($h1_block_list)){
                    break;
                }
                $searchStr = "<li><a href=\"#" . $h1_block_list[$block_cnt]["key"] . "\">";
                $mode = 1;
            }
            elseif (strpos($line, $searchStr) !== false ) {
                $link_html = $line;
                    
                // h1のブロックのリンクと同じ行に </li>があったら１行でhtmlが完結
                if (strrpos($line, "</li>") !== false) {
                    $h1_block_list[$block_cnt]["link"] = $link_html;
                    $block_cnt++;
                }
                else {
                    $mode = 2;  // </ul>を探すモード
                }
                // 次のブロック検索文字列
                $searchStr = "<li><a href=\"#" . $h1_block_list[$block_cnt]["key"] . "\">";
            }
            else {
                $link_html .= $line;
            }
        }
    }

    // デバッグ
    foreach ($h1_block_list as $key=>$value) {
        print "${key} : ${value["key"]},  link: " . strlen($value["link"]) . "  body: " . strlen($value["body"]) . "\n";
    }

    // 分割したhtmlを保存
    foreach ($h1_block_list as $key=>$value) {
        if (!($fp = fopen($outputfile . $value["key"] . ".html", "w"))) {
            break;
        }
        // *** insert point *** まで
        fputs($fp, $template["head"]);

        // ページ内リンク
        fputs($fp, "<ul>\n");
        fputs($fp, $value['link']);
        fputs($fp, "\n</ul>\n");
        
        // 本体
        fputs($fp, $value['body']);
        
        // *** insert point *** の後ろ
        fputs($fp, $template["tail"]);

        fclose($fp);
    }
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

// function get_link_list()

$template = readTemplate($argv[2]);
createHtmls($argv[1], $template, $argv[3]);

?>