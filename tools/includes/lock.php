<?php
// Public tool pages ask for a username and password in the browser, the same
// kind of lock as bolc.kasdo.org. Change the two values below to rotate it.
$ohscToolsUser = 'ohsc';
$ohscToolsPass = 'OhscTools26';

if (!headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet, noimageindex');
}

$ohscToolsHeader = '';
if (!empty($_SERVER['PHP_AUTH_USER']) || isset($_SERVER['PHP_AUTH_PW'])) {
    $ohscGivenUser = isset($_SERVER['PHP_AUTH_USER']) ? (string) $_SERVER['PHP_AUTH_USER'] : '';
    $ohscGivenPass = isset($_SERVER['PHP_AUTH_PW']) ? (string) $_SERVER['PHP_AUTH_PW'] : '';
} else {
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        $ohscToolsHeader = (string) $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $ohscToolsHeader = (string) $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    }
    $ohscGivenUser = '';
    $ohscGivenPass = '';
    if (stripos($ohscToolsHeader, 'basic ') === 0) {
        $ohscDecoded = base64_decode(substr($ohscToolsHeader, 6));
        if ($ohscDecoded !== false && strpos($ohscDecoded, ':') !== false) {
            $ohscParts = explode(':', $ohscDecoded, 2);
            $ohscGivenUser = $ohscParts[0];
            $ohscGivenPass = $ohscParts[1];
        }
    }
}

if (!hash_equals($ohscToolsUser, $ohscGivenUser) || !hash_equals($ohscToolsPass, $ohscGivenPass)) {
    if (!headers_sent()) {
        header('WWW-Authenticate: Basic realm="OHSC tools"');
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: text/plain; charset=UTF-8');
    }
    echo 'Login required.';
    exit;
}
