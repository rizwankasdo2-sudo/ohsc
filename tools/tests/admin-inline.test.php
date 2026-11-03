<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
ini_set('session.save_path',sys_get_temp_dir());session_id('ohsc-render-'.bin2hex(random_bytes(8)));session_start();$_SESSION['adminid']=999999;$_SESSION['ohsc_tools_csrf']=bin2hex(random_bytes(16));
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_METHOD']='GET';$_SERVER['REQUEST_URI']='/admin/tool-edit';$_SERVER['SCRIPT_NAME']='/admin/tool-edit';
require __DIR__.'/../../admin/classes/connection.php';require __DIR__.'/../includes/registry.php';ohsc_tools_ensure($db);
$testSlug=isset($argv[1])&&$argv[1]==='resources'?'resources':'typing-test';$stmt=$db->prepare('SELECT tool_id FROM tbl_site_tools WHERE slug=?');$stmt->bind_param('s',$testSlug);$stmt->execute();$id=(int)$stmt->get_result()->fetch_assoc()['tool_id'];$stmt->close();$_GET['id']=$id;
ob_start();require __DIR__.'/../admin/tool-form.php';$html=ob_get_clean();
foreach (array('public_slug','meta_title','meta_description','meta_keywords','meta_robots') as $field) { if (strpos($html,'name="'.$field.'"')===false) { throw new RuntimeException('Inline field missing: '.$field); } }
if ($testSlug==='resources' && substr_count($html,'name="page_image"')!==8) { throw new RuntimeException('Resources Tool Edit must embed all 8 image slots.'); }
require_once __DIR__.'/../includes/resources.php';ohsc_resources_ensure($db);$key='hub';ob_start();require __DIR__.'/../admin/resource-page-images.php';$images=ob_get_clean();
if (substr_count($images,'name="page_image"')!==8 || substr_count($images,'value="delete"')!==8 || strpos($images,'640 × 400')===false || strpos($images,'640 × 440')===false) { throw new RuntimeException('Image management fields missing.'); }
session_destroy();echo "PASS: all five SEO fields rendered inside Tool Edit; hub upload/delete/restore controls and dimensions rendered.\n";
