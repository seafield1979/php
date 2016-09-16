#!/bin/sh

# ターミナルから
# sh test.sh 
# で実行

#SublimeText
function create_sublime() {
    if [ ! -e sublime ]; then
        `mkdir sublime`
    fi

    php create_htmls.php "_sublime_memo.html" "_template.html" "./sublime/" "sublime_" "_sublime_top.html" "Sublime Text 3"
}

#Markdown
function create_markdown() {
    if [ ! -e markdown ]; then
        `mkdir markdown`
    fi
    php create_one_page_html.php "_markdown.html" "_markdown_template.html" "./markdown/_markdown_top.html" "マークダウン"
}

#Shell Script
function create_shell() {
    if [ ! -e shell ]; then
        `mkdir shell`
    fi
    php create_htmls.php "_shell_memo.html" "_template.html" "./shell/" "shell_" "_shell_top.html" "Shell Script プログラミング"
}

# PHP
function create_php() {
    if [ ! -e php ]; then
        `mkdir php`
    fi
    php create_htmls.php "_php_memo.html" "_template.html" "./php/" "php_" "_php_top.html" "PHP プログラミング"
}

# Swift
function create_swift() {
    if [ ! -e swift ]; then
        `mkdir swift`
    fi
    php create_htmls.php "_swift_memo.html" "_template.html" "./swift/" "swift_" "_swift_top.html" "Swift プログラミング"
}

# iOS_Swift
function create_swift_iOS() {
    if [ ! -e swift_iOS ]; then
        `mkdir swift_iOS`
    fi
    php create_htmls.php "_swift_iOS_memo.html" "_template.html" "./swift_iOS/" "swift_iOS_" "_swift_iOS_top.html" "Swift iOS プログラミング"
}

# create_sublime
# create_markdown
create_shell
create_php
create_swift
create_swift_iOS
