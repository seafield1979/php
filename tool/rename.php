<?php

// ファイル名にフォルダ名を追加する
// hoge/001.jpg -> hoge/hoge_001.jpg

function file_tree()
{
    function show_file($dir_name, $indent)
    {        
        $dir = opendir("./");
        if (!$dir) {
            return;
        }

        while (($file = readdir($dir)) !== false) {
            if (is_file("$file")) {
                $file_info = pathinfo($file);
                if ($file_info['extension'] == "jpg") {
                    // ファイル名を変更
                    rename($file, "${dir_name}_${file}");
                }
                echo "$indent$file\n";
            }
            else {
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