<?php
// 五子棋核心逻辑
class GomokuDomain {
    private $redis;
    public function __construct() {
        $this->redis = new Redis();
        $this->redis->connect('redis', 6379);
    }
    public function createRoom($uid) {
        $roomId = uniqid('gomoku_');
        $board = array_fill(0, 15, array_fill(0, 15, 0));
        $data = [
            'board' => $board,
            'players' => [$uid],
            'turn' => $uid,
            'winner' => 0
        ];
        $this->redis->set($roomId, json_encode($data));
        return $roomId;
    }
    public function joinRoom($roomId, $uid) {
        $data = json_decode($this->redis->get($roomId), true);
        if (!$data) return ['code'=>-1, 'msg'=>'房间不存在'];
        if (count($data['players']) >= 2) return ['code'=>-1, 'msg'=>'房间已满'];
        if (in_array($uid, $data['players'])) return ['code'=>0, 'msg'=>'已在房间'];
        $data['players'][] = $uid;
        $this->redis->set($roomId, json_encode($data));
        return ['code'=>0, 'msg'=>'加入成功'];
    }
    public function move($roomId, $uid, $x, $y) {
        $data = json_decode($this->redis->get($roomId), true);
        if (!$data) return ['code'=>-1, 'msg'=>'房间不存在'];
        if ($data['winner']) return ['code'=>-1, 'msg'=>'游戏已结束'];
        if (!in_array($uid, $data['players'])) return ['code'=>-1, 'msg'=>'未加入房间'];
        $color = array_search($uid, $data['players'])+1;
        if ($data['turn'] != $uid) return ['code'=>-1, 'msg'=>'未轮到你'];
        if ($x<0||$x>14||$y<0||$y>14) return ['code'=>-1, 'msg'=>'坐标非法'];
        if ($data['board'][$x][$y]!=0) return ['code'=>-1, 'msg'=>'该位置已落子'];
        $data['board'][$x][$y] = $color;
        $data['turn'] = $data['players'][1-$color+1];
        $winner = $this->checkWinner($data['board'], $x, $y, $color);
        if ($winner) $data['winner'] = $uid;
        $this->redis->set($roomId, json_encode($data));
        return ['code'=>0, 'msg'=>'落子成功', 'winner'=>$winner?$uid:0, 'board'=>$data['board']];
    }
    public function getBoard($roomId) {
        $data = json_decode($this->redis->get($roomId), true);
        if (!$data) return ['code'=>-1, 'msg'=>'房间不存在'];
        return ['code'=>0, 'board'=>$data['board'], 'players'=>$data['players'], 'turn'=>$data['turn'], 'winner'=>$data['winner']];
    }
    private function checkWinner($board, $x, $y, $color) {
        $dirs = [[1,0],[0,1],[1,1],[1,-1]];
        foreach ($dirs as $d) {
            $cnt = 1;
            for ($i=1;$i<5;$i++) {
                $nx=$x+$d[0]*$i; $ny=$y+$d[1]*$i;
                if ($nx<0||$nx>14||$ny<0||$ny>14||$board[$nx][$ny]!=$color) break;
                $cnt++;
            }
            for ($i=1;$i<5;$i++) {
                $nx=$x-$d[0]*$i; $ny=$y-$d[1]*$i;
                if ($nx<0||$nx>14||$ny<0||$ny>14||$board[$nx][$ny]!=$color) break;
                $cnt++;
            }
            if ($cnt>=5) return true;
        }
        return false;
    }
}
