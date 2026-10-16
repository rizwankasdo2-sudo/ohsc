<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['NewsTitle']) && isset($_POST['max']) && isset($_POST['status']))

{

		$NewsTitle =   $_POST['NewsTitle'];
		$max = $_POST['max'];

		  

		  $status  =  $_POST['status'];

		 $qry="insert into tbl_program(	IProgramName,mexprice,IStatus) values('$NewsTitle','$max','$status')";

		  $db->query($qry) or die("insert query error").mysqli_error($db);

		  $msg = "Inserted Successfully";

		echo "<script language='javascript'>window.location='Tprogram.php?msg=$msg'</script>";

		   }

		 

	



else{

	$msg="you cant pass empty fields";

	

	echo "<script language='javascript'>window.location='Tprogram.php?msg=$msg'</script>";

	}



?>