<?php
session_start();
ob_start();
if ($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
include("classes/connection.php");
if (isset($_GET['id']) && isset($_GET['did'])) {
    $id = $_GET['id'];
    $did = $_GET['did'];
    $db->query("DELETE FROM tbl_modules WHERE id = $id");
    header('location:modules.php?did=' . $did . '&msg=Module deleted successfully.');
    exit();
}
header('location:cours.php');
exit();
