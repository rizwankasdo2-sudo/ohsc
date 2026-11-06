<?php
require_once __DIR__.'/includes/lock.php';require __DIR__.'/../admin/classes/connection.php';require_once __DIR__.'/includes/registry.php';require_once __DIR__.'/includes/resources.php';
ohsc_tools_ensure($db);ohsc_resources_ensure($db);
$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));$resourcePages=ohsc_resource_pages($db);
if (ohsc_resource_page_resolve($resourcePages,$path)) { require __DIR__.'/resources/index.php';exit; }
$aliasPath=rtrim($path,'/');$stmt=$db->prepare('SELECT page_key FROM tbl_resource_page_aliases WHERE path=?');$stmt->bind_param('s',$aliasPath);$stmt->execute();$alias=$stmt->get_result()->fetch_assoc();$stmt->close();
if ($alias&&isset($resourcePages[$alias['page_key']])) { header('Location: '.rtrim($base_url,'/').ohsc_resource_page_path($resourcePages,$alias['page_key']).(!empty($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:''),true,301);exit; }
require __DIR__.'/router.php';
