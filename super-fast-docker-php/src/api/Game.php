<?php
// 游戏接口控制器
require_once __DIR__ . '/../domain/GameDomain.php';
require_once __DIR__ . '/../helpers.php';

class GameApi {
    public function handle($action, $params) {
        $domain = new GameDomain();
        switch ($action) {
            case 'settleGame':
                return $this->settleGame($params, $domain);
            case 'checkGame':
                return $this->checkGame($params, $domain);
            case 'Jinhua':
                return $this->Jinhua($params, $domain);
            case 'endGame':
                return $this->endGame($params, $domain);
            case 'JinhuaBet':
                return $this->JinhuaBet($params, $domain);
            case 'Dial':
                return $this->Dial($params, $domain);
            case 'Dial_end':
                return $this->Dial_end($params, $domain);
            case 'Dial_Bet':
                return $this->Dial_Bet($params, $domain);
            case 'getGameRecord':
                return $this->getGameRecord($params, $domain);
            case 'getBankerProfit':
                return $this->getBankerProfit($params, $domain);
            case 'getBanker':
                return $this->getBanker($params, $domain);
            case 'setBanker':
                return $this->setBanker($params, $domain);
            case 'quietBanker':
                return $this->quietBanker($params, $domain);
            case 'Slot':
                return $this->Slot($params);
            default:
                return ['code' => 404, 'msg' => '接口不存在', 'info' => []];
        }
    }
    public function settleGame($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $info = $domain->settleGame($uid, $gameid);
        if ($info === 1000) {
            return ['code' => 1000, 'msg' => '游戏信息不存在', 'info' => []];
        }
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function checkGame($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        $info = $domain->checkGame($liveuid, $stream);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function Jinhua($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        $token = checkNull($params['token'] ?? '');
        if ($liveuid < 1 || $token == '' || $stream == '') {
            return ['code' => 1001, 'msg' => '信息错误', 'info' => []];
        }
        $checkToken = checkToken($liveuid, $token);
        if ($checkToken == 700) {
            return ['code' => 700, 'msg' => '您的登陆状态失效，请重新登陆！', 'info' => []];
        }
        $info = $domain->Jinhua($liveuid, $stream, $token);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function endGame($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $type = checkNull($params['type'] ?? '');
        $ifset = checkNull($params['ifset'] ?? '');
        $checkToken = checkToken($liveuid, $token);
        if ($checkToken == 700) {
            return ['code' => 700, 'msg' => '您的登陆状态失效，请重新登陆！', 'info' => []];
        }
        $info = $domain->endGame($liveuid, $gameid, $type, $ifset);
        if ($info === 1000) {
            return ['code' => 1000, 'msg' => '该游戏已经被关闭', 'info' => []];
        }
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function JinhuaBet($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $coin = checkNull($params['coin'] ?? '');
        $grade = checkNull($params['grade'] ?? '');
        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            return ['code' => 700, 'msg' => '您的登陆状态失效，请重新登陆！', 'info' => []];
        }
        $info = $domain->JinhuaBet($uid, $gameid, $token, $coin, $grade);
        if ($info === 1000) {
            return ['code' => 1000, 'msg' => '你的余额不足，无法下注', 'info' => []];
        } elseif ($info === 1001) {
            return ['code' => 1001, 'msg' => '本轮游戏已经结束', 'info' => []];
        } elseif ($info === 1002) {
            return ['code' => 1002, 'msg' => '下注失败', 'info' => []];
        } elseif ($info === 1003) {
            return ['code' => 1003, 'msg' => '下注金额已达上限', 'info' => []];
        }
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function Dial($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        $token = checkNull($params['token'] ?? '');
        if ($liveuid < 1 || $token == '' || $stream == '') {
            return ['code' => 1001, 'msg' => '信息错误', 'info' => []];
        }
        $checkToken = checkToken($liveuid, $token);
        if ($checkToken == 700) {
            return ['code' => 700, 'msg' => '您的登陆状态失效，请重新登陆！', 'info' => []];
        }
        $info = $domain->Dial($liveuid, $stream, $token);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function Dial_end($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $type = checkNull($params['type'] ?? '');
        $ifset = checkNull($params['ifset'] ?? '');
        $checkToken = checkToken($liveuid, $token);
        if ($checkToken == 700) {
            return ['code' => 700, 'msg' => '您的登陆状态失效，请重新登陆！', 'info' => []];
        }
        $info = $domain->Dial_end($liveuid, $gameid, $type, $ifset);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function Dial_Bet($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $coin = checkNull($params['coin'] ?? '');
        $grade = checkNull($params['grade'] ?? '');
        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            return ['code' => 700, 'msg' => '您的登陆状态失效，请重新登陆！', 'info' => []];
        }
        $info = $domain->Dial_Bet($uid, $gameid, $token, $coin, $grade);
        if ($info === 1000) {
            return ['code' => 1000, 'msg' => '你的余额不足，无法下注', 'info' => []];
        } elseif ($info === 1001) {
            return ['code' => 1001, 'msg' => '本轮游戏已经结束', 'info' => []];
        } elseif ($info === 1002) {
            return ['code' => 1002, 'msg' => '下注失败', 'info' => []];
        } elseif ($info === 1003) {
            return ['code' => 1003, 'msg' => '下注金额已达上限', 'info' => []];
        }
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function getGameRecord($params, $domain) {
        $action = checkNull($params['action'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        $info = $domain->getGameRecord($action, $stream);
        return ['code' => 0, 'msg' => '', 'info' => $info];
    }
    public function getBankerProfit($params, $domain) {
        $bankerid = checkNull($params['bankerid'] ?? '');
        $action = 4;
        $stream = checkNull($params['stream'] ?? '');
        $info = $domain->getBankerProfit($bankerid, $action, $stream);
        return ['code' => 0, 'msg' => '', 'info' => $info];
    }
    public function getBanker($params, $domain) {
        $stream = checkNull($params['stream'] ?? '');
        $info = $domain->getBanker($stream);
        return ['code' => 0, 'msg' => '', 'info' => $info];
    }
    public function setBanker($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        $deposit = checkNull($params['deposit'] ?? '');
        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            return ['code' => 700, 'msg' => '您的登陆状态失效，请重新登陆！', 'info' => []];
        }
        $info = $domain->setBanker($uid, $deposit, $stream);
        return $info;
    }
    public function quietBanker($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        $info = $domain->quietBanker($uid, $stream);
        return $info;
    }
    public function Slot($params) {
        // 老虎机符号
        $symbols = [0,1,2,3,4,5]; // 前端有6种符号
        $rows = 3; $cols = 3;
        $result = [];
        for ($r=0;$r<$rows;$r++) {
            $row = [];
            for ($c=0;$c<$cols;$c++) {
                $row[] = $symbols[array_rand($symbols)];
            }
            $result[] = $row;
        }
        // 判断中奖：任意一行/列/对角线全相同即为中奖
        $win = false;
        // 行
        foreach($result as $row) if(count(array_unique($row))==1) $win=true;
        // 列
        for($c=0;$c<$cols;$c++){
            $col = [$result[0][$c],$result[1][$c],$result[2][$c]];
            if(count(array_unique($col))==1) $win=true;
        }
        // 对角线
        if($result[0][0]==$result[1][1]&&$result[1][1]==$result[2][2]) $win=true;
        if($result[0][2]==$result[1][1]&&$result[1][1]==$result[2][0]) $win=true;
        return [
            'code'=>0,
            'result'=>$result,
            'win'=>$win?1:0
        ];
    }
}

// 路由分发
if (php_sapi_name() !== 'cli') {
    header('Content-Type: application/json');
    $action = $_GET['action'] ?? '';
    $params = array_merge($_GET, $_POST);
    // 兼容 Authorization 头
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $params['token'] = $_SERVER['HTTP_AUTHORIZATION'];
        $params['uid'] = 1; // 模拟uid
    }
    $api = new GameApi();
    $res = $api->handle($action, $params);
    echo json_encode($res);
    exit;
}
