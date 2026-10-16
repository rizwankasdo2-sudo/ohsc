<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

    header("location:index.php");

    exit();

}



include("classes/connection.php");

if(isset($_POST['payment'])&& isset($_POST['order'])&& isset($_POST['status']) && isset($_POST['month']))

{

    $name =   $_POST['payment'];
    $order = $_POST['order'];
    $status  =  $_POST['status'];
    $cid = $_POST['cid'];
    $did = $_POST['did'];
    $month = $_POST['month'];

    $qry = $db->query("insert into tbl_installment(diplomaid,fullpayment,advance,months,IStatus) values($did,'$name',$order,$month,$status)");


    $msg = "Inserted Successfully";

    header("location: installment.php?did=$did&cid=$cid&msg=$msg");
    echo "<script language='javascript'>window.location='installment.php?did=$did&cid=$cid&msg=$msg'</script>";

}







else{

    $msg="Not allowed empty fields";
    header("location: fullpayment.php?did=$did&cid=$cid&msg=$msg");
    echo "<script language='javascript'>window.location='fullpayment.php??did=$did&cid=$cid&msg=$msg'</script>";

}



?>