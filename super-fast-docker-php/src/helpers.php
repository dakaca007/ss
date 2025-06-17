<?php
// 工具函数集合
function checkNull($val) {
    return isset($val) ? trim($val) : '';
}

function checkToken($uid, $token) {
    // 简单模拟token校验，实际可接入数据库或Redis
    if (empty($uid) || empty($token)) return 700;
    return 0;
}

function getUserInfo($uid) {
    // 模拟用户信息
    return [
        'uid' => $uid,
        'level' => rand(1, 10),
        'coin' => rand(100, 1000)
    ];
}

function NumberFormat($num) {
    return number_format($num, 2, '.', '');
}
