<?php
header('X-Robots-Tag: noindex, nofollow', true);
include('../admin/classes/connection.php');
include_once('../includes/functions.php');
require_once('../includes/blog-landing-helpers.php');
require_once('../includes/blog-category-helpers.php');

header('Content-Type: application/json; charset=utf-8');

$category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$sort = isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'oldest' : 'latest';
$per_page = 5;

if ($category_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid category.']);
    exit();
}

$category_row = $db->query('SELECT id, name FROM blog_categories WHERE id = ' . $category_id . ' LIMIT 1')->fetch_array();
if (!$category_row) {
    http_response_code(404);
    echo json_encode(['error' => 'Category not found.']);
    exit();
}

$category_name = trim((string) $category_row['name']);
$total = countBlogCategoryArticles($db, $category_id);
$total_pages = max(1, (int) ceil($total / $per_page));
$page = max(1, min($page, $total_pages));
$posts = loadBlogCategoryArticles($db, $category_id, $page, $per_page, $sort);

echo json_encode([
    'articles_html' => renderBlogCategoryArticlesHtml($posts, $category_name),
    'pagination_html' => renderBlogCategoryPaginationHtml($page, $per_page, $total),
    'summary_html' => getBlogCategoryArticlesSummaryText($page, $per_page, $total),
    'page' => $page,
    'total' => $total,
    'total_pages' => $total_pages,
]);
