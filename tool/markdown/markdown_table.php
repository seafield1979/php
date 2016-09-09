<?php
/*
使用方法
    php makrdown_table.php [行数] 入力ファイル名
例:
    php markdown_table.php 2 input.txt


入力データ(テキスト)
    aaa bbb
    ccc ddd
    eee fff
    ...

出力データ
    |header1|header2|
    |!--|!--|
    aaa | bbb
    ccc | ddd
    eee | fff

 */

function create_table($header_num, $input_file) 
{
    $file_array = file($input_file);
    if (! $file_array) {
        return -1;
    }

    // ヘッダー出力
    // |header1|header2|...|
    for ($cnt=0; $cnt <= $header_num; $cnt++) {
        if ($cnt == $header_num) {
            echo "|\n";
        }
        else {
            echo "| header" . ($cnt + 1) . " ";
        }
    }
    // |!--|!--|...|
    for ($cnt=0; $cnt <= $header_num; $cnt++) {
        if ($cnt == $header_num) {
            echo "|\n";
        }
        else {
            echo "| !--" . ($cnt + 1) . " ";
        }
    }
    

    foreach ($file_array as $str) {
        // スペースで分割する
        $split = preg_split("/[\s]+/", $str);
        
        foreach ($split as $index => $cell) {
            if ($cell === reset($split)) {
                // 最初
                echo $cell;
            }
            else if ($cell === end($split)) {
                // 最後
            }
            else {
                echo " | " . $cell;
            }
        }
        echo "\n";
    }
}

create_table($argv[1], $argv[2]);

?>