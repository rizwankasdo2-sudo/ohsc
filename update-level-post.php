<?php



error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new mysqli('localhost', 'root', '', 'oxfordhomestudy_testdb');

// Check if the connection was successful


$result = $db->query("SELECT id, title FROM courses");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $course_id = $row['id'];
        $title = strtolower($row['title']); // Convert title to lowercase



        // Extract level number, considering cases like "- Level 3", "Level - 3", "Level3"
        // preg_match('/level\s*-?\s*(\d+)/i', $title, $matches);

        // Extract level number, handling typos and different formats
        preg_match('/l[e]?v[e]?l\s*-?\s*(\d{1,2})/i', $title, $matches);
        
        if (!empty($matches[1])) {
            $level_number = (int)$matches[1]; // Extracted level number

            // Find corresponding level_id
            $level_result = $db->query("SELECT id FROM level WHERE name = 'Level $level_number' LIMIT 1");

            if ($level_result->num_rows > 0) {
                $level_row = $level_result->fetch_assoc();
                $level_id = $level_row['id'];

                // Update courses table
                $db->query("UPDATE courses SET level_id = $level_id WHERE id = $course_id");
            }
        }
    }
}

echo "Courses updated successfully!";

?>
