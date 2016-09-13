<?php
/**
    マークダウンファイル(*.md)から出力されたhtmlファイルをh1単位でさらに分割する
    マークダウンから出力されたh1にはそれぞれidが振られているので、このidをファイル名につける
    使用例:    
        php get_h1_block.php <マークダウンhtmlファイル名>
                             <テンプレートhtmlファイル名>
                             <フォルダパス>
                             <ファイル名>
        php get_h1_block.php swift_memo.html
                              _swift_template.html
                              ./swift_html/
                              swift_
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
// $outputDir:   出力フォルダパス 
// $outputFile:   出力ファイル名の先頭部分
function createHtmls($infile, $outputDir, $outputFile, $template)
{
    $file = file($infile);
    $getFlag = false;
    $block_cnt = 0;
    $h1_block_list[$block_cnt] = array();
    $key = null;
    $h1_block_body = "";

    foreach($file as $line) {
        // 記事の終点のタグを見つけたら終了
        if (strpos($line, "</article>") !== false) {
            break;
        }
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
    $title = "";
    // ブロック検索文字列
    // <li><a href="#uiwindow">UIWindow</a><ul> のような行を見つける
    $searchStr = "/<li><a href=\"#(.+)\">(.+)<\/a>/";
                
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
                $h1_block_list[$block_cnt]["title"] = $title;

                $block_cnt++;
                if ($block_cnt >= count($h1_block_list)){
                    break;
                }
                $mode = 1;
            }
            elseif (preg_match($searchStr, $line, $m) &&
                     $m[1]==$h1_block_list[$block_cnt]["key"] ) 
            {
                $link_html = $line;
                $title = $m[2];
                    
                // h1のブロックのリンクと同じ行に </li>があったら１行でhtmlが完結
                if (strrpos($line, "</li>") !== false) {
                    $h1_block_list[$block_cnt]["link"] = $link_html;
                    $h1_block_list[$block_cnt]["title"] = $title;
                    $block_cnt++;
                }
                else {
                    $mode = 2;  // </ul>を探すモード
                }
            }
            else {
                $link_html .= $line;
            }
        }
    }

    // デバッグ
    foreach ($h1_block_list as $key=>$value) {
        print "${key} : " . $value["key"] . ", title: " . $value["title"] . "\n";
    }

    // サイドバー用のタグを作成する
    $sideBarHtml = "<ul>\n";
    foreach ($h1_block_list as $value) {
        $sideBarHtml .= "<li><a href=\"" . $outputFile . $value["key"] . ".html\">" . $value["title"] . "</a></li>\n";
    }
    $sideBarHtml .= "</ul>\n";
    
    // 分割したhtmlを保存
    foreach ($h1_block_list as $key=>$value) {
        if (!($fp = fopen($outputDir . $outputFile . $value["key"] . ".html", "w"))) {
            break;
        }
        // *** insert point *** まで
        fputs($fp, $template[0]);

        // ページ内リンク
        fputs($fp, "<ul>\n");
        fputs($fp, $value['link']);
        fputs($fp, "\n</ul>\n");
        
        // 本体
        fputs($fp, $value["body"]);
        
        // *** insert point *** から *** sidebar point *** まで
        fputs($fp, $template[1]);

        // サイドバーに各htmlファイルのリンクを挿入
        fputs($fp, $sideBarHtml);
        fputs($fp, $template[2]);

        fclose($fp);
    }
}

// テンプレートのファイルを分割する
// 先頭 ~ insert point まで
// insert point ~ sidebar point まで
// sidebar point ~ 末尾 まで
function getTemplateBlocks($templateFile) {
    if (! ($file = file_get_contents($templateFile))) {
        exit("couldn't open inputfile!");
    }

    $blocks = array();
    $markStrLen1 = strlen("*** insert point ***");
    $markStrLen2 = strlen("*** sidebar point ***");

    if ($pos = strpos($file, "*** insert point ***")) {
        if ($pos2 = strpos($file, "*** sidebar point ***")) {
            // insert point までのブロックを $blocksに追加
            $blocks[0] = substr($file, 0, $pos - 1);
            // insert point から sidebar point までのブロックを追加
            $blocks[1] = substr($file, $pos + $markStrLen1, $pos2 - $pos - $markStrLen1);
            // sidebar point から末尾までのブロックを追加
            $blocks[2] = substr($file, $pos2 + $markStrLen2);
        }
    }

    return $blocks;
}

$template = getTemplateBlocks($argv[2]);
createHtmls($argv[1], $argv[3], $argv[4], $template);

?>