<?php
  // テキストファイルの ■見出し の行を抽出する
  // 引数 argv[1]: 入力テキストファイル名
  //     argv[2]: 出力テキストファイル名

  // 読み込みファイルを開く
  $text = file($argv[1]);
  if ($text === FALSE) {
    exit("text doesn't opened.");
  }

  // ファイルに書き込む(追加書き込み)
  if (!($fp = @fopen($argv[2], "w"))) {
    exit("failed to open file\n");
  }

  fputs($fp, "■title\n");

  foreach ($text as $key => $value) {
    preg_match('/^■(.*)/', $value, $m);
    if (count($m) >= 2) {
      echo $m[1] . "\n";
      fputs($fp, $m[1] . "\n");
    }  
  }

  fclose($fp);

?>