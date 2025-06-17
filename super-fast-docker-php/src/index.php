<?php
// 简单路由，转发 /api/* 到 api.php，其余返回 index.html
if (preg_match('#^/api/#', $_SERVER['REQUEST_URI'])) {
    require __DIR__ . '/api.php';
    exit;
}
readfile(__DIR__ . '/h5/index.html');
