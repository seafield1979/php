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
        // 改行を除去
        $fileName = str_replace("\n", "", $fileName);
        $text = readMyFile($fileName);
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
                $text = readMyFile($filePath);
                $joinedText .= $text;
            }
        }
    }
}

// ファイルを読み込む
// 読み込めなかったらプログラム終了
function readMyFile($fileName) {
    $fp = fopen($fileName, "r");
    if ($fp === FALSE) {
        exit($fileName . " ファイルが開けませんでした\n");
    }
    $text = fread($fp, filesize($fileName));
    $text .= "\n\n"; // テーブル等が次のファイルに続くのを防ぐため改行を入れる
    fclose($fp);

    return $text;
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