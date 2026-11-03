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
                $source=dirname(__DIR__).'/seed-resources/'.basename($r[$format]);$ext=$format==='pdf'?'pdf':'txt';$filename=md5('ohsc-starter-v1-'.$r['seed_key'].'-'.$ext).'.'.$ext;$target=dirname(__DIR__).'/resource-files/'.$filename;
                if (!is_file($source)) { throw new RuntimeException('Starter file missing.'); }
                if (!is_file($target)) { if (!copy($source,$target)) { throw new RuntimeException('Starter file could not be copied.'); }$created[]=$target; }
                $name=basename($r[$format]);$bytes=filesize($target);$mime=$ext==='pdf'?'application/pdf':'text/plain';$stmt=$db->prepare('INSERT INTO tbl_career_resource_files(resource_id,filename,original_name,extension,mime,bytes) VALUES(?,?,?,?,?,?)');$stmt->bind_param('issssi',$id,$filename,$name,$ext,$mime,$bytes);$stmt->execute();$stmt->close();
            }
            $stmt=$db->prepare('UPDATE tbl_resource_starter_seeds SET resource_id=? WHERE seed_key=?');$stmt->bind_param('is',$id,$r['seed_key']);$stmt->execute();$stmt->close();$db->commit();
        } catch (Throwable $e) { $db->rollback();foreach ($created as $path) { if (is_file($path)) { unlink($path); } }throw $e; }
    }
}
