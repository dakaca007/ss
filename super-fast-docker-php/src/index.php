<?php
// PHP 入口文件，转发到 index.html 或 API
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false; // 让 nginx 处理静态文件
}
readfile(__DIR__ . '/index.html');
