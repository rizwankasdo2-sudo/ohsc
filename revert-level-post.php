<?php



error_reporting(E_ALL);

ini_set('display_errors', 1);



$base = 'https://www.oxfordhomestudy.com/lms/';



$db = new mysqli('localhost', 'root', '', 'oxfordhomestudy_myhomestudy');



// Check if the connection was successful



$sql = "UPDATE courses SET level_id = NULL";

mysqli_query($db, $sql);



if (mysqli_affected_rows($db) > 0) {

    echo "All rows updated successfully!";

} else {

    echo "No rows were updated.";

}



?>

