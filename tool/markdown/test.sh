#!/bin/sh

# ターミナルから
# sh test.sh 
# で実行

# Swift
if [ ! -e swift ]; then
    `mkdir swift`
fi
php create_page_html.php "_swift_memo.html" "_swift_template.html" "./swift/swift_"
php create_top_html.php "_swift_memo.html" "_swift_template.html" "swift_" "./swift/swift_top.html"

# iOS_Swift
if [ ! -e swift_iOS ]; then
    `mkdir swift_iOS`
fi
php create_page_html.php "_swift_iOS_memo.html" "_swift_iOS_template.html" "./swift_iOS/swift_iOS_"
php create_top_html.php "_swift_iOS_memo.html" "_swift_iOS_template.html" "iOS_swift_" "./swift_iOS/swift_iOS_top.html"
