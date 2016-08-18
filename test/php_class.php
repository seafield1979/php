<?php

// ターミナルから
// $ php 01_helloworld.php 
// みたいにして実行する

// クラステスト
// クラスの定義とメソッドの呼び出し
function class_test1()
{
    class CTest1
    {
        public $hoge = "123";
        public function func_hoge(){
            echo $this->hoge, "\n";
        }
    }

    $hoge1 = new CTest1();
    echo $hoge1->hoge, "\n";
    $hoge1->func_hoge();

    // 参照変数を作ってみる
    $hoge2 = & $hoge;       // $hoge2は$hogeの参照
    $hoge3 = $hoge;         // $hoge3は$hogeの参照

    $hoge2->name = "shutaro2";
    $hoge3->name = "shutaro3";

    // インスタンス変数を表示。すべて同じ内容だとわかる
    var_dump($hoge);
    var_dump($hoge2);
    var_dump($hoge3);
}

// コンストラクタとデストラクタ
function class_test2()
{
    class CTest1
    {
        public $name = null;
        // コンストラクタ
        function __construct($name) {
            $this->name = $name;
        }
        // デストラクタ (参照元が１つもなくなった時に呼ばれる)
        function __destruct() {
            echo "destroy $this->name \n";
        }
        public function disp(){
            echo $this->name . "\n";
        }
    }
    // コンストラクタが呼ばれる
    $hoge = new CTest1('shutaro');
    $hoge->disp();

    var_dump($hoge);
    $hoge = null;       // ここで$hogeのインスタンスへの参照がなくなるのでデスクトラクタが呼ばれる
}

// static変数、const定数、staticメソッド
function class_test3()
{
    class CTest1 {
        static $HOGE = "HOGE";
        public static function func_hoge(){
            echo "hoge " . self::$HOGE . "\n";
        }
    }

    // static メンバ
    echo CTest1::$HOGE . "\n";

    // static メソッド
    CTest1::func_hoge();
}

// アクセス権
// public, private, protected
function class_test4()
{
    class CTest1{
        public $public1 = "public hoge";
        private $private1 = "private hoge";
        protected $protected1 = "protected1";

        public function disp1(){
            echo "CTest1::disp1\n";
            echo $this->public1 . "\n";
            echo $this->private1 . "\n";
            echo $this->protected1 . "\n";
        }
        private function private_func(){
            echo "CTest1::private_func\n";
        }
    }

    // CTest1を継承したクラス
    class CTest2 extends CTest1 {
        public function disp1(){
            echo "CTest2::disp1\n";
            echo $this->public1 . "\n";
            echo $this->private1 . "\n";        // 空
            echo $this->protected1 . "\n";
        }
    }

    $instance = new CTest1();
    echo $instance->public1 . "\n";
     // echo $instance->protected1 . "\n";   error protectedプロパティは外部からアクセスできない
     // echo $instance->private1 . "\n";     error privateプロパティは外部からアクセスできない
    $instance->disp1();

    $instance2 = new CTest2();
    $instance2->disp1();
    echo $instance2->private1 . "\n";
    echo $hoge123 . "\n";

}

// クローンのテスト clone:
function class_test5()
{
    class CTest1{
        public static $counter = 0;
        public $id;     // すべてのインスタンスで独自のIDになる
        public $name;

        function __construct($name){
            self::$counter++;
            $this->id = self::$counter;
            $this->name = $name;
        }
        public function disp(){
            echo $this->name . " (" . $this->id . ")" . "\n";
        }


        public function __clone(){
            self::$counter++;
            $this->id = self::$counter;
        }
    }

    $hoge1 = new CTest1("shutaro");
    $hoge2 = clone $hoge1;
    $hoge2->name = "naotaro";

    $hoge1->disp();
    $hoge2->disp();
}

// 継承(inherit)
function class_test6()
{
    class CTest1{
      function __construct(){
        echo "CTest1::__construct\n";
      }
      public function disp(){
        echo "CTest1::disp\n";
      }
    }
    class CTest2 extends CTest1 {
      function __construct(){
        parent::__construct();
        echo "CTest2::__construct\n";
      }
      public function disp(){
        parent::disp();
        echo "CTest2::disp\n";
      }
    }

    $test2 = new CTest2;
    $test2->disp();
}

class_test6();

?>
