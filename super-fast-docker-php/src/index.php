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

// 默认输出前端页面，带有简单的 H5 游戏交互
?>
<!DOCTYPE html>
<html lang='zh-cn'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'>
    <title>H5 精彩手机游戏</title>
    <style>
        body { margin:0; padding:0; font-family:sans-serif; background:#222; color:#fff; }
        .container { max-width:480px; margin:40px auto; padding:20px; background:rgba(0,0,0,0.7); border-radius:12px; }
        h1 { font-size:2em; text-align:center; }
        .game-btn { display:block; width:100%; padding:16px; margin:30px 0 10px 0; font-size:1.2em; background:#ff9800; color:#fff; border:none; border-radius:8px; cursor:pointer; }
        .result { background:#333; border-radius:8px; padding:16px; margin-top:20px; min-height:40px; font-size:1.1em; }
        @media (max-width: 600px) {
            .container { margin:10px; padding:10px; }
            h1 { font-size:1.5em; }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>H5 精彩手机游戏</h1>
        <button class='game-btn' onclick='playGame()'>一键结算游戏（settleGame）</button>
        <div class='result' id='result'>点击上方按钮体验接口</div>
    </div>
    <script>
    function playGame() {
        document.getElementById('result').innerText = '游戏结算中...';
        fetch('?action=settleGame&uid=1&gameid=test')
            .then(r => r.json())
            .then(data => {
                document.getElementById('result').innerText = JSON.stringify(data, null, 2);
            })
            .catch(e => {
                document.getElementById('result').innerText = '请求失败：' + e;
            });
    }
    </script>
</body>
</html>