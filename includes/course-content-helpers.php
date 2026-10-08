<?php

if (!function_exists('renderCourseStoredHtml')) {
    function renderCourseStoredHtml($html)
    {
        if ($html === null) {
            return '';
        }

        $html = (string) $html;
        if ($html === '') {
            return '';
        }

        if (strpos($html, '\\') !== false) {
            $html = stripcslashes($html);
        }

        if (strpos($html, '&lt;') !== false || strpos($html, '&gt;') !== false) {
            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $html;
    }
}

if (!function_exists('normalizeCourseUkSpelling')) {
    function normalizeCourseUkSpelling($text)
    {
        static $replacements = array(
            '/\benrollment\b/i' => 'enrolment',
            '/\borganizations\b/i' => 'organisations',
            '/\borganization\b/i' => 'organisation',
            '/\brecognized\b/i' => 'recognised',
            '/\brecognize\b/i' => 'recognise',
            '/\banalyzed\b/i' => 'analysed',
            '/\banalyze\b/i' => 'analyse',
            '/\bbehavior\b/i' => 'behaviour',
            '/\bfavorites\b/i' => 'favourites',
            '/\bfavorite\b/i' => 'favourite',
            '/\bfavor\b/i' => 'favour',
            '/\bmodeling\b/i' => 'modelling',
            '/\blabeled\b/i' => 'labelled',
            '/\blabeling\b/i' => 'labelling',
        );

        foreach ($replacements as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }
}

if (!function_exists('fixCourseJoinedWordsInText')) {
    function fixCourseJoinedWordsInText($text)
    {
        $text = preg_replace('/(\.)([A-Za-z])/', '$1 $2', $text);
        $text = preg_replace('/([a-z])([A-Z])/', '$1 $2', $text);
        $text = preg_replace('/\s{2,}/', ' ', $text);

        return $text;
    }
}

if (!function_exists('fixCourseJoinedWords')) {
    function fixCourseJoinedWords($html)
    {
        return preg_replace_callback('/>([^<]+)</', function ($matches) {
            return '>' . fixCourseJoinedWordsInText($matches[1]) . '<';
        }, $html);
    }
}

if (!function_exists('removeEmptyCourseHeadings')) {
    function removeEmptyCourseHeadings($html)
    {
        return preg_replace('/<h([1-6])(\s[^>]*)?>\s*(?:&nbsp;|\xc2\xa0|\s|<br\s*\/?>)*\s*<\/h\1>/i', '', $html);
    }
}

if (!function_exists('prepareCourseHtml')) {
    function prepareCourseHtml($html)
    {
        $html = renderCourseStoredHtml($html);
        if (trim($html) === '') {
            return '';
        }

        $html = fixCourseJoinedWords($html);
        $html = removeEmptyCourseHeadings($html);
        $html = normalizeCourseUkSpelling($html);

        return $html;
    }
}

if (!function_exists('prepareCourseSubjectRow')) {
    function prepareCourseSubjectRow($row)
    {
        if (!is_array($row)) {
            return $row;
        }

        $htmlFields = array('courseoverview', 'learning_outcomes', 'accreditation', 'coursecontent');
        foreach ($htmlFields as $field) {
            if (isset($row[$field])) {
                $row[$field] = prepareCourseHtml($row[$field]);
            }
        }

        return $row;
    }
}

if (!function_exists('parseCourseCsvList')) {
    function parseCourseCsvList($csv)
    {
        if ($csv === null || trim((string) $csv) === '') {
            return array();
        }

        $items = explode(',', (string) $csv);
        $items = array_map('trim', $items);
        $items = array_map('normalizeCourseUkSpelling', $items);

        return array_values(array_filter($items, function ($item) {
            return $item !== '';
        }));
    }
}

if (!function_exists('formatCourseModuleDisplay')) {
    function formatCourseModuleDisplay($index, $title)
    {
        $title = trim(normalizeCourseUkSpelling((string) $title));
        $moduleNumber = (int) $index;

        if ($title !== '' && preg_match('/^module\s*' . $moduleNumber . '\s*[:.\-]?\s*(.*)$/i', $title, $matches)) {
            $cleanTitle = trim($matches[1]);
            if ($cleanTitle === '') {
                return array(
                    'label' => 'Module ' . $moduleNumber,
                    'title' => '',
                );
            }

            return array(
                'label' => 'Module ' . $moduleNumber,
                'title' => $cleanTitle,
            );
        }

        if ($title !== '' && preg_match('/^module\s*\d+/i', $title)) {
            return array(
                'label' => '',
                'title' => $title,
            );
        }

        return array(
            'label' => 'Module ' . $moduleNumber,
            'title' => $title,
        );
    }
}
