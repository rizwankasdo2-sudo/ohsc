<?php
include("classes/connection.php");
$status = $_POST['status'];
$info_id = $_POST['info_id'];
$sql = "UPDATE tbl_info SET status = '$status' WHERE infoid = '$info_id'";
if(!$db->query($sql)){
    die(mysqli_error($db));
}