<?php
// 五子棋接口控制器
require_once __DIR__ . '/../../helpers.php';
require_once __DIR__ . '/../../domain/gomoku/GomokuDomain.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$domain = new GomokuDomain();

switch ($action) {
    case 'createRoom':
        $user = checkToken();
        $roomId = $domain->createRoom($user['uid']);
        echo json_encode(['code'=>0, 'roomId'=>$roomId]);
        break;
    case 'joinRoom':
        $user = checkToken();
        $roomId = $_POST['roomId'] ?? '';
        $res = $domain->joinRoom($roomId, $user['uid']);
        echo json_encode($res);
        break;
    case 'move':
        $user = checkToken();
        $roomId = $_POST['roomId'] ?? '';
        $x = intval($_POST['x'] ?? -1);
        $y = intval($_POST['y'] ?? -1);
        $res = $domain->move($roomId, $user['uid'], $x, $y);
        echo json_encode($res);
        break;
    case 'getBoard':
        $roomId = $_GET['roomId'] ?? '';
        $res = $domain->getBoard($roomId);
        echo json_encode($res);
        break;
    default:
        echo json_encode(['code'=>-1, 'msg'=>'Invalid action']);
}
