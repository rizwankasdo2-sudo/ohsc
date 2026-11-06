<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_URI']='/tools/';
require __DIR__.'/../../admin/classes/connection.php';require __DIR__.'/../includes/registry.php';require __DIR__.'/../includes/resources.php';
function tp_check($ok,$why) { if (!$ok) { throw new RuntimeException($why); } }
ohsc_tools_ensure($db);ohsc_resources_ensure($db);
foreach (ohsc_resource_page_seed() as $page) { $cat=$page['page_key']==='hub'?'':($page['parent_key']==='hub'?$page['page_key']:$page['parent_key']);$sub=$page['parent_key']!==''&&$page['parent_key']!=='hub'?$page['page_key']:'';tp_check(count(ohsc_resources_list($db,'',$cat,'',$sub))>=1,'No published starter resource for '.$page['page_key']); }
$seedCount=(int)$db->query('SELECT COUNT(*) n FROM tbl_resource_starter_seeds')->fetch_assoc()['n'];tp_check($seedCount===24,'Expected 24 starter items');
$before=(int)$db->query('SELECT COUNT(*) n FROM tbl_career_resources')->fetch_assoc()['n'];ohsc_resource_starters($db);tp_check($before===(int)$db->query('SELECT COUNT(*) n FROM tbl_career_resources')->fetch_assoc()['n'],'Starter migration duplicated resources');
$db->begin_transaction();
try {
    $first=$db->query('SELECT resource_id FROM tbl_resource_starter_seeds LIMIT 1')->fetch_assoc();$id=(int)$first['resource_id'];ohsc_resource_action($db,$id,'delete');
    tp_check(!in_array($id,array_map('intval',array_column(ohsc_resources_list($db),'resource_id')),true),'Deleted starter remains public');
    ohsc_resource_starters($db);tp_check((int)$db->query('SELECT is_deleted FROM tbl_career_resources WHERE resource_id='.$id)->fetch_assoc()['is_deleted']===1,'Deleted starter respawned');
    ohsc_resource_action($db,$id,'restore');$state=$db->query('SELECT is_deleted,is_active FROM tbl_career_resources WHERE resource_id='.$id)->fetch_assoc();tp_check((int)$state['is_deleted']===0&&(int)$state['is_active']===0,'Restore should create a draft');
} finally { $db->rollback(); }
$pages=ohsc_tool_pages($db);tp_check(isset($pages['tools-hub'],$pages['typing-test'],$pages['eq-test'],$pages['career-quiz'],$pages['cv-builder'],$pages['interview-prep']),'Missing tool SEO pages');
foreach ($db->query('SELECT industry_id FROM tbl_iv_industries')->fetch_all(MYSQLI_ASSOC) as $i) { tp_check(isset($pages['profession-'.$i['industry_id']]),'Missing profession SEO'); }
$key='seo-test-'.bin2hex(random_bytes(5));
try {
    $stmt=$db->prepare("INSERT INTO tbl_tool_pages(page_key,tool_slug,slug,title,meta_title) VALUES(?,'test',?,'SEO fixture','Fixture')");$stmt->bind_param('ss',$key,$key);$stmt->execute();$stmt->close();$pages=ohsc_tool_pages($db);
    $input=array('slug'=>$key.'-new','meta_title'=>'Custom meta title','meta_description'=>'Custom description','meta_keywords'=>'career, tools','meta_robots'=>'index, follow');
    tp_check(ohsc_tool_page_save($db,$pages,$key,$input)==='','Custom slug/SEO save failed');$updated=ohsc_tool_pages($db);
    tp_check($updated[$key]['meta_title']===$input['meta_title']&&$updated[$key]['meta_keywords']===$input['meta_keywords'],'SEO not persisted');
    $bad=$input;$bad['slug']='../unsafe';tp_check(ohsc_tool_page_save($db,$updated,$key,$bad)!=='','Unsafe slug accepted');$bad=$input;$bad['slug']='resources';tp_check(ohsc_tool_page_save($db,$updated,$key,$bad)!=='','Reserved slug accepted');
    $bad=$input;$bad['slug']=$updated['typing-test']['slug'];tp_check(ohsc_tool_page_save($db,$updated,$key,$bad)!=='','Duplicate slug accepted');
    $old='/career-expert/'.$key;$stmt=$db->prepare('SELECT page_key FROM tbl_tool_page_aliases WHERE path=?');$stmt->bind_param('s',$old);$stmt->execute();tp_check($stmt->get_result()->fetch_assoc()['page_key']===$key,'Old slug alias missing');$stmt->close();
} finally { $stmt=$db->prepare('DELETE FROM tbl_tool_pages WHERE page_key=?');$stmt->bind_param('s',$key);$stmt->execute();$stmt->close();$stmt=$db->prepare('DELETE FROM tbl_tool_page_aliases WHERE page_key=?');$stmt->bind_param('s',$key);$stmt->execute();$stmt->close(); }
echo "PASS: all 30 resource pages populated, 24 starters idempotent, delete/restore, tool/profession SEO, slug validation and old URL aliases.\n";
$auth=getenv('OHSC_RESOURCE_TEST_AUTH');if ($auth) {
    $pages=ohsc_tool_pages($db);$context=stream_context_create(array('http'=>array('header'=>'Authorization: Basic '.base64_encode($auth),'ignore_errors'=>true,'timeout'=>20)));
    foreach ($pages as $k=>$p) { $path=ohsc_tool_page_path($pages,$k);$html=file_get_contents('http://localhost:300'.$path,false,$context);tp_check(strpos($http_response_header[0],'200')!==false,'HTTP tool route failed: '.$path);tp_check(strpos($html,'<title>'.htmlspecialchars($p['meta_title'],ENT_QUOTES,'UTF-8').'</title>')!==false,'Tool meta title missing: '.$path);tp_check(substr_count($html,'name="robots"')===1&&strpos($html,'name="keywords"')!==false,'Missing/duplicate tool SEO tags'); }
    $original=$pages['typing-test'];$oldAliases=$db->query("SELECT path,page_key FROM tbl_tool_page_aliases WHERE page_key='typing-test'")->fetch_all(MYSQLI_ASSOC);
    try {
        $custom=array('slug'=>'typing-qa-'.bin2hex(random_bytes(4)),'meta_title'=>'Typing QA custom SEO','meta_description'=>'Custom typing description','meta_keywords'=>'typing, QA','meta_robots'=>'index, follow');
        tp_check(ohsc_tool_page_save($db,$pages,'typing-test',$custom)==='','Real typing SEO save failed');
        $customPath='/tools/'.$custom['slug'].'/';$html=file_get_contents('http://localhost:300'.$customPath,false,$context);
        tp_check(strpos($http_response_header[0],'200')!==false&&strpos($html,'<title>Typing QA custom SEO</title>')!==false&&strpos($html,'id="ohsc-typing"')!==false,'Custom tool slug did not load the real tool');
        $noFollow=stream_context_create(array('http'=>array('header'=>'Authorization: Basic '.base64_encode($auth),'ignore_errors'=>true,'follow_location'=>0,'timeout'=>20)));
        file_get_contents('http://localhost:300/tools/typing-test/',false,$noFollow);tp_check(strpos($http_response_header[0],'301')!==false&&strpos(implode(' ', $http_response_header),$customPath)!==false,'Original tool URL did not redirect');
    } finally {
        $stmt=$db->prepare("UPDATE tbl_tool_pages SET slug=?,meta_title=?,meta_description=?,meta_keywords=?,meta_robots=? WHERE page_key='typing-test'");$stmt->bind_param('sssss',$original['slug'],$original['meta_title'],$original['meta_description'],$original['meta_keywords'],$original['meta_robots']);$stmt->execute();$stmt->close();$db->query("DELETE FROM tbl_tool_page_aliases WHERE page_key='typing-test'");
        foreach ($oldAliases as $a) { $stmt=$db->prepare('INSERT INTO tbl_tool_page_aliases(path,page_key) VALUES(?,?)');$stmt->bind_param('ss',$a['path'],$a['page_key']);$stmt->execute();$stmt->close(); }
    }
    echo "PASS: ".count($pages)." tool/profession pages load with SEO tags.\n";
    echo "PASS: custom tool URL serves the tool and its edited metadata; original address redirects.\n";
}
