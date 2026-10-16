<?php

function normalizeDiplomaSlug($slugInput, $fallbackName)
{
    if (!isset($slugInput) || trim($slugInput) === '') {
        return str_replace(' ', '-', strtolower($fallbackName));
    }

    return str_replace(' ', '-', strtolower(trim($slugInput)));
}

function validateNewDiplomaSlug($db, $slug)
{
    $slug = trim($slug);

    if ($slug === '') {
        return [
            'valid' => false,
            'message' => 'Slug cannot be empty.',
        ];
    }

    $escapedSlug = mysqli_real_escape_string($db, $slug);
    $result = $db->query("SELECT diplomaid FROM tbl_diploma WHERE slug = '$escapedSlug' LIMIT 1");

    if ($result && $result->num_rows > 0) {
        return [
            'valid' => false,
            'message' => 'This slug already exists in diplomas.',
        ];
    }

    return ['valid' => true];
}
