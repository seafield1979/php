<?php
  // テストレコードを出力する
  // 引数: $argv[1]  出力ファイル名(追記)
  // 出力するレコードは
  // INSERT INTO scores (name,score,pref) VALUES ("shutaro[数字]", [スコア], "[都道府県名]")

  // 出力するレコード数(Max1000件)
  $record_num = 10;

  // ファイルに書き込む(追加書き込み)
  $filename = $argv[1];
  if (!($fp = @fopen($filename, "a+"))) {
    exti("failed to open file\n");
  }

  $prefs = array(
    '青森','岩手','秋田','山形','宮城','福島'
  );

  if ($record_num > 1000) {
    $record_num = 1000;
  }

  for( $cnt=0; $cnt<$record_num; $cnt++ ) {
    $name = "shutaro" . rand(1,1000);
    $score = rand(0,10000) * 100;
    $pref = $prefs[array_rand($prefs)];

    fputs($fp, "INSERT INTO scores (name,score,pref) VALUES (\"$name\", $score, \"$pref\");\n") ;
  }

  fclose($fp);

?>