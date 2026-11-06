<?php
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
$_SERVER['HTTP_HOST']='localhost:300';$_SERVER['REQUEST_URI']='/tools/resources/';
require __DIR__.'/../../admin/classes/connection.php';require __DIR__.'/../includes/registry.php';require __DIR__.'/../includes/resources.php';
function resource_check($ok,$message) { if (!$ok) { throw new RuntimeException($message); } }
ohsc_resources_ensure($db);ohsc_resources_ensure($db);
$tmp=tempnam(sys_get_temp_dir(),'resource-test');file_put_contents($tmp,"Career planning checklist\n1. Set a goal.\n2. Plan your next step.\n");
resource_check(ohsc_resource_validate_file($tmp,'checklist.txt',filesize($tmp))!=='', 'Text upload accepted');
resource_check(ohsc_resource_validate_file($tmp,'fake.pdf',filesize($tmp))!=='', 'Fake PDF accepted');
resource_check(ohsc_resource_validate_file($tmp,'file.php',filesize($tmp))!=='', 'Executable accepted');
resource_check(ohsc_resource_validate_file($tmp,'large.txt',11*1024*1024)!=='', 'Oversized file accepted');
resource_check(ohsc_resource_validate_file($tmp,'fake.docx',filesize($tmp))!=='', 'Fake Office document accepted');
resource_check(ohsc_resource_file_path('../escape.pdf')===null,'Unsafe path accepted');
if (class_exists('ZipArchive')) {
    $zpath=$tmp.'.zip';$zip=new ZipArchive();$zip->open($zpath,ZipArchive::CREATE);$zip->addFromString('checklist.txt','Plan your next step.');$zip->close();
    resource_check(ohsc_resource_validate_file($zpath,'toolkit.zip',filesize($zpath))==='', 'Safe ZIP rejected');
    $office=$tmp.'.docx';$zip->open($office,ZipArchive::CREATE);$zip->addFromString('[Content_Types].xml','<Types/>');$zip->addFromString('word/document.xml','<document/>');$zip->close();
    resource_check(ohsc_resource_validate_file($office,'template.docx',filesize($office))==='', 'Valid Word container rejected');
    resource_check(ohsc_resource_validate_file($office,'template.xlsx',filesize($office))!=='', 'Wrong Office extension accepted');unlink($office);
    $zip->open($zpath);$zip->addFromString('../escape.txt','bad');$zip->close();
    resource_check(ohsc_resource_validate_file($zpath,'unsafe.zip',filesize($zpath))!=='', 'Traversal ZIP accepted');unlink($zpath);
}
$db->begin_transaction();
try {
    $title='Resource integration '.bin2hex(random_bytes(6));$stmt=$db->prepare("INSERT INTO tbl_career_resources(title,summary,category,goal,is_active,is_featured,created_at,updated_at) VALUES(?,'A safe checklist.','checklists','interviews',0,1,NOW(),NOW())");$stmt->bind_param('s',$title);$stmt->execute();$id=(int)$db->insert_id;$stmt->close();
    resource_check(!ohsc_resources_list($db,$title),'Hidden resource published');
    $filename=bin2hex(random_bytes(16)).'.txt';$stmt=$db->prepare("INSERT INTO tbl_career_resource_files(resource_id,filename,original_name,extension,mime,bytes) VALUES(?,?,'checklist.txt','txt','text/plain',64)");$stmt->bind_param('is',$id,$filename);$stmt->execute();$stmt->close();
    $db->query('UPDATE tbl_career_resources SET is_active=1 WHERE resource_id='.$id);
    resource_check(count(ohsc_resources_list($db,$title,'checklists','interviews'))===1,'Published matching resource missing');
    resource_check(!ohsc_resources_list($db,$title,'templates',''),'Category filter failed');
    resource_check(!ohsc_resources_list($db,$title,'','find-job'),'Goal filter failed');
    resource_check(!ohsc_resources_list($db,'nonexistent '.bin2hex(random_bytes(6))),'Search failed');
    resource_check(count(ohsc_resource_files($db,$id))===1,'Download file missing');
    $db->query('DELETE FROM tbl_career_resource_files WHERE resource_id='.$id);
    resource_check(!ohsc_resources_list($db,$title),'Resource without files published');
} finally { $db->rollback();unlink($tmp); }
echo "PASS: file signatures/types/size, archive validation, safe paths, publication, category/goal/search, files and idempotence.\n";

// Optional real HTTP download checks, using credentials supplied only in the environment.
$auth=getenv('OHSC_RESOURCE_TEST_AUTH');
if ($auth) {
    $filename=bin2hex(random_bytes(16)).'.txt';$disk=ohsc_resource_file_path($filename);$body="Resource download integration check.\n";file_put_contents($disk,$body);$resourceId=0;
    function resource_http($path,$auth) {
        $context=stream_context_create(array('http'=>array('header'=>"Authorization: Basic ".base64_encode($auth)."\r\n",'ignore_errors'=>true,'timeout'=>20)));
        $body=file_get_contents('http://localhost:300'.$path,false,$context);return array($body,$http_response_header);
    }
    try {
        $title='Temporary download test '.bin2hex(random_bytes(4));
        $stmt=$db->prepare("INSERT INTO tbl_career_resources(title,summary,category,goal,is_active,created_at,updated_at) VALUES(?,'Temporary test only.','checklists','interviews',1,NOW(),NOW())");$stmt->bind_param('s',$title);$stmt->execute();$resourceId=(int)$db->insert_id;$stmt->close();
        $bytes=strlen($body);$stmt=$db->prepare("INSERT INTO tbl_career_resource_files(resource_id,filename,original_name,extension,mime,bytes) VALUES(?,?,'checklist.txt','txt','text/plain',?)");$stmt->bind_param('isi',$resourceId,$filename,$bytes);$stmt->execute();$fid=(int)$db->insert_id;$stmt->close();
        list($download,$headers)=resource_http('/tools/resources/download.php?id='.$fid,$auth);
        resource_check($download===$body,'Download content changed');resource_check(strpos(implode('\n',$headers),'attachment; filename="checklist.txt"')!==false,'Attachment header missing');
        list($html,$headers)=resource_http('/resources?q='.rawurlencode($title),$auth);resource_check(strpos($html,htmlspecialchars($title,ENT_QUOTES,'UTF-8'))!==false && strpos($html,'download.php?id='.$fid)!==false,'Published resource not linked');
        list($blocked,$headers)=resource_http('/tools/resource-files/'.$filename,$auth);resource_check(strpos($headers[0],'403')!==false,'Raw file exposed');
        ohsc_resource_action($db,$resourceId,'delete');
        list($deleted,$headers)=resource_http('/tools/resources/download.php?id='.$fid,$auth);resource_check(strpos($headers[0],'404')!==false,'Deleted download still available');
        ohsc_resource_action($db,$resourceId,'restore');
        $db->query('UPDATE tbl_career_resources SET is_active=0 WHERE resource_id='.$resourceId);
        list($hidden,$headers)=resource_http('/tools/resources/download.php?id='.$fid,$auth);resource_check(strpos($headers[0],'404')!==false,'Hidden download still available');
        list($missing,$headers)=resource_http('/tools/resources/download.php?id=0',$auth);resource_check(strpos($headers[0],'404')!==false,'Invalid download ID accepted');
        echo "PASS: real HTTP attachment, exact bytes, listing link, direct-file block, hidden/invalid download 404.\n";
    } finally {
        if ($resourceId) { $db->query('DELETE FROM tbl_career_resource_files WHERE resource_id='.$resourceId);$db->query('DELETE FROM tbl_career_resources WHERE resource_id='.$resourceId); }
        if (is_file($disk)) { unlink($disk); }
    }
}
