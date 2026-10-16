<?php
session_start();
include("classes/connection.php");

if($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
//include "../includes/database.php";
//include "../includes/functions.php";

$id = $_GET['id'];
$banner_img = "";

$banner_img = $_POST['banner_img'];


$sql = "UPDATE form_manage SET banner_img = '$banner_img' WHERE id = $id";

 $u = $db->query($sql);

 if($u){

$_SESSION['message_course'] = "Banner Image Successfully Removed";
echo "<script language='javascript'>window.location='edit_form_manage.php'</script>";
}

?>