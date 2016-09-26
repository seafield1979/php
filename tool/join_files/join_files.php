<?php

/*
ファイルを結合する
--dir フォルダ名 オプションで指定フォルダ内のファイルを結合
--list 結合するファイルリスト名 オプションで指定したテキストファイルに書かれたファイルを結合する

使用例
    php join_files.php --dir ./PHP --extension md
    php join_files.php --list listfile.txt
 */

$longopt = array("list:", "dir:", "extension:");
$options = getopt("o:", $longopt);

// 結合先のファイル
$joinedText = "";


if (isset($options["list"])) {
    // 指定したファイル内のファイルリストをすべて結合
    $fileList = file($options["list"]);
    foreach ($fileList as $fileName) {
        echo $fileName;

        // 改行を除去
        $fileName = str_replace("\n", "", $fileName);
         $text = file_get_contents($fileName) . "\n\n";  // 前のファイルのテーブル等が繋がらないように改行を入れる
         $joinedText .= $text;
    }
} else if (isset($options["dir"])) {
    // ディレクトリ内の指定拡張しファイルをすべて結合

    $dirName = $options["dir"];
    if (isset($options["extension"])) {
        $extension = $options["extension"];
    } else {
        exit("no extension\n");
    }

    $dir = opendir($dirName);
    if (!$dir) {
        exit("not directory\n");
    }
    
    while (($file = readdir($dir)) !== false) {

        $filePath = $dirName . "/" . $file;
        if (is_file("$filePath")) {

            $file_info = pathinfo($file);
            if ($file_info['extension'] == $extension) {
                $text = file_get_contents($filePath);
                $joinedText .= $text . "\n\n";  // 前のファイルのテーブルやリストが繋がらないように改行を入れる
            }
        }
    }
}

// ファイルに書き込み
$outputName = "hoge.txt";
if (isset($options["o"])) {
    $outputName = $options["o"];
}
if(!($fp = fopen($outputName, "w"))) {
  exit("file open error");
}
fputs($fp, $joinedText);

?>