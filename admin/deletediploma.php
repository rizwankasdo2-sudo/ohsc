<?php

exit;session_start();

ob_start();

if($_SESSION['adminid']== "")

{

    header("location:index.php");

    exit();

}



include("classes/connection.php");





if (isset($_GET['uid']))

{

    $NewsID = $_GET['uid'];
    if(isset($_GET['cid']))
    {
        $cid = $_GET['cid'];

    }
    
    // Begin images deletion
    $diploma_result = $db->query("select * from  tbl_diploma where diplomaid = '$NewsID' ");
    $diploma_row = $diploma_result->fetch_array();
    if ($diploma_row['thumbnail']) {
        @unlink('../images/subject/' . $diploma_row['thumbnail']);
    }
    if ($diploma_row['square_icon']) {
        @unlink('../images/subject/' . $diploma_row['square_icon']);
    }
    // End images deletion

    $delteProductFromTable = $db->query("DELETE FROM tbl_subject WHERE diplomaid=$NewsID");
    $delteProductFromTable = $db->query("DELETE FROM tbl_payment WHERE diplomaid=$NewsID");
    $delteProductFromTable = $db->query("DELETE FROM tbl_installment WHERE diplomaid=$NewsID");
    $delteProductFromTable = $db->query("DELETE FROM tbl_diploma WHERE diplomaid=$NewsID");

}

$msg = "Deleted Sucessfully";
header("location: diploma.php?cid=$cid&msg=$msg");
echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";?>