<?php
session_start();
include("classes/connection.php");
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}

$sid = $_GET['sid'];
$cid = $_GET['cid'];
$did = $_GET['did'];

$sql = "UPDATE tbl_subject SET ab='' WHERE subjectid='$sid'";
if(!$db->query($sql)){
    die(mysqli_error($db));
}
$_SESSION['message_course'] = "Awarding Body Thumbnail Successfully Removed";
echo "<script language='javascript'>window.location='edit_subject.php?sid=$sid&cid=$cid&did=$did'</script>";
?>