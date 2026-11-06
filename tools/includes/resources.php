<?php
require_once __DIR__.'/resource-pages.php';
require_once __DIR__.'/resource-starters.php';
require_once __DIR__.'/resource-images.php';
/** Downloadable career resources, isolated from courses and tool question banks. */
function ohsc_resource_categories() { return array('templates'=>'Templates','checklists'=>'Checklists','examples'=>'Examples','worksheets'=>'Worksheets','faqs'=>'FAQs','downloads'=>'Downloads','toolkits'=>'Toolkits'); }
function ohsc_resource_goals() { return array('find-job'=>'Find a job','interviews'=>'Prepare for interviews','plan-career'=>'Plan your career','develop-skills'=>'Develop your skills'); }
function ohsc_resources_ensure($db) {
    static $ready=false; if ($ready) { return; }
    $db->query("CREATE TABLE IF NOT EXISTS tbl_career_resources (
        resource_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(150) NOT NULL, summary VARCHAR(500) NOT NULL DEFAULT '',
        category VARCHAR(30) NOT NULL, goal VARCHAR(30) NOT NULL,
        image VARCHAR(80) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
        is_featured TINYINT(1) NOT NULL DEFAULT 0, is_active TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
        KEY publication (is_active,sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $db->query("CREATE TABLE IF NOT EXISTS tbl_career_resource_files (
        file_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        resource_id INT UNSIGNED NOT NULL, filename VARCHAR(80) NOT NULL,
        original_name VARCHAR(200) NOT NULL, extension VARCHAR(8) NOT NULL,
        mime VARCHAR(100) NOT NULL, bytes INT UNSIGNED NOT NULL,
        KEY resource (resource_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $cols=$db->query("SHOW COLUMNS FROM tbl_career_resources")->fetch_all(MYSQLI_ASSOC);
    $names=array_column($cols,'Field');
    if (!in_array('subcategory',$names,true)) { $db->query("ALTER TABLE tbl_career_resources ADD subcategory VARCHAR(80) NOT NULL DEFAULT ''"); }
    if (!in_array('answer',$names,true)) { $db->query("ALTER TABLE tbl_career_resources ADD answer TEXT NULL"); }
    if (!in_array('is_deleted',$names,true)) { try { $db->query("ALTER TABLE tbl_career_resources ADD is_deleted TINYINT(1) NOT NULL DEFAULT 0"); } catch (mysqli_sql_exception $e) { if ((int)$e->getCode()!==1060) { throw $e; } } }
    if (!in_array('image_hidden',$names,true)) { try { $db->query("ALTER TABLE tbl_career_resources ADD image_hidden TINYINT(1) NOT NULL DEFAULT 0"); } catch (mysqli_sql_exception $e) { if ((int)$e->getCode()!==1060) { throw $e; } } }
    ohsc_resource_pages_ensure($db);
    ohsc_resource_starters($db);
    ohsc_resource_preferred_pages($db);
    ohsc_resource_owner_migrate($db);
    $ready=true;
}
function ohsc_resource_files($db,$id) {
    $stmt=$db->prepare('SELECT * FROM tbl_career_resource_files WHERE resource_id=? ORDER BY file_id');
    $stmt->bind_param('i',$id);$stmt->execute();$rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();return $rows;
}
function ohsc_resources_list($db,$search='', $category='', $goal='', $subcategory='') {
    ohsc_resources_ensure($db);
    $like='%'.str_replace(array('!','%','_'),array('!!','!%','!_'),$search).'%';
    $stmt=$db->prepare("SELECT r.* FROM tbl_career_resources r WHERE is_active=1 AND is_deleted=0 AND (EXISTS (SELECT 1 FROM tbl_career_resource_files f WHERE f.resource_id=r.resource_id) OR (category='faqs' AND answer IS NOT NULL AND answer<>'')) AND (title LIKE ? ESCAPE '!' OR summary LIKE ? ESCAPE '!') AND (?='' OR category=?) AND (?='' OR goal=?) AND (?='' OR subcategory=?) ORDER BY is_featured DESC,sort_order,resource_id DESC");
    $stmt->bind_param('ssssssss',$like,$like,$category,$category,$goal,$goal,$subcategory,$subcategory);$stmt->execute();$rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();return $rows;
}
function ohsc_resource_formats() { return array('pdf'=>'PDF','docx'=>'Word','xlsx'=>'Excel','pptx'=>'PowerPoint','zip'=>'ZIP'); }
function ohsc_resource_validate_file($path,$name,$bytes) {
    $ext=strtolower(pathinfo($name,PATHINFO_EXTENSION));
    if (!isset(ohsc_resource_formats()[$ext])) { return 'Allowed files: PDF, DOCX, XLSX, PPTX and ZIP.'; }
    if ($bytes<1 || $bytes>10*1024*1024) { return 'Each download must be between 1 byte and 10 MB.'; }
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($path);
    if ($ext==='pdf') {
        $handle=fopen($path,'rb');$sig=fread($handle,5);fclose($handle);
        if ($sig!=='%PDF-' || $mime!=='application/pdf') { return 'The PDF file is invalid.'; }
    } elseif ($ext==='txt') {
        if ($mime!=='text/plain' || strpos(file_get_contents($path),"\0")!==false) { return 'Upload a plain text file.'; }
    } else {
        if (!class_exists('ZipArchive')) { return 'ZIP support is unavailable on this server. Upload a PDF instead.'; }
        $zip=new ZipArchive();
        if ($zip->open($path)!==true) { return 'Invalid Office document or ZIP archive.'; }
        $total=0;$error='';
        if ($zip->numFiles<1 || $zip->numFiles>500) { $error='Archive must contain between 1 and 500 files.'; }
        for ($i=0;$i<$zip->numFiles && $error==='';$i++) {
            $entry=$zip->statIndex($i);
            $opsys=0;$attributes=0;$zip->getExternalAttributesIndex($i,$opsys,$attributes);
            if ((!empty($entry['encryption_method'])) || (($attributes >> 16) & 0170000) === 0120000) { $error='Encrypted archives and symbolic links are not allowed.';break; }
            $entryName=str_replace('\\','/',$entry['name']);$total+=$entry['size'];
            if ($total>50*1024*1024 || preg_match('~(^|/)\.\.(/|$)|^/|^[a-z]:|\.(php[0-9]?|phtml|phar|exe|dll|bat|cmd|ps1|vbs|js|html?|svg|hta|com|scr|msi|jar|zip|rar|7z|sh|bash|py|rb|cgi|pl|psm1|mjs|cjs|jse|vbe|wsf|lnk|docm|xlsm|pptm)$~i',$entryName) || stripos($entryName,'vbaProject')!==false) { $error='Archive contains unsafe entries or exceeds 50 MB uncompressed.'; }
        }
        $office=array('docx'=>'word/document.xml','xlsx'=>'xl/workbook.xml','pptx'=>'ppt/presentation.xml');
        if (isset($office[$ext]) && ($zip->locateName('[Content_Types].xml')===false || $zip->locateName($office[$ext])===false)) { $error='File contents do not match the selected Office format.'; }
        $zip->close();if ($error!=='') { return $error; }
    }
    return '';
}
function ohsc_resource_mime($ext) {
    $map=array('pdf'=>'application/pdf','docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document','xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','pptx'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation','txt'=>'text/plain','zip'=>'application/zip');
    return isset($map[$ext])?$map[$ext]:'application/octet-stream';
}
function ohsc_resource_file_path($filename) {
    if (!preg_match('/^[a-f0-9]{32}\.(pdf|docx|xlsx|pptx|txt|zip)$/',$filename)) { return null; }
    return dirname(__DIR__).'/resource-files/'.$filename;
}

function ohsc_resource_icon($kind) {
    $paths=array(
        'search'=>'<circle cx="10" cy="10" r="6"/><path d="m15 15 6 6"/>',
        'templates'=>'<path d="M5 2h9l5 5v15H5zM14 2v6h5M8 12h8M8 16h8"/>',
        'checklists'=>'<path d="M7 3H4v19h16V3h-3M8 2h8v4H8zM7 10l1 1 2-2m2 1h5M7 16l1 1 2-2m2 1h5"/>',
        'worksheets'=>'<path d="M5 2h9l5 5v15H5zM14 2v6h5M8 11h8v7H8zM8 14h8m-4-3v7"/>',
        'examples'=>'<path d="M5 2h9l5 5v15H5zM14 2v6h5M8 12h8M8 16h5"/>',
        'toolkits'=>'<rect x="2" y="7" width="20" height="15" rx="3"/><path d="M8 7V3h8v4M2 13h20m-10-2v4"/>',
        'find-job'=>'<rect x="2" y="7" width="20" height="15" rx="3"/><path d="M8 7V3h8v4M2 13h20m-10-2v4"/>',
        'interviews'=>'<circle cx="12" cy="7" r="3"/><circle cx="4" cy="9" r="2"/><circle cx="20" cy="9" r="2"/><path d="M6 22v-3a6 6 0 0 1 12 0v3zM1 21v-3a4 4 0 0 1 4-4m18 7v-3a4 4 0 0 0-4-4"/>',
        'plan-career'=>'<path d="M3 22v-6h4v6m3 0V11h4v11m3 0V6h4v16M3 11l7-5 4 1 7-5m-5 0h5v5"/>',
        'develop-skills'=>'<path d="m1 8 11-5 11 5-11 5zM5 10v8q7 5 14 0v-8m4-2v11"/>'
    );
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.(isset($paths[$kind])?$paths[$kind]:$paths['templates']).'</svg>';
}

function ohsc_resources_for_page($db,$pageKey,$includeDeleted=false) {
    $pages=ohsc_resource_pages($db);$rows=$db->query('SELECT r.*, (SELECT COUNT(*) FROM tbl_career_resource_files f WHERE f.resource_id=r.resource_id) file_count FROM tbl_career_resources r ORDER BY sort_order,resource_id DESC')->fetch_all(MYSQLI_ASSOC);
    return array_values(array_filter($rows,function($r)use($pages,$pageKey,$includeDeleted){
        if ((bool)$r['is_deleted']!==$includeDeleted) { return false; }
        if (!$pageKey || $pageKey==='hub') { return true; }
        if (!isset($pages[$pageKey])) { return false; }
        if (!empty($pages[$pageKey]['resource_id'])) { return (int)$r['resource_id']===(int)$pages[$pageKey]['resource_id']; }
          return $pages[$pageKey]['parent_key']==='hub'?$r['category']===$pageKey:$r['subcategory']===$pageKey;
    }));
}
function ohsc_resource_action($db,$id,$action) {
    if (!in_array($action,array('hide','delete','restore'),true)) { return false; }
    $sql=$action==='delete'?'is_deleted=1,is_active=0':($action==='restore'?'is_deleted=0,is_active=0':'is_active=0');
    $stmt=$db->prepare('UPDATE tbl_career_resources SET '.$sql.',updated_at=NOW() WHERE resource_id=?');$stmt->bind_param('i',$id);$ok=$stmt->execute();$stmt->close();return $ok;
}
