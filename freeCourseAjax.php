<?php
include("admin/classes/connection.php");
$diplomaId = $_REQUEST['diplomaId'];
$sql = "SELECT course_state FROM tbl_diploma WHERE diplomaid='$diplomaId'";
$result = $db->query($sql);
if(!$result){
    die(mysqli_error($db));
}

$row = $result->fetch_array();

echo (int) $row['course_state'];