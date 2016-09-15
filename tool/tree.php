<?php

// カレント以下のファイルツリーを表示する

function file_tree()
{
    function show_file($dir_name, $indent)
    {        
        $dir = opendir("./");
        if (!$dir) {
            return;
        }

        while (($file = readdir($dir)) !== false) {
            // ファイルの処理
            if (is_file("$file")) {
                $file_info = pathinfo($file);
                //if ($file_info['extension'] == "jpg") {
                    // 特定の拡張子の処理
                //}
                echo "$indent$file\n";
            }
            else {
                // フォルダの処理
                if ($file != "." && $file != "..") {
                    echo "$indent$file/\n";
                    chdir(${file});
                    show_file("${file}", "  " . $indent);
                }
            }
        }
        closedir($dir);
        chdir("..");
    }
    
    show_file("./", "  ");
}


file_tree();
?>