<?php
//require_once(dirname(__FILE__) . '/../../includes/smtp.php');
// postmark is not in git. Load it only when the folder has been copied onto the server.
$ohscPostmark = dirname(__FILE__) . '/../../postmark/basic.php';
if (is_file($ohscPostmark)) {
    require_once $ohscPostmark;
}
// ORIGINAL: session_start();
// LOCAL CHANGE: some pages already call session_start() before this file, and PHP 8 then prints "Ignoring session_start() because a session is already active". Start a session only when one is not active.
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
error_reporting(E_ALL);
// ORIGINAL: header('Cache-Control: max-age=31536000');
// LOCAL CHANGE: a one-year cache kept the old signup page, so the browser still blocked Submit on the reCAPTCHA error. Local pages are not cached. Other hosts keep the original header.
$cacheHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
if (stripos($cacheHost, 'tools.kasdo.org') !== false) {
    ini_set('display_errors', '1');
}
if (!headers_sent()) {
    if (preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/', $cacheHost)) {
        header('Cache-Control: no-store');
    } else {
        header('Cache-Control: max-age=31536000');
    }
}

$dbhost = "localhost";
$dbuser = "oxfordhomestudy_thedbuser";
$dbpass ="";
$dbname ="oxfordhomestudy_kasdo";

//Connect to MySQL Server
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($db->connect_errno) {
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=UTF-8');
    }
    echo "Database connection failed. In admin/classes/connection.php set dbuser, dbpass and dbname to the cPanel MySQL account for this site.\n";
    exit;
}


// ORIGINAL production URL:
// $base_url = 'https://www.oxfordhomestudy.com';
// LOCAL CHANGE: on localhost / 127.0.0.1, links stay on this machine (http://localhost:300). Any other host still uses the live URL.
// $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:300';
// if (preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/', $httpHost)) {
//     $base_url = 'http://' . $httpHost;
// } else {
//     $base_url = 'https://www.oxfordhomestudy.com';
// }
// TOOLS HOST: tools.kasdo.org uses its own URL. Localhost stays local. Every other host still uses the live site.
$httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:300';
if (preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/', $httpHost)) {
    $base_url = 'http://' . $httpHost;
} else if (stripos($httpHost, 'tools.kasdo.org') !== false) {
    $base_url = 'https://tools.kasdo.org';
} else {
    $base_url = 'https://www.oxfordhomestudy.com';
}

$uri_array = explode('/', $_SERVER['REQUEST_URI']);
switch (count($uri_array)) {
    case 2:
        $pdots = '../';
        break;
    case 3:
        $pdots = '../../';
        break;
    case 4:
        $pdots = '../../../';
        break;
    case 5:
        $pdots = '../../../../';
        break;
    default:
        $pdots = './';
        break;
}
