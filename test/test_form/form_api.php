<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8" />
<title>GETメッセージを表示する</title>

<!--
 ページ内にcssを記述 
-->
<style type="text/css">
   h1{color:#101010}
   .red{color:#ff0000;}
   .green{color:#00ff00;}
   .blue{color:#0000ff;}
</style>


<!--
  JavaScriptのソース
-->
<script type="text/javascript">
  // jsロード後に実行される処理
  (function(){
    document.write("js is loaded!!<br>");
  })();

  // DOMロード後に実行される処理
  // ウェブ上のあらゆるオブジェクトの読み込みがすべて完了した後で処理をする方法
  window.onload = function() {
  	// document.write("html is loaded complete!!")
  }
</script>

</head>

<body> 
<!-- ここにhtmlの本体を記述する -->
hello html
<h1>GETメッセージを受け取るページ</h1>


<?php
	echo("<h1>" + $_GET["username"] + "</h1>");
?>


<!-- htmlが読み込まれた時点で実行される処理 -->
<script type="text/javascript">
    document.write("html is loaded!!");
</script>
</body>
</html>
