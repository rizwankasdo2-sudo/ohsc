<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['NewsTitle']) && isset($_POST['max']) && isset($_POST['price']) && isset($_POST['status']))

{

		$NewsTitle =   $_POST['NewsTitle'];
		$max = $_POST['max'];
		$price = $_POST['price'];

		  

		  $status  =  $_POST['status'];

		 $qry= $db->query("insert into tbl_zip(`Startzip`,`Endzip`,`price`,`IStatus`) values('$NewsTitle','$max',$price,'$status')");

		

		  $msg = "Inserted Successfully";

		echo "<script language='javascript'>window.location='zipcode.php?msg=$msg'</script>";

		   }

		 

	



else{

	$msg="you cant pass empty fields";

	

	echo "<script language='javascript'>window.location='zipcode.php?msg=$msg'</script>";

	}



?>