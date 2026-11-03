<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
ini_set('session.save_path',sys_get_temp_dir());session_id('ohsc-save-'.bin2hex(random_bytes(8)));session_start();$_SESSION['adminid']=999999;$_SESSION['ohsc_tools_csrf']=bin2hex(random_bytes(16));
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_METHOD']='POST';$_SERVER['REQUEST_URI']='/admin/tool-edit';$_SERVER['SCRIPT_NAME']='/admin/tool-edit';
require __DIR__.'/../../admin/classes/connection.php';require __DIR__.'/../includes/registry.php';ohsc_tools_ensure($db);
$slug=isset($argv[1])&&$argv[1]==='resources'?'resources':'typing-test';$stmt=$db->prepare('SELECT * FROM tbl_site_tools WHERE slug=?');$stmt->bind_param('s',$slug);$stmt->execute();$t=$stmt->get_result()->fetch_assoc();$stmt->close();
if ($slug==='resources') { require __DIR__.'/../includes/resources.php';ohsc_resources_ensure($db);$meta=ohsc_resource_pages($db)['hub']; }else { $meta=ohsc_tool_pages($db)[$slug]; }
$_GET['id']=$t['tool_id'];$_POST=array('csrf'=>$_SESSION['ohsc_tools_csrf'],'tool_id'=>$t['tool_id'],'title'=>$t['title'],'summary'=>$t['summary'],'sort_order'=>$t['sort_order'],'public_slug'=>$meta['slug'],'meta_title'=>$meta['meta_title'],'meta_description'=>$meta['meta_description'],'meta_keywords'=>$meta['meta_keywords'],'meta_robots'=>$meta['meta_robots']);if ($t['is_active']) { $_POST['is_active']=1; }
// The fixture uses its own CLI-only session directory and saves identical values.
// Successful POST redirects and exits without rendering HTML. Any output is a failure.
ob_start();register_shutdown_function(function(){ $out=ob_get_contents();if ($out!==false && $out!=='') { ob_end_clean();fwrite(STDERR,"Inline save rendered a validation error.\n");exit(1); }if (session_status()===PHP_SESSION_ACTIVE) { session_destroy(); } });
require __DIR__.'/../admin/tool-form.php';
