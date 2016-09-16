<?php

$argv = array("" , "_swift_iOS_memo.html", "_swift_iOS_template.html", "./swift_iOS/", "swift_iOS_");
$argc = count($argv);
require_once("./create_page_html.php");

$block_list = createHtmls1($argv[1]);
foreach ($block_list as $key=>$value) {
    print "${key} : " . $value["key"] . ", title: " . $value["title"] . "\n";
}


?>