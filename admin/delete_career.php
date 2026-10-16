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
    $career_row = $db->query("SELECT * FROM tbl_careers WHERE id = $id")->fetch_array();
    if ($career_row) {
        unlink('../images/hiring_company_logos/' . $career_row['hiring_company_logo_1']); 
        unlink('../images/hiring_company_logos/' . $career_row['hiring_company_logo_2']);
        unlink('../images/hiring_company_logos/' . $career_row['hiring_company_logo_3']);
        $db->query("DELETE FROM tbl_careers WHERE id = $id");
        header('location:careers.php?msg=Career deleted successfully.');
        exit();
    }
}
header('location:careers.php');
exit();
