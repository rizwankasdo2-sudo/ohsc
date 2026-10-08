<?php
include('admin/classes/connection.php');
include_once('includes/functions.php');
require_once('includes/blog-landing-helpers.php');

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/i', '', (string) $_GET['slug']) : '';
if ($slug === '') {
    header('HTTP/1.1 404 Not Found');
    header('Refresh:0; url=' . rtrim($base_url, '/') . '/page-not-found');
    exit();
}

$blog = loadPublishedBlogPostBySlug($db, $slug);
if ($blog) {
    $_GET['slug'] = $slug;
    require __DIR__ . '/blog-article.php';
    exit();
}

$slugSql = mysqli_real_escape_string($db, $slug);
$categoryRow = $db->query("SELECT id FROM blog_categories WHERE slug = '$slugSql' LIMIT 1")->fetch_array();
if ($categoryRow) {
    $_GET['slug'] = $slug;
    require __DIR__ . '/blog-category.php';
    exit();
}

header('HTTP/1.1 404 Not Found');
header('Refresh:0; url=' . rtrim($base_url, '/') . '/page-not-found');
exit();
