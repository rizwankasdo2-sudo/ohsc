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

        $db->query("CREATE TABLE IF NOT EXISTS tbl_eq_questions (
            question_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            prompt VARCHAR(400) NOT NULL,
            area VARCHAR(40) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (question_id),
            KEY area_sort (area, sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->query("CREATE TABLE IF NOT EXISTS tbl_eq_choices (
            choice_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            question_id INT UNSIGNED NOT NULL,
            choice_text VARCHAR(255) NOT NULL,
            points TINYINT UNSIGNED NOT NULL DEFAULT 1,
            sort_order INT NOT NULL DEFAULT 0,
            PRIMARY KEY (choice_id),
            KEY question_id (question_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $eqTool = $db->query("SELECT tool_id FROM tbl_site_tools WHERE slug = 'eq-test' LIMIT 1");
        if ($eqTool && (int) $eqTool->num_rows === 0) {
            $stmt = $db->prepare("INSERT INTO tbl_site_tools (title, slug, summary, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $title = 'Emotional Intelligence Test';
                $slug = 'eq-test';
                $summary = 'Answer short questions and see how you notice, manage, and respond to emotions.';
                $sort = 2;
                $active = 1;
                $stmt->bind_param('sssiss', $title, $slug, $summary, $sort, $active, $now);
                $stmt->execute();
                $stmt->close();
            }
        }

        $eqCount = $db->query("SELECT COUNT(*) AS total FROM tbl_eq_questions");
        $eqTotal = ($eqCount) ? (int) $eqCount->fetch_assoc()['total'] : 0;
        if ($eqTotal === 0) {
            $seedQuestions = require dirname(__FILE__) . '/eq-seed.php';
            $insertQuestion = $db->prepare("INSERT INTO tbl_eq_questions (prompt, area, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, ?, ?)");
            $insertChoice = $db->prepare("INSERT INTO tbl_eq_choices (question_id, choice_text, points, sort_order) VALUES (?, ?, ?, ?)");
            if ($insertQuestion && $insertChoice) {
                $order = 1;
                foreach ($seedQuestions as $seedQuestion) {
                    $insertQuestion->bind_param('ssiss', $seedQuestion['prompt'], $seedQuestion['area'], $order, $now, $now);
                    $insertQuestion->execute();
                    $questionId = (int) $db->insert_id;
                    $choiceOrder = 1;
                    foreach ($seedQuestion['choices'] as $seedChoice) {
                        $points = (int) $seedChoice[1];
                        $insertChoice->bind_param('isii', $questionId, $seedChoice[0], $points, $choiceOrder);
                        $insertChoice->execute();
                        $choiceOrder++;
                    }
                    $order++;
                }
                $insertQuestion->close();
                $insertChoice->close();
            }
        }

        $cvTool = $db->query("SELECT tool_id FROM tbl_site_tools WHERE slug = 'cv-builder' LIMIT 1");
        if ($cvTool && (int) $cvTool->num_rows === 0) {
            $stmt = $db->prepare("INSERT INTO tbl_site_tools (title, slug, summary, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $title = 'CV Builder';
                $slug = 'cv-builder';
                $summary = 'Enter your details, pick a template, and download a CV that is ready to send.';
                $sort = 3;
                $active = 1;
                $stmt->bind_param('sssiss', $title, $slug, $summary, $sort, $active, $now);
                $stmt->execute();
                $stmt->close();
            }
        }

        $quizTool = $db->query("SELECT tool_id FROM tbl_site_tools WHERE slug = 'career-quiz' LIMIT 1");
        if ($quizTool && (int) $quizTool->num_rows === 0) {
            $stmt = $db->prepare("INSERT INTO tbl_site_tools (title, slug, summary, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $title = 'Career Quiz';
                $slug = 'career-quiz';
                $summary = 'Answer ten short questions and see which career paths could suit you.';
                $sort = 4;
                $active = 1;
                $stmt->bind_param('sssiss', $title, $slug, $summary, $sort, $active, $now);
                $stmt->execute();
                $stmt->close();
            }
        }

        $interviewTool = $db->query("SELECT tool_id FROM tbl_site_tools WHERE slug = 'interview-prep' LIMIT 1");
        if ($interviewTool && (int) $interviewTool->num_rows === 0) {
            $stmt = $db->prepare("INSERT INTO tbl_site_tools (title, slug, summary, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $title = 'Interview Prep';
                $slug = 'interview-prep';
                $summary = 'Practise interview questions by industry and review sample answers.';
                $sort = 5;
                $active = 1;
                $stmt->bind_param('sssiss', $title, $slug, $summary, $sort, $active, $now);
                $stmt->execute();
                $stmt->close();
            }
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

if (!function_exists('ohsc_eq_areas')) {
    function ohsc_eq_areas()
    {
        return array(
            'self-awareness' => array('Self-Awareness', 'Understand your emotions, strengths and triggers.'),
            'self-regulation' => array('Self-Regulation', 'Learn how you manage your emotions and handle challenges.'),
            'motivation' => array('Motivation', 'Discover your drive, ambition and commitment to goals.'),
            'empathy' => array('Empathy', 'Understand and connect with others.'),
            'social-skills' => array('Social Skills', 'See how you communicate and build relationships.'),
        );
    }
}

if (!function_exists('ohsc_eq_questions')) {
    function ohsc_eq_questions($db)
    {
        $questions = array();
        if (!($db instanceof mysqli)) {
            return $questions;
        }
        ohsc_tools_ensure($db);
        $result = $db->query("SELECT question_id, prompt, area FROM tbl_eq_questions WHERE is_active = 1 ORDER BY sort_order ASC, question_id ASC");
        if (!$result) {
            return $questions;
        }
        $ids = array();
        while ($row = $result->fetch_assoc()) {
            $row['choices'] = array();
            $questions[$row['question_id']] = $row;
            $ids[] = (int) $row['question_id'];
        }
        if (!$ids) {
            return array();
        }
        $choiceResult = $db->query("SELECT choice_id, question_id, choice_text, points FROM tbl_eq_choices WHERE question_id IN (" . implode(',', $ids) . ") ORDER BY sort_order ASC, choice_id ASC");
        if ($choiceResult) {
            while ($choice = $choiceResult->fetch_assoc()) {
                $questionId = (int) $choice['question_id'];
                if (isset($questions[$questionId])) {
                    $questions[$questionId]['choices'][] = array(
                        'id' => (int) $choice['choice_id'],
                        'text' => $choice['choice_text'],
                        'points' => (int) $choice['points'],
                    );
                }
            }
        }
        return array_values($questions);
    }
}
