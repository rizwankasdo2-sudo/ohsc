<?php
/** Add starter content once. Admin edits, hiding and deletion are never overwritten. */
function ohsc_resource_starters($db) {
    static $tableReady=false;
    if (!$tableReady) { $db->query("CREATE TABLE IF NOT EXISTS tbl_resource_starter_seeds(seed_key VARCHAR(80) NOT NULL PRIMARY KEY,resource_id INT UNSIGNED NOT NULL DEFAULT 0) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");$tableReady=true; }
    $manifest=json_decode(file_get_contents(dirname(__DIR__).'/seed-resources/manifest.json'),true);
    if (!is_array($manifest)) { return; }
    $done=array_column($db->query('SELECT seed_key FROM tbl_resource_starter_seeds')->fetch_all(MYSQLI_ASSOC),'seed_key');
    foreach ($manifest as $r) {
        if (in_array($r['seed_key'],$done,true)) { continue; }
        $created=array();$db->begin_transaction();
        try {
            $stmt=$db->prepare('INSERT IGNORE INTO tbl_resource_starter_seeds(seed_key) VALUES(?)');$stmt->bind_param('s',$r['seed_key']);$stmt->execute();$claimed=$stmt->affected_rows===1;$stmt->close();
            if (!$claimed) { $db->rollback();continue; }
            $stmt=$db->prepare("INSERT INTO tbl_career_resources(title,summary,category,goal,subcategory,answer,is_active,is_featured,sort_order,created_at,updated_at) VALUES(?,?,?,?,?,?,1,0,10,NOW(),NOW())");$stmt->bind_param('ssssss',$r['title'],$r['summary'],$r['category'],$r['goal'],$r['subcategory'],$r['answer']);$stmt->execute();$id=(int)$db->insert_id;$stmt->close();
            foreach (array('pdf','text') as $format) {
                $source=dirname(__DIR__).'/seed-resources/'.basename($r[$format]);$ext=$format==='pdf'?'pdf':'docx';$filename=md5('ohsc-starter-v1-'.$r['seed_key'].'-'.$ext).'.'.$ext;$target=dirname(__DIR__).'/resource-files/'.$filename;
                if (!is_file($source)) { throw new RuntimeException('Starter file missing.'); }
                if (!is_file($target)) { if (!($ext==='docx'?ohsc_starter_docx($source,$target):copy($source,$target))) { throw new RuntimeException('Starter file could not be copied.'); }$created[]=$target; }
                $name=pathinfo($r[$format],PATHINFO_FILENAME).'.'.$ext;$bytes=filesize($target);$mime=ohsc_resource_mime($ext);$stmt=$db->prepare('INSERT INTO tbl_career_resource_files(resource_id,filename,original_name,extension,mime,bytes) VALUES(?,?,?,?,?,?)');$stmt->bind_param('issssi',$id,$filename,$name,$ext,$mime,$bytes);$stmt->execute();$stmt->close();
            }
            $stmt=$db->prepare('UPDATE tbl_resource_starter_seeds SET resource_id=? WHERE seed_key=?');$stmt->bind_param('is',$id,$r['seed_key']);$stmt->execute();$stmt->close();$db->commit();
        } catch (Throwable $e) { $db->rollback();foreach ($created as $path) { if (is_file($path)) { unlink($path); } }throw $e; }
    }
    ohsc_starter_word_migrate($db,$manifest);
}

/** Real editable Word package, generated from the supplied starter text. */
function ohsc_starter_docx($source,$target) {
    $bundled=preg_replace('/\.txt$/i','.docx',$source);if ($bundled!==$source && is_file($bundled)) { return copy($bundled,$target); }
    if (!class_exists('ZipArchive')) { throw new RuntimeException('PHP ZIP extension is required for Word resources.'); }
    $xml='';foreach (preg_split('/\r?\n/',file_get_contents($source)) as $line) { $xml.='<w:p><w:r><w:t xml:space="preserve">'.htmlspecialchars($line,ENT_XML1|ENT_QUOTES,'UTF-8').'</w:t></w:r></w:p>'; }
    $zip=new ZipArchive();if ($zip->open($target,ZipArchive::CREATE|ZipArchive::OVERWRITE)!==true) return false;
    $zip->addFromString('[Content_Types].xml','<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/></Types>');
    $zip->addFromString('_rels/.rels','<?xml version="1.0"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>');
    $zip->addFromString('word/document.xml','<?xml version="1.0" encoding="UTF-8"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>'.$xml.'<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1134" w:right="1134" w:bottom="1134" w:left="1134"/></w:sectPr></w:body></w:document>');return $zip->close();
}
function ohsc_starter_word_migrate($db,$manifest) {
    foreach ($manifest as $r) {
        $old=md5('ohsc-starter-v1-'.$r['seed_key'].'-txt').'.txt';$new=md5('ohsc-starter-v1-'.$r['seed_key'].'-docx').'.docx';
        $stmt=$db->prepare("SELECT f.file_id FROM tbl_career_resource_files f JOIN tbl_resource_starter_seeds s ON s.resource_id=f.resource_id WHERE s.seed_key=? AND f.filename=? AND f.extension='txt'");$stmt->bind_param('ss',$r['seed_key'],$old);$stmt->execute();$found=$stmt->get_result()->fetch_assoc();$stmt->close();if (!$found) continue;
        $target=dirname(__DIR__).'/resource-files/'.$new;
        if (!is_file($target)&&!ohsc_starter_docx(dirname(__DIR__).'/seed-resources/'.basename($r['text']),$target)) { throw new RuntimeException('Word resource could not be generated.'); }
        $name=pathinfo($r['text'],PATHINFO_FILENAME).'.docx';$mime=ohsc_resource_mime('docx');$size=filesize($target);
        $stmt=$db->prepare("UPDATE tbl_career_resource_files SET filename=?,original_name=?,extension='docx',mime=?,bytes=? WHERE file_id=? AND filename=?");$stmt->bind_param('sssiis',$new,$name,$mime,$size,$found['file_id'],$old);$stmt->execute();$stmt->close();
    }
}
