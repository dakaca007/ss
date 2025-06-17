<?php
// 入口文件，支持接口和前端页面
require_once __DIR__ . '/api/Game.php';

if (isset($_GET['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_GET['action'];
    $params = $_REQUEST;
    $api = new GameApi();
    $result = $api->handle($action, $params);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}

// 默认输出前端页面
?>
<!DOCTYPE html>
<html lang='zh-cn'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'>
    <title>H5 Mobile Game Test</title>
    <style>
        body { margin:0; padding:0; font-family:sans-serif; background:#222; color:#fff; }
        .container { max-width:480px; margin:40px auto; padding:20px; background:rgba(0,0,0,0.7); border-radius:12px; }
        h1 { font-size:2em; text-align:center; }
        p { font-size:1.2em; text-align:center; }
        @media (max-width: 600px) {
            .container { margin:10px; padding:10px; }
            h1 { font-size:1.5em; }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Welcome to the H5 Mobile Game!</h1>
        <p>This is a test project for mobile responsive game development.</p>
        <p>已经完美预先</p>
    </div>
</body>
</html>