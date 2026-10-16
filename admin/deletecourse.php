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

    $delteProductFromTable = $db->query("select * FROM tbl_diploma WHERE cid=$NewsID");

    while($row = $delteProductFromTable->fetch_array()) {

        $did = $row['diplomaid'];
        $delteProductFromTable = $db->query("DELETE FROM tbl_subject WHERE diplomaid=$did");
        $delteProductFromTable = $db->query("DELETE FROM tbl_payment WHERE diplomaid=$did");
        $delteProductFromTable = $db->query("DELETE FROM tbl_installment WHERE diplomaid=$did");
        $delteProductFromTable = $db->query("DELETE FROM tbl_diploma WHERE cid=$NewsID");
    }
    
    // Begin images deletion
    $category_result = $db->query("SELECT thumbnail, icon FROM tbl_courses WHERE cid = '$NewsID' ");
    $category_row = $category_result->fetch_array();
    if ($category_row['thumbnail']) {
        @unlink('../images/' . $category_row['thumbnail']);
        @unlink('../images/thumbs/' . $category_row['thumbnail']);
    }
    if ($category_row['icon']) {
        @unlink('../images/nd/category/' . $category_row['icon']);
    }
    // End images deletion

    $delteProductFromTable = $db->query("DELETE FROM tbl_courses WHERE cid=$NewsID");

}
$msg = "Deleted Sucessfully";
header("location: cours.php?msg=$msg");
echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>"; ?>