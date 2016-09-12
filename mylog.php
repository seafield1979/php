<?php

date_default_timezone_set('Asia/Tokyo');



// ログを出力する
// 出力先 /var/log/php/mylog_[日付].txt
// $message      ログメッセージ
// $file         ログファイル（nullならデフォルトの名前)
// $print_time   ログの先頭に日付をつけるかどうか
function mylog($message, $file, $print_time){
    if (isset($file) && !(is_null($file))) {
        $file_path = $file;
    }
    else {
        // ユーザーのホームディレクトリ ~/ は使えない
        $file_path = "/Users/shutaro/log/php/mylog_" . date("Y-m-d") . ".txt";
    }

    if ($print_time) {
        $time = date("[H:i:s] ");
        $message = $time . $message;
    }

    $fp = fopen($file_path, "a+");
    
    fputs($fp, $message . "\n");
    
    fclose($fp);
}

?>
