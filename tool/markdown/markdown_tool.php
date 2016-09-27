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
    $block_list = array();
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
            // １つ前のループのデータを$block_listに追加
            if ($key) {
                $block_list[$block_cnt] = array("key"=>$key, "body" => $h1_block_body);
                $h1_block_body = "";
                $block_cnt++;
            }

            $key = $m[1];
            $getFlag = true;

            // h1のタイトル文字列をスペースで分割した左側のみ残す
            $h1_block_body .= "<h1 id=\"$m[1]\">$m[2]</h1>";
        }
        else {
            if ($getFlag) {
                $h1_block_body .= $line;
            }
        }
    }
    $block_list[$block_cnt] = array("key"=>$key, "body" => $h1_block_body);

    
    // ページの見出しとサイドバーに表示する用のリンク部分を取得
    $block_cnt = 0;
    $html = file_get_contents($markdownHtmlFile);
    $dom = phpQuery::newDocument($html);
    $h1_tags = $dom[".toc > ul > li"];

    foreach($h1_tags as $tag) {
        $keyName = pq($tag)['> a']->attr("href");
        $keyName = substr($keyName, 1);     // 先頭の # を除去
        
        echo $block_list[$block_cnt]["key"] . " " . $keyName . "\n"; 
        if ($block_list[$block_cnt]["key"] == $keyName) {
            $block_list[$block_cnt]["link"] = pq($tag);
            $block_list[$block_cnt]["title"] = pq($tag)['> a']->text();
        }
        $block_cnt++;
    }
    return $block_list;
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
            $topKey = substr($topKey, 1);  // 先頭の # を除去
            $fileName = $link_html_head . $topKey . ".html";
            pq($element)->attr("href", $fileName);
        }

        $ul_tag = pq($tag)[">ul"];
        if (count($ul_tag) > 0) {
            tracUlTree($ul_tag, $fileName, 1);
        }
    }

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