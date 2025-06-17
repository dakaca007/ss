<?php
// 简单 API 路由
if (preg_match('#^/api/stream/flv$#', $_SERVER['REQUEST_URI'])) {
    // 返回 SRS 拉流地址
    header('Content-Type: application/json');
    echo json_encode(["url" => "http://localhost:8080/live/stream.flv"]);
    exit;
}
if (preg_match('#^/api/chat/send$#', $_SERVER['REQUEST_URI'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $msg = $data['msg'] ?? '';
    if ($msg) {
        $redis = new Redis();
        $redis->connect('redis', 6379);
        $redis->rpush('chat', $msg);
    }
    echo json_encode(["ok" => true]);
    exit;
}
if (preg_match('#^/api/chat/list$#', $_SERVER['REQUEST_URI'])) {
    $redis = new Redis();
    $redis->connect('redis', 6379);
    $msgs = $redis->lrange('chat', -20, -1);
    header('Content-Type: application/json');
    echo json_encode($msgs);
    exit;
}
// 其他情况，返回 404
http_response_code(404);
echo 'Not Found';
