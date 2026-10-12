<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__FILE__) . '/../../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../includes/registry.php';

$path = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
$script = basename(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '');
$prettyMap = array(
    'index.php' => '/admin/tools-manager',
    'passages.php' => '/admin/typing-passages',
    'passage-form.php' => '/admin/typing-passage',
    'tool-form.php' => '/admin/tool-edit',
);

if (strpos($path, '/tools/admin') === 0 && isset($prettyMap[$script])) {
    $target = $base_url . $prettyMap[$script];
    if (!empty($_SERVER['QUERY_STRING'])) {
        $target .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('Location: ' . $target);
    exit;
}

if (strlen($path) > 1 && substr($path, -1) === '/') {
    $target = rtrim($path, '/');
    if (!empty($_SERVER['QUERY_STRING'])) {
        $target .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('Location: ' . $target);
    exit;
}

if (empty($_SESSION['adminid'])) {
    header('Location: ' . $base_url . '/admin/index.php');
    exit;
}

ohsc_tools_ensure($db);

if (empty($_SESSION['ohsc_tools_csrf'])) {
    $_SESSION['ohsc_tools_csrf'] = bin2hex(random_bytes(16));
}

function ohsc_tools_csrf_ok()
{
    if (empty($_POST['csrf']) || empty($_SESSION['ohsc_tools_csrf'])) {
        return false;
    }
    return hash_equals($_SESSION['ohsc_tools_csrf'], (string) $_POST['csrf']);
}

function ohsc_tools_admin_open($heading)
{
    global $base_url;
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ohsc_h($heading); ?></title>
<link rel="stylesheet" href="<?php echo ohsc_h($base_url); ?>/Resources/admin_css/admin-style.css" />
<link rel="stylesheet" href="<?php echo ohsc_h($base_url); ?>/Resources/admin_css/products.css" />
<script type="text/javascript" src="<?php echo ohsc_h($base_url); ?>/Resources/admin_js/csshorizontalmenu.js"></script>
<style>
#main_content input[type="text"], #main_content input[type="number"], #main_content textarea { width: 100%; max-width: 680px; padding: 8px; font-size: 14px; }
#main_content textarea { min-height: 180px; }
#main_content .tools-actions a, #main_content button.save-btn { background: #60487C; color: #fff; border: 0; padding: 8px 14px; cursor: pointer; text-decoration: none; display: inline-block; }
#main_content table.tools td { padding: 8px; border-bottom: 1px solid #e5e5e5; vertical-align: top; }
.tools-note { color: #333; }
.tools-error { color: #b00020; font-weight: bold; }
</style>
</head>
<body>
<div id="wrap_dashboard">
    <?php include dirname(__FILE__) . '/../../admin/includes/admin-header.php'; ?>
    <?php include dirname(__FILE__) . '/../../admin/includes/admin-menu.php'; ?>
    <div id="outer_contanier">
        <div id="board">
            <div id="left_corner"></div>
            <div id="midle_repeat">
                <div id="productsheading"><h3><?php echo ohsc_h($heading); ?></h3></div>
            </div>
            <div id="right_corner"></div>
            <div id="main_content">
                <p class="tools-actions">
                    <a href="<?php echo ohsc_h($base_url); ?>/admin/tools-manager">Tools</a>
                    <a href="<?php echo ohsc_h($base_url); ?>/admin/typing-passages">Typing texts</a>
                </p>
    <?php
}

function ohsc_tools_admin_close()
{
    ?>
            </div>
        </div>
        <?php include dirname(__FILE__) . '/../../admin/includes/admin-footer.php'; ?>
    </div>
</div>
</body>
</html>
    <?php
}
