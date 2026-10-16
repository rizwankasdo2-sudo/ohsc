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
$footer_img = "";

$footer_img = $_POST['footer_img'];


$sql = "UPDATE form_manage SET footer_img = '$footer_img' WHERE id = $id";

 $u = $db->query($sql);

 if($u){

$_SESSION['message_course'] = "Footer Image Successfully Removed";
echo "<script language='javascript'>window.location='edit_form_manage.php'</script>";
}

?>