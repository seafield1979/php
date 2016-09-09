<?php

// ターミナルから
// $ php 01_helloworld.php 
// みたいにして実行する

// ※Cookieのサンプルはなし。理由はサーバー環境でないと正しく動作しないから

// 基本テスト
function test1()
{
    print_r($argv);
    // 定数
    echo "\n-1-","\n";
    define(HOGE, 100);
    define("HAGE", 200);

    echo HOGE, "\n";
    echo HAGE, "\n";

    // 自動的に定義される定数
    echo "\n-2-","\n";
    echo __FUNCTION__ . " " . __LINE__ . "\n";

    // 文字列を１文字づつ処理
    echo "\n-3-\n";
    $hoge = "hoge";
    echo $hoge[0] . "\n";
    foreach(str_split($hoge) as $key=>$value){
        print($value . "\n");
    }

    // 日時を取得
    echo "\n-4-\n";
    date_default_timezone_set('Asia/Tokyo');
    $date_str = date("Y/m/d H:i:s");
    echo $date_str, "\n";

    // プログラムに渡された引数
    echo "\n-5-\n";
    global $argv;
    print_r($argv);
    echo $argv[0] . "\n";
}

// 文字列のテスト
function string_test1()
{
    // 文字列の宣言
    echo "\n-1-","\n";
    $str1 = 'hogehoge1\n';  // エスケープや変数が展開されない
    $str2 = "hogehoge2\n";  // エスケープや変数が展開される
    print_r($str1);
    print_r($str2);

    echo "\n-2-","\n";
    echo 1 + "10.5";
    echo "\n";   
    echo 1 + "abc";
    echo "\n";   

    // ヒアドキュメント
    // 閉じる行は先頭にタブを入れてはいけない。
    // echo <<<EOT
    //     hoge
    //     hoge
    //     hoge
    // EOT;
    echo "\n-3-","\n";
$str = <<<EOD
    Example of string
    spanning multiple lines
    using heredoc syntax.

EOD;
echo $str;

echo <<<hoge
aaa
bbb
ccc

hoge;

    // 引数としてヒアドキュメントを渡す
var_dump(array(<<<EOD
foobar!
EOD
));

    // 文字列の比較
    echo "\n-4-","\n";
    $str1 = "hoge";
    $str2 = "hage";
    $str3 = "hoge";
    if ($str1 == $str2) {
        echo "str1 == str2\n";
    }
    if ($str1 == $str3) {
        echo "str1 == str3\n";
    }

    // 型も含めた比較
    echo "\n-5-","\n";
    $val1 = 123;
    $val2 = "123";
    $val3 = 123;
    
    if ($val1 == $val2){
        echo "val1 == val2\n";
    }
    if ($val1 === $val2) {
        echo "val1 equal val2\n";
    }
    else {
        echo "val1 not equal val2\n";
    }
    if ($val1 === $val3) {
        echo "val1 equal val3\n";
    }
    else {
        echo "val1 not equal val3\n";
    }
}

// 型の判定テスト
function gettype_test1()
{
    // 各種変数の型を判定
    echo "\n-1-","\n";
    $array = array("hello", 123, array(1,2,3));

    foreach($array as $value) {
        echo gettype($value), "\n";
    }

    // 配列かどうかの判定
    echo "\n-2-","\n";
    $array = array(1,2,3);
    if (is_array($array)){
        echo "array!\n";
    }
    else {
        echo "not array!\n";
    }
}

// 配列のテスト
function array_test1()
{
    // 配列の初期化
    echo "\n-1-\n";
    $array1 = array("a1", "a2", "a3");
    $array2 = array(a1=>10, a2=>20, a3=>30);  // キーを""で囲まなくてもOK
    $array3 = array("a1"=>10, "a2"=>20, "a3"=>30);  // キーを""で囲った

    // 要素の追加
    $array1[] = "a4";       // $array1の最後に"a4"が追加された
    echo var_dump($array1);
    echo var_dump($array2); 
    echo var_dump($array3); 

    // 要素の参照
    echo "\n-2-\n";
    $array = array(1,2,3,4,5);
    echo $array[0] . "\n";   // 1
    echo $array[2] . "\n";   // 3

    // 指定のキーが存在するかチェック
    // isset は危険なので使わない。詳しくは http://d.hatena.ne.jp/omoon/20111217/1324109105
    echo "\n-22-\n";
    $array = array(a=>1,b=>2,c=>3,d=>null);
    if (array_key_exists("a", $array)){
        echo "a is exist\n";
    }
    if (array_key_exists("d", $array)){
        echo "1:d is exist\n";
    }
    // ちなみに isset を使うと dの要素はないと判定される
    if (isset($array["d"])){
        echo "2:d is exist\n";
    }
    else {
        echo "2:d isn't exist\n";
    }

    // 配列の中に配列
    echo "\n-3-\n";
    $array1 = array("hoge", array("hoge21", "hoge22", array("hoge31", "hoge32")));
    print_r($array1);

    // 要素の削除
    echo "\n-4-\n";
    $array = array(a=>1, b=>2, c=>3, d=>4);
    unset($array["b"]);     // bの要素が削除される
    unset($array[c]);     // cの要素が削除される。キーをコーテーションで囲まなくてもOK
    print_r($array);

    // 全要素にアクセスするforループ
    echo "\n-5-\n";
    $array = array(a=>1, b=>2, c=>3, d=>4);

    // 値
    foreach($array as $value) {
        echo "value=$value\n";
    }
    // キーと値
    foreach($array as $key=>$value) {
        echo "key=$key value=$value\n";
    }

    // 要素数を取得
    echo "\n-6-\n";
    $array = array(1,2,3,4,5);
    echo "array_count:" . count($array) . "\n";
    
}

// if文のテスト
function if_test1(){
    if (true) {
        echo "1\n";
    }
    if (!true) {
        echo "21\n";
    }
    else {
        echo "22\n";
    }
}

// ループテスト
function loop_test1(){
    // for
    echo "\n-1-\n";
    for ($cnt=0; $cnt < 10; $cnt++) {
        echo "cnt=${cnt}\n";
    }

    // foreach:
    echo "\n-2-\n";
    $array = array(name=>"shutaro", age=>36, comment=>"hoge");
    foreach ($array as $key=>$value) {
        echo "key=${key} value=${value}\n";
    }

    $str = "shutaro";
    foreach(str_split($str) as $value){
        echo $value . "\n"; 
    }

    // while
    echo "\n-3-\n";
    $cnt = 10;
    while($cnt > 0){
        echo "cnt=${cnt}\n";
        $cnt--;
    }



}

// 関数のテスト
function func_test1(){
    function func1(){
        echo "func1\n";
        function func2(){
            echo "func2\n";
        }
    }
    // この並びはOK
    func1();
    func2();

    // この並びはNG
    // func2();
    // func1();
}

// ファイルのテスト
function file_test1()
{
    // ファイルの内容を読み込み表示する
    echo "\n-1-\n";
    $filename = "./text1.txt";
    if (!($fp = @fopen($filename, "r"))) {
        echo "failed to open file\n";
        return;
    }
    
    while( ! feof( $fp ) ){
      echo fgets( $fp, 9182 );
    }

    fclose($fp);

    // ファイルに書き込む(追加書き込み)
    echo "\n-2-\n";
    $filename = "./text_w.txt";
    if (!($fp = @fopen($filename, "a+"))) {
        echo "failed to open file\n";
        return;
    }
    fputs($fp, "hoge\n");

    fclose($fp);
}

// ファイルツリーを表示
function file_tree()
{
    function show_file($dir, $indent){
        while (($file = readdir($dir)) !== false) {
            if (is_dir($file)) {
                if ($file != "." && $file != "..") {
                    echo "$indent$file/\n";
                    if ($dir2 = opendir($file)) {
                        show_file($dir2, "  " . $indent);
                    }
                }
            }
            else {                
                echo "$indent$file\n";
            }
        } 
    }
    if ($dir = opendir("./")) {
        show_file($dir, "  "); 
        closedir($dir);
    }
}

// 正規表現テスト
function re_test1()
{
    $src_string = "HOGE123.abcd";
    preg_match('/([a-z|A-Z]+)(\d+)\.(\w+)/', $src_string, $match);
    print_r($match);
}

// クラステスト
function class_test1()
{
    class CTest1
    {
        public $hoge = "123";
        private $phoge = "abc";
        public function func_hoge(){
            echo $this->hoge . " " . $this->phoge .  "\n";
        }
    }

    $hoge1 = new CTest1();
    echo $hoge1->hoge, "\n";
    //echo $hoge1->phoge, "\n";   エラーになる
    $hoge1->func_hoge();
}

// 外部ファイルの読み込みテスト
function include_test(){
    require_once "include1.php";

    // include1.php で定義されたメソッドを呼び出す
    include_func1();

    $array1 = array( include "include2.php" );
    print_r($array1);
}

// 例外処理 exception:
function exception_test1()
{
    try {
      // 例外が発生するかもしれない処理を try スコープに入れる
      throw new Exception("error1");
    } catch (Exception $e){
      echo $e->getMessage() . "\n";
    } finally {
      // 必ず実行される処理
    }
}

// Exceptionを継承した自前の例外クラスを使う
function exception_test2()
{
    class MyException extends Exception { }
    class MyException2 extends Exception { }

    class Test {
        public function testing() {
            try {
                try {
                    throw new MyException('foo!');
                    throw new MyException2('woo!');
                } catch (MyException $e) {
                    // MyException の throwはこちらでキャッチされる
                    // 改めてスロー
                    throw $e;
                } catch (MyException2 $e) {
                    // MyException2 の throwはこちらでキャッチされる
                    throw $e;
                }
            } catch (Exception $e) {
                var_dump($e->getMessage());
            } finally {
                echo "finnaly!\n";
            }
        }
    }

    $foo = new Test;
    $foo->testing();
}

// 型変換
function convert_test()
{
    // 数値と文字列を変換。小数値や文字列を整数値に変換する
    echo intval(42),"\n";                      // 42
    echo intval(4.2),"\n";                     // 4
    echo intval('42'),"\n";                    // 42
    echo intval('+42'),"\n";                   // 42
    echo intval('-42'),"\n";                   // -42
    echo intval(042),"\n";                     // 34 先頭に0がつくと8進数として扱われる
    echo intval('042'),"\n";                   // 42 文字列の場合は先頭に0が付こうが10進数として扱われる
}

// ハッシュのテスト hash:
function hash_test1()
{
    $base = "hoge";

    // ハッシュを取得
    $hashed = password_hash($base, PASSWORD_DEFAULT);
    echo "ハッシュ：${hashed}\n";

    // ハッシュの比較
    // ハッシュ文字列が、指定の文字列を元にハッシュ化されたかどうかをチェックする
    if (password_verify($base, $hashed)) {
        echo "OK\n";
    }
    else {
        echo "NG\n";
    }
}

// test1();
// string_test1();
// array_test1();
// gettype_test1();
//if_test1();
// loop_test1();
// func_test1();
 // file_test1();
// file_tree();
// re_test1();
// class_test1();
// include_test();
// exception_test1();
//convert_test();
hash_test1();

?>
