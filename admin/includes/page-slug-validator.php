<?php

require_once __DIR__ . '/course-slug-validator.php';

function normalizePageSlug($slugInput)
{
    return str_replace(' ', '-', strtolower(trim($slugInput)));
}

function validateNewPageSlug($db, $slug)
{
    $slug = trim($slug);

    if ($slug === '') {
        return [
            'valid' => false,
            'code' => '1',
            'message' => 'Slug cannot be empty.',
        ];
    }

    $escapedSlug = mysqli_real_escape_string($db, $slug);
    $pageResult = $db->query("SELECT id FROM pages WHERE slug = '$escapedSlug' LIMIT 1");

    if ($pageResult && $pageResult->num_rows > 0) {
        return [
            'valid' => false,
            'code' => '2',
            'message' => 'This slug already exists in pages.',
        ];
    }

    $courseResult = $db->query("SELECT cid FROM tbl_courses WHERE slug = '$escapedSlug' LIMIT 1");

    if ($courseResult && $courseResult->num_rows > 0) {
        return [
            'valid' => false,
            'code' => '4',
            'message' => 'This slug is already used by a course category.',
        ];
    }

    $htaccessPath = dirname(__DIR__, 2) . '/.htaccess';
    $reservedSlugs = extractRootReservedSlugsFromHtaccess($htaccessPath);

    if (in_array(strtolower($slug), $reservedSlugs, true)) {
        return [
            'valid' => false,
            'code' => '3',
            'message' => 'This slug conflicts with a reserved site URL path.',
        ];
    }

    return ['valid' => true];
}
