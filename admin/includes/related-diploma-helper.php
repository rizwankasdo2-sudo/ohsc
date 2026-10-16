<?php

function buildRelatedDiplomasFromIds($db, $related_diploma_ids_raw)
{
    $related_diplomas = [];

    if (empty($related_diploma_ids_raw)) {
        return $related_diplomas;
    }

    $related_id_list = array_filter(array_map('intval', array_map('trim', explode(',', $related_diploma_ids_raw))));

    if (empty($related_id_list)) {
        return $related_diplomas;
    }

    $related_result = $db->query('SELECT diplomaid, name FROM tbl_diploma WHERE diplomaid IN (' . implode(',', $related_id_list) . ') ORDER BY name ASC');

    while ($related_row = $related_result->fetch_array()) {
        $related_diplomas[] = $related_row;
    }

    return $related_diplomas;
}

function formatRelatedDiplomaIds($related_diploma_ids_post, $exclude_id = 0)
{
    if (empty($related_diploma_ids_post) || !is_array($related_diploma_ids_post)) {
        return '';
    }

    $related_ids = array_filter(array_map('intval', $related_diploma_ids_post), function ($id) use ($exclude_id) {
        return $id > 0 && $id != $exclude_id;
    });

    return implode(', ', $related_ids);
}
