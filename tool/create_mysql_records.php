<?php

  // ファイルに書き込む(追加書き込み)
  $filename = "./mysql_records.txt";
  if (!($fp = @fopen($filename, "a+"))) {
      echo "failed to open file\n";
      return;
  }

  $prefs = array(
    '青森','岩手','秋田','山形','宮城','福島'
  );

  for( $cnt=0; $cnt<10; $cnt++ ) {
    $name = "shutaro" . rand(1,1000);
    $score = rand(0,10000) * 100;
    $pref = $prefs[array_rand($prefs)];

    fputs($fp, "INSERT INTO scores (name,score,pref) VALUES (\"$name\", $score, \"$pref\");\n") ;
  }

  fclose($fp);

?>