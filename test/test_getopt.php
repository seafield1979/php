<?php
// getoptのサンプル
// -o outputfile -s  等のオプションを解析する

// テストモードを指定 -m [テンスモード番号]
$options = getopt("m:");
$mode = (int)$options["m"];

switch ($mode) {
    case 1:
        // オプション無し -a
        $options = getopt("a");
        if (isset($options["a"])){
            echo "ok\n";
        } else {
            echo "ng\n";
        }
        break;
    case 2:
        // オプション無し2つ -a -b
        $options = getopt("ab");
        if (isset($options["a"]) && isset($options["b"])) {
            echo "ok\n";
        } else {
            echo "ng\n";
        }
        break;
    case 3:
        // 引数ありオプション -a hoge.txt
        $options = getopt("a:");
        if (isset($options["a"])) {
            echo "ok " . $options["a"] . "\n";
        } else {
            echo "ng\n";
        }
        break;
    case 4:
        // 複合型 aとbとcオプションが
        // -a -b -c -d hoge.txt -e hoge2.txt -f hoge3.txt
        $options = getopt("m:abcd:e:f:");
        if (isset($options["a"]) && isset($options["d"]) && isset($options["c"])) {
            echo "ok " . $options["a"] . "\n";
        } else {
            echo "ng\n";
        }
        break;
    case 5:
        // 同じオプションを２つ設定すると配列形式になる
        // -a hoge -a hoge2
        echo "42---\n";
        $options = getopt("m:a:");
        if (is_array($options["a"])) {
            foreach($options["a"] as $value)  {
                echo "value=" . $value . "\n";
            }
        } else {
            echo "ng\n";
        }
        break;
    case 10:
        // longoption 長いオプション名 
        // コマンドラインから  --option
        // -m 10 --option1 
        $longoption = array(
            "option1"
            );
        $options = getopt("m:", $longoption);
        break;
    case 11:
        // longoption 長いオプション名
        // -m 11 --option1 --option2 hoge --option3 hogehoge
        $longoption = array(
            "option1",
            "option2:",
            "option3:");
        $options = getopt("m:", $longoption);
        break;
}
foreach($options as $key=>$value){
    echo $key . "=" . $value . "\n";
}

?>