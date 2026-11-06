<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_URI']='/resources';
require __DIR__.'/../../admin/classes/connection.php';require __DIR__.'/../includes/registry.php';require __DIR__.'/../includes/resources.php';
function ri_check($ok,$why) { if (!$ok) { throw new RuntimeException($why); } }
ohsc_resources_ensure($db);ohsc_resource_images_table($db);$spec=ohsc_resource_image_specs('hub');ri_check(count($spec)===8,'Missing hub image slots');ri_check(count(ohsc_resource_image_specs('cv-templates'))===2,'Missing subpage image slots');
$filename=bin2hex(random_bytes(16)).'.webp';$file=__DIR__.'/../uploads/'.$filename;$im=imagecreatetruecolor(640,400);imagewebp($im,$file);imagedestroy($im);
ri_check(ohsc_image_validate($file,'image.webp',filesize($file),$spec['featured-cv'])==='','Featured WebP rejected');ri_check(ohsc_image_validate($file,'image.webp',filesize($file),$spec['hero'])!=='','Incorrect hero dimensions accepted');
$db->begin_transaction();try {
    ohsc_resource_image_store($db,'hub','featured-cv',$filename,0);$records=ohsc_resource_image_records($db,'hub');
    ri_check(strpos(ohsc_resource_image_url($records,'featured-cv','http://localhost:300'),$filename)!==false,'Uploaded image not rendered');
    ri_check(strpos(ohsc_resource_image_url($records,'featured-interviews','http://localhost:300'),$filename)===false,'Featured image leaked to another card');
    ri_check(strpos(ohsc_resource_image_url(ohsc_resource_image_records($db,'cv-templates'),'hero','http://localhost:300'),$filename)===false,'Hub image leaked into subpage');
    ohsc_resource_image_store($db,'hub','featured-cv','',1);ri_check(ohsc_resource_image_url(ohsc_resource_image_records($db,'hub'),'featured-cv','http://localhost:300')==='','Delete did not hide image');
    $db->query("DELETE FROM tbl_resource_page_images WHERE page_key='hub' AND slot='featured-cv'");ri_check(strpos(ohsc_resource_image_url(ohsc_resource_image_records($db,'hub'),'featured-cv','http://localhost:300'),'resources-cv.svg')!==false,'Default not restored');
} finally { $db->rollback();unlink($file); }
echo "PASS: 8 hub slots, per-page images, independent featured cards, exact dimensions, delete and restore.\n";

$auth=getenv('OHSC_RESOURCE_TEST_AUTH');if ($auth) {
    $pages=ohsc_resource_pages($db);$original=$pages['hub'];$oldAliases=$db->query('SELECT path,page_key FROM tbl_resource_page_aliases')->fetch_all(MYSQLI_ASSOC);$oldPaths=array_column($oldAliases,'path');
    $context=stream_context_create(array('http'=>array('header'=>'Authorization: Basic '.base64_encode($auth),'ignore_errors'=>true,'timeout'=>20)));
    try {
        $input=array_intersect_key($original,array_flip(array('slug','title','summary','meta_title','meta_description','meta_keywords','meta_robots')));$input['slug']='resource-qa-'.bin2hex(random_bytes(4));
        ri_check(ohsc_resource_page_save($db,$pages,'hub',$input)==='','Hub slug save failed');$updated=ohsc_resource_pages($db);$path=ohsc_resource_page_path($updated,'hub');
        $html=file_get_contents('http://localhost:300'.$path,false,$context);ri_check(strpos($http_response_header[0],'200')!==false,'Renamed root not served');ri_check(strpos($html,'id="resource-list"')===false,'Full resource list remains on hub');
        $html=file_get_contents('http://localhost:300'.ohsc_resource_page_path($updated,'cv-templates'),false,$context);ri_check(strpos($http_response_header[0],'200')!==false&&strpos($html,'Professional CV Template')!==false,'Renamed subpage not served');
        $html=file_get_contents('http://localhost:300'.$path.'?q=CV',false,$context);ri_check(strpos($http_response_header[0],'200')!==false&&strpos($html,'id="resource-list"')!==false,'Renamed hub search failed');
        $html=file_get_contents('http://localhost:300/career-expert/resources',false,$context);ri_check(strpos($html,'Practical tools.')!==false,'Legacy prefix did not redirect after rename');
    } finally {
        $stmt=$db->prepare("UPDATE tbl_resource_pages SET slug=? WHERE page_key='hub'");$stmt->bind_param('s',$original['slug']);$stmt->execute();$stmt->close();
        foreach ($db->query('SELECT path FROM tbl_resource_page_aliases')->fetch_all(MYSQLI_ASSOC) as $a) { if (!in_array($a['path'],$oldPaths,true)) { $stmt=$db->prepare('DELETE FROM tbl_resource_page_aliases WHERE path=?');$stmt->bind_param('s',$a['path']);$stmt->execute();$stmt->close(); } }
    }
    echo "PASS: root hub, renamed root/subpages/search, legacy redirects and clean default hub layout.\n";
}
