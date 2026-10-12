<?php
/**
 * Tools module.
 * This folder is separate from the rest of the site. Pages only read $db
 * and $base_url. Tool records and typing texts are stored in their own tables
 * and edited from the admin panel.
 */

if (!function_exists('ohsc_h')) {
    function ohsc_h($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('ohsc_tools_ensure')) {
    function ohsc_tools_ensure($db)
    {
        static $ready = false;
        if ($ready || !($db instanceof mysqli)) {
            return;
        }

        $db->query("CREATE TABLE IF NOT EXISTS tbl_site_tools (
            tool_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(150) NOT NULL,
            slug VARCHAR(80) NOT NULL,
            summary VARCHAR(255) NOT NULL DEFAULT '',
            sort_order INT NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (tool_id),
            UNIQUE KEY slug (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->query("CREATE TABLE IF NOT EXISTS tbl_typing_passages (
            passage_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(150) NOT NULL,
            passage_text TEXT NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (passage_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $now = date('Y-m-d H:i:s');
        $toolCount = $db->query("SELECT COUNT(*) AS total FROM tbl_site_tools");
        $toolTotal = ($toolCount) ? (int) $toolCount->fetch_assoc()['total'] : 0;
        if ($toolTotal === 0) {
            $stmt = $db->prepare("INSERT INTO tbl_site_tools (title, slug, summary, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $title = 'Typing Speed Test';
                $slug = 'typing-test';
                $summary = 'Check your typing speed and accuracy in one, three, or five minutes.';
                $sort = 1;
                $active = 1;
                $stmt->bind_param('sssiss', $title, $slug, $summary, $sort, $active, $now);
                $stmt->execute();
                $stmt->close();
            }
        }

        $passageCount = $db->query("SELECT COUNT(*) AS total FROM tbl_typing_passages");
        $passageTotal = ($passageCount) ? (int) $passageCount->fetch_assoc()['total'] : 0;
        if ($passageTotal === 0) {
            $samples = array(
                array(
                    'Daily study habit',
                    'A student who studies a little every day remembers more than a student who studies for many hours once a week. Short lessons, clear notes, and a quiet place to work make learning easier. When you type your notes, you also practise a skill that offices use every day. Keep your sentences short, and read them once before you save them.'
                ),
                array(
                    'Clear writing at work',
                    'Clear writing helps people at work. A short email that says what you need, and when you need it, saves time for everyone. Read the message once before you send it. Check the names, the dates, and the next step. A calm tone is easier to answer than a long message written in a hurry.'
                ),
                array(
                    'Why speed matters',
                    'Many jobs ask for a steady typing speed. Reception work, customer support, and data entry all use a keyboard for most of the day. Accuracy matters first. Speed grows when your fingers learn the keys and you stop looking down. A clean page with few mistakes is worth more than a fast page full of errors.'
                ),
                array(
                    'Home row keys',
                    'The home row is the base of touch typing. The left hand rests on A, S, D, and F. The right hand rests on J, K, L, and the semicolon key. From there, each finger reaches the keys above and below, then returns home. If you keep this habit, your hands stop hunting for the next letter.'
                ),
                array(
                    'A comfortable desk',
                    'A good chair, a screen at eye level, and wrists that stay relaxed will help you type for longer. Keep both feet on the floor. Take a short break when your shoulders feel tight. Comfort lets you keep a smooth rhythm. Sit tall, look at the screen, and let the keyboard stay just below your elbows.'
                ),
                array(
                    'Common words',
                    'Practice with real words, not only single letters. Common words such as the, and, you, that, and for appear in almost every sentence. When those words become automatic, your speed on a full paragraph rises quickly. Then move on to longer lines about work, study, and the tasks you do each day.'
                ),
            );
            $stmt = $db->prepare("INSERT INTO tbl_typing_passages (title, passage_text, is_active, created_at, updated_at) VALUES (?, ?, 1, ?, ?)");
            if ($stmt) {
                foreach ($samples as $sample) {
                    $stmt->bind_param('ssss', $sample[0], $sample[1], $now, $now);
                    $stmt->execute();
                }
                $stmt->close();
            }
        }

        $extraPassages = require dirname(__FILE__) . '/extra-passages.php';
        $haveTitles = array();
        $titleResult = $db->query("SELECT title FROM tbl_typing_passages");
        if ($titleResult) {
            while ($titleRow = $titleResult->fetch_assoc()) {
                $haveTitles[$titleRow['title']] = true;
            }
        }
        $insertExtra = $db->prepare("INSERT INTO tbl_typing_passages (title, passage_text, is_active, created_at, updated_at) VALUES (?, ?, 1, ?, ?)");
        if ($insertExtra) {
            foreach ($extraPassages as $extraPassage) {
                if (isset($haveTitles[$extraPassage[0]])) {
                    continue;
                }
                $insertExtra->bind_param('ssss', $extraPassage[0], $extraPassage[1], $now, $now);
                $insertExtra->execute();
            }
            $insertExtra->close();
        }

        $ready = true;
    }
}

if (!function_exists('ohsc_tools_active')) {
    function ohsc_tools_active($db)
    {
        $tools = array();
        if (!($db instanceof mysqli)) {
            return $tools;
        }
        ohsc_tools_ensure($db);
        $result = $db->query("SELECT tool_id, title, slug, summary FROM tbl_site_tools WHERE is_active = 1 ORDER BY sort_order ASC, tool_id ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tools[] = $row;
            }
        }
        return $tools;
    }
}

if (!function_exists('ohsc_tools_slug_ok')) {
    function ohsc_tools_slug_ok($slug)
    {
        return (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
    }
}

if (!function_exists('ohsc_typing_passages')) {
    function ohsc_typing_passages($db)
    {
        $passages = array();
        if (!($db instanceof mysqli)) {
            return $passages;
        }
        ohsc_tools_ensure($db);
        $result = $db->query("SELECT passage_text FROM tbl_typing_passages WHERE is_active = 1 AND passage_text <> '' ORDER BY passage_id ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $text = trim(preg_replace('/\s+/', ' ', $row['passage_text']));
                if ($text !== '') {
                    $passages[] = $text;
                }
            }
        }
        return $passages;
    }
}
