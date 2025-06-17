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
            default:
                return ['code' => 404, 'msg' => '接口不存在', 'info' => []];
        }
    }

    public function settleGame($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $info = $domain->settleGame($uid, $gameid);
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
        $info = $domain->Jinhua($liveuid, $stream, $token);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function endGame($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $type = checkNull($params['type'] ?? '');
        $ifset = checkNull($params['ifset'] ?? '');
        $info = $domain->endGame($liveuid, $gameid, $type, $ifset);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function JinhuaBet($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $coin = checkNull($params['coin'] ?? '');
        $grade = checkNull($params['grade'] ?? '');
        $info = $domain->JinhuaBet($uid, $gameid, $token, $coin, $grade);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function Dial($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $info = $domain->Dial($liveuid, $stream, $token);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function Dial_end($params, $domain) {
        $liveuid = checkNull($params['liveuid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $type = checkNull($params['type'] ?? '');
        $ifset = checkNull($params['ifset'] ?? '');
        $info = $domain->Dial_end($liveuid, $gameid, $type, $ifset);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function Dial_Bet($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $gameid = checkNull($params['gameid'] ?? '');
        $token = checkNull($params['token'] ?? '');
        $coin = checkNull($params['coin'] ?? '');
        $grade = checkNull($params['grade'] ?? '');
        $info = $domain->Dial_Bet($uid, $gameid, $token, $coin, $grade);
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
        $info = $domain->setBanker($uid);
        return ['code' => 0, 'msg' => '', 'info' => [$info]];
    }
    public function quietBanker($params, $domain) {
        $uid = checkNull($params['uid'] ?? '');
        $stream = checkNull($params['stream'] ?? '');
        return ['code' => 0, 'msg' => '', 'info' => [['msg' => '下庄成功']]];
    }
}
