<?php
// Browser login for the whole tools.kasdo.org site. Localhost stays open.
if (defined('OHSC_SITE_LOCK')) {
    return;
}
define('OHSC_SITE_LOCK', true);

$ohscLockHost = isset($_SERVER['HTTP_HOST']) ? (string) $_SERVER['HTTP_HOST'] : '';
if (stripos($ohscLockHost, 'tools.kasdo.org') === false) {
    return;
}

if (!headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet, noimageindex');
}

$ohscLockUser = 'ohsc';
$ohscLockPass = 'OhscTools26';
$ohscGivenUser = isset($_SERVER['PHP_AUTH_USER']) ? (string) $_SERVER['PHP_AUTH_USER'] : '';
$ohscGivenPass = isset($_SERVER['PHP_AUTH_PW']) ? (string) $_SERVER['PHP_AUTH_PW'] : '';
if ($ohscGivenUser === '' && $ohscGivenPass === '') {
    $ohscLockHeader = '';
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        $ohscLockHeader = (string) $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $ohscLockHeader = (string) $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    }
    if (stripos($ohscLockHeader, 'basic ') === 0) {
        $ohscDecoded = base64_decode(substr($ohscLockHeader, 6));
        if ($ohscDecoded !== false && strpos($ohscDecoded, ':') !== false) {
            $ohscParts = explode(':', $ohscDecoded, 2);
            $ohscGivenUser = $ohscParts[0];
            $ohscGivenPass = $ohscParts[1];
        }
    }
}

if (!hash_equals($ohscLockUser, $ohscGivenUser) || !hash_equals($ohscLockPass, $ohscGivenPass)) {
    if (!headers_sent()) {
        header('WWW-Authenticate: Basic realm="Courses with Certificates Online | OHSC"');
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: text/plain; charset=UTF-8');
    }
    echo 'Login required.';
    exit;
}

if (!ob_get_level()) {
    ob_start(function ($html) {
        return preg_replace(
            '/(<meta\s+name=["\']robots["\']\s+content=["\'])[^"\']*(["\'])/i',
            '$1noindex, nofollow$2',
            $html
        );
    });
}
