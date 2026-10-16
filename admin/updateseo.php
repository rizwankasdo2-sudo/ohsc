<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 
 include("classes/connection.php");




$ptitle=$_POST['ptitle'];

$metatitle=$_POST['metatitle'];

$discription=trim($_POST['desc']);

$id=$_POST['hid'];
$robots = $_POST['Robot'];

$seo_query = $db->query("UPDATE tbl_seo SET pagetitle='$ptitle',metatitle='$metatitle',metadiscription='$discription', robots='$robots' WHERE seoid=$id");
$msg="Seo data updated successfully";
if(!$seo_query){
    die(mysqli_error($db));
}
header("location:seo.php?msg=$msg");



?>
