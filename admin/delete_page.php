<?php
session_start();
ob_start();

if($_SESSION['adminid']== "")
{
    header("location:index.php");
    exit();
}

include("classes/connection.php");
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $delteProductFromTable = $db->query("DELETE FROM pages WHERE id='$id'");
}
    $msg = "Page Successfully Deleted";
    echo "<script language='javascript'>window.location='pages.php?msg=$msg'</script>";