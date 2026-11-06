<?php
function ohsc_content_article($db,$topic,$fallback='/career-expert/career-advice') {
    $like='%'.$topic.'%';$stmt=$db->prepare("SELECT slug FROM blog WHERE status=1 AND slug<>'' AND title LIKE ? ORDER BY publish_date DESC LIMIT 1");$stmt->bind_param('s',$like);$stmt->execute();$row=$stmt->get_result()->fetch_assoc();$stmt->close();return $row?'/OHSC-Blog/'.rawurlencode($row['slug']):$fallback;
}
