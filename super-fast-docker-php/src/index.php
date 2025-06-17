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
        .game-list { display:flex; gap:24px; justify-content:center; margin:32px 0; flex-wrap:wrap; }
        .game-card { background:rgba(255,255,255,0.07); border-radius:16px; box-shadow:0 2px 8px #0004; padding:18px 16px 12px 16px; width:180px; text-align:center; cursor:pointer; transition:transform .2s; margin-bottom:18px; }
        .game-card:hover { transform:scale(1.05); background:rgba(255,255,255,0.13); }
        .game-svg { width:90px; height:90px; margin-bottom:10px; }
        .game-title { font-size:1.2em; font-weight:bold; margin-bottom:6px; }
        .game-desc { color:#ccc; font-size:0.98em; min-height:36px; }
        .game-area { display:none; margin-top:30px; }
        .back-btn { margin:18px 0 0 0; background:#ff9800; color:#fff; border:none; border-radius:8px; padding:10px 24px; font-size:1em; cursor:pointer; }
        .bet-btn { margin:0 4px; background:#43a047; color:#fff; border:none; border-radius:6px; padding:7px 16px; font-size:1em; cursor:pointer; }
        .bet-btn:disabled { background:#888; cursor:not-allowed; }
        .winner { box-shadow:0 0 16px 4px #ffd600; border:2px solid #ffd600 !important; animation: winnerFlash 0.8s alternate infinite; }
        @keyframes winnerFlash { 0%{box-shadow:0 0 16px 4px #ffd600;} 100%{box-shadow:0 0 32px 8px #fff700;} }
        .dial-sector { transition:filter .3s; }
        .dial-winner { filter:drop-shadow(0 0 12px #ffd600); animation: dialWin 1s alternate infinite; }
        @keyframes dialWin { 0%{filter:drop-shadow(0 0 12px #ffd600);} 100%{filter:drop-shadow(0 0 32px #fff700);} }
        .status-tip { color:#ffd600; font-size:1.1em; margin:10px 0; }
        .card-anim { animation: cardDeal 0.5s cubic-bezier(.4,2,.6,1) both; }
        @keyframes cardDeal { 0%{transform:scale(0.2) rotate(-30deg); opacity:0;} 100%{transform:scale(1) rotate(0); opacity:1;} }
        .dial-spin { animation: dialSpin 1.2s cubic-bezier(.4,2,.6,1) both; }
        @keyframes dialSpin { 0%{transform:rotate(0deg);} 100%{transform:rotate(720deg);} }
        .slot-reel { display:inline-block; width:38px; height:38px; background:#fff; border-radius:8px; margin:0 2px; font-size:2em; text-align:center; line-height:38px; box-shadow:0 2px 8px #0003; border:2px solid #888; }
        .slot-anim { animation: slotSpin 0.5s cubic-bezier(.4,2,.6,1) both; }
        @keyframes slotSpin { 0%{transform:scale(0.2) rotate(-30deg); opacity:0;} 100%{transform:scale(1) rotate(0); opacity:1;} }
        .bj-card { display:inline-block; width:36px; height:48px; background:#fff; border-radius:6px; border:2px solid #888; margin:0 2px; box-shadow:0 2px 8px #0003; position:relative; }
        .bj-card .bj-num { color:#222; font-weight:bold; font-size:1.2em; position:absolute;left:6px;top:4px; }
        .bj-card.bj-anim { animation: cardDeal 0.5s cubic-bezier(.4,2,.6,1) both; }
        .bj-bust { color:#e53935; font-weight:bold; }
        .bj-win { color:#ffd600; font-weight:bold; }
        @media (max-width: 600px) {
            .container { margin:8px; padding:8px; }
            .game-list { flex-direction:column; gap:18px; }
            .game-card { width:100%; }
        }
    </style>
</head>
<body>
    <audio id="audioDeal" src="https://cdn.jsdelivr.net/gh/xiangyuecn/recorder/assets/recorder-core/btn.mp3"></audio>
    <audio id="audioWin" src="https://cdn.jsdelivr.net/gh/xiangyuecn/recorder/assets/recorder-core/ok.mp3"></audio>
    <audio id="audioSpin" src="https://cdn.jsdelivr.net/gh/xiangyuecn/recorder/assets/recorder-core/recorder.mp3"></audio>
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
            <div class='game-card' onclick="showGame('blackjack')">
                <svg class='game-svg' viewBox='0 0 100 100'>
                    <rect x='20' y='20' width='60' height='60' rx='12' fill='#fff' stroke='#222' stroke-width='4'/>
                    <text x='50' y='60' text-anchor='middle' font-size='32' fill='#222' font-family='Arial' font-weight='bold'>21</text>
                </svg>
                <div class='game-title'>21点</div>
                <div class='game-desc'>与庄家对决，点数不能爆！</div>
            </div>
            <div class='game-card' onclick="showGame('slot')">
                <svg class='game-svg' viewBox='0 0 100 100'>
                    <rect x='10' y='30' width='80' height='40' rx='10' fill='#fff' stroke='#222' stroke-width='4'/>
                    <circle cx='30' cy='50' r='8' fill='#e53935'/>
                    <circle cx='50' cy='50' r='8' fill='#ffd600'/>
                    <circle cx='70' cy='50' r='8' fill='#43a047'/>
                </svg>
                <div class='game-title'>老虎机</div>
                <div class='game-desc'>拉动拉杆，水果连线中大奖！</div>
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
        <div class='game-area' id='blackjackArea'>
            <h2 style='text-align:center;'>21点（Blackjack）</h2>
            <div id='bjTable' style='text-align:center; margin:18px 0;'></div>
            <div style='text-align:center;'>
                <button class='back-btn' onclick="backToHall()">返回大厅</button>
            </div>
        </div>
        <div class='game-area' id='slotArea'>
            <h2 style='text-align:center;'>老虎机</h2>
            <div id='slotTable' style='text-align:center; margin:18px 0;'></div>
            <div style='text-align:center;'>
                <button class='back-btn' onclick="backToHall()">返回大厅</button>
            </div>
        </div>
    </div>
    <script>
    function playAudio(id) {
        let a = document.getElementById(id); if(a) { a.currentTime=0; a.play(); }
    }
    function showGame(game) {
        document.getElementById('gameList').style.display = 'none';
        document.getElementById('jinhuaArea').style.display = (game==='jinhua') ? 'block' : 'none';
        document.getElementById('dialArea').style.display = (game==='dial') ? 'block' : 'none';
        document.getElementById('blackjackArea').style.display = (game==='blackjack') ? 'block' : 'none';
        document.getElementById('slotArea').style.display = (game==='slot') ? 'block' : 'none';
        if(game==='jinhua') loadJinhua();
        if(game==='dial') loadDial();
        if(game==='blackjack') loadBlackjack();
        if(game==='slot') loadSlot();
    }
    function backToHall() {
        document.getElementById('gameList').style.display = 'flex';
        document.getElementById('jinhuaArea').style.display = 'none';
        document.getElementById('dialArea').style.display = 'none';
        document.getElementById('blackjackArea').style.display = 'none';
        document.getElementById('slotArea').style.display = 'none';
    }
    // 炸金花演示
    let jinhuaState = {};
    function loadJinhua() {
        jinhuaState = {};
        let table = document.getElementById('jinhuaTable');
        table.innerHTML = `<button class='game-btn' onclick='jinhuaStart()'>开局发牌</button><div id='jinhuaResult' style='margin-top:18px;'></div>`;
    }
    function jinhuaStart() {
        let res = document.getElementById('jinhuaResult');
        res.innerHTML = '发牌中...';
        fetch('?action=Jinhua&liveuid=1&stream=test&token=abc').then(r=>r.json()).then(data=>{
            if(data.code===0 && data.info[0].cards) {
                playAudio('audioDeal');
                jinhuaState = {gameid:data.info[0].gameid, bets:[0,0,0], cards:data.info[0].cards, betCount:0};
                let html = '<div style="margin:10px 0;">';
                data.info[0].cards.forEach((hand,i)=>{
                    html += `<span id='jhand${i}' style='display:inline-block;margin:0 8px;'>`+hand.map(card=>renderCard(card,true)).join(' ')+`</span>`;
                });
                html += '</div>';
                html += `<div style='margin:10px 0;'>`;
                for(let i=0;i<3;i++) {
                    html += `<button class='bet-btn' onclick='jinhuaBet(${i+1})'>下注第${i+1}家</button>`;
                }
                html += `</div><div class='status-tip' id='jinhuaTip'>请选择下注位置，可多次下注</div>`;
                html += `<button class='game-btn' onclick='jinhuaEnd()'>比牌结算</button>`;
                res.innerHTML = html;
            } else {
                res.innerHTML = '开局失败：'+data.msg;
            }
        });
    }
    function jinhuaBet(pos) {
        let res = document.getElementById('jinhuaTip');
        if(!jinhuaState.gameid) return;
        playAudio('audioDeal');
        fetch(`?action=JinhuaBet&uid=1&gameid=${jinhuaState.gameid}&token=abc&coin=100&grade=${pos}`).then(r=>r.json()).then(data=>{
            jinhuaState.bets[pos-1] += 100;
            res.innerHTML = `已下注：${jinhuaState.bets.map((b,i)=>`第${i+1}家${b}`).join(' | ')}`;
        });
    }
    function jinhuaEnd() {
        let res = document.getElementById('jinhuaResult');
        if(!jinhuaState.gameid) return;
        fetch(`?action=endGame&liveuid=1&gameid=${jinhuaState.gameid}&token=abc&type=1&ifset=0`).then(r=>r.json()).then(endData=>{
            let winner = (endData.info[0]&&endData.info[0].winner)||0;
            playAudio('audioWin');
            let html = `<div>结算结果：<br>赢家位置：<b style='color:#ffd600;'>${parseInt(winner)+1}</b><br>牌面：<br>`;
            endData.info[0].cards.forEach((hand,i)=>{
                html += `<span id='jhand${i}' class='${i==winner?'winner card-anim':'card-anim'}' style='display:inline-block;margin:0 8px;'>`+hand.map(card=>renderCard(card)).join(' ')+`</span>`;
            });
            html += `</div><button class='game-btn' onclick='loadJinhua()'>再来一局</button>`;
            res.innerHTML = html;
        });
    }
    // 转盘演示
    let dialState = {};
    function loadDial() {
        dialState = {};
        let table = document.getElementById('dialTable');
        table.innerHTML = `<button class='game-btn' onclick='dialStart()'>开局下注</button><div id='dialResult' style='margin-top:18px;'></div>`;
    }
    function dialStart() {
        let res = document.getElementById('dialResult');
        res.innerHTML = '下注中...';
        fetch('?action=Dial&liveuid=1&stream=test&token=abc').then(r=>r.json()).then(data=>{
            if(data.code===0 && data.info[0].gameid) {
                playAudio('audioSpin');
                dialState = {gameid:data.info[0].gameid, bets:[0,0,0,0,0,0]};
                let html = `<svg id='dialSVG' width='160' height='160' viewBox='0 0 160 160' style='margin:10px 0;' class='dial-spin'>`;
                for(let i=0;i<6;i++) {
                    let angle = i*60;
                    html += `<path id='dsector${i}' class='dial-sector' d='M80,80 L${80+70*Math.cos((angle-30)*Math.PI/180)},${80+70*Math.sin((angle-30)*Math.PI/180)} A70,70 0 0,1 ${80+70*Math.cos((angle+30)*Math.PI/180)},${80+70*Math.sin((angle+30)*Math.PI/180)} Z' fill='${i%2==0?'#ffd600':'#ff9800'}' stroke='#fff' stroke-width='2'/>`;
                }
                html += `<circle cx='80' cy='80' r='30' fill='#fff' stroke='#888' stroke-width='2'/></svg>`;
                html += `<div style='margin:10px 0;'>`;
                for(let i=0;i<6;i++) {
                    html += `<button class='bet-btn' onclick='dialBet(${i+1})'>下注区${i+1}</button>`;
                }
                html += `</div><div class='status-tip' id='dialTip'>请选择下注区，可多次下注</div>`;
                html += `<button class='game-btn' onclick='dialEnd()'>开奖</button>`;
                res.innerHTML = html;
            } else {
                res.innerHTML = '开局失败：'+data.msg;
            }
        });
    }
    function dialBet(pos) {
        let res = document.getElementById('dialTip');
        if(!dialState.gameid) return;
        playAudio('audioDeal');
        fetch(`?action=Dial_Bet&uid=1&gameid=${dialState.gameid}&token=abc&coin=50&grade=${pos}`).then(r=>r.json()).then(data=>{
            dialState.bets[pos-1] += 50;
            res.innerHTML = `已下注：${dialState.bets.map((b,i)=>`区${i+1}:${b}`).join(' | ')}`;
        });
    }
    function dialEnd() {
        let res = document.getElementById('dialResult');
        if(!dialState.gameid) return;
        fetch(`?action=Dial_end&liveuid=1&gameid=${dialState.gameid}&token=abc&type=1&ifset=0`).then(r=>r.json()).then(endData=>{
            let result = (endData.info[0]&&endData.info[0].result)||0;
            playAudio('audioWin');
            let html = `<svg id='dialSVG' width='160' height='160' viewBox='0 0 160 160' style='margin:10px 0;'>`;
            for(let i=0;i<6;i++) {
                let angle = i*60;
                html += `<path id='dsector${i}' class='dial-sector${i==result?' dial-winner':''}' d='M80,80 L${80+70*Math.cos((angle-30)*Math.PI/180)},${80+70*Math.sin((angle-30)*Math.PI/180)} A70,70 0 0,1 ${80+70*Math.cos((angle+30)*Math.PI/180)},${80+70*Math.sin((angle+30)*Math.PI/180)} Z' fill='${i%2==0?'#ffd600':'#ff9800'}' stroke='#fff' stroke-width='2'/>`;
            }
            html += `<circle cx='80' cy='80' r='30' fill='#fff' stroke='#888' stroke-width='2'/></svg>`;
            html += `<div>开奖结果：幸运区块 <b style='color:#ffd600;'>${parseInt(result)+1}</b></div>`;
            html += `<button class='game-btn' onclick='loadDial()'>再来一局</button>`;
            res.innerHTML = html;
        });
    }
    // 21点小游戏
    function loadBlackjack() {
        let table = document.getElementById('bjTable');
        table.innerHTML = `<button class='game-btn' onclick='bjStart()'>发牌</button><div id='bjResult' style='margin-top:18px;'></div>`;
    }
    let bjState = {};
    function bjStart() {
        playAudio('audioDeal');
        bjState = {player:[],dealer:[],over:false};
        bjState.player.push(bjDrawCard());
        bjState.player.push(bjDrawCard());
        bjState.dealer.push(bjDrawCard());
        bjState.dealer.push(bjDrawCard());
        bjRender();
    }
    function bjDrawCard() {
        let n = Math.floor(Math.random()*13)+1;
        return n>10?10:n; // JQK算10
    }
    function bjSum(arr) {
        let sum = arr.reduce((a,b)=>a+b,0);
        let ace = arr.filter(x=>x==1).length;
        while(ace>0 && sum+10<=21) { sum+=10; ace--; }
        return sum;
    }
    function bjRender() {
        let res = document.getElementById('bjResult');
        let html = `<div>庄家：`+bjState.dealer.map((n,i)=>`<span class='bj-card${i==0?'':' bj-anim'}'><span class='bj-num'>${n}</span></span>`).join('')+`</div>`;
        html += `<div>玩家：`+bjState.player.map(n=>`<span class='bj-card bj-anim'><span class='bj-num'>${n}</span></span>`).join('')+`</div>`;
        html += `<div>玩家点数：<b>${bjSum(bjState.player)}</b></div>`;
        if(bjState.over) {
            let p = bjSum(bjState.player), d = bjSum(bjState.dealer);
            let msg = '';
            if(p>21) msg = '<span class="bj-bust">爆了！你输了</span>';
            else if(d>21) msg = '<span class="bj-win">庄家爆了，你赢了！</span>';
            else if(p==d) msg = '平局';
            else if(p>d) msg = '<span class="bj-win">你赢了！</span>';
            else msg = '<span class="bj-bust">你输了</span>';
            html += `<div style='margin:10px 0;'>${msg}</div><button class='game-btn' onclick='loadBlackjack()'>再来一局</button>`;
            playAudio(msg.includes('赢')?'audioWin':'audioDeal');
        } else {
            html += `<button class='bet-btn' onclick='bjHit()'>要牌</button><button class='bet-btn' onclick='bjStand()'>停牌</button>`;
        }
        res.innerHTML = html;
    }
    function bjHit() {
        bjState.player.push(bjDrawCard());
        if(bjSum(bjState.player)>=21) { bjState.over=true; bjDealerPlay(); }
        bjRender();
    }
    function bjStand() {
        bjState.over=true; bjDealerPlay(); bjRender();
    }
    function bjDealerPlay() {
        while(bjSum(bjState.dealer)<17) bjState.dealer.push(bjDrawCard());
    }
    // 老虎机小游戏
    function loadSlot() {
        let table = document.getElementById('slotTable');
        table.innerHTML = `<button class='game-btn' onclick='slotSpin()'>拉动拉杆</button><div id='slotResult' style='margin-top:18px;'></div>`;
    }
    function slotSpin() {
        playAudio('audioSpin');
        let res = document.getElementById('slotResult');
        let icons = ['🍒','🍋','🍊','🍉','⭐','7️⃣'];
        let r = [0,0,0];
        for(let i=0;i<3;i++) r[i]=Math.floor(Math.random()*icons.length);
        let html = `<div style='margin:10px 0;'>`+r.map(i=>`<span class='slot-reel slot-anim'>${icons[i]}</span>`).join('')+`</div>`;
        let win = (r[0]==r[1]&&r[1]==r[2]);
        html += `<div style='margin:10px 0;'>${win?'<span class="bj-win">恭喜！中三连！</span>':'再试一次吧~'}</div>`;
        html += `<button class='game-btn' onclick='loadSlot()'>再来一局</button>`;
        res.innerHTML = html;
        if(win) playAudio('audioWin');
    }
    // 牌面渲染
    function renderCard(card, anim) {
        let [color,num] = card.split('-');
        let colorMap = {1:'#388e3c',2:'#1976d2',3:'#d32f2f',4:'#fbc02d'};
        let numMap = {11:'J',12:'Q',13:'K',14:'A'};
        let showNum = numMap[num]||num;
        return `<span style='display:inline-block;width:32px;height:44px;background:#fff;border-radius:6px;border:2px solid #888;margin:0 2px;box-shadow:0 2px 8px #0003;position:relative;' class='${anim?'card-anim':''}'><span style='color:${colorMap[color]||'#222'};font-weight:bold;font-size:1.2em;position:absolute;left:6px;top:4px;'>${showNum}</span></span>`;
    }
    </script>
</body>
</html>