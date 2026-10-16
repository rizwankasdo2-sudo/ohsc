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
    $certificate_row = $db->query("SELECT * FROM certificates WHERE id = $id")->fetch_array();
    if ($certificate_row) {
        unlink('../images/certificates/' . $certificate_row['pic']);
        $db->query("DELETE FROM certificates WHERE id = $id");
        header('location:certificates.php?msg=Certificate deleted successfully.');
        exit();
    }
}
header('location:certificates.php');
exit();
