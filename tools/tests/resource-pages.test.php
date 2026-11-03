<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_URI']='/resources';
require __DIR__.'/../../admin/classes/connection.php';require __DIR__.'/../includes/registry.php';require __DIR__.'/../includes/resources.php';
function pages_check($ok,$why) { if (!$ok) { throw new RuntimeException($why); } }
ohsc_resources_ensure($db);$pages=ohsc_resource_pages($db);
pages_check(count(ohsc_resource_page_seed())===30,'Unexpected front-end page structure');
foreach (ohsc_resource_page_seed() as $seed) { pages_check(isset($pages[$seed['page_key']]),'Seed page missing');$path=ohsc_resource_page_path($pages,$seed['page_key']);pages_check(ohsc_resource_page_resolve($pages,$path)['page_key']===$seed['page_key'],'Route resolution failed'); }
$key='test-'.bin2hex(random_bytes(5));$child=$key.'-child';$faqId=0;
try {
    $stmt=$db->prepare("INSERT INTO tbl_resource_pages(page_key,parent_key,slug,title,summary,meta_title) VALUES(?,'',?,'Test page','Test','Test SEO')");$stmt->bind_param('ss',$key,$key);$stmt->execute();$stmt->close();
    $stmt=$db->prepare("INSERT INTO tbl_resource_pages(page_key,parent_key,slug,title,summary,meta_title) VALUES(?,?,'child','Child page','Child','Child SEO')");$stmt->bind_param('ss',$child,$key);$stmt->execute();$stmt->close();
    $pages=ohsc_resource_pages($db);$before=$pages[$key];$input=array_intersect_key($before,array_flip(array('slug','title','summary','meta_title','meta_description','meta_keywords','meta_robots')));$input['slug']=$key.'-new';$input['meta_title']='Custom SEO <title>';$input['meta_description']='Description & text';$input['meta_keywords']='career, template';$input['meta_robots']='index, follow';
    $oldChild=ohsc_resource_page_path($pages,$child);
    pages_check(ohsc_resource_page_save($db,$pages,$key,$input)==='','Page save failed');
    $changed=ohsc_resource_pages($db);pages_check($changed[$key]['meta_title']===$input['meta_title'] && $changed[$key]['meta_keywords']===$input['meta_keywords'],'SEO fields not saved');
    pages_check(ohsc_resource_page_path($changed,$child)==='/'.$input['slug'].'/child','Child URL did not follow parent slug');
    $stmt=$db->prepare('SELECT page_key FROM tbl_resource_page_aliases WHERE path=?');$stmt->bind_param('s',$oldChild);$stmt->execute();pages_check($stmt->get_result()->fetch_assoc()['page_key']===$child,'Old child URL alias missing');$stmt->close();
    $bad=$input;$bad['slug']='../unsafe';pages_check(ohsc_resource_page_save($db,$changed,$key,$bad)!=='','Unsafe slug accepted');
    $bad=$input;$bad['meta_robots']='unsafe';pages_check(ohsc_resource_page_save($db,$changed,$key,$bad)!=='','Unsafe robots accepted');
    $db->query("INSERT INTO tbl_career_resources(title,summary,category,goal,subcategory,answer,is_active,created_at,updated_at) VALUES('FAQ test integration','FAQ','faqs','plan-career','career-faqs','A safe answer.',1,NOW(),NOW())");$faqId=(int)$db->insert_id;
    pages_check(count(ohsc_resources_list($db,'FAQ test integration','faqs','','career-faqs'))===1,'FAQ without attachment missing');
    pages_check(!ohsc_resources_list($db,'FAQ test integration','faqs','','interview-faqs'),'Subcategory filter failed');
    echo "PASS: 30 pages, canonical hierarchy, slug/SEO save, parent/child aliases, unsafe inputs, FAQ publication and subcategory filtering.\n";
} finally {
    foreach (array($child,$key) as $cleanupKey) { $stmt=$db->prepare('DELETE FROM tbl_resource_pages WHERE page_key=?');$stmt->bind_param('s',$cleanupKey);$stmt->execute();$stmt->close();$stmt=$db->prepare('DELETE FROM tbl_resource_page_aliases WHERE page_key=?');$stmt->bind_param('s',$cleanupKey);$stmt->execute();$stmt->close(); }
    if ($faqId) { $db->query('DELETE FROM tbl_career_resources WHERE resource_id='.$faqId); }
}

$auth=getenv('OHSC_RESOURCE_TEST_AUTH');
if ($auth) {
    $pages=ohsc_resource_pages($db);$base='http://localhost:300';
    $context=stream_context_create(array('http'=>array('header'=>'Authorization: Basic '.base64_encode($auth),'ignore_errors'=>true,'timeout'=>20)));
    foreach (ohsc_resource_page_seed() as $seed) {
        $page=$pages[$seed['page_key']];$path=ohsc_resource_page_path($pages,$seed['page_key']);
        $html=file_get_contents($base.$path,false,$context);
        pages_check(strpos($http_response_header[0],'200')!==false,'HTTP route failed: '.$path);
        pages_check(strpos($html,'<title>'.htmlspecialchars($page['meta_title'],ENT_QUOTES,'UTF-8').'</title>')!==false,'Meta title missing: '.$path);
        pages_check(strpos($html,'name="description" content="'.htmlspecialchars($page['meta_description'],ENT_QUOTES,'UTF-8').'"')!==false,'Meta description missing');
        pages_check(strpos($html,'name="keywords"')!==false && substr_count($html,'name="robots"')===1,'Missing/duplicate SEO tags');
        pages_check(strpos($html,'rel="canonical" href="'.$base.$path.'"')!==false,'Canonical missing');
    }
    $html=file_get_contents($base.ohsc_resource_page_path($pages,'toolkits').'?q=Job%20Search%20Toolkit',false,$context);pages_check(strpos($http_response_header[0],'200')!==false && strpos($html,'Job Search Toolkit')!==false,'Resource search route blocked');
    $html=file_get_contents($base.'/tools/resources/?category=templates',false,$context);
    pages_check(strpos(implode('\n',$http_response_header),'301')!==false && strpos($html,'CV Templates')!==false,'Legacy redirect failed');
    file_get_contents($base.'/resources/not-a-real-page',false,$context);pages_check(strpos($http_response_header[0],'404')!==false,'Unknown page did not return 404');
    echo "PASS: all 30 HTTP pages, meta tags, canonicals, legacy redirect and unknown-route 404.\n";
}
