#!/bin/bash

#不要なファイルをフォルダごと削除
dirnames=("php" "mac" "markdown" "shell" "sublime" "swift" "swift_iOS")
for dirname in ${dirnames[@]}
do
    echo "${dirname}"
    if [ -e ${dirname} ]; then
        rm -r /Users/shutaro/Dropbox/sunsunsoft/contents/programing/${dirname}
    fi
    cp -R ${dirname} /Users/shutaro/Dropbox/sunsunsoft/contents/programing/${dirname}
done
