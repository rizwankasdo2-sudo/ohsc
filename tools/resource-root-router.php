<?php
require_once __DIR__.'/includes/lock.php';require __DIR__.'/../admin/classes/connection.php';require_once __DIR__.'/includes/resources.php';ohsc_resources_ensure($db);
$resourcePages=ohsc_resource_pages($db);$resourcePath=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));$match=ohsc_resource_page_resolve($resourcePages,$resourcePath);
$lookup=rtrim($resourcePath,'/');$stmt=$db->prepare('SELECT page_key FROM tbl_resource_page_aliases WHERE path=?');$stmt->bind_param('s',$lookup);$stmt->execute();$alias=$stmt->get_result()->fetch_assoc();$stmt->close();
if ($match || $alias) { require __DIR__.'/resources/index.php';exit; }
if (!empty($_SERVER['QUERY_STRING'])) { http_response_code(410);exit('This address is unavailable.'); }
if (preg_match('~^/([a-zA-Z0-9_-]+)/?$~',$resourcePath,$m)) { $_GET['slug']=$m[1];chdir(dirname(__DIR__));require dirname(__DIR__).'/page.php';exit; }
http_response_code(404);header('Content-Type: text/plain; charset=UTF-8');exit('Page not found.');
