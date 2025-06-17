<?php
// 游戏业务逻辑类
class GameDomain {
    private $redis;
    public function __construct() {
        $this->redis = new Redis();
        $this->redis->connect('redis', 6379);
    }

    // ---------------- 炸金花核心逻辑 ----------------
    public function Jinhua($liveuid, $stream, $token) {
        $roomKey = "jinhua_room_{$stream}";
        $gameid = uniqid('jinhua_');
        $cards = $this->dealJinhuaCards();
        $game = [
            'gameid' => $gameid,
            'liveuid' => $liveuid,
            'stream' => $stream,
            'token' => $token,
            'cards' => $cards,
            'bets' => [0,0,0],
            'status' => 'betting',
            'start_time' => time(),
        ];
        $this->redis->set($roomKey, json_encode($game));
        return [
            'time' => 30,
            'token' => $gameid,
            'gameid' => $gameid,
            'cards' => $cards
        ];
    }
    public function JinhuaBet($uid, $gameid, $token, $coin, $grade) {
        $roomKey = $this->findJinhuaRoomByGameid($gameid);
        if (!$roomKey) return 1001;
        $game = json_decode($this->redis->get($roomKey), true);
        if ($game['status'] !== 'betting') return 1001;
        $grade = intval($grade) - 1;
        $game['bets'][$grade] += intval($coin);
        $this->redis->set($roomKey, json_encode($game));
        return [
            'uid' => $uid,
            'coin' => rand(100, 1000),
            'level' => rand(1, 10),
            'bets' => $game['bets']
        ];
    }
    public function endGame($liveuid, $gameid, $type, $ifset) {
        $roomKey = $this->findJinhuaRoomByGameid($gameid);
        if (!$roomKey) return 1000;
        $game = json_decode($this->redis->get($roomKey), true);
        $game['status'] = 'ended';
        $winner = $this->getJinhuaWinner($game['cards']);
        $game['winner'] = $winner;
        $this->redis->set($roomKey, json_encode($game));
        return [
            'msg' => '游戏已关闭',
            'winner' => $winner,
            'cards' => $game['cards'],
            'bets' => $game['bets']
        ];
    }
    private function dealJinhuaCards() {
        $cards = [];
        $deck = [];
        foreach ([1,2,3,4] as $color) {
            for ($num=2;$num<=14;$num++) $deck[] = "$color-$num";
        }
        shuffle($deck);
        $cards[] = array_slice($deck, 0, 3);
        $cards[] = array_slice($deck, 3, 3);
        $cards[] = array_slice($deck, 6, 3);
        return $cards;
    }
    private function getJinhuaWinner($cards) {
        // 简化：随机选一个赢家
        return rand(0,2);
    }
    private function findJinhuaRoomByGameid($gameid) {
        foreach($this->redis->keys('jinhua_room_*') as $key) {
            $game = json_decode($this->redis->get($key), true);
            if ($game['gameid'] === $gameid) return $key;
        }
        return false;
    }

    // ---------------- 转盘核心逻辑 ----------------
    public function Dial($liveuid, $stream, $token) {
        $roomKey = "dial_room_{$stream}";
        $gameid = uniqid('dial_');
        $game = [
            'gameid' => $gameid,
            'liveuid' => $liveuid,
            'stream' => $stream,
            'token' => $token,
            'bets' => [0,0,0,0,0,0],
            'status' => 'betting',
            'start_time' => time(),
        ];
        $this->redis->set($roomKey, json_encode($game));
        return [
            'time' => 30,
            'token' => $gameid,
            'gameid' => $gameid
        ];
    }
    public function Dial_Bet($uid, $gameid, $token, $coin, $grade) {
        $roomKey = $this->findDialRoomByGameid($gameid);
        if (!$roomKey) return 1001;
        $game = json_decode($this->redis->get($roomKey), true);
        if ($game['status'] !== 'betting') return 1001;
        $grade = intval($grade) - 1;
        $game['bets'][$grade] += intval($coin);
        $this->redis->set($roomKey, json_encode($game));
        return [
            'uid' => $uid,
            'coin' => rand(100, 1000),
            'level' => rand(1, 10),
            'bets' => $game['bets']
        ];
    }
    public function Dial_end($liveuid, $gameid, $type, $ifset) {
        $roomKey = $this->findDialRoomByGameid($gameid);
        if (!$roomKey) return 1000;
        $game = json_decode($this->redis->get($roomKey), true);
        $game['status'] = 'ended';
        $result = rand(0,5);
        $game['result'] = $result;
        $this->redis->set($roomKey, json_encode($game));
        return [
            'msg' => '转盘已开奖',
            'result' => $result,
            'bets' => $game['bets']
        ];
    }
    private function findDialRoomByGameid($gameid) {
        foreach($this->redis->keys('dial_room_*') as $key) {
            $game = json_decode($this->redis->get($key), true);
            if ($game['gameid'] === $gameid) return $key;
        }
        return false;
    }

    // 其他接口保持原有模拟逻辑...
    public function settleGame($uid, $gameid) {
        return [
            'gamecoin' => rand(10, 100),
            'coin' => rand(100, 1000),
            'banker_profit' => rand(0, 50),
            'isshow' => rand(0, 1)
        ];
    }
    public function checkGame($liveuid, $stream) {
        return [
            'gamecoin' => rand(10, 100),
            'coin' => rand(100, 1000)
        ];
    }
    public function getGameRecord($action, $stream) {
        $list = [];
        for ($i = 0; $i < 10; $i++) {
            $list[] = [rand(0, 1), rand(0, 1), rand(0, 1), rand(0, 1)];
        }
        return $list;
    }
    public function getBankerProfit($bankerid, $action, $stream) {
        return [
            ['banker_profit' => rand(10, 100)]
        ];
    }
    public function getBanker($stream) {
        return [
            ['id' => 1, 'user_nickname' => '庄家A', 'avatar' => '', 'coin' => 1000],
            ['id' => 2, 'user_nickname' => '庄家B', 'avatar' => '', 'coin' => 800]
        ];
    }
    public function setBanker($uid, $deposit, $stream) {
        return ['code'=>0,'msg'=>'申请成功','info'=>[['coin'=>rand(1000,5000),'msg'=>'申请成功']]];
    }
    public function quietBanker($uid, $stream) {
        return ['code'=>0,'msg'=>'','info'=>[['msg'=>'下庄成功']]];
    }
}
