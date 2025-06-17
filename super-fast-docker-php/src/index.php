<?php
// 入口文件，仅保留并优化经典老虎机
?>
<!DOCTYPE html>
<html lang='zh-cn'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'>
    <title>经典老虎机</title>
    <style>
        body { margin:0; padding:0; font-family:sans-serif; background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%); color:#fff; }
        .container { max-width:420px; margin:40px auto; padding:24px; background:rgba(0,0,0,0.85); border-radius:18px; box-shadow:0 8px 32px #0008; }
        h1 { font-size:2.2em; text-align:center; letter-spacing:2px; margin-bottom:18px; }
        .game-btn-slot{background:linear-gradient(90deg,#ff7043,#ffd600);color:#fff;font-weight:bold;border-radius:8px;padding:14px 0;margin:18px 0;box-shadow:0 2px 8px #ff704388;border:none;transition:.2s;display:block;width:100%;font-size:1.2em;}
        .game-btn-slot:active{background:#ffd600;color:#bf360c;transform:scale(0.98);}
        #slotModal .modal-content{background:linear-gradient(135deg,#fffde7 60%,#ffe082 100%);box-shadow:0 0 24px #ff704388;border-radius:16px;}
        .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.8); }
        .modal-content { background-color:#222; margin:10% auto; padding:20px; border:1px solid #888; width:92%; max-width:400px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.2); }
        .close { color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer; }
        .close:hover, .close:focus { color:#fff; text-decoration:none; cursor:pointer; }
        @media (max-width: 600px) {
            .container { margin:8px; padding:8px; }
            .modal-content { width:98%; padding:10px; }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>经典老虎机</h1>
        <button class="game-btn-slot" onclick="showSlot()">老虎机 <span style="font-size:18px;">🎰</span></button>
    </div>
    <!-- 老虎机弹窗 -->
    <div id="slotModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeSlot()">&times;</span>
        <h2 style="margin-bottom:10px;">经典老虎机 <span id="slotThemeBtn" style="cursor:pointer;">🎨</span></h2>
        <div id="slotMachine"></div>
        <div id="slotStatus" style="margin:10px 0 16px 0;"></div>
        <button id="slotLeverBtn" onclick="slotSpin()" style="font-size:20px;width:100%;padding:12px 0;border-radius:10px;background:#ffd600;color:#bf360c;font-weight:bold;">拉杆</button>
        <button onclick="closeSlot()" style="width:100%;margin-top:10px;">退出</button>
      </div>
    </div>
    <audio id="slotSpinAudio" src="https://cdn.pixabay.com/audio/2022/07/26/audio_124b7e7e7e.mp3" preload="auto"></audio>
    <audio id="slotWinAudio" src="https://cdn.pixabay.com/audio/2022/03/15/audio_115b9b3b3e.mp3" preload="auto"></audio>
    <script>
// 老虎机主题与符号
const slotThemes=[
  {body:'#ffd600',panel:'#fffde7',border:'#ff7043',lever:'#bf360c',win:'#ff1744',symbol:['🍒','🔔','🍋','7️⃣','⭐','🍀']},
  {body:'#fffde7',panel:'#ffd600',border:'#bf360c',lever:'#ff7043',win:'#43a047',symbol:['🍒','🍋','🔔','7️⃣','🍀','⭐']},
  {body:'#ffe082',panel:'#fff',border:'#ff7043',lever:'#ffd600',win:'#0ff',symbol:['🍋','🍒','7️⃣','🔔','⭐','🍀']}
];
let slotTheme=0,slotLastData={};
// 主题切换
function bindSlotThemeBtn(){
  document.getElementById('slotThemeBtn').onclick=function(){
    slotTheme=(slotTheme+1)%slotThemes.length;
    renderSlotMachine(slotLastData);
  };
}
// 打开弹窗并自动拉一次
function showSlot(){
  document.getElementById('slotModal').style.display='block';
  bindSlotThemeBtn();
  slotSpin();
}
// 关闭弹窗
function closeSlot(){
  document.getElementById('slotModal').style.display='none';
  document.getElementById('slotStatus').innerText='';
}
// 拉杆动画与请求
function slotSpin(){
  setBtnDisabled('slotLeverBtn',true);
  let headers = {};
  if(localStorage.token) headers['Authorization'] = localStorage.token;
  fetch('src/api/Game.php?action=Slot',{headers}).then(r=>r.json()).then(d=>{
    if(d.code===0){slotLastData=d;renderSlotMachine(d,true);}else alert(d.msg);});
}
// 渲染老虎机SVG
function renderSlotMachine(d,spin){
  let t=slotThemes[slotTheme],syms=t.symbol;
  let rows=3,cols=3,cellW=60,cellH=60,ox=60,oy=60;
  let svg=`<svg width='320' height='260' style='background:${t.body};border-radius:24px;box-shadow:0 0 24px #ff704388;'>`;
  svg+=`<rect x='10' y='10' width='300' height='240' rx='32' fill='${t.body}' stroke='${t.border}' stroke-width='8'/>`;
  svg+=`<rect x='40' y='40' width='240' height='140' rx='18' fill='${t.panel}' stroke='${t.border}' stroke-width='4'/>`;
  for(let c=0;c<cols;c++)for(let r=0;r<rows;r++){
    let x=ox+c*cellW,y=oy+r*cellH;
    let symIdx = d.result && d.result[r] && typeof d.result[r][c]!=="undefined" ? d.result[r][c]%syms.length : 0;
    let sym = syms[symIdx];
    svg+=`<rect x='${x}' y='${y}' width='${cellW}' height='${cellH}' rx='12' fill='#fff' stroke='${t.border}' stroke-width='2'/><text x='${x+cellW/2}' y='${y+cellH/2+12}' text-anchor='middle' font-size='36' font-weight='bold' fill='#333'>${sym}</text>`;
  }
  svg+=`<rect x='290' y='60' width='16' height='80' rx='8' fill='${t.lever}' stroke='#fff' stroke-width='2'/><circle cx='298' cy='60' r='12' fill='${t.lever}' stroke='#fff' stroke-width='2'/>`;
  svg+='</svg>';
  document.getElementById('slotMachine').innerHTML=svg;
  if(spin){
    playAudio('slotSpinAudio');
    let n=20,interval=60,step=0;
    let anim=setInterval(()=>{
      for(let c=0;c<cols;c++)for(let r=0;r<rows;r++){
        let x=ox+c*cellW,y=oy+r*cellH,sym=syms[Math.floor(Math.random()*syms.length)];
        let el=document.querySelector('#slotMachine svg');
        if(el){
          let texts=el.querySelectorAll('text');
          let idx=r*cols+c;
          if(texts[idx])texts[idx].textContent=sym;
        }
      }
      if(++step>=n){
        clearInterval(anim);
        setTimeout(()=>{renderSlotMachine(d,false);if(d.win){playAudio('slotWinAudio');setStatus('中奖!');}else{setStatus('未中奖');}setBtnDisabled('slotLeverBtn',false);},200);
      }
    },interval);
  }else{
    setBtnDisabled('slotLeverBtn',false);
    if(d.win){playAudio('slotWinAudio');setStatus('中奖!');}else{setStatus('未中奖');}
  }
}
// 工具函数
function setBtnDisabled(id, dis) {
  let btn = document.getElementById(id); if(btn) btn.disabled = dis;
}
function playAudio(id) {
  let a = document.getElementById(id); if(a) { a.currentTime=0; a.play(); }
}
function setStatus(msg) {
  document.getElementById('slotStatus').innerText=msg;
}
</script>
</body>
</html>