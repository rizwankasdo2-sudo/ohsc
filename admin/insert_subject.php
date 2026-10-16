<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

    header("location:index.php");

    exit();

}



include("classes/connection.php");

if(isset($_POST['main']) && isset($_POST['overview']) && isset($_POST['content']) && isset($_POST['payment']) && isset($_POST['status']))

{

    $main =   $_POST['main'];

    $did = $_POST['did'];
    $cid = $_POST['cid'];

    $overview = $_POST['overview'];



    $content = $_POST['content'];
    $payment = $_POST['payment'];

    $status  =  $_POST['status'];
    
    $qry = $db->query("insert into tbl_subject(`diplomaid`,`main`,`courseoverview`,`coursecontent`,`paymentoption`,`IStatus`) values($did,'$main','$overview','$content','$payment','$status')");


    echo $msg = "Inserted Successfully";

    header("location: subject.php?did=$did&cid=$cid&msg=$msg");
    echo "<script language='javascript'>window.location='subject.php?did=$did&cid=$cid&msg=$msg'</script>";
    exit();
}


else{

    $msg="you cant pass empty fields";

    header("location: subject.php?did=$did&cid=$cid&msg=$msg");
    echo "<script language='javascript'>window.location='subject.php?did=$did&cid=$cid&msg=$msg'</script>";
    exit();
}



?>