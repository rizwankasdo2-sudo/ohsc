<?php
require_once dirname(__FILE__) . '/includes/lock.php';
ini_set('display_errors', '1');
error_reporting(E_ALL);
include dirname(__DIR__) . '/admin/classes/connection.php';
if (!headers_sent()) {
    header('Content-Type: text/plain; charset=UTF-8');
}
echo 'php ' . PHP_VERSION . PHP_EOL;
echo 'host ' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . PHP_EOL;
echo 'base ' . (isset($base_url) ? $base_url : '') . PHP_EOL;
$check = $db->query('SELECT 1 AS ok');
echo $check ? "query ok\n" : ("query fail " . $db->error . "\n");
$seo = $db->query("SELECT seoid FROM tbl_seo WHERE seoid = '1'");
echo $seo ? ('seo rows ' . $seo->num_rows . PHP_EOL) : ('seo fail ' . $db->error . PHP_EOL);
