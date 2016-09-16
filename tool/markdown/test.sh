#!/bin/sh

# ターミナルから
# sh test.sh 
# で実行

#SublimeText
function create_sublime() {
    if [ ! -e sublime ]; then
        `mkdir sublime`
    fi
    php create_page_html.php "_sublime_memo.html" "_sublime_template.html" "./sublime/" "sublime_"
    php create_top_html.php "_sublime_memo.html" "_sublime_template.html" "sublime_" "./sublime/_sublime_top.html"
}

#Markdown
function create_markdown() {
    if [ ! -e markdown ]; then
        `mkdir markdown`
    fi
    php create_one_page_html.php "_markdown.html" "_markdown_template.html" "./markdown/_markdown_top.html"
}

#Shell Script
function create_shell() {
    if [ ! -e shell ]; then
        `mkdir shell`
    fi
    php create_page_html.php "_shell_memo.html" "_shell_template.html" "./shell/" "shell_"
    php create_top_html.php "_shell_memo.html" "_shell_template.html" "shell_" "./shell/_shell_top.html"
}

# PHP
function create_php() {
    if [ ! -e php ]; then
        `mkdir php`
    fi
    php create_page_html.php "_php_memo.html" "_php_template.html" "./php/" "php_"
    php create_top_html.php "_php_memo.html" "_php_template.html" "php_" "./php/_php_top.html"
}

# Swift
function create_swift() {
    if [ ! -e swift ]; then
        `mkdir swift`
    fi
    php create_page_html.php "_swift_memo.html" "_swift_template.html" "./swift/" "swift_"
    php create_top_html.php "_swift_memo.html" "_swift_template.html" "swift_" "./swift/_swift_top.html"
}

# iOS_Swift
function create_iOS_swift() {
    if [ ! -e swift_iOS ]; then
        `mkdir swift_iOS`
    fi
    php create_page_html.php "_swift_iOS_memo.html" "_swift_iOS_template.html" "./swift_iOS/" "swift_iOS_"
    php create_top_html.php "_swift_iOS_memo.html" "_swift_iOS_template.html" "swift_iOS_" "./swift_iOS/_swift_iOS_top.html"
}

create_sublime
# create_markdown
# create_shell
# create_php
