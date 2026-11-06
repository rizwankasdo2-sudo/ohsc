<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
ini_set('session.save_path',sys_get_temp_dir());session_id('ohsc-review-'.bin2hex(random_bytes(8)));session_start();$_SESSION['adminid']=999999;$_SESSION['ohsc_tools_csrf']=bin2hex(random_bytes(16));
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_METHOD']='GET';$_SERVER['REQUEST_URI']='/admin/tools-manager';$_SERVER['SCRIPT_NAME']='/admin/tools-manager';
$mode=$argv[1]??'hub';ob_start();
if ($mode==='hub') { $_GET['page']='tools-hub';require __DIR__.'/../admin/tool-pages.php'; }
elseif ($mode==='resource') { $_GET['new']='1';require __DIR__.'/../admin/resource-pages.php'; }
else { require __DIR__.'/../admin/iv-professions.php'; }
$html=ob_get_clean();
if (preg_match('/(?:Fatal error|Warning:|Parse error)/',$html)) throw new RuntimeException('Admin rendering error');
$expected=$mode==='hub'?array('name="tool_image"','name="meta_robots"','name="meta_keywords"'):($mode==='resource'?array('name="parent_key"','name="resource_id"','name="meta_title"'):array('name="profession_image"','560 × 400','WebP only'));
foreach ($expected as $text) {if (strpos($html,$text)===false) throw new RuntimeException('Missing admin control: '.$text);}
if ($mode==='profession' && strpos($html,'<select name="image"')!==false) throw new RuntimeException('Image dropdown remains');
session_destroy();echo 'PASS: '.$mode.' admin controls render with upload/SEO fields.'.PHP_EOL;
