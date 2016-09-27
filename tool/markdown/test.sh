#!/bin/sh

# ターミナルから
# sh test.sh 
# もしくは
# ./test.sh
# で実行

#Java
function create_Java() {
    if [ ! -e Java ]; then
        `mkdir Java`
    fi

    php create_htmls.php "--mdHtml" "./_MainHtmls/_Java_memo.html" \
                        "--template" "_template.html" \
                        "--topTemplate" "_template.html" \
                        "--outputDir" "./Java/" \
                        "--htmlName" "Java_" \
                        "--topHtmlName" "_Java_top.html" \
                        "--titleText" "Java"
}

#SublimeText
function create_sublime() {
    if [ ! -e sublime ]; then
        `mkdir sublime`
    fi

    php create_htmls.php "--mdHtml" "./_MainHtmls/_sublime_memo.html" \
                        "--template" "_template.html" \
                        "--topTemplate" "_template.html" \
                        "--outputDir" "./sublime/" \
                        "--htmlName" "sublime_" \
                        "--topHtmlName" "_sublime_top.html" \
                        "--titleText" "Sublime Text 3"
}

#Macメモ
function create_mac() {
    if [ ! -e mac ]; then
        `mkdir mac`
    fi
    php create_one_page_html.php "./_MainHtmls/_mac_memo.html" "_template.html" "./mac/_mac_top.html" "Mac(OSX)メモ"
}

#Markdown
function create_markdown() {
    if [ ! -e markdown ]; then
        `mkdir markdown`
    fi
    php create_one_page_html.php "./_MainHtmls/_markdown.html" "_template.html" "./markdown/_markdown_top.html" "マークダウン"
}

#Shell Script
function create_shell() {
    if [ ! -e shell ]; then
        `mkdir shell`
    fi
    
    php create_htmls.php "--mdHtml" "./_MainHtmls/_shell_memo.html" \
                        "--template" "_template.html" \
                        "--topTemplate" "_template.html" \
                        "--outputDir" "./shell/" \
                        "--htmlName" "shell_" \
                        "--topHtmlName" "_shell_top.html" \
                        "--titleText" "Shell Script プログラミング"
}

# PHP
function create_php() {
    if [ ! -e php ]; then
        `mkdir php`
    fi
    php create_htmls.php "--mdHtml" "./_MainHtmls/_php_memo.html" \
                        "--template" "_template.html" \
                        "--topTemplate" "_template.html" \
                        "--outputDir" "./php/" \
                        "--htmlName" "php_" \
                        "--topHtmlName" "_php_top.html" \
                        "--titleText" "PHP プログラミング"
}

# Swift
function create_swift() {
    if [ ! -e swift ]; then
        `mkdir swift`
    fi

    php create_htmls.php "--mdHtml" "./_MainHtmls/_swift_memo.html" \
                        "--template" "_template.html" \
                        "--topTemplate" "_template.html" \
                        "--outputDir" "./swift/" \
                        "--htmlName" "swift_" \
                        "--topHtmlName" "_swift_top.html" \
                        "--titleText" "Swift プログラミング"
}

# iOS_Swift
function create_swift_iOS() {
    if [ ! -e swift_iOS ]; then
        `mkdir swift_iOS`
    fi

    php create_htmls.php "--mdHtml" "./_MainHtmls/_swift_iOS_memo.html" \
                        "--template" "_template.html" \
                        "--topTemplate" "_template.html" \
                        "--outputDir" "./swift_iOS/" \
                        "--htmlName" "swift_iOS_" \
                        "--topHtmlName" "_swift_iOS_top.html" \
                        "--titleText" "Swift iOS プログラミング"
}

#create_Java
create_mac
create_sublime
create_markdown
create_shell
create_php
create_swift
create_swift_iOS
