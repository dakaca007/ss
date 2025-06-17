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
        .game-btn { display:block; width:100%; padding:14px; margin:18px 0 0 0; font-size:1.1em; background:#ff9800; color:#fff; border:none; border-radius:8px; cursor:pointer; }
        .result { background:#333; border-radius:8px; padding:16px; margin-top:20px; min-height:40px; font-size:1.1em; }
        .desc { color:#aaa; font-size:0.95em; margin-bottom:8px; }
        @media (max-width: 600px) {
            .container { margin:10px; padding:10px; }
            h1 { font-size:1.5em; }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>H5 精彩手机游戏</h1>
        <div class='desc'>点击下方按钮体验各类游戏接口</div>
        <button class='game-btn' onclick="playApi('settleGame', {uid:1,gameid:'test'})">结算游戏（settleGame）</button>
        <button class='game-btn' onclick="playApi('checkGame', {liveuid:1,stream:'test'})">检测游戏状态（checkGame）</button>
        <button class='game-btn' onclick="playApi('Jinhua', {liveuid:1,stream:'test',token:'abc'})">炸金花开局（Jinhua）</button>
        <button class='game-btn' onclick="playApi('endGame', {liveuid:1,gameid:'test',token:'abc',type:1,ifset:0})">炸金花关局（endGame）</button>
        <button class='game-btn' onclick="playApi('JinhuaBet', {uid:1,gameid:'test',token:'abc',coin:100,grade:1})">炸金花下注（JinhuaBet）</button>
        <button class='game-btn' onclick="playApi('Dial', {liveuid:1,stream:'test',token:'abc'})">转盘开局（Dial）</button>
        <button class='game-btn' onclick="playApi('Dial_end', {liveuid:1,gameid:'test',token:'abc',type:1,ifset:0})">转盘关局（Dial_end）</button>
        <button class='game-btn' onclick="playApi('Dial_Bet', {uid:1,gameid:'test',token:'abc',coin:50,grade:2})">转盘下注（Dial_Bet）</button>
        <button class='game-btn' onclick="playApi('getGameRecord', {action:1,stream:'test'})">获取游戏记录（getGameRecord）</button>
        <button class='game-btn' onclick="playApi('getBankerProfit', {bankerid:1,stream:'test'})">庄家流水（getBankerProfit）</button>
        <button class='game-btn' onclick="playApi('getBanker', {stream:'test'})">上庄列表（getBanker）</button>
        <button class='game-btn' onclick="playApi('setBanker', {uid:1,token:'abc',stream:'test',deposit:500})">用户上庄（setBanker）</button>
        <button class='game-btn' onclick="playApi('quietBanker', {uid:1,stream:'test'})">用户下庄（quietBanker）</button>
        <div class='result' id='result'>点击任意按钮体验接口</div>
    </div>
    <script>
    function playApi(action, params) {
        document.getElementById('result').innerText = '请求中...';
        let url = '?action=' + encodeURIComponent(action);
        for (let k in params) {
            url += '&' + encodeURIComponent(k) + '=' + encodeURIComponent(params[k]);
        }
        fetch(url)
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