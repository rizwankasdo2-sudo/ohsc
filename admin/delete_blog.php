<?php
exit;session_start();
ob_start();

if($_SESSION['adminid']== "")
{
    header("location:index.php");
    exit();
}

include("classes/connection.php");
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $delteProductFromTable = $db->query("DELETE FROM blog WHERE id='$id'");
}
    $msg = "Blog Post Successfully Deleted";
    echo "<script language='javascript'>window.location='blog.php?msg=$msg'</script>";