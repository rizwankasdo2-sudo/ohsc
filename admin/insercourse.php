<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['catname'])&& isset($_POST['order'])&& isset($_POST['status']))

{

		  $name =   $_POST['catname'];
		  $order = $_POST['order'];


		  $status  =  $_POST['status'];
$qry = $db->query("insert into tbl_menu(ICatName,IOrder,IStatus) values('$name',$order,$status)");
		 

		   $msg = "Category  Inserted Successfully";

		echo "<script language='javascript'>window.location='category.php?msg=$msg'</script>";

		   }

		 

	



else{

	$msg="Not allowed empty fields";

	

	echo "<script language='javascript'>window.location='category.php?msg=$msg'</script>";

	}



?>