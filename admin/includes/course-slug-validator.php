<?php

function normalizeCourseSlug($slugInput, $fallbackName)
{
    if (!isset($slugInput) || trim($slugInput) === '') {
        return str_replace(' ', '-', strtolower($fallbackName));
    }

    return str_replace(' ', '-', strtolower(trim($slugInput)));
}

function normalizeHtaccessPatternSegment($segment)
{
    $segment = preg_replace('/\\\\(.)/', '$1', $segment);

    return strtolower(trim($segment, '/'));
}

function parseHtaccessReservedSlugs($htaccessPath, $includeCourseCategorySegments)
{
    static $cache = array();

    $cacheKey = $includeCourseCategorySegments ? 'full' : 'root';

    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }

    $reserved = array();

    if (!is_readable($htaccessPath)) {
        $cache[$cacheKey] = $reserved;

        return $cache[$cacheKey];
    }

    $lines = file($htaccessPath, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || $line[0] === '#') {
            continue;
        }

        if (preg_match('/^Redirect(?:Match|\s+\d+)?\s+/i', $line)) {
            if (preg_match('#\s(/[^?\s#]+)#', $line, $match)) {
                $parts = explode('/', trim($match[1], '/'));

                if (!empty($parts[0])) {
                    $reserved[] = normalizeHtaccessPatternSegment($parts[0]);
                }
            }

            continue;
        }

        if (!preg_match('/^RewriteRule\s+\^(.+?)\s+/i', $line, $match)) {
            continue;
        }

        $pattern = $match[1];

        if (preg_match('/^([a-zA-Z0-9\\\\._-]+)\/?\$/', $pattern, $segmentMatch)) {
            $reserved[] = normalizeHtaccessPatternSegment($segmentMatch[1]);
        }

        if (preg_match('/^([a-zA-Z0-9\\\\._-]+)(?:\(|\/)/', $pattern, $segmentMatch)) {
            $reserved[] = normalizeHtaccessPatternSegment($segmentMatch[1]);
        }

        if ($includeCourseCategorySegments && preg_match('/^courses\\/([a-zA-Z0-9\\\\._-]+)/', $pattern, $segmentMatch)) {
            if ($segmentMatch[1][0] !== '(') {
                $reserved[] = normalizeHtaccessPatternSegment($segmentMatch[1]);
            }
        }

        if (preg_match('/^([a-zA-Z0-9\\\\._-]+)\\.php\$/', $pattern, $segmentMatch)) {
            $reserved[] = normalizeHtaccessPatternSegment($segmentMatch[1]);
        }
    }

    $reserved = array_values(array_unique(array_filter($reserved)));
    $cache[$cacheKey] = $reserved;

    return $cache[$cacheKey];
}

function extractRootReservedSlugsFromHtaccess($htaccessPath)
{
    return parseHtaccessReservedSlugs($htaccessPath, false);
}

function extractReservedSlugsFromHtaccess($htaccessPath)
{
    return parseHtaccessReservedSlugs($htaccessPath, true);
}

function validateNewCourseSlug($db, $slug, $previousFreeCategory)
{
    $slug = trim($slug);

    if ($slug === '') {
        return [
            'valid' => false,
            'code' => '6',
            'message' => 'Slug cannot be empty.',
        ];
    }

    $escapedSlug = mysqli_real_escape_string($db, $slug);
    $courseResult = $db->query("SELECT cid FROM tbl_courses WHERE slug = '$escapedSlug' LIMIT 1");

    if ($courseResult && $courseResult->num_rows > 0) {
        return [
            'valid' => false,
            'code' => '3',
            'message' => 'This slug already exists in courses.',
        ];
    }

    $htaccessPath = dirname(__DIR__, 2) . '/.htaccess';
    $reservedSlugs = extractReservedSlugsFromHtaccess($htaccessPath);

    if (in_array(strtolower($slug), $reservedSlugs, true)) {
        return [
            'valid' => false,
            'code' => '4',
            'message' => 'This slug conflicts with a reserved site URL path.',
        ];
    }

    if ((int) $previousFreeCategory === 1 && courseSlugConflictsWithPages($db, $slug)) {
        return [
            'valid' => false,
            'code' => '5',
            'message' => 'This slug already exists in CMS pages.',
        ];
    }

    return ['valid' => true];
}

function courseSlugConflictsWithPages($db, $slug)
{
    $slug = trim($slug);

    if ($slug === '') {
        return false;
    }

    $escapedSlug = mysqli_real_escape_string($db, $slug);
    $pageResult = $db->query("SELECT id FROM pages WHERE slug = '$escapedSlug' LIMIT 1");

    return $pageResult && $pageResult->num_rows > 0;
}

function validateUpdateCourseFreeCategory($db, $courseId, $previousFreeCategory)
{
    if ((int) $previousFreeCategory !== 1) {
        return ['valid' => true];
    }

    $courseId = (int) $courseId;
    $courseResult = $db->query("SELECT slug FROM tbl_courses WHERE cid = $courseId LIMIT 1");

    if (!$courseResult || $courseResult->num_rows === 0) {
        return [
            'valid' => false,
            'message' => 'Course not found.',
        ];
    }

    $courseRow = $courseResult->fetch_array();

    if (courseSlugConflictsWithPages($db, $courseRow['slug'])) {
        return [
            'valid' => false,
            'message' => 'This slug already exists in CMS pages.',
        ];
    }

    return ['valid' => true];
}
