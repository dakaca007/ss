<?php
// This is the entry point of the application.
// You can write PHP code here to handle requests and return responses for your game logic.

echo "<!DOCTYPE html>
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
</html>";
?>