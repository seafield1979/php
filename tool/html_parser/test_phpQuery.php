<?php

require_once("../Library/phpQuery-onefile.php");

// var_dump の出力を戻り値として返す
function my_dump($val, $max_len) {
    ob_start();
    var_dump($val);
    $str = ob_get_contents();
    ob_end_clean();

    if (isset($max_len)) {
        return substr($str, 0, $max_len);
    }

    return $str;
}

// Get Data Source
$html = <<<html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <div id="top">
    <div id="hoge_id">
        <p>test id1!!</p>
    </div>
    <div class="hoge">
        <p>test class1!!</p>
    </div>
    <div class="hoge2">
        <p>test class2!!</p>
    </div>    
    <div name="shutaro">
        <p>test attr!!</p>
    </div>
    <div name="naotaro">
        <p>test attr!!</p>
    </div>
    <ul class="ul1">
        <li>li1</li>
        <li>li2</li>
        <li>li3</li>
        <li>li4</li>
        <li>li5
            <ul class="ul2">
                <li><a href="hoge.html">hoge.html</a></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </li>
    </ul>
  </div>
</body>
</html>
html;

// print($html);

// Get DOM Object
$dom1 = phpQuery::newDocumentHTML($html);

// セレクター (['div.toc']とか)で取得できるのはDOMオブジェクトの配列形式
$tag_top = $dom1['.ul1 > li'];

$mode = 4;

switch($mode) {
case 0:
    // 基本
    // PHP Queryオブジェクトをforeachで回した時に取得できるのは DOM Element オブジェクト
    // PHP Queryオブジェクトとして扱うには pq()で変換してから使用する
    $tag_li = $tag_top['.ul1 > li'];
    foreach($tag_li as $li) {
        // pq()で囲むと phpQueryObject になる
        print(pq($li) . "\n");
        // DOM Element Object
        print($li->node . "\n");
        break;
    }
    break;
case 1:
    // そのまま、html()、text()
    $tag_hoge = $tag_top['.hoge'];
    $tag_hoge->attr("name", "name_hoge");
    print("-------------------\n");
    print($tag_hoge . "\n");
    
    print($tag_hoge->html() . "\n");
    
    print($tag_hoge->text() . "\n");
    //set 
    print("-------------------\n");
    // html(<new html>)
    $tag_hoge->html("<div id='newhoge'>new hoge</div>");
    print($tag_top['.hoge'] . "\n");
    print($tag_top['#newhoge'] . "\n");
    break;

case 2:
    // 属性のget/set
    $tag_hoge = $tag_top['.hoge'];
    print("-------------------\n");
    $tag_hoge->attr("name", "hoge1");
    print($tag_hoge->attr("name") . "\n");

    print("-------------------\n");
    // remove attr
    print($tag_hoge . "\n");
    $tag_hoge->removeAttr("name");
    print($tag_hoge . "\n");

    break;

case 3:
    // selector
    // element tag_name
    $tag_p = $tag_top['p'];
    print("-------------------\n");
    print($tag_p . "\n");

    // id #
    print("-------------------\n");
    $tag_id = $tag_top['#hoge_id'];
    print($tag_id . "\n");
    
    // class .
    print("-------------------\n");
    $tag_class = $tag_top['.hoge'];
    print($tag_class . "\n");
    
    // attr
    print("-------------------\n");
    // have
    $tag_attr = $tag_top['[href]'];
    print($tag_attr . "\n");
    // attr=value
    $tag_attr = $tag_top['[name=shutaro]'];
    print($tag_attr . "\n");
    // attr!=value
    $tag_attr = $tag_top['[name!=shutaro]'];
    print($tag_attr . "\n");

    // multiple selector
    // selector1,selector2,...
    print("-------------------\n");
    $tag_mul = $tag_top['div.hoge,div.hoge2'];
    print($tag_mul . "\n");

    // only child (not grandchild)
    print("only child-------------------\n");
    $tag_child = $tag_top['.ul1 > li'];
    print("count:" . count($tag_child) . "\n");
    print($tag_child . "\n");

    // filter
    print("filter-------------------\n");
    // fist element
    $tag_filter = $tag_top['.ul1 > li:first'];
    print($tag_filter . "\n");
    // last element
    $tag_filter = $tag_top['.ul1 > li:last'];
    print($tag_filter . "\n");
    // first child (自分の親の最初の子要素)
    $tag_filter = $tag_top['.ul1 > li:first-child'];
    print($tag_filter . "\n");
    print("parent-------------------\n");
    // parent
    $tag_filter = $tag_top['.ul2:parent'];
    print($tag_filter . "\n");
    break;
case 4:
    // PHPQueryオブジェクトとDOMElementオブジェクト
    // セレクタで取得できるのは
    $tag_li = $tag_top['ul.ul1 > li'];

    ob_start();
    var_dump($tag_li);
    $str = ob_get_contents();
    ob_end_clean();

    print(substr($str, 0, 100));

    // foreachで取り出した子要素はPHP Queryオブジェクトではないので pq()でPHP Queryオブジェクトに変換する
    foreach( $tag_li as $element) {
        print(pq($element)->html());
        print( my_dump($element, 100) . "\n");
    }
    break;
}

?>