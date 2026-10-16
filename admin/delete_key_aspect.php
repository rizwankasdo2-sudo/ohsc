<?php
session_start();
ob_start();
if ($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
include("classes/connection.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $ka_row = $db->query("SELECT icon FROM key_aspects WHERE id = $id")->fetch_array();
    if ($ka_row) {
        unlink('../images/key_aspects/' . $ka_row['icon']); 
        $db->query("DELETE FROM key_aspects WHERE id = $id");
        header('location:key_aspects.php?msg=Key aspect deleted successfully.');
        exit();
    }
}
header('location:key_aspects.php');
exit();
