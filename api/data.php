<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

global $db;

switch ($action) {
    case 'get_articles':
        $category_id = $_GET['category_id'] ?? null;
        $limit = $_GET['limit'] ?? 10;
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $results = searchArticles($search, $limit);
            echo json_encode($results);
        } else {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT a.*, c.name as category_name FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.status = 'published'";
            
            if ($category_id) {
                $sql .= " AND a.category_id = " . (int)$category_id;
            }
            
            $sql .= " ORDER BY a.published_at DESC LIMIT $offset, $limit";
            
            $result = $db->query($sql);
            $articles = $result->fetch_all(MYSQLI_ASSOC);
            
            echo json_encode($articles);
        }
        break;
    
    case 'get_article':
        $slug = $_GET['slug'] ?? '';
        $article = getArticleBySlug($slug);
        
        if ($article) {
            updateArticleViews($article['id']);
            echo json_encode($article);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Artikel tidak ditemukan']);
        }
        break;
    
    case 'get_comments':
        $article_id = (int)$_GET['article_id'];
        $comments = getComments($article_id);
        echo json_encode($comments);
        break;
    
    case 'add_comment':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $article_id = (int)$data['article_id'];
        $author_name = $data['author_name'] ?? '';
        $author_email = $data['author_email'] ?? '';
        $content = $data['content'] ?? '';
        
        if (!$article_id || !$author_name || !$author_email || !$content) {
            http_response_code(400);
            echo json_encode(['error' => 'Data tidak lengkap']);
            exit;
        }
        
        if (!isValidEmail($author_email)) {
            http_response_code(400);
            echo json_encode(['error' => 'Email tidak valid']);
            exit;
        }
        
        if (addComment($article_id, $author_name, $author_email, $content)) {
            echo json_encode(['success' => true, 'message' => 'Komentar berhasil disubmit dan menunggu persetujuan']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menambah komentar']);
        }
        break;
    
    case 'get_gallery':
        $category = $_GET['category'] ?? null;
        $gallery = getGallery($category);
        echo json_encode($gallery);
        break;
    
    case 'get_achievements':
        $achievements = getAchievements();
        echo json_encode($achievements);
        break;
    
    case 'get_extracurriculars':
        $type = $_GET['type'] ?? null;
        $extracurriculars = getExtracurriculars($type);
        echo json_encode($extracurriculars);
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Action tidak dikenali']);
}
