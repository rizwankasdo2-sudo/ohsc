<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}include("classes/connection.php");

$id = $_GET['id'];
$sql = "DELETE FROM tbl_banner WHERE IBannerID = '$id'";
if(!$db->query($sql)){
    die(mysqli_error($db));
}
header('location:banner.php?msg=2');
?>