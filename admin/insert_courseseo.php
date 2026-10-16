<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['NewsTitle']) && isset($_POST['Newsdiscription']) && isset($_POST['NewsDetail']))

{
	$cid = filter_var($_POST['cid'], FILTER_SANITIZE_STRING);
	$did = filter_var($_POST['did'], FILTER_SANITIZE_STRING);

		  $NewsTitle =   filter_var($_POST['NewsTitle'], FILTER_SANITIZE_STRING);

		  

		   $Newsdiscription = filter_var($_POST['Newsdiscription'], FILTER_SANITIZE_STRING);

		 

		    $NewsDetail = filter_var($_POST['NewsDetail'], FILTER_SANITIZE_STRING);


		 $qry="insert into tbl_caourseseo(diplomaid,pagetitle,metatitle,metadescription) values($did,'$NewsTitle','$Newsdiscription','$NewsDetail')";

		  $db->query($qry) or die("insert query error").mysqli_error($db);

		  echo $msg = "Inserted Successfully";

		echo "<script language='javascript'>window.location='courseseo.php?did=$did&cid=$cid&msg=$msg'</script>";

		   }

		 

	



else{

	$msg="you cant pass empty fields";

	

	echo "<script language='javascript'>window.location='courseseo.php?did=$did&cid=$cid&msg=$msg'</script>";

	}



?>