<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_URI']='/career-expert';
require __DIR__.'/../../admin/classes/connection.php';require __DIR__.'/../includes/registry.php';require __DIR__.'/../includes/resources.php';
function review_check($ok,$message) { if (!$ok) throw new RuntimeException($message); }
ohsc_tools_ensure($db);ohsc_resources_ensure($db);$pages=ohsc_resource_pages($db);$toolPages=ohsc_tool_pages($db);
review_check(ohsc_tool_page_path($toolPages,'tools-hub')==='/career-expert','Wrong hub URL');
review_check(ohsc_tool_page_path($toolPages,'typing-test')==='/career-expert/typing-speed-test','Wrong typing URL');
review_check(ohsc_tool_page_path($toolPages,'eq-test')==='/career-expert/emotional-intelligence-test','Wrong EQ URL');
ob_start();ohsc_tool_seo_head(array('meta_title'=>'Editor title','meta_description'=>'Editor description','meta_keywords'=>'editor, keywords','meta_robots'=>'index, follow','canonical'=>'https://example.test/career-expert'));$head=ob_get_clean();
review_check(strpos($head,'content="index, follow" name="robots"')!==false,'Robots not controlled by editor');
$fixture='review-'.bin2hex(random_bytes(6));$id=0;$key=null;
try {
 $stmt=$db->prepare("INSERT INTO tbl_career_resources(title,summary,category,goal,is_active,created_at,updated_at) VALUES(?,'Fixture resource','templates','plan-career',0,NOW(),NOW())");$stmt->bind_param('s',$fixture);$stmt->execute();$id=$db->insert_id;$stmt->close();
 $input=array('slug'=>$fixture,'title'=>'Review resource page','summary'=>'Test summary','meta_title'=>'Test metadata','meta_description'=>'Test description','meta_keywords'=>'test, resource','meta_robots'=>'noindex, follow');
 list($error,$key)=ohsc_resource_page_create($db,$pages,$input,'hub',$id);review_check(!$error,'Cannot create resource page: '.$error);
 $changed=ohsc_resource_pages($db);review_check(ohsc_resource_page_path($changed,$key)==='/career-expert/resources/'.$fixture,'Created route incorrect');
 list($duplicate)=ohsc_resource_page_create($db,$changed,$input,'hub',$id);review_check($duplicate!=='','Duplicate resource owner accepted');
 $bad=$input;$bad['slug']='../invalid';list($invalid)=ohsc_resource_page_create($db,$changed,$bad,'hub',$id);review_check($invalid!=='','Unsafe page slug accepted');
 $input['slug'].='-updated';review_check(ohsc_resource_page_save($db,$changed,$key,$input)==='','Custom page cannot be edited');
} finally {
 if ($key) { foreach (array('tbl_resource_pages','tbl_resource_page_aliases') as $t) { $stmt=$db->prepare('DELETE FROM '.$t.' WHERE page_key=?');$stmt->bind_param('s',$key);$stmt->execute();$stmt->close(); } }
 if ($id) $db->query('DELETE FROM tbl_career_resources WHERE resource_id='.(int)$id);
}
$rows=$db->query("SELECT f.filename FROM tbl_career_resource_files f JOIN tbl_resource_starter_seeds s ON s.resource_id=f.resource_id WHERE f.extension='docx'")->fetch_all(MYSQLI_ASSOC);
foreach ($rows as $r) { $file=__DIR__.'/../resource-files/'.$r['filename'];review_check(ohsc_resource_validate_file($file,'template.docx',filesize($file))==='','Invalid generated Word package'); }
review_check(count($rows)>=24,'Missing editable starters');
echo "PASS: approved tool routes, editable robots metadata, resource page create/edit/aliases, duplicate and traversal validation, genuine Word downloads.\n";
