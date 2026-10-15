<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=UTF-8');
echo 'php ' . PHP_VERSION . PHP_EOL;
echo 'host ' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . PHP_EOL;
try {
    include dirname(__DIR__) . '/admin/classes/connection.php';
    echo 'connection ok' . PHP_EOL;
    echo 'base ' . (isset($base_url) ? $base_url : '') . PHP_EOL;
} catch (Throwable $e) {
    echo 'ERR ' . $e->getMessage() . PHP_EOL;
    echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;
}
