<?php
require_once __DIR__ . '/Database.php';
class Post {
    private $db;
    public function __construct() {
        $this->db = (new Database())->getPdo();
    }
    // 发帖
    public function create($forum_id, $user_id, $title, $content) {
        $stmt = $this->db->prepare('INSERT INTO posts (forum_id, user_id, title, content) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$forum_id, $user_id, $title, $content]);
    }
    // 获取帖子列表
    public function list($forum_id) {
        $stmt = $this->db->prepare('SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE forum_id = ? ORDER BY p.created_at DESC');
        $stmt->execute([$forum_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
// 简单接口路由
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $post = new Post();
    if ($action === 'create') {
        $res = $post->create($_POST['forum_id'], $_POST['user_id'], $_POST['title'], $_POST['content']);
        echo $res ? '发帖成功' : '发帖失败';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['forum_id'])) {
        $post = new Post();
        echo json_encode($post->list($_GET['forum_id']));
    }
}
