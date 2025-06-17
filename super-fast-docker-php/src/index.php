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
    <title>H5 精彩手机游戏大厅</title>
    <style>
        body { margin:0; padding:0; font-family:sans-serif; background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%); color:#fff; }
        .container { max-width:520px; margin:40px auto; padding:24px; background:rgba(0,0,0,0.85); border-radius:18px; box-shadow:0 8px 32px #0008; }
        h1 { font-size:2.2em; text-align:center; letter-spacing:2px; margin-bottom:18px; }
        .game-list { display:flex; gap:24px; justify-content:center; margin:32px 0; }
        .game-card { background:rgba(255,255,255,0.07); border-radius:16px; box-shadow:0 2px 8px #0004; padding:18px 16px 12px 16px; width:180px; text-align:center; cursor:pointer; transition:transform .2s; }
        .game-card:hover { transform:scale(1.05); background:rgba(255,255,255,0.13); }
        .game-svg { width:90px; height:90px; margin-bottom:10px; }
        .game-title { font-size:1.2em; font-weight:bold; margin-bottom:6px; }
        .game-desc { color:#ccc; font-size:0.98em; min-height:36px; }
        .game-area { display:none; margin-top:30px; }
        .back-btn { margin:18px 0 0 0; background:#ff9800; color:#fff; border:none; border-radius:8px; padding:10px 24px; font-size:1em; cursor:pointer; }
        @media (max-width: 600px) {
            .container { margin:8px; padding:8px; }
            .game-list { flex-direction:column; gap:18px; }
            .game-card { width:100%; }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>H5 精彩手机游戏大厅</h1>
        <div class='game-list' id='gameList'>
            <div class='game-card' onclick="showGame('jinhua')">
                <svg class='game-svg' viewBox='0 0 100 100'>
                    <ellipse cx='50' cy='50' rx='45' ry='30' fill='#e53935' stroke='#fff' stroke-width='4'/>
                    <text x='50' y='58' text-anchor='middle' font-size='32' fill='#fff' font-family='Arial' font-weight='bold'>J</text>
                </svg>
                <div class='game-title'>炸金花</div>
                <div class='game-desc'>三张牌，斗智斗勇，体验刺激对决！</div>
            </div>
            <div class='game-card' onclick="showGame('dial')">
                <svg class='game-svg' viewBox='0 0 100 100'>
                    <circle cx='50' cy='50' r='45' fill='#43a047' stroke='#fff' stroke-width='4'/>
                    <path d='M50 50 L50 10 A40 40 0 0 1 90 50 Z' fill='#ffd600'/>
                    <circle cx='50' cy='50' r='12' fill='#fff'/>
                </svg>
                <div class='game-title'>幸运转盘</div>
                <div class='game-desc'>下注转盘，幸运开奖，赢取大奖！</div>
            </div>
        </div>
        <div class='game-area' id='jinhuaArea'>
            <h2 style='text-align:center;'>炸金花</h2>
            <div id='jinhuaTable' style='text-align:center; margin:18px 0;'></div>
            <div style='text-align:center;'>
                <button class='back-btn' onclick="backToHall()">返回大厅</button>
            </div>
        </div>
        <div class='game-area' id='dialArea'>
            <h2 style='text-align:center;'>幸运转盘</h2>
            <div id='dialTable' style='text-align:center; margin:18px 0;'></div>
            <div style='text-align:center;'>
                <button class='back-btn' onclick="backToHall()">返回大厅</button>
            </div>
        </div>
    </div>
    <script>
    function showGame(game) {
        document.getElementById('gameList').style.display = 'none';
        document.getElementById('jinhuaArea').style.display = (game==='jinhua') ? 'block' : 'none';
        document.getElementById('dialArea').style.display = (game==='dial') ? 'block' : 'none';
        if(game==='jinhua') loadJinhua();
        if(game==='dial') loadDial();
    }
    function backToHall() {
        document.getElementById('gameList').style.display = 'flex';
        document.getElementById('jinhuaArea').style.display = 'none';
        document.getElementById('dialArea').style.display = 'none';
    }
    // 炸金花演示
    function loadJinhua() {
        let table = document.getElementById('jinhuaTable');
        table.innerHTML = `<button class='game-btn' onclick='jinhuaStart()'>开局发牌</button><div id='jinhuaResult' style='margin-top:18px;'></div>`;
    }
    function jinhuaStart() {
        let res = document.getElementById('jinhuaResult');
        res.innerHTML = '发牌中...';
        fetch('?action=Jinhua&liveuid=1&stream=test&token=abc').then(r=>r.json()).then(data=>{
            if(data.code===0 && data.info[0].cards) {
                let html = '<div style="margin:10px 0;">';
                data.info[0].cards.forEach((hand,i)=>{
                    html += `<span style='display:inline-block;margin:0 8px;'>`+hand.map(card=>renderCard(card)).join(' ')+`</span>`;
                });
                html += '</div>';
                html += `<button class='game-btn' onclick='jinhuaBet("${data.info[0].gameid}")'>下注并结算</button>`;
                res.innerHTML = html;
            } else {
                res.innerHTML = '开局失败：'+data.msg;
            }
        });
    }
    function jinhuaBet(gameid) {
        let res = document.getElementById('jinhuaResult');
        res.innerHTML = '下注中...';
        fetch(`?action=JinhuaBet&uid=1&gameid=${gameid}&token=abc&coin=100&grade=1`).then(r=>r.json()).then(data=>{
            fetch(`?action=endGame&liveuid=1&gameid=${gameid}&token=abc&type=1&ifset=0`).then(r=>r.json()).then(endData=>{
                res.innerHTML = `<div>结算结果：<br>赢家位置：${(endData.info[0]&&endData.info[0].winner+1)||'-'}<br>牌面：<br>`+endData.info[0].cards.map((hand,i)=>hand.map(card=>renderCard(card)).join(' ')).join('<br>')+`</div>`;
            });
        });
    }
    // 转盘演示
    function loadDial() {
        let table = document.getElementById('dialTable');
        table.innerHTML = `<button class='game-btn' onclick='dialStart()'>开局下注</button><div id='dialResult' style='margin-top:18px;'></div>`;
    }
    function dialStart() {
        let res = document.getElementById('dialResult');
        res.innerHTML = '下注中...';
        fetch('?action=Dial&liveuid=1&stream=test&token=abc').then(r=>r.json()).then(data=>{
            if(data.code===0 && data.info[0].gameid) {
                let html = `<svg width='160' height='160' viewBox='0 0 160 160' style='margin:10px 0;'>`;
                for(let i=0;i<6;i++) {
                    let angle = i*60;
                    html += `<path d='M80,80 L${80+70*Math.cos((angle-30)*Math.PI/180)},${80+70*Math.sin((angle-30)*Math.PI/180)} A70,70 0 0,1 ${80+70*Math.cos((angle+30)*Math.PI/180)},${80+70*Math.sin((angle+30)*Math.PI/180)} Z' fill='${i%2==0?'#ffd600':'#ff9800'}' stroke='#fff' stroke-width='2'/>`;
                }
                html += `<circle cx='80' cy='80' r='30' fill='#fff' stroke='#888' stroke-width='2'/></svg>`;
                html += `<button class='game-btn' onclick='dialBet("${data.info[0].gameid}")'>下注并开奖</button>`;
                res.innerHTML = html;
            } else {
                res.innerHTML = '开局失败：'+data.msg;
            }
        });
    }
    function dialBet(gameid) {
        let res = document.getElementById('dialResult');
        res.innerHTML = '开奖中...';
        fetch(`?action=Dial_Bet&uid=1&gameid=${gameid}&token=abc&coin=50&grade=2`).then(r=>r.json()).then(data=>{
            fetch(`?action=Dial_end&liveuid=1&gameid=${gameid}&token=abc&type=1&ifset=0`).then(r=>r.json()).then(endData=>{
                let result = (endData.info[0]&&endData.info[0].result)||'-';
                res.innerHTML = `<div>开奖结果：幸运区块 <b style='color:#ffd600;'>${parseInt(result)+1}</b></div>`;
            });
        });
    }
    // 牌面渲染
    function renderCard(card) {
        let [color,num] = card.split('-');
        let colorMap = {1:'#388e3c',2:'#1976d2',3:'#d32f2f',4:'#fbc02d'};
        let numMap = {11:'J',12:'Q',13:'K',14:'A'};
        let showNum = numMap[num]||num;
        return `<span style='display:inline-block;width:32px;height:44px;background:#fff;border-radius:6px;border:2px solid #888;margin:0 2px;box-shadow:0 2px 8px #0003;position:relative;'><span style='color:${colorMap[color]||'#222'};font-weight:bold;font-size:1.2em;position:absolute;left:6px;top:4px;'>${showNum}</span></span>`;
    }
    </script>
</body>
</html>