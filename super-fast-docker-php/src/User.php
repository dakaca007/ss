<?php
require_once __DIR__ . '/Database.php';

class User {
    private $db;
    public function __construct() {
        $this->db = (new Database())->getPdo();
    }
    // 注册
    public function register($username, $password) {
        $stmt = $this->db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([$username, $hash]);
    }
    // 登录
    public function login($username, $password) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
// 简单接口路由
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user = new User();
    if ($action === 'register') {
        $res = $user->register($_POST['username'], $_POST['password']);
        echo $res ? '注册成功' : '注册失败';
    } elseif ($action === 'login') {
        $res = $user->login($_POST['username'], $_POST['password']);
        echo $res ? json_encode($res) : '登录失败';
    }
}
