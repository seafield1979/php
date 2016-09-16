<?php
/*
  マークダウンのhtmlを処理するための関数
*/
require_once("../Library/phpQuery-onefile.php");

/* マークダウンで出力されたhtmlファイルを h1 タグ毎に分割し配列形式で出力する
     $markdownHtmlFile:
     @return : array( array("key"=>h1ブロックのid名,
                      "title"=>h1ブロックのタイトル,
                      "body"=>h1ブロックで囲まれた範囲のhtml本体,
                      "link"=>htmlの先頭部にあるページ内のリンク),
                      array...
 */
function makeH1BlockList($markdownHtmlFile)
{
    $file = file($markdownHtmlFile);
    $h1_block_list = array();
    $getFlag = false;
    $block_cnt = 0;
    $key = null;
    $h1_block_body = "";

    foreach($file as $line) {
        // 記事の終点のタグを見つけたら終了
        if (strpos($line, "</article>") !== false) {
            break;
        }
        // <h1 id="hoge"></h1> の行を境界にファイルを作成する
        preg_match('/^<h1 id="(.*)">(.*)<\/h1>/', $line, $m);

        if (count($m) == 3) {
            // １つ前のループのデータを$h1_block_listに追加
            if ($key) {
                $h1_block_list[$block_cnt] = array("key"=>$key, "body" => $h1_block_body);
                $h1_block_body = "";
                $block_cnt++;
            }

            $key = $m[1];
            $getFlag = true;

            // h1のタイトル文字列をスペースで分割した左側のみ残す
            $titles = explode(" ", $m[2]);
            $h1_block_body .= "<h1 id=\"$m[1]\">$titles[0]</h1>";
        }
        else {
            if ($getFlag) {
                $h1_block_body .= $line;
            }
        }
    }
    $h1_block_list[$block_cnt] = array("key"=>$key, "body" => $h1_block_body);

    
    // ページの見出しとサイドバーに表示する用のリンク部分を取得
    $mode = 0;
    $block_cnt = 0;
    $link_html = "";
    $title = "";
    // ブロック検索文字列
    // <li><a href="#uiwindow">UIWindow</a><ul> のような行を見つける
    $searchStr = "/<li><a href=\"#(.+)\">(.+)<\/a>(.*)/";
                
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
                // h1のタイトル スペースで２つ以上に分割できたら左側の文字列だけ取得する
                $titles = explode(" ", $m[2]);
                $title = $titles[0];

                $link_html = "<li><a href=\"#$m[1]\">$title</a>$m[3]";

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
    return $h1_block_list;
}


/*
    H1ブロック情報($block_list)を元に各htmlファイルを作成する
    $outputDir   htmlを出力する先のフォルダパス 例:"./swift/"
    $ouptuptFile  htmlファイル名の先頭部分  例:"swift_"
    $block_list  マークダウンのhtmlを解析して出力した各htmlファイルの情報
    $template    出力先のhtmlのテンプレート。配列形式([0~2] 各パートのhtml文字列)

    出力ファイル: 複数のhtmlファイル
 */
function createHtmls($outputDir, $outputFile, $block_list, $template) {

    // サイドバーに表示する各htmlのリンク
    $sideBarLinks = makeSidebarLinks($outputFile, $block_list);

    // 分割したhtmlを保存
    foreach ($block_list as $key=>$value) {
        if (!($fp = fopen($outputDir . $outputFile . $value["key"] . ".html", "w"))) {
            break;
        }
        // *** insert point *** まで
        fputs($fp, $template[0]);

        // ページ内リンク
        fputs($fp, "<ul>\n");
        fputs($fp, $value["link"]);
        fputs($fp, "\n</ul>\n");
        
        // 本体
        fputs($fp, $value["body"]);
        
        // *** insert point *** から *** sidebar point *** まで
        fputs($fp, $template[1]);

        // サイドバーに各htmlファイルのリンクを挿入
        fputs($fp, $sideBarLinks);
        fputs($fp, $template[2]);

        fclose($fp);
    }
}

/*
    サイドバー用に表示する各htmlへのリンクを作成する
    $fileTopStr   各htmlファイルの先頭部分
    $block_list   マークダウンのhtmlを解析して出力した各htmlファイルの情報

    @return  サイドバーに挿入するhtml文字列
 */
function makeSidebarLinks($outputFile, $block_list) {
    $sideBarLinks = "<ul>\n";
    foreach ($block_list as $value) {
        $sideBarLinks .= "<li><a href=\"" . $outputFile . $value["key"] . ".html\">" . $value["title"] . "</a></li>\n";
    }
    $sideBarLinks .= "</ul>\n";

    return $sideBarLinks;
}

/*
    テンプレートのファイルを分割する
    $templateFile   テンプレートファイル名
    $topHtmlName    カテゴリーのトップファイル名
    $titleText      タイトルタグに表示する文字列

    @return   array([0]  先頭 ~ insert point まで
                    [1]  insert point ~ sidebar point まで
                    [2]  sidebar point ~ 末尾 まで
                    )
 */
function getTemplate($templateFile, $topHtmlName, $titleText) {
    if (! ($html = file_get_contents($templateFile))) {
        exit("couldn't open inputfile!");
    }

    // タイトルを書き換えるためにPHP QueryでDOMを取得する
    // Get DOM Object
    $dom = phpQuery::newDocument($html);
    $tag_title_a = $dom["#boxB .category_top > a"];
    $tag_title_a->attr("href", $topHtmlName);
    $tag_title_a->text($titleText);    

    $html = (string)$dom;

    $blocks = array();
    $markStrLen1 = strlen("*** insert point ***");
    $markStrLen2 = strlen("*** sidebar point ***");

    if ($pos = strpos($html, "*** insert point ***")) {
        if ($pos2 = strpos($html, "*** sidebar point ***")) {
            // insert point までのブロックを $blocksに追加
            $blocks[0] = substr($html, 0, $pos - 1);
            // insert point から sidebar point までのブロックを追加
            $blocks[1] = substr($html, $pos + $markStrLen1, $pos2 - $pos - $markStrLen1);
            // sidebar point から末尾までのブロックを追加
            $blocks[2] = substr($html, $pos2 + $markStrLen2);
        }
    }
    return $blocks;
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

            // h1ブロックのテキスト部分をスペースで分割して左側だけ残す
            $titles = explode(" ", pq($element)->text());
            pq($element)->text($titles[0]);
        }

        $ul_tag = pq($tag)[">ul"];
        if (count($ul_tag) > 0) {
            tracUlTree($ul_tag, $fileName, 1);
        }
    }

    // 更新を反映させるために再取得
    //$tags = $dom["div.toc > ul > li"];
    
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


?>