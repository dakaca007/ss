<?php
require_once __DIR__ . '/Database.php';
class Reply {
    private $db;
    public function __construct() {
        $this->db = (new Database())->getPdo();
    }
    // 回复
    public function create($post_id, $user_id, $content) {
        $stmt = $this->db->prepare('INSERT INTO replies (post_id, user_id, content) VALUES (?, ?, ?)');
        return $stmt->execute([$post_id, $user_id, $content]);
    }
    // 获取回复列表
    public function list($post_id) {
        $stmt = $this->db->prepare('SELECT r.*, u.username FROM replies r JOIN users u ON r.user_id = u.id WHERE post_id = ? ORDER BY r.created_at ASC');
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
// 简单接口路由
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $reply = new Reply();
    if ($action === 'create') {
        $res = $reply->create($_POST['post_id'], $_POST['user_id'], $_POST['content']);
        echo $res ? '回复成功' : '回复失败';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['post_id'])) {
        $reply = new Reply();
        echo json_encode($reply->list($_GET['post_id']));
    }
}
