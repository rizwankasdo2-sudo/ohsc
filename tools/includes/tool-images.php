<?php
/** Per-tool image overrides. Original assets remain intact. */
function ohsc_image_table($db) {
    static $ready = false;
    if ($ready) { return; }
    $db->query("CREATE TABLE IF NOT EXISTS tbl_tool_images (tool_slug VARCHAR(80) NOT NULL, source VARCHAR(240) NOT NULL, filename VARCHAR(80) NOT NULL, PRIMARY KEY(tool_slug, source)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $ready = true;
}
function ohsc_image_dimensions($file) {
    $size = @getimagesize($file);
    if ($size) { return array((int)$size[0], (int)$size[1]); }
    if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'svg') {
        $svg = file_get_contents($file);
        if (preg_match('/<svg[^>]*width="([0-9]+)"[^>]*height="([0-9]+)"/', $svg, $m)) { return array((int)$m[1], (int)$m[2]); }
        if (preg_match('/viewBox="0 0 ([0-9]+) ([0-9]+)"/', $svg, $m)) { return array((int)$m[1], (int)$m[2]); }
    }
    return null;
}
function ohsc_image_inventory($db, $slug) {
    $allowed = array('typing-test','eq-test','career-quiz','cv-builder','interview-prep');
    if (!in_array($slug, $allowed, true)) { return array(); }
    $root = dirname(__DIR__, 2);
    $files = array($root . '/tools/' . $slug . '/index.php');
    if ($slug === 'career-quiz') { $files[] = __DIR__ . '/career-quiz-seed.php'; }
    $sources = array();
    foreach ($files as $file) {
        preg_match_all('/[a-zA-Z0-9_-]+\.(?:jpg|jpeg|png|webp|svg)\b/', file_get_contents($file), $matches);
        foreach ($matches[0] as $name) { $sources['/tools/assets/' . $name] = true; }
    }
    if ($slug === 'interview-prep') {
        $rows = $db->query("SELECT image FROM tbl_iv_industries");
        while ($rows && $row = $rows->fetch_assoc()) { $sources['/tools/assets/' . basename($row['image'])] = true; }
    }
    if ($slug === 'typing-test') {
        $rows = $db->query("SELECT td.square_icon FROM tbl_diploma td INNER JOIN tbl_courses tc ON tc.cid=td.cid WHERE td.IStatus=1 AND tc.IStatus=1 AND td.type=2 ORDER BY td.ordernumber ASC LIMIT 4");
        while ($rows && $row = $rows->fetch_assoc()) { if ($row['square_icon'] !== '') { $sources['/images/subject/' . basename($row['square_icon'])] = true; } }
    }
    $result = array();
    foreach ($sources as $source => $unused) {
        $size = ohsc_image_dimensions($root . $source);
        if ($size) { $result[$source] = array('width'=>$size[0], 'height'=>$size[1], 'label'=>ucwords(str_replace(array('-', '_'), ' ', pathinfo($source, PATHINFO_FILENAME)))); }
    }
    return $result;
}
function ohsc_image_overrides($db, $slug) {
    ohsc_image_table($db);
    $stmt = $db->prepare('SELECT source, filename FROM tbl_tool_images WHERE tool_slug=?');
    $stmt->bind_param('s', $slug); $stmt->execute(); $rows = $stmt->get_result(); $map = array();
    while ($row = $rows->fetch_assoc()) {
        if (preg_match('/^[a-f0-9]{32}\.webp$/', $row['filename']) && is_file(dirname(__DIR__) . '/uploads/' . $row['filename'])) { $map[$row['source']] = '/tools/uploads/' . $row['filename']; }
    }
    $stmt->close(); return $map;
}
function ohsc_image_validate($tmp, $name, $size, $required) {
    if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) !== 'webp') { return 'Only .webp images are allowed.'; }
    if ($size <= 0 || $size > 5 * 1024 * 1024) { return 'Image must be no larger than 5 MB.'; }
    $info = @getimagesize($tmp);
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
    if (!$info || $info[2] !== IMAGETYPE_WEBP || $mime !== 'image/webp') { return 'The file must be a genuine WebP image.'; }
    if ($info[0] !== $required['width'] || $info[1] !== $required['height']) { return 'Required dimensions: ' . $required['width'] . ' × ' . $required['height'] . ' px. Uploaded: ' . $info[0] . ' × ' . $info[1] . ' px.'; }
    return '';
}
function ohsc_image_start($db, $slug, $base) {
    $map = ohsc_image_overrides($db, $slug);
    $GLOBALS['ohsc_tool_image_urls'] = array();
    foreach ($map as $source => $target) { $GLOBALS['ohsc_tool_image_urls'][basename($source)] = rtrim($base, '/') . $target; }
    ob_start(function($html) use ($map, $base) {
        return preg_replace_callback('/(<img\b[^>]*\bsrc=")([^"]+)(")/i', function($m) use ($map, $base) {
            $path = parse_url(html_entity_decode($m[2], ENT_QUOTES, 'UTF-8'), PHP_URL_PATH);
            foreach ($map as $source => $target) {
                if ($path === parse_url(rtrim($base, '/') . $source, PHP_URL_PATH)) { return $m[1] . htmlspecialchars(rtrim($base, '/') . $target, ENT_QUOTES, 'UTF-8') . $m[3]; }
            }
            return $m[0];
        }, $html);
    });
}
