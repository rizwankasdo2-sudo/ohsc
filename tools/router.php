<?php
require_once __DIR__.'/includes/lock.php';require __DIR__.'/../admin/classes/connection.php';require_once __DIR__.'/includes/registry.php';ohsc_tools_ensure($db);
$pages=ohsc_tool_pages($db);$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));$page=null;
foreach ($pages as $key=>$candidate) { if (rtrim($path,'/')===rtrim(ohsc_tool_page_path($pages,$key),'/')) { $page=$candidate;break; } }
if (!$page) { $aliasPath=rtrim($path,'/').'/';$stmt=$db->prepare('SELECT page_key FROM tbl_tool_page_aliases WHERE path=?');$stmt->bind_param('s',$aliasPath);$stmt->execute();$old=$stmt->get_result()->fetch_assoc();$stmt->close();if ($old&&isset($pages[$old['page_key']])) { header('Location: '.rtrim($base_url,'/').ohsc_tool_page_path($pages,$old['page_key']).(!empty($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:''),true,301);exit; } }
if (!$page) { http_response_code(404);header('Content-Type: text/plain; charset=UTF-8');exit('Tool page not found.'); }
if ($page['industry_id']) { $id=(int)$page['industry_id'];$stmt=$db->prepare('SELECT slug FROM tbl_iv_industries WHERE industry_id=? AND is_active=1');$stmt->bind_param('i',$id);$stmt->execute();$profession=$stmt->get_result()->fetch_assoc();$stmt->close();if (!$profession) { http_response_code(404);exit('Profession not available.'); }$_GET['industry']=$profession['slug'];$_SERVER['OHSC_TOOL_ROUTED_INDUSTRY']=$id; }
if ($page['tool_slug']==='hub') { require __DIR__.'/index.php'; }
elseif (in_array($page['tool_slug'],array('typing-test','eq-test','cv-builder','career-quiz','interview-prep'),true)) { require __DIR__.'/'.$page['tool_slug'].'/index.php'; }
else { http_response_code(404); }
